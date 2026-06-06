<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\StockTransaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function stats()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // 1. Customers
        $totalCustomers = Customer::count();
        $customersLastMonth = Customer::where('created_at', '<=', $endOfLastMonth)->count();
        
        $customerDiff = $totalCustomers - $customersLastMonth;
        $customerGrowth = $customersLastMonth > 0 ? round(($customerDiff / $customersLastMonth) * 100) : 100;

        $retailCount = Customer::where('type', 'retail')->count();
        $wholesaleCount = Customer::where('type', 'wholesale')->count();
        
        $retailPercentage = $totalCustomers > 0 ? round(($retailCount / $totalCustomers) * 100) : 0;
        $wholesalePercentage = $totalCustomers > 0 ? round(($wholesaleCount / $totalCustomers) * 100) : 0;

        // 2. Incoming Stock (formerly Revenue/Finance)
        $currentIncoming = StockTransaction::where('type', 'in')
            ->whereBetween('stock_transactions.created_at', [$startOfMonth, $now])
            ->sum('quantity');

        $lastMonthIncoming = StockTransaction::where('type', 'in')
            ->whereBetween('stock_transactions.created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('quantity');

        $incomingDiff = $currentIncoming - $lastMonthIncoming;
        $incomingGrowth = $lastMonthIncoming > 0 ? round(($incomingDiff / $lastMonthIncoming) * 100) : 100;

        // 3. Outgoing Stock (formerly Orders)
        $currentOutgoing = StockTransaction::where('type', 'out')
            ->whereBetween('stock_transactions.created_at', [$startOfMonth, $now])
            ->sum('quantity');

        $lastMonthOutgoing = StockTransaction::where('type', 'out')
            ->whereBetween('stock_transactions.created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('quantity');

        $outgoingDiff = $currentOutgoing - $lastMonthOutgoing;
        $outgoingGrowth = $lastMonthOutgoing > 0 ? round(($outgoingDiff / $lastMonthOutgoing) * 100) : 100;

        // 4. Category Sales Summary
        $categories = \DB::table('categories')->get();
        $categorySales = [];

        foreach ($categories as $cat) {
            $items = \DB::table('items')->where('category_id', $cat->id)->get();
            $itemIds = $items->pluck('id')->toArray();
            
            $modelCount = $items->count();
            $totalStock = $items->sum('stock');
            
            $transactions = \DB::table('stock_transactions')
                ->whereIn('item_id', $itemIds)
                ->where('type', 'out')
                ->join('items', 'stock_transactions.item_id', '=', 'items.id')
                ->select('stock_transactions.quantity', 'items.price_usd')
                ->get();
                
            $totalSold = $transactions->sum('quantity');
            $totalRevenueUsd = $transactions->sum(function($t) {
                return $t->quantity * $t->price_usd;
            });

            $categorySales[] = [
                'category_id' => $cat->id,
                'category_name' => $cat->name,
                'model_count' => $modelCount,
                'total_stock' => $totalStock,
                'total_sold' => $totalSold,
                'total_revenue_usd' => $totalRevenueUsd
            ];
        }

        return response()->json([
            'customers' => [
                'total' => $totalCustomers,
                'last_month' => $customersLastMonth,
                'growth' => $customerGrowth,
                'retail_percentage' => $retailPercentage,
                'wholesale_percentage' => $wholesalePercentage,
            ],
            'incoming' => [
                'total' => $currentIncoming,
                'last_month' => $lastMonthIncoming,
                'growth' => $incomingGrowth,
            ],
            'outgoing' => [
                'total' => $currentOutgoing,
                'last_month' => $lastMonthOutgoing,
                'growth' => $outgoingGrowth,
            ],
            'category_sales' => $categorySales
        ]);
    }
}
