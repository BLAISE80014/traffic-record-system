<?php
include 'db.php';

echo "<h1>Database Setup Helper</h1>";
echo "<p>This page helps you set up the database for Google OAuth</p>";

// Check if users table exists
echo "<h2>1. Checking users table...</h2>";
$result = $conn->query("DESCRIBE users");
if (!$result) {
    echo "<p style='color: red;'>❌ Error: users table does not exist or cannot be queried</p>";
    echo "<p>Error: " . $conn->error . "</p>";
    exit();
}

echo "<p style='color: green;'>✅ users table exists</p>";

// Show current table structure
echo "<h3>Current table structure:</h3>";
echo "<table border='1'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Default']) . "</td>";
    echo "</tr>";
}
echo "</table>";

// Check if google_id column exists
echo "<h2>2. Checking google_id column...</h2>";
$result = $conn->query("SHOW COLUMNS FROM users WHERE Field='google_id'");
$has_google_id = $result && $result->num_rows > 0;

if ($has_google_id) {
    echo "<p style='color: green;'>✅ google_id column exists</p>";
} else {
    echo "<p style='color: red;'>❌ google_id column does NOT exist</p>";
    echo "<p>Adding google_id column...</p>";
    
    if ($conn->query("ALTER TABLE users ADD COLUMN google_id VARCHAR(255) UNIQUE NULL")) {
        echo "<p style='color: green;'>✅ Successfully added google_id column</p>";
    } else {
        echo "<p style='color: red;'>❌ Error adding column: " . $conn->error . "</p>";
    }
}

// Check if password allows NULL
echo "<h2>3. Checking password column...</h2>";
$result = $conn->query("SHOW COLUMNS FROM users WHERE Field='password'");
if ($result) {
    $row = $result->fetch_assoc();
    if ($row['Null'] == 'YES') {
        echo "<p style='color: green;'>✅ password column allows NULL</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ password column does NOT allow NULL</p>";
        echo "<p>This is needed for Google OAuth users (they don't have passwords)</p>";
        echo "<p>Modifying password column...</p>";
        
        if ($conn->query("ALTER TABLE users MODIFY password VARCHAR(255) NULL")) {
            echo "<p style='color: green;'>✅ Successfully modified password column</p>";
        } else {
            echo "<p style='color: red;'>❌ Error: " . $conn->error . "</p>";
        }
    }
}

echo "<h2>Setup Complete!</h2>";
echo "<p><a href='index.php'>Go to Login</a></p>";
echo "<p><a href='test_oauth.php'>Test OAuth Config</a></p>";
?>
