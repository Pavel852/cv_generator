<?php
// load.php — PHP 7.0 compatible
// Returns JSON stored under ./data/<KEY>.json
// Author PB (c) 2025

header("Content-Type: application/json; charset=UTF-8");
header("X-Powered-By: PB CV Generator 1.0.1");
header("Cache-Control: no-store");

$key = isset($_GET["key"]) ? $_GET["key"] : "";
$key = strtoupper(trim($key));

if (!$key || !preg_match('/^[A-Z0-9]{6,40}$/', $key)) {
    http_response_code(400);
    echo json_encode(["ok"=>false, "error"=>"Invalid key format"]);
    exit;
}
$file = __DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . $key . ".json";
if (!is_file($file)) {
    http_response_code(404);
    echo json_encode(["ok"=>false, "error"=>"Not found"]);
    exit;
}
$raw = file_get_contents($file);
if ($raw === false) {
    http_response_code(500);
    echo json_encode(["ok"=>false, "error"=>"Failed to read file"]);
    exit;
}
echo $raw;
