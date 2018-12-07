<?php

//connect to database
$host = "fall-2018.cs.utexas.edu";
$user = "cs329e_mitra_eshresth";
$pwd = "strife!Morgue6Tying";
$dbs = "cs329e_mitra_eshresth";
$port = "3306";
$connect = mysqli_connect ($host, $user, $pwd, $dbs, $port);
if (empty($connect)) {
  die("mysqli_connect failed: " . mysqli_connect_error());
}


//set values from POST
$uname = $_POST["userName"];
$upword = $_POST["pass"];
$phone = $_POST["home_phone"];
$address = $_POST['mailing_address'];
$subdivision = $_POST['subdivision'];

//encrypt password
$hash = password_hash($upword, PASSWORD_DEFAULT, ['cost' => 12]);

//show table
$table = "users";

//insert user info
$stmt = mysqli_prepare ($connect, "INSERT INTO $table VALUES (?, ?, ?, ?, ?)");
mysqli_stmt_bind_param ($stmt, 'sssss', $uname, $hash, $phone, $address, $subdivision);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

//create entry in children table
$table = 'children';
$id = uniqid("", true);
$child_name = $_POST['child_name'];
$nickname = $_POST['nickname'];
$sex = $_POST['sex'];
$dob = $_POST['date_of_birth'];
$dob = explode("/", $dob);
$dob = $dob[2] . "-" . $dob[1] . "-" . $dob[0];
$grade = $_POST['current_school_grade'];

$stmt = mysqli_prepare ($connect, "INSERT INTO $table VALUES (?, ?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param ($stmt, 'sssssss', $uname, $id, $child_name, $nickname, $sex, $dob, $grade);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

//add father
$table = 'parents';
$name = $_POST['father_name'];
$relationship = 'Father';
$occupation = $_POST['father_occupation'];
$employer = $_POST['father_employed_by'];
$business_phone = $_POST['father_business_phone'];
$cell_phone = $_POST['father_cell_phone'];
$email = $_POST['father_email'];

$stmt = mysqli_prepare ($connect, "INSERT INTO $table VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param ($stmt, 'ssssssss', $uname, $name, $relationship, $occupation, $employer, $business_phone, $cell_phone, $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

//add mother
$table = 'parents';
$name = $_POST['mother_name'];
$relationship = 'Mother';
$occupation = $_POST['mother_occupation'];
$employer = $_POST['mother_employed_by'];
$business_phone = $_POST['mother_business_phone'];
$cell_phone = $_POST['mother_cell_phone'];
$email = $_POST['mother_email'];

$stmt = mysqli_prepare ($connect, "INSERT INTO $table VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param ($stmt, 'ssssssss', $uname, $name, $relationship, $occupation, $employer, $business_phone, $cell_phone, $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

//add pickup information
$name_array = Array();
$relation_array = Array();
$phone_array = Array();
$address_array = Array();
foreach($_POST as $name => $value) {
  if(strpos($name, "pickup") !== false) {
    if(strpos($name, "name") !== false) { 
      array_push($name_array, $value);
    }    
    elseif(strpos($name, "relationship") !== false) {    
      array_push($relation_array, $value);
    } 
    elseif(strpos($name, "phone") !== false) {     
      array_push($phone_array, $value);
    }
    elseif(strpos($name, "address") !== false) {     
      array_push($address_array, $value);
    }
  }
}

$table = "pickup";
for($i = 0; $i < sizeof($name_array); $i++) {
  $name = $name_array[$i];
  $relationship = $relation_array[$i];
  $phone = $phone_array[$i];
  $address = $address_array[$i];
  
  $stmt = mysqli_prepare ($connect, "INSERT INTO $table VALUES (?, ?, ?, ?, ?)");
  mysqli_stmt_bind_param ($stmt, 'sssss', $uname, $name, $relationship, $phone, $address);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>


<html lang = "en">

<head>
  <meta charset = "UTF-8" />
  <title>School in the Pines Summer Camp</title>
  <link rel = "stylesheet" title = "basic style" type = "text/css" href = "./navbar.css" media = "all" />
  <link rel = "stylesheet" title = "basic style" type = "text/css" href = "./application.css" media = "all" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Signika:400,700" rel="stylesheet">
</head>

<body>


  <a href="./home.html">
  <img id="logo" src = "./logo.png" alt = "sitp logo"/>
  </a>

  <div class="navbar">
    <a class="no-drop" href="./about.html">About Us</a>
    <a class="no-drop" href="./nutrition.html">Nutrition</a>
  <div class="dropdown">
    <button class="dropbtn">Forms
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
      <a href="./application.html">Application</a>
      <a href="./policies_fees.pdf">Policies & Fees</a>
      <a class="bottom-dropdown" href="./field_trips.html">Field Trip Waivers</a>
    </div>
  </div>
  <a class="no-drop" href="./contact_page.html">Contact Us</a>
  <a class="no-drop" href="./login.php">Log In</a>
  </div>

  <h1 id = "top">School in the Pines Summer Camp 2019 Schooler Application</h1>
  <p id='alert'>Thank you for filling out your application, we will be in contact with you shortly!</p>
</body>
</html>


