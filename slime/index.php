<?php

require "global.php";

cron_clear_old_users();
$q = mysqli_query($link, "SELECT COUNT(*) FROM `clients`");
$q = mysqli_fetch_row($q);

$pl_count = $q[0];

echo json_encode(array(
	"slime-server" => "0.0 beta",
	"desc" => "Slime Demo 1",
	"players_active" => $pl_count,
	"players_in" => $pl_count,
	"players_max" => 2,
	"ping" => isset($_GET['t']) ? (int)$_GET['t'] : null,
	"connect" => "connect.php",
));
