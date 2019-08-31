<?php
require ('../steamauth/steamauth.php');
include ('../steamauth/userInfo.php');
session_start();
if(!isset($_SESSION['steamid'])) {
    exit(header ('Location: ../index.php'));
}
include '../dbcon.php';
if ($_SESSION['ticker'] > 4) {
	$_SESSION['error']="<strong>Из-за большого количества попыток ввести неверный код, Ваш аккаунт был заморожен. Обратитесь к администрации.</strong>";
    logMerrick($steamprofile['steamid'],'User abused promos, changed steamid from DB');
    $stmt = $pdo->prepare('UPDATE players SET playerid = ? WHERE playerid = ?');
	$stmt->execute(array('Abused Promo', $steamprofile['steamid']));
	exit(header ('Location: ../index.php?logout'));
}
//filter end
	$stmt = $pdo->prepare('SELECT * FROM lk_promo WHERE code=?');
	$stmt->execute(array($_POST['promo']));
	foreach ($stmt as $row){
    	$dbcode = $row['code'];
    	$dbtype = $row['type'];
    	$dbamount = $row['amount'];
    };
    if ($dbcode === $_POST['promo']) {
    	if ($dbtype === "VIP") {
    		$stmt = $pdo->prepare('SELECT * FROM players WHERE playerid=?');
    		$stmt->execute(array($steamprofile['steamid']));
			foreach ($stmt as $row){
			$curLvl = $row['donorlevel'];
			};
			if ($curLvl > 0) {
				$_SESSION['error']="У вас активен VIP-статус. Активировать промокод можно будет только после окончания текущего периода!";
				exit(header ('Location: ../index.php'));
			}

    		$stmt = $pdo->prepare('UPDATE players SET donorlevel = ?, donat_time = DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY) WHERE playerid = ?');
			$stmt->execute(array($dbamount, $steamprofile['steamid']));
			//deleting code
			$stmt = $pdo->prepare('DELETE FROM lk_promo WHERE code = ?');
			$stmt->execute(array($dbcode));
    	} else if ($dbtype === "MONEY") {//MONEY
    		$stmt = $pdo->prepare('UPDATE players SET bankacc = bankacc + ?  WHERE playerid = ?');
			$stmt->execute(array($dbamount, $steamprofile['steamid']));
			//deleting code
			$stmt = $pdo->prepare('DELETE FROM lk_promo WHERE code = ?');
			$stmt->execute(array($dbcode));
    	} else if ($dbtype === "DONATE") {//MONEY
    		$stmt = $pdo->prepare('UPDATE players SET EPoint = EPoint + ?  WHERE playerid = ?');
			$stmt->execute(array($dbamount, $steamprofile['steamid']));
			//deleting code
			$stmt = $pdo->prepare('DELETE FROM lk_promo WHERE code = ?');
			$stmt->execute(array($dbcode));
    	} else if ($dbtype === "VEHICLE") {//VEHICLES
    		$inv = '"[[],0]"';
    		$gear = '"[]"';
    		$dam = '"[]"';
    		$stmt = $pdo->prepare('INSERT INTO vehicles (side, classname, type, pid, plate, color, inventory, gear, damage) VALUES ("civ",?,"Car",?,"777777","0",?,?,?)');
			$stmt->execute(array($dbamount, $steamprofile['steamid'], $inv, $gear, $dam));
			//deleting code
			$stmt = $pdo->prepare('DELETE FROM lk_promo WHERE code = ?');
			$stmt->execute(array($dbcode));
		} else if ($dbtype === "AIR") {//AIR
    		$inv = '"[[],0]"';
    		$gear = '"[]"';
    		$dam = '"[]"';
    		$stmt = $pdo->prepare('INSERT INTO vehicles (side, classname, type, pid, plate, color, inventory, gear, damage) VALUES ("civ",?,"Air",?,"777777","0",?,?,?)');
			$stmt->execute(array($dbamount, $steamprofile['steamid'], $inv, $gear, $dam));
			//deleting code
			$stmt = $pdo->prepare('DELETE FROM lk_promo WHERE code = ?');
			$stmt->execute(array($dbcode));
		} else if ($dbtype === "SHIP") {//Ship
    		$inv = '"[[],0]"';
    		$gear = '"[]"';
    		$dam = '"[]"';
    		$stmt = $pdo->prepare('INSERT INTO vehicles (side, classname, type, pid, plate, color, inventory, gear, damage) VALUES ("civ",?,"Ship",?,"777777","0",?,?,?)');
			$stmt->execute(array($dbamount, $steamprofile['steamid'], $inv, $gear, $dam));
			//deleting code
			$stmt = $pdo->prepare('DELETE FROM lk_promo WHERE code = ?');
			$stmt->execute(array($dbcode));
		} else {
    		$_SESSION['error']="Ваш промокод не сработал, обратитесь к администрации!";
    		logMerrick($steamprofile['steamid'],'User tried to activate promo - '.$_POST['promo'].'');
			header ('Location: ../index.php');
    	}
    	logMerrick($steamprofile['steamid'],'User activated promo - '.$_POST['promo'].'');
		$_SESSION['success']="Промокод активирован!";
		exit(header ('Location: ../index.php'));
	} else {
		$_SESSION['error']="Такого кода не существует или уже активирован!";
		$_SESSION['ticker'] = ++$_SESSION['ticker'];
		if ($_SESSION['ticker'] == 5) {$_SESSION['error']="<strong>Вы использовали последнюю попытку ввести код, следующая попытка заморозит Ваш аккаунт!</strong>";}
		header ('Location: ../index.php');
	}
?>