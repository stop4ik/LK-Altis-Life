<?require ('steamauth/steamauth.php');
session_start();
?>
<html>
<head>
<title>Личный кабинет</title>
<link rel="stylesheet" href="styles/bootstrap.min.css" />
<link rel="stylesheet" href="styles/font-awesome.min.css" />
<script src="scripts/jquery-1.11.3.min.js"></script>
<script src="scripts/bootstrap.min.js"></script>
<link href="styles/style.css" rel="stylesheet">
<link rel="shortcut icon" href="/images/favicon.ico" />
</head>
<body>
<?php
if(!isset($_SESSION['steamid'])) {
    echo "<div style='margin: 150px auto; text-align: center;'><h1>Авторизуйтесь для доступа в личный кабинет</h1><br>";
    loginbutton();
	echo "</div>";
	}  else {
    include ('steamauth/userInfo.php');
	include 'dbcon.php';
}

    $stmt = $pdo->prepare('SELECT * FROM players WHERE playerid=?');
    $stmt->execute(array($steamprofile['steamid']));
foreach ($stmt as $row){
    $db64 = $row['playerid'];
    };
    
    if ($db64 != $steamprofile['steamid']){
         echo '<center><div class style="margin-top: 150px;"><h1>У нас на сервере нет игрока c PID '.$steamprofile['steamid'].'</h1><a href="?logout"><button type="submit" class="btn btn-danger">Выход</button></a></div><center>';
		 exit;
    }
    $error = $_SESSION['error'];
    $success = $_SESSION['success'];
?>
<br><br><br>
<?
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM houses WHERE pid = (?)');
    $stmt->execute(array($steamprofile['steamid']));
    $counthouses = $stmt->fetch(PDO::FETCH_COLUMN);
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM vehicles WHERE pid = (?)');
    $stmt->execute(array($steamprofile['steamid']));
    $сountcars = $stmt->fetch(PDO::FETCH_COLUMN);
	$stmt = $pdo->prepare('SELECT * FROM wanted WHERE wantedID=?');
    $stmt->execute(array($steamprofile['steamid']));
    foreach ($stmt as $row)
{
	$act = $row['active'];
    $nagr = $row['wantedBounty'];

    }
	$stmt = $pdo->prepare('SELECT * FROM gangs WHERE members LIKE ?');
	$paramm = "%{$steamprofile['steamid']}%";
    $stmt->execute(array($paramm));
    foreach ($stmt as $row)
	{
	$gangs = $row['name'];
    }
    $stmt = $pdo->prepare('SELECT * FROM players WHERE playerid=?');
    $stmt->execute(array($steamprofile['steamid']));
    foreach ($stmt as $row)
{
    $dbname = $row['name'];
    $dnlvl = $row['donorlevel'];
    $dbcash = $row['cash'];
    $dbbank = $row['bankacc'];
    $points = $row['EPoint'];
    $dbdontime = $row['donat_time'];
	$jailtime = $row['jail_time'];
    $dbins = date('Y-m-d', strtotime($row['insert_time']));
    $dbseen = date('Y-m-d', strtotime($row['last_seen']));
    if ($row['donorlevel'] > 0 ){
        $disabled = 'disabled=""';
    }
    switch (true) {
    case ($row['mediclevel'] > 0) && ($row['coplevel'] > 0):
        $fraction = "Мультитул";
        $label = "label label-danger";
        break;
    case ($row['coplevel'] > 0):
        $fraction = "Полицейский";
        $label = "label label-primary";
        break;
    case ($row['mediclevel'] > 0):
        $fraction = "Медик";
        $label = "label label-warning";
        break;
    case ($row['reblevel'] > 0):
        $fraction = "Повстанец";
        $label = "label label-success";
        break;
    default:$fraction = "Гражданский";$label = "label label-info";}
}?>

<div class="container">
<div id="main">
<div id="logoff">

<?if (!empty($_SESSION['error'])) {echo '<div class="alert alert-danger" id="alert"><strong>Ошибка!</strong> '.$error.'</div>';$_SESSION['error']="";}?>
<?if (!empty($_SESSION['success'])) {echo '<div class="alert alert-success" id="alert"><strong>Операция прошла успешно!</strong> '.$success.'</div>';$_SESSION['success']="";}?>

