<?php 
/*$fp = fopen("/var/log/nginx/access.log","r");
while(!feof($fp))
{
	$data = fgets($fp);
	preg_match("#^\d{1,3}\.+\d{1,3}\.+\d{1,3}\.+\d{1,3} -.*\"(GET|POST).*HTTP.*\"$#",$data,$data2);
	var_export($data2);
}
*/
/*
*250 sayısını artırarak log dosyasının içinde incelenecek satır sayısını artırırsınız
*/

$data = exec("tail -n 250 /var/log/nginx/access.log",$out,$errorno);
$d = array();
foreach($out as $data)
{
	preg_match("#^\d{1,3}\.+\d{1,3}\.+\d{1,3}\.+\d{1,3}#",$data,$ip);
	$sayac = 0;
	preg_match("#\[\d{1,2}+/\w{1,5}+/\d{1,4}:\d{1,2}:\d{1,2}#",$data,$tarih);
	foreach($out as $data2)
	{
		preg_match("#^\d{1,3}\.+\d{1,3}\.+\d{1,3}\.+\d{1,3}#",$data2,$ip2);
		preg_match("#\[\d{1,2}+/\w{1,5}+/\d{1,4}:\d{1,2}:\d{1,2}#",$data2,$tarih2);

		if($ip===$ip2 && $tarih===$tarih2)
		{
			$sayac++;
		}
	}
	$sonuc[$ip[0]]=$sayac;
	$sonuc = array_unique($sonuc);
}

foreach($sonuc as $key=>$value)
{
	if($key!="haric ip adresi kendi ip adresinizi buraya yazarsanız engellemez")
	{
		if($value>3)
		{
			exec("iptables -I INPUT -s ".$key." -j DROP");

		}else
		{
			//Herhangi bir ip adresinin kayıtını bulamazsa iptables input chaini temizler
			exec("iptables -F INPUT");
		}
		echo $key.$value." Kere bulundu.".PHP_EOL;
	}
}
