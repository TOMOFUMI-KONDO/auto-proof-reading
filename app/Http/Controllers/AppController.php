<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index() {
        $data = [
            'before' => '',
            'after' => '',
        ];
        return view('app.index', $data);
    }

    public function post(Request $request) {
        $sentence = $request->sentence;
        $before = str_replace('れる', '<span class="replaced">れる</span>', $sentence);
        $after = str_replace('れる', '<span class="replaced">られる</span>', $sentence);
        $data = [
            'before' => $before,
            'after' => $after,
        ];
        return view('app.index', $data);
    }
}
