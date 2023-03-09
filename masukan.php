<?php
error_reporting(0);
/**
 * @author akbar
 * @copyright 2012
 */
 
//$inputan='sony'; 


include('proses/class_koneksi.php');
include('proses/Enhanced_CS.php');
include('proses/class_tokenizer.php');
include('proses/o_corpus.php');
$corpus_size = sizeof($corpus);
ini_set('memory_limit', '-1');
$hasil_akhir=array();

//stemming dan stopword inputan query

$kon = new database;
$kon->database();
$pre = new Preprocessing;
    
    
    $tokenKarakter=array('’','—',' ','/',',','?','.',':',';',',','!','[',']','{','}','(',')','-','_','+','=','<','>','\'','"','\\','@','#','$','%','^','&','*','`','~','0','1','2','3','4','5','6','7','8','9','â€','”','“');
    $inputan = str_replace($tokenKarakter,' ',$inputan);
    $cek_in=trim($inputan);
    

    $inputan = $pre->tokenText($inputan);
    $inputan = $pre->removeStopword();
    $inputan = $pre->text;
		/* Use tab and newline as tokenizing characters as well  */
    $tok = strtok($inputan, " \n\t");   
    
     

    $input_stem=array();
    $i=0; 
	while ($tok != false) {
		$inputan = Enhanced_CS(trim($tok)).' ';
        $tok = strtok(" \n\t");
   $input_stem[$i]=$inputan;
   $i++;
    }
  
    $in_stem="";
    foreach ($input_stem as $nilai) {
    $in_stem=$in_stem.$nilai;
    }  
 // echo $in_stem."</br>";
/** --------------------------------BATAS STEMMING % TF IDF---------------------------------------*/


//buka file input
$fileRead = "proses/o_stem_test.txt";
$fSumber = fopen($fileRead, 'r') or die("can't open file");
$fileRead = "proses/o_stem_judul.txt";
$fSumberJudul = fopen($fileRead, 'r') or die("can't open file");
$fileRead = "proses/svm/output.dat";
$fSumberHasil = fopen($fileRead, 'r') or die("can't open file");
$fileRead = "proses/input_test.txt";
$fSumberAsli = fopen($fileRead, 'r') or die("can't open file");
$fileRead = "proses/input_judul.txt";
$fSumberJudulAsli = fopen($fileRead, 'r') or die("can't open file");
$fileRead = "proses/kelas_test.txt";
$fSumberTest = fopen($fileRead, 'r') or die("can't open file");



/** tf masukan */
    $count_in=0;

	$tf_baris_in = array();
	for ($i = 0; $i < $corpus_size; $i++) {
		array_push($tf_baris_in, 0);
	}
    $tok_in = strtok( $in_stem," \n\t");
   	while ($tok_in != false) {	
		$cur_word_in = $tok_in;
		$key_in = array_search($cur_word_in, $corpus);
        
        if (empty($key_in)){
        $count_in++;
        }
        
		$tf_baris_in[ $key_in ] ++;
		$tok_in = strtok(" \n\t");
        }
        
    $tf_baris_in[0]=$tf_baris_in[0]-$count_in;
    $cek_tf_in=0;
    for ($i = 0; $i < $corpus_size; $i++) {
		array_push($tf_baris_in, 0);
        $cek_tf_in=$cek_tf_in+$tf_baris_in[$i];
	}
    
/** penganganan error */
//    if (($cek_in=="") and ($cek_tf_in==0)) echo "silahkan masukkan inputan dengan benar ";
//    if (($cek_in!="") and ($cek_tf_in==0)) echo "pencarian Anda tidak ditemukan ";
//

//	for( $i = 0 ; $i < $corpus_size ; $i++) {
//		set_time_limit(0);
//		echo $tf_baris_in[$i]. ",";
//	}
//
//echo "</br>";
//
//



/** tf-idf masukan-isi */

$tf = array();
while (($line = fgets($fSumber)) !== false) {
    //echo $line;
    
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
        if (empty($key)){
        }
  
        
		$tok = strtok(" \n\t");
	}
	$tok = strtok( $res_line," \n\t");	
	array_push($tf,$tf_baris);
    //echo "</br>";
    
}

$jml_data = sizeof($tf);
for( $i = 0 ; $i < $corpus_size ; $i++) {
		set_time_limit(0);
		$tf[$jml_data][$i]=$tf_baris_in[$i];
	}

