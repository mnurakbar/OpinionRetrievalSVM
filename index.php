<?php
$inputan="";
if (isset($_GET['Input'])){
    $inputan = $_GET['search'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>SVM Opinion Retrieval</title>
		<link rel="stylesheet" type="text/css" href="style.css" media="screen">

		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="ie.css" media="screen">
		<![endif]-->
		<link rel="stylesheet" type="text/css" href="lightbox.css" media="screen">
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.lightbox-0.5.pack.js"></script>
		<script type="text/javascript" src="js/cufon-yui.js"></script>
		<script type="text/javascript" src="js/Desyrel_400.font.js"></script>
		
		<!-- Cufon text replacement -->
		<script type="text/javascript">
			$(document).ready(function(){
				Cufon.replace('#about .right ul li');
				Cufon.replace('h2', { textShadow: '0 2px rgba(0, 0, 0, 0.15)' });
				Cufon.replace('#social .right a', {hover:true});
				Cufon.replace('#work .left h3', {hover:true});
				Cufon.replace('h3', { textShadow: '0 2px rgba(0, 0, 0, 0.15)' });
				Cufon.replace('#contactinfo'), {hover:true};
			});
		</script>
		
		<!-- jQuery lightbox -->
		<script type="text/javascript">
			$(document).ready(function(){
				$('#work .right a').lightBox();
			});
		</script>
		
		<!-- Navigation and Content effects -->
		<script type="text/javascript">
			$(document).ready(function(){
				$('.page').hide();
				$('#navigation li:first').addClass('active').show();
				$('.page:first').show();
				
				$('#navigation li').click(function(){
					$('#navigation li').removeClass('active');
					$(this).addClass('active');
					
					$('.page').hide();
					var activeTab = $(this).find('a').attr('href');
					$(activeTab).fadeIn('slow');
					return false;
				});
			});
		</script>

	</head>
	<body>
		<div id="pagewrap">
        	<div id="logo"></div>
			
			<!-- Site header and navigation -->
			<div id="header">
				<ul id="navigation">
					<li><a class="about" href="#about"><img src="images/icon-search.png" alt="Search"></a></li>
					<li><a class="contact" href="#contact"><img src="images/icon-about.png" alt="Classification"></a></li>
				</ul>
			</div>
			
			<!-- Site content -->



			<div id="content">
				<div id="about" class="page">
                	<div id="searching">
                   	<form id="searchbox" action="index.php" method="GET" name="input">
    				<input id="search" type="text" placeholder="Type here" name="search">   
    				<input id="submit" type="submit" value="Cari" name="Input">
					</form>
                    </div>
					<div class="left">
						<img src="images/author-photo.png" alt="photo">
					</div>
					<div class="right">
						<h2>Hasil pencarian opini Anda :</h2>	
                        <textarea><?php include('masukan.php'); ?></textarea>
					</div>
				</div>
	
			<div id="contact" class="page">
					<div class="left">
						<img src="images/me.png" alt="photo">
					</div>
					<div class="right">
                    	<h2>About : </h2>
					       <h3><font color="red">Opinion Retrieval</font> with <font color="red">SVM </font>merupakan aplikasi pencarian informasi berupa opini publik terhadap suatu hal, mengingat opini dapat menjadi sebuah informasi yang sangat berharga. </h3> <h3>Digunakan <font color="red">Support Vector Machine</font> sebagai teknik Machine Learning untuk mendeteksi Opini.</h3>
						
					</div>
				</div>
			</div>

			<div id="footer">
				<p>&copy; Muhammad Nur Akbar | 113090200  </a></p>
			</div>
		</div>
	</body>
</html>