<?php

namespace App\Http\Requests\Admin\Categories;

use App\Enums\Permissions\CategoryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditRequest extends FormRequest
{

    protected $redirectRoute = 'admin.categories.create';
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return auth()->user()?->can(CategoryEnum::EDIT->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('category')->id;
        return [
            'name'=>['required','string','min:2', 'max:255',Rule::unique('categories', 'name')->ignore($id)],
            'parent_id'=>['nullable','integer','exists:categories,id'],
        ];
    }
}
