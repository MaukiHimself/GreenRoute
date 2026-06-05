<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'vodacom_mpesa_lipa_no' => ['nullable', 'string', 'max:255'],
            'airtel_money_lipa_no' => ['nullable', 'string', 'max:255'],
            'halopesa_lipa_no' => ['nullable', 'string', 'max:255'],
            'mixx_by_yas_lipa_no' => ['nullable', 'string', 'max:255'],
            'crdb_bank_lipa_no' => ['nullable', 'string', 'max:255'],
            'nmb_bank_lipa_no' => ['nullable', 'string', 'max:255'],
            'nbc_bank_lipa_no' => ['nullable', 'string', 'max:255'],
        ];
    }
}
