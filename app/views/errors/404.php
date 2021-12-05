<?php 

    http_response_code(404);
    echo json_encode(['status' => http_response_code(), 'error' => 'Not Found']);

?>