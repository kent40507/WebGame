<?php
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
    $name = $_POST["name"];
    $password = $_POST["password"];
    $password_again = $_POST["password_again"];
    $gender = $_POST["gender"];
    $identity = $_POST["identity"];
    $ICId = $_POST["ICId"];
    $phone = $_POST["phone"];
        
    //05142017 判斷輸入的名字內是否是英文或中文
    if(preg_match('/^([0-9A-Za-z]+)$/', $name) || preg_match('/^([\x7f-\xff]+)$/', $name)){
        //05142017 判斷輸入的帳號內是否是英文
        if(preg_match('/^([0-9A-Za-z]+)$/', $account)){
            //05/22/2017 判斷電話號碼是否為數字
            if(is_numeric($phone)){
                //05142017 判斷輸入的密碼是否是英文
                if(preg_match('/^([0-9A-Za-z]+)$/', $password)){
                    //05142017 判斷輸入的密碼是否相同
                    if($password == $password_again){
                        //05132017 判斷帳號是否重複,重複則出現錯誤訊息
                        $query_insert="INSERT INTO `gameMember` (`memberName`, `memberAccount`, `memberPassword`, `memberGender`, `memberIdentityIndex`,`memberPhone`,`memberICIndex`) SELECT ?, ?, PASSWORD(?), ?, ?,?,? FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `gameMember` WHERE `memberAccount` = ?);";
                        if($stmt=$mysqli->prepare($query_insert)){
                            $stmt->bind_param('sssiiiis',$name,$account,$password,$gender,$identity,$phone,$ICId,$account);
                            $stmt->execute();
                            $Success = $mysqli->insert_id;
                            $stmt->close(); 

                            if($Success > 0){
                                $status=true;
                                $error_msg="";
                            }else{
                                $status=false;
                                $error_msg=$ERROR_MSG[3];
                            }
                        }else{
                            $status=false;
                            $error_msg=$mysqli->error;                      
                        }
                    }else{
                        $status=false;
                        $error_msg=$ERROR_MSG[2];                         
                    }
                }else{
                    $status=false;
                    $error_msg=$ERROR_MSG[6];                      
                }
            }else{
                $status=false;
                $error_msg=$ERROR_MSG[7];  
            }
        }else{
            $status=false;
            $error_msg=$ERROR_MSG[5];              
        }
    }else{
        $status=false;
        $error_msg=$ERROR_MSG[4];
    }
    echo json_encode(array("status"=>$status,"error_msg"=>$error_msg));

    $mysqli->close();
?>