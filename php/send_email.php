<?php
require 'vendor/autoload.php'; // Include the AWS SDK for PHP

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST["email"];

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
    $emailBody = "A new email subscription request:\nEmail: $email";

    // Send the email using Amazon SES
    try {
        $result = $sesClient->sendEmail([
            'Destination' => [
                'ToAddresses' => ['YOUR_RECIPIENT_EMAIL_ADDRESS'], // Replace with recipient email address
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
                    'Data' => 'New Email Subscription Request',
                ],
            ],
            'Source' => 'YOUR_SENDER_EMAIL_ADDRESS', // Replace with sender email address
        ]);

        // Redirect or provide feedback to the user
        echo "success";
    } catch (AwsException $e) {
        // Handle errors
        echo "error";
    }
} else {
    // Redirect back to the form if accessed directly
    header("Location: contact_form.html");
    exit;
}
