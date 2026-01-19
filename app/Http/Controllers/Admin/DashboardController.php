<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Job;
use App\Models\CompanyProfile;
use App\Models\AppliedJob;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        // Get statistics
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        $totalCompanies = CompanyProfile::count();
        $totalJobs = Job::count();
        // Jobs table doesn't have is_active column, so just count all jobs
        $activeJobs = $totalJobs;
        
        $totalApplications = AppliedJob::count();
        $pendingApplications = AppliedJob::where('status', 'pending')->count();
        
        // Get recent data
        $recentUsers = User::latest()->take(5)->get();
        $recentJobs = Job::with('user')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersThisMonth',
            'totalCompanies',
            'totalJobs',
            'activeJobs',
            'totalApplications',
            'pendingApplications',
            'recentUsers',
            'recentJobs'
        ));
    }
}
