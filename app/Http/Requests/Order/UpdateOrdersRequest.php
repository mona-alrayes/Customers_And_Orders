<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrdersRequest extends OrderServiceRequest
{
    public function rules(): array
    {
        return parent::rules();
    }
}
