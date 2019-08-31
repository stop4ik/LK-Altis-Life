<?php    
    $host = '';
    $db   = '';
    $user = '';
    $pass = '';
    $charset = 'utf8';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
	$pdo = new PDO($dsn, $user, $pass, $opt);
 ?>
<?
	// Настройка цен
	$cenavip1 = 10; // цена 1 випки
	$cenavip2 = 20;
	$cenavip3 = 30;
	$cenavip4 = 40;
	$cenavip5 = 50; // цена 5 випки (если нету удалите 276 строку в index.php, а данный парамерт поставьте 99999 )
	
	$cenaname = 50; // Сколько стоит сменить имя
	
	$cenamoney1 = 100; // Сколько давать денег на острове за ОДИН DonatMoney при обмене до 1000
	$cenamoney2 = 120; // Сколько давать денег на острове за ОДИН DonatMoney при обмене от 1000 до 2000
	$cenamoney3 = 130; // Сколько давать денег на острове за ОДИН DonatMoney при обмене свыше 2000
	
	$adminmenu = array("76561198060819775","9999999"); // SteamID для доступа АдминМеню через запятую в кавычках
	
	$cronpass = Jgnft65ktbh65mb56g34; // пароль для выполнения скрипта по чистке устекших VIP (cron)
	
	
 function logMerrick($pid, $log)
{
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO lk_logs (user,action) VALUES (?,?)');
    $stmt->execute(array($pid,$log));
}
function getIP() 
{
if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}
?>