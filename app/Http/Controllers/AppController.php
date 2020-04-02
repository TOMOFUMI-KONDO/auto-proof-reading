<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\MessageBag;
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
            'app_env' => app('env'),
            'hide_file_upload' => '',
            'hide_text_upload' => 'hide',
            'condition_number' => self::$condition_number,
            'before_rep' => '校正前の文章が出ます。',
            'after_rep' => '校正後の文章が出ます。',
            'file_name' => '',
            'is_docx' => false,
        ];

        return view('app.index', $data);
    }

    /**
     * @param AppRequest $request
     * @return Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|View
     */
    //フォームの内容のバリデーションはAppRequestで行う。
    public function post(AppRequest $request) {
         //現在の校正文の入力形式（ファイルorテキスト）を保存&形式（ファイルorテキスト）ごとの文章の内容の取得処理

        //提出されたファイルがwordファイルであることを示すフラグを定義
        $is_docx = false;

        //ファイルをアップロードされた場合
        if ($request->input('submit_type') === 'file') {

            $hide_file_upload = '';
            $hide_text_upload = 'hide';

            //一時ファイルのパスを取得
            $file_path = $request->file('sentence')->getRealPath();

            //.txtファイルの場合
            if ($request->file('sentence')->extension() === "txt") {
                $sentence = file_get_contents($file_path); //ファイル形式で取得
            }
            //.docxファイルの場合
            else {
                $is_docx = true; //wordファイルが提出されたことを示すフラグを立てる。
                $sentence = '';
                $zip = new \ZipArchive();

                if ($zip->open($file_path) === true) {
                    $xml = $zip->getFromName("word/document.xml"); //wordファイルを解凍し、document.xml（文書の内容を記すxmlファイル）を取得
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
                        $sentence = rtrim($sentence, "\n"); //末尾の改行文字は除去
                    }
                }
            }
        }
        //テキストボックスに入力された場合($request->input('submit_type') === 'text')
        else {
            $hide_file_upload = 'hide';
            $hide_text_upload = '';
            $sentence = $request->input('sentence'); //テキストエリアへの入力を取得

            //textareaに入力がない場合をここでチェックする。
            if (is_null($sentence)) {
                $message = new MessageBag();
                $message->add('sentence', '校正する文章が入力されていません。');
                return redirect('/')
                    ->withErrors($message)
                    ->withInput();
            }
        }

         //前回の校正条件の数を引継ぎ
        $this->checkCondition();

        //nullにならないように、校正元の文を代入しておく
        $before_rep = $sentence ?? '';
        $after_rep = $sentence ?? '';

         //input入力の条件に従って校正する処理
        //正規表現を使わない場合
        if ($request->input('regex_text') !== 'yes') {
            for ($i = 1; $i <= self::$condition_number; $i++) {
                $before_str = $request->input("before_str$i");
                $after_str = $request->input("after_str$i");

                //校正条件が空欄の場合はスキップ
                if (isset($before_str)) {
                    $before_rep = str_replace($before_str, '<span class="replaced">' . $before_str . '</span>', $before_rep);
                    $after_rep = str_replace($before_str, '<span class="replaced">' . $after_str . '</span>', $after_rep);
                }
            }
        }
        //正規表現を使う場合
        else {
            for ($i = 1; $i <= self::$condition_number; $i++) {
                $before_str = $request->input("before_str$i");
                $after_str = $request->input("after_str$i");

                //校正条件が空欄の場合はスキップ
                if (isset($before_str)) {

                    //置き換えられる文字列を示す変数を作成（spanタグ内の端っこの空白はspanタグの外に出す。<-以降の置換に影響が出ないように。）
                    $before_rep = preg_replace($before_str, '<span class="replaced">$0</span>', $before_rep);
                    $before_rep = str_replace('<span class="replaced"> ', ' <span class="replaced">', $before_rep);
                    $before_rep = str_replace(' </span>', '</span> ', $before_rep);

                    //置き換えられた文字列を示す変数を作成（spanタグ内の端っこの空白はspanタグの外に出す。<-以降の置換に影響が出ないように。）
                    $after_rep = preg_replace($before_str, '<span class="replaced">' . $after_str . '</span>', $after_rep);
                    $after_rep = str_replace('<span class="replaced"> ', ' <span class="replaced">', $after_rep);
                    $after_rep = str_replace(' </span>', '</span> ', $after_rep);
                }
            }
        }

        //csvファイルの条件に従って校正する処理
        if (!empty($request->file('condition_file'))) {

            $condition_file_name = $request->file('condition_file')->getClientOriginalName();
            $condition_file = $request->file('condition_file')->storeAs('condition_files', $condition_file_name);
            $fp = fopen(storage_path('app/') . $condition_file, 'r');

            //正規表現を使わない場合
            if ($request->input('regex_csv') !== 'yes') {
                while ($line = fgetcsv($fp, 1024, ',', '"')) {
                    $before_str = $line[0];
                    $after_str = $line[1];

                    //校正条件が空欄の場合はスキップ
                    if (isset($before_str)) {
                        $before_rep = str_replace($before_str, '<span class="replaced">' . $before_str . '</span>', $before_rep);
                        $after_rep = str_replace($before_str, '<span class="replaced">' . $after_str . '</span>', $after_rep);
                    }
                }
            }
            //正規表現を使う場合
            else {
                while ($line = fgetcsv($fp, 1024, ',', '"')) {
                    $before_str = $line[0];
                    $after_str = $line[1];

                    //校正条件が空欄の場合はスキップ
                    if (isset($before_str)) {

                        //置き換えられる文字列を示す変数を作成（spanタグ内の端っこの空白はspanタグの外に出す。<-以降の置換に影響が出ないように。）
                        $before_rep = preg_replace($before_str, '<span class="replaced">$0</span>', $before_rep);
                        $before_rep = str_replace('<span class="replaced"> ', ' <span class="replaced">', $before_rep);
                        $before_rep = str_replace(' </span>', '</span> ', $before_rep);

                        //置き換えられた文字列を示す変数を作成（spanタグ内の端っこの空白はspanタグの外に出す。<-以降の置換に影響が出ないように。）
                        $after_rep = preg_replace($before_str, '<span class="replaced">' . $after_str . '</span>', $after_rep);
                        $after_rep = str_replace('<span class="replaced"> ', ' <span class="replaced">', $after_rep);
                        $after_rep = str_replace(' </span>', '</span> ', $after_rep);
                    }
                }
            }
        }

        //ダウンロード用のファイル名に利用するため、タイムスタンプを取得
        $time_stamp = Carbon::now()->timestamp;

        //<span>タグを無しの校正後の文章を取得
        $plain_after_rep = str_replace('<span class="replaced">', '', $after_rep); //<span class="replaced">を削除
        $plain_after_rep = str_replace('</span>', '', $plain_after_rep); //</span>を削除

        //校正後の文字列を漢字を抜いてアルファベットに変換。
        $roman_after_rep = StringProcessing::kanaToRoman($plain_after_rep);

        //post時点でのタイムスタンプと、校正後の文字列の漢字以外をローマ字に変換した最初の10文字をつなげたものをファイル名にする。
        $calibrated_file_name = $time_stamp . '_' . substr($roman_after_rep, 0, 10);

       //校正後の文章をテキストファイルとして保存
        $calibrated_file_name = $calibrated_file_name . '.txt';
        file_put_contents(storage_path('app/public/calibrated/' . $calibrated_file_name), $plain_after_rep);

        $data = [
            'app_env' => app('env'),
            'hide_file_upload' => $hide_file_upload ?? '',
            'hide_text_upload' => $hide_text_upload ?? '',
            'condition_number' => self::$condition_number ?? 5,
            'before_rep' => $before_rep !== '' ? $before_rep : '校正前の文章が出ます。',
            'after_rep' => $after_rep !== '' ? $after_rep : '校正後の文章が出ます。', //入力は空文字でない前提
            'file_name' => $calibrated_file_name ?? '',
            'is_docx' => $is_docx ?? false,
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
