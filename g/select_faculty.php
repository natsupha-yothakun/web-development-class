<mata charset="utf-8">
<h1>ณัฐสุภา โยธากุล(บลู)</h1><hr>
<a href="insert_faculty.php">ดูข้อมูลคณะ</a>
<hr>

<?php
include("connectdb.php");

$sql = "SELECT * FROM faculty";
$rs = mysqli_query($conn,$sql);

while($data = mysqli_fetch_array($rs)){ 
    echo $data['f_id']. "<br>";
	 echo $data['f_name']. "<br>";
	 echo "<a href='delete_faculty.php?fid={$data['f_id']}' onClick='return confirm(\"ยืนยันการลบ?\");'>ลบ</a><hr>";
}
?>