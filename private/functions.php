<?php
    
    function getTotalPages($tot_res_var){
        // They use 10 job items per page.  Extract the total
        // Nr of pages from the total # of results:
        $tot_pgs = 1;  // default
        if (strlen($tot_res_var) > 1) {
            if (substr($tot_res_var,-1) > 0) {
                $tot_pgs = ((int) substr($tot_res_var,0,-1)) + 1;
            } else {
                $tot_pgs = (int) substr($tot_res_var,0,-1);
            }
        }
        return $tot_pgs;
    }

    function polish($debris_var){
        $arrObj = new ArrayObject(array());
        foreach($debris_var as $d){
            /* clean unwanted strings
             www.w3schools.com/PHP/func_string_str_replace.asp
             
             stackoverflow.com/questions/14743812/how-to-use-str-replace-to-replace-single-and-double-quotes */
            
            /* THIS METHOD (php str manipulations) WORKS but currently using (...)->find(), which is more robust.
             
             $find = array("<p class=\"empleoVaca\">","<p class=\"empleoVaca oculto\">","</p>","<span class=\"empleoVaca\">","<span class=\"empleoCier\">","<span aria-hidden=\"true\">","</span>"); // Check for more hidden tags compling in Terminal
             $replace = array("","","","","","");
             
             // append(): www.geeksforgeeks.org/arrayobject-append-function-in-php/
             
             $arrObj->append(trim(str_replace($find,$replace,$d)));
             */
            
            $arrObj->append(trim($d));
        } // foreach
        return $arrObj;
    } // function
    
    /* $elems and $var_elems = jobs as str
     $e and $var_e = job as str
     $debris and $var_debris = job as array
     $d and $e_item = job item as str */
    function prepare_jobprofile($e_var) {
        // replace <i class='aria-hidden'>...</i> with '|' (to be used as separator)
        foreach($e_var->find('i[aria-hidden]') as $i){
            $i->outertext = '|';
        } // foreach
        foreach($e_var->find('a') as $i){
            $i->outertext = '';
        } // foreach
        // Replaces <span>"HOLA"</span> with "HOLA":
        foreach($e_var->find('span') as $i){
            $i->outertext = $i->innertext;
        } // foreach
        // Replaces <p>"HOLA"</p> with "HOLA":
        foreach($e_var->find('p') as $i){
            $i->outertext = $i->innertext;
        } // foreach
        
        $debris = explode('|',$e_var->innertext);
        // 1st element is empty. Remove it:
        array_shift($debris);
        // Get the first 9 items of Job Profile after index 0:
        $debris = array_slice($debris,0,9);
        
        $arrObj_jobitems = polish($debris);
        
        return $arrObj_jobitems;
        
    } // function
    
    // Job Details show after click
    // of the Down Arrow in the Job Profile.
    function prepare_jobdetails($arrObj_elems2_var) {
        $arrObj_details = new ArrayObject(array());

        foreach($arrObj_elems2_var as $e){
            foreach($e->find('a') as $i){
                $i->outertext = '';
            }
            foreach($e->find('i[aria-hidden]') as $i){
                $i->outertext = '|';
            }
            foreach($e->find('li') as $i){
                $i->outertext = $i->innertext;
            }
            // Removing sections: Propósito, Funciones
            // Desired sections (Estudio, Dependencia, Municipio) are enclosed by <ul class="sinVignetas">...</ul>
            $e = implode(" ",$e->find('ul.sinVignetas'));
            //echo gettype($e);
            /* implode() converts array-object type (input) to string type (output), preventing the call of find() afterwards...*/
            $find = array("<ul class=\"sinVignetas\">","</ul>","<span class=\"requLabel\">","</span>");
            $replace = array("","","","","","");
            $e = trim(str_replace($find,$replace,$e));
            $e = explode('|',$e);
            array_shift($e); // removes unwanted first component
        
            $arrObj_details->append($e);
        }
        return $arrObj_details;
    }
    
    function prepare_and_merge($arrObj_elems1_var,$arrObj_elems2_var){
        // Filter outdated job profiles and save their positions
        // to later filter job details...
        $current_date = "0000-01-01"; // default: date("Y-m-d");
        $arrObj_profiles = new ArrayObject(array());
        $arrObj_selection = new ArrayObject(array());
        $max = count($arrObj_elems1_var) - 1;
        
        foreach(range(0, $max) as $i){
            $arrObj_e = prepare_jobprofile($arrObj_elems1_var[$i]);
            // $deadline = Fecha cierre de inscripcion
            // Assuming deadline is in $arrObj_e[7]
            $deadline = trim(explode(':',$arrObj_e[7])[1]);
            if($deadline>$current_date){
                $arrObj_profiles->append($arrObj_e);
                $arrObj_selection->append($i);
            } // if
        } // foreach
        
        $arrObj_details = prepare_jobdetails($arrObj_elems2_var);
        
        // Filter Job Details and merge with Job Profile...
        $n = 0;
        $arrObj_elems = new ArrayObject(array());
        foreach($arrObj_selection as $i){
            // Merge array objects: stackoverflow.com/questions/455700/what-is-the-best-method-to-merge-two-php-objects
            $merged = (object) array_merge((array) $arrObj_profiles[$n], (array) $arrObj_details[$i]);
            $arrObj_elems->append($merged);
            $n = $n + 1;
        }
        return $arrObj_elems;
    }
    
    function post_process_1($arrObj_elems_var, $curr_pg_var) {
        // Some general cleaning...
        // Remove 'Alternative estudio' y 'Alternative experiencia'
        $arrObj_elems_new = new ArrayObject(array());
        foreach($arrObj_elems_var as $arrObj_items_old){
            $arrObj_items_new = new ArrayObject(array());
            foreach($arrObj_items_old as $item){
                $a = trim(explode(':',$item,2)[0]);
                $b = "Alternativa de estudio";
                $c = "Equivalencia de estudio";
                $d = "Alternativa de experiencia";
                $e = "Equivalencia de experiencia";
                if(($a !== $b)&&($a !== $c)&&($a !== $d)&&($a !== $e)){
                    $arrObj_items_new->append($item);
                }
            }
            $arrObj_elems_new->append($arrObj_items_new);
        }
        // More general cleaning...
        $arrObj_elems_new2 = new ArrayObject(array());
        foreach($arrObj_elems_new as $arrObj_items_new){
            $arrObj_select = new ArrayObject(array());
            $arrObj_dependencia = new ArrayObject(array());
            $arrObj_municipio = new ArrayObject(array());
            $max = count($arrObj_items_new) - 1;
            foreach(range(0,$max) as $i){
                $current_item = $arrObj_items_new[$i];
                $a = trim(explode(':',$current_item)[0]);
                $b = "Dependencia";
                $c = "Municipio";
                if(($a == $b)||($a == $c)){
                    $arrObj_select->append($i);
                    if($a == $b){
                        // It also removes comma in 'dependencia' item
                        $aux = explode(":",$current_item)[1];
                        $aux2 = trim(str_replace(",","",$aux));
                        $arrObj_dependencia->append($aux2);
                    } elseif($a == $c) {
                        $aux = explode(":",$current_item)[1];
                        // Remove 'Total Vacantes' after 'Municipio'
                        $aux2 = trim(str_replace(", Total vacantes","",$aux));
                        $arrObj_municipio->append($aux2);
                    }
                }
            }
            
            $arrObj_items_new2 = $arrObj_items_new;
            // Handle duplicated vacancies...
            //$vacancies = explode(':',$arrObj_items_new[8])[1];
            //www.geeksforgeeks.org/removing-array-element-and-re-indexing-in-php/
            // If at least 2 dependencies and 2 municipalities...
            // If(($vacancies > 1)&&(count($arrObj_select) >= 4)){
            // if() loop commented out for good.
            foreach($arrObj_select as $i){
                unset($arrObj_items_new2[$i]); // removing...
            }
            // re-indexing...
            $arrObj_items_new2 = (object) array_values((array) $arrObj_items_new);
            // Remove duplicated dependencies and municipalities...
            $aux = array_unique((array) $arrObj_dependencia);
            $dep = (object) ("Dependencia: ". implode(", ",$aux));
            $aux = array_unique((array) $arrObj_municipio);
            $mun = (object) ("Municipio: ". implode(", ",$aux));
            // HERE you need to prepend "Dependencia: "
            // and "Municipio: " respectively.
            $dep = array_values((array) $dep);
            $mun = array_values((array) $mun);
            //$arrObj_items->append($dep); // not working (?)
            //$arrObj_items->append($mun); // not working (?)
            $arrObj_items_new2 = (object) array_merge((array) $arrObj_items_new2,(array) $dep);
            $arrObj_items_new2 = (object) array_merge((array) $arrObj_items_new2,(array) $mun);
            //} //if
            
            // Add 'page' item:
            $arrObj_items_new2 = (object) array_merge((array) ("Página: ". $curr_pg_var),(array) $arrObj_items_new2);
            $arrObj_elems_new2->append($arrObj_items_new2);
        } //foreach
        return $arrObj_elems_new2;
    }
    
