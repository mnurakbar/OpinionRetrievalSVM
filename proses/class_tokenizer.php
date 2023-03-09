<?php

class Preprocessing extends database{

	private $tokenKarakter=array('’','—',' ','/','?','.',':',';',',','!','[',']','{','}','(',')','-','_','+','=','<','>','\'','"','\\','@','#','$','%','^','&','*','`','~','0','1','2','3','4','5','6','7','8','9','â€','”','“');
	public $text;
	public $kataAsal=array();
	//hapus tanda baca
	public function tokenText($text){
		$this->text = str_replace($this->tokenKarakter,' ',$text);
		/* Use tab and newline as tokenizing characters as well  */
		$tok = strtok($this->text, "\n\t");

		while ($tok !== false) {
    		$this->text = $tok;
    		$tok = strtok(" \n\t");
		}
	}
	
	//hapus stopword
	public function removeStopword(){
	
		$words = explode(" ",trim(strtolower($this->text)));
		$words = str_replace(" ","",$words);
		$qry = $this->query("select stoplist from tb_stoplist");
		$u=0;
		
		while($stoplist = $this->tampilkan()){
			$stopword[$u] = trim($stoplist[0]);	
			$u++;
		}	
		//print_r($words);
		$stopList = array_intersect($stopword, $words);
		$hapusStoplist = array_diff($words, $stopList);
		$this->kataAsal = $hapusStoplist;
		//print_r($this->kataAsal);
		$this->text = implode(" ",$hapusStoplist);
	}
	
}
/*
$preProcess = new Preprocessing;
$preProcess->tokenText($string);
$preProcess->removeStopword();
//echo $preProcess->text;*/
?> 