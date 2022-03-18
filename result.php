<?php

require_once "classes/Novel.php";

$path = isset($_POST["path"]) ? $_POST["path"] : null;
$msg = "";
$list = get_list();
check_unified_text($list, $path);

function delete_text($file){
    if(file_exists($file)){
        $bool = unlink($file);
        echo $bool ? "Deleted: " . $file : "Not deleted: " . $file;
        echo "<br>";
    } else {
        echo "Not found: " . $file . "<br>";
    }
}

function delete_texts($path){
    $chapters = "novels/" . $path . "/chapters.txt";
    $list = "novels/" . $path . "/list.txt";
    delete_text($chapters);
    delete_text($list);
    foreach (glob("novels/" . $path . "/txts/*.txt") as $file) {
        delete_text($file);
    }
}

function separate_once($title, $path){
    $unified = "novels/" . $path . "/unified.txt";
    delete_texts($path);
    if(file_exists($unified)){
        $lines = file($unified);
        $novel = new Novel($title, $path);
        $novel->separate_unified_text(1, $lines);
        echo "Separated: " . $unified . ".<br>";
//        return "Separated: " . $unified . ".";
    } else {
        echo "404 NOT FOUND: " . $unified . ".<br>";
//        return  "404 NOT FOUND: " . $unified . ".";
    }
}

function check_unified_text($list, $path){
//    $results = [];
    if($path === null || $path === "all"){
        foreach ($list as $item){
            separate_once($item["title"], $item["path"]);
//            array_push($results, $result);
        }
    } else {
        separate_once("Untitled", $path);
//        array_push($results, $result);
    }
//    return $results;
}

function get_list (){
    $list = "novels/novels_list.txt";
    $separated = [];
    if(file_exists($list)){
        $temp_array = file($list);
        foreach ($temp_array as $line){
            $temp = explode("|", $line);
            $temp[1] = str_replace(["\r", "\n", "\r\n", " "], "", $temp[1]);
            array_push($separated, [
                "title" => $temp[0],
                "path" => $temp[1]
            ]);
        }
        echo "Got: " . $list;
        echo "<br>";
//        return $separated;
    } else {
        echo "Not found: " . $list;
//        return ["404 NOT FOUND: " . $list];
    }
    return $separated;
}