//**************************************************
//**************************************************
    
// Note that most SQL queries are in this PHP file.
// Pros: 1) Easier debugging
// Cons: 1)
    
// The use of column names with space is highly discouraged.
// If you insist a column name such as Asignación_salarial
// can be replaced as `Asignación salarial`.
// I WILL NOT USE col names with space because SQL queries
// from BASH script do not accept `Asignación salarial` sintax.

// Salario = Asignación salarial
// Vacantes = Número de vacantes
// Cierre = Cierre de inscripciones
// Keywords = Palabras clave
    
//************************************************************
    
function make_table_Jobs_tmp(){
    // Old method to fetch config data (no need of parsing):
    //require 'admin_config.php';
    
    // Current method to fetch config data:
    // https://stackoverflow.com/questions/3480186/best-easiest-way-to-parse-configuration-parameters-in-sh-bash-and-php
    
    $cnf = parse_ini_file("admin_config.sh"); // INI format similar to SH
    $servername = $cnf["SERVER"];
    $username = $cnf["USER"];
    $password = $cnf["PASSWORD"];
    $dbname = $cnf["DATABASE"];
    
    try {
        $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
        // SQL Query
        // columns with spacings: https://www.tutorialspoint.com/how-to-select-a-column-name-with-spaces-in-mysql
        // utf8_decode() to handle tildes and ñ in columns' names
        $stmt = $conn->prepare("DROP TABLE IF EXISTS Jobs_tmp; CREATE TABLE Jobs_tmp (id INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT, `Creado` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,`Página` SMALLINT, `Nivel` VARCHAR(25), `Denominación` VARCHAR(250), `Grado` TINYINT, `Código` VARCHAR(10), `OPEC` VARCHAR(50), `Salario` VARCHAR(50), `Convocatoria` VARCHAR(250), `Cierre` DATE NOT NULL, `Vacantes` SMALLINT, `Estudio` TEXT, `Keywords` TEXT, `Dependencia` VARCHAR(5000), `Departamento` VARCHAR(150), `Municipio` VARCHAR(1000)) DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;");
        $stmt->execute();
        return true;
    } catch(PDOException $e) {
        echo "Error: " . "<br>" . $e->getMessage();
    }
        $conn = null;
}
    
