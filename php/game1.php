<?php
    include_once('server.php');

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

    $pictureNum = $_POST["pictureNum"];
    $pictureNum = (int)$pictureNum;
    $comPictureNum = rand(1,4);
    $money = $_SESSION["money"];
    $id = $_SESSION["id"];
    //$id =1;
    //$money =100;


    if($money > 10){
        if($pictureNum==2 && $comPictureNum==1 ){
            $money = $money-10;
            $message = "電腦獲勝";
            $_SESSION["money"] = $money;
        }else if ($pictureNum==3 && $comPictureNum==2 ){
            $money = $money-10;
            $message = "電腦獲勝";
            $_SESSION["money"] = $money;
        }else if ($pictureNum==4 && $comPictureNum==3 ){
            $money = $money-10;
            $message = "電腦獲勝";
            $_SESSION["money"] = $money;
        }else if ($pictureNum==1 && $comPictureNum==4 ){
            $money = $money-10;
            $message = "電腦獲勝";
            $_SESSION["money"] = $money;
        }else if ($pictureNum==1 && $comPictureNum==2 ){
            $money = $money+10;
            $message = "玩家獲勝";
            $_SESSION["money"] = $money;
        }else if ($pictureNum==2 && $comPictureNum==3 ){
            $money = $money+10;
            $message = "玩家獲勝";
            $_SESSION["money"] = $money;
        }else if ($pictureNum==3 && $comPictureNum==4 ){
            $money = $money+10;
            $message = "玩家獲勝";
            $_SESSION["money"] = $money;
        }else if ($pictureNum==4 && $comPictureNum==1){
            $money = $money+10;
            $message = "玩家獲勝";
            $_SESSION["money"] = $money;
        }else{
            $message = "雙方平手";
            $_SESSION["money"] = $money;
        }
        
        $query_update="UPDATE `gameMember` SET `memberMoney`=? WHERE `memberId`=?";
         if($stmt=$mysqli->prepare($query_update)){
            $stmt->bind_param('ii',$money,$id);
            $stmt->execute();
            $Success = $mysqli->affected_rows;
            $stmt->close(); 

            if($Success > 0){
                echo json_encode(array("status"=>true,"message"=>$message,"playerImage"=>$pictureNum,"comPictureNum"=>$comPictureNum,"money"=>$money));
            }else if($message = "雙方平手"){
                 echo json_encode(array("status"=>true,"message"=>$message,"playerImage"=>$pictureNum,"comPictureNum"=>$comPictureNum,"money"=>$money));            
            }else{
                echo json_encode(array("status"=>false,"error_msg"=>$ERROR_MSG[8]));
            }
        }
    }else{
        echo json_encode(array("status"=>false,"error_msg"=>$ERROR_MSG[9]));
    }

    
    $mysqli->close();
?>
