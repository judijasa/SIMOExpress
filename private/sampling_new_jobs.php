<?php
    // *****
    // Name: sampling_new_jobs.php
    //
    // This program generates OPEC list of some
    // the new jobs fetched from SIMO Official Website
    
    // *****
    
    class TableRows extends RecursiveIteratorIterator {
        function __construct($it) {
            parent::__construct($it, self::LEAVES_ONLY);
        }
        
        function current() {
            return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
        }
        
        function beginChildren() {
            echo "<tr>";
        }
        
        function endChildren() {
            echo "</tr>" . "\n";
        }
    }
    
    //************************************
    // Compare tables in MySQL:
    // www.mysqltutorial.org/compare-two-tables-to-find-unmatched-records-mysql.aspx
    //************************************
    
    $cnf = parse_ini_file("admin_config.sh"); // INI format similar to SH
    $servername = $cnf["SERVER"];
    $username = $cnf["USER"];
    $password = $cnf["PASSWORD"];
    $dbname = $cnf["DATABASE"];
    
    try {
        $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // *** BEGIN: Only for testing ***
        $conn->exec("DROP TABLE IF EXISTS Not_new_jobs");
        // *** END: Only for testing ***
        
        // The table Not_new_jobs should exist,
        // except the 1st time. If exist, it should
        // be a copy of Jobs before recent update
        $conn->exec("CREATE TABLE IF NOT EXISTS Not_new_jobs LIKE Jobs");
        
        // ***
        
        $stmt1 = $conn->prepare("SELECT `Cierre`, COUNT(*) FROM (SELECT `OPEC`, `Cierre` FROM (SELECT `OPEC`, `Cierre` FROM Jobs UNION ALL SELECT `OPEC`, `Cierre` FROM Not_new_jobs) New_jobs GROUP BY `OPEC`, `Cierre` HAVING COUNT(*) = 1 ORDER BY `OPEC`) New_jobs GROUP BY `Cierre`;");
        $stmt2 = $conn->prepare("SELECT `OPEC`, `Cierre` FROM (SELECT `OPEC`, `Cierre` FROM Jobs UNION ALL SELECT `OPEC`, `Cierre` FROM Not_new_jobs) New_jobs GROUP BY `OPEC`, `Cierre` HAVING COUNT(*) = 1 ORDER BY `OPEC`;"); // LIMIT 5 (test)
        
        $stmt1->execute();
        $stmt2->execute();
        
        $stmt1->setFetchMode(PDO::FETCH_ASSOC);
        $stmt2->setFetchMode(PDO::FETCH_ASSOC);
        
        $var_1 = $stmt1->fetchAll();
        $var_2 = $stmt2->fetchAll();
        
        echo count($var_2). " <b>new jobs</b> added after recent download.";
        
        //**********************************
        // 1st Table: # de empleos por cada
        // cierre de inscripiciones
        //**********************************
    
        echo "<br><table style='border: solid 1px black;'>";
        echo "<caption>New jobs added, grouped by deadline</caption>";
        echo "<tr><th>Cierre de inscripciones</th><th>Número de Empleos</th></tr>";
        
        
        foreach(new TableRows(new RecursiveArrayIterator($var_1)) as $k=>$v) {
            echo $v;
        }
        echo "</table>";

        //**********************************
        // 2nd Table: Some new Jobs by OPEC
        //**********************************
        
        // Table might be too big for
        // email hence limit shown rows:
        $rowmax = 10;
        
        echo "<br><table style='border: solid 1px black;'>";
        echo "<caption>First ". $rowmax. " new jobs added, ordered by OPEC</caption>";
        echo "<tr><th>OPEC</th><th>Cierre de inscripciones</th></tr>";
        
        foreach(new TableRows(new RecursiveArrayIterator(array_slice($var_2,0,$rowmax))) as $k=>$v) {
            echo $v;
        }
        echo "</table>";

    $conn->exec("DELETE FROM Not_new_jobs; INSERT INTO Not_new_jobs SELECT * FROM Jobs;");
        
    } catch(PDOException $e) {
        echo "Error: " . "<br>" . $e->getMessage();
    }
    $conn = null;
?>
