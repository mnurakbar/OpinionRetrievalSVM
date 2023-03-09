<?php
include 'svm.php';
$svm = new PHPSVM();
$svm->test('data/test.dat', 'model.svm', 'output.dat'); 
?>