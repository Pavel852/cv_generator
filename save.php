<?php
// save.php — PHP 7.0 compatible
// Stores CV JSON payload into ./data/<KEY>.json and returns the KEY.
// If a key is already known (previously loaded or saved), we overwrite the same file.
// Author PB (c) 2025

header("Content-Type: application/json; charset=UTF-8");
header("X-Powered-By: PB CV Generator 1.0.1");
header("Cache-Control: no-store");

try {
    $raw = file_get_contents("php://input");
    if ($raw === false || strlen($raw) === 0) {
        http_response_code(400);
        echo json_encode(["ok"=>false, "error"=>"Empty request body"]);
        exit;
    }
    if (strlen($raw) > 8 * 1024 * 1024) { // 8 MB limit
        http_response_code(413);
        echo json_encode(["ok"=>false, "error"=>"Payload too large"]);
        exit;
    }
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(["ok"=>false, "error"=>"Invalid JSON"]);
        exit;
    }

    // Provided edit key? Prefer URL ?key=... then JSON.edit_key
    $providedKey = "";
    if (isset($_GET["key"])) {
        $providedKey = strtoupper(trim($_GET["key"]));
    } elseif (isset($data["edit_key"])) {
        $providedKey = strtoupper(trim($data["edit_key"]));
    }

    // Validate key format if provided
    if ($providedKey !== "" && !preg_match('/^[A-Z0-9]{6,40}$/', $providedKey)) {
        http_response_code(400);
        echo json_encode(["ok"=>false, "error"=>"Invalid key format"]);
        exit;
    }

    // Generate new key if none provided
    $key = $providedKey;
    if ($key === "") {
        $alphabet = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
        $key = "";
        for ($i = 0; $i < 20; $i++) {
            $key .= $alphabet[random_int(0, strlen($alphabet)-1)];
        }
    }

    $dir = __DIR__ . DIRECTORY_SEPARATOR . "data";
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0700, true)) {
            http_response_code(500);
            echo json_encode(["ok"=>false, "error"=>"Failed to create data dir"]);
            exit;
        }
    }

    // Attach server metadata
    $data["_storage"] = [
        "key" => $key,
        "stored_at_utc" => gmdate("c"),
        "server_version" => "PB CV Generator 1.0.1 (PHP7)",
        "ip_hint" => isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null
    ];

    $file = $dir . DIRECTORY_SEPARATOR . $key . ".json";
    $ok = file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    if ($ok === false) {
        http_response_code(500);
        echo json_encode(["ok"=>false, "error"=>"Failed to write data file"]);
        exit;
    }

    echo json_encode(["ok"=>true, "key"=>$key], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["ok"=>false, "error"=>$e->getMessage()]);
}
