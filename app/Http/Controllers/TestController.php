<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    public function index()
    {
        $html = "
            <h3>hello h1</h3>
            <h2>hello h2</h2>
            <h2>hello h2</h2>
            <h5>hello h5</h5>
            <h3>hello h3</h3>
            <h4>hello h4</h4>
            <h5>hello h5</h5>
            <h6>hello h6</h6>
            <h1>hello h1</h1>
            <h2>hello h2</h2>
            <h5>hello h5</h5>
            <h3>hello h3</h3>
        ";

        print_r($this->getToc($html));
    }

    function getToc($html)
    {
        $supportedTags = ["h1", "h2", "h3", "h4", "h5", "h6"];
        $tagsByNumber = str_replace("h", "", $supportedTags);
        $tags = implode(",", $tagsByNumber);
        $hierarchy = true;
        $toc = '';
        preg_match_all('/(<h([' . $tags . ']{1})[^>]*>).*<\/h\2>/msuU', $html, $matches, PREG_SET_ORDER);
        if ($matches) {
            $arr = [1,2,3,4,5,6];
            $supportedLastTag = end($arr);
            $firstMatch = $matches[0][2];
            $currentMatch = $firstMatch;    // headings can't be larger than h6 but 100 as a default to be sure
            $numberedItems = array();
            $depthMatch = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0);
            $numberedItemsMin = null;
            $levelDepth = 1;    //default 1
            $depthMatch[$firstMatch] = $levelDepth;
            $maxMatch = 1;      //default 1
            $maxDepth = 1;      //default 1

            if ($hierarchy === true) {
                $toc .= "<ol>";

                // find the minimum heading to establish our baseline
                foreach ($matches as $i => $match) {
                    if ($currentMatch < $firstMatch) {
                        $currentMatch = $firstMatch;
                    } elseif ($currentMatch > $matches[$i][2]) {
                        $currentMatch = (int)$matches[$i][2];
                    }
                    if ($matches[$i][2] > $maxMatch) {
                        $maxMatch = $matches[$i][2];
                    }
                }

                $numberedItems[$currentMatch] = 0;

                $numberedItemsMin = $currentMatch;

                dump([
                    "currentMatch"=>$currentMatch,
                    "maxMatch"=>$maxMatch,
                    "firstMatch"=>$firstMatch,
                    "numberedItems"=>$numberedItems,
                    "numberedItemsMin"=>$numberedItemsMin,
                    "matches"=>$matches,
                ]);


                foreach ($matches as $i => $match) {
                    $level = $matches[$i][2];
                    $nextLevel = isset($matches[$i + 1][2]) ? $matches[$i + 1][2] : $firstMatch;

                    if ($depthMatch[(int)$matches[$i][2]] !== 0) {
                        $depthMatch[(int)$matches[$i][2]] = $levelDepth;
                    }
                    $depthStatus = ($supportedLastTag === $level || $nextLevel < $level) ? 'stop' : 'continue';
                    if ($currentMatch == (int)$matches[$i][2]) {
                        $toc .= '<li class="level-' . $currentMatch . '">';
                    }

                    // start lists
                    if ($currentMatch != (int)$matches[$i][2]) {
                        $diff = $currentMatch - (int)$matches[$i][2];
                        for (
                            $currentMatch;
                            $currentMatch < (int)$matches[$i][2];
                            $currentMatch = $currentMatch - $diff
                        ) {
                            if ($depthStatus == 'continue') {
                                $levelDepth++;
                            }
                            $depthMatch[(int)$matches[$i][2]] = $levelDepth;
                            if (($matches[$i][2] == $maxMatch)) {
                                $maxDepth = $levelDepth;
                            }
                            $numberedItems[$currentMatch + 1] = 0;
                            $toc .= '<ol class="level-' . $level . '"><li class="level-' . $level . '">';
                        }
                    }

                    $title = $matches[$i][0];
                    $title = strip_tags($title);
                    $hasId = preg_match('/id=(["\'])(.*?)\1[\s>]/si', $matches[$i][0], $matchedIds);
                    $id = $hasId ? $matchedIds[2] : $i . '-toc-title';

                    $toc .= '<a href="#' . $id . '">' . $title . '</a>';

                    // end lists
                    if ($i != count($matches) - 1) {
                        $nextMatch = (int)$matches[$i + 1][2];
                        $diff = $currentMatch - $nextMatch;
                        $levelDepthDiff = $levelDepth - $depthMatch[$nextMatch];

                        if ($currentMatch > $nextMatch && $levelDepth == 1 && $nextMatch !== 1) {
                            $loop = $diff - $nextMatch;
                            for ($g = 0; $g < $loop; $g++) {
                                $toc .= '</li></ol>';
                                $numberedItems[$currentMatch] = 0;
                                $levelDepth--;
                            }
                            $currentMatch = $nextMatch;
                        } elseif ($currentMatch > $nextMatch && $diff > 1 && $levelDepth > $levelDepthDiff) {
                            for ($currentMatch; $currentMatch > $nextMatch; $currentMatch = $currentMatch - $diff) {
                                for ($k = 0; $k <= $levelDepthDiff; $k++) {
                                    $toc .= '</li></ol>';
                                    $numberedItems[$currentMatch] = 0;
                                }
                                $levelDepth = $levelDepth - $levelDepthDiff;
                            }
                        } elseif ($currentMatch > $nextMatch && $diff > $maxDepth) {
                            for ($currentMatch; $currentMatch > $nextMatch; $currentMatch = $currentMatch - $maxDepth) {
                                $toc .= '</li></ol>';
                                $numberedItems[$currentMatch] = 0;
                                $levelDepth--;
                            }
                        } elseif ($currentMatch > $nextMatch && $nextMatch === 1) {
                            for ($currentMatch; $currentMatch > $nextMatch; $currentMatch--) {
                                $toc .= '</li></ol>';
                                $numberedItems[$currentMatch] = 0;
                                $levelDepth--;
                            }
                        } elseif ($currentMatch > $nextMatch && $nextMatch === 2) {
                            for ($currentMatch; $currentMatch > $nextMatch; $currentMatch--) {
                                $toc .= '</li></ol>';
                                $numberedItems[$currentMatch] = 0;
                                $levelDepth--;
                            }
                        }

                        if ($levelDepth < 1) {
                            $levelDepth = 1;
                        }

                        if ($currentMatch == $nextMatch) {
                            $toc .= '</li>';
                        }
                    } else {
                        // this is the last item, make sure we close off all tags
                        for ($currentMatch; $currentMatch >= $levelDepth * $maxDepth; $currentMatch--) {
                            $toc .= '</li>';
                            if ($currentMatch != $numberedItemsMin) {
                                $toc .= '</ol>';
                            }
                        }
                    }
                }
            } else {
                $toc .= '<ol class="">';
                foreach ($matches as $i => $match) {
                    $count = $i + 1;
                    $toc .= '<li>';
                    $title = $matches[$i][0];
                    $title = strip_tags($title);
                    $has_id = preg_match('/id=(["\'])(.*?)\1[\s>]/si', $matches[$i][0], $matched_ids);
                    $id = $has_id ? $matched_ids[2] : $i . '';
                    $toc .= '<a href="#' . $id . '">' . $title . '</a>';
                    $toc .= '</li>';
                }
                $toc .= '</ol>';
            }
        }
        return $toc ;
    }
}
