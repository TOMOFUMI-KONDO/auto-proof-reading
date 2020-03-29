<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use App\StringProcessing;
use Carbon\Carbon;

/**
 * Class AppController
 * @package App\Http\Controllers
 */
class AppController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index() {
         //前回の校正条件の数を引継ぎ
        self::checkCondition();

        $data = [
            'hide_file_upload' => '',
            'hide_text_upload' => 'hide',
            'condition_number' => self::$condition_number,
            'before_rep' => '校正前の文章が出ます。',
            'after_rep' => '校正後の文章が出ます。',
            'file_name' => '',
        ];

        return view('app.index', $data);
    }

    /**
     * フォームの内容のバリデーションはAppRequestで行う。
     * @param AppRequest $request
     * @return Factory|View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function post(AppRequest $request) {
         //現在の校正文の入力形式（ファイルorテキスト）を保存&形式（ファイルorテキスト）ごとの文章の内容の取得処理

        //ファイルをアップロードされた場合
        if ($request->input('submit_type') === 'file') {
            $hide_file_upload = '';
            $hide_text_upload = 'hide';

            //.txtファイルの場合
            if ($request->file('sentence')->extension() === "txt") {
                $sentence = file_get_contents($request->file('sentence')->getRealPath()); //ファイル形式で取得
            }
            //.docxファイルの場合
            else {
                $sentence = '';
                $file_path = $request->file('sentence')->getRealPath();
                $zip = new \ZipArchive(); //wordファイルを解凍

                if ($zip->open($file_path) === true) {
                    $xml = $zip->getFromName("word/document.xml"); //解凍したフォルダからdocument.xml（文書の内容を記すxmlファイル）を取得
                    if ($xml) {
                        $dom = new \DOMDocument();
                        $dom->loadXML($xml);
                        $paragraphs = $dom->getElementsByTagName("p");
                        foreach ($paragraphs as $p) {
                            $texts = $p->getElementsByTagName("t");
                            foreach ($texts as $t) {
                                $sentence .= $t->nodeValue;
                            }
                            //Add New Line after a paragraph.
                            $sentence .= "\n";
                        }
                    }
                }
            }
        }
        //テキストボックスに入力された場合
        else {
            $hide_file_upload = 'hide';
            $hide_text_upload = '';
            $sentence = $request->input('sentence'); //テキストエリアへの入力を取得
        }

         //前回の校正条件の数を引継ぎ
        $this->checkCondition();

         //入力は空文字出ない前提
        $before_rep = '';
        $after_rep = '';

         //input入力の条件に従って校正する処理
        for ($i = 1; $i <= self::$condition_number; $i++) {
            $before_str = $request->input("before_str$i");
            $after_str = $request->input("after_str$i");

            if ($i === 1) {
                $before_rep = str_replace($before_str, '<span class="replaced">' . $before_str . '</span>', $sentence);
                $after_rep = str_replace($before_str, '<span class="replaced">' . $after_str . '</span>', $sentence);
            } else {
                $before_rep = str_replace($before_str, '<span class="replaced">' . $before_str . '</span>', $before_rep);
                $after_rep = str_replace($before_str, '<span class="replaced">' . $after_str . '</span>', $after_rep);
            }
        }

         //csvファイルの条件に従って校正する処理
        if (!empty($request->file('condition_file'))) {
            $condition_file_name = $request->file('condition_file')->getClientOriginalName();
            $condition_file = $request->file('condition_file')->storeAs('condition_files', $condition_file_name);
            $fp = fopen(storage_path('app/') . $condition_file, 'r');

            while ($line = fgetcsv($fp, 1024, ',', '"')) {
                $before_str = $line[0];
                $after_str = $line[1];
                $before_rep = str_replace($before_str, '<span class="replaced">' . $before_str . '</span>', $before_rep);
                $after_rep = str_replace($before_str, '<span class="replaced">' . $after_str . '</span>', $after_rep);
            }
        }

        //校正後の文章をダウンロードできるように、.txt形式で保存しておく。
        $time_stamp = Carbon::now()->timestamp;

        //<span>タグを無しの校正後の文章を作成
        $plane_after_rep = str_replace('<span class="replaced">', '', $after_rep); //<span class="replaced">を削除
        $plane_after_rep = str_replace('</span>', '', $plane_after_rep); //</span>を削除

        //校正後の文字列を漢字を抜いてアルファベットに変換。
        $roman_after_rep = StringProcessing::kanaToRoman($plane_after_rep);

        //post時点でのタイムスタンプと、校正後の文字列の漢字以外をローマ字に変換した最初の10文字をつなげたものをファイル名にする。
        $calibrated_file_name = $time_stamp . '_' . substr($roman_after_rep, 0, 10) . '.txt';

        //校正後の文章をテキストファイルとして保存
        file_put_contents(storage_path('app/public/calibrated/' . $calibrated_file_name), $plane_after_rep);

        $data = [
            'hide_file_upload' => $hide_file_upload,
            'hide_text_upload' => $hide_text_upload,
            'condition_number' => self::$condition_number,
            'before_rep' => $before_rep !== '' ? $before_rep : '校正前の文章が出ます。',
            'after_rep' => $after_rep !== '' ? $after_rep : '校正後の文章が出ます。', //入力は空文字でない前提
            'file_name' => $calibrated_file_name,
        ];
        $request->flash();

        return view('app.index', $data);
    }

    private static $condition_number;

    private function checkCondition() {
         //前回の校正条件の数を引継ぎ
        self::$condition_number =  $_COOKIE['condition_number'] ?? 5;

        //校正条件の入力BOXは最低でも１つ表示
        if (self::$condition_number < 1) {
            self::$condition_number = 1;
        }
    }
}
