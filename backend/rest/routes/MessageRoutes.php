<?php

require_once 'vendor/autoload.php';
require_once 'data/roles.php';  
require_once 'middleware/AuthMiddleware.php';  

function generate_ai_message($recipient, $occasion, $tone) {
    $apiKey =  Config::OPENAI_API_KEY();
    error_log("Using OpenAI API key: " . substr($apiKey, 0, 8) . "...");

    $prompt = "Write a heartfelt $tone message for a $occasion to someone named $recipient.";

    $postData = [
        "model" => "gpt-3.5-turbo",
        "messages" => [
            ["role" => "system", "content" => "You are a helpful assistant creating personalized flower card messages."],
            ["role" => "user", "content" => $prompt]
        ]
    ];

    $ch = curl_init("https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ["error" => "Curl error: $error"];
    }
    
    // For debugging
file_put_contents('openai_response.log', $response);

    $decoded = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
    return ["error" => "JSON decode error: " . json_last_error_msg()];
}

// If no message content, log the full decoded response for inspection
if (empty($decoded['choices'][0]['message']['content'])) {
    file_put_contents('openai_response_decoded.log', print_r($decoded, true));
    return "No message generated. See openai_response_decoded.log for details.";
}

return $decoded['choices'][0]['message']['content'];

    //return $decoded['choices'][0]['message']['content'] ?? "No message generated.";
}



/**
 * @OA\Post(
 *     path="/ai/message",
 *     summary="Generate a personalized AI flower card message",
  * security={
    *         {"ApiKey": {}}
    *     },
 *     description="Uses OpenAI GPT-3.5 to generate a heartfelt message based on recipient, occasion, and tone.",
 *     tags={"AI"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Personalization data",
 *         @OA\JsonContent(
 *             required={"recipient_name", "occasion", "tone"},
 *             @OA\Property(property="recipient_name", type="string", example="Emma", description="The recipient's first name"),
 *             @OA\Property(property="occasion", type="string", example="Birthday", description="The occasion for the message"),
 *             @OA\Property(property="tone", type="string", example="warm", description="The tone of the message (e.g., warm, funny, romantic)")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Generated message",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Happy Birthday, Emma! Wishing you a day filled with love and joy.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing message personalization data"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error or OpenAI API error"
 *     )
 * )
 */
Flight::route('POST /ai/message', function () {
    Flight::auth_middleware()->authorizeUserTypes([Roles::ADMIN, Roles::USER]);
    $data = Flight::request()->data->getData();

    $recipient = $data['recipient_name'] ?? '';
    $occasion = $data['occasion'] ?? '';
    $tone = $data['tone'] ?? '';

    if (!$recipient || !$occasion || !$tone) {
        Flight::halt(400, "Missing message personalization data.");
    }

    $message = generate_ai_message($recipient, $occasion, $tone);

    if (is_array($message) && isset($message['error'])) {
        Flight::halt(500, $message['error']);
    }

    Flight::json(['message' => $message]);
});


