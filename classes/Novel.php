<?php

require_once "Chapter.php";
require_once "Episode.php";

class Novel
{
    public $title;
    public $path;
    public $chapters = [];

    function __construct($title, $path){
        $this->title = $title;
        $this->path = $path;
//        $this->separate_unified_text(1, $lines);
    }

    function separate_unified_text($ep_id, $lines){
//        $ep_id = 1;
        $txt = __DIR__ . "novels/" . $this->path . "/txts/" . $ep_id . ".txt";
        $chapters_txt = __DIR__ . "novels/" . $this->path . "/chapters.txt";
        $subtitle_txt = __DIR__ . "novels/" . $this->path . "/list.txt";
        $new_lines = "";
        $chapter_regex = "/<Chapter>(.*)<\/Chapter>/i";
        $subtitle_regex = "/<Sub>(.*)<\/Sub>/i";
        $br_regex = "/<Break(.*)\/>/i";
        for($i = 0; $i < count($lines); $i++){
            if(preg_match($br_regex, $lines[$i])){
                if($i < count($lines) - 1){
                    error_log($new_lines, 3, $txt);
                    $this->separate_unified_text(
                        $ep_id + 1,
                        array_slice($lines, $i + 1)
                    );
                    break;
                }
//                return $i < count($lines) - 1 ? $i + 1 : null;
            } else if(preg_match($chapter_regex, $lines[$i])){
//                array_push($chapters, new Chapter($lines[$i]));
                $chapter = preg_replace(
                    $chapter_regex,
                    "$1",
                    $lines[$i]
                );
                error_log(
                    $chapter . "\n",
                    3,
                    $chapters_txt
                );
                array_push($this->chapters, $chapter);
            } else if(preg_match($subtitle_regex, $lines[$i])){
                $subtitle = preg_replace(
                    $subtitle_regex,
                    "$1",
                    $lines[$i]
                );
                $str = count($this->chapters) . "|" . $ep_id . "|" . $subtitle;
                error_log(
                    $str . "\n",
                    3,
                    $subtitle_txt
                );
            }
            $new_lines .= $lines[$i];
//            error_log($lines[$i], 3, $txt);
        }
//        foreach ($lines as $line){
////            $title_regex = "/<Title>(.*)<\/Title>/i";
//            $chapter_regex = "/<Chapter>(.*)<\/Chapter>/i";
//            $br_regex = "/<Break(.*)\/>/i";
//            if(preg_match($br_regex, $line)){
//                break;
//            } else if(preg_match($chapter_regex, $line)){
//                array_push($chapters, new Chapter($line));
////                error_log($msg . "\n", 3, $path);
//            }
//            $new_lines .= $line;
//            $ep_id++;
//        }
    }
}