<?php
// txt

// Kernel is always loaded by dispatch

require __DIR__ . '/twilio-php-main/src/Twilio/autoload.php';
use Twilio\Rest\Client;
list($SID, $Token, $IGNORE) = @file(__DIR__ . "/twilio.config", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$client = new Client($SID, $Token);

$Auth = false;
if (isset(v::$a["_sid"])) {
	// BACKLOG: Check for valid session
	if ( v::$a["_sid"] == "123581321" ) {
		$Auth = true;
	}
}

// This is just to keep logging consistent
if (isset(v::$a["_tribe"])) {
	if (strlen(vClean(v::$a["_tribe"])) > 0) {
		v::$v["tribe"] = vClean(v::$a["_tribe"]);
	}
}
if (isset(v::$a["_uid"])) {
	if (strlen(vClean(v::$a["_uid"])) > 0) {
		v::$v["uid"] = vClean(v::$a["_uid"]);
	}
}

if ($Auth) {
	// BACKLOG: Switch from depending on which is assigned to tribe
	$From = "+12134014580";

	$Country = "+1"; // Default to USA
		if (isset(v::$a["country"])) {
		if (v::$a["country"][0] == "+") {
			$Country = trim(v::$a["country"]);
		}
	}
	$To = "";
	if ( isset(v::$a["to"]) ) {
		$To = vCleanPhone(v::$a["to"]);
	}

	if ( strlen($To) >= 10 ) {
		$Msg = "v";
		if (isset(v::$a["msg"])) {
			$Msg = v::$a["msg"];
		}
		$X = array( "from" => $From, "body" => $Msg );
		if (isset(v::$a["url"])) {
			if (strlen(v::$a["url"]) > 10) {
				$X["mediaUrl"] = v::$a["url"];
			}
		}
		// Dev Hack!!!
		if ($To == "3333333333" || $To == "0000000000" || $To == "2222222222") {
vLog("Dev HACK using mobile number");
file_put_contents("/var/tmp/txt.txt", date("m/d/Y H:i:s", time())."\n".str_replace("\n", " ", $X["body"])."\n\n", FILE_APPEND);
		} else {
			$client->messages->create( $Country.$To, $X );
		}
		//v::$a["msg"] = strstr(v::$a["msg"], " code: ", true);
		v::$a["msg"] = "";
vLog("Text message sent: ", v::$a);
		v::$r = vR(200, "Text message sent");
	} else { // Invalid input
vLog("Invalid mobile number: ", v::$a);
		v::$r = vR(300, "Invalid mobile number");
	}
} else {
	v::$r = vR(400, "Unauthorized text request");
vLog("Unauthorized text request", v::$a);
}
