<?php
$server = $_POST['server'];
$site = $_POST['site'];
$phrase = $_POST['phrase'];
define(YANDEX, 1);
define(RAMBLER, 2);
define(APORT, 3);
?>
<form method = "POST">
<select name = "server">
<option value = <?php echo YANDEX;?>
<? if($server == YANDEX) echo "selected";?>>YANDEX</option>

<option value = <?php echo RAMBLER;?>
<? if($server == RAMBLER) echo "selected";?>>RAMBLER</option>

<option value = <?php echo APORT;?>
<? if($server == APORT) echo "selected";?>>APORT</option>

</select>
<br>
Сайт: <input type = "text" name = "site" size = "40" maxlength = "256" value = '<? echo $site ?>'><br>
Фраза: <input type = "text" name = "phrase" size = "40" maxlength = "256" value = '<? echo $phrase ?>'><br>
<input type = "submit" value = "Искать">
</form>
<?
function siteposition($server, $site, $phrase)
{
	$numberpage = 0;
	switch ($server)
	{
		case YANDEX:
		$phrase = trim($phrase);
		$phrase = rawurldecode($phrase);
		$endstr = str_replace("%20", "+", $phrase);
		$total = 10;
		$numberpage = -1;
		break;
		case APORT:
		$phrase = trim($phrase);
		$phrase = rawurlencode($phrase);
		$endstr = str_replace("%20", "+", $phrase);
		$total = 10;
		$numberpage = -1;
		break;
		case RAMBLER:
		$phrase = trim($phrase);
		//преобразуем кодировку
		$phrase = rawurlencode(convert_cyr_string ($phrase, "w", "k"));
		$endstr = str_replace("%20", "+", $phrase);
		$total = 152;
		$numberpage = -14;
	}
	while ((count($arr[0]) == 0) && ($numberpage < $total))
	{
		switch($server)
		{
			case YANDEX:
			$numberpage += 1;
			break;
			case APORT:
			$numberpage += 1;
			break;
			case RAMBLER:
			$numberpage += 15;
			break;
		}
		$strurl = geturl ($numberpage, $endstr, $server);
		$text = openpage($strurl);
		$arr = findsite($site, $text);
	}
	if (count($arr[0])>0)
	{
		switch($server)
		{
			case YANDEX:
			echo "Ссылка на сайт найдена на старнице:".($numberpage+1)."<br>";
			echo "Перейти по ссылке: <a href = '$strurl'>Яндекс</a><br>";
			list($startpattern, $endpattern) = pattern(YANDEX);
			echo "<br>Позиция".findposition($server, $site, $text, $startpattern, $endpattern);
			break;
			case APORT:
			echo "Ссылка на сайт найдена на старнице:".($numberpage+1)."<br>";
			echo "Перейти по ссылке: <a href = '$strurl'>Апорт</a><br>";
			list($startpattern, $endpattern) = pattern(APORT);
			echo "<br>Позиция".findposition($server, $site, $text, $startpattern, $endpattern);
			break;
			case RAMBLER:
			$phrase = rawurlencode(convert_cyr_string (rawurlencode($phrase), "k", "w"));
			$endstr = str_replace("%20", "+", $phrase);
			$strurl = geturl ($numberpage, $endstr, $server);
			$numberpage = $numberpage/15;
			
			echo "Ссылка на сайт найдена на старнице:".($numberpage+1)."<br>";
			echo "Перейти по ссылке: <a href = '$strurl'>Яндекс</a><br>";
			list($startpattern, $endpattern) = pattern(YANDEX);
			echo "<br>Позиция".findposition($server, $site, $text, $startpattern, $endpattern);
			break;
		}
	}
}
//если все поля были заполнены
if (($server != "") && (phrase != "") && (site != ""))
{
	siteposition($server, $site, $phrase);
}
?>


