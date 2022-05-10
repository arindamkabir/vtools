<?php
// get

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

$Tribe = null;
if (isset(v::$a["_tribe"])) {
        $Tribe = vClean(v::$a["_tribe"]);
        unset(v::$a["_tribe"]);
} else if (isset(v::$a["tribe"])) {
	$Tribe = vClean(v::$a["tribe"]);
	unset(v::$a["tribe"]);
}

vDBConnect();
v::$r = vGet($Tribe);
vDBclose();
