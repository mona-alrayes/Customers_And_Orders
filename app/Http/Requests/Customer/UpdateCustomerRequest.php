<?php

namespace App\Http\Requests\Customer;

class UpdateCustomerRequest extends CustomerServiceRequest
{
    public function rules(): array
    {
        return parent::rules();
    }
}