// hitung tf-idf
//$idf = array();
for( $i = 0 ; $i < $corpus_size ; $i++) {
	$hitung = 0;
	
	//hitung idf
	for( $j = 0 ; $j <= $jml_data ; $j++) {
		if( $tf[$j][$i] > 0 ) $hitung++ ;
	}
	if ($hitung > 0 ) $hitung = log($jml_data / $hitung);
	else $hitung = 1;
	
	//kalikan tf dengan idf
	for( $j = 0 ; $j <= $jml_data ; $j++) {
		$tf[$j][$i] = $tf[$j][$i] * $hitung ;

	}
}
//
//for( $j = 0 ; $j <= $jml_data ; $j++) {
//	for( $i = 0 ; $i < $corpus_size ; $i++) {
//		set_time_limit(0);
//		echo $tf[$j][$i]. ",";
//	}
//	echo "</br>";
//}


/** tf-idf masukan-judul */

$tf_judul = array();

while (($lineJudul = fgets($fSumberJudul)) !== false) {
    //echo $lineJudul."</br>";
	$tf_barisJudul = array();
	for ($i = 0; $i < $corpus_size; $i++) {
		array_push($tf_barisJudul, 0);
	}
    $tokJudul = strtok( $lineJudul," \n\t");
	$res_lineJudul = "";
	while ($tokJudul != false) {	
		$cur_wordJudul = $tokJudul;
			 
		$keyJudul = array_search($cur_wordJudul, $corpus);
		$tf_barisJudul[ $keyJudul ] ++;
         
        
		$tokJudul = strtok(" \n\t");
	}
	$tokJudul = strtok( $res_lineJudul," \n\t");	
	array_push($tf_judul,$tf_barisJudul);
}

$jml_data = sizeof($tf_judul);
	for( $i = 0 ; $i < $corpus_size ; $i++) {
		set_time_limit(0);
		$tf_judul[$jml_data][$i]=$tf_baris_in[$i];
	}
    
   // echo $tf_judul[5][0]."</br>";



 //hitung tf-idf
$idf = array();
//$jml_data = sizeof($tf_judul);
//echo $jml_data;
for( $i = 0 ; $i < $corpus_size ; $i++) {
	$hitung = 0;
	
//	hitung idf
	for( $j = 0 ; $j <= $jml_data ; $j++) {
		if( $tf_judul[$j][$i] > 0 ) $hitung++ ;
	}
	if ($hitung > 0 ) $hitung = log($jml_data / $hitung);
	else $hitung = 1;
	
//	kalikan tf dengan idf
	for( $j = 0 ; $j <= $jml_data ; $j++) {
		$tf_judul[$j][$i] = $tf_judul[$j][$i] * $hitung ;

	}
}


//for( $j = 0 ; $j <= $jml_data ; $j++) {
//	for( $i = 0 ; $i < $corpus_size ; $i++) {
//		set_time_limit(0);
//		echo $tf_judul[$j][$i]. ",";
//	}
//	echo "</br>";
//}
//

/** Hitung cosine Isi */

//Step 1 Perkalian skalar masukan dan Isi
$skalar_isi=array();
$skalar_isi_sum=array();
for( $j = 0 ; $j < $jml_data ; $j++) {
        $skalar_isi_sum[$j]=0;
	for( $i = 0 ; $i < $corpus_size ; $i++) {
		set_time_limit(0);
		$skalar_isi[$j][$i]=$tf[$j][$i]*$tf[$jml_data][$i];
        //echo $skalar_isi[$j][$i]. " ";
        $skalar_isi_sum[$j]= $skalar_isi_sum[$j]+$skalar_isi[$j][$i];
        }
        
//echo "</br>";
//echo $skalar_isi_sum[$j]."</br>";
}

//Step 2 kuadratkan 
$kuad_isi=array();
$kuad_isi_sum=array();
for( $j = 0 ; $j <= $jml_data ; $j++) {
    $kuad_isi_sum[$j]=0;
	for( $i = 0 ; $i < $corpus_size ; $i++) {
		set_time_limit(0);
		$kuad_isi[$j][$i]=$tf[$j][$i]*$tf[$j][$i];
       // echo $kuad_isi[$j][$i]. " ";
        $kuad_isi_sum[$j]=$kuad_isi_sum[$j]+$kuad_isi[$j][$i];
	}
    $kuad_isi_sum[$j]=sqrt($kuad_isi_sum[$j]);
     //echo "</br>";
//     echo $kuad_isi_sum[$j]."</br>";
}


