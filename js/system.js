var inputs = document.getElementsByTagName('input');
var values=[];
function load(){
    inputs[0].focus();
}
for(var i=0;i<14;i++) {
    (function (i){
        inputs[i].onkeydown = function (e) {
            var e = e || window.event;
            if (e.which == 13) {
                values[i]=inputs[i].value;
                for(var j=0;j<i;j++){
                    if(values[j] == values[i]){
                        alert("条码不允许重复扫描！");
                        inputs[i].select();
                        return;
                    }
                }
                if(values[i].length != 13 && i != 0) {
                    alert("条码长度不符合规则！");
                    inputs[i].select();
                    return;
                }
                if(i == 1 && values[i].substring(0,3) != '4HJ'){
                    alert("SAP码必须以4HJ打头");
                    //values[i]='';
                    inputs[i].select();
                    //console.log(111);
                    return;
                }else if(i == 2 && values[i].substring(0,3) != '52J'){
                    alert("AMD码必须以52J打头");
                    inputs[i].select();
                    return;
                }else if(i == 3 && values[i].substring(0,3) != '43J'){
                    alert("AMD_DS必须以43J打头");
                    inputs[i].select();
                    return;
                }else if(i>3 && values[i].substring(0,3) != '11J'){
                    alert("显卡必须以11J打头");
                    inputs[i].select();
                    return;
                }
                inputs[i].select();
                inputs[i+1].focus();
                console.log(values[i]);
            }
        }
    }(i))
}
inputs[14].onkeydown=function(e){
    var e = e || window.event;
    if(e.which == 13){
        //console.log(111111);
        inputs[15].focus();
        //inputs[14].select();
    }
}
//提交数据之后再让input处于select()状态
function getSelect(){
    inputs[14].select();
    //console.log(222);
}
var put1=document.getElementById('put1');
//put1.onclick=function(){return false;}
//ajax发送数据
function send(){
    if(!navigator.onLine){
        alert("提交数据失败，请检查网络");
        location.reload();
    }
    var form1=document.getElementById('form1');
    var fd=new FormData(form1);
    var xhr=null;
    if(window.XMLHttpRequest){
        xhr=new XMLHttpRequest();
    }else{
        xhr=new ActiveXObject('Microsoft.XMLHttp');
    }
    xhr.open('POST','binding.php',true);
    xhr.onreadystatechange=function(){
        if(this.readyState == 4 && this.status == 200){
            var return_message=document.getElementById('return_message');
            return_message.innerHTML=this.responseText;
            inputs[1].focus();
        }
    }
    xhr.send(fd);
}

//封装ajax,第一个参数为一个对象，第二个参数为一个url地址的字符串，第三个函数为接受后台数据的元素ID，第4个参数为自定义函数。
function ajax(object,url,id,fun){
    if(!navigator.onLine){
        alert("提交数据失败，请检查网络");
        location.reload();
    }
    var fd=new FormData(object);
    console.log(fd);
    var xhr=null;
    if(window.XMLHttpRequest){
        xhr=new XMLHttpRequest();
    }else{
        xhr=new ActiveXObject('Microsoft.XMLHttp');
    }
    xhr.open('POST',url,true);
    xhr.onreadystatechange=function(){
        if(this.readyState == 4 && this.status == 200){
            var return_message=document.getElementById(id);
            return_message.innerHTML=this.responseText;
            if(fun != undefined) {
                fun();
            }
        }
    }
    xhr.send(fd);
}

