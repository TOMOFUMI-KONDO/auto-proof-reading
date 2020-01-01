<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    private static $condition_number;

    public function index() {
//        self::$condition_number = $_POST['condition_number'] ?? 5;
        self::$condition_number = 10;
        $data = [
            'condition_number' => self::$condition_number,
            'before_rep' => '校正前',
            'after_rep' => '校正後',
        ];

        return view('app.index', $data);
    }

    public function post(Request $request) {
//        self::$condition_number = $_POST['condition_number'] ?? 5;
        self::$condition_number = 10;
        $sentence = $request->sentence;
        $before_rep = '';
        $after_rep = '';

        for($i = 0; $i < self::$condition_number; $i++) {
            $before_str = $request->input("before_str" . $i);
            $after_str = $request->input("after_str" . $i);
            if($i === 0) {
                $before_rep = str_replace($before_str, '<span class="replaced">' . $before_str . '</span>', $sentence);
                $after_rep = str_replace($before_str, '<span class="replaced">' . $after_str . '</span>', $sentence);
            }
            else {
                $before_rep = str_replace($before_str, '<span class="replaced">' . $before_str . '</span>', $before_rep);
                $after_rep = str_replace($before_str, '<span class="replaced">' . $after_str . '</span>', $after_rep);
            }
        }

        $data = [
            'condition_number' => self::$condition_number,
            'before_rep' => $before_rep,
            'after_rep' => $after_rep,
        ];
        $request->flash();

        return view('app.index', $data);
    }
}
