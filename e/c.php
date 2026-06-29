<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ณัฐสุภา โยธากุล (บลู)</title>
</head>


<body>
<h1>ณัฐสุภา โยธากุล (บลู) </h1><hr>
<form method="post" action="">
	กรอกเลยตรงนี้ <input type="text" name="a" autofocus required >
	<button type="Submit" name="Submit">OK</button>
</form><hr>

<?php

if(isset($_POST['Submit'])){
	$a= $_POST['a'];
    if (($a =="dog") or ($a == "หมา") ||  ($a == "สุนัข")) {
		echo '<img src="1.jpg" width="400"> ';
	} 
	else if (($a=="cat")or ($a == "แมว") ||  ($a == "แงว")){
		echo  '<img src="2.jpg" width="400"> ';
		
	}else if (($a=="tiger")or ($a == "เสือ") ||  ($a == "มาร์ค")){
		echo  '<img src="3.jpg" width="400"> ';
		
		}else if (($a=="บลู")or ($a == "blue") ||  ($a == "มาร์คคึ")){
		echo  '<img src="28.jpg" width="400"> ';
		
	 } else {
		  echo '<img src="4.jpg" width="400"> ';
	 }
		
	 }
		 

		

?>

</body>
</html>
