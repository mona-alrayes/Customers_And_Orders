<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrdersRequest;
use App\Http\Requests\Order\UpdateOrdersRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Services\OrderService;

class OrderController extends Controller
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
      return self::paginated($orders , 'orders retrieved successfully',200);
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
    public function showDeleted(): \Illuminate\Http\JsonResponse
    {
       $deletedOrders = Order::onlyTrashed()->get();
       return self::success($deletedOrders , 'Trashed orders retrieved successfully');
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreDeleted(string $id): \Illuminate\Http\JsonResponse
    {
        $order=Order::onlyTrashed()->findOrFail($id);
        $order->restore();
        return self::success($order , 'Order restored successfully');
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    function forceDeleted(string $id): \Illuminate\Http\JsonResponse
    {
       $order=Order::onlyTrashed()->findOrFail($id)->forceDelete();
       return self::success(null , 'Order deleted successfully');
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function customerOrders(string $id): \Illuminate\Http\JsonResponse
    {
        $orders = Customer::findOrFail($id)->orders()->paginate(10);
        return self::paginated($orders , 'orders retrieved successfully', 200);
    }
}
