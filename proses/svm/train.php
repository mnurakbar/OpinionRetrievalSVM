<?php
include 'svm.php';
$svm = new PHPSVM();
$svm->train('data/train.dat', 'model.svm');
?>