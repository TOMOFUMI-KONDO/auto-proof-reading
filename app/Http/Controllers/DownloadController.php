<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Exception\Exception;

class DownloadController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    //txtファイルをダウンロード
    public function index(Request $request) {
        $file_path = '/calibrated/' . $request->input('file-name');
        $file_name = $request->input('file-name');
        $mime_type = Storage::disk('local_public')->mimeType($file_path);
        $headers = [['Content-Type' => $mime_type]];

        return Storage::disk('local_public')->download($file_path, $file_name, $headers);
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    //docxファイルをダウンロード
    public function docx(Request $request) {
        $file_path = storage_path('app/public/calibrated/') . $request->input('file-name');
        $file_contents = File::get($file_path); //保存しておいたテキストファイルから、校正後の文章を取得
        $file_contents = str_replace("\n", "<w:br />", $file_contents); //改行文字を変換

        $php_word = new \PhpOffice\PhpWord\PhpWord();
        $section = $php_word->addSection();

        $font_style = new \PhpOffice\PhpWord\Style\Font();
//        $font_style->setBold(true);
        $font_style->setName('游ゴシック');
        $font_style->setSize(10.5);
        $test_element = $section->addText($file_contents);
        $test_element->setFontStyle($font_style);

        $file_name = str_replace('.txt', '.docx', $request->input('file-name'));
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file_name .'"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');

        try {
            $xml_writer = \PhpOffice\PhpWord\IOFactory::createWriter($php_word, 'Word2007');
        } catch (Exception $e) {
            print($e->getMessage());
        }
        ob_end_clean(); //バッファ消去
        $xml_writer->save("php://output");
    }
}
