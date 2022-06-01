<?php

    @header("Content-Type:application/json;charset=utf-8");
    require_once $_SERVER['DOCUMENT_ROOT']."/components/_util.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/components/_imap.php";


    if(!isset($_SERVER['HTTP_REFERER'])) exit();

    $rpmail = new rpmail();

    $_inbox = null;
    $_mid = null;

    !isset($_GET['u'])?exit():$_inbox=$_GET['u'];
    !isset($_GET['mid'])?exit():$_mid=$_GET['mid'];
    
    $rpmail->setInbox($_inbox);

    if($rpmail->existMailCheck($_mid) == 0)
    {
        $push_msg["code"] = "404";
        $push_msg["msg"] = "삭제되거나 없는 메일입니다.";
        $_jsondata = $push_msg;
        print_r(json_encode($_jsondata));
        $rpmail->close();
        exit();
    }
    
    switch($rpmail->getMailHeader($_mid, "header")[0]->flagged) {
        case 0: 
            $rpmail->changeFlag($_mid, "\\Flagged");
            break;
        case 1:
            $rpmail->clearFlag($_mid, "\\Flagged");
            break;
        default:
            echo "Not flugged";
    }



    $data["code"] = 200;
    $data["msg"] = "자료를 읽었습니다.";
    $data["data"] = array("
        Flagged" => $rpmail->getMailHeader($_mid, "header")[0]->flagged
    );

    
    print_r(json_encode($data), false);
    $rpmail->close();
?>