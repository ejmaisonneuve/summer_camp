<?php
session_start();

if (!isset($_SESSION["selectedPage"])) {
    $_SESSION["selectedPage"] = 0;
}

updateChildren();
$username = $_SESSION["username"];
if (isset($_POST["logoutBtn"])) {
    onLogout();
} elseif (isset($_POST["childrenBtn"])) {
    unset($_SESSION["selectedChild"]);
    $_SESSION["selectedPage"] = 0;
} elseif (isset($_POST["accountSettingsBtn"])) {
    $_SESSION["selectedPage"] = 1;
} elseif (isset($_POST["pickupBtn"])) {
    $_SESSION["selectedPage"] = 2;
}
if (isset($username)) {
    showDashboard();
} else {
    onInvalidAccess();
}

function getPickupPage() {
    $pickups = getPickups($_SESSION["username"]);
    $pickupText = "";
    if (empty($pickups)) {
        $pickupText = "It seems like we don't have anyone approved to pick your children up";
    } else {
        $pickupText = "<table class='childTable'><tr><td>Name</td><td>Relationship</td><td>Phone</td><td>Address</td></tr>";
        foreach ($pickups as $key => $value) {
            $relationship = $value[0];
            $name = $value[1];
            $phone = $value[2];
            $address = $value[3];
            $pickupText = $pickupText . "<tr><td>$name</td><td>$relationship</td><td>$phone</td><td>$address</td></tr>";
        }
        $pickupText = $pickupText . "</table>";
    }
    return <<<PAGE
<h4>Your approved pickups</h4>
$pickupText
PAGE;
}

