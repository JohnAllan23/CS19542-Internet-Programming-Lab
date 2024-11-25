document.getElementById('regno').addEventListener('input', function(event) {
    this.value = this.value.replace(/[^0-9]/g, '');
});

document.getElementById('phone').addEventListener('input', function(event) {
    this.value = this.value.replace(/[^0-9]/g, '');
});

document.getElementById('registrationForm').addEventListener('submit', function(event) {
    const regno = document.getElementById('regno').value;
    const phone = document.getElementById('phone').value;
    document.getElementById('regno').addEventListener('input', function(event) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    document.getElementById('phone').addEventListener('input', function(event) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    document.getElementById('registrationForm').addEventListener('submit', function(event) {
        let isValid = true;
    
        const regno = document.getElementById('regno').value;
        const regnoError = document.getElementById('regnoError');
        if (regno.length !== 9) {
            regnoError.textContent = 'Registration number must be 9 digits';
            regnoError.style.display = 'block';
            isValid = false;
        } else {
            regnoError.style.display = 'none';
        }
    
        const phone = document.getElementById('phone').value;
        const phoneError = document.getElementById('phoneError');
        if (phone.length !== 10) {
            phoneError.textContent = 'Phone number must be 10 digits';
            phoneError.style.display = 'block';
            isValid = false;
        } else {
            phoneError.style.display = 'none';
        }
    
        if (!isValid) {
            event.preventDefault();
        }
    });
    
    if (regno.length !== 9) {
        alert('Registration number must be 9 digits');
        event.preventDefault();
    }
    
    if (phone.length !== 10) {
        alert('Phone number must be 10 digits');
        event.preventDefault();
    }
});
