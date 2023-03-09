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

$fileWrite = "o_stem_train.txt";
$fHasil = fopen($fileWrite, 'w') or die("can't open file");


    // tentukan nama file yang akan dibaca
   // $nama_file = "i_corpus.txt";
    
    // baca file, masukkan ke array
   // $isi = file($nama_file);
    
    // cari jumlah baris
    //$jumlah_baris = sizeof($isi);
    
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
        fwrite($fHasil, $teks); 
        //echo "\"". $teks . "\",";
        //echo $teks;
   
    }
    fwrite($fHasil, "\n"); 
    //echo "<br>";
    
}

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