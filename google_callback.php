<?php
session_start();
include 'db.php';
include 'google_config.php';

if (isset($_GET['code'])) {
    try {
        // Debug: Log the authorization code
        error_log("Received authorization code: " . substr($_GET['code'], 0, 20) . "...");

        // Check if we already have a valid session to prevent double processing
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            error_log("User already authenticated, redirecting to dashboard");
            header('Location: dashboard.php');
            exit();
        }

        $token = $google_client->fetchAccessTokenWithAuthCode($_GET['code']);

        if (!isset($token['error'])) {
            $google_client->setAccessToken($token['access_token']);

            $google_oauth = new Google_Service_Oauth2($google_client);
            $google_account_info = $google_oauth->userinfo->get();

            $google_id = $google_account_info->id;
            $name = $google_account_info->name;
            $email = $google_account_info->email;

            // Debug: Log the Google user data
            error_log("Google OAuth User Data - ID: $google_id, Name: $name, Email: $email");

            // Try to check if user exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            if (!$stmt) {
                error_log("Database error checking user: " . $conn->error);
                die("Database error: " . $conn->error);
            }
            
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 0) {
                // New user, insert - try with google_id first, fallback if column doesn't exist
                $stmt = $conn->prepare("INSERT INTO users (name, email, google_id) VALUES (?, ?, ?)");
                
                if (!$stmt) {
                    // Column might not exist, try without google_id
                    $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
                    $stmt->bind_param("ss", $name, $email);
                    if (!$stmt->execute()) {
                        error_log("Insert error (no google_id): " . $stmt->error);
                        die("Insert error: " . $stmt->error);
                    }
                    error_log("New user inserted without google_id: $name ($email)");
                } else {
                    $stmt->bind_param("sss", $name, $email, $google_id);
                    if (!$stmt->execute()) {
                        error_log("Insert error (with google_id): " . $stmt->error);
                        die("Insert error: " . $stmt->error);
                    }
                    error_log("New user inserted with google_id: $name ($email) - Google ID: $google_id");
                }
            } else {
                // Update existing user with google_id if not already set
                $user = $result->fetch_assoc();
                $stmt = $conn->prepare("UPDATE users SET google_id = ? WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("si", $google_id, $user['id']);
                    $stmt->execute();
                    error_log("Existing user updated with google_id: User ID {$user['id']} - Google ID: $google_id");
                }
            }

            // Get user ID for session
            $stmt = $conn->prepare("SELECT id, name, email FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            header('Location: dashboard.php');
            exit();
        } else {
            error_log("OAuth token error: " . json_encode($token));

            // Handle specific OAuth errors
            $error_msg = isset($token['error_description']) ? $token['error_description'] : $token['error'];

            if ($token['error'] === 'invalid_grant') {
                $error_msg = "Authorization code expired or already used. Please try logging in again.";
                header('Location: index.php?error=' . urlencode($error_msg));
                exit();
            } elseif ($token['error'] === 'invalid_client') {
                $error_msg = "Invalid client credentials. Check your Google OAuth configuration.";
                header('Location: index.php?error=' . urlencode($error_msg));
                exit();
            } elseif ($token['error'] === 'redirect_uri_mismatch') {
                $error_msg = "Redirect URI mismatch. Check your Google Console configuration.";
                header('Location: index.php?error=' . urlencode($error_msg));
                exit();
            }

            die("OAuth error: " . $error_msg);
        }
    } catch (Exception $e) {
        error_log("Exception during OAuth: " . $e->getMessage());
        error_log("Exception details: " . $e->getTraceAsString());
        die("Error: " . $e->getMessage());
    }
}

header('Location: index.php');
exit();
?>