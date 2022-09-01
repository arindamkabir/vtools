<?php
// get

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"] . "v.php");

$Org = null;
if (isset(v::$a["_org"])) {
    $Org = vCleanFilename(v::$a["_org"]);
    unset(v::$a["_org"]);
} else if (isset(v::$a["org"])) {
    $Org = vCleanFilename(v::$a["org"]);
    unset(v::$a["org"]);
}
if (strlen($Org) < 1) {
    $Org = "ocavunet";
}
$Top = null;
if (isset(v::$a["top"])) {
    $Top = vClean(v::$a["top"]);
    unset(v::$a["top"]);
}
if (strlen($Top) < 1) {
    $Top = 10;
}

vDBConnect();
$Q = vQ("select xps.id, SUM(xps) as xps, tribes.name, tribes.media, tribes.mediacdn FROM xps inner join tribes on xps.id = tribes.id where org='" . $Org . "' GROUP BY xps.id, tribes.name, tribes.media, tribes.mediacdn order by xps desc LIMIT " . $Top . ";");


if (count($Q) === 1 && empty($Q[0])) {
    v::$r = [];
} else {
    v::$r = $Q;
}

vDBclose();
