<?php

$app_text = "<html><head><title>Application</title></head><body><table border=1>";

foreach($_POST as $name => $value) {
  if(strlen($name) > 1) {
    $array = explode("_", $name);
    for ($i = 0; $i < sizeof($array); $i++) {
      $array[$i] = ucfirst($array[$i]);
    }
    $name = implode(" ", $array);
    $name = str_replace("_", " ", $name);
    $app_text .= "<tr><td>$name:</td> <td>$value</td></tr>\n";
  }
}
$app_text .= "</table></body></html>";

$child_name = $_POST['child_name'];
$father_email = $_POST['father_email'];
$mother_email = $_POST['mother_email'];

$to_sitp = "ejmaisonneuve@gmail.com";
$subject_sitp = "SITP Application for $child_name";
$headers = "ME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: <ejmaisonneuve@utexas.edu>' . "\r\n";
mail($to_sitp, $subject_sitp, wordwrap($app_text, 70), $headers);

if(strlen($father_email) > 0 && strlen($mother_email) > 0) { 
  $to_parents = "$father_email, $mother_email"; 
}
elseif(strlen($father_email) > 0 ) { 
  $to_parents = $father_email; 
}
else {
  $to_parents = $mother_email; 
}
$subject_parents = "Thank you for your Application!";
$parent_text = "Thank you for your application! A copy of your application is provided below and we will be in contact with you shortly!" . $app_text;
mail($to_parents, $subject_parents, wordwrap($parent_text, 70), $headers);

print <<<HTML
<!DOCTYPE html>

<html lang = "en">
<head>
  <title>Account Sign-Up</title>
  <link rel = "stylesheet" title = "basic style" type = "text/css" href = "./account_signup.css" media = "all" />
  <script src = "./account_signup.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>

<body>

  <h1>Thank You for Applying!</h1>
  <p>Now, please sign up for an account so we can keep you updated on your application process</p>

  <form id = "textForm" method = "post" action = "./redirect.html" onsubmit = "return validate();" >
    <ul>
      <li><input class = "inputs" type = "text" name = "userName" placeholder="Family Last Name (pick one if multiple)"/></li>
      <li><input class = "inputs" type = "password" name = "pass" placeholder="Password"/></li>
      <li><input class = "inputs" type = "password" name = "rep_pass" placeholder="Retype your Password"/></li>
    </ul>
    <ul id = "buttons">
      <li class = "button_li"><input class = "button" id = "submit" type = "submit" value = "Register" /></li>
      <li class = "button_li"><input class = "button" id = "clear" type = "reset" value = "Clear" /></li>
    </ul>

  </form>

</body>
</html>

HTML;

?>
