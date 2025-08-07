<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class HomeController extends BaseController
{
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
        return view('frontend/pages/apartments');
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