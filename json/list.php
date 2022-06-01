<?php

    @header("Content-Type:application/json;charset=utf-8");
    require_once $_SERVER['DOCUMENT_ROOT']."/components/_util.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/components/_imap.php";

    $rpmail = new rpmail();

    if(!isset($_SERVER['HTTP_REFERER'])) exit();

    !isset($_GET['oInbox'])?exit():$_inbox=$_GET['oInbox'];
    !isset($_GET['oParameter'])?$oParameter=null:$oParameter=true;
    !isset($_GET['oPage'])?$oPage=null:$oPage=true;

    $_mid = null;
    
    $rpmail->setInbox($_inbox);
    if(in_array('204', $rpmail->getInbox()) == false) {
            
        $data["code"] = 200;
        $data["msg"] = "자료를 읽었습니다.";
        $data["ver"] = explode(".", phpversion())[0];
        $i = 0;
        if($oParameter == "uSEEN") {
            foreach($result = $rpmail->getInbox() as $overview) {
                if($overview->seen == '0') {
                    $data["data"][$i] = array(
                        "mid" => $i,
                        "Seen" => $overview->seen,
                        "Sender" => explode(" ", mb_decode_mimeheader($overview->from))[0],
                        "Subject" => mb_decode_mimeheader($overview->subject),
                        "Flagged" => $overview->flagged,
                        "Date" => date('y.m.d H:i', strtotime($overview->date)),
                        "Size" => formatSizeUnits($overview->size)
                    );
                    $i++;
                }
                //var_dump($overview);
            }
        }
        else if($oParameter == "Flagged") {
            foreach($result = $rpmail->getInbox() as $overview) {
                if($overview->flagged == '1') {
                    $data["data"][$i] = array(
                        "mid" => $i,
                        "Seen" => $overview->seen,
                        "Sender" => explode(" ", mb_decode_mimeheader($overview->from))[0],
                        "Subject" => mb_decode_mimeheader($overview->subject),
                        "Flagged" => $overview->flagged,
                        "Date" => date('y.m.d H:i', strtotime($overview->date)),
                        "Size" => formatSizeUnits($overview->size)
                    );
                    $i++;
                }
                //var_dump($overview);
            }
        } else {
            foreach($result = $rpmail->getInbox() as $overview) {
                if($overview->deleted == '0') {
                    $data["data"][$i] = array(
                        "mid" => $i,
                        "Seen" => $overview->seen,
                        "Sender" => explode(" ", mb_decode_mimeheader($overview->from))[0],
                        "Subject" => mb_decode_mimeheader($overview->subject),
                        "Flagged" => $overview->flagged,
                        "Deleted" => $overview->deleted,
                        "Date" => date('y.m.d H:i', strtotime($overview->date)),
                        "Size" => formatSizeUnits($overview->size)
                    );
                }
                $i++;
            }
        }

        $data["rows"] = $i;
    } else {
        $data["code"] = 404;
        $data["msg"] = "메일이 없습니다.";
    }

    print_r(json_encode($data), false);
    $rpmail->close();

?>