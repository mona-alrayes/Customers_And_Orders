<?php

namespace App\Http\Requests\Customer;

class StoreCustomerRequest extends CustomerServiceRequest
{
    public function rules(): array
    {
        return parent::rules();
    }
}
