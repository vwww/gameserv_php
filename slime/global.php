<?php

require "config.php";

function sanitize_sid($sid){ return preg_replace("/[^a-f0-9]/", "", $sid); }
function cron_clear_old_users()
{
	global $link;
	$to_purge = array();
	$q = mysqli_query($link, "SELECT `secret` FROM `clients` WHERE `last_receive` <= ".(time() - 10));
	while($r = mysqli_fetch_assoc($q))
	{
		$to_purge[] = $r['secret'];
	}
	mysqli_query($link, "DELETE FROM `clients` WHERE `secret` IN ('".implode("','", $to_purge)."')");
	mysqli_query($link, "DELETE FROM `messages` WHERE NOT EXISTS (SELECT 1 FROM `clients` WHERE `clients`.`secret`=`messages`.`target`)");
	//foreach($to_purge as $p)
	if($to_purge)
	{
		broadcast("d", array()); // lazy...
	}
}

function broadcast($type, $payload, $id = false, $exclusive = false)
{
	global $link;
	// $exclusive is only for 2-player
	if($exclusive) mysqli_query($link, "DELETE FROM `messages` WHERE `type`='$type' AND `target` != '$id'");
	mysqli_query($link, "INSERT INTO `messages` (`target`, `type`, `payload`) SELECT `secret`, '$type', '".mysqli_real_escape_string($link, serialize($payload))."' FROM `clients`".($id ? " WHERE `secret` != '$id'" : ""));
}

?>