<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Models\AppartementModel;

class HomeController extends BaseController
{
    protected $appartementModel;

    public function __construct()
    {
        $this->appartementModel = new AppartementModel();
    }

    public function index()
    {
        return view('frontend/pages/home');
    }

    public function about()
    {
        return view('frontend/pages/about');
    }

    public function apartments()
    {
        // Récupérer uniquement les appartements disponibles et meublés
        $appartements = $this->appartementModel
            ->where('statut', 'disponible')
            ->where('type', 'meuble')
            ->orderBy('tarifs', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Nos Appartements Meublés',
            'appartements' => $appartements,
        ];

        return view('frontend/pages/apartments', $data);
    }

    public function apartmentDetails($id = null)
    {
        $data['apartment_id'] = $id;
        return view('frontend/pages/apartment_details', $data);
    }

    public function services()
    {
        return view('frontend/pages/services');
    }

    public function gallery()
    {
        return view('frontend/pages/gallery');
    }

    public function pricing()
    {
        return view('frontend/pages/pricing');
    }

    public function team()
    {
        return view('frontend/pages/team');
    }

    public function blog()
    {
        return view('frontend/pages/blog');
    }

    public function blogDetails($id = null)
    {
        $data['blog_id'] = $id;
        return view('frontend/pages/blog_details', $data);
    }

    public function contact()
    {
        return view('frontend/pages/contact');
    }
}