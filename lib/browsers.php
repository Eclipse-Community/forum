<?php

//$isFirefox = FALSE;
//$isIE6 = FALSE;

//$lastKnownBrowser = $_SERVER['HTTP_USER_AGENT'];
$lastKnownBrowser = "Something";

//Opera/9.80 (iPhone; Opera Mini/5.0.0176/764; U; en) Presto/2.4.15

$knownBrowsers = array
(
	"IE" => "Internet Explorer",
	"Trident" => "Internet Explorer",
	"Edge" => "Microsoft Edge",
	"OPR" => "Opera",
	"Otter" => "Otter",
	"Opera Mini" => "Opera Mini",
	"Opera" => "Opera",
	"Iceweasel" => "Iceweasel",
	"SeaMonkey" => "SeaMonkey",
	"Mypal" => "Mypal",
	"PaleMoon" => "Pale Moon",
	"Firefox" => "Firefox",
	"Chrome" => "Chromium",
	"Android" => "Android",
	"Safari" => "Safari",
	"Konqueror" => "Konqueror",
	"Lynx" => "Lynx",
	"ELinks" => "ELinks",
	"Links" => "Links",
);

$knownOSes = array
(
	'iPod' => 'iPod',
	'iPad' => 'iPad',
	'iPhone' => 'iPhone',
	"Android" => "Android %",
	"Windows NT 4.0" => "Windows NT 4",
	"Windows NT 5.0" => "Windows 2000",
	"Windows NT 5.1" => "Windows XP",
	"Windows NT 5.2" => "Windows Server 2003",
	"Windows NT 6.0" => "Windows Vista",
	"Windows NT 6.1" => "Windows 7",
	"Windows NT 6.2" => "Windows 8",
	"Windows NT 6.3" => "Windows 8.1",
	"Windows NT 6.4" => "Windows 10",
	"Windows NT 10.0" => "Windows 10",
	"Windows Mobile" => "Windows Mobile",
	"FreeBSD" => "FreeBSD",
	"Ubuntu" => "Ubuntu",
	"Linux" => "GNU/Linux %",
	"Mac OS X" => "Mac OS X %",
	"BlackBerry" => "BlackBerry",
);

$mobileBrowsers = array('Android', 'Nokia', 'iPod', 'iPad', 'iPhone');
$mobileLayout = false;

$ua = $_SERVER['HTTP_USER_AGENT'];

foreach($knownBrowsers as $code => $name)
{
	if (strpos($ua, $code) !== FALSE)
	{
		$versionStart = strpos($ua, $code) + strlen($code);
		if ($code != "dwb" || $code != "rekonq") $version = GetVersion($ua, $versionStart);

		//Opera Mini wasn't detected properly because of the Opera 10 hack.
		if ((strpos($ua, "Opera/9.80") !== FALSE && $code != "Opera Mini" || $code == "Safari") && strpos($ua, "Version/") !== FALSE)
			$version = substr($ua, strpos($ua, "Version/") + 8);
		
		//TODO: Add equivalent hacks for IE10 and IE11, as well as Windows NT 5.2 ~pixieditzy
			
		if (in_array($code, $mobileBrowsers)) $mobileLayout = true;

		$lastKnownBrowser = $name." ".$version;
		break;
	}
}

$browserName = $name;
$browserVers = (float)$version;

$os = "";
foreach($knownOSes as $code => $name)
{
	if (strpos($ua, "X11")) $suffix = " (X11)";
	else if (strpos($ua, "textmode")) $suffix = " (text mode)";
	if (strpos($ua, $code) !== FALSE)
	{
		$os = $name;

		if(strpos($name, "%") !== FALSE)
		{
			$versionStart = strpos($ua, $code) + strlen($code);
			$version = GetVersion($ua, $versionStart);
			$os = str_replace("%", $version, $os);
		}
		//If we're using the default Android browser, just report the version of Android being used ~Nina
		$lkbhax = explode(' ', $lastKnownBrowser);
		if ($lkbhax[0] == "Android") break;
		if (isset($suffix)) $os = $os . $suffix;

		if (in_array($code, $mobileBrowsers)) $mobileLayout = true;

		$lastKnownBrowser = format(__("{0} on {1}"), $lastKnownBrowser, $os);
		break;
	}
}

$lastKnownBrowser .= "<!-- ".htmlspecialchars($ua)." -->";

function GetVersion($ua, $versionStart)
{
	$numDots = 0;
	$version = "";
	for($i = $versionStart; $i < strlen($ua); $i++)
	{
		$ch = $ua[$i];
		if($ch == '_' && strpos($ua, "Mac OS X"))
			$ch = '.';
		if($ch == '.')
		{
			$numDots++;
			if($numDots == 3)
				break;
			$version .= '.';
		}
		else if(strpos("0123456789.-", $ch) !== FALSE)
			$version .= $ch;
		else if(strpos(":/", $ch) !== FALSE)
			continue;
		else if(!$numDots)
		{
			preg_match('/\G\w+/', $ua, $matches, 0, $versionStart + 1);
			return $matches[0];
		}
		else
			break;
	}
	return $version;
}

if ($_COOKIE['forcelayout'] == 1) $mobileLayout = true;
else if ($_COOKIE['forcelayout'] == -1) $mobileLayout = false;

?>
