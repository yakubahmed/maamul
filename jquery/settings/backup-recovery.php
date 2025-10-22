<?php 
session_start();

include('../../inc/config.php');

date_default_timezone_set('Africa/Nairobi');


if(isset($_POST['new_backup'])){

    $backupFile = '../../maamul_backup.sql';  // Set the backup file name
    createBackup($con, $backupFile);

    echo "completed";
}

function createBackup($con, $backupFile)
{
    $tables = array();
    $result = $con->query("SHOW TABLES");

    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }

    $output = '';

    foreach ($tables as $table) {
        $result = $con->query("SELECT * FROM $table");
        $output .= "DROP TABLE IF EXISTS $table;\n";
        $row2 = $con->query("SHOW CREATE TABLE $table")->fetch_row();
        $output .= $row2[1] . ";\n";

        while ($row = $result->fetch_assoc()) {
            $output .= "INSERT INTO $table VALUES (";
            foreach ($row as $value) {
                $output .= "'" . $con->real_escape_string($value) . "', ";
            }
            $output = rtrim($output, ", ") . ");\n";
        }
    }

    // Write to a backup file
    file_put_contents($backupFile, $output);
}


//Recover db
if(isset($_POST['recover'])){

    $sqlfile = $_FILES["rfile"]["tmp_name"];

    $temp = explode(".", $_FILES["rfile"]["name"]);
    $sqlfile = rand() . date('m-d-y-h-i-s') . '.' . end($temp);

    move_uploaded_file($_FILES["rfile"]["tmp_name"], "../../" . $sqlfile);

    recoverDatabase($con, '../../'.$sqlfile);

    echo "completed";

    
}

function recoverDatabase($con, $backupFile)
{

    

    // Read the SQL backup file
    $sqlCommands = file_get_contents($backupFile);

    // Split SQL commands into individual statements
    $sqlCommands = explode(';', $sqlCommands);

    // Execute each SQL statement
    foreach ($sqlCommands as $sqlCommand) {
        $sqlCommand = trim($sqlCommand);
        if (!empty($sqlCommand)) {
            if (!$con->query($sqlCommand)) {
                echo "Error executing SQL statement: $sqlCommand - " . $con->error;
            }
        }
    }
}



?>