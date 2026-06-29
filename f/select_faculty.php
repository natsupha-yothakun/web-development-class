<mata charset="utf-8">
<h1>ณัฐสุภา โยธากุล(บลู)</h1><hr>

<?php
include("connectdb.php");

$sql = "SELECT * FROM faculty";
$rs = mysqli_query($conn,$sql);

while($data = mysqli_fetch_array($rs)){ 
    echo $data['f_id']. "<br>";
	 echo $data['f_name']. "<hr>";
}
?>