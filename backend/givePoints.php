<?
require ('../steamauth/steamauth.php');
include ('../steamauth/userInfo.php');
include '../dbcon.php';
if(!isset($_SESSION['steamid']) or ($_SESSION['steamid'] < 10)) {
	exit(header ('Location: ../index.php'));
}
if(!in_array($steamprofile['steamid'],$adminmenu)) {
	$log = 'User '.$_SESSION['steamid'].' TRIED TO GIVE POINTS ILLEGALLY';
	logMerrick($_SESSION['steamid'],$log);
	exit(header ('Location: ../index.php'));
}
session_start();
$id = $_POST['id'];
$points = $_POST['points'];
if (empty($_POST['id']) or (($_POST['id']) == 0) or (($_POST['points']) == 0) or (empty($_POST['points']))) {
	$_SESSION['error']="Значение не может быть нулем!";
	exit(header ('Location: ../index.php'));
};
if (is_int($_POST['id'])){
	$_SESSION['error']="Введите целое число!";
	exit(header ('Location: ../index.php'));
}

$stmt = $pdo->prepare('UPDATE players SET EPoint = EPoint + ?  WHERE playerid = ?');
$_SESSION['success']="Аккаунт $id пополнен на $points";
$stmt->execute(array($points, $id));

$log = 'User '.$_SESSION['steamid'].' gave '.$id.' - '.$points.' points';
logMerrick($_SESSION['steamid'],$log);
exit(header ('Location: ../index.php'));
?>