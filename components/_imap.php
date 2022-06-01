<?php

/*

    imap을 활용한 메일 데이터 가져오기

        -  make by. 2ero (https://2ero.dev)
           최초작성 2022.04.23 (Leagcy)
           구조화 2022.04.26 (Object)

    */
class rpmail {
    private $_host = "";
    private $_mode = "";
    private $_userid = "";
    private $_userpw = "";
    private $_inbox = "";


    public $_conn;
    private $attachment;

    // IMAP 접속
    public function __construct() {
        $_conn = imap_open('{'.$this->_host.'/'.$this->_mode.'}'.$this->_inbox, $this->_userid, $this->_userpw, NULL, 1,array('DISABLE_AUTHENTICATOR' => array('GSSAPI','NTLM')));

        return $this->_conn = $_conn;
    }

    public function connect()
    {
        return $this->_conn;
    }

    // 계정 메일함으로 변경
    public function setInbox($id) {
        return imap_reopen($this->_conn, '{'.$this->_host.'/'.$this->_mode.'}'.$id);
    }

    // 메일함에 있는 목록 가져옴
    public function getInbox() {
        //$mbox = $this->connect();
        $mc = imap_check($this->_conn);
        $headers = imap_fetch_overview($this->_conn, "1:{$mc->Nmsgs}", 0);
        if($mc->Nmsgs == 0) imap_close($this->_conn);

        return $headers;
    }

    // 메일 체크
    public function existMailCheck($mid) {
        return imap_uid($this->_conn, $mid);
    }

    // 메일 본문의 정보를 가져옴
    public function getMailHeader($mid) {
        return imap_fetch_overview($this->_conn, $mid, 0);
    }

    // 메일 내용을 가져옴
    public function getMailData($mid) {
        $data = array();
        $data1 = null;
        $structure = imap_fetchstructure($this->_conn, $mid);
        $fileList = array();
        $file = 0;
        if($structure->subtype !== "MIXED") { 
            array_push($data, imap_base64(imap_fetchbody($this->_conn, $mid, 1)));
        } else {
            $msg = 0;
            $file = 0;
            // MESSAGE
            foreach($structure->parts as $ele) {
                if($ele->type !== 0 && ($ele->subtype !== "HTML" || $ele->subtype !== "PLAIN")) {
                    $msg++;
                }
                $section= "1.".$msg;
                $message = imap_fetchbody($this->_conn, $mid, $section);
                if($message == null) $message = imap_fetchbody($this->_conn, $mid, 1);
                $message =imap_base64($message);
                
            }

            // FILE
            $i = 0;
            foreach($structure->parts as $ele) {
                if(isset($ele->dparameters) && isset($ele->parameters)) {
                    $attachment["fid"] = $file;
                    $attachment["ftype"] = $ele->subtype;
                    $attachment["encoding"] = $ele->encoding;
                    $attachment["filename"] = iconv_mime_decode($ele->dparameters[0]->value);
                    $attachment["name"] = iconv_mime_decode($ele->parameters[0]->value);
                    $attachment["size"] = formatSizeUnits($ele->bytes);
                    /*switch($attachment["encoding"]) {
                        case 0:
                            $attachment["data"] = imap_base64(imap_binary(imap_qprint(imap_8bit($fdata))));
                            break;
                        case 1:
                            $attachment["data"] = imap_base64(imap_binary(imap_qprint(imap_8bit($fdata))));
                            break;
                        case 2:
                            $attachment["data"] = imap_base64(imap_binary($fdata));
                            break;
                        case 3:
                            $attachment["data"] = imap_base64($fdata);
                            break;
                        case 4:
                            $attachment["data"] = imap_base64(imap_binary($fdata));
                            break;
                        default:
                            $attachment["data"] = $fdata;
                    }*/
                    array_push($fileList, $attachment);
                }
                $file++;
            }
            array_push($data, $message, $fileList);
           
        }
        //$data = $structure;
        return $data;
    }

    public function printbody($mid, $part, $encode, $mime, $fname = NULL) {
        $data = imap_fetchbody($this->_conn, $mid, $part);
        switch($encode) {
            case 0:
                $data = imap_base64(imap_binary(imap_qprint(imap_8bit($data))));
                break;
            case 1: //8bit
                $data = imap_base64(imap_binary(imap_qprint(imap_8bit($data))));
                break;
            case 2: //binary
                $data = imap_base64(imap_binary($data));
                break;
            case 3: //base64
                $data = "11";//imap_base64($data);
                break;
            case 4: // quoted-print
                $data = imap_base64(imap_binary(imap_qprint($data)));
                break;
            case 5: // other
                echo "알수없는 encoding";
                exit;
        }

        switch($mime) {
            case "PLAIN":
                return str_replace("\n", "<br>", $data);
                break;
            case "HTML": 
                return $data;
                break;
            default: // 첨부파일
                return "<br> FILE: <a href=\"mail_down.php?u=vitzro2011&mid=$mid\">".$fname."</a>";
        }
    }

    // 이메일 데이터를 다운로드
    public function getFile($mid, $fileid, $filename, $start=1) {
        $structure = imap_fetchstructure($this->_conn, $mid);
        //return $fdata;
        
    }

    public function getFileSize() {
    }

    public function changeFlag($mid, $flag) {        
        return imap_setflag_full($this->connect(), imap_uid($this->_conn, $mid), $flag);
    }

    public function clearFlag($mid, $flag) {        
        return imap_clearflag_full($this->connect(), imap_uid($this->_conn, $mid), $flag);
    }

    public function close() {
        return imap_close($this->_conn);
    }
}
?>