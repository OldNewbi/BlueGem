<?php
require_once 'sqlHelper.class.php';
$sqlHelper= new sqlHelper();
if(!empty($_POST)){
    //print_r($_POST);
    $sql="INSERT INTO BlueGem_Isn (WO,SAP_SN,AMD_SN,AMD_DS,VIDEO_SN1,VIDEO_SN2,VIDEO_SN3,VIDEO_SN4,VIDEO_SN5,VIDEO_SN6,VIDEO_SN7,VIDEO_SN8,VIDEO_SN9,SAVEDATE)
 VALUES ('{$_POST["wo"]}','{$_POST["sap_sn"]}','{$_POST["amd_sn"]}','{$_POST["amd_ds"]}','{$_POST["video1"]}','{$_POST["video2"]}','{$_POST["video3"]}','{$_POST["video4"]}','{$_POST["video5"]}','{$_POST["video6"]}','{$_POST["video7"]}','{$_POST["video8"]}','{$_POST["video9"]}',getdate())";
    $sql1="SELECT WO FROM BlueGem_Isn WHERE WO='{$_POST["wo"]}'";
    $sql2="SELECT VIDEO_SN1,VIDEO_SN2,VIDEO_SN3,VIDEO_SN4,VIDEO_SN5,VIDEO_SN6,VIDEO_SN7,VIDEO_SN8,VIDEO_SN9 FROM BlueGem_Isn WHERE WO='{$_POST["wo"]}'";
    if(!$_POST['wo'] == '') {
        //数据比对，不允许数据库中显卡数据有重复，我感觉这样可能会影响速度，如果包装速度过慢，我会注释这段代码。
        $data=$sqlHelper->select($sql2);
        foreach ($data as $k=>$v){
            foreach ($v as $key=>$value){
                foreach ($_POST as $x=>$y){
                    if($value == $y){
                        die("<label style='color: mediumvioletred'>条码当中存在之前已经扫描过的条码。{$key}-{$y}</label>");
                    }
                }
            }
        }
        $sqlHelper->insert_all1($sql);
        $count=$sqlHelper->count($sql1);
        $sqlHelper->close();
        echo "本工单已扫描".$count."套。";
    }else{
        echo "<h3 style='color: navy'>工单号不能为空</h3>";
    }
}else{
    echo "没有数据";
}