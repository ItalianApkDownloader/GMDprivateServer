<?php
//error_reporting(0);
include "connection.php";
require_once "incl/GJPCheck.php";
$gjp =  htmlspecialchars($_POST["gjp"],ENT_QUOTES);
$gameVersion =  htmlspecialchars($_POST["gameVersion"],ENT_QUOTES);
$binaryVersion =  htmlspecialchars($_POST["binaryVersion"],ENT_QUOTES);
$secret =  htmlspecialchars($_POST["secret"],ENT_QUOTES);
$subject =  htmlspecialchars($_POST["subject"],ENT_QUOTES);
$toAccountID =  htmlspecialchars($_POST["toAccountID"],ENT_QUOTES);
$body =  htmlspecialchars($_POST["body"],ENT_QUOTES);
$accID =  htmlspecialchars($_POST["accountID"],ENT_QUOTES);

$query3 = "SELECT * FROM users WHERE extID = :accID ORDER BY userName DESC";
$query3 = $db->prepare($query3);
$query3->execute([':accID' => $accID]);
$result = $query3->fetchAll();
$result69 = $result[0];
$userName = $result69["userName"];

//continuing the accounts system
$accountID = "";
$id = $accID =  htmlspecialchars($_POST["udid"],ENT_QUOTES);
if($_POST["accountID"]!=""){
	$id = htmlspecialchars($_POST["accountID"],ENT_QUOTES);
	$register = 1;
}else{
	$register = 0;
}
$query2 = $db->prepare("SELECT * FROM users WHERE extID = :id");
$query2->execute([':id' => $id]);
$result = $query2->fetchAll();
if ($query2->rowCount() > 0) {
$userIDalmost = $result[0];
$userID = $userIDalmost[1];
} else {
$query = $db->prepare("INSERT INTO users (isRegistered, extID)
VALUES (:register,:id)");

$query->execute([':register' => $register, ':id' => $id]);
$userID = $db->lastInsertId();
}
$uploadDate = time();

$query = $db->prepare("INSERT INTO messages (subject, body, accID, userID, userName, toAccountID, secret)
VALUES (:subject, :body, :accID, :userID :userName, :toAccountID, :secret)");

$GJPCheck = new GJPCheck();
$gjpresult = $GJPCheck->check($gjp,$accID);
if($gjpresult == 1){
	$query->execute([':subject' => $subject, ':body' => $body, ':accID' => $accID, ':userID' => $userID, ':userName' => $userName, ':toAccountID' => $toAccountID, ':secret' => $secret]);
	echo 1;
}else{echo -1;}
?>