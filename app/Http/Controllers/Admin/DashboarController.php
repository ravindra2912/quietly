<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\{Plan, PlanPurchase, ContactUs};

class DashboarController extends Controller
{

    /**
     * Display the admin dashboard.
     */
    public function index(Request $request): View
    {
        // 1. Total Users (role 'user')
        $userCount = User::where('role', 'user')->count();

        // 2. Total Free Users (role 'user' and no active plan)
        $freeUserCount = User::where('role', 'user')
            ->whereDoesntHave('planPurchases', function ($query) {
                $query->where('status', 'active')
                    ->where('expired_at', '>', now());
            })->count();

        // 3. Total Plans
        $totalPlanCount = Plan::count();

        // 4. Active Subscriptions
        $activePlanCount = PlanPurchase::where('status', 'active')->count();

        // 5. Total Revenue
        $totalRevenue = PlanPurchase::sum('price');

        // 6. Pending Inquiries
        $pendingInquiriesCount = ContactUs::where('status', 'pending')->count();

        // Chart 1: Monthly Revenue (Sum of price)
        $monthlyRevenue = PlanPurchase::select(
            DB::raw('sum(price) as total'),
            DB::raw('MONTH(start_at) as month')
        )
            ->whereYear('start_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Chart 2: Monthly Plan Purchases (Count of id)
        $monthlyPurchases = PlanPurchase::select(
            DB::raw('count(id) as count'),
            DB::raw('MONTH(start_at) as month')
        )
            ->whereYear('start_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Prepare data for Charts
        $revenueChartData = [];
        $purchaseChartData = [];
        $chartLabels = [];

        for ($i = 1; $i <= 12; $i++) {
            $chartLabels[] = date("M", mktime(0, 0, 0, $i, 1));
            $revenueChartData[] = $monthlyRevenue[$i] ?? 0;
            $purchaseChartData[] = $monthlyPurchases[$i] ?? 0;
        }

        return view('admin.dashboard', compact(
            'userCount',
            'freeUserCount',
            'totalPlanCount',
            'activePlanCount',
            'totalRevenue',
            'pendingInquiriesCount',
            'chartLabels',
            'revenueChartData',
            'purchaseChartData'
        ));
    }
}
