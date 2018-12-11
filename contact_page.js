function validate() {
  var errors = "";
  window.alert("FDSA");
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

}

function check_phone() {


}
