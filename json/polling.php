<?php

    @header("Content-Type:application/json;charset=utf-8");
    require_once $_SERVER['DOCUMENT_ROOT']."/components/_util.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/components/_imap.php";

    if(!isset($_SERVER['HTTP_REFERER'])) exit();
    !isset($_GET['u'])?exit():$_inbox=$_GET['u'];
    
    $rpmail = new rpmail();
    
    $rpmail->setInbox($_inbox);
    if(in_array('204', $rpmail->getInbox()) == false) {
            
        $data["code"] = 200;
        $data["msg"] = "자료를 읽었습니다.";
        $i = 0;
        $j = 1;
        foreach($result = $rpmail->getInbox() as $overview) {
            if($overview->seen == '0') 
                $i++;
        }
        $data["data"] = array(
            "Unseen" => $i
        );
    } else {   
        $data["code"] = 404;
        $data["msg"] = "통신에 문제가 있습니다.";
        $data["data"] = array("Unseen" => 0);
    }


    print_r(json_encode($data), false);
    $rpmail->close();

?>