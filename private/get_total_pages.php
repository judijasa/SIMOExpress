<?php
    // This program fetch the total number job items in SIMO.  Knowing that there are 10 job items per page, it computes the corresponding total number of pages.  It saves it in a .txt file.
    
    // INI format similar to SH
    $cnf = parse_ini_file("admin_config.sh");
    $path2casper = $cnf["PATH2CASPER"];
    $target_site = $cnf["SITE"];
    
    require 'vendor/autoload.php';
    require 'functions.php';
    
    // MAX_FILE_SIZE: https://stackoverflow.com/questions/48098911/the-use-of-the-php-simple-html-dom-parser-when-parsing-large-html-files-result
    //  https://stackoverflow.com/questions/30966569/str-get-html-doesnt-work-and-return-blank/30967650
    
    define('MAX_FILE_SIZE', 4000000);
    use Sunra\PhpSimple\HtmlDomParser;
    use Browser\Casper;
    
    // '/Users/juandiego/.anyenv/envs/nodenv/shims/'
    $casper = new Casper($path2casper);
    $casper->setOptions(array(
                              'ignore-ssl-errors' => 'yes'
                              ));

    $casper->start($target_site);
    
    // Wait for '.itemEmpleo' but fetch '.dgrid-status',
    // otherise problems arise.
    // '.dgrid-navigation' is at bottom right
    $casper->waitForSelector('.itemEmpleo', 30000);
    // '.dgrid-status' is at bottom left
    $casper->fetchText('.dgrid-status');
    
    $casper->run();
    
    //print_r(array_slice($casper->getOutput(), 5, 15,true));
    
    $fetch = ($casper->getOutput())[15];
    //echo "Fetched element: ". $fetch. "\n";
    $tot_res = trim(explode('de',explode('resultados',$fetch)[0])[1]);
    //echo "Total results: ". $tot_res. "\n";
    $tot_pgs = getTotalPages($tot_res);
    //echo "Total pages: ". $tot_pgs. "\n";
    echo $tot_pgs;
?>
