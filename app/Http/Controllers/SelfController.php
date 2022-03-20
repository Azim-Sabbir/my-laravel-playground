<?php

namespace App\Http\Controllers;

class SelfController extends Controller
{
    public function index()
    {
        $randomHtml = "
            <h2>Hello h2</h2>
            <h2>Hello h2</h2>
                <h3>Hello h3</h3>
                    <h4>Hello h4</h4>
                        <h5>Hello h5</h5>

            <h1>Hello h1</h1>
                <h3>Hello h3</h3>
                    <h4>Hello h4</h4>
            <h1>Hello h1</h1>
                <h2>Hello h2</h2>
                    <h3>Hello h3</h3>

                <h2>Hello h2</h2>
                    <h6>Hello h5</h6>
";

        echo $this->getToc($randomHtml);

    }

    function getToc($randomHtml)
    {
        $data = preg_match_all('|<\s*h[1-6](?:.*)>(.*)</\s*h|Ui', $randomHtml, $tags, PREG_SET_ORDER);
        $realTags = [];
        foreach ($tags as $key => $tag) {
            $level = substr($tag[0], 0, 4);
            $title = $tag[1];
            $realTags[$key] = [
                "level" => $level,
                "title" => $title
            ];
        }
        dd($this->getParentChildTags($realTags));
        $tagList = $this->getParentChildTags($realTags);

        echo $this->generateToc($tagList);
    }

    public function getParentChildTags($realTags)
    {
        $count = count($realTags);
        $firstTag = $realTags[0] ?? null;
        $lastTagIndex = array_key_last($realTags);
        $nextTag = $count > 1 ? $realTags[1] : [];


        $tagBreakPoints = [];
        $parent = '';
        $children = [];
//
//        if($realTags == []){
//            dd('null');
//        }

        foreach ($realTags as $key => $tag) {
            if ($realTags) {
                if (strcmp($firstTag['level'], $tag['level']) < 0) {
                    $parent = $firstTag;
                    $children[] = $tag;
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

                if (strcmp($firstTag['level'], $tag['level']) == 0) {
                    dump($firstTag['level']);
                    if (isset($nextTag['level'])) {
                        if ($tag['level'] == $nextTag['level']) {
                            $tagBreakPoints[] = [
                                "parent" => $tag,
                                "children" => []
                            ];

                            $firstTag = $tag;
                            $parent = $tag;
                            $children = [];
                            $nextTag = $key + 1 === $count ? $realTags[$key] : $realTags[$key + 1];
                        } else {
//                    $firstTag = $tag;
//                    $parent = $tag;
//                    $children = [];

                            $tagBreakPoints[] = [
                                "parent" => $parent,
                                "children" => $children
                            ];

                            $nextTag = $key + 1 === $count ? $realTags[$key] : $realTags[$key + 1];
                        }
                    }


                }

            }
        }
        $tagBreakPoints[] = [
            "parent" => $parent,
//            "children" => $children
            "children" => $children ? $this->getParentChildTags($children) : []
        ];

        return $tagBreakPoints;
    }

    public function generateToc($tagLists)
    {
        $output = "<ol>";

        foreach ($tagLists as $key => $tag) {
            if (isset($tag['parent'])) {
                $output .= "<a href='#'><li>{$tag['parent']['title']}";
            }
//            $output .= "<a href='#'><li>{$tag['title']}</li></a>";
            if (isset($tag['children'])) {
                $output .= "<li>";
                foreach ($tag['children'] as $children) {
                    if (gettype($children) == 'string') {
                        dump($children);
//                        $output .= $this->generateToc($children);
                    }
                    if (gettype($children) == 'array') {
//                        dump($children);
//                        $output .= $this->generateToc($children['children']);
                    }
                }
                $output .= "</li>";
            }

        }

        $output .= "</ol>";

        return $output;
    }

}
