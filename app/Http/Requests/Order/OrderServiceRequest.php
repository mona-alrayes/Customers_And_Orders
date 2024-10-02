<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Common validation rules.
     */
    public function rules(): array
    {
        $rules = [
            'customer_id' => 'required|exists:customers,id',
            'product_name' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|integer',
            'status' => 'required|boolean', //0= pending , 1=competed
            'order_date' => 'required|date',
        ];

        if ($this->isMethod('post')) {
            // Required for store request
            $rules['customer_id'][] = 'required';
            $rules['product_name'][] = 'required';
            $rules['quantity'][] = 'required';
            $rules['price'][] = 'required';
            $rules['status'][] = 'required';
            $rules['order_date'][] = 'required';
        } else if ($this->isMethod('put')) {
            // Allow optional fields for update request
            $rules['customer_id'][] = 'sometimes';
            $rules['product_name'][] = 'sometimes';
            $rules['quantity'][] = 'sometimes';
            $rules['price'][] = 'sometimes';
            $rules['status'][] = 'sometimes';
            $rules['order_date'][] = 'sometimes';
        }

        return $rules;
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'required' => 'حقل :attribute مطلوب',
            'string' => 'حقل :attribute يجب أن يكون نصًا وليس أي نوع آخر',
          ,
        ];
    }

    /**
     * Custom attribute names for error messages.
     */
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

    /**
     * Prepare data before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('name') !== null) {
            $this->merge([
                'product_name' => ucwords(strtolower($this->input('name'))),
            ]);
        }
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'خطأ',
            'message' => 'فشلت عملية التحقق من صحة البيانات.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
