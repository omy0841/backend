<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    protected function ensureAdmin()
    {
        abort_unless(auth()->user()->is_admin, 403);
    }

    public function index(Request $request)
    {
        $this->ensureAdmin();

        $query = $this->buildOrderQuery($request);

        $orders = $query->paginate(15)->withQueryString();

        $summary = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'approved' => Order::where('status', 'approved')->count(),
            'rejected' => Order::where('status', 'rejected')->count(),
        ];

        $activeFilterCount = collect(['q', 'status', 'from', 'to'])->filter(fn ($key) => $request->filled($key))->count();

        return view('admin.orders.index', compact('orders', 'summary', 'activeFilterCount'));
    }

    public function export(Request $request)
    {
        $this->ensureAdmin();

        $query = $this->buildOrderQuery($request);
        $orders = $query->get();

        $filename = 'orders_export_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order ID', 'Date', 'Customer', 'Hotel', 'Location', 'Phone', 'Email', 'Status', 'Total Amount', 'Items']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->contact_name,
                    $order->hotel_name,
                    $order->delivery_location,
                    $order->phone,
                    $order->email,
                    ucfirst($order->status),
                    number_format($order->total_amount, 2),
                    implode('; ', $order->items ?? []),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function buildOrderQuery(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('q')) {
            $query->where(function ($query) use ($request) {
                $query->where('contact_name', 'like', '%' . $request->q . '%')
                    ->orWhere('hotel_name', 'like', '%' . $request->q . '%')
                    ->orWhere('delivery_location', 'like', '%' . $request->q . '%')
                    ->orWhere('phone', 'like', '%' . $request->q . '%')
                    ->orWhere('email', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        return $query;
    }

    public function approve(Order $order)
    {
        $this->ensureAdmin();

        $order->update(['status' => 'approved']);

        return back()->with('status', 'Order approved successfully.');
    }

    public function reject(Order $order)
    {
        $this->ensureAdmin();

        $order->update(['status' => 'rejected']);

        return back()->with('status', 'Order rejected.');
    }
}
