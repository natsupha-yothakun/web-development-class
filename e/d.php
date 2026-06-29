<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ณัฐสุภา โยธากุล (บลู)</title>
</head>


<body>
<h1>ณัฐสุภา โยธากุล (บลู) </h1><hr>
<form method="post" action="">
	รหัสนิสิต <input type="text" name="a" autofocus required >
	<button type="Submit" name="Submit">OK</button>
</form><hr>

<?php

if(isset($_POST['Submit'])){
	$a= $_POST['a']; //รหัสนิสิต
	$y = substr($a,0,2);	//2ตัวแรกรหัสนิสิต
    echo "<img src = 'http://202.28.32.211/picture/student/{$y}/{$a}.jpg' width='600''>";
	 }
		 

		

?>

</body>
</html>
