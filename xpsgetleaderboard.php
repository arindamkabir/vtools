<?php
// xpsgetleaderboard

// Also need Visyfy core functions
require_once(v::$v["root"] . "v.php");

if (isset(v::$a["org"])) {
    $org = vClean(v::$a["org"]);
    vDBConnect();
    v::$r = vQ("select xps.id,tribes.name, sum(xps.xps) as xps_total from xps inner join tribes on xps.id = tribes.id where xps.org='" . $org . "' group by xps.id,tribes.name order by xps_total desc limit 10;");
    vDBclose();
} else {
    v::$r = vR(300, "No org specified.");
}
