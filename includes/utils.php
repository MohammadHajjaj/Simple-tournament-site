<?php

function updateCountdownTime($newunix)
{
	global $db;

	$update = $db->prepare("UPDATE misc SET value=:newunix WHERE item=:item;");
	$update->bindValue(':newunix', $newunix);
	$update->bindValue(':item', 'countdown');

	$_SESSION["MSG"] = "updated the countdown time!";
	$_SESSION["MSG_TYPE"] = "success";

	if($update->execute())
		return true;

	return false;
}

function getCountdownTime()
{
	global $db;

	$gettime = $db->prepare("SELECT * FROM misc WHERE item=:item;");
	$gettime->bindValue(':item', 'countdown');
	$gettime->execute();

	$delta = $gettime->fetch()["value"] - time();

	$days = floor($delta / 86400);
	$hours = ($delta / 3600) % 24;
	$mins = ($delta / 60) % 60;
	$secs = ($delta) % 60;

	$str = "";

	if($days > 0)
		$str = $days . " days";

	if($hours > 0)
		$str .= " " . $hours . " hours";

	if($mins > 0)
		$str .= " " . $mins . " mins";
	
	$str .= " " . $secs . " secs";

	if($days < 0 OR $hours < 0 OR $mins < 0 OR $secs < 0)
		return "games are live!";

	return $str;
}

function updateChampion($teamid)
{
	global $db;

	$update = $db->prepare("UPDATE misc SET value=:teamid WHERE item=:item;");
	$update->bindValue(':teamid', $teamid);
	$update->bindValue(':item', 'champion');

	if($teamid == 0)
		$_SESSION["MSG"] = "reset the chamption team!";
	else
		$_SESSION["MSG"] = "updated the chamption team!";

	$_SESSION["MSG_TYPE"] = "success";

	if($update->execute())
		return true;

	return false;
}

function getChampion()
{
	global $db;

	$champion = $db->prepare("SELECT * FROM misc WHERE item=:item;");
	$champion->bindValue(':item', 'champion');
	$champion->execute();
	
	return $champion->fetch()["value"];
}

function updateAvatars()
{
	global $db;

	$users = $db->prepare("SELECT * FROM members;");
	$users->execute();

	while ($user = $users->fetch()) 
	{
		$updateAvatar = $db->prepare("UPDATE members SET avatarurl=:avatarurl WHERE id=:id;");
		$updateAvatar->bindValue(':avatarurl', getAvatar($user["steamurl"]));
		$updateAvatar->bindValue(':id', $user["id"]);
		$updateAvatar->execute();
	}

	$_SESSION["MSG"] = "updated the team members avatars!";
	$_SESSION["MSG_TYPE"] = "success";
}

function getClientIPAddress() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    
    return $ipaddress;
}

function teamExists($teamname)
{
	global $db;

	$teams = $db->prepare("SELECT * FROM teams WHERE teamname=:teamname;");
	$teams->bindValue(':teamname', $teamname);
	$teams->execute();

	return ($teams->rowCount() > 0 ? true : false);
}

function registerUser($username, $steamurl, $teamname)
{
	global $db;

	if(!teamExists($teamname))
		return false;
	$steamurl = str_replace(" ","", $steamurl);

	$url = $steamurl;
	if (!strstr($url, 'http') && !strstr($url, 'https'))
   		$url = "http://" . $url;

	$new_url = $url . "?xml=1";
  	$xml = simplexml_load_file($new_url, 'SimpleXMLElement', LIBXML_NOCDATA);

	$createuser = $db->prepare("INSERT INTO members (username, steamurl, steamid64, avatarurl, teamname) VALUES(:username, :steamurl, :steamid64, :avatarurl, :teamname);");
	$createuser->bindValue(':username', $username);
	$createuser->bindValue(':steamurl', $steamurl);
	$createuser->bindValue(':steamid64', $xml->steamID64);
	$createuser->bindValue(':avatarurl', $xml->avatarFull);
	$createuser->bindValue(':teamname', $teamname);
	
 	if($createuser->execute())
		return true;

	$_SESSION["MSG"] = "failed to execute the query for " . $username . "!";
	$_SESSION["MSG_TYPE"] = "fail";
	return false;
}

