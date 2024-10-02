<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class CustomerController extends Controller
{

    protected CustomerService $CustomerService;

    public function __construct(CustomerService $CustomerService){
        $this->CustomerService = $CustomerService;
    }

    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index(Request $request): JsonResponse
    {
     $customers = $this->CustomerService->getCustomers($request);
     return self::paginated($customers,'Customers retrieved successfully',200);
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Exception
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $customer = $this->CustomerService->storeCustomer($request->validated());
        return self::success($customer,'Customer created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): JsonResponse
    {
        return self::success($customer,'Customer retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     * @throws \Exception
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $updated_customer = $this->CustomerService->updateCustomer($customer, $request->validated());
        return self::success($updated_customer,'Customer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();
        return self::success(null,'Customer deleted successfully');
    }

    /**
     * @return JsonResponse
     */
    public function showDeleted(): JsonResponse
    {
        $customers = Customer::onlyTrashed()->get();
        return self::success($customers,'Customers retrieved successfully');
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function restoreDeleted(string $id): JsonResponse
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer->restore();
        return self::success($customer,'Customer restored successfully');
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function forceDeleted(string $id): JsonResponse
    {
        $customer = Customer::onlyTrashed()->findOrFail($id)->forceDelete();
//        $customer->forceDelete();
        return self::success(null,'Customer force deleted successfully');
    }
}
