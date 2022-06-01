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
        <!--<script src="/assets/scripts/app.js?<?php echo time();?>"></script>-->
        <script>
            const u = "";
            const qs = window.location.search.substr(1);
        </script>
        <script>
            let newMail = null;
            
            const ready = (callback) => {
                if(document.readyState != "loading") callback();
                else document.addEventListener("DOMContentLoaded", callback);
            }

            function openPage(url, method, loading=false, data=null) {
                if(loading == true) document.querySelector('#loading').style.display = "block";
                return new Promise ((resolve, reject) => {
                    let xmlHttp = new XMLHttpRequest();
                    xmlHttp.onload = function() {
                        if (xmlHttp.readyState === XMLHttpRequest.DONE && xmlHttp.status === 200) resolve(xmlHttp.response);
                        else reject({status: xmlHttp.status, statusText: xmlHttp.statusText});
                    }
                    xmlHttp.open(method, url, true);
                    xmlHttp.send();
                });
            }

            function openView(mid) {
                openPage("/layout/view.html?<?php echo time();?>", "GET", true).then((response) => {
                    const mHtml = response;
                    openPage(`/json/read.php?u=${u}&mid=${mid}`, "GET").then((data) => {
                        const json = JSON.parse(data);
                        document.querySelector("#mContent").innerHTML = mHtml;
                        if(json['code'] !== 200 && json['rows'] === 0) {              
                            return false;
                        }
                        if(json['attachment'] !== undefined) {
                            let fList = `<article class="fileList"><span class="fTitle"><i class="las la-paperclip"></i>일반 첨부파일</span>`;
                            fList += "<ul>\n";
                            json['attachment'].forEach((ele, idx) => {
                                ele.forEach((fdata, fidx) => {
                                    fList += `<li><a href="/download.php?u=${u}&mid=${mid}&fid=${fdata.fid}&fname=${fdata.filename}" target="blank"><i class="las la-download"></i>${fdata.filename} <span class="fsize">${fdata.size}</span></a></li>`;
                                })
                                //document.querySelector('.mView').innerHTML += `<p>${ele['filename']}</p>`;
                            });
                            fList += "</ul>\n</article>\n";
                            document.querySelector('.mViewBody').innerHTML += fList;
                        }
                        document.querySelector('.mSubject').innerHTML = json['data'].Subject;
                        document.querySelector('.mSendDate').innerHTML = json['data'].Date;
                        document.querySelector('.mSender').innerHTML = json['data'].Sender;
                        document.querySelector('.mTo').innerHTML = json['data'].To;
                        document.querySelector('.mViewBody').innerHTML += json['data'].Content;
                        document.querySelector('.delete').setAttribute('onclick', `return flaggedChange('${u}', '${json['data'].mid}', 'deleted')`);
                        document.querySelector('#loading').style.display = "none";
                        document.querySelector('.mView').style.display = "block";
                        document.querySelector('#loading').style.display = "none";
                    });
                })
            }

            function openList(oInbox="", oParameter="") {
                openPage("/layout/list.html?<?php echo time();?>", "GET", true).then((response) => {
                    const mHtml = response;
                    oParameter=""?oParameter="":oParameter="&" + oParameter;
                    openPage("/json/list.php?oInbox=" + u + oParameter, "GET").then((data) => {
                        const json = JSON.parse(data);
                        document.querySelector("#mContent").innerHTML = mHtml;
                        if(json['code'] !== 200 || json['rows'] === 0) {
                            document.querySelector('.mList').innerHTML = `<li class="empty"><div><i class="las la-envelope-open"></i><span>메일이 없습니다.</span></div></li>`;
                            document.querySelector('#loading').style.display = "none";
                            return false;
                        }
                        json['data'].forEach((element, idx) => {
                            let seenico = null;
                            let flaggedico = null;
                            element.Seen==0?seenico="":seenico="-open";
                            element.Flagged==0?flaggedico="":flaggedico=" yellow";
                            document.querySelector('.mList').innerHTML += `
                                <li class="list-${element.mid}">
                                    <span class="mChkbox"><input type="checkbox" name="chkContent" id="chkContent" value="${element.mid}"></span>
                                    <span class="mFlagged"><a href="javascript:;" onclick="return flaggedChange('${u}', '${element.mid}', 'flagged')"><i class="fa-solid fa-star${flaggedico}"></i></a></span>
                                    <span class="mRead"><i class="fa-solid fa-envelope${seenico}"></i></span>
                                    <span class="mSender">${element.Sender}</span>
                                    <span class="mSubject"><a href="javascript:;" class="linkMail" onclick="return openView('${element.mid}');">${element.Subject}</a></span>
                                    <span class="mDate">${element.Date}</span>
                                    <span class="mDataSize">${element.Size}</span>
                                </li>`;
                        });
                        document.querySelector('#loading').style.display = "none";
                    });
                })
            }

            function flaggedChange(uid,mid) {
                const xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                        if(xmlhttp.status == 200) {
                            const data = JSON.parse(xmlhttp.responseText);
                            data['data'].Flagged==1?document.querySelector('span.mFlagged a i').classList.add('yellow'):document.querySelector('span.mFlagged a i').classList.remove('yellow')
                        }
                    }
                };
                xmlhttp.open("GET", "/json/changeFlagged.php?u=" + uid + "&mid=" + mid, true);
                xmlhttp.send();
            }

            function deleteMail(uid,mid) {
                const xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                        if(xmlhttp.status == 200) {
                            const data = JSON.parse(xmlhttp.responseText);
                            data['data'].Flagged==1?document.querySelector('span.mFlagged a i').classList.add('yellow'):document.querySelector('span.mFlagged a i').classList.remove('yellow')
                        }
                    }
                };
                xmlhttp.open("GET", "/json/deleteMail.php?u=" + uid + "&mid=" + mid, true);
                xmlhttp.send();
            }

            function polling() {
                openPage(`/json/polling.php?u=${u}`, "GET").then((response) => {
                    const data = JSON.parse(response);
                    newMail = data['data'].Unseen;
                    if(data['code'] !== 200) return false;
                    document.querySelector('.notRead').innerHTML = data['data'].Unseen;
                });

            }
            
            ready(() => {
                polling();
                setInterval(() => { polling();}, 20000);
            });
        </script>
    </head>
    <body>
        <div id="container">
            <header id="rpcms_header">
                <h1 class="logo">
                    <a href="javscript:;">
                        <span class="txtLogo">rm</span>
                        <span class="systemText">메일</span>
                    </a>
                </h1>
            </header>
            <main id="rpcms_content">
                <aside id="mSide">
                    <div class="menuBtn">
                        <a href="/write.php" class="sideBtn">메일쓰기</a>
                        <a href="/write.php" class="sideBtn">내게쓰기</a>
                    </div>
                    <ul class="quickLink">
                        <li>
                            <a href="/list.php?oParameter=uSEEN" onclick="return openListPage('newmail');">
                                <span class="notRead">0</span>
                                안 읽음
                            </a>
                        </li>
                        <li>
                            <a href="/list.php?oParameter=Flagged" onclick="return openListPage('flagged');">
                                <span><i class="fa-solid fa-star"></i></span>
                                중요
                            </a>
                        </li>
                    </ul>
                    <nav class="mBoxList">
                        <a href="/list.php" class="mBoxLink">
                            <span>받은 메일함</span>
                        </a>
                        <a href="/list.php" class="mBoxLink">
                            <span>보낸 메일함</span>
                        </a>
                        <a href="/list.php" class="mBoxLink">
                            <span>휴지통</span>
                        </a>
                    </nav>
                </aside>
                <section id="mBody">
                    <div id="loading">
                        <div id="loadingMain">
                            <div class="loadingBody">
                                <div class="loadingSpin"></div>
                                <div class="loadingText">잠시만 기다려 주십시요.</div>
                            </div>
                        </div>
                    </div>
                    <section id="mContent">