function registerTeam($teamname, $username, $steamurl, $discord )
{
	global $db;


	if(teamExists($teamname))
	{
		$_SESSION["MSG"] = "a team already exists with that name!";
		$_SESSION["MSG_TYPE"] = "fail";
		return false;
	}

	$steamurl = str_replace(" ","", $steamurl);

	$createteam = $db->prepare("INSERT INTO teams (teamname, captain, captain_steam, captain_discord, registrant_ip, accepted) VALUES (:teamname, :captain, :captain_steam, :captain_discord, :ip, :accepted);");
	$createteam->bindValue(':teamname', $teamname);
	$createteam->bindValue(':captain', $username);
	$createteam->bindValue(':captain_steam', $steamurl);
	$createteam->bindValue(':captain_discord', $discord);
	$createteam->bindValue(':ip', getClientIPAddress());
	$createteam->bindValue(':accepted', '0');

	if($createteam->execute())
		return true;

	$_SESSION["MSG"] = "failed to execute the query!";
	$_SESSION["MSG_TYPE"] = "fail";
	return false;
}

function updateTeam($id, $newvalue)
{
	global $db;

	if(!ctype_digit ( $newvalue ))
		return false;

	if($newvalue != 1 && $newvalue != 0)
		return false;

	$teams = $db->prepare("SELECT * FROM teams WHERE id=:id;");
	$teams->bindValue(':id', $id);
	$teams->execute();

	if($teams->rowCount() < 1)
		return false;

	$update = $db->prepare("UPDATE teams SET accepted=:newvalue WHERE id=:id;");
	$update->bindValue(':newvalue', $newvalue);
	$update->bindValue(':id', $id);

	if($update->execute())
	{
		$_SESSION["MSG"] = "updated team!";
		$_SESSION["MSG_TYPE"] = "success";

		return true;
	}

	$_SESSION["MSG"] = "failed to execute the query!";
	$_SESSION["MSG_TYPE"] = "fail";

	return false;
}

function deleteTeam($id)
{
	global $db;

	$teams = $db->prepare("SELECT * FROM teams WHERE id=:id;");
	$teams->bindValue(':id', $id);
	$teams->execute();

	if($teams->rowCount() < 1)
		return false;

	$deleteusers = $db->prepare("DELETE FROM members WHERE teamname=:teamname;");
	$deleteusers->bindValue(':teamname', $teams->fetch()["teamname"]);
	$deleteusers->execute();

	$delete = $db->prepare("DELETE FROM teams WHERE id=:id;");
	$delete->bindValue(':id', $id);
	$delete->execute();

	$_SESSION["MSG"] = "deleted the team & the members which belong to it!";
	$_SESSION["MSG_TYPE"] = "success";
}

function getAvatar($url)
{
	$url = strtolower($url);
	$url = str_replace(" ","", $url);

	if (!strstr($url, 'http') && !strstr($url, 'https'))
   		$url = "http://" . $url;

	$new_url = $url . "?xml=1";
  	$xml = simplexml_load_file($new_url,'SimpleXMLElement', LIBXML_NOCDATA);
  	return $xml->avatarFull;
}

function getSteamID64($url)
{
	$url = strtolower($url);
	$url = str_replace(" ","", $url);

	if (!strstr($url, 'http') && !strstr($url, 'https'))
   		$url = "http://" . $url;

	$new_url = $url . "?xml=1";
  	$xml = simplexml_load_file($new_url, 'SimpleXMLElement', LIBXML_NOCDATA);
  	return $xml->steamID64;
}

function isUrlValid($url)
{
	$url = strtolower($url);

	if(substr($url, -1) == "/")
		$url = substr($url, 0, -1);

	$exploded = $pieces = explode("/", $url);
		
	if (strpos($url, "steamcommunity.com") !== false) 
	{
		$expl_str = $exploded[sizeof($exploded) - 2];

		if($expl_str == "id" OR $expl_str == "profiles")
		{
			if($expl_str == "profiles")
			{
				$value = $exploded[sizeof($exploded) - 1];

				if(strlen($value) < 17)
					return false;

				if(!ctype_digit ( $value ))
					return false;
			}

	   		return true;
		}

	   	return false;
	}

	return false;
}


?>