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
    $host = "localhost";
    $user = "root";
    $pwd = "root";
    $dbs = "summer_camp";
    $port = "8889";
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