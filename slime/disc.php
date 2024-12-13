<?php

require "global.php";

$sid = sanitize_sid(isset($_GET['d']) ? $_GET['d'] : '');

mysqli_query($link, "DELETE FROM `clients` WHERE `secret`='$sid'");

if(mysqli_affected_rows($link))
{
	echo ".";
	broadcast("d", array()); // lazy...
}
else echo "!";
