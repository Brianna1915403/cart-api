<?php
    session_start();
    require "init.php";
    echo "<div style=text-align:center;> Keep this very preciously, you will need it to login in the future: <br/> License Number = ".$_SESSION['LICENSE_NUMBER']."</ div><br /><bt />";
    include("convert.html");

    
    // echo $_SESSION['LICENSE_NUMBER']."<br/>";
    // echo $_SESSION['PASSWORD'];
    if(isset($_POST['convertFile'])) {
        echo "<div class='download-link'><a href='".HTTPPostFile()."' target='_blank' rel='noopener noreferrer'>Download</a></ div>";
        echo "<br / >";
        var_dump(HTTPGetClientHistory('file'));
    } else if (isset($_POST['convertVideo'])) {
        echo "<div class='download-link'><a href='".HTTPPostVideo()."' target='_blank' rel='noopener noreferrer'>Download</a></ div>";
        echo "<br />";
        var_dump(HTTPGetClientHistory('video'));
    }

    function HTTPPostFile(){
        $urlFile = "http://localhost/WebServicesProject/Converter/api/file/convert/"; // convert a file.

        $target = getcwd().'\\files\\'.$_FILES['upload']['name'];
        if (move_uploaded_file($_FILES['upload']['tmp_name'], $target)) {
            $post = array(
                "licenseNumber" => $_SESSION['LICENSE_NUMBER'],
                "password_hash" => $_SESSION['PASSWORD'],
                "originalFormat" => $_POST['originalFormat'],
                "targetFormat"=> $_POST['targetFormat'],
                "file" => $target,
                "saveAs" => getcwd().'\\files'
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
            unlink($target);
            return $response;
        }        
    }

    function HTTPPostVideo(){
        $urlVideo  = "http://localhost/WebServicesProject/Converter/api/video/convert/"; // convert a video.

        $target = getcwd().'\\files\\'.$_FILES['video']['name'];
        if (move_uploaded_file($_FILES['video']['tmp_name'], $target)) { 
            $post = array(
                "licenseNumber" => $_SESSION['LICENSE_NUMBER'],
                "password_hash" => $_SESSION['PASSWORD'],
                "originalFormat" => $_POST['originalFormat'],
                "targetFormat"=> $_POST['targetFormat'],
                "file" => $target,
                "saveAs" => getcwd().'\\files'
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
            unlink($target);
            return $response;
        }        
    }

    function HTTPGetClientHistory($controller){
        $url = "http://localhost/WebServicesProject/Converter/api/".$controller."/".$_SESSION['LICENSE_NUMBER'];
        
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json'
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
?>