function last_pg_loaded(){
    // Old method to fetch config data (no need of parsing):
    //require 'admin_config.php';
    
    // Current method to fetch config data:
    // https://stackoverflow.com/questions/3480186/best-easiest-way-to-parse-configuration-parameters-in-sh-bash-and-php
    
    $cnf = parse_ini_file("admin_config.sh"); // INI format similar to SH
    $servername = $cnf["SERVER"];
    $username = $cnf["USER"];
    $password = $cnf["PASSWORD"];
    $dbname = $cnf["DATABASE"];
    
    try {
        $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
        // SQL Query
        // columns with spacings: www.tutorialspoint.com/how-to-select-a-column-name-with-spaces-in-mysql
        // utf8_decode() to handle tildes and ñ in columns' names
        // "SELECT `Página` FROM Jobs_tmp ORDER BY `Página` DESC LIMIT 1;"
        // "SELECT `Página` FROM Jobs_tmp WHERE id=(SELECT max(id) FROM Jobs_tmp);"
        // query(): https://phpdelusions.net/pdo_examples/select
        $stmt = $conn->query("SELECT `Página` FROM Jobs_tmp ORDER BY id DESC LIMIT 1");
        $result = $stmt->fetch();
        if($result['Página'] == ''){
            return 0;
        }
        return $result['Página'];
    } catch(PDOException $e) {
        echo "Error: " . "<br>" . $e->getMessage();
    }
    $conn = null;
}

