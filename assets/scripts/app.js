let newMail=null;const qs=window.location.search.substr(1),ready=e=>{"loading"!=document.readyState?e():document.addEventListener("DOMContentLoaded",e)};function openViewPage(e){if(init(),void 0===e||null==e||""==e)return alert("잘못된 링크입니다."),!1;const t=new XMLHttpRequest;t.onreadystatechange=function(){t.readyState==XMLHttpRequest.DONE&&(200==t.status?(document.querySelector("#mBody").innerHTML=t.responseText,readmail(e)):400==t.status?alert("There was an error 400"):alert("something else other than 200 was returned"))},t.open("GET","/layout/view.html?<?php echo time();?>",!0),t.send()}function openWritePage(){init(),document.querySelector(".loadingText").textContent="작성페이지를 불러오고 있습니다.";const e=new XMLHttpRequest;e.onreadystatechange=function(){if(e.readyState==XMLHttpRequest.DONE)if(200==e.status){document.querySelector("#mBody").innerHTML=e.responseText;const t=[];nhn.husky.EZCreator.createInIFrame({oAppRef:t,elPlaceHolder:"ir1",sSkinURI:"/assets/plugins/se2/SmartEditor2Skin.html",fCreator:"createSEditor2"}),fadeOut("#loading")}else 400==e.status?alert("There was an error 400"):alert("something else other than 200 was returned")},e.open("GET","/layout/write.html",!0),e.send()}function flaggedChange(e,t){const n=new XMLHttpRequest;n.onreadystatechange=function(){if(n.readyState==XMLHttpRequest.DONE&&200==n.status){1==JSON.parse(n.responseText).data.Flagged?document.querySelector("span.mFlagged a i").classList.add("yellow"):document.querySelector("span.mFlagged a i").classList.remove("yellow")}},n.open("GET","/json/changeFlagged.php?u="+e+"&mid="+t,!0),n.send()}function init(){document.querySelector("#mBody").innerHTML="",document.querySelector("#loading").style.display="flex",document.querySelector("#loading").style.opacity=1}function readmail(e){const t=new XMLHttpRequest;t.onreadystatechange=function(){if(t.readyState==XMLHttpRequest.DONE)if(200==t.status){const e=JSON.parse(t.responseText);if(document.querySelector(".loadingText").textContent="메일 정보를 받고 있습니다.",200!==e.code)return alert("해당 메일이 존재하지 않습니다."),!1;document.querySelector(".mSubject").innerHTML=e.data.Subject,document.querySelector(".mSendDate").innerHTML=e.data.Date,document.querySelector(".mSender").innerHTML=e.data.Sender,document.querySelector(".mTo").innerHTML=e.data.To,document.querySelector(".mViewBody").innerHTML=e.data.Content,fadeOut("#loading"),document.querySelector(".mView").style.display="block"}else 400==t.status?alert("There was an error 400"):alert("something else other than 200 was returned")},t.open("GET","/json/read.php?u=vitzro2011&mid="+e,!0),t.send()}function openListPage(e=null){init();const t=new XMLHttpRequest;t.onreadystatechange=function(){t.readyState==XMLHttpRequest.DONE&&(200==t.status?(document.querySelector("#mBody").innerHTML=t.responseText,openList(e)):400==t.status?alert("There was an error 400"):alert("something else other than 200 was returned"))},t.open("GET","/layout/list.html",!0),t.send()}function openList(e=null){const t=new XMLHttpRequest;t.onreadystatechange=function(){if(t.readyState==XMLHttpRequest.DONE)if(200==t.status){const e=JSON.parse(t.responseText);if(document.querySelector(".loadingText").textContent="메일 정보를 받고 있습니다.",200!==e.code)return alert("해당 메일이 존재하지 않습니다."),!1;0!==e.rows?e.data.forEach((e,t)=>{let n=null,a=null;n=0==e.Seen?"":"-open",a=0==e.Flagged?"":" yellow",document.querySelector(".mList").innerHTML+=`\n                                        <li class="list-${e.mid}">\n                                            <span class="mChkbox"><input type="checkbox" name="" id=""></span>\n                                            <span class="mFlagged"><a href="javascript:;" onclick="return flaggedChange('vitzro2011', '${e.mid+1}')"><i class="fa-solid fa-star${a}"></i></a></span>\n                                            <span class="mRead"><i class="fa-solid fa-envelope${n}"></i></span>\n                                            <span class="mSender">${e.Sender}</span>\n                                            <span class="mSubject"><a href="javascript:;" class="linkMail" onclick="return openViewPage('${e.mid}');">${e.Subject}</a></span>\n                                            <span class="mDate">${e.Date}</span>\n                                            <span class="mDataSize">${e.Size}</span>\n                                        </li>`}):document.querySelector(".mList").innerHTML='<li class="empty"><div><i class="las la-envelope-open"></i><span>메일이 없습니다.</span></div></li>',fadeOut("#loading")}else 400==t.status?alert("There was an error 400"):alert("something else other than 200 was returned")},null!==e?t.open("GET","/json/list.php?"+e+"=true",!0):t.open("GET","/json/list.php",!0),t.send()}function polling(){const e=new XMLHttpRequest;e.onreadystatechange=function(){if(e.readyState==XMLHttpRequest.DONE&&200==e.status){const t=JSON.parse(e.responseText);if(newMail=t.data.Unseen,200!==t.code)return!1;document.querySelector(".notRead").innerHTML=t.data.Unseen}},e.open("GET","/json/polling.php?",!0),e.send()}function fadeOut(e){const t=document.querySelector(e),n=setInterval(()=>{t.style.opacity||(t.style.opacity=1),t.style.opacity>0?t.style.opacity-=.1:(clearInterval(n),t.style.display="none")},100)}ready(()=>{polling(),openListPage(),setInterval(()=>{polling()},2e4)});