function onAccountUpdate($username, $password) {
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

    $username = trim($username);

    if ($password != "") {

        $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

        $stmt = mysqli_prepare ($connect, "UPDATE users SET encrypted_password=? WHERE username=?");
        mysqli_stmt_bind_param ($stmt, 'ss', $hash, $_SESSION["username"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    if ($username != "") {
        $stmt = mysqli_prepare ($connect, "UPDATE users SET username=? WHERE username=?");
        mysqli_stmt_bind_param ($stmt, 'ss', $username, $_SESSION["username"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $stmt = mysqli_prepare ($connect, "UPDATE behavior SET username=? WHERE username=?");
        mysqli_stmt_bind_param ($stmt, 'ss', $username, $_SESSION["username"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $stmt = mysqli_prepare ($connect, "UPDATE children SET username=? WHERE username=?");
        mysqli_stmt_bind_param ($stmt, 'ss', $username, $_SESSION["username"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $stmt = mysqli_prepare ($connect, "UPDATE pickup SET username=? WHERE username=?");
        mysqli_stmt_bind_param ($stmt, 'ss', $username, $_SESSION["username"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $stmt = mysqli_prepare ($connect, "UPDATE parents SET username=? WHERE username=?");
        mysqli_stmt_bind_param ($stmt, 'ss', $username, $_SESSION["username"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION["username"] = $username;
    }

    $result -> free();
    mysqli_close($connect);


}

function updateChildren() {
    $host = "fall-2018.cs.utexas.edu";
    $user = "cs329e_mitra_eshresth";
    $pwd = "strife!Morgue6Tying";
    $dbs = "cs329e_mitra_eshresth";
    $port = "3306";
    $connect = mysqli_connect ($host, $user, $pwd, $dbs, $port);
    $username = $_SESSION["username"];
    if (empty($connect)) {
        die("mysqli_connect failed: " . mysqli_connect_error());
    }
    $newArray = array();
    $table = "children";
    $result = mysqli_query($connect, "SELECT child_id, name from $table where username='$username'");
    while ($row = $result->fetch_row()) {
        $newArray[$row[0]] = $row[1];
    }
    $_SESSION["children"] = $newArray;
    mysqli_close($connect);
    $result->free();
}

function getPickups($username) {
    $host = "fall-2018.cs.utexas.edu";
    $user = "cs329e_mitra_eshresth";
    $pwd = "strife!Morgue6Tying";
    $dbs = "cs329e_mitra_eshresth";
    $port = "3306";
    $connect = mysqli_connect ($host, $user, $pwd, $dbs, $port);
    $username = $_SESSION["username"];
    if (empty($connect)) {
        die("mysqli_connect failed: " . mysqli_connect_error());
    }
    $newArray = array();
    $table = "pickup";
    $result = mysqli_query($connect, "SELECT relationship, name, phone, address from $table where username='$username'");
    while ($row = $result->fetch_row()) {
        $newArray[] = [$row[0], $row[1], $row[2], $row[3]];
    }
    mysqli_close($connect);
    $result->free();
    return $newArray;
}

function getBehaviorReports($child_id) {
    $host = "fall-2018.cs.utexas.edu";
    $user = "cs329e_mitra_eshresth";
    $pwd = "strife!Morgue6Tying";
    $dbs = "cs329e_mitra_eshresth";
    $port = "3306";
    $connect = mysqli_connect ($host, $user, $pwd, $dbs, $port);
    if (empty($connect)) {
        die("mysqli_connect failed: " . mysqli_connect_error());
    }
    $newArray = array();
    $table = "behavior";
    $result = mysqli_query($connect, "SELECT date, report from $table where child_id='$child_id'");
    while ($row = $result->fetch_row()) {
        $newArray[] = [$row[0], $row[1]];
    }
    mysqli_close($connect);
    $result->free();
    return $newArray;
}

function showRandomGreeting() {
    $greetings = array("What would you like to do today?", "Your kids are doing great!", "How's your day going?", "Have a great day!");
    $index = rand(0, 3);
    return $greetings[$index];
}

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

function getChildrenPage() {
$children = "";
foreach ($_SESSION["children"] as $key=>$value) {
    $niceKey = str_replace(".", "", $key);
    $children = $children . "<li><input type='submit' class='childBtn' name='childBtn-$niceKey' value='$value'></li>";
    if (isset($_POST["childBtn-$niceKey"])) {
        $_SESSION["selectedChild"] = $key;
        $behaviorReports = getBehaviorReports($key);
    }
}
if (isset($_SESSION["selectedChild"])) {
    $behavior = "";
    $childName = $_SESSION["children"][$_SESSION["selectedChild"]];
    if (empty($behaviorReports)) {
        $behavior = "It looks like " . $childName . " has been doing great!";
    } else {
        $behavior = "<table class='childTable'><tr><td>Date</td><td>Report</td></tr>";
        foreach ($behaviorReports as $key=>$value) {
            $date = $value[0];
            $report = $value[1];
            $behavior = $behavior . "<tr>";
            $behavior = $behavior . "<td>$date</td>";
            $behavior = $behavior . "<td>$report</td>";
            $behavior = $behavior . "</tr>";
        }
        $behavior = $behavior . "</table>";
    }
return <<<PAGE
    <h4>$childName</h4>
    <h5>Behavior</h5>
    $behavior
PAGE;
}

return <<<PAGE
<h4>My Children</h4>
Here are your enrolled children. Click on their names to see their information.
<ul>
$children
</ul>
PAGE;

}
function getAccountPage() {
    $notification = "";
    if (isset($_POST["saveChanges"])) {
        $notification = "<span class='notification'>We've updated your account for you.</span>";
        $newUsername = trim($_POST["changeUsername"]);
        $newPassword = $_POST["changePassword"];
        $verifyNewPassword = $_POST["changePasswordVerify"];
        $currentPassword = $_POST["currentPassword"];
        $validated = false;
        if (validateUser($_SESSION["username"], $currentPassword)) {
            if ($newPassword == $verifyNewPassword) {
                $validated = true;
            } else {
                $notification = "<span class='notification'>Hmm... make sure your new passwords match.</span>";
            }
        } else {
            $notification = "<span class='notification'>Hmm... make sure your current password is filled in correctly.</span>";
        }
        if ($validated) {
            onAccountUpdate($newUsername, $newPassword);
            $notification = "<span class='notification'>We've updated your account for you.</span>";
        }
    }

return <<<PAGE
<h4>My Account</h4>
$notification
<table class="accountTable">
<tr><td>New Username</td><td><input class="accountInput" type="text" name="changeUsername"></td></tr>
<tr><td>New Password</td><td><input class="accountInput" type="password" name="changePassword"></td></tr>
<tr><td>Verify New Password</td><td><input class="accountInput" type="password" name="changePasswordVerify"></td></tr>
<tr><td>Current Password</td><td><input class="accountInput" type="password" name="currentPassword"></td></td>
<tr><td><input class="accountButton" type="submit" name="saveChanges" value="Save Changes"></td><td><input class="accountButton" type="reset" name="Clear" value="Clear"></td></tr>
PAGE;
}

function showDashboard() {
    $script = $_SERVER["PHP_SELF"];
    $username = $_SESSION["username"];
    $greeting = showRandomGreeting();
    $selectedPage = $_SESSION["selectedPage"];
    $childrenClass = "";
    $accountClass = "";
    $pickupClass = "";
    $information = "";
    if ($selectedPage == 0) {
        $childrenClass = "selected";
        $information = getChildrenPage();
    } elseif ($selectedPage == 1) {
        $accountClass = "selected";
        $information = getAccountPage();
    } elseif ($selectedPage == 2) {
        $pickupClass = "selected";
        $information = getPickupPage();
    } else {
        $_SESSION["selectedPage"] = 0;
        showDashboard();
    }
  print <<<DASHBOARD
<html>
<head>
<title>Dashboard</title>
<link href="https://fonts.googleapis.com/css?family=Signika:400,700" rel="stylesheet">
<link rel = "stylesheet" title = "basic style" type = "text/css" href = "./home.css" media = "all" />
<link rel = "stylesheet" type = "text/css" href = "./dashboard.css" media = "all" />
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
<br>
<br>
<br>
<br>
<br>
<h1 id="top">My Dashboard</h1>
<div class="content">
<h2 id="greeting">Hi, $username</h2>
<h3 id="greeting-subtitle">$greeting</h3>
<form id="dashboardForm" action="$script" method="post">
<div class="options">
<input class="optionBtn $childrenClass" type="submit" name="childrenBtn" id="childrenBtn" value="My Children" ><br>
<input class="optionBtn $accountClass" type="submit" name="accountSettingsBtn" id="accountSettingsBtn" value="My Account"><br>
<input class="optionBtn $pickupClass" type="submit" name="pickupBtn" id="pickupBtn" value="Approved Pickups"><br>
<input class="optionBtn" type="submit" name="logoutBtn" id="logoutBtn" value="Logout">
</div>
<div class="information">
$information
</div>
</form>

</div>
</body>
</html>
DASHBOARD;
}

function onLogout() {
    session_destroy();
    header("Location: login.php"); /* Redirect browser */
    exit();
}
function validateUser($username, $password) {
    $hash = getPasswordHash($username);

    return password_verify($password, $hash);
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
