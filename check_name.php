<?php

$host = "fall-2018.cs.utexas.edu";
$user = "cs329e_mitra_eshresth";
$pwd = "strife!Morgue6Tying";
$dbs = "cs329e_mitra_eshresth";
$port = "3306";
$connect = mysqli_connect ($host, $user, $pwd, $dbs, $port);
if (empty($connect)) {
  die("mysqli_connect failed: " . mysqli_connect_error());
}

$uname = $_GET['userName'];
$uname = trim($uname); 

//show table
$table = "users";
$result = mysqli_query($connect, "SELECT * from $table where username=\"$uname\"");
$count = 0;
while ($row = $result->fetch_row()) {
  $count++;
}
$result->free();

if($count >= 1) {
  $response = "true";
}
else {
  $response = "false";
}
echo $response;
?>
