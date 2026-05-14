<?php
// Test Google OAuth Configuration
include 'google_config.php';

echo "<h1>Google OAuth Configuration Test</h1>";

$has_real_credentials = true;

if (GOOGLE_CLIENT_ID === '855899536999-qmpbretop7uia796mvkghpmdafhirej2.apps.googleusercontent.com') {
    echo "<p style='color: red;'>❌ ERROR: Client ID not configured!</p>";
    echo "<p>Please update google_config.php with your actual Google Client ID</p>";
    $has_real_credentials = false;
} else {
    echo "<p style='color: green;'>✅ Client ID is configured</p>";
}

if (GOOGLE_CLIENT_SECRET === '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') {
    echo "<p style='color: red;'>❌ ERROR: Client Secret not configured!</p>";
    echo "<p>Please update google_config.php with your actual Google Client Secret</p>";
    $has_real_credentials = false;
} else {
    echo "<p style='color: green;'>✅ Client Secret is configured</p>";
}

echo "<p>Redirect URI: " . GOOGLE_REDIRECT_URI . "</p>";

if ($has_real_credentials) {
    echo "<h2>Quick Network Test</h2>";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo "<p style='color: red;'>❌ Network connectivity issue: $error</p>";
        echo "<p>Check your internet connection and firewall settings.</p>";
    } else {
        echo "<p style='color: green;'>✅ Network connectivity OK</p>";
    }

    echo "<h2>Common Issues & Solutions</h2>";
    echo "<ul>";
    echo "<li><strong>Connection timeout:</strong> Check firewall/antivirus, try different network</li>";
    echo "<li><strong>Invalid client:</strong> Verify Client ID/Secret in Google Console</li>";
    echo "<li><strong>Redirect URI mismatch:</strong> Ensure redirect URI matches Google Console exactly</li>";
    echo "<li><strong>Domain not authorized:</strong> Add your domain to OAuth consent screen</li>";
    echo "</ul>";
}

echo "<p><a href='debug_oauth.php'>Run Detailed Debug</a> | <a href='index.php'>Back to Login</a></p>";
?>