<?php
// add

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

$List = null;
if (isset(v::$a["_list"])) {
        $List = vClean(v::$a["_list"]);
        unset(v::$a["_list"]);
}
$Tribe = null;
if (isset(v::$a["_tribe"])) {
        $Tribe = vClean(v::$a["_tribe"]);
        unset(v::$a["_tribe"]);
}

vDBConnect();
v::$r = vAdd($List, v::$a, $Tribe);
vDBClose();
