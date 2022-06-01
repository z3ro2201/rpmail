<?php
include_once $_SERVER['DOCUMENT_ROOT']."/components/_header.php";
?>
<script src="/assets/plugins/se2/js/service/HuskyEZCreator.js" charset="utf-8"></script>
<nav class="mExtMenu">
    <div class="mExtSbMenu">
        <a href="javascript:;" class="btnExt btnSend">보내기</a>
    </div>
</nav>
<article class="mWrite">
    <header class="mWriteHeader">
        <div class="mWriteGroup">
            <span class="mTitle">받는사람</span>
            <div class="rpcmsInput">
                <div id="toArea"></div>
                <input type="text" id="txtTo" name="txtInputTo">
            </div>
        </div>
        <details class="mWriteSee">
            <summary>
                <div class="mWriteGroup">
                    <span class="mTitle">참조</span>
                    <div class="rpcmsInput">
                        <input type="text" id="txtCc" class="">
                    </div>
                </div>
            </summary>
            <div class="mWriteGroup">
                <span class="mTitle">숨은참조</span>
                <div class="rpcmsInput">
                    <input type="text" id="txtBcc" class="">
                </div>
            </div>
        </details>
        <div class="mWriteGroup">
            <span class="mTitle">제목</span>
            <div class="rpcmsInput">
                <input type="text" id="txtSubject" class="">
            </div>
        </div>
    </header>
    <section class="mWriteBody">
        <textarea name="ir1" id="ir1" style="width: 100%;"></textarea>
    </section>
</article>

<script id="smartEditor" type="text/javascript">
    /*let array_to = new Array();
    let array_cc = new Array();
    let array_bcc = new Array();
    function keyevent(event, element) {
        const keyword = element.value;
        const thisNode = element.getAttribute('id');
        const parentNode = element.parentNode.getAttribute('id');
        if(!event) event = window.event;
        const kCd = event.keyCode || event.which || event.key;
        if(kCd === ',' || kCd === 188 || kCd === 13) {
            if(!checkRegex(element.value))
            {
                alert('잘못된 이메일 형식입니다.');
                return false;
            }
            const createNode = document.createElement('div');
            const text = element.value.split(',');
            console.log(uniqueArr.length)
            //if(uniqueArr.length !== 0) return false;
            let arrayText = element.parentNode.children[2];
            createNode.setAttribute('class', 'mAddr blue');
            createNode.innerText = text;
            array_to.push(text);
            element.value = "";
            arrayText.value = "";
            //document.getElementById('txtTo').value = element.value;
            element.parentNode.children[0].append(createNode);
            let i = 0;
            array_to.forEach((addr) => {
                if(i !== 0) arrayText.value += ",";
                arrayText.value += addr
                i++;
            })
            //console.log(array_to)
        
        }
        
    }*/

	let oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
	    oAppRef: oEditors,
	    elPlaceHolder: "ir1",  //textarea ID 입력
	    sSkinURI: "/assets/plugins/se2/SmartEditor2Skin.html",  //martEditor2Skin.html 경로 입력
	    fCreator: "createSEditor2",
	    htParams : { 
            aAdditionalFontList : [['Malgun Gothic', '맑은고딕']],
            SE2M_FontName: {
                htMainFont: {'id': 'Malgun Gothic','name': '맑은고딕', 'url': '','cssUrl': ''} // 기본 글꼴 설정 
            }, 
	        bUseToolbar : true, 
		bUseVerticalResizer : true, 
		bUseModeChanger : false 
	    },
        fOnAppLoad: function(){
            oEditors.getById['ir1'].setDefaultFont( "Malgun Gothic", 12);
        }
	});

    document.querySelector('.btnExt.btnSend').addEventListener('click', () => {
        //oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);
        let email_to = document.querySelector('#txtTo');
        let email_cc = document.querySelector('#txtCc');
        let email_bcc = document.querySelector('#txtBcc');
        
        if(email_to.value.trim() === "") {
            alert('받는 사람의 메일주소를 입력하세요.');
            document.querySelector('#txtTo').focus();
            return false;
        }
        
        if(email_to.value.indexOf(',') !== -1) email_to = email_to.value.split(',');
        if(email_cc.value.indexOf(',') !== -1) email_cc = email_cc.value.split(',');
        if(email_bcc.value.indexOf(',') !== -1) email_bcc = email_bcc.value.split(',');
        

        if(document.querySelector('#txtSubject').value.trim() === "") {
            if(!confirm("제목이 입력되지 않았습니다. 제목을 입력하지 않고 전송하시겠습니까?")) {
                document.querySelector('#txtSubject').focus();
                console.log("입력");
                return false;
            }
        }

        oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);
        console.log(document.querySelector('#ir1').value);
        const data = {
            to: email_to,
            cc: email_cc,
            bcc: email_bcc,
            subject: document.querySelector('#txtTo').value,
            content: document.querySelector('#ir1').value
        }
        console.log(data);
    });

    function checkRegex(str) {
        const reg_email = /^([0-9a-zA-Z_\.-]+)@([0-9a-zA-Z_-]+)(\.[0-9a-zA-Z_-]+){1,2}$/;
        if(!reg_email.test(str)) return false;
        else return true;
    }
</script>
<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/components/_footer.php";?>