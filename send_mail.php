<?php
// Set the email address where the form data will be sent
$webmaster_email = "vickykarthik14@gmail.com";

// Set the URLs for the form, error, and thank you pages
$feedback_page = "feedback_form.html";
$error_page = "error_message.html";
$thankyou_page = "thank_you.html";

// Load form field data and sanitize inputs to prevent XSS
$first_name = htmlspecialchars(trim($_POST['first_name']));
$email_address = htmlspecialchars(trim($_POST['email_address']));
$comments = htmlspecialchars(trim($_POST['comments']));
$number = htmlspecialchars(trim($_POST['number']));

// Prepare the message for the email
$msg = "Name: " . $first_name . "\r\n" .
       "Email: " . $email_address . "\r\n" .
       "Contact_number: " . $number . "\r\n" .
       "Comments: " . $comments;

// Function to check for email injection
function isInjected($str) {
    $injections = array('(\n+)', '(\r+)', '(\t+)', '(%0A+)', '(%0D+)', '(%08+)', '(%09+)');
    $inject = join('|', $injections);
    return preg_match("/$inject/i", $str);
}

// If the user tries to access this script directly, redirect them to the feedback form
if (!isset($_POST['email_address'])) {
    header("Location: $feedback_page");
    exit();
}

// If required fields are empty, redirect to the error page
elseif (strlen($first_name) == 0 || strlen($email_address) == 0) {
    header("Location: $error_page");
    exit();
}

// If the email is not valid, redirect to the error page
elseif (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
    header("Location: $error_page");
    exit();
}

// If email injection is detected, redirect to the error page
elseif (isInjected($email_address) || isInjected($first_name) || isInjected($comments)) {
    header("Location: $error_page");
    exit();
}

// If everything is valid, send the email and redirect to the thank you page
else {
    if (mail($webmaster_email, "Feedback Form Results", $msg)) {
        header("Location: $thankyou_page");
    } else {
        header("Location: $error_page");
    }
    exit();
}
?>
