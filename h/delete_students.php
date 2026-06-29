<meta charset="utf-8">
<?php
	if(isset($_GET['id'])){
	include("connectdb.php");
	$sql = "DELETE FROM students WHERE s_id = '{$_GET['id']}' ";
	mysqli_query($conn, $sql) or die ('ลบข้อมูลบ่าได้');
	
	echo "<script>";
	echo "window.location='select_students.php';";
	echo "</script>";
	
	}


?>