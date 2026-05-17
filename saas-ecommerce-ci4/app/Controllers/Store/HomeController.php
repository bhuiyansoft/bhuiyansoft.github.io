<?php

namespace App\Controllers\Store;

use App\Controllers\BaseController;
use App\Models\PlanModel;

class HomeController extends BaseController
{
    public function index()
    {
        $planModel = new PlanModel();
        return view('store/home', [
            'title' => 'SaaSCommerce - Build Your Online Store',
            'plans' => $planModel->getActivePlans(),
        ]);
    }

    public function pricing()
    {
        $planModel = new PlanModel();
        $plans = $planModel->getActivePlans();
        foreach ($plans as &$plan) {
            $plan = $planModel->decodeFeatures($plan);
        }

        return view('store/pricing', [
            'title' => 'Pricing Plans',
            'plans' => $plans,
        ]);
    }

    public function contact()
    {
        return view('store/contact', ['title' => 'Contact Us']);
    }
}
