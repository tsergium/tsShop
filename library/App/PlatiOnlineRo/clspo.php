<?php

class PO3{

	public  $KeyEnc=null;
	public  $KeyMod=null;
	public  $LoginID=null;
	public  $amount=null;
	public  $currency=null;
	public  $OrderNumber=null;
	public  $action=null;
	public  $TransID=null;
	public  $shipping=null;
	public  $awb=null;
	public  $ret;
	public  $version="3.0.3";

	var $strMult;
	var $sTemp;
	var $sAsci;
	var $iAsci;
	var $decSt;

	var $rep;
	var $hex;
	var $tot;
	var $encSt;
	

	function PO3(){
		$this->version="3.0.3";
	}

	function Mult($x){
		$y=1;
		$pg=$this->KeyEnc;
		$m=$this->KeyMod;
		while ($pg > 0){
			while (($pg / 2)==intval($pg / 2)){
				$x=$this->nMod(($x*$x),$m);
				$pg=$pg / 2;			
			}
			$y=$this->nMod(($x*$y),$m);
			$pg=$pg-1;
		}
		return ($y);
	}

	function nMod($x,$y){
		$bInt=0;
		if($y==0){
			return;
		};
		$bInt=$x-(intval($x/$y)*$y);	
		return($bInt);
	}

	////////////////// GCEncode/////////////////////////
	//metoda de codificare pe baza cheilor
	////////////////////////////////////////////////////
	function GCEncode($tIp){
		$this->encSt ="";
		for($z=0;$z < strlen($tIp);$z++){
			$this->sTemp = substr($tIp,$z,1);
			$this->sAsci = ord($this->sTemp);
			$this->iAsci = intval($this->sAsci);			
			$this->encSt=$this->encSt.$this->NumberToHex($this->Mult($this->iAsci),8);
		}
		return strtoupper($this->encSt);
	}


	////////////////// NumberToHex////////////////////////////
	//metoda de conversie a unui numar din zecimal in hexa 
	//////////////////////////////////////////////////////////
	function NumberToHex($pLngNumber,$pLngLength){
		$this->rep = str_repeat("0",$pLngLength);	
		$this->hex = dechex($pLngNumber);
		$this->tot = $this->rep.$this->hex;	
		return(substr($this->tot,strlen($this->tot) - $pLngLength,strlen($this->tot)));
	}


	////////////////// HexToNumber/////////////////////////
	//metoda de conversie a unui numar hexa in zecimal
	///////////////////////////////////////////////////////
	function HexToNumber($pStrHex){
		$this->ret = intval(hexdec("&h".$pStrHex));	
		return($this->ret);
	}

	/////////////////// hex2str/////////////////////////
	//metoda de conversie a unui numar hexa in string
	////////////////////////////////////////////////////
	function hex2str($hex){
		$str="";
		for($i=0;$i<strlen($hex);$i+=2){
			$str.=chr(hexdec(substr($hex,$i,2)));
		}
		return $str;
	}


	//key is a hexadecimal number
	////////////////// POEncode/////////////////////////
	//metoda de codificare pe baza cheilor
	////////////////////////////////////////////////////
	function POEncode($key, $data) {
		$key=$this->hex2str($key);
		$blocksize=64;
		$hashfunc='sha1';
		if (strlen($key)>$blocksize)
		$key=pack('H*', $hashfunc($key));
		$key=str_pad($key, $blocksize, chr(0x00));
		$ipad=str_repeat(chr(0x36),$blocksize);
		$opad=str_repeat(chr(0x5c),$blocksize);
		$hmac = pack(
			'H*',$hashfunc(
				($key^$opad).pack(
					'H*',$hashfunc(
						($key^$ipad).$data
					)
				)
			)
		);
		return bin2hex($hmac);
	}

	////////////////// InsertHash /////////////////////////
	//metoda de codificare a mesajului - Cerere de Autorizare card
	////////////////////////////////////////////////////
	public function InsertHash_Auth(){
		$sequence = rand(1, 1000);
		$tstamp = date("m")."/".date("d")."/".date("Y")." ".date("h:i:s a");                         
		$msg = $sequence."^".$this->LoginID."^".$tstamp."^".$this->amount."^".$this->currency."^".$this->OrderNumber."^".$this->action;
		$stringRSA=$this->GCEncode(strval($msg));
		$cheie = strval($this->KeyEnc).strval($this->KeyMod);
		$sEncoded= $this->POEncode($cheie, $stringRSA);
		$ret='';
		$ret.= "<input type = hidden name = \"f_message\" value=\"".$msg."\">";
		$ret.= "<input type = hidden name = \"f_crypt_message\" value=\"".$sEncoded."\">";
		$ret.= "<input type = hidden name = \"f_action\" value = \"".$this->action."\" >";
		return $ret;
	}


