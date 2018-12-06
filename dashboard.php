<?php
session_start();
$username = $_SESSION["username"];
if (isset($_POST["logoutBtn"])) {
    onLogout();
}
if (isset($username)) {
    showDashboard();
    createUser();
} else {
    onInvalidAccess();
}



// function createUser() {
//     $username = "username";
//     $password = "password";
//     $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    
//     $host = "localhost";
//     $user = "root";
//     $pwd = "root";
//     $dbs = "summer_camp";
//     $port = "8889";
//     $connect = mysqli_connect ($host, $user, $pwd, $dbs, $port);

//     if (empty($connect))
//     {
//       die("mysqli_connect failed: " . mysqli_connect_error());
//     }

//     $table = "users";

//     $stmt = mysqli_prepare ($connect, "INSERT INTO $table VALUES (?, ?)");
//     mysqli_stmt_bind_param ($stmt, 'ss', $username, $hash);
//     mysqli_stmt_execute($stmt);
//     mysqli_stmt_close($stmt);
//     mysqli_close($connect);

// }

function onInvalidAccess() {
    $script = $_SERVER["PHP_SELF"];
    print <<<INVALID
<html>
<head>
</head>
<body>
There seems to be something wrong. Click <a href="login.php">here</a> to go back to the login screen.
</body>
</html>
INVALID;
}

function showDashboard() {
    $script = $_SERVER["PHP_SELF"];
    $username = $_SESSION["username"];
  print <<<DASHBOARD
<html>
<head>
<title>Dashboard</title>
</head>
<body>
<h1>Hi, $username</h1>
<form id="dashboardForm" action="$script" method="post">
<input type="submit" name="logoutBtn" id="logoutBtn" value="Logout">
</form>
</body>
</html>
DASHBOARD;
}

function onLogout() {
    session_destroy();
    header("Location: login.php"); /* Redirect browser */
    exit();
}
?>