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
            'sentence.file' => '校正するファイルが選択されていません。',
//            'sentence.string' => '校正する文章が入力されていません', //必須項目
            //note: ファイルサイズの制限。2MBを超えるとapacheの設定でテンプレートの"$errors->first('sentence')"に「failed to upload」のエラーが出るが上書きできなかった。
            'sentence.max' => 'ファイルサイズの上限は1.5MBです。',
            'sentence.mimes' => 'ファイルの形式が正しくありません（.txt, .docxのみ）。', //ファイル拡張子の制限
        ];
    }

    public function withValidator(Validator $validator) {
        //sumit_typeがfileの時に、ファイルがアップロードされているかをチェック
        //php.iniのupload_file_sizeが2Mなので、超えないように。
        //扱えるファイル形式は.txtと.docxのみ
        $validator->sometimes('sentence', 'bail|file|max:1500|mimes:txt,docx', function ($input) {
           return  $input->submit_type === 'file';
        });

        //note: submit_typeがtextの時に、テキストエリアに入力があるかをチェックしたかったが、
        //note: ここでのsentenceは$request->file('sentence')を指してしまう。（inputよりfileの方が優先される）
        //note: よって、submit_typeがtextの時にtextareaに入力があるかのバリデーションはAppControllerにて行う。
//        $validator->sometimes('sentence', 'string', function ($input) {
//           return $input->submit_type === 'text';
//        });
    }
}
