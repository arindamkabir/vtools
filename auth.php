<?php
// auth
//
// BACKLOG: Convert to processing a one-way hash instead of the code

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
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
$Email = "";
if ( isset(v::$a["email"]) ) {
	if ( vIsEmail(v::$a["email"]) ) {
		$Email = v::$a["email"];
	} else {
		$Email = "";
	}
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
	} else {
vLog("Invalid country code specified: ".v::$a["country"]);
	}
}

// BACKLOG: Support non-USA country codes in Twilio

$DQ = '$v$'; // Postgres dollar quote
$Code = "";
if ( isset(v::$a["code"]) ) {
	$Code = trim(v::$a["code"]);
}

if (strlen($Code) < v::$v["_auth_digits"] || strlen($Code) < 1) {
	v::$r = array(
		"auth" => $Auth,
		"_" => array( "x" => 430, "r" => "Invalid authorization code", "syntax" => "JSON: type, auth, code")
	);
} else {
	if ($Type == "email") {
		// BACKLOG: email support

		v::$r = array(
			"_" => array( "x" => 400, "r" => "Email authentication not yet available", "syntax" => "JSON: type, auth, code")
		);
	} else {
		// Default is phone
		if (strlen($Auth) < 10) {
			v::$r = array(
				"country" => $Country,
				"auth" => $Auth,
				"_" => array( "x" => 420, "r" => "Invalid phone number", "syntax" => "JSON: type, auth, code")
			);
		} else {
			vDBconnect();
			$Q = vQ1("select * from auth1 where phonecountry='".$Country."' and phone='".$Auth."' order by time desc limit 1;");
			if ( ( time() - strtotime($Q["time"]) ) > v::$v["_auth_expiration"] ) {
				// Auth code expired
				$AuthCode = "";
				vQ("delete from auth1 where phonecountry='".$Country."' and phone='".$Auth."';");
vLog("Auth code expired.");
			} else {
				$AuthCode = $Q["code"];
			}
			// HACK: App store review hack
			if ($Auth == "2222222222") {
				$AuthCode = "1988";
			}
			if ( strlen(trim($AuthCode)) > 0 && $Code == $AuthCode ) {
				vQ("delete from auth1 where phonecountry='".$Country."' and phone='".$Auth."';");
				// BACKLOG: purge expired sessions

				// Login successful, create new user session
				v::$v["sid"] = vGUID();
				$Q = vQ1("select * from tribes where phonecountry='".$Country."' and phone='".$Auth."';");
				if (isset($Q["id"])) {
					v::$v["uid"] = $Q["id"];
				}
				if ( vTribeExists(v::$v["uid"]) ) {
vLog("Tribe exists ".v::$v["uid"]);
					// Get existing user tribe
					v::$u = $Q;
					v::$v["tribe"] = $Q["context"];
					v::$t = vQ1("select * from tribes where id='".v::$v["tribe"]."';");
				} else {
					// Create new user tribe
					if (strlen(v::$v["uid"]) < 21) {
						v::$v["uid"] = vGUID();
					}
					v::$v["tribe"] = v::$v["uid"]; // Tribe context is user tribe for new users
					// Populate new user tribe's system data
					$Token = v::$v["uid"];
					$Data = array(
						"id" => v::$v["uid"],
						"phonecountry" => $Country,
						"phone" => $Auth,
						"token" => $Token,
						"type" => "user"
					);
					if ( vIsEmail($Email) ) {
						$Data["email"] = $Email;
						$Data["emailverified"] = "no";
					}
					// BACKLOG: Assign custodial wallet

					vLog(vPut($Data)); // chief set by vPut with new tribe
					v::$u = $Data;
					v::$t = v::$u;
vLog("New tribe created: ", v::$u);
				}
				vQ("insert into sessions (time,sid,tribe) values (now(),'".v::$v["sid"]."','".v::$v["uid"]."');");
				// BACKLOG: fix json encoding so we don't need 2 queries
				vQ("update sessions set meta=".$DQ.json_encode( array("sid" => v::$v["sid"], "ip" => v::$v["ip"], "device" => v::$v["agent"], "location" => v::$v["location"]), JSON_UNESCAPED_SLASHES).$DQ." where sid='".v::$v["sid"]."';");
				v::$r = array(
					"sid" => v::$v["sid"],
					"uid" => v::$v["uid"],
					"tribe" => v::$v["tribe"],
					"_" => array( "x" => 200, "r" => "Login successful")
				);
			} else {
				v::$r = array(
					"country" => $Country,
					"auth" => $Auth,
					"code" => $Code,
					"_" => array( "x" => 400, "r" => "Incorrect or expired authorization code.", "syntax" => "JSON: type, auth, code")
				);
			}
			vDBclose();
		}
	}
}

