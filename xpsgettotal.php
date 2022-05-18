<?php
// xpsgettotal

// Also need Visyfy core functions
require_once(v::$v["root"] . "v.php");

$Tribe = v::$v["uid"];
if (isset(v::$a["_uid"])) {
    $Tribe = vClean(v::$a["_uid"]);
} else if (isset(v::$a["uid"])) {
    $Tribe = vClean(v::$a["uid"]);
}
if (isset(v::$a["org"])) {
    $org = vClean(v::$a["org"]);
    vDBConnect();
    v::$r = vQ1("select sum(xps) as xps_total from xps where id='" . $Tribe . "' and org='" . $org . "';");
    vDBclose();
} else {
    v::$r = vR(300, "No org specified.");
}
