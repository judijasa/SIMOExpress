<?php
// Keyword Generator:
// Extrae palabras clave (carreras, estudios, oficios, etc.) de la lista 'Estudios' de la tabla 'Jobs_tmp'.

// Remove accents
// https://stackoverflow.com/questions/1017599/how-do-i-remove-accents-from-characters-in-a-php-string

// strtr() approach not working (utf8 issue)

function remove_accents($string) {
    if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;
    
    $chars = array(
                   // Decompositions for Latin-1 Supplement
                   chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
                   chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
                   chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
                   chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
                   chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
                   chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
                   chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
                   chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
                   chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
                   chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
                   chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
                   chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
                   chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
                   chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
                   chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
                   chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
                   chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
                   chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
                   chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
                   chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
                   chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
                   chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
                   chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
                   chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
                   chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
                   chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
                   chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
                   chr(195).chr(191) => 'y',
                   // Decompositions for Latin Extended-A
                   chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
                   chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
                   chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
                   chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
                   chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
                   chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
                   chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
                   chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
                   chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
                   chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
                   chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
                   chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
                   chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
                   chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
                   chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
                   chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
                   chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
                   chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
                   chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
                   chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
                   chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
                   chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
                   chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
                   chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
                   chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
                   chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
                   chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
                   chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
                   chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
                   chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
                   chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
                   chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
                   chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
                   chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
                   chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
                   chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
                   chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
                   chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
                   chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
                   chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
                   chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
                   chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
                   chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
                   chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
                   chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
                   chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
                   chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
                   chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
                   chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
                   chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
                   chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
                   chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
                   chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
                   chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
                   chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
                   chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
                   chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
                   chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
                   chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
                   chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
                   chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
                   chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
                   chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
                   chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
                   );
    
    $string = strtr($string, $chars);
    
    return $string;
}
    
// str_contains() only from PHP 8
// Additional feature: includes remove_accents()
function contains($haystack, $needle){
    $result = False;
    $pos = stripos(remove_accents($haystack),remove_accents($needle));
    // strpos() if case-sensitive
    if($pos !== False) {
        $result = True;
    }
    return($result);
}

$cnf = parse_ini_file("admin_config.sh");
$servername = $cnf["SERVER"];
$username = $cnf["USER"];
$password = $cnf["PASSWORD"];
$dbname = $cnf["DATABASE"];
    
