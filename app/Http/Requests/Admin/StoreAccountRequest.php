<?php

namespace App\Http\Requests\Admin;

use App\Enums\AccountStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'game_id' => ['required', 'integer', Rule::exists('games', 'id')],
            'account_code' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Z0-9]+(?:-[A-Z0-9]+)*$/',
                Rule::unique('accounts', 'account_code'),
            ],
            'title' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'max:150'],
            'password' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'status' => ['required', Rule::enum(AccountStatus::class)],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('account_code')) {
            $this->merge(['account_code' => Str::upper(trim((string) $this->input('account_code')))]);
        }
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'account_code.regex' => 'Kode akun hanya boleh berisi huruf kapital, angka, dan tanda hubung (contoh: ML-001).',
            'account_code.unique' => 'Kode akun sudah dipakai akun lain.',
            'price.numeric' => 'Harga harus berupa angka.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'game_id' => 'game',
            'account_code' => 'kode akun',
            'title' => 'judul',
            'username' => 'username',
            'password' => 'password',
            'description' => 'deskripsi',
            'price' => 'harga',
            'status' => 'status',
        ];
    }
}
