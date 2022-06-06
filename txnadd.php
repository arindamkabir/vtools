<?php
// txnadd
// adds transaction to db
// response - the response from api or tools call
// org - the org from which the call was made
// type - either "vapi" or "vdo" depending on the function making the call and thus the endpoint being called
// change table name from txn_test1 to txn in production.

// Also need Visyfy core functions
require_once(v::$v["root"] . "v.php");

$Tribe = v::$v["uid"];
if (isset(v::$a["uid"])) {
    $Tribe = vClean(v::$a["uid"]);
}

if (
    isset(v::$a["response"]) &&
    isset(v::$a["endpoint"]) &&
    isset(v::$a["org"]) &&
    isset(v::$a["type"])
) {
    $response = vJSONarray(v::$a["response"], true);
    $response['endpoint'] = v::$a["endpoint"];
    $org = v::$a["org"];
    $type = v::$a["type"];

    $txnId = vGUID();

    if (isset($response['txn_id'])) {
        $txnId = $response['txn_id'];
    }

    vDBConnect();

    // Change table name from txn_test1 to txn in production.
    $Q = vQ("insert into txn_test1 (id,time,tribe,org,type,meta) values ('" . $txnId . "',now(),'" . $Tribe . "','" . $org . "','" . $type . "'," . vJSONdq($response) . ");");

    v::$r = vR(200, "Transaction Added.");

    vDBclose();
} else {
    v::$r = vR(300, "Missing required data.");
}
