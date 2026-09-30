<?php

namespace App\Http\Requests\Admin;

use App\Models\AccountImage;
use Illuminate\Foundation\Http\FormRequest;

class StoreAccountImageRequest extends FormRequest
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
            'images' => ['required', 'array', 'min:1', 'max:'.AccountImage::MAX_PER_ACCOUNT],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'cover_first' => ['nullable', 'boolean'],
        ];
    }

    /**
     * The max rule only counts this request's files, so an account that already
     * holds images is checked against its remaining slots by AccountImageService.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
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
            'images' => 'gambar',
            'images.*' => 'gambar',
        ];
    }
}
