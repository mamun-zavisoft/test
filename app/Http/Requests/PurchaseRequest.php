<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'discount_amount' => 'nullable|numeric|min:0',
            'shipping_charge' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'reference_no' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'status' => 'required|in:pending,received',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'qty' => 'required|array',
            'qty.*' => 'required|integer|min:1',
            // validation for payment
            'payment_type' => 'required|in:full_due,partial_paid,full_paid',
            'account_id' => 'nullable|exists:accounts,id|required_if:payment_type,partial_paid,full_paid',
            'amount' => 'nullable|numeric|min:1|required_if:payment_type,partial_paid,full_paid',
            'payment_date' => 'nullable|date|before_or_equal:today|required_if:payment_type,partial_paid,full_paid',
            'note' => 'nullable|string',
        ];
    }
}
