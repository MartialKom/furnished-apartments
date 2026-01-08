<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Navigation (1)
            ['nom' => 'Voir le tableau de bord', 'code' => 'view_dashboard', 'description' => 'Accès au tableau de bord principal', 'module' => 'dashboard', 'groupe' => 'Navigation', 'ordre' => 1],

            // Gestion des Appartements (14)
            ['nom' => 'Voir les appartements', 'code' => 'view_appartements', 'description' => 'Consulter la liste des appartements', 'module' => 'appartements', 'groupe' => 'Gestion des Appartements', 'ordre' => 10],
            ['nom' => 'Gérer les appartements', 'code' => 'manage_appartements', 'description' => 'Créer, modifier, supprimer des appartements', 'module' => 'appartements', 'groupe' => 'Gestion des Appartements', 'ordre' => 11],
            ['nom' => 'Voir les réservations', 'code' => 'view_reservations', 'description' => 'Consulter la liste des réservations', 'module' => 'reservations', 'groupe' => 'Gestion des Appartements', 'ordre' => 12],
            ['nom' => 'Gérer les réservations', 'code' => 'manage_reservations', 'description' => 'Créer, modifier, confirmer, annuler des réservations', 'module' => 'reservations', 'groupe' => 'Gestion des Appartements', 'ordre' => 13],
            ['nom' => 'Voir les locataires', 'code' => 'view_locataires', 'description' => 'Consulter la liste des locataires', 'module' => 'locataires', 'groupe' => 'Gestion des Appartements', 'ordre' => 14],
            ['nom' => 'Gérer les locataires', 'code' => 'manage_locataires', 'description' => 'Créer, modifier, supprimer des locataires', 'module' => 'locataires', 'groupe' => 'Gestion des Appartements', 'ordre' => 15],
            ['nom' => 'Voir les paiements', 'code' => 'view_paiements', 'description' => 'Consulter les paiements effectués', 'module' => 'paiements', 'groupe' => 'Gestion des Appartements', 'ordre' => 16],
            ['nom' => 'Gérer les paiements', 'code' => 'manage_paiements', 'description' => 'Enregistrer, modifier des paiements', 'module' => 'paiements', 'groupe' => 'Gestion des Appartements', 'ordre' => 17],
            ['nom' => 'Voir les contrats long terme', 'code' => 'view_contrats', 'description' => 'Consulter les contrats de location longue durée', 'module' => 'contrats', 'groupe' => 'Gestion des Appartements', 'ordre' => 18],
            ['nom' => 'Gérer les contrats long terme', 'code' => 'manage_contrats', 'description' => 'Créer, modifier, terminer des contrats', 'module' => 'contrats', 'groupe' => 'Gestion des Appartements', 'ordre' => 19],
            ['nom' => 'Voir les paiements mensuels', 'code' => 'view_paiements_mensuels', 'description' => 'Consulter les échéances de paiement mensuels', 'module' => 'paiements_mensuels', 'groupe' => 'Gestion des Appartements', 'ordre' => 20],
            ['nom' => 'Gérer les paiements mensuels', 'code' => 'manage_paiements_mensuels', 'description' => 'Enregistrer, envoyer des rappels de paiements mensuels', 'module' => 'paiements_mensuels', 'groupe' => 'Gestion des Appartements', 'ordre' => 21],
            ['nom' => 'Voir les factures d\'eau', 'code' => 'view_factures_eau', 'description' => 'Consulter les factures d\'eau', 'module' => 'factures_eau', 'groupe' => 'Gestion des Appartements', 'ordre' => 22],
            ['nom' => 'Gérer les factures d\'eau', 'code' => 'manage_factures_eau', 'description' => 'Créer, modifier, marquer comme payées les factures d\'eau', 'module' => 'factures_eau', 'groupe' => 'Gestion des Appartements', 'ordre' => 23],

            // Location de Voitures (4)
            ['nom' => 'Voir le parc automobile', 'code' => 'view_voitures', 'description' => 'Consulter la liste des véhicules', 'module' => 'voitures', 'groupe' => 'Location de Voitures', 'ordre' => 30],
            ['nom' => 'Gérer le parc automobile', 'code' => 'manage_voitures', 'description' => 'Ajouter, modifier, supprimer des véhicules', 'module' => 'voitures', 'groupe' => 'Location de Voitures', 'ordre' => 31],
            ['nom' => 'Voir les locations de voitures', 'code' => 'view_locations_voitures', 'description' => 'Consulter les locations de véhicules', 'module' => 'locations_voitures', 'groupe' => 'Location de Voitures', 'ordre' => 32],
            ['nom' => 'Gérer les locations de voitures', 'code' => 'manage_locations_voitures', 'description' => 'Créer, démarrer, terminer des locations de véhicules', 'module' => 'locations_voitures', 'groupe' => 'Location de Voitures', 'ordre' => 33],

            // Gestion du Stock (11)
            ['nom' => 'Voir le tableau de bord stock', 'code' => 'view_stock_dashboard', 'description' => 'Accès au tableau de bord du stock', 'module' => 'stock', 'groupe' => 'Gestion du Stock', 'ordre' => 40],
            ['nom' => 'Voir les produits', 'code' => 'view_produits', 'description' => 'Consulter le catalogue des produits', 'module' => 'produits', 'groupe' => 'Gestion du Stock', 'ordre' => 41],
            ['nom' => 'Gérer les produits', 'code' => 'manage_produits', 'description' => 'Créer, modifier, supprimer des produits', 'module' => 'produits', 'groupe' => 'Gestion du Stock', 'ordre' => 42],
            ['nom' => 'Voir les approvisionnements', 'code' => 'view_approvisionnements', 'description' => 'Consulter les entrées de stock', 'module' => 'approvisionnements', 'groupe' => 'Gestion du Stock', 'ordre' => 43],
            ['nom' => 'Gérer les approvisionnements', 'code' => 'manage_approvisionnements', 'description' => 'Enregistrer des approvisionnements', 'module' => 'approvisionnements', 'groupe' => 'Gestion du Stock', 'ordre' => 44],
            ['nom' => 'Voir les sorties de stock', 'code' => 'view_sorties', 'description' => 'Consulter les sorties de stock', 'module' => 'sorties', 'groupe' => 'Gestion du Stock', 'ordre' => 45],
            ['nom' => 'Gérer les sorties de stock', 'code' => 'manage_sorties', 'description' => 'Enregistrer des sorties de stock', 'module' => 'sorties', 'groupe' => 'Gestion du Stock', 'ordre' => 46],
            ['nom' => 'Voir les inventaires', 'code' => 'view_inventaires', 'description' => 'Consulter les inventaires physiques', 'module' => 'inventaires', 'groupe' => 'Gestion du Stock', 'ordre' => 47],
            ['nom' => 'Gérer les inventaires', 'code' => 'manage_inventaires', 'description' => 'Créer, valider des inventaires', 'module' => 'inventaires', 'groupe' => 'Gestion du Stock', 'ordre' => 48],
            ['nom' => 'Voir les rapports stock', 'code' => 'view_rapports_stock', 'description' => 'Consulter les rapports de stock', 'module' => 'rapports_stock', 'groupe' => 'Gestion du Stock', 'ordre' => 49],

            // Rapports & Analyse (5)
            ['nom' => 'Voir les rapports', 'code' => 'view_rapports', 'description' => 'Consulter les rapports généraux', 'module' => 'rapports', 'groupe' => 'Rapports & Analyse', 'ordre' => 50],
            ['nom' => 'Gérer les rapports', 'code' => 'manage_rapports', 'description' => 'Créer, modifier, supprimer des rapports', 'module' => 'rapports', 'groupe' => 'Rapports & Analyse', 'ordre' => 51],
            ['nom' => 'Voir les analytics', 'code' => 'view_analytics', 'description' => 'Accès aux statistiques et analyses avancées', 'module' => 'analytics', 'groupe' => 'Rapports & Analyse', 'ordre' => 52],
            ['nom' => 'Voir les contrats de location', 'code' => 'view_contrats_location', 'description' => 'Consulter les contrats de location générés', 'module' => 'contrats_location', 'groupe' => 'Rapports & Analyse', 'ordre' => 53],
            ['nom' => 'Gérer les contrats de location', 'code' => 'manage_contrats_location', 'description' => 'Générer et télécharger des contrats de location', 'module' => 'contrats_location', 'groupe' => 'Rapports & Analyse', 'ordre' => 54],

            // Système (6)
            ['nom' => 'Voir les utilisateurs', 'code' => 'view_utilisateurs', 'description' => 'Consulter la liste des utilisateurs', 'module' => 'utilisateurs', 'groupe' => 'Système', 'ordre' => 60],
            ['nom' => 'Gérer les utilisateurs', 'code' => 'manage_utilisateurs', 'description' => 'Créer, modifier, supprimer des utilisateurs', 'module' => 'utilisateurs', 'groupe' => 'Système', 'ordre' => 61],
            ['nom' => 'Voir les rôles', 'code' => 'view_roles', 'description' => 'Consulter la liste des rôles et permissions', 'module' => 'roles', 'groupe' => 'Système', 'ordre' => 62],
            ['nom' => 'Gérer les rôles', 'code' => 'manage_roles', 'description' => 'Créer, modifier, supprimer des rôles et attribuer des permissions', 'module' => 'roles', 'groupe' => 'Système', 'ordre' => 63],
            ['nom' => 'Voir les paramètres', 'code' => 'view_parametres', 'description' => 'Consulter les paramètres système', 'module' => 'parametres', 'groupe' => 'Système', 'ordre' => 64],
            ['nom' => 'Gérer les paramètres', 'code' => 'manage_parametres', 'description' => 'Modifier les paramètres système', 'module' => 'parametres', 'groupe' => 'Système', 'ordre' => 65],
        ];

        foreach ($permissions as $permission) {
            // Check if permission already exists
            $existing = $this->db->table('permissions')
                ->where('code', $permission['code'])
                ->get()
                ->getRowArray();

            if (!$existing) {
                $this->db->table('permissions')->insert($permission);
                echo "✓ Permission '{$permission['nom']}' créée.\n";
            } else {
                echo "- Permission '{$permission['nom']}' existe déjà.\n";
            }
        }

        echo "\n✓ Total: " . count($permissions) . " permissions\n";
    }
}
