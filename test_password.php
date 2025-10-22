<?php
include('inc/config.php');

echo "=== PASSWORD TEST ===\n";

// Test different password combinations
$test_passwords = ['password', '123456', 'admin', 'password123', '1234'];

foreach($test_passwords as $pwd) {
    $hashed = md5($pwd);
    echo "Password: '$pwd' -> MD5: $hashed\n";
    
    $stmt = "SELECT * FROM users WHERE email_addr = 'yaaqa91@gmail.com' AND password = '$hashed'";
    $result = mysqli_query($con, $stmt);
    
    if(mysqli_num_rows($result) > 0) {
        echo "✅ MATCH FOUND for password: '$pwd'\n";
        $row = mysqli_fetch_assoc($result);
        echo "User: {$row['fullname']}\n";
        echo "Status: {$row['status']}\n";
        break;
    }
}

// Check what's actually in the database
echo "\n=== ACTUAL USER DATA ===\n";
$stmt = "SELECT email_addr, password, fullname, status FROM users WHERE email_addr = 'yaaqa91@gmail.com'";
$result = mysqli_query($con, $stmt);

if($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo "Email: {$row['email_addr']}\n";
    echo "Password Hash: {$row['password']}\n";
    echo "Full Name: {$row['fullname']}\n";
    echo "Status: {$row['status']}\n";
} else {
    echo "No user found with email: yaaqa91@gmail.com\n";
}
?>



