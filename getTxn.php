<?php
// getpaytxn

// Also need Visyfy core functions
require_once(v::$v["root"] . "v.php");

$Tribe = v::$v["uid"];
if (isset(v::$a["uid"])) {
    $Tribe = vClean(v::$a["uid"]);
}

if (
    isset(v::$a["pay_instance"])
) {
    $pay_instance = v::$a["pay_instance"];

    vDBConnect();

    v::$r = vQ1("select 1 from txn where instance='" . $pay_instance . "' and type='gem_asset_purchase';");


    vDBclose();
} else {
    v::$r = vR(300, "Missing required data.");
}
