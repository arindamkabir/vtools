<?php
// xpsadd
// Response codes:
// 200 Normal / OK
// 300 Data error
// 400 Syntax error
// 500 Authorization error
// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"] . "v.php");

$Tribe = v::$v["uid"];
if (isset(v::$a["_uid"])) {
    $Tribe = vClean(v::$a["_uid"]);
} else if (isset(v::$a["uid"])) {
    $Tribe = vClean(v::$a["uid"]);
}
if (isset(v::$a["xps"])) {
    $XPS = v::$a["xps"];
    if (is_numeric($XPS)) {
        $XPS = intval(v::$a["xps"]);
        $Type = "login";
        if (isset(v::$a["type"])) {
            $Type = vClean(v::$a["type"]);
        }
        $org = "";
        if (isset(v::$a["org"])) {
            $org = vClean(v::$a["org"]);
            vDBConnect();
            $Q = vQ("insert into xps (time,id,xps,type,org) values (now(),'" . $Tribe . "'," . $XPS . ",'" . $Type . "', '" . $org . "');");
            v::$r = vR(200, $XPS . " XPS added to: " . $Tribe);
            vDBclose();
        } else {
            v::$r = vR(300, "No org specified");
        }
    } else {
        v::$r = vR(300, "Invalid XPS specified: " . $XPS);
    }
}
