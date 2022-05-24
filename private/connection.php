<?php
    // This file is a dependency of index.php
    // https://www.javatpoint.com/php-pagination
    
    // PHP Connect to MySQL
    // https://www.w3schools.com/php/php_mysql_connect.asp
    
    $cnf = parse_ini_file("client_config.sh"); // INI format similar to SH
    $servername = $cnf["SERVER"];
    $username = $cnf["USER"];
    $password = $cnf["PASSWORD"];
    $dbname = $cnf["DATABASE"];
    
    $conn = mysqli_connect($servername, $username, $password);
    if (! $conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else {
        mysqli_select_db($conn, $dbname);
    }
    
    /* // Highly compatible alternative...
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected successfully";
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
     */
?>
