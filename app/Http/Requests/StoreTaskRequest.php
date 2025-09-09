<?php

namespace App\Http\Requests;

use App\Enums\TaskStatuses;
use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Task::class);
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