try {
    $conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Use $conn->exec() if no results are returned
    // Use $conn->prepare() if using bindValue()
    
    // Query "SELECT Estudio FROM Jobs_tmp LIMIT #1 OFFSET #2"
    // returns only #1 records, starting from record #2 (1st item #2 = 0)
    //$stmt1 = $conn->prepare("SELECT id, Estudio, Convocatoria FROM Jobs_tmp LIMIT 15 OFFSET 0"); // Only for testing
    $stmt1 = $conn->prepare("SELECT id, Estudio, Convocatoria, Municipio, OPEC FROM Jobs_tmp");
    $stmt1->execute();
    $stmt1->setFetchMode(PDO::FETCH_ASSOC);
    $res1 = $stmt1->fetchAll();
    
    //*********************************
    // Feed vals 2 Col 'Palabras Clave'
    // in table 'Jobs_tmp'
    //*********************************
    
    $stmt2 = $conn->prepare("SELECT Núcleo_Básico FROM Static_Data WHERE Núcleo_Básico IS NOT NULL");
    $stmt2->execute();
    $stmt2->setFetchMode(PDO::FETCH_ASSOC);
    $res2 = new RecursiveArrayIterator($stmt2->fetchAll());
    $pres2 = new RecursiveIteratorIterator($res2);
    
    $stmt3 = $conn->prepare("SELECT Especialización FROM Static_Data WHERE Especialización IS NOT NULL");
    $stmt3->execute();
    $stmt3->setFetchMode(PDO::FETCH_ASSOC);
    $res3 = new RecursiveArrayIterator($stmt3->fetchAll());
    $pres3 = new RecursiveIteratorIterator($res3);

    $stmt4 = $conn->prepare("SELECT Otros FROM Static_Data WHERE Otros IS NOT NULL");
    $stmt4->execute();
    $stmt4->setFetchMode(PDO::FETCH_ASSOC);
    $res4 = new RecursiveArrayIterator($stmt4->fetchAll());
    $pres4 = new RecursiveIteratorIterator($res4);
    
    // limit # of new careers reports to $upper_bound
    $counter = 0; // Detect new careers
    $upper_bound = 5; // Detect new careers
    $flag = True; // Detect new careers
    
    $kmax = count($res1)-1;
    foreach(range(0,$kmax) as $k) {
        // Remove 'EDUCACIÓN' here, otherwise wrongly taken as career 'Educación'
        $v = str_replace('EDUCACIÓN','',$res1[$k]["Estudio"]);
        // Remove space between words to bypass
        // the bug of unexpected missing spaces
        // in the scraping of Estudios info.
        $v = str_replace(' ','',$v);
        
        $keywords = "";
        $foo = False;
        foreach($pres2 as $i=>$j) {
            if(contains($v,str_replace(' ','',$j))) {
                if($foo){
                    $keywords .= ", ";
                }
                $keywords .= $j;
                $foo = True;
            }
        }

        foreach($pres3 as $i=>$j) {
            if(contains($v,str_replace(' ','',$j))) {
                if($foo){
                    $keywords .= ", ";
                }
                $keywords .= $j;
                $foo = True;
            }
        }
        
        foreach($pres4 as $i=>$j) {
            if(contains($v,str_replace(' ','',$j))) {
                if($foo){
                    $keywords .= ", ";
                }
                $keywords .= $j;
                $foo = True;
            }
        }
    
        //********************************
        // Remove redunancies from findings e.g.
        // (Admin, Admin de Empresas,...)
        // becomes (Admin de Empresas,...)
        //********************************
        
        if($foo){
            $explode_keywords = explode(", ", $keywords);
            $filter_explode_keywords = $explode_keywords;
            
            $first_complement = array(); // Detect new careers (optional)
            $r = 0; // Detect new careers (optional)
            foreach(range(0,count($explode_keywords)-1) as $j){
                if(substr_count($keywords,$explode_keywords[$j]) > 1){
                    // remove item at index $j
                    unset($filter_explode_keywords[$j]);
                    
                    // first_complement: Detect new careers (optional)
                    $first_complement[$r] = $explode_keywords[$j];
                    $r = $r+1; // Detect new careers (optional)
                }
            }
            // filter_explode_keywords now has 'Ing. Admin. y Finanzas'
            // but not 'Ing. Admin.', 'Ing.', 'Admin.', 'Finanzas'
            // first_complement has 'Ing. Admin.', 'Ing.', 'Admin.', 'Finanzas'
            // but not 'Ing. Admin. y Finanzas'.
            
            // Re-index array
            $filter_explode_keywords = array_values($filter_explode_keywords);
            $reduce_keywords = implode(", ",$filter_explode_keywords);
        }
        
        // The following module named 'Detect new careers'
        // is OPTIONAL.  It can be commented out if you want.
        //**************************
        // BEGIN: Detect new careers
        //**************************
            
        //************************************
        // Example:
        // First, remove: 'Ing. Admin. y Finanzas'
        // Second, remove: 'Ing. Admin.'
        // Finally, remove: 'Ing.', 'Admin.'
        //************************************
            
        // Second round to remove redundancies
        if($foo){
            if(count($first_complement) > 0){
                $implode_first_complement = implode($first_complement);
                $filter_explode_reduce_keywords = $first_complement;
                $second_complement = array();
                $r = 0;
                foreach(range(0,count($first_complement)-1) as $j){
                    if(substr_count($implode_first_complement,$first_complement[$j]) > 1){
                        // remove item at index $j
                        unset($filter_explode_reduce_keywords[$j]);
                        $second_complement[$r] = $first_complement[$j];
                        $r = $r+1;
                    }
                }
                // filter_explode_reduce_keywords now has 'Ing. Admin.'
                // but not 'Ing.', 'Admin.', 'Finanzas'
                // second_complement has 'Ing.', 'Admin.', 'Finanzas'
                // but not 'Ing. Admin.'
                
                // Second round to Re-index array
                $filter_explode_reduce_keywords = array_values($filter_explode_reduce_keywords);
                $reduce_reduce_keywords = implode(", ",$filter_explode_reduce_keywords);
            } //if
            
            // BEGIN: first round str_replace()
            // replace 'Ing. Admin. y Finanzas'
            $reduce_v = str_replace($filter_explode_keywords, '_',$v);
            // replace 'Ing. Admin.'
            if(count($first_complement) > 0){
                $reduce_v = str_replace($filter_explode_reduce_keywords, '_',$reduce_v);
            }
            // Later (2nd round) we replace single words
            // END: first round str_replace()
        } //if

        if(!$foo){$reduce_v = $v;}
        
        // Remove capitalized words that are not a career.
        // WARNING: In array below, do not include letters
        // that could be part of new career name
        $not_a_career = array('Afines','afin','aprobar','Área','Áreas','Artículo','artículo','autoridad','Básico','Basico','Básicos','Basicos','cátedra','C-','capacitación', 'Conocimiento','conocimiento','competente','Curso','Cursar','Del','determinados','Decreto','demás','Disciplina','EDUCACIÓN','EDUCACION','Especialización','establecida','Formación','formación','intensidad','Ley','Licencia','Matrícula', 'Matricula','mínima','Modalidad','Nucleo','Núcleo', 'Núcleos','Nucleos','NBC','Otros','Postgrado','profesional','Profesional','programa','Relacionada','requisitos','SENA','SG','SST','Servicios','Tarjeta','Título','Titulo');
        $reduce_v = str_replace($not_a_career,'_',$reduce_v);
        $to_print_reduce_v = $reduce_v;
        
        // BEGIN: Remove 1st word and 1st word after dot
        // (because they are capitalized but are not a career)
        $explode_reduce_v = explode('.', $reduce_v);
        $post_explode_reduce_v = array();
        foreach(range(0,count($explode_reduce_v)-1) as $h){
            // Remove 1st word after dot
            $post_explode_reduce_v[$h]  = trim(strstr(trim($explode_reduce_v[$h]), ' '));
        }
        $reduce_v = implode('_',$post_explode_reduce_v);
        // END: Remove 1st word and 1st word after dot
        
        // BEGIN: 2nd round of str_replace()
        // (this time without space between words)
        if($foo){
            // replace 'Ing.Admin.yFinanzas'
            $reduce_v = str_replace(explode(',',str_replace(' ','',$reduce_keywords)), '_',str_replace(' ','',$reduce_v));
            if(count($first_complement) > 0){
                // replace 'Ing.Admin.'
                $reduce_v = str_replace(explode(',',str_replace(' ','',$reduce_reduce_keywords)), '_',$reduce_v);
                // replace 'Ing.', 'Admin.', 'Finanzas'
                $reduce_v = str_replace($second_complement, '_',$reduce_v);
            }
        }
        // END: 2nd round of str_replace()
        
        // Report only if captial letters remain...
        if(preg_match('/[A-Z]/', $reduce_v) && ($counter < $upper_bound)){
            $counter = $counter + 1;
            if($flag){echo "Detected possible new careers:". PHP_EOL. PHP_EOL; }
            $flag = False;
            
            //****** BEGIN: only for testing ******
            //echo "k = ". $k. PHP_EOL; // 4testing
            //echo "keywords: ". $keywords. PHP_EOL;
            //echo "implode(filter_explode_keywords): ". implode(',', $filter_explode_keywords). PHP_EOL;
            //if(count($filter_explode_reduce_keywords) > 0){echo "implode(filter_explode_reduce_keywords): ". implode(',',$filter_explode_reduce_keywords). PHP_EOL;}
            //if(count($second_complement) > 0){echo "implode(second_complement): ". implode(',',$second_complement). PHP_EOL;}
            //echo PHP_EOL;
            //if($k == 66){echo $v. PHP_EOL.PHP_EOL.PHP_EOL;}
            //****** END: only for testing *********

            echo "OPEC: ". $res1[$k]["OPEC"]. PHP_EOL. $reduce_v. '"'. PHP_EOL. PHP_EOL;
        } // if
        //***************************
        // END: Detect new careers
        //***************************
        
        // Updating Table 'Jobs_tmp' in its Column 'Keywords'
        
        // WRONG: "INSERT INTO Jobs_tmp (`Keywords`) VALUES (:w) WHERE id= :id"
        // INSERT assumes the target row doesn't exist. Use UPDATE:
        // stackoverflow.com/questions/485039/mysql-insert-query-doesnt-work-with-where-clause
        
        $stmt = $conn->prepare("UPDATE Jobs_tmp SET Keywords = :w WHERE id = :id");
        // Use bindValue() with prepare() instead of exec().
        $stmt->bindValue(':id', $res1[$k]["id"]);
        $stmt->bindValue(':w', $reduce_keywords);
        $stmt->execute();
    } // foreach
    
    //*********************************
    // Feed vals 2 Col 'Departamento'
    // in table 'Jobs_tmp'
    //*********************************
    
    //**********************************
    // Similar routine as the one above.
    // Specify the Departamento to every
    // Municipio from Table 'Jobs_tmp'
    //**********************************
    
    $stmt = $conn->prepare("UPDATE Jobs_tmp SET Departamento = 'Bogotá D.C.' WHERE Municipio = 'Bogotá D.C.'");
    $stmt->execute();
    
    // Renaming $stmt2...
    $stmt2 = $conn->prepare("SELECT Departamento FROM Static_Data WHERE Departamento IS NOT NULL");
    $stmt2->execute();
    $stmt2->setFetchMode(PDO::FETCH_ASSOC);
    $res2 = new RecursiveArrayIterator($stmt2->fetchAll());
    $pres2 = new RecursiveIteratorIterator($res2);
    
    // Renaming $stmt3...
    // To Do: Make Col UAE in Table Static_Data
    // AUE : Unidad Administrativa Especial
    //$stmt3 = $conn->prepare("SELECT UAE FROM Static_Data");
    //$stmt3->execute();
    //$stmt3->setFetchMode(PDO::FETCH_ASSOC);
    //$res3 = new RecursiveArrayIterator($stmt3->fetchAll());
    //$pres3 = new RecursiveIteratorIterator($res3);
    
    // Find Departamento in Dependencia from Jobs_tmp
    foreach(range(0, $kmax) as $k) {
        $v = $res1[$k]["Convocatoria"];
        //echo $v. " || ";  // Only for testing
        
        $flag = False;
        if(contains($v, "Valle del Cauca")){
            $dept_found = "Valle del Cauca";
            $flag = True;
        }else{
            foreach($pres2 as $i=>$j) {
                if(contains($v,$j)) {
                    $dept_found = $j;
                    $flag = True;
                    break;
                }
            }
        }
        
        if($flag){
        // Renaming $stmt...
        $stmt = $conn->prepare("UPDATE Jobs_tmp SET Departamento = :w WHERE id = :id");
        $stmt->bindValue(':id', $res1[$k]["id"]);
        $stmt->bindValue(':w', $dept_found);
        $stmt->execute();
        }
    } // foreach
} catch(PDOException $e) {
    echo "Error: " . "<br>" . $e->getMessage();
}
$conn = null;
?>
