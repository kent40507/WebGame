<?php
    SESSION_START();
    include_once("server.php");    
    header("Access-Control-Allow-Origin: *");
    header("Content-Type:text/html; charset=utf-8");

    $mysqli=new mysqli($SERVER,$USERNAME,$PWD,$DATABASE);
    $mysqli->set_charset('utf-8');

    if(mysqli_connect_errno()){
        echo 'Could not connect to database. Error: '.mysqli_connect_error();
        exit();
    }

    $account = $_POST["account"];
    $password = $_POST["password"];
    $action = $_POST["action"];

    switch($action){
        case "login":
            $query_select="SELECT `memberId`,`memberName`,`memberIdentityIndex`,`memberMoney` FROM `gameMember` WHERE `memberAccount`=? and `memberPassword`=PASSWORD(?);";
            if($stmt=$mysqli->prepare($query_select)){
                $stmt->bind_param('ss',$account,$password);
                $stmt->execute();
                $stmt->bind_result($id,$name,$identity,$money);
                $stmt->fetch();
                $stmt->close(); 

                if($id > 0){
                    $_SESSION["id"]=$id;
                    $_SESSION["name"]=$name;
                    $_SESSION["identity"]=$identity;
                    $_SESSION["money"]=$money;
                    echo json_encode(array("status"=>true,"id"=>$id,"name"=>$name,"identity"=>$identity,"money"=>$money));
                }else{
                    echo json_encode(array("status"=>false,"error_msg"=>$ERROR_MSG[1]));
                }
            }
            break;
        case "logout":
            SESSION_DESTROY();
            break;
    }
    $mysqli->close();
?>
