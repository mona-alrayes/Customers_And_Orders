<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'customer_id' => ['required', 'exists:customers,id'],
            'product_name' => ['required', 'string'],
            'quantity' => ['required', 'integer'],
            'price' => ['required', 'integer'],
            'status' => ['required', 'in:pending,completed'],
            'order_date' => ['required', 'date_format:d-m-Y'],
        ];

        if ($this->isMethod('post')) {
            $rules['customer_id'][] = 'required';
            $rules['product_name'][] = 'required';
            $rules['quantity'][] = 'required';
            $rules['price'][] = 'required';
            $rules['status'][] = 'required';
            $rules['order_date'][] = 'required';
        } else if ($this->isMethod('put')) {
            $rules['customer_id'][] = 'sometimes';
            $rules['product_name'][] = 'sometimes';
            $rules['quantity'][] = 'sometimes';
            $rules['price'][] = 'sometimes';
            $rules['status'][] = 'sometimes';
            $rules['order_date'][] = 'sometimes';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'required' => 'حقل :attribute مطلوب',
            'string' => 'حقل :attribute يجب أن يكون نصًا وليس أي نوع آخر',
            'date_format' => 'حقل :attribute يجب أن يكون بتنسيق التاريخ d-m-y',
            'in' => 'الحالة يجب أن تكون إما "pending" أو "completed"',
            'exists' => 'معرف الزبون غير موجود',
            'integer' => 'حقل :attribute يجب أن يكون عددًا صحيحًا',
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_id' => 'معرف الزبون',
            'product_name' => 'اسم المنتج',
            'quantity' => 'الكمية',
            'price' => 'السعر',
            'status' => 'الحالة',
            'order_date' => 'تاريخ الطلب',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('name') !== null) {
            $this->merge([
                'product_name' => ucwords(strtolower($this->input('name'))),
            ]);
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'خطأ',
            'message' => 'فشلت عملية التحقق من صحة البيانات.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
