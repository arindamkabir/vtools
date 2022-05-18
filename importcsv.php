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
            $name = $data[2] . " " . $data[3];
            $email = $data[4];
            $phone = $data[16];
            $chiefs = json_encode(["chief" => "j"], JSON_UNESCAPED_SLASHES);

            $Q = vQ("insert into tribes_test1(id,token,name,type,email,phone,chiefs,time) values ('" . $tribe . "', '" . $tribe . "', '" . $name . "', 'user', '" . $email . "', '" . $phone . "', '" . $chiefs . "', '" . now() . "');");

            $sid = vGUID();
            // create session
            $Q2 = vQ("insert into sessions (sid, tribe, time) values ('" . $sid . "', '" . $tribe . "', '" . now() . "');");

            // assign wallet
            $meta_tags = get_meta_tags("https://www.seeknftmint.com/v1/api/assignWallet/?chain=MATIC&_sid=" . $sid);
        } else {

            $uid = "";



            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://www.seeknftmint.com/v1/api?uid=' . $uid,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "campaign_id": "postman_inline_both_example",
                "recipients": [ { "address": "' . $To . '" }, { "address":"test-u9ffig6x9@srv1.mail-tester.com" }, { "address": "jeremy@seekxr.com" } ],
                "content": {
                        "from": { "email": "help@mail.visyfy.net", "name": "SeekXR" },
                        "subject": "' . $Subject . '",
                        "html": "<html><body>' . $Msg . '<br></body></html>",
                        "text": "' . $Msg . '"
                }
        }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: 4bd09cd2ed2f44e7265b4be1585672e3cb423bb7'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
        }


        vDBClose();
    }

    fclose($open);
}
