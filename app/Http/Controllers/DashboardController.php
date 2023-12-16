<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $data = [
            "breadcrumbs" => [
                "parent" => "Dashboard",
                "child" => "Home"
            ],
            "title" => "Home"
        ];
        return view('admin.dashboard', $data);
    }
}
