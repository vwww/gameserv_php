<?php

require "global.php";

$input = json_decode(file_get_contents("php://input"));
$sid = sanitize_sid(isset($input->s) ? $input->s : '');

$sender = mysqli_query($link, "SELECT * FROM `clients` WHERE `secret`='$sid'");
$sender = mysqli_fetch_assoc($sender);

if(!$sender)
	exit("!Invalid session.");

$ping = (int)$input->p;
$input = $input->m;

// check if input is good
if((array)$input !== $input)
	exit("!Malformed input.");

$changed = array('last_receive' => time());
$messages = array(array("t" => "pp", "p" => $ping),);
// fetch pending messages
mysqli_query($link, 'LOCK TABLE `messages` WRITE');
$q = mysqli_query($link, "SELECT `type`, `payload` FROM `messages` WHERE `target`='$sid'");
while($row = mysqli_fetch_assoc($q)){
	$m = &$messages[];
	$m = unserialize($row['payload']);
	$m['t'] = $row['type'];
}
mysqli_query($link, "DELETE FROM `messages` WHERE `target`='$sid'");
mysqli_query($link, 'UNLOCK TABLES');

// process the message
foreach($input as $in)
{
	$type = isset($in->t) ? $in->t : 0;
	switch($type)
	{
		case 't': // transfer control
			// record controller?
			broadcast("t", array("y" => $in->y, "w" => $in->w, "z" => $in->z), $sid);
			break;
		case 'b': // ball position
			// check for who has control?
			broadcast("b", array("x" => $in->x, "y" => $in->y, "w" => $in->w, "z" => $in->z), $sid, true);
			break;
		case 'l': // loses
			// record loss?
			$messages[] = array("t" => "s", "s" => false);
			broadcast("s", array("s" => true), $sid);
			break;
		case 'p': // player position
			broadcast("p", array("x" => $in->x, "y" => $in->y, "w" => $in->w, "z" => $in->z), $sid, true);
			break;
		case 'pp': // player ping
			broadcast("op", array("p" => $in->p), $sid, true);
			break;
	}
}
echo json_encode($messages);

// update records
function change2mysql(&$item, $k)
{
	$item = "`$k`='$item'";
}
array_walk($changed, 'change2mysql');
mysqli_query($link, "UPDATE `clients` SET ".implode(',', $changed)." WHERE `secret`='$sid'");

?>