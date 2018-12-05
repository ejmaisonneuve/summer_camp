<?php

$text = "<html><head><title>Contact Form</title></head><body><table border=1>";

foreach($_POST as $name => $value) {
  if(strlen($name) > 1) {
    $array = explode("_", $name);
    for ($i = 0; $i < sizeof($array); $i++) {
      $array[$i] = ucfirst($array[$i]);
    }
    $name = implode(" ", $array);
    $name = str_replace("_", " ", $name);
    $text .= "<tr><td>$name:</td> <td>$value</td></tr>\n";
  }
}
$text .= "</table></body></html>";

$name = $_POST['name'];

$to = "ejmaisonneuve@gmail.com";
$subject = "SITP Contact from $name";
$headers = "ME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: <ejmaisonneuve@utexas.edu>' . "\r\n";
if(mail($to, $subject, wordwrap($text, 70), $headers)) {
  $response = "Your message has been sent to us! Expect to hear back from us within the next 24 hours. Thank you!";
}
else {
  $response = "Their was an error sending your message, please try again.";
}

print <<<HTML
<!DOCTYPE html>

<html lang = "en">

<head>
  <meta charset = "UTF-8" />
  <title>School in the Pines Summer Camp</title>
  <link rel = "stylesheet" title = "basic style" type = "text/css" href = "./home.css" media = "all" />
  <link rel = "stylesheet" title = "basic style" type = "text/css" href = "./contact_page.css" media = "all" />
  <link href="https://fonts.googleapis.com/css?family=Signika:400,700" rel="stylesheet">
</head>

<body>


  <a href="./home.html">
  <img id="logo" src = "./logo.png" alt = "sitp logo"/>
  </a>

  <div class="navbar">
    <a class="no-drop" href="./about.html">About Us</a>
    <div class="dropdown">
    <button class="dropbtn">Nutrition
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
      <a href="./lunch_menu.html">Lunch Menu</a>
      <a href="./snacks.html">Snacks</a>
      <a class="bottom-dropdown" href="./allergies.html">Allergy Information</a>
    </div>
  </div>
  <div class="dropdown">
    <button class="dropbtn">Policies/Fees
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
      <a href="./apps_fees.html">Applications & Fees</a>
      <a class="bottom-dropdown" href="./field_trips.html">Field Trip Waivers</a>
    </div>
  </div>
  <a class="no-drop" href="./contact_page.html">Contact Us</a>
  <a class="no-drop" href="./login.html">Log In</a>
  </div>


  <div class = "titles">
    <h1 id="contact-title">Contact Us</h1>
    <h2 id ="sub-title"</h2>
  </div>

  <p id='submitted_text'>$response</p>

</body>
</html>
HTML;

?>
