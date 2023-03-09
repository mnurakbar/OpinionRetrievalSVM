<?php
include('class_koneksi.php');
include('Enhanced_CS.php');
include('class_tokenizer.php');

$kon = new database;
$kon->database();
$pre = new Preprocessing;

//echo "<font size='7'><b>Enhanced CS Stemmer For Indonesian Text Only</b></font>";
//echo "<form action='' method='post'><textarea name=text cols=50 rows=10></textarea><br><input type=submit name=proses value=Go!></form>";

$fileRead = "input_train.txt";
$fSumber = fopen($fileRead, 'r') or die("can't open file");

$fileWrite = "o_corpus.php";
$fHasil = fopen($fileWrite, 'w') or die("can't open file");


    // tentukan nama file yang akan dibaca
    $nama_file = "input_train.txt";
    
    // baca file, masukkan ke array
    $isi = file($nama_file);
    
    // cari jumlah baris
    $jumlah_baris = sizeof($isi);

   
$count=0;
$array_corpus=array();
while (($teks = fgets($fSumber))!== false){
    $tokenKarakter=array('’','—',' ','/',',','?','.',':',';',',','!','[',']','{','}','(',')','-','_','+','=','<','>','\'','"','\\','@','#','$','%','^','&','*','`','~','0','1','2','3','4','5','6','7','8','9','â€','”','“');
    //echo ($teks."<br>");
    $teks= str_replace($tokenKarakter,' ',$teks);
    $teks = $pre->tokenText($teks);
    $teks = $pre->removeStopword();
    $teks = $pre->text;
		/* Use tab and newline as tokenizing characters as well  */
    $tok = strtok($teks, " \n\t");   
    
    
    
	while ($tok != false) {
		$teks = Enhanced_CS(trim($tok)).' ';
        $tok = strtok(" \n\t");
        $teks = trim($teks);
        $array_corpus[$count]=$teks;
        $count=$count+1;
        //fwrite($fHasil, "\"". $teks ."\","); 
        }

    
}


//cek double kata
for ($i=0; $i<$count; $i++){
    for ($j=0; $j<$count; $j++)    {
        if ( ($array_corpus[$i]==$array_corpus[$j]) and ($i<>$j) and ($i<$j) ) {
           $array_corpus[$i]="";         
        }     
    }
}


for ($i=0; $i<$count; $i++){
    if ($array_corpus[$i]==""){
        unset ($array_corpus[$i]);
    }
    }

fwrite($fHasil,'<?php $corpus = array("xVariabel",');


foreach ($array_corpus as $value){
    fwrite($fHasil,"\"".$value."\",");
}

//$fh = fopen("o_corpus.php", "r+");
//$dok = file_get_contents("o_corpus.php");
//echo $dok;

fwrite($fHasil,");"."\n"."?>");

//echo ("$array_corpus[0]"."$array_corpus[1]");
//echo $count;


//if (!empty($_POST['text'])){
//	$teks = strtolower($_POST['text']); /*Case Folding */
//	$tokenKarakter=array('’','—',' ','/',',','?','.',':',';',',','!','[',']','{','}','(',')','-','_','+','=','<','>','\'','"','\\','@','#','$','%','^','&','*','`','~','0','1','2','3','4','5','6','7','8','9','â€','”','“');
//	$teks= str_replace($tokenKarakter,' ',$teks);
//	$teks = $pre->tokenText($teks);
//	$teks = $pre->removeStopword();
//	$teks = $pre->text;
		/* Use tab and newline as tokenizing characters as well  */
//	$tok = strtok($teks, " \n\t");
//
//	while ($tok !== false) {
//    	echo $teks = Enhanced_CS(trim($tok)).'<br>';
//    	$tok = strtok(" \n\t");
//	}
//}

fclose($fHasil);
fclose($fSumber);
?>