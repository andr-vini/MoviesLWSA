<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
            'email' => 'required|email',
            'password' => 'required|min:4'
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'O email deve ser um email válido!',
            'password.min' => 'O mínimo de caracteres é 4',
            'required' => 'Todos os campos precisam ser preenchidos'
        ];
    }
}
