<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //パスが"/"の場合のみ許可
        if ($this->path() === "/") {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "sentence" => "required",
        ];
    }

    /**
     * @return array
     */
    public function messages() {
        return [
            "sentence.required" => "校正する文を入力してください。",
        ];
    }
}
