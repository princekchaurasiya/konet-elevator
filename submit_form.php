<?php

function submitToGoogleForm($formData) {
    $googleFormsURL = "https://docs.google.com/forms/d/e/1FAIpQLSd9ptlN0qG7APjlaiWPcdkGuVc3BSXFvWkrFpUs4ieDKTi2Gw/formResponse";
    $googleFormsURL .= '?' . http_build_query($formData);

    $ch = curl_init($googleFormsURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if ($response === false) {
        // Handle cURL error
        $error = "cURL Error: " . curl_error($ch);
        logError($error);
        // You might want to throw an exception or handle the error appropriately
        exit();
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpCode;
}

function logError($message) {
    $logFilePath = 'error_log.txt';
    $logMessage = "[" . date("Y-m-d H:i:s") . "] " . $message . PHP_EOL;
    file_put_contents($logFilePath, $logMessage, FILE_APPEND);
}

echo "Debug Output:<pre>";
print_r($_GET);
echo "</pre>";

// Update the keys to match the actual keys in the $_GET array
if (empty($_GET['entry_1884265043']) || empty($_GET['entry_167369742']) || empty($_GET['entry_524038999']) || empty($_GET['entry_170033134'])) {
    echo "No arguments provided!";
    return false;
}

$name = rawurldecode($_GET['entry_1884265043']);
$phone = rawurldecode($_GET['entry_167369742']); // Use rawurldecode here
$email = rawurldecode($_GET['entry_524038999']);
$requirements = rawurldecode($_GET['entry_170033134']);

$formData = [
    "entry.1884265043" => $name,
    "entry.167369742" => $phone,
    "entry.524038999" => $email,
    "entry.170033134" => $requirements,
];

try {
    $httpCode = submitToGoogleForm($formData);

    if ($httpCode == 200) {
        // Successful submission
        header('Location: ./thank-you.php'); // Update with your actual domain
        exit();
    } else {
        // Submission failed
        header('Location: ./form-submit-failed.php'); // Update with your actual domain
        exit();
    }
} catch (Exception $e) {
    // Log the exception
    logError("Exception: " . $e->getMessage());
    // Handle the exception or redirect to an error page
    header('Location: ./form-submit-failed.php'); // Update with your actual domain
    exit();
}
?>
