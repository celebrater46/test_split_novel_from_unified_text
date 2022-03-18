<?php

require_once "classes/Novel.php";

$path = isset($_POST["path"]) ? $_POST["path"] : null;
$msg = "";
$list = get_list();
$results = check_unified_text($list, $path);

function delete_text($file){
    $bool = unlink($file);
    echo $bool ? "Deleted: " . $file : "Not deleted: " . $file;
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
        return "Separated: " . $unified . ".";
    } else {
        return  "404 NOT FOUND: " . $unified . ".";
    }
}

function check_unified_text($list, $path){
    $results = [];
    if($path === null || $path === "all"){
        foreach ($list as $item){
            $result = separate_once($item["title"], $item["path"]);
            array_push($results, $result);
//            if(file_exists($unified)){
//                $lines = file($unified);
//                $novel = new Novel($item["title"], $item["path"]);
//                $novel->separate_unified_text(1, $lines);
//                array_push($results, "Separated: " . $unified . ".");
//            } else {
//                array_push($results, "404 NOT FOUND: " . $unified . ".");
//            }
        }
    } else {
        $result = separate_once("Untitled", $path);
        array_push($results, $result);
    }
    return $results;
}

function get_list (){
    $list = "novels/novels_list.txt";
    if(file_exists($list)){
        $temp_array = file($list);
        $separated = [];
        foreach ($temp_array as $line){
            $temp = explode("|", $line);
            $temp[1] = str_replace(["\r", "\n", "\r\n", " "], "", $temp[1]);
            array_push($separated, [
                "title" => $temp[0],
                "path" => $temp[1]
            ]);
        }
        return $separated;
    } else {
        return ["404 NOT FOUND: " . $list];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Result</title>
</head>
<body>
    <h1>Result</h1>
    <p><?php var_dump($results); ?></p>
</body>
</html>
