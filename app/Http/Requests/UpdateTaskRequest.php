<?php

namespace App\Http\Requests;

use App\Enums\TaskStatuses;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', request()->route('task'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $toDo = TaskStatuses::TO_DO->value;
        $completed = TaskStatuses::COMPLETED->value;

        return [
            'task' => 'sometimes|required|string|max:255',
            'scheduled_at' => 'required|date',
            'priority' => 'required|numeric|min:1|max:100',
            'is_completed' => "required|numeric|min:{$toDo}|max:{$completed}",
        ];
    }
}
