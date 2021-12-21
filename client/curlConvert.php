<?php
    session_start();
    require "init.php";
    echo "<h2 class='center'>Hope you are doing great ".$_SESSION['NAME']."!</h2><br/><br/>"; 
    echo "<div class='center'> Keep this very preciously, you will need it to login in the future: <br/> License Number = ".$_SESSION['LICENSE_NUMBER']."</ div><br /><bt />";
    include("convert.html");

    
    if(isset($_POST['modifyAccount'])){
        header("location: ".BASE."/curlModify.php");
    }
    else if(isset($_POST['logOut'])){
        header("location: ".BASE."/curlLogin.php");
    }
    else if(isset($_POST['convertFile'])) {
        echo "<div class='download-link'><a href='".HTTPPostFile()."' target='_blank' rel='noopener noreferrer'>Download</a></div>";
        echo "<br /><br /><br />";
        echo "<div class='history'>";
        get_table(HTTPGetClientHistory('file'));
        echo "</div>";
    } else if (isset($_POST['convertVideo'])) {
        echo "<div class='download-link'><a href='".HTTPPostVideo()."' target='_blank' rel='noopener noreferrer'>Download</a></div>";
        echo "<br /><br /><br />";
        echo "<div class='history'>";
        get_table(HTTPGetClientHistory('video'));   
        echo "</div>";
    }

    function get_uniqid($lenght = 13) {
        $bytes = random_bytes(ceil($lenght / 2));

        return substr(bin2hex($bytes), 0, $lenght);
    }

    function get_table($rows) {
        echo "<table class='history-table'>";
        echo "  <tr>";
        echo "      <th>Request Date</th>";
        echo "      <th>Original Format</th>";
        echo "      <th>Target Format</th>";
        echo "      <th>File</th>";
        echo "  </tr>";

        foreach($rows as $row) {
            echo "  <tr>";
            echo "      <th>".$row['requestDate']."</th>";
            echo "      <th>".$row['originalFormat']."</th>";
            echo "      <th>".$row['targetFormat']."</th>";
            echo "      <th>".$row['file']."</th>";
            echo "  </tr>";
        }

        echo "</table>";
    }

    function HTTPPostFile(){
        $urlFile = "http://localhost/WebServicesProject/Converter/api/file/convert/"; // convert a file.

        $target = getcwd().'\\files\\'.get_uniqid(5).'_'.$_FILES['upload']['name'];
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

        $target = getcwd().'\\files\\'.get_uniqid(5).'_'.$_FILES['video']['name'];
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