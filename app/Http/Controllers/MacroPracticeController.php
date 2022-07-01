<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class MacroPracticeController extends Controller
{
    public function index()
    {
        $customResponse = \App\Models\User::get();
        $macroConcatString = Str::concatStrings('this is string 1', 'this is string 2');
        $macroPrefixedString = Str::addPrefix($macroConcatString);

        $data = [
            "macro_able_response" => $customResponse,
            "macro_able_concated_string" => $macroConcatString,
            "macro_able_prefix" => $macroPrefixedString,
        ];

        return Response::customResponse($data, 'data fetched successfully', ResponseAlias::HTTP_OK);
    }
}