	////////////////// InsertHash_Incasare/////////////////////////
	//metoda Cerere de incasare
	////////////////////////////////////////////////////////////////
	public function InsertHash_Incasare(){
		$sequence = rand(1, 1000);
		$tstamp = date("m")."/".date("d")."/".date("Y")." ".date("h:i:s a");                         
		$msg = $sequence."^".$this->LoginID."^".$tstamp."^".$this->TransID."^".$this->OrderNumber."^".$this->awb."^".$this->shipping."^".$this->action;
		$stringRSA=$this->GCEncode(strval($msg));
		$cheie = strval($this->KeyEnc).strval($this->KeyMod);
		$sEncoded= $this->POEncode($cheie, $stringRSA);
		$ret='';
		$ret.= "<input type = hidden name = \"f_message\" value=\"".$msg."\">";
		$ret.= "<input type = hidden name = \"f_crypt_message\" value=\"".$sEncoded."\">";
	//	$ret.= "<input type = hidden name = \"f_test_request\" value=\"1\">";
		$ret.= "<input type = hidden name = \"f_action\" value = \"".$this->action."\" >";
		return $ret;
	}



	////////////////// InsertHash_Void//////////////////////////////
	//metoda de anulare
	////////////////////////////////////////////////////////////////
	public function InsertHash_Void(){
		$sequence = rand(1, 1000);
		$tstamp = date("m")."/".date("d")."/".date("Y")." ".date("h:i:s a");                         
		$msg = $sequence."^".$this->LoginID."^".$tstamp."^".$this->TransID."^".$this->OrderNumber."^".$this->action;
		$stringRSA=$this->GCEncode(strval($msg));
		$cheie = strval($this->KeyEnc).strval($this->KeyMod);
		$sEncoded= $this->POEncode($cheie, $stringRSA);
		$ret='';
		$ret.= "<input type = hidden name = \"f_message\" value=\"".$msg."\">";
		$ret.= "<input type = hidden name = \"f_crypt_message\" value=\"".$sEncoded."\">";
	//	$ret.= "<input type = hidden name = \"f_test_request\" value=\"1\">";
		$ret.= "<input type = hidden name = \"f_action\" value = \"".$this->action."\" >";
		return $ret;
	}

	
	////////////////// InsertHash_Interog/////////////////////////
	//metoda de interogare
	////////////////////////////////////////////////////////////////
	public function InsertHash_Interog(){
		$sequence = rand(1, 1000);
		$tstamp = date("m")."/".date("d")."/".date("Y")." ".date("h:i:s a");                         
		$msg = $sequence."^".$this->LoginID."^".$tstamp."^".$this->TransID."^".$this->OrderNumber."^".$this->action;
		$stringRSA=$this->GCEncode(strval($msg));
		$cheie = strval($this->KeyEnc).strval($this->KeyMod);
		$sEncoded= $this->POEncode($cheie, $stringRSA);
		$ret='';
		$ret.= "<input type = hidden name = \"f_message\" value=\"".$msg."\">";
		$ret.= "<input type = hidden name = \"f_crypt_message\" value=\"".$sEncoded."\">";
	//	$ret.= "<input type = hidden name = \"f_test_request\" value=\"1\">";
		$ret.= "<input type = hidden name = \"f_action\" value = \"".$this->action."\" >";
		return $ret;
	}


	////////////////// InsertHash_Credit/////////////////////////
	//metoda de creditare
	////////////////////////////////////////////////////////////////
	public function InsertHash_Credit(){
		$sequence = rand(1, 1000);
		$tstamp = date("m")."/".date("d")."/".date("Y")." ".date("h:i:s a");                         
		$msg = $sequence."^".$this->LoginID."^".$tstamp."^".$this->TransID."^".$this->OrderNumber."^".$this->amount."^".$this->action;
		$stringRSA=$this->GCEncode(strval($msg));
		$cheie = strval($this->KeyEnc).strval($this->KeyMod);
		$sEncoded= $this->POEncode($cheie, $stringRSA);
		$ret='';
		$ret.= "<input type = hidden name = \"f_message\" value=\"".$msg."\">";
		$ret.= "<input type = hidden name = \"f_crypt_message\" value=\"".$sEncoded."\">";
	//	$ret.= "<input type = hidden name = \"f_test_request\" value=\"1\">";
		$ret.= "<input type = hidden name = \"f_action\" value = \"".$this->action."\" >";
		return $ret;
	}

	public function VerifyFRM($msg){
		$stringRSA=$this->GCEncode(strval($msg));
		$cheie = strval($this->KeyEnc).strval($this->KeyMod);
		$sEncoded=$this->POEncode($cheie, $stringRSA);
		return $sEncoded;
	}
}
?>
