
function checkStuff() {
   
    var phone = document.form1.phone;
    
    
    var msg = document.getElementById('msg');
    
 
   
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

  
    
      return  true
  }
