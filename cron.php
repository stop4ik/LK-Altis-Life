<?php
session_start();
require_once 'dbcon.php';
if( isset( $_GET['cron'] ) && $_GET['cron'] == $cronpass ) {
$stmt = $pdo->prepare('UPDATE `players` SET `donorlevel` = "0", donat_time = NULL WHERE donat_time < CURRENT_DATE');
$stmt->execute();
	die('Успех');
}
exit( 'Неверный ключ-крон' );
?>