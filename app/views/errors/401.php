<?php 

    http_response_code(401);
    echo json_encode(['status' => http_response_code(), 'error' => 'Invalid Credentials']);

?>