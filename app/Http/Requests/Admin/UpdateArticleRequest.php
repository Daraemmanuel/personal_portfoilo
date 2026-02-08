<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
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
        $article = $this->route('article');
        $articleId = $article?->id ?? null;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('articles', 'slug')->ignore($articleId),
            ],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'category' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'series' => ['nullable', 'string', 'max:255'],
            'series_order' => ['nullable', 'integer', 'min:1'],
        ];
    }
}

