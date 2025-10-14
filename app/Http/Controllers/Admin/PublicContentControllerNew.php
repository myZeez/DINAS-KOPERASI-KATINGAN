<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublicContentController extends Controller
{
    public function index()
    {
        $data = [
            'totalCarousels' => 3,
            'activeCarousels' => 2,
            'totalServices' => 6,
            'activeServices' => 5,
            'totalViews' => 1234,
            'monthlyGrowth' => 15.5
        ];

        return view('admin.public-content.index', $data);
    }
}
