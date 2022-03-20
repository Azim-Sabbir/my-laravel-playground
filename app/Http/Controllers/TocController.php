<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use ShakilAhmmed\TableOfContents\Contents;
use TOC\MarkupFixer;
use TOC\TocGenerator;

class TocController extends Controller
{
    public function index()
    {
//        $html = DB::table('posts')
//            ->select('description')
//            ->where('id',563)->get();
        $html = "
            <h1>Hello h1</h1>
            <h2>Hello h2</h2>
            <h2>Hello h2</h2>
            <p style='color: red'>hello form p</p>
            <h3>Hello h3</h3>
            <h4>Hello h4</h4>
            <h5>Hello h5</h5>
            <h3>Hello h3</h3>
            <h4>Hello h4</h4>
            <h2>Hello h2</h2>
            <h3>Hello h3</h3>
        ";


        return view('welcome', compact('html'));
    }
}
