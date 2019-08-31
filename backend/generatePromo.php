<?
require ('../steamauth/steamauth.php');
include ('../steamauth/userInfo.php');
include '../dbcon.php';

if(!isset($_SESSION['steamid']) or ($_SESSION['steamid'] < 10)) {
    exit(header ('Location: ../index.php'));
}
if(!in_array($steamprofile['steamid'],$adminmenu)) {
	$log = 'User '.$_SESSION['steamid'].' TRIED TO GENERATE PROMOS ILLEGALLY';
	logMerrick($_SESSION['steamid'],$log);
	exit(header ('Location: ../index.php'));
}
function promoMerrick($number,$type,$promo) 
{
global $pdo;
$chars = 'A1B2C3D4E5F6G7H8I9J0KLMNOPQRSTUVWXYZ';
for($ichars = 0; $ichars < 10; ++$ichars) {
    $random = str_shuffle($chars);
    $hashpromo .= $random[0];
    $code = $type.'-'.$hashpromo.'';
}
$stmt = $pdo->prepare('INSERT INTO lk_promo (code,amount,type,author) VALUES (?,?,?,?)');
$stmt->execute(array($code,$promo,$type,$_SESSION['steamid']));
echo $code;
echo '<br>';
}
session_start();
$number = $_POST['number'];
$type = $_POST['type'];
$promo = $_POST['promo'];

for ($i = 0; $i < $number; ++$i){
	promoMerrick($number,$type,$promo);
}
$log = 'User'.$_SESSION['steamid'].' generated '.$number.' promos with type '.$type.' and amount '.$promo.'';
logMerrick($_SESSION['steamid'],$log);
?>