<?php
require ('../steamauth/steamauth.php');
include ('../steamauth/userInfo.php');
session_start();
if(!isset($_SESSION['steamid'])) {
    exit(header ('Location: ../index.php'));
}
else include '../dbcon.php';

if(empty($_POST['name'])) {
	$_SESSION['error']="Вы не написали имя!";
	exit(header ('Location: ../index.php'));
}

if (preg_match('/[^a-zA-Z ]+/', $_POST['name'])) {
	$_SESSION['error']="Введите корректное имя!";
	exit(header ('Location: ../index.php'));
}
//filter end
	$stmt = $pdo->prepare('SELECT * FROM players WHERE name=?');
	$stmt->execute(array($_POST['name']));
	foreach ($stmt as $row){
    	$exiName = $row['name'];
    };
    if ($exiName === $_POST['name']) {
		$_SESSION['error']="Такое имя уже существует!";
		exit(header ('Location: ../index.php'));
	};
	$pid = $steamprofile['steamid'];
    $stmt = $pdo->prepare('SELECT * FROM players WHERE playerid=?');
    $stmt->execute(array($steamprofile['steamid']));
	foreach ($stmt as $row){
		$points = $row['EPoint'];
		$db64 = $row['playerid'];
	};
	if ($points < $cenaname) {
		$_SESSION['error']="Недостаточно DonatMoney!";
		exit(header ('Location: ../index.php'));
	}
//update DB
$name = preg_replace('!\s+!', ' ', $_POST['name']);

$stmt = $pdo->prepare('UPDATE players SET name = ?, EPoint = EPoint - ? WHERE playerid = ?');
$stmt->execute(array($name, $cenaname, $steamprofile['steamid']));

$log = 'User '.$db64.' changed nickname to "'.$name.'"';
logMerrick($pid,$log);
$_SESSION['success']="Вы успешно изменили ник!";
header ('Location: ../index.php');
?>