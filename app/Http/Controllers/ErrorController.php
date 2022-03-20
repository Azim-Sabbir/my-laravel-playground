<?php

namespace App\Http\Controllers;

use App\handler\ErrorHandler;
use ShakilAhmmed\TableOfContents\Contents;

class ErrorController extends Controller
{
    public function handleError()
    {
        $content = new Contents();
    }
}
