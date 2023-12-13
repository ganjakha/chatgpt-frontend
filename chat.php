<?php

require 'vendor/autoload.php';

$chat_history = $_POST['chat_history'];

// Convert chat messages to array of objects that ChatGPT can use.
$messages = [];
foreach ($chat_history as $history) {
    $messages[] = (object) $history;
}

// Use the unofficial API endpoint and the free API key
$api_url = 'https://ngoctuanai-gpt4api.hf.space/api/openai/chat/completions';
$api_key = 'free';

$chat_reply = '';

try {
    // Create a HTTP client with the API key as a header
    $client = new GuzzleHttp\Client(['headers' => ['x-api-key' => $api_key]]);

    // Send a POST request to the API with the chat messages as JSON data
    $response = $client->post($api_url, ['json' => $messages]);

    // Parse the response body as JSON
    $data = json_decode($response->getBody(), true);

    // Get the chat reply from the data
    $message = current(reset($data));
    if (!empty($message)) {
        $chat_reply = $message['content'];
    }
} catch (Exception $e) {
    $chat_reply = $e->getMessage();
}

// Respond with JSON.
header('Content-type: application/json; charset=utf-8');

echo json_encode((object) ['chat_reply' => $chat_reply]);
