<?php

namespace App\Http\Controllers;

// authorize()メソッドを使えるようにするためのトレイトを読み込む
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    // このトレイトをuseすることで、全てのController(このクラスを継承しているクラス)で
    // $this->authorize('アクション名', $モデル) が使えるようになる
    use AuthorizesRequests;
}