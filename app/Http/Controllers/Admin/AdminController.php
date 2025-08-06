<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
 use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with order statistics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Total Orders (All)
        $totalOrders = Order::count();

        // Monthly Completed Orders and Revenue (Only Delivered Orders)
        $monthlyOrders = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'delivered')
            ->count();

        $monthlyRevenue = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'delivered')
            ->sum('total');

        // Total Revenue from all orders
        $totalRevenue = Order::where('status', 'delivered')->sum('total');

        // Pending and Cancelled Orders
        $pendingOrders = Order::where('status', 'pending')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();    

        // Completed Revenue
        $completedRevenue = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)->where('status', 'delivered')->sum('total');

        // Pending Revenue
        $pendingRevenue = Order::where('status', 'pending')->sum('total');

        return view('admin.frontend.dashboard', compact(
            'totalOrders',
            'monthlyOrders',
            'monthlyRevenue',
            'pendingOrders',
            'cancelledOrders',
            'completedRevenue',
            'pendingRevenue',
            'totalRevenue'
        ));
    }
   

public function getRevenueData(Request $request)
{
    $granularity = $request->get('granularity', 'monthly');

    $query = Order::where('status', 'delivered');

    $labels = [];
    $data = [];

    if ($granularity === 'monthly') {
        $monthlyData = $query
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as period, SUM(total) as revenue')
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        foreach ($monthlyData as $row) {
            $labels[] = Carbon::createFromFormat('Y-m', $row->period)->format('M Y');
            $data[] = $row->revenue ?? 0; // Ensure we have a value
        }
    } elseif ($granularity === 'yearly') {
        $yearlyData = $query
            ->selectRaw('YEAR(created_at) as period, SUM(total) as revenue')
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        foreach ($yearlyData as $row) {
            $labels[] = $row->period;
            $data[] = $row->revenue ?? 0;
        }
    } elseif ($granularity === 'daily') {
        $dailyData = $query
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as period, SUM(total) as revenue')
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        foreach ($dailyData as $row) {
            $labels[] = Carbon::parse($row->period)->format('d M');
            $data[] = $row->revenue ?? 0;
        }
    }

    // If no data, return empty arrays
    if (empty($labels)) {
        $labels = [];
        $data = [];
    }

    return response()->json([
        'labels' => $labels,
        'data' => $data,
    ]);
}
}
