<?php
require_once 'sqlHelper.class.php';
$sqlHelper=new sqlHelper();
if($_POST['crate'] != ''){
    //print_r($_POST);
    //print_r($_GET);
    $crate=$_POST['crate'];
}else{
    die("û������");
}
$sql="SELECT * FROM BlueGem_Isn WHERE SAP_SN='{$crate}'";
$data1=$sqlHelper->select($sql);
//die($data1[0]["WO"]);
$data=$sqlHelper->count($sql);
if($data == 0) die("<label style='color: indianred'>������֮ǰû�а󶨣�</label>");
$sql1="INSERT INTO BlueGem_WayOut (CRATE,SAVEDATE,WO) VALUES ('{$crate}',getdate(),'{$data1[0]["WO"]}')";
$sqlHelper->insert_all1($sql1);
$sql2="SELECT CRATE FROM BlueGem_WayOut WHERE WO='{$data1[0]["WO"]}'";
$woCount=$sqlHelper->count($sql2);
echo "�������ѳ���".$woCount."�ס�";
/*echo "<pre>";
echo $data;
echo "</pre>";*/