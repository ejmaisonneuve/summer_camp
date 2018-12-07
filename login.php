<?php
$username = "";

if (isset($_POST["userName"]) && isset($_POST["pass"])) {
  $username = $_POST["userName"];
  $password = $_POST["pass"];
  if (validateUser($username, $password)) {
    session_start();
    $_SESSION["username"] = $username;
    showDashboard();
  } else {
    echo "incorrect username or password";
  }
} else {

  if (isset($_COOKIE["username"])) {
    $username = $_COOKIE["username"];
  }

  if (isset($_POST["rememberMe"])) {
    $username = $_POST["userName"];
    setcookie("username", $username);
  }

  showLoginPage($username);
}

function showDashboard() {
  header("Location: dashboard.php"); /* Redirect browser */
  exit();
}

function showLoginPage($username) {
  $script = $_SERVER["PHP_SELF"];
print <<<LOGIN
<html lang = "en">
<head>
  <meta charset = "UTF-8" />
  <title>Login</title>
  <link rel = "stylesheet" title = "basic style" type = "text/css" href = "./home.css" media = "all" />
  <link rel = "stylesheet" title = "basic style" type = "text/css" href = "./login.css" media = "all" />
  <link href="https://fonts.googleapis.com/css?family=Signika:400,700" rel="stylesheet">
</head>
<body>


  <a href='./home.html'><img id="logo" src = "./logo.png" alt = "sitp logo"/>
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

 <h1>Login</h1>
  <form id = "textForm" method = "post" action = "$script" >
    <ul>
      <li><input class = "inputs" type = "text" name = "userName" id = "userName" placeholder="Username" value="$username" required/></li>
      <li><input class = "inputs" type = "password" name = "pass" placeholder="Password" required/></li>
    </ul>
      <input type="checkbox" name="rememberMe" id="rememberMe">Remember me<br>
    <ul id = "buttons">
      <li class = "button_li"><input class = "button" id = "submit" type = "submit" value = "Login" /></li>
      <li class = "button_li"><input class = "button" id = "clear" type = "reset" value = "Clear" /></li>
    </ul>
  </form>
  </body>
LOGIN;
}

function validateUser($username, $password) {
    $hash = getPasswordHash($username);

    if (password_verify($password, $hash)) {
        return true;
    } else {
        return false;
    }
}

function getPasswordHash($username) {
    $host = "fall-2018.cs.utexas.edu";
    $user = "cs329e_mitra_eshresth";
    $pwd = "strife!Morgue6Tying";
    $dbs = "cs329e_mitra_eshresth";
    $port = "3306";
    $connect = mysqli_connect ($host, $user, $pwd, $dbs, $port);

    if (empty($connect))
    {
      die("mysqli_connect failed: " . mysqli_connect_error());
    }

    $table = "users";
    $result = mysqli_query($connect, "SELECT encrypted_password from $table WHERE username = '$username'");

    $hash = "";
    while ($row = $result->fetch_row()) {
        $hash = $row[0];
    }
    $result -> free();
    mysqli_close($connect);
    return $hash;
}
?>