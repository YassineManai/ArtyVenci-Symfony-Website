var pwd = document.getElementById('password');

var Cpwd = document.getElementById('Cpwd');
var eye = document.getElementById('eye');
var eye1 = document.getElementById('eye1');


eye.addEventListener('click',togglePass);
eye1.addEventListener('click',togglePass1);


function togglePass(){

   eye.classList.toggle('active');
   (pwd.type == 'password') ? pwd.type = 'text' : pwd.type = 'password';
   

}


function togglePass1(){
  eye1.classList.toggle('active');
  (Cpwd.type == 'password') ? Cpwd.type = 'text' : Cpwd.type = 'password';
}


function checkStuff() {
    var username = document.form1.username;
    var email = document.form1.email;
    var password = document.form1.password;
    var confirmpwd = document.form1.Cpwd;
    var role = document.form1.role;
    
    var msg = document.getElementById('msg');
    
    if (username.value == "") {
      msg.style.display = 'block';
      msg.innerHTML = "Please enter your username";
      username.focus();
      return false;
    }
   
    else if (email.value == "") {
        msg.style.display = 'block';
        msg.innerHTML = "Please enter your email";
        email.focus();
        return false;
      }

    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
     if (!re.test(email.value)) {
        msg.style.display = 'block';
      msg.innerHTML = "Please enter a valid email";
      email.focus();
      return false;
    }
    
    if (password.value == "") {
        msg.style.display = 'block';
      msg.innerHTML = "Please enter your password";
      password.focus();
      return false;
    }

    if (confirmpwd.password == "") {
        msg.style.display = 'block';
        msg.innerHTML = "Please enter your password";
        password.focus();
        return false;
      }
      if (password.value != confirmpwd.value) {
        msg.style.display = 'block';
        msg.innerHTML = "Please confirm your password";
        confirmpwd.focus();
        return false;
      }
  
    if (role.value == "") {
        msg.style.display = 'block';
      msg.innerHTML = "Please choose your role";
      role.focus();
      return false;
    }
      return  true
  }


  function check() {
    var firstname = document.form1.firstname;
    var lastname = document.form1.lastname;
    var adress = document.form1.adress;
    var phone = document.form1.phone;
    var date = document.form1.dateInput;
    var gender = document.form1.Gender;
    var msg = document.getElementById('msg');
    
    if (firstname.value == "") {
      msg.style.display = 'block';
      msg.innerHTML = "Please enter your firstname";
      firstname.focus();
      return false;
    }
   
    else if (lastname.value == "") {
        msg.style.display = 'block';
        msg.innerHTML = "Please enter your lastname";
        lastname.focus();
        return false;
      }


    
    if (adress.value == "") {
        msg.style.display = 'block';
      msg.innerHTML = "Please enter your address";
      adress.focus();
      return false;
    }
    
    if (phone.value === "") {
        msg.style.display = 'block';
        msg.innerHTML = "Please enter your Phone number";
        phone.focus();
        return false;
    } else if (isNaN(phone.value) || phone.value.length !== 8) {
        msg.style.display = 'block';
        msg.innerHTML = "Please enter a valid Phone number (8 digits)";
        phone.focus();
        return false;
    }
    
  
    if (date.value == "") {
        msg.style.display = 'block';
      msg.innerHTML = "Please choose your birthday";
      date.focus();
      return false;
    }
    if (gender.value == "") {
        msg.style.display = 'block';
      msg.innerHTML = "Please choose your gender";
      gender.focus();
      return false;
    }
      return  true
  }