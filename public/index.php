<!doctype html>
<html>

<!--
Content: Display SQL Tables with pagination
Source: www.javatpoint.com/php-pagination

Browser address:
http://localhost/web-projects/scraping_SIMO/index.php

Author: judijasa <ciudadania.ab@gmail.com>
-->

<head>
    <title>SimoEx</title>
    <meta name="viewport" charset="utf-8" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="mystyle.css">

    <!-- Bootstrap -->
    <!-- <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

    <!-- Load search icon library
    www.w3schools.com/howto/howto_css_search_button.asp
    nothing here
    -->

    <!-- Load arrow icon script src
    www.w3schools.com/icons/tryit.asp?icon=fas_fa-angle-left&unicon=f104
    -->
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

    <!-- Twitter Bootstrap: Button to match the style of the select menu with selectBoxIt

    www.c-sharpcorner.com/UploadFile/736ca4/twitter-bootstrap-3-layout-and-buttons/
    -->

    <link href="bootstrap/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="bootstrap/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
    <script src="bootstrap/bootstrap/js/bootstrap.min.js"></script>

    <!--
         To handle long text in select options
         Required links:
         gregfranko.com/jquery.selectBoxIt.js/#GettingStarted
         Theme: SelectBoxIt with Twitter Bootstrap
    -->
        <link type="text/css" rel="stylesheet" href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" />
        <link type="text/css" rel="stylesheet" href="http://gregfranko.com/jquery.selectBoxIt.js/css/jquery.selectBoxIt.css" />
