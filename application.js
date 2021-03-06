function show_rows() {
  var sib_sel = document.getElementById("num_sib");
  var num_sibs = sib_sel.options[sib_sel.selectedIndex].value;
  var rows_list = document.getElementsByClassName("sibling_row");

  for (i = 0; i < (rows_list.length); i++) {
    if(rows_list[i].childNodes.length > 0) {
      rows_list[i].removeChild(rows_list[i].childNodes[0]);
    }
    else {
      break;
    }
  }

  for (i = 0; i < (num_sibs*2); i+=2) {
    var new_input = document.createElement("input");
    var num = i/2 + 1;
    new_input.type = "text";
    if(i == 12) {
      new_input.placeholder = "Rest of Names";
    }
    else {
      new_input.placeholder = "Name";
    }
    new_input.name = "sibling_name_" + num;
    new_input.className = "sibling_info";
    rows_list[i].appendChild(new_input);
    new_input = document.createElement("input");
    new_input.type = "text";
    if(i == 12) {
      new_input.placeholder = "Rest of Ages";
    }
    else {
      new_input.placeholder = "Age";
    }
    new_input.name = "sibling_age_" + num;
    new_input.className = "sibling_info";
    rows_list[i+1].appendChild(new_input);
  }
}

function add_pickup(item) {
  var helper = document.getElementById("pickup_helper");
  var new_num = parseInt(helper.name) + 1;
  helper.id = "";
  var prev_name = document.getElementById("prev_name").value;
  var prev_address = document.getElementById("prev_address").value;
  var prev_rel = document.getElementById("prev_rel").value;
  var prev_phone = document.getElementById("prev_phone").value;
  if(prev_name.length == 0 || prev_address.length == 0 || prev_rel.length == 0 || prev_phone.length == 0) {
    window.alert("Please fill out current person's information before adding another person.");
    return;
  }

  document.getElementById("prev_name").required = true;
  document.getElementById("prev_address").required = true;
  document.getElementById("prev_rel").required = true;
  document.getElementById("prev_phone").required = true;
  var table = document.getElementById("app_fam");
  var new_row = document.createElement("tr");
  new_row.className = "pick_up_row";
  var new_input = create_input(item, "Name", "Edith Anne Doe", "2", "pickup_name_"+new_num);
  document.getElementById("prev_name").id = "";
  new_input.childNodes[1].id = "prev_name";
  new_row.appendChild(new_input);
  new_input = create_input(item, "Relationship", "Grandmother", "1", "pickup_relationship_"+new_num);
  document.getElementById("prev_rel").id = "";
  new_input.childNodes[1].id = "prev_rel";
  new_row.appendChild(new_input);
  new_input = create_input(item, "Phone", "(123) 456-7890", "1", "pickup_phone_"+new_num);
  document.getElementById("prev_phone").id = "";
  new_input.childNodes[1].id = "prev_phone";
  new_input.childNodes[1].className = "phones";
  var new_helper = document.createElement("input");
  new_helper.type = 'hidden';
  new_helper.name = new_num;
  new_helper.id = 'pickup_helper';
  new_row.appendChild(new_input);
  new_row.appendChild(new_helper);
  table.appendChild(new_row);

  var new_row = document.createElement("tr");
  new_row.className = "pick_up_row";
  new_input = create_input(item, "Address", "1111 Left Lane, Spring, TX, 77388", "2", "pickup_address_"+new_num);
  document.getElementById("prev_address").id = "";
  new_input.childNodes[1].id = "prev_address";
  new_row.appendChild(new_input);
  new_button = create_button(item);
  new_row.append(new_button);
  table.appendChild(new_row);

  return;

}

function create_button(item) {
  var new_place = document.createElement("td");
  new_place.appendChild(item);
  return new_place;

}

function create_input(item, title, ph, span, name) {
  var new_place = document.createElement("td");
  var new_title = document.createElement("div");
  var new_text = document.createTextNode(title);
  var new_item = document.createElement("input");
  new_item.type = "text";
  new_item.name = name;
  new_item.placeholder = ph;
  if(span === "2") {
    new_item.style.width = "330px";
  }
  new_title.appendChild(new_text);
  new_place.appendChild(new_title);
  new_place.colSpan = span;
  new_place.appendChild(new_item);
  return new_place;
}

function check_info() {
  var problems = "";
  problems += check_dob();
  problems += check_phones();
  problems += check_parent();
  problems += check_sibling_info();
  problems += check_pickup();
  problems += check_emails();

  if(problems.length > 0) {
    window.alert(problems);
    return false;
  }
  return true;
}

function check_emails() {
  var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  var f_email = document.getElementById("femail").value;
  var m_email = document.getElementById("memail").value;
  
  if(!re.test(f_email) && !re.test(m_email)) {
    return "Make sure both parents' emails are formatted email@email.com";
  }
  else if(!re.test(f_email)) {
    return "Make sure father's email is formatted email@email.com";
  }
  else if(!re.test(m_email)) {
    return "Make sure mother's email is formatted email@email.com";
  }
  else {
    return "";
  }
}

function check_pickup() {
  var name = document.getElementById("prev_name").value;
  var add = document.getElementById("prev_address").value;
  var rel = document.getElementById("prev_rel").value;
  var phone = document.getElementById("prev_phone").value;
  var any_filled = name.length > 0 || rel.length > 0 || add.length > 0 || phone.length > 0;
  var all_filled = name.length > 0 && rel.length > 0 && add.length > 0 && phone.length
 > 0;

  if(any_filled && !all_filled) {
    return "Please fill out all infomration for the people who you would like to pick up your child.";
  }
  return "";
}

function check_parent() {
  var dad = document.getElementById("father").value;
  var mom = document.getElementById("mother").value;
  if(dad.length == 0 && mom.length == 0) {
    return "Please fill out information for at least one parent \n";
  }
  return "";
}

function check_phones() {
  var phone_nums = document.getElementsByClassName("phones");
  var count = 0;
  var bad_nums = [];
  var ans = "";

  var exp = /\(*[0-9]{3}\)*\s*-*\s*[0-9]{3}\s*-*\s*[0-9]{4}/
  for(i = 0; i < phone_nums.length; i++) {
    var phone_text = phone_nums[i].value;
    phone_text = phone_text.trim();
    phone_text = phone_text.toLowerCase();
    if(!exp.test(phone_nums[i].value) && phone_nums[i].value.length > 0 && phone_text != "n/a") {
      bad_nums.push(phone_nums[i].id);
    }
  }
  if(bad_nums.length > 0) {
    ans = "Make sure your ";
    for(i = 0; i <bad_nums.length; i++) {
      if(i == bad_nums.length - 1) {
        ans += bad_nums[i];
      }
      else if (i == bad_nums.length - 2) {
        ans += bad_nums[i] + " and ";
      }
      else {
        ans += bad_nums[i] + ", ";
      }
    }
    ans += " phone number information is filled out correctly: (832) 123-1234 \n";
  }
  return ans;
}

function check_sibling_info() {
  var sibling_info = document.getElementsByClassName("sibling_info");
  var count = 0;
  for(i = 0; i < sibling_info.length; i++) {
    if(sibling_info[i].value.length == 0) {
      return "Please fill out sibling info for each child. \n"
    }
  }
  return "";
}

function check_dob() {
  var dob = document.getElementById("dob").value;
  var re = /\d\d\/\d\d\/\d\d\d\d/;
  if(re.test(dob) && dob.length == 10) {
    return "";
  }
  else {
    return "Make sure date of birth is in DD/MM/YYYY format. \n";
  }
}

