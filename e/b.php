<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ณัฐสุภา โยธากุล (บลู)</title>
</head>


<body>
<h1>ณัฐสุภา โยธากุล (บลู) </h1><hr>
<form method="post" action="">
	กรอกข้อมูล <input type="text" name="a" autofocus required >
	<button type="Submit" name="Submit">OK</button>
</form><hr>

<?php

if(isset($_POST['Submit'])){
	$a= $_POST['a'];
    if ($a ==1){
		echo "เพศชาย";
	} 
	 else if ($a==2){
		echo "เพศหญิง";
	 } else {
		 echo "กรอก 1 หรือ 2 เท่านั้น ";
	 }
		 
}
		

?>

</body>
</html>
