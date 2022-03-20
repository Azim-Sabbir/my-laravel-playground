<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TryController extends Controller
{
    public function index()
    {
        $randomHtml = "
            <h1>Hello h1</h1>
            <h2>Hello h2</h2>
            <h3>Hello h3</h3>
            <h4>Hello h4</h4>
            <h5>Hello h5</h5>
            <h6>Hello h6</h6>
";
        dd($this->getToc($randomHtml));
        $this->getToc($randomHtml);

    }

    function getToc($randomHtml)
    {
        preg_match_all('|<\s*h[1-6](?:.*)>(.*)</\s*h|Ui', $randomHtml, $tags, PREG_SET_ORDER);

        $realTags = [];
        foreach ($tags as $key => $tag) {
            $level = substr($tag[0], 0, 4);
            $title = $tag[1];
            $realTags[$key] = [
                "level" => $level,
                "title" => $title
            ];
        }

        return $this->getParentChildTags($realTags);
    }

    public function getParentChildTags($formattedTags)
    {
        $totalTags = count($formattedTags);
        $firstTag = $formattedTags[0] ?? null;
        $lastTagIndex = array_key_last($formattedTags);
        $nextTag = $totalTags > 1 ? $formattedTags[1] : null;

        $tagBreakPoints = [];
        $parent = '';
        $children = [];

        foreach ($formattedTags as $key => $tag) {

            if ($tag != null) {

                if (strcmp($firstTag['level'], $tag['level']) == 0) {

                    if (isset($nextTag['level'])) {

                        if ($tag['level'] == $nextTag['level']) {
                            $tagBreakPoints[] = [
                                "parent" => $tag,
                                "children" => []
                            ];

                            $firstTag = $key + 1 == $totalTags ? $formattedTags[$key] : $formattedTags[$key + 1];
                            $parent = $key + 1 == $totalTags ? $formattedTags[$key] : $formattedTags[$key + 1];
                            $children = [];
                            $nextTag = $key + 2 == $totalTags + 1 ? $formattedTags[$key + 1] : $formattedTags[$key + 2];

                        } else {

                            $tagBreakPoints[] = [
                                "parent" => $parent,
                                "children" => $children
                            ];

                            $nextTag = $key + 2 == $totalTags + 1 ? $formattedTags[$key + 1] : $formattedTags[$key + 2];
                        }
                    }
                }

                if (strcmp($firstTag['level'], $tag['level']) < 0) {
                    $parent = $firstTag;
                    $children[] = $tag;

//                    $nextTag = $key + 2 == $totalTags + 1 ? $formattedTags[$key + 1] : $formattedTags[$key + 2];
                }

                if (strcmp($firstTag['level'], $tag['level']) > 0) {
                    $tagBreakPoints[] = [
                        "parent" => $parent,
                        "children" => $children
                    ];

                    $firstTag = $tag;
                    $parent = $tag;
                    $children = [];
                }
            }
        }

        $tagBreakPoints[] = [
            "parent" => $parent,
            "children" => $this->getParentChildTags($children),
        ];
        return $tagBreakPoints;
    }
}

