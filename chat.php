<?php

require 'vendor/autoload.php';

$chat_history = $_POST['chat_history'];

// Convert chat messages to array of objects that OpenAI can use.
$messages = [];
foreach ($chat_history as $history) {
    $messages[] = (object) $history;
}

$api = new OpenAI\OpenAIChat('YOUR API KEY');

$chat_reply = '';

try {
    $response = $api->create('gpt-3.5-turbo', $messages);

    // Get the chat reply from the API response.
    $message = current(reset($response));
    if (!empty($message)) {
        $chat_reply = $message->content;
    }
} catch (OpenAI\OpenAIException $e) {
    $chat_reply = $e->getMessage();
}

// Respond with JSON.
header('Content-type: application/json; charset=utf-8');

echo json_encode((object) ['chat_reply' => $chat_reply]);
