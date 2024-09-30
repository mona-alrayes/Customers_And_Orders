<?php

namespace App\Services;

use App\Models\Customer;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerService
{

    /**
     * get all customers with orders they have
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function getCustomers($request): LengthAwarePaginator
    {
        try {
            $query = Customer::query();

            if ($request->has('status')) {
                $query->whereRelation('orders', 'status', '=', $request->input('status'));
            }

            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereRelation('orders', 'order_date', '>=', $request->input('start_date'))
                    ->whereRelation('orders', 'order_date', '<=', $request->input('end_date'));
            }

            return $query->paginate($request->input('per_page', 10));
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }


    /**
     * store new customer in storage
     *
     * @param [type] $Data
     * @return Customer
     * @throws Exception
     */
    public function storeCustomer($Data): Customer
    {
        try {
            $customer = Customer::create($Data);
            return $customer->load('orders');
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }


    /**
     * update specific course
     * @throws Exception
     * @param Customer $customer
     * @param [type] $Data
     * @return Course
     */
    public function updateCustomer(Customer $customer, $Data): Customer
    {
        try {
            $customer->update(array_filter($Data));
            return $customer->load('orders');
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
