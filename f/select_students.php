<meta charset="utf-8">
<h1>ณัฐสุภา โยธากุล(บลู)</h1><hr>

<form method = "post" action="">
คำค้น <input type="text" name="k" autofocus required>
<button type="submit" name= "Submit">OK</button>
</form>

<?php
include("connectdb.php");

$sql = "SELECT * FROM students AS s
LEFT JOIN faculty AS f
ON s.f_id = f.f_id
WHERE (s.s_name LIKE '%" .@$_POST['k']."%' OR s.s_address LIKE '%" .@$_POST['k']."%')
ORDER BY s.s_id ASC ";
$rs = mysqli_query($conn,$sql);

while ($data = mysqli_fetch_array($rs)) {
    echo "รหัสนิสิต:". $data['s_id']. "<br>";
    echo "ชื่อ:". $data['s_name']. "<br>";
    echo "ที่อยู่:". $data['s_address']. "<br>";
    echo "GPAX:". $data['s_gpax']. "<br>";
    echo "คณะ:". $data['f_name']. "<hr>";
    
}
?>