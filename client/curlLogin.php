<?php
    session_start();
    require "init.php";    
    include("login.html");

    if(isset($_POST['login'])) {
        $result = HTTPLogin();
        if((!is_null($result)) && password_verify($_POST['password'], $result[2])){
            header("location: ".BASE."/curlConvert.php");
        }else{
            var_dump(password_verify($_POST['password'], $result['password_hash']));
            echo "<br/>";
            var_dump($result['password_hash']);
            echo "Fuck you!";
        }
    } else if (isset($_POST['register'])) {
        if($_POST['password'] == $_POST['confirm_password']){
            $_SESSION['LICENSE_NUMBER'] = HTTPRegister();
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
            'Accept: application/json'
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    function HTTPRegister(){
        $url = "http://localhost/WebServicesProject/Converter/api/client/create"; // url to create a user.
        
        $hash_password = password_hash($_POST['password_hash'], PASSWORD_DEFAULT);
        
        $post = array(
            "clientName" => $_POST['username'],
            "password_hash" => $hash_password
        );

        $_SESSION['PASSWORD'] = $_POST['password_hash'];

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