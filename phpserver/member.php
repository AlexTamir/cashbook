<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
//主要为跨域CROS配置的两大基本信息,Origin和headers

require 'MyPDO.class.php';
$db = MyPDO::getInstance('localhost', 'root', 'YSC123', 'cashbook', 'utf8');//连接数据库


//$json = file_get_contents('php://input');//实际传过来一个json字符串
//$data = json_decode($json);//将json字符串转化为json对象，第二个参数默认为false，如果为true则转化为数组
//print_r($data);
//print_r($data->ruleForm->password);

//echo json_encode($data);
//$do = trim($_REQUEST['do']);

$json = file_get_contents('php://input');
$data = json_decode($json);

$do = $data->do;

//注册检查identity是否重复
if($do == "checkIdentity") {

    $identity = $data->identity;

    $sql = "select * from personal_account where identity like '%".$identity."%'";
    $checkResult = $db->query($sql);

    if(!$checkResult) {
        $returnCheckSucData = array('success' => true , 'msg' => "未重复" , 'identity' => $checkResult[0]['identity']);
        echo json_encode($returnCheckSucData);
    }else {
        $returnCheckFalseData = array('success' => false , 'msg' => "重复" , 'identity' => $checkResult[0]['identity']);
        echo json_encode($returnCheckFalseData);
    }
}


//注册插入用户数据
if($do == "register") {

    $username = $data->ruleForm->username;
    $identity = $data->ruleForm->identity;
    $password = $data->ruleForm->password;

    //在这里加上操作不能注册管理员账号
    if($identity !== 'admin') {
        if(($username) && ($identity) && ($password)) {
            $arrayData = array('username' => $username , 'identity' => $identity , 'password' => $password);
            $table = 'personal_account';
            //插入操作的返回值，1代表成功
            $registerResult = $db->insert($table , $arrayData);

            if($registerResult === 1) {
                $returnRegSucData = array('success' => true , 'msg' => "注册成功" , 'user' => array('username' => $registerResult[0]['username'] , 'identity' => $registerResult[0]['identity']));
                echo json_encode($returnRegSucData);
            }
        }   
    }else {
        $returnRegFalseData = array('success' => false , 'msg' => "不允许注册管理员账号");
        echo json_encode($returnRegFalseData);
    }  
}


//用户登录
if($do == "login") {
    $identity = $data->ruleForm->identity;
    $password = $data->ruleForm->password;

    if(($identity) && ($password)) {
        $sql = "select * from personal_account where identity like '%".$identity."%' and password like '%".$password."%'";
        $loginResult = $db->query($sql);
        if($loginResult) {
            $returnLoginSucData = array('success' => true , 'msg' => "登录成功" , 'identity' => $loginResult[0]['identity'] , 'memberID' => $loginResult[0]['memberID']);
            echo json_encode($returnLoginSucData);
        }else {
            $returnLoginFalseData = array('success' => false , 'msg' => "登录失败");
            echo json_encode($returnLoginFalseData);
        }
    }
}

$db->destruct();
?>