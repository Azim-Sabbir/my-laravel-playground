<?php

namespace App\Http\Controllers;

class CleanTocController extends Controller
{
    protected $configs = [
        "hierarchy" => false,
        "supportedHeadings" => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        "tocNumbering" => false,
    ];

    public function getToc()
    {
        $supportedTags = $this->configs['supportedHeadings'];
        $supportedTagLevels = str_replace("h", "", $supportedTags);
        $supportedTagLevels = implode(",", $supportedTagLevels);

        $html = '
            <h2 id="Rumi-F">Hello h2</h2>
            <h2 id="Sabbir asd">Hello h2</h2>
                <h3>Hello h3</h3>x``
                    <h4>Hello h4</h4>
                        <h5>Hello h5</h5>
                            <h6>Hello h6</h6>

            <h2>Hello h2</h2>
                <h3 id="Sabbir">Hello h3</h3>

            <h1>Hello h1</h1>
                <h3>Hello h3</h3>
                    <h4 id="my name is Rumi">Hello h4</h4>

            <h1>Hello h1</h1>
                <h2>Hello h2</h2>

            <h1>Sabbir h1</h1>
                <h3>Hello h3</h3>
                <h2>Hello h2</h2>
                    <h5>Hello h5</h5>
            <h1>Sabbir h1</h1>
';

        $parentChildArray = $this->formatTagsFromArray($html, $supportedTagLevels);
        $tocOutput = $this->generateTableOfContent($parentChildArray);
        $content = $this->addAnchorToHeadingTags($html);
        return view('welcome', compact('tocOutput', 'content'));
    }

    function formatTagsFromArray($html, $supportedTagLevels)
    {
        preg_match_all('|<\s*h[' . $supportedTagLevels . '](?:.*)>(.*)</\s*h|Ui', $html, $tags, PREG_SET_ORDER);

        $realTags = [];
        $depth = 1;

        foreach ($tags as $key => $tag) {
            $depth += 1;
            preg_match_all('/id=\"(.*?)\"/', $tag[0], $string);

            if (!blank($string[1])) {
                $id = $depth . $this->makeId($string[1][0]);
            } else {
                $id = $depth . $this->makeId($tag[1]);
            }

            $level = substr($tag[0], 0, 4);
            $title = $tag[1];

            $realTags[$key] = [
                "level" => $level,
                "id" => $id,
                "title" => $title
            ];
        }
        return $this->getParentChildTags($realTags);
    }

    public function getParentChildTags($tags)
    {
        if ($this->configs['hierarchy'] == false){
            return $tags;
        }

        $firstTag = $tags[0] ?? null;
        $tagBreakPoints = [];
        $parent = '';
        $children = [];

        foreach ($tags as $tag) {
            if (strcmp($firstTag['level'], $tag['level']) < 0) {
                $parent = $firstTag;
                $children[] = $tag;
            }

            if (strcmp($firstTag['level'], $tag['level']) >= 0) {

                if (!empty($parent)) {

                    $tagBreakPoints[] = [
                        "level" => $parent['level'],
                        "title" => $parent['title'],
                        'id' => $parent['id'],
                        "children" => $this->getParentChildTags($children),
                    ];
                }

                $firstTag = $tag;
                $parent = $tag;
                $children = [];
            }
        }

        if (!empty($parent)) {
            $tagBreakPoints[] = [
                "level" => $parent['level'],
                "title" => $parent['title'],
                "id" => $parent['id'],
                "children" => $this->getParentChildTags($children),
            ];
        }

        return $tagBreakPoints;
    }

    public function generateTableOfContent($tagsArray)
    {
        if ($this->configs['hierarchy'] == false){
            return $this->tocWithoutHierarchy($tagsArray);
        }

        $selectedNumberingFormat = $this->configs['tocNumbering'] == true ? '<ol>' : '<ul>';
        $output = $selectedNumberingFormat;

        foreach ($tagsArray as $tag) {
            $output .= "<li>
                            <a href=\"#{$tag['id']}\">
                                {$tag['title']}
                            </a>
                            {$this->generateTableOfContent($tag['children'])}
                        </li>";
        }

        return $output . $this->listTagCloser($selectedNumberingFormat);
    }

    public function tagCloser($tag)
    {
        $tagLevel = mb_substr($tag, 2, 1);

        return "</h{$tagLevel}>";
    }

    public function listTagCloser($tag)
    {
        $tagLevel = mb_substr($tag, 1, 3);

        return "</{$tagLevel}";
    }

    public function makeId($string)
    {
        return preg_replace('/\s+/', '-', $string);
    }

    function addAnchorToHeadingTags($html)
    {
        $depth = 1;
        $html = preg_replace_callback(
            '/(\<h[1-6](.*?))\>(.*)(<\/h[1-6]>)/i',
            function ($matches) use (&$depth) {
                $level = $depth += 1;
                preg_match_all('/id=\"(.*?)\"/', $matches[0], $string);
                if (!blank($string[1])) {
                    $id = $level . $this->makeId($string[1][0]);
                    $matches[1] = preg_replace("/" . $string[0][0] . "/", "id=" . $id, $matches[1]);
                    $hash_link = '<span id="' . $id . '" class="anchor"
                     style="cursor: pointer;margin-left: 10px;"
                    onclick="copyTitleToClipboard(`' . $id . '`)">#</span>';
                } else {
                    $id = $this->makeId($matches[3]);
                    $matches[1] = $matches[1] . " id=" . $level . $id;
                    $hash_link = '<span id="' . $level . $id . '" class="anchor"
                     style="cursor: pointer;margin-left: 10px;"
                    onclick="copyTitleToClipboard(`' . $level . $id . '`)">#</span>';
                }
                return $matches[1] . ">" . $matches[3] . $hash_link . $matches[4];
            },
            $html
        );

        return $html;
    }

    public function tocWithoutHierarchy($tagsArray)
    {
        $selectedNumberingFormat = $this->configs['tocNumbering'] == true ? '<ol>' : '<ul>';
        $output = $selectedNumberingFormat;

        foreach ($tagsArray as $tag) {
            $output .= "<li>
                            <a href=\"#{$tag['id']}\">
                                {$tag['title']}
                            </a>
                        </li>";
        }

        return $output . $this->listTagCloser($selectedNumberingFormat);

    }
}
