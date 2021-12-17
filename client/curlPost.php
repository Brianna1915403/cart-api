<?php
    $post = array(
        "clientName" => "something",
        "password_hash" => "something"
    );

    $urlCreate = "http://localhost/WebServicesProject/Converter/api/client/create"; // url to create a user.
    $urlFile   = "http://localhost/WebServicesProject/Converter/api/file/convert"; // convert a file.
    $urlVideo  = "http://localhost/WebServicesProject/Converter/api/video/convert"; // convert a video.
    
    function HTTPPostFile($urlFile){
        $post = array(
            "licenceNumber" => "something",
            "password_hash" => "something"
        );

        $data = json_encode($post);

        $ch = curl_init($urlFile);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); // Set the verb of the request
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Add the data to the request

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

    function HTTPPostVideo($urlVideo){
        $post = array(
            "licenceNumber" => "something",
            "password_hash" => "something"
        );

        $data = json_encode($post);

        $ch = curl_init($urlVideo);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); // Set the verb of the request
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Add the data to the request

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

    // create an user
    function HTTPPostCreate($urlCreate){
        $post = array(
            "clientName" => "something",
            "password_hash" => "something"
        );

        $data = json_encode($post);

        $ch = curl_init($urlCreate);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); // Set the verb of the request
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Add the data to the request
    
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

    //
?>