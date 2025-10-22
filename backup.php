<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "maamul-v2";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



function createBackup($conn, $backupFile)
{
    $tables = array();
    $result = $conn->query("SHOW TABLES");

    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }

    $output = '';

    foreach ($tables as $table) {
        $result = $conn->query("SELECT * FROM $table");
        $output .= "DROP TABLE IF EXISTS $table;\n";
        $row2 = $conn->query("SHOW CREATE TABLE $table")->fetch_row();
        $output .= $row2[1] . ";\n";

        while ($row = $result->fetch_assoc()) {
            $output .= "INSERT INTO $table VALUES (";
            foreach ($row as $value) {
                $output .= "'" . $conn->real_escape_string($value) . "', ";
            }
            $output = rtrim($output, ", ") . ");\n";
        }
    }

    // Write to a backup file
    file_put_contents($backupFile, $output);
}

$backupFile = 'backup.sql';  // Set the backup file name

createBackup($conn, $backupFile);

echo "Backup completed and saved to $backupFile";



?>
