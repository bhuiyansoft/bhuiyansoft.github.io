<?php

namespace App\Controllers;

use App\Models\PlanModel;
use CodeIgniter\Controller;

class HomeController extends Controller
{
    public function index(): string
    {
        $planModel = new PlanModel();
        return view('home/index', [
            'plans' => $planModel->getActivePlans(),
        ]);
    }

    public function pricing(): string
    {
        $planModel = new PlanModel();
        return view('home/pricing', [
            'plans' => $planModel->getActivePlans(),
        ]);
    }

    public function features(): string
    {
        return view('home/features');
    }
}
