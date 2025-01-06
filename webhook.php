<?php
// Webhook secret (must match the secret set in GitHub webhook settings)
$secret = '121205minh';

// Get the payload and verify the signature
$payload = file_get_contents('php://input');
$signature = 'sha1=' . hash_hmac('sha1', $payload, $secret);

if (hash_equals($signature, $_SERVER['HTTP_X_HUB_SIGNATURE'])) {
    // Decode the payload
    $data = json_decode($payload, true);

    // Check if it's a push event
    if ($data['ref'] === 'refs/heads/main') {
        // Pull the latest changes
        $output = shell_exec('cd /var/www/html/myphpapp && git pull 2>&1');
        file_put_contents('webhook.log', date('Y-m-d H:i:s') . " - Pull output:\n$output\n\n", FILE_APPEND);

        // Optionally, clear any caches or perform other tasks

        // Send a success response to GitHub
        http_response_code(200);
        echo "Webhook received and processed successfully.";
    } else {
        http_response_code(200);
        echo "Webhook received but no action was taken.";
    }
} else {
    // Invalid signature
    http_response_code(403);
    echo "Forbidden - Invalid signature.";
}
?>