<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
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
            'sentence.required' => '校正する文章を入力してください。', //必須項目
            //note: ファイルサイズの制限。2MBを超えるとapacheの設定でテンプレートの"$errors->first('sentence')"に「failed to upload」のエラーが出るが上書きできなかった。
            'sentence.max' => 'ファイルサイズの上限は1.5MBです。',
            'sentence.mimes' => 'ファイルの形式が正しくありません（.txt, .docxのみ）。', //ファイル拡張子の制限
        ];
    }

    public function withValidator(Validator $validator) {
        //php.iniのupload_file_sizeが2Mなので、超えないように。
        $validator->sometimes('sentence', 'bail|max:1500|mimes:txt,docx', function ($input) {
            return is_file($input->sentence);
        });
    }
}
