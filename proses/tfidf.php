<?php
ini_set('memory_limit', '-1');
include("o_corpus.php");
$corpus_size = sizeof($corpus);

if (isset($_POST['Pilih'])){
    $data = $_POST['data'];
}

//buka file input
if ($data=="train"){
$fileRead = "o_stem_train.txt";    
$fSumber = fopen($fileRead, 'r') or die("can't open file");
}
else {
$fileRead = "o_stem_test.txt";
$fSumber = fopen($fileRead, 'r') or die("can't open file");
}

//buka file kelas
if ($data=="train"){
$fh = fopen("kelas_train.txt", "r");
}
else {
$fh = fopen("kelas_test.txt", "r");
}


//buka file kelas

$kelas=array();
$baris = 0;
while(true)
	{
    $line = fgets($fh);
    if($line == null)break;
    $kelas[$baris]=$line;
    $kelas[$baris]=trim($kelas[$baris]);
    $baris=$baris+1;
	}
fclose($fh);
//foreach($kelas as $nilai){
//    echo $nilai."<br>";
//}


//buka file output
if ($data=="train"){
$fileWrite = "svm/data/train.dat";
$fHasil = fopen($fileWrite, 'w') or die("can't open file");    
}
else{
$fileWrite = "svm/data/test.dat";     
$fHasil = fopen($fileWrite, 'w') or die("can't open file");
}


echo "anda memilih data : <b> <font color='red'> $data </red> </b>";

$tf = array();
while (($line = fgets($fSumber)) !== false) {
	$tf_baris = array();
	for ($i = 0; $i < $corpus_size; $i++) {
		array_push($tf_baris, 0);
	}
    $tok = strtok( $line," \n\t");
	$res_line = "";
	while ($tok != false) {	
		$cur_word = $tok;
	 
		$key = array_search($cur_word, $corpus);
		$tf_baris[ $key ] ++;
	
		$tok = strtok(" \n\t");
	}
    
	$tok = strtok( $res_line," \n\t");	
	

	array_push($tf,$tf_baris);

}
$tf_baris[0]=$tf_baris[0]+1;

// hitung tf-idf
$idf = array();
$jml_data = sizeof($tf);
for( $i = 0 ; $i < $corpus_size ; $i++) {
	$hitung = 0;
	
	//hitung idf
	for( $j = 0 ; $j < $jml_data ; $j++) {
		if( $tf[$j][$i] > 0 ) $hitung++ ;
	}
	if ($hitung > 0 ) $hitung = log($jml_data / $hitung);
	else $hitung = 1;
	
	//kalikan tf dengan idf
	for( $j = 0 ; $j < $jml_data ; $j++) {
		$tf[$j][$i] = $tf[$j][$i] * $hitung ;
	}
}


//nilai scaling
$max=0;
for( $j = 0 ; $j < $jml_data ; $j++) {
    for( $i = 0 ; $i < $corpus_size ; $i++) {
        if ($tf[$j][$i]>$max){
            $max=$tf[$j][$i];
        }
   	}  
}


$tmp=array();
//$bar = 1;
for( $j = 0 ; $j < $jml_data ; $j++) {
$kol = 1;
    for( $i = 0 ; $i < $corpus_size ; $i++) {
    set_time_limit(0);
        $tmp[$j][$kol]= $tf[$j][$i]/$max;
        $kol++;     	
	}
    }

//gabung kelas
for( $j = 0 ; $j < $jml_data ; $j++) {
    $tmp[$j][0]=$kelas[$j];
}

for( $j = 0 ; $j < $jml_data ; $j++) {
    for( $i = 0 ; $i <= $corpus_size ; $i++) {
                if ((($tmp[$j][$i])>0) or (($tmp[$j][$i])==-1)) {      
                if ($i==0){
                //$tfidf = $tmp[$j][$i]." ";
                //echo $tmp[$j][$i]." ";
                fwrite($fHasil, $tmp[$j][$i]." ");
                    }    
                else if($i!=1){
                //$tfidf = $i. ":" .$tmp[$j][$i]." ";    
                //echo $i. ":" .$tmp[$j][$i]." ";
                fwrite($fHasil, $i. ":" .$tmp[$j][$i]." "); 
                    }
                }
                }
            
            fwrite($fHasil, "\n");
            echo "<br>";
          }


fclose($fHasil);
fclose($fSumber);
?>