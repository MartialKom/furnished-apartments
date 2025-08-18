<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('admin/pages/dashboard');
    }

    public function analytics()
    {
        return view('admin/pages/analytics');
    }

    public function customers()
    {
        return view('admin/pages/customers');
    }

    public function createCustomer()
    {
        return view('admin/pages/customers_create');
    }

    public function viewCustomer($id = null)
    {
        $data['customer_id'] = $id;
        return view('admin/pages/customers_view', $data);
    }

    public function leads()
    {
        return view('admin/pages/leads');
    }

    public function createLead()
    {
        return view('admin/pages/leads_create');
    }

    public function viewLead($id = null)
    {
        $data['lead_id'] = $id;
        return view('admin/pages/leads_view', $data);
    }

    public function projects()
    {
        return view('admin/pages/projects');
    }

    public function createProject()
    {
        return view('admin/pages/projects_create');
    }

    public function viewProject($id = null)
    {
        $data['project_id'] = $id;
        return view('admin/pages/projects_view', $data);
    }

    public function settings()
    {
        return view('admin/pages/settings');
    }

    public function reports()
    {
        return view('admin/pages/reports');
    }
}