//Step 3 hasil cosine
$cosine_isi=array();
for( $j = 0 ; $j < $jml_data ; $j++) {
    set_time_limit(0);
	$cosine_isi[$j]=($skalar_isi_sum[$j]/($kuad_isi_sum[$jml_data]*$kuad_isi_sum[$j]));
    //echo $cosine_isi[$j]." ";
	}


/** Hitung cosine judul */

//Step 1 Perkalian skalar masukan dan Isi
$skalar_judul=array();
$skalar_judul_sum=array();
for( $j = 0 ; $j < $jml_data ; $j++) {
        $skalar_judul_sum[$j]=0;
	for( $i = 0 ; $i < $corpus_size ; $i++) {
		set_time_limit(0);
		$skalar_judul[$j][$i]=$tf_judul[$j][$i]*$tf_judul[$jml_data][$i];
        //echo $skalar_judul[$j][$i]. " ";
        $skalar_judul_sum[$j]= $skalar_judul_sum[$j]+$skalar_judul[$j][$i];
        }
        
//echo "</br>";
//echo $skalar_judul_sum[$j]."</br>";
}

//Step 2 kuadratkan 
$kuad_judul=array();
$kuad_judul_sum=array();
for( $j = 0 ; $j <= $jml_data ; $j++) {
    $kuad_judul_sum[$j]=0;
	for( $i = 0 ; $i < $corpus_size ; $i++) {
		set_time_limit(0);
		$kuad_judul[$j][$i]=$tf_judul[$j][$i]*$tf_judul[$j][$i];
       // echo $kuad_judul[$j][$i]. " ";
        $kuad_judul_sum[$j]=$kuad_judul_sum[$j]+$kuad_judul[$j][$i];
	}
    $kuad_judul_sum[$j]=sqrt($kuad_judul_sum[$j]);
     //echo "</br>";
//     echo $kuad_judul_sum[$j]."</br>";
}


//Step 3 hasil cosine
$cosine_judul=array();
for( $j = 0 ; $j < $jml_data ; $j++) {
    set_time_limit(0);
	$cosine_judul[$j]=($skalar_judul_sum[$j]/($kuad_judul_sum[$jml_data]*$kuad_judul_sum[$j]));
    //echo $cosine_judul[$j]." "."</br>";
	}

/** jumlahkan hasil cosine */
$cosine_all=array();
for( $j = 0 ; $j < $jml_data ; $j++) {
    set_time_limit(0);
	$cosine_all[$j]=$cosine_isi[$j]+$cosine_judul[$j];
    //echo $j." ".$cosine_all[$j]."</br>";
	}

//sorting hasil cosine

$cos_tmp=array();
for( $j = 0 ; $j < $jml_data ; $j++) {
    $cos_tmp[$j]=$cosine_all[$j];
    }

$sor_cos=array();
for( $j = 0 ; $j < $jml_data ; $j++) {
    set_time_limit(0);
    $sor_cos[$j]=max($cos_tmp);
    $index_max=array_search($sor_cos[$j],$cos_tmp);
    unset ($cos_tmp[$index_max]);
    }
    
//echo "</br>";
// for( $j = 0 ; $j < $jml_data ; $j++) {
//    echo $sor_cos[$j]."</br>";
//    }
////    


/** ----------------------------- hasil akhir gabungkan -----------------------------*/

$hasil_akhir=array();

//masukkan nilai cosine
for( $j = 0 ; $j < $jml_data ; $j++) {
    set_time_limit(0);
	$hasil_akhir[$j][0]=$cosine_all[$j];
    }

//masukkan nilai klasifikasi
$j=0;
while (($line = fgets($fSumberHasil)) !== false) {
//echo $line."</br>";
$hasil_akhir[$j][1]=$line;
$j++;
}

//masukkan isi
$j=0;
while (($line = fgets($fSumberAsli)) !== false) {
//echo $line."</br>";
$hasil_akhir[$j][2]=$line;
$j++;
}

