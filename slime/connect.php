<?php

require "global.php";

cron_clear_old_users();
$q = mysqli_query($link, "SELECT COUNT(*) FROM `clients`");
$q = mysqli_fetch_row($q);

if($q && $q[0] >= 2) exit("Server is full.");
else
{
	$name = trim(preg_replace('/[^---a-zA-Z0-9 ]+/', '', isset($_GET['n']) ? $_GET['n'] : ""));
	if(!$name) $name = "unnamed";
	else $name = substr($name, 0, 16);

	$color = isset($_GET['c']) ? (int)$_GET['c'] : 0;
	if($color < 0 || $color >= 0xFFFFFF) $color = 0xFFFFFF;

	// check for opponent
	$opponent = false;
	$q = mysqli_query($link, "SELECT `playername`, `color` FROM `clients`");
	if($q = mysqli_fetch_assoc($q)){
		$opponent = array(
			"n" => $q['playername'],
			"c" => $q['color'],
		);
	}

	// store it?
	$secret = bin2hex(openssl_random_pseudo_bytes(20));
	broadcast("j", array("n" => $name, "c" => $color));//, $secret);
	mysqli_query($link, "INSERT INTO `clients` (`secret`, `playername`, `color`, `last_receive`) VALUES ('$secret', '$name', $color, ".time().")");

	echo "*".json_encode(array(
		"secret" => $secret,
		"n" => $name,
		"c" => $color,
		"opponent" => $opponent,
		"resources" => array(
			"d" => "d.php",
			"disc" => "disc.php",
		),
	));
}
