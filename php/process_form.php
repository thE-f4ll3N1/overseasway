<?php
require 'vendor/autoload.php'; // Include the AWS SDK for PHP

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    // Instantiate an Amazon SES client
    $sesClient = new SesClient([
        'version' => 'latest',
        'region' => 'YOUR_AWS_REGION', // Replace with your AWS region
        'credentials' => [
            'key' => 'YOUR_AWS_ACCESS_KEY_ID', // Replace with your AWS access key ID
            'secret' => 'YOUR_AWS_SECRET_ACCESS_KEY', // Replace with your AWS secret access key
        ],
    ]);

    // Compose the email message
    $emailBody = "Name: $name\nEmail: $email\nPhone Number: $phone\nMessage: $message";

    // Send the email using Amazon SES
    try {
        $result = $sesClient->sendEmail([
            'Destination' => [
                'ToAddresses' => ['RECIPIENT_EMAIL_ADDRESS'], // Replace with recipient email address
            ],
            'Message' => [
                'Body' => [
                    'Text' => [
                        'Charset' => 'UTF-8',
                        'Data' => $emailBody,
                    ],
                ],
                'Subject' => [
                    'Charset' => 'UTF-8',
                    'Data' => $subject,
                ],
            ],
            'Source' => 'SENDER_EMAIL_ADDRESS', // Replace with sender email address
        ]);

        echo "Thank you for contacting us! We will get back to you soon.";
    } catch (AwsException $e) {
        echo "Sorry, there was an error sending your message. Please try again later.";
    }
} else {
    // Redirect back to the form if accessed directly
    header("Location: contact_form.html");
    exit;
}
