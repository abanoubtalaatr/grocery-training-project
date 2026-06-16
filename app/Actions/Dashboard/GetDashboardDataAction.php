<?php


namespace App\Actions\Dashboard;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GetDashboardDataAction
{
    public function execute(User $user): array
    {
        return [
            'overview' => $this->getOverview($user),
            'shopping_insights' => $this->getShoppingInsights($user),
            'category_distribution' => $this->getCategoryDistribution($user),
            'recent_orders' => $this->getRecentOrders($user),
            'top_purchases' => $this->getTopPurchases($user),
        ];
    }

    private function getOverview(User $user): array
    {
        $activeOrder = Order::where('user_id', $user->id)
            ->whereNotIn('status', ['cancelled', 'delivered'])
            ->orderBy('created_at', 'desc')
            ->first();

        $cart = $user->activeCart()->with('items')->first();
        if ($cart) {
            $cart->calculateTotals();
        }

        $upcoming = Order::where('user_id', $user->id)
            ->whereIn('status', ['placed', 'processing', 'shipping', 'out_for_delivery'])
            ->whereNotNull('estimated_delivery_time')
            ->orderBy('estimated_delivery_time', 'asc')
            ->first();

        return [
            'tracking_order' => $activeOrder ? [
                'id' => $activeOrder->id,
                'order_number' => $activeOrder->order_number,
                'status' => $activeOrder->status,
                'status_description' => $activeOrder->status_description,
                'status_position' => $activeOrder->status_position,
            ] : null,
            'loyalty_points' => (int) ($user->loyalty_points ?? 0),
            'store_credits' => (float) ($user->store_credits ?? 0),
            'current_cart' => [
                'items_count' => $cart ? $cart->items->sum('quantity') : 0,
                'total' => $cart ? (float) $cart->total : 0.0,
                'last_updated' => $cart?->updated_at,
            ],
            'upcoming_delivery' => $upcoming ? [
                'order_id' => $upcoming->id,
                'order_number' => $upcoming->order_number,
                'date' => $upcoming->estimated_delivery_time?->format('Y-m-d'),
                'time' => $upcoming->estimated_delivery_time?->format('H:i'),
                'estimated_delivery_time' => $upcoming->estimated_delivery_time,
            ] : null,
        ];
    }

    private function getShoppingInsights(User $user): array
    {
        $now = Carbon::now();
        $ordersThisMonth = Order::where('user_id', $user->id)
            ->whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->where('status', '!=', 'cancelled')
            ->get();

        $monthlySpend = $ordersThisMonth->sum('total');
        $ordersCount = $ordersThisMonth->count();

        // حساب فترات الأيام الفاصلة بين الطلبات باحترافية وبدون تعقيد
        $avgDays = 0;
        if ($ordersCount > 1) {
            $dates = $ordersThisMonth->pluck('created_at')->sort()->values();
            $diffs = [];
            for ($i = 1; $i < $dates->count(); $i++) {
                $diffs[] = $dates[$i]->diffInDays($dates[$i - 1]);
            }
            $avgDays = round(array_sum($diffs) / count($diffs), 1);
        }

        $orderSavings = Order::where('user_id', $user->id)->where('status', '!=', 'cancelled')->sum('discount');
        
        // حساب توفير الوجبات مباشرة من خلال قاعدة البيانات (أسرع وأخف للذاكرة من الـ Loops)
        $mealSavings = OrderItem::whereHas('order', fn($q) => $q->where('user_id', $user->id)->where('status', '!=', 'cancelled'))
            ->whereHas('meal', fn($q) => $q->whereNotNull('discount_price'))
            ->join('meals', 'order_items.meal_id', '=', 'meals.id')
            ->sum(DB::raw('(meals.price - meals.discount_price) * order_items.quantity'));

        return [
            'monthly_spend' => (float) $monthlySpend,
            'orders_this_month' => [
                'count' => $ordersCount,
                'average_days_between_orders' => $avgDays,
            ],
            'total_savings' => (float) ($orderSavings + $mealSavings),
            'average_order_value' => $ordersCount > 0 ? round($monthlySpend / $ordersCount, 2) : 0.0,
        ];
    }

    private function getCategoryDistribution(User $user): array
    {
        // استعلام SQL مباشر ومجمع لحساب النسب المئوية بدلاً من عمل Foreach على آلاف السطور يدوياً
        $totals = OrderItem::whereHas('order', fn($q) => $q->where('user_id', $user->id)->where('status', '!=', 'cancelled'))
            ->join('meals', 'order_items.meal_id', '=', 'meals.id')
            ->join('categories', 'meals.category_id', '=', 'categories.id')
            ->select('categories.id', 'categories.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('categories.id', 'categories.name')
            ->get();

        $totalItems = $totals->sum('total_quantity');

        return $totals->map(fn($item) => [
            'category_id' => $item->id,
            'category_name' => $item->name,
            'total_quantity' => (int) $item->total_quantity,
            'percentage' => $totalItems > 0 ? round(($item->total_quantity / $totalItems) * 100, 1) : 0,
        ])->sortByDesc('percentage')->values()->toArray();
    }

    private function getRecentOrders(User $user): array
    {
        return Order::where('user_id', $user->id)
            ->withCount([
                'items as items_count' => fn($q) => $q->select(DB::raw('SUM(quantity)'))
            ])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($order) => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'status_description' => $order->status_description,
                'total' => (float) $order->total,
                'created_at' => $order->created_at,
                'items_count' => (int) $order->items_count,
            ])->toArray();
    }

    private function getTopPurchases(User $user): array
    {
        return OrderItem::whereHas('order', fn($q) => $q->where('user_id', $user->id)->where('status', '!=', 'cancelled'))
            ->join('meals', 'order_items.meal_id', '=', 'meals.id')
            ->leftJoin('categories', 'meals.category_id', '=', 'categories.id')
            ->select(
                'order_items.meal_id',
                'meals.title',
                'meals.image_url',
                'categories.id as cat_id',
                'categories.name as cat_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_spent')
            )
            ->groupBy('order_items.meal_id', 'meals.title', 'meals.image_url', 'categories.id', 'categories.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'meal_id' => $item->meal_id,
                'title' => $item->title,
                'image_url' => $item->image_url,
                'category' => $item->cat_id ? [
                    'id' => $item->cat_id,
                    'name' => $item->cat_name,
                ] : null,
                'total_quantity_purchased' => (int) $item->total_quantity,
                'total_spent' => (float) $item->total_spent,
            ])->toArray();
    }
}