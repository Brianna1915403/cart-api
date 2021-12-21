<?php
    session_start();
    require "init.php";
    echo "<h2>Hope you are doing great ".$_SESSION['NAME']."!</h2><br/><br/>";    
    include("modify.html");

    if(isset($_POST['updateUsername'])) {
        $result = HTTPUpdateUsername();
        if($result == "Name Updated."){
            header("location: ".BASE."/curlLogin.php");
        }
        else{
            echo $result;
        }
    } else if (isset($_POST['updatePassword'])) {
        $result = HTTPUpdatePassword();
        if($result == "Password Updated."){
            header("location: ".BASE."/curlLogin.php");
        }
        else{
            echo $result;
        }
    }
    else if(isset($_POST['logOut'])){
        header("location: ".BASE."/curlLogin.php");
    }

    function HTTPUpdateUsername(){
        $url = "http://localhost/WebServicesProject/Converter/api/client/name";

        $put = array(
            "licenseNumber" => $_SESSION['LICENSE_NUMBER'],
            "clientName" => $_POST['new_username']
        );

        $data = json_encode($put);

        $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Set the verb of the request
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Add the data to the request
    
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Set the content and accept type
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                array(
                    'Content-Type:application/json',
                    'Accept:application/json',
                )
            );
    
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
    }

    function HTTPUpdatePassword(){
        $url = "http://localhost/WebServicesProject/Converter/api/client/password";

        $put = array(
            "licenseNumber" => $_SESSION['LICENSE_NUMBER'],
            "password_hash" => password_hash($_POST['new_password'], PASSWORD_DEFAULT)
        );

        $data = json_encode($put);

        $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Set the verb of the request
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Add the data to the request
    
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Set the content and accept type
            curl_setopt($ch, CURLOPT_HTTPHEADER,
                array(
                    'Content-Type:application/json',
                    'Accept:application/json',
                )
            );
    
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
    }
?>