function insert2db($arrObj_items_var, $conn){
    // Old method to fetch config data (no need of parsing):
    //require 'admin_config.php';
    
    // Current method to fetch config data:
    //stackoverflow.com/questions/3480186/best-easiest-way-to-parse-configuration-parameters-in-sh-bash-and-php
    
    // Connection and try{} env commented out
    // INI format similar to SH
    //$cnf = parse_ini_file("admin_config.sh");
    //$servername = $cnf["SERVER"];
    //$username = $cnf["USER"];
    //$password = $cnf["PASSWORD"];
    //$dbname = $cnf["DATABASE"];
    
    //try {
         //$conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname", $username, $password);
         // set the PDO error mode to exception
         //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        //*************************************
        // ... use INSERT IF NOT EXISTS to avoid duplicates:
        //*************************************
        // Columns with spacings:
        // www.tutorialspoint.com/how-to-select-a-column-name-with-spaces-in-mysql
        //foreach(){$stmt = $conn->(...); $stmt->bindValue(...);...}
        // Insert record if not exist:
        // thispointer.com/insert-record-if-not-exists-in-mysql/
        // Other:
        // utf8_decode() to handle tildes and ñ in columns' names
        //******************************************
        
        $stmt = $conn->prepare("INSERT INTO Jobs_tmp (`Página`, `Nivel`, `Denominación`, `Grado`, `Código`, `OPEC`, `Salario`, `Convocatoria`, `Cierre`, `Vacantes`, `Estudio`, `Dependencia`, `Municipio`) SELECT * FROM (SELECT :pagina AS `Página`, :nivel AS `Nivel`, :denominacion AS `Denominación`, :grado AS `Grado`, :codigo AS `Código`, :opec AS `OPEC`, :salario AS `Salario`, :convocatoria AS `Convocatoria`, :cierre AS `Cierre`, :vacantes AS `Vacantes`, :estudio AS `Estudio`, :dependencia AS `Dependencia(s)`, :municipio AS `Municipio`) AS temp WHERE NOT EXISTS (SELECT `OPEC` FROM Jobs_tmp WHERE `OPEC` = :opec) LIMIT 1");
        $array_items = (array) $arrObj_items_var;
        $stmt->bindValue(':pagina', trim(explode(': ',$array_items[0])[1]));
        $stmt->bindValue(':nivel', trim(explode(': ',$array_items[1])[1]));
        $stmt->bindValue(':denominacion', trim(explode(': ',$array_items[2])[1]));
        $grado = trim(explode(': ',$array_items[3])[1]);
        if(gettype($grado)== 'string'){
            $grado = 0; // set Grado = 'no aplica' to Grado = 0
        }
        $stmt->bindValue(':grado', $grado);
        $stmt->bindValue(':codigo', trim(explode(': ',$array_items[4])[1]));
        $stmt->bindValue(':opec',trim(explode(': ',$array_items[5])[1]));
        $salario = trim(substr(explode(': ',$array_items[6])[1], 2));
        $salario = substr_replace($salario, ".", -3, 0);
        $salario = substr_replace($salario, "'", -7, 0);
        $stmt->bindValue(':salario', "$". $salario);
        // Item 'Convocatoria': no need for explode()
        $stmt->bindValue(':convocatoria', trim($array_items[7]),2);
        // Handle cierres 'por definir' 2avoid conflict with format DATE
        $cierre = trim(explode(': ',$array_items[8])[1]);
        if($cierre == 'por definir'){ $cierre = '1000-01-01';}
        $stmt->bindValue(':cierre', $cierre);
        // Handle variations in 'vacantes' content:
        $vacantes = explode(',',$array_items[9],2)[0];
        $stmt->bindValue(':vacantes', trim(explode(': ',$vacantes)[1]));
        $estudio = trim(explode(': ',$array_items[10],2)[1]);
        $stmt->bindValue(':estudio', $estudio);
        $dependencia = trim(explode(': ',$array_items[12],2)[1]);
        $stmt->bindValue(':dependencia', $dependencia);
        $municipio = trim(explode(': ',$array_items[13])[1]);
        if(stripos($municipio, "Bogot") !== false) {
            $municipio = "Bogotá D.C.";
        }
        $stmt->bindValue(':municipio',$municipio);
        $stmt->execute();
        //return true;
    //} catch(PDOException $e) {
        //echo "Error: " . "<br>" . $e->getMessage();
    //}
    //$conn = null;
} // function
    
function display_table(){
    /* www.w3schools.com/php/php_mysql_select.asp
       www.php.net/manual/en/functions.arguments.php */
    
    // Old method to fetch config data (no need of parsing):
    //require 'admin_config.php';
    
    // Current method to fetch config data:
    // stackoverflow.com/questions/3480186/best-easiest-way-to-parse-configuration-parameters-in-sh-bash-and-php
    
    $cnf = parse_ini_file("admin_config.sh");  // INI format similar to SH
    $servername = $cnf["SERVER"];
    $username = $cnf["USER"];
    $password = $cnf["PASSWORD"];
    $dbname = $cnf["DATABASE"];
    
    echo "<table style='border: solid 1px black;'>";
    echo "<tr><th>Página</th><th>Nivel</th><th>Denominación</th><th>Grado</th><th>Código</th><th>OPEC</th><th><font color='#FFFFFF'>ss</font>Salario<font color='#FFFFFF'>ss</font></th><th>Convocatoria</th><th>Cierre de inscripciones</th><th>Número de vacantes</th><th>Estudio</th><th>Palabras clave</th><th>Dependencia</th><th>Municipio</th><th>Departamento</th></tr>";
    
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
    
    try {
        $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // SQL Query
        // columns with spacings: https://www.tutorialspoint.com/how-to-select-a-column-name-with-spaces-in-mysql
        // utf8_decode() to handle tildes and ñ in columns' names
        $stmt = $conn->prepare("SELECT `Página`, `Nivel`, `Denominación`, `Grado`, `Código`, `OPEC`, `Salario`, `Convocatoria`, `Cierre`, `Vacantes`, `Estudio`, `Keywords`, `Dependencia`, `Municipio`, `Departamento` FROM Jobs_tmp");
        $stmt->execute();
        
        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
            echo $v;
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
    echo "</table>";
}
?>
