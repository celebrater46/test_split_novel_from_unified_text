<?php

$msg = "";
$list = get_list();
$result = check_unified_text($list);

//function separate_unified_text($lines){
//    foreach ($lines as $line){
//        if(preg_match("/<Title>(.*)<\/Title>/i", $line)){
//
//        }
//    }
//}

function check_unified_text($list){
//    $results = [];
    foreach ($list as $item){
        $unified = "novels/" . $item["path"] . "/unified.txt";
        if(file_exists($unified)){
            $lines = file($unified);
            $novel = new Novel($item["title"], $item["path"]);
            $novel->separate_unified_text(1, $lines);
            return "Separated: " . $unified . ".";
//            array_push($results, "Separated: " . $unified . ".");
        } else {
            return "404 NOT FOUND: " . $unified . ".";
//            array_push($results, "404 NOT FOUND: " . $unified . ".");
        }
    }
}

function get_list (){
    $list = "novels/novels_list.txt";
    if(file_exists($list)){
        $temp_array = file($list);
        $separated = [];
        foreach ($temp_array as $line){
            $temp = explode("|", $line);
            $temp[1] = str_replace($temp[1], ["\r", "\n", "\r\n", " "], "");
            array_push($separated, [
                "title" => $temp[0],
                "path" => $temp[1]
            ]);
        }
        return [
            "result" => true,
            "array" => $separated
        ];
    } else {
        return [
            "result" => false,
            "array" => ["404 NOT FOUND: " . $list]
        ];
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
    <p><?php echo $result; ?></p>
</body>
</html>
