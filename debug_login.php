<?php
include('inc/config.php');

echo "=== LOGIN DEBUG SCRIPT ===\n";

// Test database connection
if (mysqli_ping($con)) {
    echo "✅ Database connection: OK\n";
} else {
    echo "❌ Database connection: FAILED\n";
    exit();
}

// Check users table structure
echo "\n=== USERS TABLE STRUCTURE ===\n";
$result = mysqli_query($con, 'DESCRIBE users');
if($result) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "{$row['Field']} - {$row['Type']}\n";
    }
} else {
    echo "Error: " . mysqli_error($con) . "\n";
}

// Check existing users
echo "\n=== EXISTING USERS ===\n";
$result = mysqli_query($con, 'SELECT userid, fullname, email_addr, status FROM users');
if($result) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "User: {$row['fullname']} ({$row['email_addr']}) - Status: {$row['status']}\n";
    }
} else {
    echo "Error: " . mysqli_error($con) . "\n";
}

// Test login query with sample data
echo "\n=== TESTING LOGIN QUERY ===\n";
$test_email = "yaaqa91@gmail.com";
$test_password = md5("password123"); // You'll need to know the actual password
$stmt = "SELECT * FROM users WHERE email_addr = '$test_email' AND password = '$test_password'";
$result = mysqli_query($con, $stmt);

if($result) {
    $count = mysqli_num_rows($result);
    echo "Login query test: $count matching users found\n";
    
    if($count > 0) {
        $row = mysqli_fetch_assoc($result);
        echo "Found user: {$row['fullname']}\n";
        echo "Status: {$row['status']}\n";
        echo "2FA Enabled: {$row['2fa_enabled']}\n";
    }
} else {
    echo "Login query error: " . mysqli_error($con) . "\n";
}

echo "\n=== SESSION TEST ===\n";
session_start();
echo "Session ID: " . session_id() . "\n";
echo "Session status: " . session_status() . "\n";

echo "\n🎉 Debug completed!\n";
?>



