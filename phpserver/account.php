<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
//主要为跨域CROS配置的两大基本信息,Origin和headers

require 'MyPDO.class.php';
$db = MyPDO::getInstance('localhost', 'root', 'YSC123', 'cashbook', 'utf8');//连接数据库


$json = file_get_contents('php://input');
$data = json_decode($json);

$do = $data->do;


//插入账单数据
if($do == "addaccount") {

    $content = $data->ruleForm->content;
    $name = $data->ruleForm->name;
    $type = $data->ruleForm->type;
    $date = $data->ruleForm->date;
    $money = $data->ruleForm->money;
    $memberID = $data->memberID;
    //check_name默认是无
    $check_name = '无';
    //isCheck默认是否
    $isCheck = '否';
    //checkway默认为无
    $checkWay = '无';

    if(($memberID) && ($content) && ($name) && ($type) && ($date) && ($money)) {
        $arrayData = array('memberID' => $memberID , 'content' => $content , 'name' => $name , 'type' => $type , 'date' => $date , 'money' => $money , 'check_name' => $check_name , 'isCheck' => $isCheck , 'checkWay' => $checkWay);
        $table = 'account';
        //插入操作的返回值，1代表成功
        $addAccountResult = $db->insert($table , $arrayData);

        if($addAccountResult === 1) {
            $returnAddAccSucData = array('success' => true , 'msg' => "存储成功" , 'accountNumID' => $addAccountResult[0]['accountNumID']);
            echo json_encode($returnAddAccSucData);
        }else {
            $returnAddAccFalseData = array('success' => false , 'msg' => "存储失败");
            echo json_encode($returnAddAccFalseData);
        }
    }  
}


//查询个人账单数据（不显示清账信息）
if($do == "queryPersonalAcc") {

    $theBeginTime = $data->queryTimeScope->theBeginTime;
    $theEndTime = $data->queryTimeScope->theEndTime;
    $memberID = $data->memberID;

    if(($theBeginTime) && ($theEndTime) && ($memberID)) {
        $sql = "select * from account where memberID like '%".$memberID."%'";
        $queryPersonalAccResult = $db->query($sql);

        //处理时间段内的事件再返回
        $selectData = array(array());
        for($i = 0 , $j = 0; $i < sizeof($queryPersonalAccResult) ; $i++) {
            if(($theBeginTime <= $queryPersonalAccResult[$i]['date']) && ($queryPersonalAccResult[$i]['date'] <= $theEndTime)) {
                $selectData[$j++] = $queryPersonalAccResult[$i];
            }
        } 
        if($selectData) {
            $returnQuerySucData = array('success' => true , 'msg' => "查询成功" , 'resultData' => $selectData);
            echo json_encode($returnQuerySucData);
        }else {
            $returnQueryFalseData = array('success' => false , 'msg' => "查询失败");
            echo json_encode($returnQueryFalseData);
        }
    }else {
        //无查询时间时返回所有数据
        $sql = "select * from account where memberID like '%".$memberID."%'";
        $queryPersonalAccResult = $db->query($sql);
        if($queryPersonalAccResult) {
            $returnQuerySucData = array('success' => true , 'msg' => "查询成功" , 'resultData' => $queryPersonalAccResult);
            echo json_encode($returnQuerySucData);
        }else {
            $returnQueryFalseData = array('success' => false , 'msg' => "查询失败");
            echo json_encode($returnQueryFalseData);
        }
    }
}


//查询所有账单数据（显示清账信息）
if($do == 'queryAllAccount') {

    $theBeginTime = $data->queryTimeScope->theBeginTime;
    $theEndTime = $data->queryTimeScope->theEndTime;

   if(($theBeginTime) && ($theEndTime)) {
        $sql = "select * from account";
        $queryAllAccountResult = $db->query($sql);

        //处理时间段内的事件再返回
        $selectData = array(array());
        for($i = 0 , $j = 0; $i < sizeof($queryAllAccountResult) ; $i++) {
            if(($theBeginTime <= $queryAllAccountResult[$i]['date']) && ($queryAllAccountResult[$i]['date'] <= $theEndTime)) {
                $selectData[$j++] = $queryAllAccountResult[$i];
            }
        } 
        if($selectData) {
            $returnQuerySucData = array('success' => true , 'msg' => "查询成功" , 'resultData' => $selectData);
            echo json_encode($returnQuerySucData);
        }else {
            $returnQueryFalseData = array('success' => false , 'msg' => "查询失败");
            echo json_encode($returnQueryFalseData);
        }
    }else {
        //无查询时间时返回所有数据
        $sql = "select * from account";
        $queryAllAccountResult = $db->query($sql);
        if($queryAllAccountResult) {
            $returnQuerySucData = array('success' => true , 'msg' => "查询成功" , 'resultData' => $queryAllAccountResult);
            echo json_encode($returnQuerySucData);
        }else {
            $returnQueryFalseData = array('success' => false , 'msg' => "查询失败");
            echo json_encode($returnQueryFalseData);
        }
    } 
}



$db->destruct();
?>




