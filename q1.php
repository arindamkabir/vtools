<?php
// q1

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

if (v::$v["uid"] == "j" || v::$v["uid"] == "rangers") {
	$SQL= null;
	if (isset(v::$a["sql"])) {
	        $SQL = v::$a["sql"];
	}

	vDBConnect();
	//v::$r = vQ1($SQL);
	v::$r = vSendMail("jeremy@whitt.com", "Sub", 'Hello\nThere!\nMy dear "friend"<br><br><h1>Bye</h1>');
	vDBclose();
} else {
	v::$r = vR(500, v::$v["uid"]." tried to access admin query");
}
