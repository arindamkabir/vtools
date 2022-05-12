<?php
// knock
//
// Request authorization code
//
// BACKLOG: convert to only storing a one-way hash instead of the code
//
// Kernel is always loaded by dispatch

// Load Visyfy core for vTxt
require_once(v::$v["root"]."v.php");

// Valid auth (phone)?
if ( ! isset(v::$a["type"]) ) {
	$Type = "phone";
} else {
	$Type = vClean(v::$a["type"]);
}
$Auth = "";
if ( isset(v::$a["auth"]) ) {
	$Auth = v::$a["auth"];
}

if ($Type == "email") {
	if (vIsEmail($Auth)) {
		// BACKLOG: Add auth2
		$Auth = "";
	}
} else {
	// Default is phone
	$Type = "phone";
	$Auth = vCleanPhone($Auth);
}

$Country = "+1"; // Default to USA
if (isset(v::$a["country"])) {
	if (v::$a["country"][0] == "+") {
		$Country = trim(v::$a["country"]);
vLog("Country code specified: ".v::$a["country"]);
	}
}

// Phone and email both must be longer than 5 characters
if ( strlen($Auth) < 5 ) {
	// Invalid auth input
	v::$r = array(
		"country" => $Country,
		"auth" => $Auth,
		"_" => array( "x" => 300, "r" => "Invalid input (".$Type."): ".$Auth, "syntax" => "JSON: type, auth")
	);
} else {
	vDBconnect();
	// Create auth code
	$Code  = vRand( 10 ** (v::$v["_auth_digits"] - 1), 10 ** (v::$v["_auth_digits"]) - 1 );
	if ($Type == "phone") {
		$Q = vQ1("select * from tribes where phonecountry='".$Country."' and phone='".$Auth."';");
	} else {
		$Q = vQ1("select * from tribes where email='".$Auth."';");
	}

	if ( vTribeExists($Q["id"]) ) {
		v::$v["tribe"] = $Q["id"];
		$Email = $Q["email"];
		// BACKLOG: Do stuff for returning user

	} else {
		$Email = vCleanEmail(v::$a["email"]);
		// BACKLOG: Do stuff for a new user
		
vLog("Tribe doees not exists for ".$Type.": ".$Auth);
	}

	if ($Type == "email" ) {
		// BACKLOG: email support

	} else {
		// Default is phone
		if (strlen($Auth) < 10 || strlen($Country) < 2) {
			v::$r = array(
				"country" => $Country,
				"auth" => $Auth,
				"_" => array( "x" => 420, "r" => "Invalid phone number", "syntax" => "JSON: type, auth")
			);
		} else {
			$Q = vQ("insert into auth1 (time,phonecountry,phone,code) values (now(),'".$Country."','".$Auth."','".$Code."')");
			if (isset(v::$a["msg"])) {
vLog("Use custom auth text message");
				$M = v::$a["msg"];
				if (strpos(v::$a["msg"], "[v/code]")) {
					$M = str_replace("[v/code]", $Code, $M);
vLog("Insert auth code");
				} else {
					$M = v::$a["msg"].$Code;
vLog("Append auth code to end of custom message");
				}
				if (strpos(v::$a["msg"], "[v/seconds]")) {
					$M = str_replace("[v/seconds]", v::$v["_auth_expiration"], $M);
vLog("Insert seconds");
				}
				if (strpos(v::$a["msg"], "[v/minutes]")) {
					$M = str_replace("[v/minutes]", (intval((v::$v["_auth_expiration"]/60) + 0.999)), $M);
vLog("Insert minutes");
				}
			} else {
				// BACKLOG: convert to minutes if longer than 120 seconds

				$M = "Visyfy login code: ".$Code."\nExpires in ".v::$v["_auth_expiration"]." seconds.\n\nHelp: help@visyfy.com";
			}
			// BACKLOG: Option to send a login image with SMS

			vTxt($Country, $Auth, $M);
			vSendMail($Email, "Authorization Code: ".$Code, $M);

			v::$r = array(
				"country" => $Country,
				"auth" => $Auth,
				"_" => array( "x" => 200, "r" => "Session authorization pending")
			);
		}
	}
	vDBclose();
}

