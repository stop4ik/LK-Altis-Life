<?php
require ('../steamauth/steamauth.php');
include ('../steamauth/userInfo.php');
session_start();
if(!isset($_SESSION['steamid'])) {
	exit(header ('Location: ../index.php'));
}
else include '../dbcon.php';

if(empty($_POST['money']) or (($_POST['money']) == 0)) {
	$_SESSION['error']="Вы не можете потратить ноль!";
	exit(header ('Location: ../index.php'));
};
if ($_POST['money'] < 0) {
	$_SESSION['error']="Отрицательные значения не принимаются!";
	exit(header ('Location: ../index.php'));
}
if (is_int($_POST['money'])){
	$_SESSION['error']="Введите целое число!";
	exit(header ('Location: ../index.php'));
}
//filter end
	$wantedpoints= $_POST['money'];
	$pid = $steamprofile['steamid'];
	switch ($wantedpoints) {
	case (9 > $wantedpoints):
		$_SESSION['error']="Вы не можете обменять меньше 10 руб.!";
		exit(header ('Location: ../index.php'));
        break;
    case ($wantedpoints < 1000):
        $factor = $cenamoney1; 
        break;
    case ($wantedpoints >= 1000 && $wantedpoints < 2000):
        $factor = $cenamoney2; 
        break;
    case ($wantedpoints >= 2000 ):
        $factor = $cenamoney3; 
        break;
    default:
    	logMerrick($pid,'Wanted to buy money, but all cases failed '.$wantedpoints.'');
		$_SESSION['error']="Произошла критическая ошибка, обратитесь к администрации!";
		exit(header ('Location: ../index.php'));
		break;
}
	$wantedmoney= $wantedpoints * $factor;
    $stmt = $pdo->prepare('SELECT * FROM players WHERE playerid=?');
    $stmt->execute(array($steamprofile['steamid']));
	foreach ($stmt as $row){
	$points = $row['EPoint'];
	$db64 = $row['playerid'];
	};
	if ($points < $wantedpoints) {
		$_SESSION['error']="Недостаточно DonatMoney!";
		exit(header ('Location: ../index.php'));
	}
//update DB
$resultP = $points - $wantedpoints;
$id64 = $steamprofile['steamid'];
$name = $_POST['name'];
$stmt = $pdo->prepare('UPDATE players SET bankacc = bankacc + ?, EPoint = ?  WHERE playerid = ?');
$stmt->execute(array($wantedmoney, $resultP, $steamprofile['steamid']));

$log = 'User '.$db64.' bought money '.$_POST['money'].'';
logMerrick($pid,$log);
$_SESSION['success']="Вы успешно обменяли валюту!";
header ('Location: ../index.php');
?>