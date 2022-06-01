<?php
ini_set('display_errors', 1);
function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) 
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    elseif ($bytes >= 1048576)
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    elseif ($bytes >= 1024)
        $bytes = number_format($bytes / 1024, 2) . ' KB';    
    elseif ($bytes > 1)
        $bytes = $bytes . ' bytes';
    elseif ($bytes == 1)
        $bytes = $bytes . ' byte';
    else
        $bytes = '0 bytes';

    return $bytes;
}

function changeText($text) {
    return htmlspecialchars(mb_decode_mimeheader($text));
}

function alert($msg, $opt=null, $opt2=null) {
    if($opt == 'back') $opt = "history.back(-1);\n";
    else if($opt == 'move') $opt = "location.href='$opt2';\n";
    else $opt = null;
    exit("<script>\nalert('$msg');\n$opt</script>");
}
?>