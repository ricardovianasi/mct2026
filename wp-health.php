<?php
header('Content-Type: application/json');
http_response_code(200);
echo json_encode([
  'status' => 'ok',
  'message' => 'PHP server is running.',
  'time' => date('c'),
]);