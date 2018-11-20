<?php
header("Content-type: text/html; charset=gbk");
date_default_timezone_set("Asia/Shanghai");
class sqlHelper{
    public $conn;
    public $serverName = "192.168.3.210\MESSQL";
    public $connectionInfo =  array("UID"=>"sa","PWD"=>"123456","Database"=>"SFC");
    //���췽��
    public function __construct()
    {
        $this->conn=sqlsrv_connect( $this->serverName, $this->connectionInfo);
        if(!$this->conn) die(var_dump(sqlsrv_errors()));
    }
    //DQL��ѯ���������һ����ά����
    public function select($sql){
        $arr=array();
        $data=sqlsrv_query($this->conn,$sql);
        if(!$data) die(print_r(sqlsrv_errors(),true));
        while($row=sqlsrv_fetch_array($data,SQLSRV_FETCH_ASSOC)){
            $arr[]=$row;
        }
        //$this->close();
        sqlsrv_free_stmt($data);
        return $arr;
    }
    //��ʾ��ѯ��¼��
    public function count($sql){
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $stmt = sqlsrv_query( $this->conn, $sql , $params, $options );

        $row_count = sqlsrv_num_rows( $stmt );
        sqlsrv_free_stmt($stmt);
        if($row_count === false){
            return "��������";
        }else{
            return $row_count;
        }

    }
    //����sql(��¼����)
    public function insert($sql){
        $data2=sqlsrv_query($this->conn,$sql);
        //var_dump($data2);
        if(!$data2) {
            //die(print_r(sqlsrv_errors(), true));
            die('<lable id="ng1">��ֹ�ظ����⡣</lable>'.'<br>');
        }else{
            echo '<lable id="ok">����ɹ���</lable>'.'<br>';
            setcookie("band_count",$_COOKIE['band_count']+1,time()+12*3600);
            echo '<lable style="color:dodgerblue" >�����ѳ���'.$_COOKIE['band_count'].'̨</lable>'.'<br>';
        }
        sqlsrv_free_stmt($data2);
    }
    //ͨ�ò���sql
    public function insert_all($sql){
        $stmt=sqlsrv_query($this->conn,$sql);
        if(!$stmt) die('<p style="color: white">��ֹ�ظ�ɨ�衣</p>'.'<br>');
        echo "<h3 style='color: chartreuse'>PASS!</h3>";
        sqlsrv_free_stmt($stmt);
    }

    public function insert_all1($sql){
        $stmt=sqlsrv_query($this->conn,$sql);
        if(!$stmt) die('<h3 style="color: red">��ֹ�ظ�ɨ�衣</h3>'.'<br>');
        echo "<h3 style='color: chartreuse'>PASS!</h3>";
        sqlsrv_free_stmt($stmt);
    }


    //���ô洢����
    public function exec($sql){
        $params=array('1');
        $stmt=sqlsrv_query($this->conn,$sql,$params);
        if($stmt === false) die(print_r(sqlsrv_errors(),true));
        $arr=array();
        while($row=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_NUMERIC)){
            $arr[]=$row;
        }
        sqlsrv_free_stmt($stmt);
        return $arr;
    }
    //�ر�����
    public function close(){
        sqlsrv_close($this->conn);
    }
}