</div>
<div class="row" id="cabinet">
<div class="col-lg-4 col-md-4 col-xs-12">
<div class="panel panel-default">
<div class="panel-heading">
<header class="panel-title">
<div class="text-center">
<strong>Данные пользователя</strong>
</div>
</header>
</div>
<div class="panel-body">
<div class="text-center" id="author">
<img src="<?echo $steamprofile['avatarfull']?>">
<h3><?echo $dbname?></h3>
<table class="table table-striped">
<tbody>
<tr><td class="active">Наличка:</td><td class="warning"><strong><?echo $dbcash?> <i class="fa fa-usd" aria-hidden="true"></i></strong></td></tr>
<tr><td class="active">В банке:</td><td class="warning"><strong><?echo number_format($dbbank);?> <i class="fa fa-usd" aria-hidden="true"></i></strong></td></tr>
<tr><td class="active">DonatMoney:</td><td class="warning"><strong><?echo number_format($points)?> <i class="fa fa-rub" aria-hidden="true"></i></strong></td></tr>
</tbody>
</table>
<form action="pay/core.php">
<strong>Приобрести DonatMoney</strong>
<p>Моментальное зачисление!</p>
<div class="input-group">
    <input name="action" value="fk_go" class="main_input" type="hidden">
    <input name="account" value="<?echo $db64?>" type="hidden">
    <input name="sum" placeholder="Сумма (руб)" type="number" class="form-control" required="required">
	<span class="input-group-btn">
	 <button type="submit" class="btn btn-info">Оплатить</button>
	 </span>
	 </div>
</form>

<hr>
<form method='POST' action='backend/activatePromo.php'>
  <p>Промокод</p>
 <div class="input-group">
   <input type="text" class="form-control" type=text name="promo" placeholder="Введите промокод" required="required">
   <span class="input-group-btn">
        <button type="submit" class="btn btn-success" type="button">Активировать</button>
  </span>
</div>
</form>
<hr>
<p class="sosmed-author">
<a href="#"><i class="fa fa-commenting" title="Наш форум"></i></a>
<a href="#"><i class="fa fa-vk" title="Мы ВКонтакте"></i></a>
<a href="#"><i class="fa fa-youtube" title="Наш канал YouTube"></i></a>
</p>

