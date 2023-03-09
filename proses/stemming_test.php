<?php
include('class_koneksi.php');
include('Enhanced_CS.php');
include('class_tokenizer.php');

$kon = new database;
$kon->database();
$pre = new Preprocessing;

//echo "<font size='7'><b>Enhanced CS Stemmer For Indonesian Text Only</b></font>";
//echo "<form action='' method='post'><textarea name=text cols=50 rows=10></textarea><br><input type=submit name=proses value=Go!></form>";

$fileRead = "input_test.txt";
$fSumber = fopen($fileRead, 'r') or die("can't open file");

$fileWrite = "o_stem_test.txt";
$fHasil = fopen($fileWrite, 'w') or die("can't open file");

$fileRead = "input_judul.txt";
$fSumberJudul = fopen($fileRead, 'r') or die("can't open file");

$fileWrite = "o_stem_judul.txt";
$fHasilJudul = fopen($fileWrite, 'w') or die("can't open file");

//stemming isi testing

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

//stemming judul testing

while (($teks = fgets($fSumberJudul))!== false){
    
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
        fwrite($fHasilJudul, $teks); 
//        echo "\"". $teks . "\",";
//        echo $teks;
//   
    }
    fwrite($fHasilJudul, "\n"); 
   // echo "<br>";
    
}



fclose($fHasil);
fclose($fSumber);
fclose($fHasilJudul);
fclose($fSumberJudul);

?>