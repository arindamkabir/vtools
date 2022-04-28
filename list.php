<?php
// list

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

$File = null;
if (isset(v::$a["_list"])) {
        $File = vClean(v::$a["_list"]);
        unset(v::$a["_list"]);
}
$Tribe = null;
if (isset(v::$a["_tribe"])) {
        $Tribe = vClean(v::$a["_tribe"]);
        unset(v::$a["_tribe"]);
}

vDBconnect();
v::$r = vList($File, $Tribe);
vDBclose();
