<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderService
{

    /**
     * get all orders with customer they belong to
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function getOrders(): LengthAwarePaginator
    {
        try {
            return Order::with('customer')->paginate(10);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }


    /**
     * store new order in storage
     *
     * @param [type] $Data
     * @return Order
     * @throws Exception
     */
    public function storeOrder($Data): Order
    {
        try {
            $order = Order::create($Data);
            return $order->load('customer');
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }


    /**
     * update specific order
     * @throws Exception
     * @param Order $order
     * @param [type] $Data
     * @return Order
     */
    public function updateOrder(Order $order, $Data): Order
    {
        try {
            $order->update(array_filter($Data));
            return $order->load('customer');
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
