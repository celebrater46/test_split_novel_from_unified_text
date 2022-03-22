<?php

class Separator
{
    public $title;
    public $path;
    public $chapters = [];

    function __construct($title, $path){
        $this->title = $title;
        $this->path = $path;
    }

    function separate_unified_text($ep_id, $lines){
        $txt = "novels/" . $this->path . "/txts/" . $ep_id . ".txt";
        $chapters_txt = "novels/" . $this->path . "/chapters.txt";
        $subtitle_txt = "novels/" . $this->path . "/list.txt";
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
                    return;
                }
            } else if(preg_match($chapter_regex, $lines[$i])){
                $chapter = preg_replace(
                    $chapter_regex,
                    "$1",
                    $lines[$i]
                );
                error_log(
                    $chapter,
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
                    $str,
                    3,
                    $subtitle_txt
                );
            }
            $new_lines .= $lines[$i];
        }
        error_log($new_lines, 3, $txt);
    }
}