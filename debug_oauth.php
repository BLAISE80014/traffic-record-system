<?php
// Debug Google OAuth Configuration
include 'google_config.php';
include 'db.php';

echo "<h1>Google OAuth Debug Information</h1>";

echo "<h2>Configuration</h2>";
echo "<p><strong>Client ID:</strong> " . GOOGLE_CLIENT_ID . "</p>";
echo "<p><strong>Client Secret:</strong> " . substr(GOOGLE_CLIENT_SECRET, 0, 10) . "...</p>";
echo "<p><strong>Redirect URI:</strong> " . GOOGLE_REDIRECT_URI . "</p>";

echo "<h2>Network Tests</h2>";

// Test basic connectivity
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "<p><strong>OAuth endpoint test:</strong> ";
if ($error) {
    echo "<span style='color: red;'>FAILED - $error</span></p>";
} else {
    echo "<span style='color: green;'>SUCCESS - HTTP $http_code</span></p>";
}

// Test Google OAuth discovery
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/.well-known/openid_configuration');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$result = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

echo "<p><strong>OAuth discovery test:</strong> ";
if ($error) {
    echo "<span style='color: red;'>FAILED - $error</span></p>";
} else {
    echo "<span style='color: green;'>SUCCESS</span></p>";
}

echo "<h2>Database Check</h2>";
if ($conn) {
    echo "<p style='color: green;'>Database connection: SUCCESS</p>";

    // Check users table
    $result = $conn->query("SHOW TABLES LIKE 'users'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>Users table exists</p>";

        // Check table structure
        $result = $conn->query("DESCRIBE users");
        echo "<p><strong>Users table structure:</strong></p><ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['Field']} - {$row['Type']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>Users table does not exist</p>";
    }
} else {
    echo "<p style='color: red;'>Database connection: FAILED</p>";
}

echo "<h2>Next Steps</h2>";
echo "<ol>";
echo "<li>Go to <a href='https://console.cloud.google.com/apis/credentials' target='_blank'>Google Cloud Console</a></li>";
echo "<li>Make sure your OAuth 2.0 Client ID has the correct redirect URI: <code>" . GOOGLE_REDIRECT_URI . "</code></li>";
echo "<li>Verify your Client ID and Secret match exactly</li>";
echo "<li>Check that your domain is authorized in OAuth consent screen</li>";
echo "<li>Try accessing your app from the exact URL configured in Google Console</li>";
echo "</ol>";

echo "<p><a href='index.php'>Back to Login</a></p>";
?>