<?php
// Define your secret token here for GitHub webhook security
$secret = '121205minh';
$logFile = '/var/www/html/log/webhook.log'; // Log file path (ensure write permissions)

// Validate the webhook request
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';

// Process webhook event based on the event type
$event = $_SERVER['HTTP_X_GITHUB_EVENT'] ?? '';

// Log incoming webhook
logEvent("Received event: $event");
logEvent("Payload: " . substr($payload, 0, 500)); // Log only the first 500 characters for brevity

switch ($event) {
    case 'push':
        handlePushEvent($payload);
        break;
    case 'pull_request':
        handlePullRequestEvent($payload);
        break;
    // Add more cases for other GitHub events as needed
    default:
	logEvent("Unsupported event: $event");
        http_response_code(400); // Bad Request
        die('Unsupported event');
}

// Handle push event (example function)
function handlePushEvent($payload) {
    logEvent("Handling push event");
    // You can perform additional logic here if needed
    // Example: Trigger deployment by calling deploy.php
    $deployOutput = shell_exec('php /var/www/html/myphpapp/deploy.php
    logEvent("Deployment output:\n$deployOutput");
    echo "<pre>Deployment output:\n$deployOutput</pre>";
}

// Handle pull request event (example function)
function handlePullRequestEvent($payload) {
    // Example: Notify team or perform other actions for pull requests
    logEvent("Handling pull request event");
    echo "Pull request event received\n";
}

// Function to log events
function logEvent($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Respond with success status
http_response_code(200);
?>
