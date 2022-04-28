<?php
// get

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

$Tribe = null;
if (isset(v::$a["_tribe"])) {
        $Tribe = vClean(v::$a["_tribe"]);
        unset(v::$a["_tribe"]);
}

vDBConnect();
v::$r = vGet($Tribe);
vDBclose();
