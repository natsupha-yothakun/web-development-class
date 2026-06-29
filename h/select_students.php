<mata charset="utf-8">
<h1>ณัฐสุภา โยธากุล(บลู)</h1><hr>
<a href="insert_students.php">ดูข้อมูลคณะ</a>
<hr>

<form method="post" action="">
    คำค้น <input type="text" name="k" autofocus>
    <button type="submit" name="Submit">ค้นหา</button>
</form>

<hr>

<?php
include("connectdb.php");
$k = @$_POST['k'];
$sql = "SELECT * FROM students AS s
INNER JOIN faculty AS f
ON s.f_id = f.f_id
WHERE  s.s_name LIKE '%{$k}%' || s.s_address LIKE '%{$k}%' || f.f_name LIKE '%{$k}%'
ORDER BY s_id ASC
";
$rs = mysqli_query($conn,$sql);

while ($data = mysqli_fetch_array($rs)) {
    $y = substr($data['s_id'],0,2) ;
    echo "<img src='http://202.28.32.211/picture/student/{$y}/{$data['s_id']}.jpg' width='250'><br>";
    echo $data['s_id']. "<br>";
    echo $data['s_name']. "<br>";
    echo $data['s_address']. "<br>";
    echo $data['s_gpax']. "<br>";
    echo $data['f_name']. "<br>";
    echo "<a href='update_student.php?id={$data['s_id']}'>แก้ไข</a> |";
    echo "<a href='delete_student.php?id={$data['s_id']}' onClick='return confirm(\"ยืนยันการลบ?\");'>ลบ</a> <hr>";
}

mysqli_close($conn);
?>