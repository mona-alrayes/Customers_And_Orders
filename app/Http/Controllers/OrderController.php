<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrdersRequest;
use App\Http\Requests\UpdateOrdersRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Services\OrderService;

class OrdersController extends Controller
{
    protected OrderService $OrderService;

    public function __construct(OrderService $OrderService){
        $this->OrderService = $OrderService;
    }

    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
      $orders = $this->OrderService->getOrders();
      return self::success($orders , 'orders retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrdersRequest $request): \Illuminate\Http\JsonResponse
    {
        $order = $this->OrderService->storeOrder($request->validated());
        return self::success($order , 'Order created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): \Illuminate\Http\JsonResponse
    {
        return self::success($order , 'Order retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     * @throws \Exception
     */
    public function update(UpdateOrdersRequest $request, Order $order): \Illuminate\Http\JsonResponse
    {
        $updatedOrder = $this->OrderService->updateOrder($order,$request->validated());
        return self::success($updatedOrder , 'Order updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): \Illuminate\Http\JsonResponse
    {
        $order->delete();
        return self::success(null , 'Order deleted successfully');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function showDeletedOrders(): \Illuminate\Http\JsonResponse
    {
       $deletedOrders = Order::withTrashed()->get();
       return self::success($deletedOrders , 'Trashed orders retrieved successfully');
    }

    /**
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreOrder(Order $order): \Illuminate\Http\JsonResponse
    {
        $order->restore();
        return self::success($order , 'Order restored successfully');
    }

    /**
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    function forceDeleteOrder(Order $order): \Illuminate\Http\JsonResponse
    {
        // Checking if the order is trashed
        if ($order->trashed()) {
            $order->forceDelete();
            return self::success(null, 'Order permanently deleted successfully.');
        } else {
            return self::error(null, 'Order is not in the trash!');
        }
    }

    public function customerOrders(string id): \Illuminate\Http\JsonResponse
    {
        $customer=Customer::findOrFail($id);
        $orders= $customer->orders()->paginate(10);
        return self::success($orders , 'orders retrieved successfully');
    }
}
