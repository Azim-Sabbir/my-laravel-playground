<?php

namespace App\Http\Controllers;

use App\handler\ErrorHandler;
use ShakilAhmmed\TableOfContents\Contents;

class ErrorController extends Controller
{
    public function handleError()
    {
        $content = new Contents();
        return "<h1 style='color: red'>Hello there, this is a test thing</h1>";
    }
}
