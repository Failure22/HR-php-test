<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {
        return view('orders.list');
    }

    public function edit(Request $request, int $id)
    {
        return view('orders.edit', ['order' => Order::with(['products.product'])->find($id)]);
    }
}
