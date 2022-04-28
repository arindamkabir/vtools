<?php
// tree

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

function vTree($Type = null, $T = null) {
	vLog("List of ".$Type." tribes linked to ".$T);
	$Q = vQ("select * from tribes where id in ( select link from links where id='".$T."' ) order by time desc;");
	$R = array();
	foreach($Q as $Row) {
		if ($Row["type"] == $Type) {
			if ($Row["mediacdn"] == "v") {
				$Row["mediacdn"] = "";
				$Row["media"] = vCDN($Row["media"], "v", "square400_");
			}
			$R[] = $Row;

		}
	}
	// BACKLOG: Recursively scan all non-duplicate linked tribes
	return $R;
}

$Type = null;
if (isset(v::$a["_type"])) {
        $Type = vClean(v::$a["_type"]);
        unset(v::$a["_type"]);
} else if (isset(v::$a["type"])) {
        $Type = vClean(v::$a["type"]);
	unset(v::$a["type"]);
}
$T = null;
if (isset(v::$a["_tribe"])) {
        $T = vClean(v::$a["_tribe"]);
        unset(v::$a["_tribe"]);
} else if (isset(v::$a["tribe"])) {
        $T = vClean(v::$a["tribe"]);
        unset(v::$a["tribe"]);
}

vDBconnect();
if (strlen($Type) < 1) { $Type = "assets"; }
if (strlen($T) < 1) { $T = v::$v["tribe"]; }
vLog("Tree of ".$Type." tribes linked to ".$T);
v::$r = vTree($Type, $T);
vDBclose();
