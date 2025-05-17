<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Prepare the data for validation.
     * Converte vírgula para ponto para valores decimais.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('amount')) {
            $this->merge([
                'amount' => str_replace(',', '.', $this->input('amount')),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'to_user_id' => [
                'required',
                'integer',
                'exists:users,id',
                'not_in:' . auth()->id(),
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'to_user_id.required' => 'Selecione um usuário para transferir.',
            'to_user_id.exists'   => 'Usuário selecionado não existe.',
            'to_user_id.not_in'   => 'Você não pode transferir para si mesmo.',
            'amount.required'     => 'O valor é obrigatório.',
            'amount.numeric'      => 'Informe um valor numérico.',
            'amount.min'          => 'O valor mínimo é R$0,01.',
        ];
    }
}