<a href="?logout" class="btn btn-danger btn-lg btn-block">Выход</a>
</div>
</div>
</div>
</div>
<div class="col-lg-8 col-md-8 col-xs-12">
<div class="panel">
<div class="panel-body">
<ul id="myTab" class="nav nav-pills">
<li class="active"><a href="#detail" data-toggle="tab">Данные игрока</a></li>
<li class=""><a href="#name" data-toggle="tab">Сменить имя</a></li>
<li class=""><a href="#money" data-toggle="tab">Обмен валюты</a></li>
<li class=""><a href="#vip" data-toggle="tab">VIP-статус</a></li>
<li class=""><a href="#transfer" data-toggle="tab">Перевод</a></li>
<li class=""><a href="#prices" data-toggle="tab">Цены</a></li>
<li class=""><a href="#sms" data-toggle="tab">Сообщения</a></li>
<li class=""><a href="#wanted" data-toggle="tab">Их разыскивают</a></li>
<li class=""><a href="#forbes" data-toggle="tab">Forbes</a></li>
<?if(in_array($steamprofile['steamid'],$adminmenu, true)) { echo '<li class=""><a href="#adminmenu" data-toggle="tab">Админ Меню</a></li>';}?>
</ul>
<div id="myTabContent" class="tab-content">
<hr>
<div class="tab-pane fade active in" id="detail">
<table class="table table-striped">
<tbody>
<tr><td>Имя в игре:</td><td><?echo $dbname?></td></tr>
<tr><td>Ваша роль:</td><td><small class="<?echo $label?>"><?echo $fraction?></small></td></tr>
<tr><td>Организация:</td><td><?if (empty($gangs)) { echo 'Вы нигде не состоите';} else { echo '<span class="label label-default">'.$gangs.'</span></td></tr>';}?>
<tr><td>PID:</td><td><?echo $db64?></td></tr>
<tr><td>Транспорта:</td><td><?echo $сountcars?></td></tr>
<tr><td>Домов:</td><td><?echo $counthouses?></td></tr>
<tr><td>Розыск:</td><td><?if ($act > 0) { echo 'Вы в розыске. Награда за Вас: '.$nagr.'$</td></tr>';} else { echo 'вас не разыскивают</td></tr>';}?>
<tr><td>Тюрьма:</td><td><?if ($jailtime > 0) { echo ''.$jailtime.' мин.</td></tr>';} else { echo 'вы не в тюрьме</td></tr>';}?>  
<tr><td>Первый день на сервере:</td><td><?echo $dbins?></td></tr>
<tr><td>Последный раз заходил:</td><td><?echo $dbseen?></td></tr>
<?if ($dnlvl > 0) { echo '<tr class="success"><td>VIP-статус:</td><td>'.$dnlvl.' уровень</td></tr>
<tr class="success"><td>Окончание VIP-статуса:</td><td>'.$dbdontime.'</td></tr>';}?>
</tbody>
</table>
</div


<!--Смена имени-->
<div class="tab-pane fade" id="name">
 <p>Если вы хотите сменить игровое имя и начать новую жизнь, то воспользовуйтесь формой ниже. <br/>
 <strong>Смена имени обойдется вам в <?echo $cenaname?> DonatMoney</strong></p>
    <p>Правила и требования к новому имени:</p>
    <ul>
      <li>Имя должно быть в рамках отыгрываемого RP и схожим с человеческим (Nikita Buyanov - хороший ник, а YaDibil98 - плохой ник)</li>
      <li>Имя может содержать только латинские символы любого регистра и символ пробел.</li>
      <li>Имя не должно совпадать или быть похожим на уже имеющимися на сервере. (Если есть игрок с ником Kalamad, то использовать ник Kaiamad - запрещено)</li>
      <li>Запрещено использовать имена администраторов. В том числе схожие по написанию.</li>
      </ul>
    <p>В случае если вы закрепили при первом входе на сервер не то имя, или же администратор выдал вам бан за имя несоответствующее нормам RP, вы можете <b>бесплатно</b> сменить его через форум. Для этого <a href="#" target="_blank">оставьте заявку в данном разделе</a>.</p>

    <hr>
    <p>Ваше текущее имя: <b><?echo $dbname?></b></p>
<div class="form-group">
 <form method='POST' action='backend/updateName.php'>
 <div class="input-group">
   <input class="form-control" type=text name="name" id="NameCheck" maxlength="24" placeholder="Ваше новое игровое имя" required="required" >
	<span class="input-group-btn">
        <button type="submit" class="btn btn-success" type="button">Сменить</button>
  </span>
</div>
</form>
</div>
</div>

  <!--Обмен валюты-->
  <div class="tab-pane fade" id="money">
  <div class="form-group">
 <p>Вы можете обменять свой DonatMoney на внутреигровую валюту в соответствии с курсом.</p>
    <p>Курс обмена:</p>
    <ul>
      <li>При обмене на сумму до 1000Р: 1 DonatMoney = <?echo $cenamoney1?>$ <i class="fa fa-rub" aria-hidden="true"></i></li>
      <li>При обмене на сумму от 1000P до 2000P: 1 DonatMoney = <?echo $cenamoney2?>$ <i class="fa fa-rub" aria-hidden="true"></i></li>
      <li>При обмене на сумм свыше 2000P: 1 DonatMoney = <?echo $cenamoney3?>$ <i class="fa fa-rub" aria-hidden="true"></i></li>
    </ul>
    <hr>
<form method='POST' action='backend/updateMoney.php'>
 <div class="input-group">
   <input type="number" name="money" id="MoneyCheck" class="form-control rounded" placeholder="Сколько DonatMoney Вы хотите поменять?">
	<span class="input-group-btn">
        <button type="submit" class="btn btn-success" type="button">Обменять</button>
  </span>
</div>
 </br><p id="MoneyCalc"></p>
</form>
</div>
</div>


  <!--Випка-->
  <div class="tab-pane fade" id="vip">
  <div class="form-group">
 <p>Вступление в VIP-клуб доступно постоянным игрокам, накопившим необходимое количество DonatMoney. Кроме членской карты в виде особого паспорта, вы получите много других привелегий:</p>
    <ul>
    <li><b>Общая скидка при покупке оружия, техники, лицензий, страховки и других вещей:</li></b>
	1ур. - 20%, 2ур. - 30%, 3ур. - 40%, 4ур. - 55% 5ур. - 70%
    <li><b>Увеличенная цена на продаже ресурсов:</b></li>
	1ур. - 5%, 2ур. - 7%, 3ур. - 10%, 4ур. - 15%, 5ур. - 20%
	<li><b>Увеличена  добыча кол-во ресурса за взмах:</b></li>
	1ур. - 2, 2ур. - 3, 3ур. - 4, 4ур. - 5, 5ур. - 6
    <li><b>Ускорение скорости добычи и переработки ресурсов (до 80%)</b></li>
    <li><b>Увеличение максимально возможного количества домов в два раза</b></li>
    <li><b>Практически в каждом магазине появляется что-то новое (одежда, оружие, авто, грузовики, вертолеты)</b></li>
    </ul>
  <p><h3>Членство в VIP-клубе выписывается на 30 дней</h3></p><hr>
  <? if ($dnlvl > 0) {
    echo '<div class="alert alert-danger">У вас активен VIP-статус. Продлить можно будет только после окончания текущего периода!</div>';

  } else {
    echo '<form method="POST" action="backend/updateVip.php">
<div class="input-group">
<select name="vipLevel" class="form-control">
  <option value="1">1 уровень = '.$cenavip1.'</option>
  <option value="2">2 уровень = '.$cenavip2.'</option>
  <option value="3">3 уровень = '.$cenavip3.'</option>
  <option value="4">4 уровень = '.$cenavip4.'</option>
  <option value="5">5 уровень = '.$cenavip5.'</option>
</select>
<span class="input-group-btn"><button type="submit" class="btn btn-success">Вступить</button></div></form>'
  ;}
  ?>
</div>
</div>
<!--ВипкаEnd-->
<div class="tab-pane fade" id="transfer">
<form method='POST' action="backend/transfer.php">
<center><strong>Перевести DonatMoney игроку</strong>
<br><br>
<p>Комиссия не взимается. Если SteamID игрока не будет найден в базе, перевод не осуществиться!</p><br>
<div class="input-group">	
    <input type="number" name="trsteamid" placeholder="SteamID игрока" class="form-control" required="required">
    <input name="trsum" placeholder="Кол-во DonatMoney" type="number" class="form-control" required="required">
	<button type="submit" class="btn btn-success btn-sm btn-block">Перевести</button>
	 </div>
</form></center>
</div>

<div class="tab-pane fade" id="prices">
<center><p>Текущие цены на ресурсы. Обновляются по мере работы динамической экономики</p></center>
<table class="table table-striped">
   <tr>
    <th>Название</th>
    <th>Цена</th>
    <th>Статус</th>
   </tr>
   <?
    $stmt = $pdo->prepare('SELECT * FROM economy');
    $stmt->execute();
foreach ($stmt as $row)
{   
    if ($row['legal'] == 1) {
        $legality = '<td class="success">Нелегально';
    } else {$legality = '<td class="info">Легально';}
    $Pform = '$'.number_format($row['sellprice']).'';
    echo '<tr><td>'.$row['localize'].'</td><td>'.$Pform.'</td>'.$legality.'</td></tr>';
}?>
</table>
</form>
</div>
<!--ЦеныEnd-->
    <div class="tab-pane fade" id="sms">
       <center>
             <p>Отображатся 50 последних сообщений</p>
        </center>
         <table class="table table-bordered">
			<tr>
                <th>От кого</th>
                 <th>Кому</th>
				<th>Текст</th>
				<th>Когда</th>
              </tr>
         <?
			$stmt = $pdo->prepare('SELECT * FROM messages WHERE fromID=? OR toID=? ORDER BY time DESC LIMIT 50');
            $stmt->execute(array($steamprofile['steamid'],$steamprofile['steamid']));
            foreach ($stmt as $row)
               {  	
                    echo '<tr><td class="info">'.$row['fromName'].'</td><td class="warning">'.$row['toName'].'</td><td class="success">'.$row['message'].'</td><td class="active">'.$row['time'].'</td></tr>';
               }?>
          </table>
    </div>
	
	<div class="tab-pane fade" id="wanted">
       <center>
             <p>Особоопасные преступники проявившие активность последнии 30 дней</p>
        </center>
         <table class="table table-bordered">
			<tr>
                <th>ФИО</th>
                <th>Награда</th>
				<th>Последнее преступление</th>
              </tr>
         <?
			$stmt = $pdo->prepare('SELECT wantedName, wantedBounty, insert_time FROM wanted WHERE wantedBounty > 100000 AND insert_time > DATE_ADD(NOW(), INTERVAL -30 DAY) ORDER BY wantedBounty DESC LIMIT 30');
             $stmt->execute();
			foreach ($stmt as $row)
               {  	
                    echo '<tr><td class="warning">'.$row['wantedName'].'</td><td class="success">'.$row['wantedBounty'].'$</td><td class="active">'.$row['insert_time'].'</td></tr>';
               }?>
          </table>
    </div>
	
	<div class="tab-pane fade" id="forbes">
       <center>
             <p>Богачи острова</p>
        </center>
         <table class="table table-bordered">
			<tr>
                <th>Имя</th>
                <th>Счет в банке</th>
              </tr>
         <?
			$stmt = $pdo->prepare('SELECT name, bankacc FROM players ORDER BY bankacc DESC LIMIT 15');
             $stmt->execute();
			foreach ($stmt as $row)
               {  	
                    echo '<tr><td class="active">'.$row['name'].'</td><td class="success">'.$row['bankacc'].'$</td></tr>';
               }?>
          </table>
    </div>
	
	<?if(in_array($steamprofile['steamid'],$adminmenu, true)) { echo '<div class="tab-pane fade" id="adminmenu">
				<div class="form-group">
                                        <form method="POST" action="backend/generatePromo.php">
										<input name="number" placeholder="Количество промокодов" type="number" class="form-control" required="required">
                                    
                                    <select name="type" class="form-control" required>
                                    	<option value="" disabled selected hidden>Что выдаём?</option>
                                    	<option value="VEHICLE">Наземный транспорт</option>
                                    	<option value="AIR">Воздушный транспорт</option>
                                    	<option value="SHIP">Морской транспорт</option>
										<option value="DONATE">DonatMoney</option>
                                    	<option value="MONEY">Островские деньги</option>
                                    	<option value="VIP">Випка</option>
                                    </select>        
                                    <input type="text" class="form-control" name="promo" required="required" placeholder="Кол-во денег, DonatMoney\Ур. VIP\Класснэйм">
                                    </div>
                                    <button type="submit" class="btn btn-success">Сгенерировать</button>
                                    </form>
                                    <hr>
                                    
                                <form method="POST" action="backend/givePoints.php">
                                    <div class="form-group">
                                        <input type="number" class="form-control"  name="id"  maxlength="17" minlength="17" placeholder="STEAMID игрока" required="required">
                                        <input type="number" class="form-control" name="points" placeholder="Количество DonatMoney" required="required">
                                    </div>
                                    <button type="submit" class="btn btn-success" type="button">Пополнить</button>
                                </form>
    </div>';}?>
	
</div>
</div>
</div>
<div class="alert alert-danger">
<strong>Внимание! Перед выполнением любых действий, связаннных с DonatMoney или Промокодом - выйдите в лобби!</strong></div>
</div>
</div><!-- /.main -->
</div><!-- /.container -->
                <div class="text-center">
                   		<span id="footer-line"><b>Личный кабинет by <a href="http://easyfrag.ru/">stop4ik</a></b></span>
                </div>

<script>
   Number.prototype.formatMoney = function(c, d, t){
var n = this,
    c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
$("#MoneyCheck").keyup(function(e) {
    var first = $("#MoneyCheck").val() * 1;
    var result;

    if (first < 10) {
        $("#MoneyCalc").text("Слишком маленькая сумма");
    } else {
        if (first<=1000) {
            result = Math.round((first * <?echo $cenamoney1?>));
            $("#MoneyCalc").text("Вы получите: $"+result.formatMoney(0));
        };
        if (first>999 && first<=2000) {
            result = Math.round((first * <?echo $cenamoney2?>));
            $("#MoneyCalc").text("Вы получите: $"+result.formatMoney(0)+" (Тариф: 1 DonatMoney = <?echo $cenamoney2?>$)");
        };
        if (first>1999) {
            result = Math.round((first * <?echo $cenamoney3?>));
            $("#MoneyCalc").text("Вы получите: $"+result.formatMoney(0)+" (Тариф: 1 DonatMoney = <?echo $cenamoney3?>$)");
        };
    };
});
$("#NameCheck").keyup(function(e) {
    this.value = this.value.replace(/[^a-zA-Z ]+/, '');
    this.value = this.value.replace(/\s\s+/g, ' ');
});
window.setTimeout(function () {
    $("#alert").fadeTo(500, 0).slideUp(500, function () {
        $(this).remove();
    });
}, 7000);
window.setTimeout(function () {
    $("#success").fadeTo(500, 0).slideUp(500, function () {
        $(this).remove();
    });
}, 7000);
</script>
<body>
</html>