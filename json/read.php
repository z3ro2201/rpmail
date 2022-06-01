<?php

    @header("Content-Type:application/json;charset=utf-8");
    require_once $_SERVER['DOCUMENT_ROOT']."/components/_util.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/components/_imap.php";


    //if(!isset($_SERVER['HTTP_REFERER'])) exit();

    $rpmail = new rpmail();

    $_inbox = null;
    $_mid = null;

    !isset($_GET['u'])?exit():$_inbox=$_GET['u'];
    !isset($_GET['mid'])?exit():$_mid=$_GET['mid']+1;
    
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
    
    // 메일을 읽음처리
    if($rpmail->getMailHeader($_mid, "header")[0]->seen == 1) $rpmail->changeFlag($_mid, "\\Seen");


    $data["code"] = 200;
    $data["msg"] = "자료를 읽었습니다.";
    if(sizeof($rpmail->getMailData($_mid)) > 1) {
        $data["data"] = array(
            "mid" => $_mid,
            "Subject" => changeText($rpmail->getMailHeader($_mid, "header")[0]->subject),
            "Sender" => changeText($rpmail->getMailHeader($_mid, "header")[0]->from),
            "To" => changeText($rpmail->getMailHeader($_mid, "header")[0]->to),
            "Date" => date('y-m-d H:i:s', strtotime($rpmail->getMailHeader($_mid, "header")[0]->date)),
            "Content" => $rpmail->getMailData($_mid)[0]
        );
        $data["attachment"] = array($rpmail->getMailData($_mid)[1]);
    } else {
        $data["data"] = array(
            "mid" => $_mid,
            "Subject" => changeText($rpmail->getMailHeader($_mid, "header")[0]->subject),
            "Sender" => changeText($rpmail->getMailHeader($_mid, "header")[0]->from),
            "To" => changeText($rpmail->getMailHeader($_mid, "header")[0]->to),
            "Date" => date('y-m-d H:i:s', strtotime($rpmail->getMailHeader($_mid, "header")[0]->date)),
            "Content" => $rpmail->getMailData($_mid)
        );    
    }

        
    print_r(json_encode($data), false);
    $rpmail->close();
?>