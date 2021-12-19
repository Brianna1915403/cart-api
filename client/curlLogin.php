<?php
    session_start();
    require "init.php";    
    include("login.html");

    if(isset($_POST['login'])) {
        echo "Login Clicked";
    } else if (isset($_POST['register'])) {
        //$_SESSION['LICENSE_KEY'] = "Orphane in my basement";
        $_SESSION['LICENSE_NUMBER'] = HTTPPostClient();
        //echo $licenseKey."<br/>"; // 46675588
        //$_SESSION['LICENSE_KEY'] = $licenseKey;
        //echo $_SESSION['LICENSE_KEY'];
        header("location: ".BASE."/curlConvert.php");
    }

    //license key: 5913901381

    function HTTPPostClient(){
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