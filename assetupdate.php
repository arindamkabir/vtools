<?php
// assetupdate.php
//
// Mode 3) update asset

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

$ID = null;
if (isset(v::$a["_id"])) {
        $ID = vClean(v::$a["_id"]);
        unset(v::$a["_id"]);
}

vDBconnect();
$DQ = '$v$'; // Postgres dollar quote
if (vAllowed($ID, v::$v["uid"])) {
	vLog("Update asset: ".$ID);
	$vArgs = v::$a;
	vLog(v::$a);
	if (isset(v::$a["name"])) {
		if (strlen(v::$a["name"]) > 0) { // Name can't be null
			vLog("DEBUG9");
			$Name = ",name='".str_replace("'", "&apos;", v::$a["name"])."'";
		}
	}
	unset(v::$a["name"]);
	if (isset(v::$a["description"])) {
		if (strlen(v::$a["description"]) > 0) { // Description can't be null
			$Description = ",description='".str_replace("'", "&apos;", v::$a["description"])."'";
		}
	}
	unset(v::$a["description"]);
	// Merge meta JSON
	$Q = vQ1("select meta from tribes where id='".$ID."';");
	$Data = array_merge($Q, v::$a);
	$JSON = json_encode($Data, JSON_UNESCAPED_UNICODE);
	// BACKLOG: CLean $JSON for SQL-Inject vulnerabilities

	$Q = vQ("update tribes set time=now()".$Name.$Description.", meta=".$DQ.$JSON.$DQ." where id='".$ID."';");
	v::$r = $JSON;
} else {
	v::$r = vR(500, v::$v["uid"]." tried to update asset: ".$ID);
}
vDBclose();
