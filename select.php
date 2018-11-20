<html>
<head>
    <style type="text/css">
        .bandView{margin: 0;width: 80%;}
        .bandView th{background: #333;color: #eeeeee;}
        .bandView td{background-color: azure;border: 1px solid #666;}
        .bandView a{background-color: #2b2b2b;color: #ffffff;font-size: 14px;line-height: 1.8em;padding: 0 5px;margin-right: 2px;text-decoration:none;}
        .bandView a:hover{background-color: #ddd;color:#2b2b2b;}
        .bandView strong{background-color: brown;color: #cccccc;font-size: 14px;line-height: 1.8em;padding: 0 5px;margin-right: 2px;}
        .bandView label{font-size: 14px;line-height: 1.8em;color: #666;}
    </style>
</head>
<body>
<div class="bandView">
<?php
header("Content-type: text/html; charset=gbk");
date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL ^ E_NOTICE);
require_once 'sqlHelper.class.php';
$sqlHelper=new sqlHelper();
if(!empty($_POST)){
    /*foreach ($_POST as $k=>$v){
        if($v == '') unset($_POST[$k]);
    }*/
    //print_r($_POST);
    $sWo=$_POST['sWo'];
    setcookie('sWo',$sWo,time()+12*3600);
    $sCrate=$_POST['sCrate'];
    setcookie('sCrate',$sCrate,time()+12*3600);
    $sTimeIn=$_POST['sTimeIn'];
    setcookie('sTimeIn',$sTimeIn,time()+12*3600);
    $sTimeOut=$_POST['sTimeOut'];
    setcookie('sTimeOut',$sTimeOut,time()+12*3600);
}else{
    $sWo=$_COOKIE['sWo'];
    $sCrate=$_COOKIE['sCrate'];
    $sTimeIn=$_COOKIE['sTimeIn'];
    $sTimeOut=$_COOKIE['sTimeOut'];
}
$sql="";
$sql1="";
$flag=0;
if(!empty($sWo) && empty($sTimeIn) && empty($sTimeOut)){
    $sql="SELECT A.WO,A.SAP_SN,A.AMD_SN,A.AMD_DS,A.VIDEO_SN1,A.VIDEO_SN2,A.VIDEO_SN3,A.VIDEO_SN4,A.VIDEO_SN5,A.VIDEO_SN6,A.VIDEO_SN7,A.VIDEO_SN8,A.VIDEO_SN9,B.SAVEDATE FROM 
dbo.BlueGem_Isn AS A,dbo.BlueGem_WayOut AS B WHERE A.SAP_SN=B.CRATE AND B.WO='{$sWo}'";
    $flag=1;
}else if(!empty($sCrate) && empty($sTimeIn) && empty($sTimeOut)){
    $sql="SELECT A.WO,A.SAP_SN,A.AMD_SN,A.AMD_DS,A.VIDEO_SN1,A.VIDEO_SN2,A.VIDEO_SN3,A.VIDEO_SN4,A.VIDEO_SN5,A.VIDEO_SN6,A.VIDEO_SN7,A.VIDEO_SN8,A.VIDEO_SN9,B.SAVEDATE FROM 
dbo.BlueGem_Isn AS A,dbo.BlueGem_WayOut AS B WHERE B.CRATE='{$sCrate}' AND A.SAP_SN=B.CRATE";
    $flag=2;
}else if(!empty($sTimeIn) && !empty($sTimeOut) && empty($sWo) && empty($sCrate)){
    $sql="SELECT A.WO,A.SAP_SN,A.AMD_SN,A.AMD_DS,A.VIDEO_SN1,A.VIDEO_SN2,A.VIDEO_SN3,A.VIDEO_SN4,A.VIDEO_SN5,A.VIDEO_SN6,A.VIDEO_SN7,A.VIDEO_SN8,A.VIDEO_SN9,B.SAVEDATE FROM 
dbo.BlueGem_Isn AS A,dbo.BlueGem_WayOut AS B WHERE B.SAVEDATE>='{$sTimeIn}' AND B.SAVEDATE<='{$sTimeOut}' AND A.SAP_SN=B.CRATE";
    $flag=3;
}else{
    $sql="SELECT A.WO,A.SAP_SN,A.AMD_SN,A.AMD_DS,A.VIDEO_SN1,A.VIDEO_SN2,A.VIDEO_SN3,A.VIDEO_SN4,A.VIDEO_SN5,A.VIDEO_SN6,A.VIDEO_SN7,A.VIDEO_SN8,A.VIDEO_SN9,B.SAVEDATE FROM 
dbo.BlueGem_Isn AS A,dbo.BlueGem_WayOut AS B WHERE B.SAVEDATE>='{$sTimeIn}' AND B.SAVEDATE<='{$sTimeOut}' AND A.SAP_SN=B.CRATE AND B.WO='{$sWo}'";
    $flag=4;
}
$pageSize = 100;        //每页显示的记录数
$totalRows=$sqlHelper->count($sql);//总记录数
$totalPage = ceil($totalRows / $pageSize);  //总页数
$page = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;//当前页数
if ($page < 1 || $page == null || !is_numeric($page)) {
    $page = 1;
}
if ($page >= $totalPage) $page = $totalPage;
$offset = ($page - 1) * $pageSize;

//组织分页查询语句
if($flag == 1){
    $sql1="SELECT TOP $pageSize A.ID,A.WO,A.SAP_SN,A.AMD_SN,A.AMD_DS,A.VIDEO_SN1,A.VIDEO_SN2,A.VIDEO_SN3,A.VIDEO_SN4,A.VIDEO_SN5,A.VIDEO_SN6,A.VIDEO_SN7,A.VIDEO_SN8,A.VIDEO_SN9,B.SAVEDATE FROM 
dbo.BlueGem_Isn AS A,dbo.BlueGem_WayOut AS B WHERE A.SAP_SN=B.CRATE AND B.WO='{$sWo}' AND A.ID NOT IN (
SELECT TOP $offset A.ID FROM dbo.BlueGem_Isn AS A,dbo.BlueGem_WayOut AS B WHERE A.SAP_SN=B.CRATE AND B.WO='{$sWo}'
)";
    //echo $sql1;
}else if($flag == 2){
    $sql1="SELECT TOP $pageSize A.ID,A.WO,A.SAP_SN,A.AMD_SN,A.AMD_DS,A.VIDEO_SN1,A.VIDEO_SN2,A.VIDEO_SN3,A.VIDEO_SN4,A.VIDEO_SN5,A.VIDEO_SN6,A.VIDEO_SN7,A.VIDEO_SN8,A.VIDEO_SN9,B.SAVEDATE FROM 
dbo.BlueGem_Isn AS A,dbo.BlueGem_WayOut AS B WHERE B.CRATE='{$sCrate}' AND A.SAP_SN=B.CRATE";
}else if($flag == 3){
    $sql1="SELECT TOP $pageSize A.ID,A.WO,A.SAP_SN,A.AMD_SN,A.AMD_DS,A.VIDEO_SN1,A.VIDEO_SN2,A.VIDEO_SN3,A.VIDEO_SN4,A.VIDEO_SN5,A.VIDEO_SN6,A.VIDEO_SN7,A.VIDEO_SN8,A.VIDEO_SN9,B.SAVEDATE FROM 
dbo.BlueGem_Isn AS A,dbo.BlueGem_WayOut AS B WHERE B.SAVEDATE>='{$sTimeIn}' AND B.SAVEDATE<='{$sTimeOut}' AND A.SAP_SN=B.CRATE AND A.ID NOT IN (
SELECT TOP $offset A.ID FROM dbo.BlueGem_Isn AS A,dbo.BlueGem_WayOut AS B WHERE B.SAVEDATE>='{$sTimeIn}' AND B.SAVEDATE<='{$sTimeOut}' AND A.SAP_SN=B.CRATE
)";
}else if($flag == 4){
    $sql1="SELECT TOP $pageSize A.ID,A.WO,A.SAP_SN,A.AMD_SN,A.AMD_DS,A.VIDEO_SN1,A.VIDEO_SN2,A.VIDEO_SN3,A.VIDEO_SN4,A.VIDEO_SN5,A.VIDEO_SN6,A.VIDEO_SN7,A.VIDEO_SN8,A.VIDEO_SN9,B.SAVEDATE FROM 
dbo.BlueGem_Isn AS A,dbo.BlueGem_WayOut AS B WHERE B.SAVEDATE>='{$sTimeIn}' AND B.SAVEDATE<='{$sTimeOut}' AND A.SAP_SN=B.CRATE AND B.WO='{$sWo}' AND A.ID NOT IN (
SELECT TOP $offset A.ID FROM dbo.BlueGem_Isn AS A,dbo.BlueGem_WayOut AS B WHERE B.SAVEDATE>='{$sTimeIn}' AND B.SAVEDATE<='{$sTimeOut}' AND A.SAP_SN=B.CRATE AND B.WO='{$sWo}'
)";
}

$findRes=$sqlHelper->select($sql1);

echo "<table style='text-align: center'>";
echo "<tr><th>序号</th><th>工单号</th><th>不知道什么码</th><th>不知道什么码</th><th>不知道什么码</th><th>显卡1</th><th>显卡2</th><th>显卡3</th><th>显卡4</th><th>显卡5</th><th>显卡6</th><th>显卡7</th><th>显卡8</th><th>显卡9</th><th>操作时间</th>";
foreach ($findRes as $k => $v) {
    $saveDate = (array)$v["SAVEDATE"];
    $saveDate['date']=substr($saveDate['date'],0,19);
    //echo "<tr><td>{$v['id']}</td>";
    echo "<tr><td>{$v['ID']}</td>";
    echo "<td>" . "{$v['WO']}" . "</td>";
    echo "<td>{$v['SAP_SN']}</td>";
    echo "<td>{$v['AMD_SN']}</td>";
    echo "<td>{$v['AMD_DS']}</td>";
    //echo "<td>{$v['ddr']}</td>";
    echo "<td>{$v['VIDEO_SN1']}</td>";
    echo "<td>{$v['VIDEO_SN2']}</td>";
    echo "<td>{$v['VIDEO_SN3']}</td>";
    echo "<td>{$v['VIDEO_SN4']}</td>";
    echo "<td>{$v['VIDEO_SN5']}</td>";
    echo "<td>{$v['VIDEO_SN6']}</td>";
    echo "<td>{$v['VIDEO_SN7']}</td>";
    echo "<td>{$v['VIDEO_SN8']}</td>";
    echo "<td>{$v['VIDEO_SN9']}</td>";
    echo "<td>{$saveDate['date']}</td></tr>";
}
echo "</table>";
echo showPage($page, $totalPage,'',$totalRows);        //输出页码链接
echo "<hr/>";
//showPage(页号，总页数，分隔符)
function showPage($page,$totalPage,$sep=" ",$totalRows){
    $totalRows=$totalRows;
    $url = $_SERVER ['PHP_SELF'];           //获取当前路径
    $index = ($page == 1) ? "<label>首页</label>" : "<a href='{$url}?page=1'>首页</a>";
    $last = ($page == $totalPage) ? "<label>尾页</label>" : "<a href='{$url}?page={$totalPage}'>尾页</a>";
    $prevPage=($page>=1)?$page-1:1;
    $nextPage=($page>=$totalPage)?$totalPage:$page+1;
    $prev = ($page == 1) ? "<label>上一页</label>" : "<a href='{$url}?page={$prevPage}'>上一页</a>";
    $next = ($page == $totalPage) ? "<label>下一页</label>" : "<a href='{$url}?page={$nextPage}'>下一页</a>";
    $str = "<label>总共{$totalPage}页/当前是第{$page}页。总共出库{$totalRows}台。</label>";
    $p="";
    $startId=max($page-5,1);
    $endID=min($startId+10,$totalPage);
    for($i =$startId; $i <=$endID; $i ++) {
        //当前页无连接
        if ($page == $i) {
            $p .= "<strong>{$i}</strong>";
        } else {
            $p .= "<a href='{$url}?page={$i}'>{$i}</a>";
        }
    }
    $pageStr=$str.$sep . $index .$sep. $prev.$sep . $p.$sep . $next.$sep . $last;
    return $pageStr;    //返回分页字符串
}
$sqlHelper->close();
?>
</div>
</body>
</html>
