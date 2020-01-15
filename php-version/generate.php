<?php
// http://no-cors-sc.appspot.com/?mime=application/x-apple-aspen-config&url=https://pastebin.com/raw/5VhDuZD1
$file = $_FILES['image'];
$fullscreen = $_POST['fullscreen'];
$name = $_POST['name'];
$description = $_POST['description'];
$identifier = $_POST['identifier'];
$url = $_POST['url'];
$removalAllowed = $_POST['removalAllowed'];
if(isset($file, $name, $description, $identifier, $url)){
    $allowed_ext= array('png');
    $file_name = $file['name'];
    $file_ext = strtolower( end(explode('.',$file_name)));
    $file_tmp= $file['tmp_name'];
    if(in_array($file_ext,$allowed_ext) === false){
        echo "Extension not allowed. Accepted extension: png";
        exit();
    }
    $dims = getimagesize($file_tmp);
    if($dims[0] != $dims[1]){
        echo "Not an icon. Width must equal height";
        exit();
    }
    
    header('Content-Type: application/x-apple-aspen-config');
    // header('Content-Type: text/plain');
    $data = file_get_contents($file_tmp);
    $base64 = base64_encode($data);
    $template = file_get_contents('template.plist');
    if(isset($fullscreen)){
        $fullscreen = "true";
    }else{
        $fullscreen = "false";
    }
    if(isset($removalAllowed)){
        $removal = "true";
        $disallowRemoval = "false";
    }else{
        $removal = "false";
        $disallowRemoval = "true";
    }
    $template = str_replace("FULLSCREENBOOLEAN", $fullscreen, $template);
    $template = str_replace("ICONBASE64", $base64, $template);
    $template = str_replace("REMOVABLEBOOLEAN", $removal, $template);
    $template = str_replace("TITLE", $name, $template);
    $template = str_replace("DESCRIPTION", $description, $template);
    $template = str_replace("IDENTIFIER", $identifier, $template);
    $template = str_replace("ORGANIZATION", "The Cheng Organization", $template);
    $template = str_replace("URLLOCATION", $url, $template);
    $template = str_replace("DISALLOW", $disallowRemoval, $template);
    echo $template;
}else{
    header("Location: /");
    echo "<html><head><meta http-equiv='refresh' content='0;url=/'></head><body><h1>Data not posted</h1></body></html>";
}

?>
