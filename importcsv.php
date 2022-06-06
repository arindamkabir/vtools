<?php
require_once(v::$v["root"] . "v.php");

// output report ( /var/tmp/cryptopia_import.csv)
// phone number, name, email, imported (bool), email_exists, phone_exists
$importOutput = array();

$handle = fopen("/var/www/v/8.2dev/tools/export.csv", "r");

$columns = array_filter(fgetcsv($handle, 1000, ','));

while (($data = fgetcsv($handle, 1000, ',')) !== false) {
    $row = [];

    for ($i = 0; $i < count($data); $i++) {
        if (!isset($columns[$i])) continue;
        $row[strtolower($columns[$i])] = $data[$i];
    }

    vDBconnect();

    $name = $row['first name'] . ' ' . $row['last name'];
    $email = $row['email'];
    $phone = $row['cell phone'];
    $ticket_type = $row['ticket type'];

    // check email or phone_number
    $QEmail = vQ1("select * from tribes where email='" . $email . "';");
    $email_exists = isset($Q['email']) ? 1 : 0;

    $QPhone = vQ1("select * from tribes where phone='" . $phone . "';");
    $phone_exists = isset($Q['phone']) ? 1 : 0;
    // if !token assign token = id
    // type = user (ticket, asset, user)
    // chiefs (json of uids)

    if (!$email_exists && !$phone_exists) {
        vLog("Email does not exist.");

        $tribe_id = vGUID();
        $chiefs = vJSONdq(["chief" => "j"]);

        //        vLog($chiefs);

        $Q = vQ("insert into tribes_test1(id,token,name,type,email,phone,chiefs,time) values ('" . $tribe_id . "', '" . $tribe_id . "', $$" . $name . "$$, 'user', '" . $email . "', '" . $phone . "', " . $chiefs . ",current_timestamp);");


        $sid = vGUID();
        // create session
        $Q2 = vQ("insert into sessions_test1 (sid, tribe, time) values ('" . $sid . "', '" . $tribe_id . "',current_timestamp);");

        // name, email, phone number, imported (bool), email_exists, phone_exists
        $importOutput[] = [
            $name,
            $email,
            $phone,
            true,
            $email_exists,
            $phone_exists
        ];
        // assign wallet
        // change to test server

        // $meta_tags = get_meta_tags("https://www.seeknftmint.com/v1/api/assignWallet/?chain=MATIC&_sid=" . $sid);

        // transfer the nft to the user's wallet

    } else {
        vLog("Email exists.");
        if ($email_exists) {
            $tribe_id = $QEmail['id'];
        } else if ($phone_exists) {
            $tribe_id = $QPhone['id'];
        }
        // should check if session exists

        $sid = vGUID();
        $importOutput[] = [
            $name,
            $email,
            $phone,
            false,
            $email_exists,
            $phone_exists
        ];
        // create session
        $Q2 = vQ("insert into sessions_test1 (sid, tribe, time) values ('" . $sid . "', '" . $tribe_id . "', current_timestamp);");
    }


    vDBClose();
}

fclose($handle);


$file = fopen('/var/tmp/cryptopia_import.csv', 'w');
foreach ($importOutput as $line) {
    fputcsv($file, $line);
}
fclose($file);

v::$r(200, 'Exported');
