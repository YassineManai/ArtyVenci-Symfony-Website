
function checkStuff() {
   
    var email = document.form1.email;
    
    
    var msg = document.getElementById('msg');
    
 
   
   if (email.value == "") {
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
    
      return  true
  }
