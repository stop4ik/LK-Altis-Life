<?php
require ('../steamauth/steamauth.php');
include ('../steamauth/userInfo.php');
session_start();
if(!isset($_SESSION['steamid'])) {
	exit(header ('Location: ../index.php'));
}
else include '../dbcon.php';

if(empty($_POST['trsteamid'])) {
	$_SESSION['error']="Вы не указали SteamID получателя!";
	exit(header ('Location: ../index.php'));
}

if(empty($_POST['trsum'])) {
	$_SESSION['error']="Вы не указали кол-во DonatMoney!";
	exit(header ('Location: ../index.php'));
}

if ($_POST['trsum'] < 0) {
	$_SESSION['error']="Сумма не может быть отрицательной!";
	exit(header ('Location: ../index.php'));
}
	$pid = $steamprofile['steamid'];
    $stmt = $pdo->prepare('SELECT * FROM players WHERE playerid=?');
    $stmt->execute(array($steamprofile['steamid']));
	foreach ($stmt as $row){
		$points = $row['EPoint'];
	};
	if ($points < $_POST['trsum']) {
		$_SESSION['error']="Недостаточно DonatMoney для перевода!";
		exit(header ('Location: ../index.php'));
	};

	$stmt = $pdo->prepare('SELECT * FROM players WHERE playerid=?');
	$stmt->execute(array($_POST['trsteamid']));
	foreach ($stmt as $row){
    	$trsteamid = $row['playerid'];
		$trname = $row['name'];
    };
	if ($steamprofile['steamid'] === $_POST['trsteamid']) {
	$_SESSION['error']="Вы осуществляете перевод самому себе!";
	exit(header ('Location: ../index.php'));
	};
    if ($trsteamid !== $_POST['trsteamid']) {
		$_SESSION['error']="Получателя нету в базе данных!";
		exit(header ('Location: ../index.php'));
	};
	
//update DB
$stmt = $pdo->prepare('UPDATE players SET EPoint = EPoint + ((playerid = ?) * 2 - 1) * ? WHERE playerid IN (?, ?)');
$stmt->execute(array($_POST['trsteamid'], $_POST['trsum'], $_POST['trsteamid'], $steamprofile['steamid'],));

$log = 'Translated '.$_POST['trsum'].' DonatMoney user '.$_POST['trsteamid'].'';
logMerrick($pid,$log);
$_SESSION['success']="DonatMoney перечислены игроку <b>$trname</b>";
header ('Location: ../index.php');
?>