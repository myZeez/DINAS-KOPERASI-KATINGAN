<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        // Sample data untuk complaints
        $totalComplaints = 25;
        $pendingComplaints = 6;
        $resolvedComplaints = 19;
        $resolvedPercentage = round(($resolvedComplaints / $totalComplaints) * 100);

        return view('admin.complaints.index', compact(
            'totalComplaints',
            'pendingComplaints',
            'resolvedPercentage'
        ));
    }
}
