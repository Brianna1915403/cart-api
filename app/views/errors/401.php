<?php 

    header("Content-Type: application/json");
    http_response_code(401);
    echo json_encode(['status' => http_response_code(), 'message' => 'Invalid Credentials']);

?>