<?php 

    http_response_code(400);
    echo json_encode(['status' => http_response_code(), 'error' => 'Invalid Request']);

?>