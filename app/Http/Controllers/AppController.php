<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    const CONDITION_NUMBER = 10;

    public function index() {
        $data = [
            'condition_number' => self::CONDITION_NUMBER,
            'before_rep' => '校正前',
            'after_rep' => '校正後',
        ];
        return view('app.index', $data);
    }

    public function post(Request $request) {
        $sentence = $request->sentence;
        $before_rep = '';
        $after_rep = '';
        for($i = 0; $i < self::CONDITION_NUMBER; $i++) {
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
            'condition_number' => self::CONDITION_NUMBER,
            'before_rep' => $before_rep,
            'after_rep' => $after_rep,
        ];
        $request->flash();
        return view('app.index', $data);
    }
}
