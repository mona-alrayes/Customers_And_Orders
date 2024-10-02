<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerServiceRequest extends FormRequest
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
            'name' => ['string', 'min:3', 'max:255'],
            'email' => ['string', 'email', 'unique:customer,email,' . $this->route('customer')],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9\s]+$/'], // Regex allows numbers, spaces, and optional +
        ];

        if ($this->isMethod('post')) {
            // Required for store request
            $rules['name'][] = 'required';
            $rules['email'][] = 'required';
            $rules['phone'][] = 'required';
        } else if ($this->isMethod('put')) {
            // Allow optional fields for update request
            $rules['name'][] = 'sometimes';
            $rules['email'][] = 'sometimes';
            $rules['phone'][] = 'sometimes';
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
            'unique' => 'حقل :attribute موجود في بياناتنا',
            'email' => 'حقل :attribute يجب ان يكون بصيغة something@something.something',
            'phone.regex' => 'حقل :attribute يجب أن يحتوي على أرقام فقط، ويُسمح باستخدام الرمز "+" والمسافات',
        ];
    }

    /**
     * Custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'name' => 'الاسم',
            'email' => 'البريد الالكتروني',
            'phone' => 'رقم الهاتف',
        ];
    }

    /**
     * Prepare data before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('name') !== null) {
            $this->merge([
                'name' => ucwords(strtolower($this->input('name'))),
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
