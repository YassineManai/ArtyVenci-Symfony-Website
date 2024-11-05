var pwd = document.getElementById('password');


var eye = document.getElementById('eye');



eye.addEventListener('click',togglePass);



function togglePass(){

   eye.classList.toggle('active');
   (pwd.type == 'password') ? pwd.type = 'text' : pwd.type = 'password';
   

}



// Form Validation

function checkStuff() {
  var username = document.form1.username;
  var password = document.form1.password;
  var msg = document.getElementById('msg');
  var user = document.getElementById('username');
  
  if (username.value == "") {
    msg.style.display = 'block';
    msg.innerHTML = "Please enter your username";
    user.focus();
    return false;
  }
  
  if (password.value == "") {
    msg.style.display = 'block';
    msg.innerHTML = "Please enter your password";
    password.focus();
    return false;
  }
  
    return  true
}