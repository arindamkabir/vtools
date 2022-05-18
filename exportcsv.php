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

        // if !token assign token = id
        // type = user (ticket, asset, user)
        // chiefs (json of uids)
        if ($check_email['exists'] == 'f') {
            $tribe = vGUID();
            $Q = vQ("insert into tribes_test1 (id,token,name,type,email,phone,chiefs) values ('" . $tribe . "','" . $data[2] . " " . $data[3] . "'," . $data[4] . ",'" . $data[16] . "');");

            $sid = vGUID();
            // create session
            $Q2 = vQ("insert into sessions (sid, tribe, time) values ('" . $sid . "', '" . $tribe . "', '" . now() . "');");

            // curl to assign wallet

            $meta_tags = get_meta_tags("https://www.seekmintnfts.com/v1/api/assignWallet/?chain=MATIC&_sid=" . $sid);
        } else {
            // check the wallet for
        }


        vDBClose();
    }

    fclose($open);
}
