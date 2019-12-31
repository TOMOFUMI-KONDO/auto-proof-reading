<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index() {
        $data = [
            'sentence' => ''
        ];
        return view('app.index', $data);
    }

    public function post(Request $request) {
        $sentence = $request->sentence;
        $sentence = str_replace('れる', '<span class="replaced">られる</span>', $sentence);
        $data = [
            'sentence' => $sentence
        ];
        return view('app.index', $data);
    }
}
