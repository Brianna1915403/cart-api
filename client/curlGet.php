<?php
    $getUrl = "";

    function HTTPGet($url){
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/json'
        ));
        curl_setopt($curl, CURLOPT_HEADER, 0);

        curl_setopt($curl);
        curl_close($curl);
    }
?>