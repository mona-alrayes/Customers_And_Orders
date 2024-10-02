<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdersRequest extends OrderServiceRequest
{
    public function rules(): array
    {
        return parent::rules();
    }
}
