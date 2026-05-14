<?php
// Test database connection and user insertion
include 'db.php';

echo "<h1>Database and User Storage Test</h1>";

// Test connection
if ($conn->connect_error) {
    die("<p style='color: red;'>❌ Database connection failed: " . $conn->connect_error . "</p>");
} else {
    echo "<p style='color: green;'>✅ Database connected successfully</p>";
}

// Check if users table exists
$result = $conn->query("SHOW TABLES LIKE 'users'");
if ($result && $result->num_rows > 0) {
    echo "<p style='color: green;'>✅ Users table exists</p>";
} else {
    echo "<p style='color: red;'>❌ Users table does NOT exist</p>";
    die();
}

// Check table structure
$result = $conn->query("DESCRIBE users");
$columns = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
}

echo "<h2>Users Table Columns:</h2><ul>";
foreach ($columns as $col) {
    echo "<li>$col</li>";
}
echo "</ul>";

$has_google_id = in_array('google_id', $columns);
$has_password = in_array('password', $columns);

if ($has_google_id) {
    echo "<p style='color: green;'>✅ google_id column exists</p>";
} else {
    echo "<p style='color: red;'>❌ google_id column missing - run setup_db.php</p>";
}

if ($has_password) {
    echo "<p style='color: green;'>✅ password column exists</p>";
} else {
    echo "<p style='color: red;'>❌ password column missing</p>";
}

// Test inserting a sample Google user
if ($has_google_id) {
    echo "<h2>Testing Google User Insertion:</h2>";

    $test_name = "Test Google User";
    $test_email = "test.google@example.com";
    $test_google_id = "123456789";

    // Check if test user exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $test_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Insert test user
        $stmt = $conn->prepare("INSERT INTO users (name, email, google_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $test_name, $test_email, $test_google_id);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>✅ Test Google user inserted successfully</p>";
        } else {
            echo "<p style='color: red;'>❌ Failed to insert test user: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p style='color: blue;'>ℹ️ Test user already exists</p>";
    }
}

echo "<p><a href='index.php'>Back to Login</a></p>";
echo "<p><a href='dashboard.php'>Go to Dashboard</a></p>";
?>