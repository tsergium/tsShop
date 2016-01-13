<?php
const DB_CREDENTIALS = [
    "dbServer"   => "host",
    "dbName"     => "name",
    "dbUser"     => "user",
    "dbPass"     => "pass"
];

function html_to_text($string){

	$search = array (
		"'<script[^>]*?>.*?</script>'si",  // Strip out javascript
		"'<[\/\!]*?[^<>]*?>'si",  // Strip out html tags
		"'([\r\n])[\s]+'",  // Strip out white space
		"'&(quot|#34);'i",  // Replace html entities
		"'&(amp|#38);'i",
		"'&(lt|#60);'i",
		"'&(gt|#62);'i",
		"'&(nbsp|#160);'i",
		"'&(iexcl|#161);'i",
		"'&(cent|#162);'i",
		"'&(pound|#163);'i",
		"'&(copy|#169);'i",
		"'&(reg|#174);'i",
		"'&#8482;'i",
		"'&#149;'i",
		"'&#151;'i",
		"'&#(\d+);'e"
		);  // evaluate as php
	
	$replace = array (
		" ",
		" ",
		"\\1",
		"\"",
		"&",
		"<",
		">",
		" ",
		"&iexcl;",
		"&cent;",
		"&pound;",
		"&copy;",
		"&reg;",
		"<sup><small>TM</small></sup>",
		"&bull;",
		"-",
		"uchr(\\1)"
		);
	
	$text = preg_replace ($search, $replace, $string);
	return $text;
	
}

function replace_not_in_tags($find_str, $replace_str, $string) {
	
	$find = array($find_str);
	$replace = array($replace_str);	
	preg_match_all('#[^>]+(?=<)|[^>]+$#', $string, $matches, PREG_SET_ORDER);	
	foreach ($matches as $val) {	
		if (trim($val[0]) != "") {
			$string = str_replace($val[0], str_replace($find, $replace, $val[0]), $string);
		}
	}	
	return $string;
}

$s = mysql_connect(DB_CREDENTIALS['dbServer'], DB_CREDENTIALS['dbUser'], DB_CREDENTIALS['dbPass']) or die(mysql_error());
$db = mysql_select_db(DB_CREDENTIALS['dbName'], $s) or die(mysql_error());

$datafeed_separator = "|"; 
$datafeed_currency = "RON";


$din_categoriile = array();
$sql_txt = "SELECT \r\n";
$sql_txt.= " * \r\n";
$sql_txt.= "FROM `ts_categories` \r\n";
$sql_rez = mysql_query($sql_txt) or die(mysql_error());
if (mysql_num_rows($sql_rez)) {
	while (($sc = mysql_fetch_assoc($sql_rez))) {
		$din_categoriile[] = $sc;
	} mysql_free_result($sql_rez);
	unset($sc);
} else {
	mysql_free_result($sql_rez);
}

$i = 0;
foreach ($din_categoriile as $value) {

	$sql_txt = "SELECT \r\n";
	$sql_txt.= " p.`id`,p.`name`,p.`price`,p.`description`,p.`imageShopmania` \r\n";
	$sql_txt.= "FROM `ts_products` as `p` \r\n";
	$sql_txt.= "LEFT JOIN `ts_products_categ_asoc` as `pa` ON pa.`productId`  = p.`id` \r\n";
	$sql_txt.= "WHERE pa.`categoryId` = '{$value['id']}' \r\n";
	$sql_txt.= "AND p.`status` = '1' \r\n";
	$sql_rez = mysql_query($sql_txt) or die(mysql_error());
	if (mysql_num_rows($sql_rez)) {
		while (($sc = mysql_fetch_assoc($sql_rez))) {
			$i++;			
			$cod_produs =  "LDL".$sc['id'];
			$descriere_produs = strip_tags($sc['description']);
			$descriere_produs = replace_not_in_tags("\n", "<BR />", $descriere_produs);
			$descriere_produs = str_replace("\n", " ", $descriere_produs);
			$descriere_produs = str_replace("\r", "", $descriere_produs);
			$link_produs = "http://www.lenjerie-de-lux.ro/p".$sc['id']."-".preg_replace('/[^a-zA-Z0-9]+/','-', strtolower($value['name']."-".$sc['name'])).".html";
			$cale_poza = "http://www.lenjerie-de-lux.ro/media/products/shopmania/".$sc['imageShopmania'];
			$pret = $sc['price'];

			print "Lenjerie intima|lenjerie-de-lux.ro|".ucfirst($value['name'])."|".$cod_produs."|".$sc['name']."|".$descriere_produs."|".$link_produs."|".$cale_poza."|".$pret."|RON \n";

		} mysql_free_result($sql_rez);
		unset($sc);
	} else {
		mysql_free_result($sql_rez);
	}
}