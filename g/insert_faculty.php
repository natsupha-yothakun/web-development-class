<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title> ณัฐสุภา โยธากุล(บลู)</title>
</head>

<body>
<h1>เพิ่มคณะ => ณัฐสุภา โยธากุล(บลู)</h1>

<a href="select_faculty.php">ดูข้อมูลคณะ</a>
<hr>
<form method="post" action="">
	ชื่อคณะ <input type ="text" name="fname"autocus required>
    <button type="submit" name="Submit">บันทึก</button>
    </form>

<?php
if(isset($_POST['Submit'])){
	include("connectdb.php");
	$fname = $_POST['fname'];
	$sql = "INSERT INTO faculty VALUES (NULL,'{$fname}');";
	//var_dump($sql); exit;
	mysqli_query ($conn, $sql) or die ("insert erorr ");
	
	echo "<script>";
	echo "alert('เพิ่มข้อมูลสำเร็จ');";
	echo "window.location='select_faculty.php';";
	echo "</script>";
	
}

?>




</body>
</html>