</head>
<body>
    <!-- <p id="demo"></p> -->
    <?php
        
        $items_per_page = 5;  // entries per page
        
        if (isset($_GET["page"])) {
            $page  = intval($_GET["page"]);
        }
        else {
            $page = 1;
        }
        
        if (isset($_GET["dept"])) {
            $dept  = intval($_GET["dept"]);
        }
        else {
            $dept = -1;
        }
        
        //***********************************
        // Get total pages...
        //***********************************
       
        $today = '1001-01-01'; //date("Y-m-d");
 
        // Import file where we define connection to Database
        require_once "../private/connection.php";
        
        $query = "SELECT COUNT(*) FROM Jobs WHERE `Cierre` > '$today'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_row($result);
        $total_records = $row[0];
        
        echo "</br>";
        // Number of pages required.
        $total_pages = ceil($total_records / $items_per_page);
        
        //*******************************
        // Get current page records...
        //*******************************
        
        $start_from = ($page-1) * $items_per_page;
        
        //// Without limit includes null components (?)
        //"SELECT Departamento FROM Static_Data LIMIT 0, 33" (working alt)
        $query = "SELECT Departamento FROM Static_Data WHERE Departamento IS NOT NULL";
        $result_depts = mysqli_query($conn, $query);
        $row = mysqli_fetch_all($result_depts);
        mysqli_free_result($result_depts);
        //print_r($row);
        $arr_length = count($row);
        
        $query = "SELECT * FROM Jobs WHERE `Cierre` > '$today' LIMIT $start_from, $items_per_page";
        
        if($dept !== -1){
        $str_dept = $row[$dept][0];
        $query = "SELECT * FROM Jobs WHERE `Cierre` > '$today' AND `Departamento` = '$str_dept' LIMIT $start_from, $items_per_page";
        }
        $result_jobs = mysqli_query($conn, $query);
        ?>

    <div class="container">
        <center>
        <h1>SIMO Express</h1>
        <p style="margin-bottom:16px;">
        <i>Ofertas de empleo público en Colombia</i>
        </p>
        <p style="margin-bottom:32px;">
        Visite la página oficial:<br>
        <a href="https://simo-ppal.cnsc.gov.co/#ofertaEmpleo"><i>Sistema de apoyo para la Igualdad, el Mérito y la Oportunidad</i> (SIMO)</a>
        </p>
        <div align="left">

            <!--******************-->
            <!--** Search Depto **-->
            <!--******************-->

            <p>Buscar por departamento:</p>

            <!--
            onchange:
            stackoverflow.com/questions/647282/is-there-an-onselect-event-or-equivalent-for-html-select
            -->

            <select id="dept" onChange="go2Dept();">
            <?php
                if($dept == -1){
                    echo "<option selected value=-1> -- todos los deptos -- </option>";
                }else{
                    echo "<option value=-1> -- todos los deptos -- </option>";
                }
                
                $i = 0; // initial val in option
                /* Also works but since we already
                   fetched all data from $result_depts we
                   better not fetch again.
                while($row = mysqli_fetch_row($result_depts)) {
                    if(!$row[0]){
                        break;
                    }
                    if($dept == $i) {
                        echo "<option selected value=$i>". $row[0]. "</option><br>";
                    }else {
                        echo "<option value=$i>". $row[0]. "</option><br>";
                    }
                    $i++;
                }; */
                for($x = 0; $x<$arr_length; $x++) {
                    if($dept == $i) {
                        echo "<option selected value=$i>". $row[$x][0]. "</option><br>";
                    }else {
                        echo "<option value=$i>". $row[$x][0]. "</option><br>";
                    }
                    $i++;
                };
                ?>
            </select>

            <!--*****************-->
            <!--** Search Page **-->
            <!--*****************-->

            <!--***** BEGIN comment *******
            Adjust column width (not working):
            stackoverflow.com/questions/928849/setting-table-column-width
                ***** END comment **********-->

            <br>
            <br>
            <p><span style="font-size:normal;">P&aacute;gina (m&aacute;x. <?php echo $total_pages;?>):</span></p>

            <table class="table table-bordered" style="width:30%;">
            <tr>
            <td>

            <!-- ********** BEGIN comment ******
            Alternatives:
            www.w3schools.com/bootstrap/bootstrap_forms_sizing.asp
            <div class="col-xs-3">
                 ********** END comment ******** -->

            <input id="page" type="text" placeholder="<?php echo $page; ?>" required>
            </td>
            <td>
            <button class="btn" onClick="go2Page();"><i class="fa fa-search"></i></button>
            </td>
            </tr>
            </table>
        </div>

        <!--*********************-->
        <!--** Jobs Data Table **-->
        <!--*********************-->

        <div class="hscroll">
        <table class="table table-striped table-condensed table-bordered">
        <thead>
        <tr>
        <th>Palabras clave</th>
        <th>Municipio</th>
        <th>Salario</th>
        <th>Cierre de inscripciones</th>
        <th>OPEC</th>
        </tr>
        </thead>
        <tbody>
        <?php
            
            while ($row = mysqli_fetch_array($result_jobs)) {
                // Display each field of the records.
                ?>
        <tr>
        <!-- <td><?php echo $row["Nivel"]. ". ". $row["Keywords"]; ?></td> -->
        <td><?php
            $text = $row["Nivel"]. ". ". $row["Keywords"];
            if(isset($_GET["width"])){
                if($_GET["width"] < 992){
                    $text = wordwrap($text, 50, "<br>", false);
                }
            }
            echo "$text";
            ?></td>
        <td><?php
            if(stripos($row["Municipio"], "Bogot") !== false){
                echo "Bogotá D.C.";
            }else{
                $text = $row["Municipio"];
                $newtext = wordwrap($text, 30, "<br>", false);
                echo "$newtext";
                //echo $row["Municipio"];
                if(isset($row["Departamento"])){
                    echo ", ". $row["Departamento"];
                }
                //echo $row["Municipio"]. ", ". $row["Departamento"];
            }?></td>
        <td><?php echo $row["Salario"]; ?></td>
        <td><?php echo $row["Cierre"]; ?></td>
        <td><?php echo $row["OPEC"]; ?></td>
        </tr>
        <?php
            };
            mysqli_free_result($result_jobs);
            mysqli_close($conn);
        ?>
        </tbody>
        </table>
        </div>

        <div class="pagination">
            <?php

                $pagLink = "";
                $here = basename(__FILE__); // name of current file 
               
                if($page>=2){
                    echo "<a href='". $here. "?page=".($page-1). "&dept=". $dept. "'><i class='fas fa-angle-left' style='font-size:24px'></i></a>";
                }
                
                $pagLink .= "<a class = 'active' href=''>".$page."</a>";
                
                echo $pagLink;
                
                if($page<$total_pages){
                    echo "<a href='". $here. "?page=".($page+1). "&dept=". $dept. "'><i class='fas fa-angle-right' style='font-size:24px'></i></i></a>";
                }
            ?>
        </div>
        </center>
    </div>

    <script>
    window.onload = function ()
    {
        let width = screen.width;
        var val = "<?php echo isset($_GET['width']);?>";
        var here = "<?php echo $here;?>";
        //document.getElementById("demo").innerHTML = width+"px";
        if(!val){
            window.location.href = here+'?width='+width;
        }
    }

    function go2Dept()
    {
        var width = "<?php echo $_GET['width'];?>";
        var page = "<?php echo $page;?>";
        var dept = document.getElementById("dept").value;
        var here = "<?php echo $here;?>";
        window.location.href = here+'?width='+width+'&page='+page+'&dept='+dept;
    }

    function go2Page()
    {
        var width = "<?php echo $_GET['width'];?>";
        var page = document.getElementById("page").value;
        var dept = "<?php echo $dept;?>";
        var here = "<?php echo $here;?>";
        page = ((page><?php echo $total_pages; ?>)?<?php echo $total_pages; ?>:((page<1)?1:page));
        window.location.href = here+'?width='+width+'&page='+page+'&dept='+dept;
    }

    // Not possible while using Bootstrap:
    // set input field size to placeholder length:
    //input.setAttribute('size',30px);
    //input.getAttribute('placeholder').length

    // Constraint Validation:
    // developer.mozilla.org/en-US/docs/Web/Guide/HTML/Constraint_validation

    //function checkInput() {
    //var page = document.getElementById("page");
    //var tot = "<?=$total_pages?>";
    //console.log(tot);
    //if (typeof page !== "undefined") {
    //if (page < 1 || page > tot) {
    //page.setCustomValidity("Page number out of range");
    //return;
    //}
    //}
    // No custom constraint violation
    //page.setCustomValidity("");
    //}

    //window.onload = function () {
    //document.getElementById("page").onchange = checkInput;
    //}
    </script>

    <!-- To handle long text in select options
         gregfranko.com/jquery.selectBoxIt.js/#GettingStarted
         Example: //jsfiddle.net/ZTs42/2/
    -->

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script src="http://gregfranko.com/jquery.selectBoxIt.js/js/jquery.selectBoxIt.min.js"></script>

    <script>
    $(function(){
    // "select" or specific target "#in_this_id_apply_selectBoxIt"
      $("select").selectBoxIt({
                              theme: "default",
                              autoWidth: false
                              });
      });
    </script>
</body>
</html>