//masukkan judul
$j=0;
while (($line = fgets($fSumberJudulAsli)) !== false) {
//echo $line."</br>";
$hasil_akhir[$j][3]=$line;
$j++;
}


//masukkan kelas test
$j=0;
while (($line = fgets($fSumberTest)) !== false) {
//echo $line."</br>";
$hasil_akhir[$j][4]=$line;
$j++;
}


$count_akurasi=0;
for( $j = 0 ; $j < $jml_data ; $j++) {
    if ((($hasil_akhir[$j][4]==+1) and  ($hasil_akhir[$j][1]>0)) or (($hasil_akhir[$j][4]==-1) and  ($hasil_akhir[$j][1]<0))) {
    $count_akurasi++;
    }  
    }

//Tampilkan Hasil Akhir 
$result=array();
for( $j = 0 ; $j < $jml_data ; $j++) {
    $hit=0;
    for( $i = 0 ; $i < $jml_data ; $i++) {
        if(($sor_cos[$j]==$hasil_akhir[$i][0]) AND ($hit!=1))
        {          
        $result[$j][0]=$hasil_akhir[$i][0];    
        $result[$j][1]=$hasil_akhir[$i][1];
        $result[$j][2]=$hasil_akhir[$i][2];
        $result[$j][3]=$hasil_akhir[$i][3];
        $result[$j][4]=$hasil_akhir[$i][4];
        
        $hasil_akhir[$i][0]=0;
        $hit=1;
        }
        
    }
}


//for( $j = 0 ; $j < $jml_data ; $j++) {
//    for ($i =0; $i<=4; $i++){
//   //  if  (($result[$j][1]>0) and ($result[$j][0]>0)){       
//        echo $result[$j][$i]." | ";
//        }
// //   }
//    echo"</br>";
//    }

//hitung jumlah dokumen relevan yang seharusnya
$rel_all=0;
for( $j = 0 ; $j < $jml_data ; $j++) {
    if (($result[$j][0]>0) and ($result[$j][4]==+1))
        {
        $rel_all++;
        }
    }
//echo $rel_all;

//hitung jumlah dokumen relevan yang teretrieve
$ret_rel=0;
for( $j = 0 ; $j < $jml_data ; $j++) {
    if (($result[$j][0]>0) and ($result[$j][4]==+1) and ($result[$j][1]>0))
        {
        $ret_rel++;
        }
    }
//echo $ret_rel;

//hitung jumlah dokumen yang teretrieve
$ret_doc=0;
for( $j = 0 ; $j < $jml_data ; $j++) {
        if  (($result[$j][1]>0) and ($result[$j][0]>0))
        { 
        $ret_doc++;    
        }   
    }
//echo $ret_doc;
//
//
if ($cek_tf_in==0){
if ($cek_in=="") {
    echo "silahkan masukkan inputan dengan benar ";
    } else if ($cek_in!="") {
        echo "pencarian Anda tidak ditemukan ";
        }
} else if ($ret_doc==0){
    echo "pencarian Anda tidak ditemukan ";
    
    } else {
        echo "\n".'======================================================='."\n";
        echo "Akurasi   : ".round((($count_akurasi/$jml_data)*100),2)."% dari ".$jml_data." data testing"."\n";
        echo "Precision : ".$ret_rel."/".$ret_doc." = ".round(($ret_rel/$ret_doc),2)."\n";
        echo "Recall    : ".$ret_rel."/".$rel_all." = ".round(($ret_rel/$rel_all),2)."\n";
        echo '======================================================='."\n";
}

       
    
//if (($cek_in!="") and ($cek_tf_in==0)) {
//    
//    } 
//

$x=1;
for( $j = 0 ; $j < $jml_data ; $j++) {
        if  (($result[$j][1]>0) and ($result[$j][0]>0))
        {    
        echo "\n".'-------------------------------------------------------'."\n";    
        echo "Peringkat $x"."\n";    
        echo "Score : ". round($result[$j][0],2)."\n";
        echo "Judul : ". $result[$j][3];
        echo "Opini : ". $result[$j][2]."\n\n\n";
        echo '-------------------------------------------------------'."\n";
		$x++;
        }

}

//tutup file    
fclose($fSumber);
fclose($fSumberJudul);
fclose($fSumberHasil);
fclose($fSumberAsli);
fclose($fSumberTest);
fclose($fSumberJudulAsli);

?>