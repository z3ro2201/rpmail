<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="-1">
        <title>메일시스템</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
        <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
        <link rel="stylesheet" href="/assets/styles/rpcms_v3.css?<?php echo time(); ?>"/>
        <style>
            section#login{width:400px;text-align:center;}
            div#loginForm{margin:0 auto;width:100%;}
            button{width:100%;border-radius:3px;height:40px;margin:10px 0 0;font-size:1.083em;font-weight:bold;border:0;}
            button#btnLogin{background:#074580;color:#fff;}
            div#loginForm > button#btnLostinfo{background:#fff;color:#074580;border:1px solid #074580}
            h1.logo{margin-bottom:16px;}
            h1.logo span.blind{display:inline-block;width:155px;height:44px;background:url(/assets/images/logo.png);background-repeat:none;text-indent:-9999px}
            h1.logo span.blind.railplanet{background-position:-22px -237px;}
            div.item_inp{display:block}
            div.item_inp label.blind{display:inline-block;position:absolute;text-indent:-9999px}
            div.item_inp input{display:inline-block;width:100%;border:1px solid #ddd;font-size:1em;padding:10px 15px;margin:5px 0px;}
            div.item_inp input:focus{outline:1px solid var(--railplanet-box-color);}
            div.item_inp input:focus::placeholder{font-weight:bold;color:var(--railplanet-box-color)}

            div.item_tip{width:100%;padding:5px 10px;font-size:.9em;text-align:left;margin:20px auto;border:1px solid #b2d2e7;background:rgba(51, 169, 252, .1)}
        </style>
    </head>
    <body>
        <div id="container" class="alignedCenter">
            <section id="login">
                <h1 class="logo">
                    <span class="blind railplanet">railplanet</span>
                </h1>
                <form id="rpcmsForm" action="" onsubmit="return chkForm();" method="post">
                    <div id="loginForm">
                        <div class="item_inp">
                            <label for="" class="blind">아이디</label>
                            <input type="text" id="txtUid" class="txtUid" placeholder="아이디">
                        </div>
                        <div class="item_inp">
                            <input type="password" id="txtUpw" class="txtUpw" placeholder="비밀번호">
                        </div>
                        <button type="submit" id="btnLogin" onclick="">로그인</button>
                        <button type="button" id="btnLostinfo" style="">비밀번호 찾기</button>
                    </div>
                </form>
                <div class="item_tip">
                    ※ 시스템 사용신청은 업무포털에서 가능합니다.
                </div>
            </section>
            <script>
                function chkForm(){
                    const uid = document.querySelector('#txtUid');
                    const upw = document.querySelector('#txtUpw').value.trim();
                    if(uid.value.trim() === "") {
                        uid.value = "";
                        alert('아이디가 입력되지 않았습니다.');
                        uid.focus();
                        return false;
                    }
                    if(upw.value.trim() === "") {
                        upw.value = "";
                        alert('비밀번호가 입력되지 않았습니다.');
                        upw.focus();
                        return false;
                    }

                    document.querySelector('#rpcmsForm').setAttribute('action', '/loginProc.php');
                    return true;
                }
            </script>
        </div>
    </body>
</html>