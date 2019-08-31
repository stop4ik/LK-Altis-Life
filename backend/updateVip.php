<?php
require ('../steamauth/steamauth.php');
include ('../steamauth/userInfo.php');
session_start();
if(!isset($_SESSION['steamid'])) {
    exit(header ('Location: ../index.php'));
}
if ((($_POST['vipLevel']) < 0) or (($_POST['vipLevel']) > 5)) {
	$_SESSION['error']="Произошла критическая ошибка, обратитесь к администрации!";
	exit(header ('Location: ../index.php'));
}
else include '../dbcon.php';
//filter end
	$wantedvip = $_POST['vipLevel'];
	$pid = $steamprofile['steamid'];
    $stmt = $pdo->prepare('SELECT * FROM players WHERE playerid=?');
    $stmt->execute(array($steamprofile['steamid']));
	foreach ($stmt as $row){
	$points = $row['EPoint'];
	$db64 = $row['playerid'];
	$curLvl = $row['donorlevel'];
	};
	switch ($wantedvip) {
    case 1:
        $takepoints = $cenavip1;
        break;
    case 2:
        $takepoints = $cenavip2;
        break;
    case 3:
        $takepoints = $cenavip3;
        break;
    case 4:
        $takepoints = $cenavip4;
        break;
	case 5:
        $takepoints = $cenavip5;
        break;
    default:
    	logMerrick($pid,'User tried to buy VIP illegally LVL - '.$wantedvip.'');
		$_SESSION['error']="Произошла критическая ошибка, обратитесь к администрации!";
		exit(header ('Location: ../index.php'));
		break;
}	
	if ($curLvl > 0) {
		$_SESSION['error']="У вас активен VIP-статус. Продлить можно будет только после окончания текущего периода!";
		exit(header ('Location: ../index.php'));
	}
	if ($points < $takepoints) {
		$_SESSION['error']="Недостаточно DonatMoney!";
		exit(header ('Location: ../index.php'));
	}
	$resultP = $points - $takepoints;
	$stmt = $pdo->prepare('UPDATE players SET EPoint = ? WHERE playerid = ?');
	$stmt->execute(array($resultP, $steamprofile['steamid']));
//update DB
$id64 = $steamprofile['steamid'];
$name = $_POST['name'];
$stmt = $pdo->prepare('UPDATE players SET donorlevel = ?, donat_time = DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY) WHERE playerid = ?');
	$stmt->execute(array($wantedvip, $steamprofile['steamid']));

$log = 'User '.$db64.' bought VIP '.$_POST['vipLevel'].'';
logMerrick($pid,$log);
$_SESSION['success']="Вы успешно вступили в VIP-клуб!";
header ('Location: ../index.php');
?>