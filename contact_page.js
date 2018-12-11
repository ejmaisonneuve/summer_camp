function validate() {
  var errors = "";
  errors += check_email();
  errors += check_phone();
  if(errors.length > 0) {
    window.alert(errors);
    return false;
  }
  else {
    return true;
  }
}

function check_email() {
  var email = document.getElementById("email").value;
  var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  if(!re.test(email)) {
    return "Not a valid email. \n";
  }
  return "";  
}

function check_phone() {
  var phone = document.getElementById("phone").value;
  var re = /\(*[0-9]{3}\)*\s*-*\s*[0-9]{3}\s*-*\s*[0-9]{4}/;
  if(!re.test(phone)) {
    return "Not a valid phone number. \n";
  }
  return "";
}
