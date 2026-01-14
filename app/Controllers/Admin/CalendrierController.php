<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReservationModel;
use App\Models\ContratLocataireModel;
use App\Models\AppartementModel;

class CalendrierController extends BaseController
{
    protected $reservationModel;
    protected $contratModel;
    protected $appartementModel;

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
        $this->contratModel = new ContratLocataireModel();
        $this->appartementModel = new AppartementModel();
    }

    /**
     * Afficher le calendrier des occupations
     */
    public function index()
    {
        $data = [
            'title' => 'Calendrier des Occupations',
            'page_title' => 'Calendrier',
            'appartements' => $this->appartementModel->findAll()
        ];

        return view('admin/pages/calendrier/index', $data);
    }

    /**
     * Récupérer les événements pour le calendrier (AJAX)
     */
    public function getEvenements()
    {
        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');
        $appartementId = $this->request->getGet('appartement_id');

        $evenements = [];

        // Récupérer les réservations
        $reservationQuery = $this->reservationModel
            ->select('reservations.*, appartements.adresse as appartement_adresse,
                     COALESCE(locataires.nom, reservations.client_nom) as client_nom')
            ->join('appartements', 'appartements.id = reservations.appartement_id')
            ->join('locataires', 'locataires.id = reservations.locataire_id', 'left')
            ->where('reservations.statut !=', 'annulee');

        if ($start) {
            $reservationQuery->where('reservations.date_fin >=', $start);
        }
        if ($end) {
            $reservationQuery->where('reservations.date_debut <=', $end);
        }
        if ($appartementId) {
            $reservationQuery->where('reservations.appartement_id', $appartementId);
        }

        $reservations = $reservationQuery->findAll();

        foreach ($reservations as $reservation) {
            $couleur = $this->getCouleurStatut($reservation['statut'], 'reservation');

            $evenements[] = [
                'id' => 'res_' . $reservation['id'],
                'title' => '🏠 ' . ($reservation['client_nom'] ?? 'Client'),
                'start' => $reservation['date_debut'],
                'end' => date('Y-m-d', strtotime($reservation['date_fin'] . ' +1 day')), // FullCalendar end is exclusive
                'backgroundColor' => $couleur['bg'],
                'borderColor' => $couleur['border'],
                'textColor' => '#fff',
                'extendedProps' => [
                    'type' => 'reservation',
                    'statut' => $reservation['statut'],
                    'appartement' => $reservation['appartement_adresse'],
                    'client' => $reservation['client_nom'],
                    'montant' => $reservation['montant_total'],
                    'id_original' => $reservation['id']
                ]
            ];
        }

        // Récupérer les contrats longue durée
        $contratQuery = $this->contratModel
            ->select('contrats_locataires.*, appartements.adresse as appartement_adresse,
                     locataires.nom as locataire_nom')
            ->join('appartements', 'appartements.id = contrats_locataires.appartement_id')
            ->join('locataires', 'locataires.id = contrats_locataires.locataire_id')
            ->where('contrats_locataires.statut', 'actif');

        if ($start) {
            $contratQuery->groupStart()
                ->where('contrats_locataires.date_fin IS NULL')
                ->orWhere('contrats_locataires.date_fin >=', $start)
            ->groupEnd();
        }
        if ($end) {
            $contratQuery->where('contrats_locataires.date_debut <=', $end);
        }
        if ($appartementId) {
            $contratQuery->where('contrats_locataires.appartement_id', $appartementId);
        }

        $contrats = $contratQuery->findAll();

        foreach ($contrats as $contrat) {
            $dateFin = $contrat['date_fin'] ?? null;

            // Si pas de date de fin, on affiche jusqu'à 1 an après la date de début
            if (!$dateFin) {
                $dateFin = date('Y-m-d', strtotime($contrat['date_debut'] . ' +1 year'));
            }

            $couleur = $this->getCouleurStatut($contrat['statut'], 'contrat');

            $evenements[] = [
                'id' => 'cont_' . $contrat['id'],
                'title' => '📋 ' . ($contrat['locataire_nom'] ?? 'Locataire'),
                'start' => $contrat['date_debut'],
                'end' => date('Y-m-d', strtotime($dateFin . ' +1 day')),
                'backgroundColor' => $couleur['bg'],
                'borderColor' => $couleur['border'],
                'textColor' => '#fff',
                'extendedProps' => [
                    'type' => 'contrat',
                    'statut' => $contrat['statut'],
                    'appartement' => $contrat['appartement_adresse'],
                    'locataire' => $contrat['locataire_nom'],
                    'loyer' => $contrat['loyer_mensuel'],
                    'date_fin_contrat' => $contrat['date_fin'],
                    'id_original' => $contrat['id']
                ]
            ];
        }

        return $this->response->setJSON($evenements);
    }

    /**
     * Obtenir les détails d'un événement (AJAX)
     */
    public function getDetailsEvenement()
    {
        $eventId = $this->request->getGet('event_id');

        if (!$eventId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID événement manquant'
            ]);
        }

        // Déterminer le type et l'ID
        $parts = explode('_', $eventId);
        $type = $parts[0];
        $id = $parts[1] ?? null;

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID invalide'
            ]);
        }

        if ($type === 'res') {
            // Réservation
            $reservation = $this->reservationModel
                ->select('reservations.*, appartements.adresse as appartement_adresse,
                         COALESCE(locataires.nom, reservations.client_nom) as client_nom,
                         COALESCE(locataires.email, reservations.client_email) as client_email,
                         COALESCE(locataires.telephone, reservations.client_telephone) as client_telephone')
                ->join('appartements', 'appartements.id = reservations.appartement_id')
                ->join('locataires', 'locataires.id = reservations.locataire_id', 'left')
                ->where('reservations.id', $id)
                ->first();

            if (!$reservation) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Réservation non trouvée'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'type' => 'reservation',
                'data' => $reservation
            ]);
        } elseif ($type === 'cont') {
            // Contrat
            $contrat = $this->contratModel
                ->select('contrats_locataires.*, appartements.adresse as appartement_adresse,
                         locataires.nom as locataire_nom, locataires.email as locataire_email,
                         locataires.telephone as locataire_telephone')
                ->join('appartements', 'appartements.id = contrats_locataires.appartement_id')
                ->join('locataires', 'locataires.id = contrats_locataires.locataire_id')
                ->where('contrats_locataires.id', $id)
                ->first();

            if (!$contrat) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Contrat non trouvé'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'type' => 'contrat',
                'data' => $contrat
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Type d\'événement non reconnu'
        ]);
    }

    /**
     * Obtenir les statistiques d'occupation
     */
    public function getStatistiques()
    {
        $mois = $this->request->getGet('mois') ?? date('Y-m');
        $debutMois = $mois . '-01';
        $finMois = date('Y-m-t', strtotime($debutMois));

        // Nombre total d'appartements
        $totalAppartements = $this->appartementModel->countAllResults();

        // Appartements occupés par réservations
        $reservationsActives = $this->reservationModel
            ->where('statut', 'en_cours')
            ->orWhere('statut', 'confirmee')
            ->where('date_debut <=', $finMois)
            ->where('date_fin >=', $debutMois)
            ->countAllResults();

        // Appartements occupés par contrats
        $contratsActifs = $this->contratModel
            ->where('statut', 'actif')
            ->where('date_debut <=', $finMois)
            ->groupStart()
                ->where('date_fin IS NULL')
                ->orWhere('date_fin >=', $debutMois)
            ->groupEnd()
            ->countAllResults();

        $totalOccupes = $reservationsActives + $contratsActifs;
        $tauxOccupation = $totalAppartements > 0 ? round(($totalOccupes / $totalAppartements) * 100, 2) : 0;

        return $this->response->setJSON([
            'success' => true,
            'stats' => [
                'total_appartements' => $totalAppartements,
                'reservations_actives' => $reservationsActives,
                'contrats_actifs' => $contratsActifs,
                'total_occupes' => $totalOccupes,
                'disponibles' => $totalAppartements - $totalOccupes,
                'taux_occupation' => $tauxOccupation
            ]
        ]);
    }

    /**
     * Obtenir les couleurs selon le statut
     */
    private function getCouleurStatut($statut, $type)
    {
        if ($type === 'reservation') {
            switch ($statut) {
                case 'confirmee':
                    return ['bg' => '#28a745', 'border' => '#1e7e34'];
                case 'en_cours':
                    return ['bg' => '#17a2b8', 'border' => '#117a8b'];
                case 'en_attente':
                    return ['bg' => '#ffc107', 'border' => '#d39e00'];
                case 'terminee':
                    return ['bg' => '#6c757d', 'border' => '#545b62'];
                default:
                    return ['bg' => '#007bff', 'border' => '#0056b3'];
            }
        } else {
            // Contrat
            switch ($statut) {
                case 'actif':
                    return ['bg' => '#d29751', 'border' => '#b8834a'];
                case 'suspendu':
                    return ['bg' => '#ff6b6b', 'border' => '#ee5a52'];
                case 'termine':
                    return ['bg' => '#95a5a6', 'border' => '#7f8c8d'];
                default:
                    return ['bg' => '#d29751', 'border' => '#b8834a'];
            }
        }
    }
}
