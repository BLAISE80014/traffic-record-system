<?php
// Google OAuth Configuration
// Replace these with your actual Google OAuth credentials
define('GOOGLE_CLIENT_ID', '855899536999-qmpbretop7uia796mvkghpmdafhirej2.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');

// Update this URL based on your XAMPP setup
// If TRS is in htdocs: http://localhost/TRS/google_callback.php
// If TRS is the root: http://localhost/google_callback.php
define('GOOGLE_REDIRECT_URI', 'http://localhost/TRS/google_callback.php');

// Include Google API Client
require_once 'vendor/autoload.php';

// Create Google Client
$google_client = new Google_Client();
$google_client->setClientId(GOOGLE_CLIENT_ID);
$google_client->setClientSecret(GOOGLE_CLIENT_SECRET);
$google_client->setRedirectUri(GOOGLE_REDIRECT_URI);
$google_client->addScope('email');
$google_client->addScope('profile');
$google_client->setPrompt('select_account consent');
$google_client->setAccessType('offline');
?>