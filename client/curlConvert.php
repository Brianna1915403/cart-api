<?php
    session_start();
    require "init.php";
    include("convert.html");
    
    // echo $_SESSION['LICENSE_NUMBER']."<br/>";
    // echo $_SESSION['PASSWORD'];
    if(isset($_POST['convertFile'])) {
        HTTPPostFile();
        echo "File Clicked";
    } else if (isset($_POST['convertVideo'])) {
        HTTPPostVideo();
        echo "Video Cliked";
    }

    function HTTPPostFile(){
        $urlFile = "http://localhost/WebServicesProject/Converter/api/file/convert/"; // convert a file.

        echo $_SESSION['PASSWORD']."<br/>";
        // echo $_POST["file"]."<br/>";
        var_dump($_FILES['upload']['tmp_name']);

        $post = array(
            "licenseNumber" => $_SESSION['LICENSE_NUMBER'],
            "password_hash" => $_SESSION['PASSWORD'],
            "originalFormat" => $_POST['originalFormat'],
            "targetFormat"=> $_POST['targetFormat'],
            "file" => $_FILES['upload']['tmp_name'],
            "saveAs" => "C:\\xampp\htdocs\cart-shop\client\\files"
        );

        $data = json_encode($post);

        $ch = curl_init($urlFile);
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
        echo $response;
    }

    function HTTPPostVideo(){
        $urlVideo  = "http://localhost/WebServicesProject/Converter/api/video/convert/"; // convert a video.

        $post = array(
            "licenseNumber" => $_SESSION['LICENSE_NUMBER'],
            "password_hash" => $_SESSION['PASSWORD'],
            "originalFormat" => $_POST['originalFormat'],
            "targetFormat"=> $_POST['targetFormat'],
            "file" => $_POST["video"]
        );

        $data = json_encode($post);

        $ch = curl_init($urlVideo);
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
        echo $response;
    }
?>