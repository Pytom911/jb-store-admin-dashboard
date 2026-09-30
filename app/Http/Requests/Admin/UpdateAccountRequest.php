<?php

namespace App\Http\Requests\Admin;

use App\Enums\AccountStatus;
use App\Models\Account;
use App\Models\AccountImage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateAccountRequest extends FormRequest
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
                Rule::unique('accounts', 'account_code')->ignore($this->account()),
            ],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'status' => ['required', Rule::enum(AccountStatus::class)],
            'images' => ['nullable', 'array', 'max:'.AccountImage::MAX_PER_ACCOUNT],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'cover_first' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('account_code')) {
            $this->merge(['account_code' => Str::upper(trim((string) $this->input('account_code')))]);
        }
    }

    /**
     * The max rule above only counts the files in this request, so an account that
     * already holds images could still be pushed past the per-account limit here.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $account = $this->account();

            if (! $account) {
                return;
            }

            $total = $account->images()->count() + count($this->file('images', []));

            if ($total > AccountImage::MAX_PER_ACCOUNT) {
                $validator->errors()->add(
                    'images',
                    'Maksimal '.AccountImage::MAX_PER_ACCOUNT.' gambar per akun. Kosongkan salah satu gambar lama dulu.',
                );
            }
        });
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
            'images.max' => 'Maksimal '.AccountImage::MAX_PER_ACCOUNT.' gambar per akun.',
            'images.*.mimes' => 'Gambar harus berformat JPG, PNG, atau WEBP.',
            'images.*.max' => 'Ukuran setiap gambar maksimal 2 MB.',
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
            'description' => 'deskripsi',
            'price' => 'harga',
            'status' => 'status',
            'images' => 'gambar',
            'images.*' => 'gambar',
        ];
    }

    private function account(): ?Account
    {
        $account = $this->route('account');

        return $account instanceof Account ? $account : null;
    }
}
