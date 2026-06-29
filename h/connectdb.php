<?php
$host = "localhost" ;
$user = "root";
$pwd = "";
$db = "msu";
$conn = mysqli_connect($host,$user,$pwd,$db);
mysqli_query($conn,"set names utf8");
?>