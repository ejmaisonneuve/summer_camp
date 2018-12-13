document.getElementById("textForm").onsubmit = validate;
function validate () {

    //set variables
    var elt = document.getElementById("textForm");
    var user_name = elt.userName.value;
    var pass = elt.pass.value;
    var rep_pass = elt.rep_pass.value;
    var errors = "";

    //test username
    var exp1 = /[^a-zA-Z0-9\-]/;
    if(exp1.test(user_name)) {
      errors += "  -Family name must contain only letters, numbers and hyphens.\n";
    }

    if(user_name[0] != user_name[0].toUpperCase()) {
      errors += "  -Family name must start with a capital letter.\n";
    }

    //test password
    if(pass.length > 10 || pass.length < 6) {
      errors += "  -Password must be between 6 and 10 characters.\n";
    }
    exp1 = /[^a-zA-Z0-9]/;
    if(exp1.test(pass)) {
      errors += "  -Password must contain only numbers and letters.\n";
    }
    if(!(/[a-z]/.test(pass) && /[A-Z]/.test(pass) && /\d/.test(pass))) {
      errors += "  -Password must contain at least one uppercase letter, one lowercase letter and one digit.\n";
    }

    //check repeated Password
    if(rep_pass != pass) {
      errors += "  -Repeat password is not the same as password.";
    }

    if(errors.length > 0) {
      window.alert("Invalid Input: \n" + errors);
      return false;
    }

    else {
      return true
    }
}
