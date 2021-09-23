<?php

namespace App\Http\Requests\Admin\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreTask extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    /*public function authorize(): bool
    {
        return Gate::allows('admin.task.create');
    }*/

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'NroExp' => ['required', 'unique:tasks'],
            'name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'government_id' => ['required', 'string'],
            'state' => ['required'],
            'city' => ['required'],
            'farm' => ['required', 'string'],
            'account' => ['required', 'string'],
            'amount' => ['required', 'integer'],
            'workflow' => ['required'],

        ];
    }


    /**
     * Modify input data
     *
     * @return array
     */
    public function getSanitized(): array
    {
        $sanitized = $this->validated();

        //Add your code for manipulation with request data here

        return $sanitized;
    }

    public function getWorkFlowId()
    {
        if ($this->has('workflow')) {
            return $this->get('workflow')['id'];
        }
        return null;
    }

    public function getStateId()
    {
        if ($this->has('state')) {
            return $this->get('state')['DptoId'];
        }
        return null;
    }

    public function getCityId()
    {
        if ($this->has('city')) {
            return $this->get('city')['CiuId'];
        }
        return null;
    }
}
