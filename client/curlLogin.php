<?php
    session_start();
    require "init.php";    
    include("login.html");
//  785598812 -> aaa
// $2y$10$m6l3k2b4uyumeJQeYCSeXusTFneWCmYtBrlg2I2Hj5KAAaAxAwNYq
    if(isset($_POST['login'])) {
        $result = HTTPLogin();

        if((!is_null($result)) && password_verify($_POST['password'], $result['password_hash'])){
            $_SESSION['NAME'] = $result['clientName'];
            header("location: ".BASE."/curlConvert.php");
        }else{
            echo "client ID : ".$result['clientID']."<br/>";
            echo "License Number : ".$_POST['license_number']."<br/>";
            echo "Password entered : ".$_POST['password']."<br/>";
            var_dump($result['password_hash']);           
            echo "<br/>";
            var_dump(password_verify($_POST['password'], $result['password_hash']));
            echo "<br /> Nope";
        }
    } else if (isset($_POST['register'])) {
        if($_POST['password'] == $_POST['confirm_password']){
            $_SESSION['LICENSE_NUMBER'] = HTTPRegister();
            $_SESSION['NAME'] = $_POST['username'];
            header("location: ".BASE."/curlConvert.php");
        }else{
            echo "Passwords does not match";
        }
    }

    function HTTPLogin(){
        $url = "http://localhost/WebServicesProject/Converter/api/client/".$_POST['license_number']; // url to login a user.

        $_SESSION['PASSWORD'] = $_POST['password'];
        $_SESSION['LICENSE_NUMBER'] = $_POST['license_number'];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type:application/json'
        ));

        
        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response, true);

        echo "IN LOGIN: <br/>";
        var_dump(password_verify($_POST['password'], $result['password_hash']));
        echo "<br />";

        return $result;
    }

    function HTTPRegister(){
        $url = "http://localhost/WebServicesProject/Converter/api/client/create"; // url to create a user.
        
        $hash_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $post = array(
            "clientName" => $_POST['username'],
            "password_hash" => $hash_password
        );

        $_SESSION['PASSWORD'] = $_POST['password'];

        $data = json_encode($post);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); // Set the verb of the request
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