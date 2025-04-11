<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);
$prompt = $input["prompt"] ?? "";

if (empty($prompt)) {
    echo json_encode(["error" => "Prompt is required"]);
    exit;
}

$API_URL = "https://api-inference.huggingface.co/models/CompVis/stable-diffusion-v1-4";
$HF_TOKEN = "Bearer YOUR_HUGGING_FACE_TOKEN_HERE";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["inputs" => $prompt]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: $HF_TOKEN",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
curl_close($ch);

if (!$response) {
    echo json_encode(["error" => "Failed to connect to API"]);
    exit;
}

// Save the image
$imageData = $response;
$filename = uniqid() . ".png";
file_put_contents($filename, $imageData);

// If you're hosting PHP backend online, save to `images/` and return the URL
$image_url = $filename;

echo json_encode(["image_url" => $image_url]);
?>
