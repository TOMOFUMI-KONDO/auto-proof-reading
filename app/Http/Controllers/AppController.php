<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class AppController
 * @package App\Http\Controllers
 */
class AppController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

         //前回の校正条件の数を引継ぎ
        self::checkCondition();

        $data = [
            'hide_file_upload' => '',
            'hide_text_upload' => 'hide',
            'condition_number' => self::$condition_number,
            'before_rep' => '校正前',
            'after_rep' => '校正後',
        ];

        return view('app.index', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function post(Request $request) {

         //校正形式の切り替え（ファイルorテキスト）
        if ($request->submit_type === 'file') {
            $hide_file_upload = '';
            $hide_text_upload = 'hide';
        }
        else if ($request->submit_type === 'text') {
            $hide_file_upload = 'hide';
            $hide_text_upload = '';
        }

        //校正形式ごとの内容の取得処理
        if ($request->file('sentence')) {
            $sentence = file_get_contents($request->file('sentence')->getRealPath());
        }
        else {
            $sentence = $request->sentence;
        }

         //前回の校正条件の数を引継ぎ
        self::checkCondition();

         //入力が空文字だった場合の備え
        $before_rep = '';
        $after_rep = '';

         //input入力の条件に従って校正する処理
        for ($i = 1; $i <= self::$condition_number; $i++) {
            $before_str = $request->input("before_str$i");
            $after_str = $request->input("after_str$i");
            if ($i === 1) {
                $before_rep = str_replace($before_str, '<span class="replaced">' . $before_str . '</span>', $sentence);
                $after_rep = str_replace($before_str, '<span class="replaced">' . $after_str . '</span>', $sentence);
            }
            else {
                $before_rep = str_replace($before_str, '<span class="replaced">' . $before_str . '</span>', $before_rep);
                $after_rep = str_replace($before_str, '<span class="replaced">' . $after_str . '</span>', $after_rep);
            }
        }

         //csvファイルの条件に従って校正する処理
        if (!empty($request->file('condition_file'))) {
            $file_name = $request->file('condition_file')->getClientOriginalName();
            $condition_file = $request->file('condition_file')->storeAs('condition_files', $file_name);
            $fp = fopen(storage_path('app/') . $condition_file, 'r');

            while ($line = fgetcsv($fp, 1024, ',', '"')) {
                $before_str = $line[0];
                $after_str = $line[1];
                $before_rep = str_replace($before_str, '<span class="replaced">' . $before_str . '</span>', $before_rep);
                $after_rep = str_replace($before_str, '<span class="replaced">' . $after_str . '</span>', $after_rep);
            }
        }

        $data = [
            'hide_file_upload' => $hide_file_upload,
            'hide_text_upload' => $hide_text_upload,
            'condition_number' => self::$condition_number,
            'before_rep' => $before_rep !== '' ? $before_rep : '校正前',
            'after_rep' => $after_rep !== '' ? $after_rep : '校正後',
        ];
        $request->flash();

        return view('app.index', $data);
    }

    private static $condition_number;

    private function checkCondition() {

         //前回の校正条件の数を引継ぎ
        self::$condition_number =  $_COOKIE['condition_number'] ?? 5;
        if (self::$condition_number < 1) {
            self::$condition_number = 1;
        }
    }
}
