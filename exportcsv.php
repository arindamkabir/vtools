<?php
// exportcsv

// Also need Visyfy core functions
require_once(v::$v["root"] . "v.php");

$csv_data = array();

if (($open = fopen("/var/www/v/8.2dev/tools/export.csv", "r")) !== FALSE) {

    while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
        $csv_data[] = $data;

        vDBconnect();

        // check if email exists
        $check_email = vQ1("select exists (select 1 from tribes where email='" . $data[4] . "');");

        if ($check_email['exists'] == 'f') {
            $Q = vQ("insert into tribes_test1 (id,name,email,phone) values ('" . uniqid() . "','" . $data[2] . " " . $data[3] . "'," . $data[4] . ",'" . $data[16] . "');");
        };


        vDBClose();
    }

    fclose($open);
}
