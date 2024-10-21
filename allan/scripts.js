// scripts.js

// Function to validate email format
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// Function to validate registration form
function validateRegistrationForm(event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const messageElement = document.getElementById('message');

    let message = '';

    // Validate email
    if (!isValidEmail(email)) {
        message = 'Please enter a valid email address.';
    } 
    // Validate password length
    else if (password.length < 6) {
        message = 'Password must be at least 6 characters long.';
    } 
    // Validate password confirmation
    else if (password !== confirmPassword) {
        message = 'Passwords do not match.';
    } 

    // If there are no validation errors, submit the form
    if (message === '') {
        document.getElementById('registrationForm').submit();
    } else {
        messageElement.innerText = message;
        messageElement.style.color = 'red';
    }
}

// Function to validate login form
function validateLoginForm(event) {
    event.preventDefault();

    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const messageElement = document.getElementById('loginMessage');

    let message = '';

    // Validate email
    if (!isValidEmail(email)) {
        message = 'Please enter a valid email address.';
    } 
    // Validate password
    else if (password.length < 6) {
        message = 'Password must be at least 6 characters long.';
    } 

    // If there are no validation errors, submit the form
    if (message === '') {
        document.getElementById('loginForm').submit();
    } else {
        messageElement.innerText = message;
        messageElement.style.color = 'red';
    }
}

// Function to submit review via AJAX
function submitReview(event) {
    event.preventDefault();

    const reviewText = document.getElementById('reviewText').value;
    const messageElement = document.getElementById('reviewMessage');

    if (reviewText.trim() === '') {
        messageElement.innerText = 'Review cannot be empty.';
        messageElement.style.color = 'red';
        return;
    }

    // Send review to the server using Fetch API
    fetch('submit_review.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            review: reviewText
        })
    })
    .then(response => response.text())
    .then(data => {
        messageElement.innerText = data; // Show server response
        messageElement.style.color = 'green';
        document.getElementById('reviewText').value = ''; // Clear the textarea
        // Optionally, update the review list dynamically
        loadReviews();
    })
    .catch(error => {
        console.error('Error:', error);
        messageElement.innerText = 'An error occurred. Please try again.';
        messageElement.style.color = 'red';
    });
}

// Function to load reviews via AJAX
function loadReviews() {
    fetch('load_reviews.php')
        .then(response => response.json())
        .then(reviews => {
            const reviewList = document.getElementById('reviewList');
            reviewList.innerHTML = ''; // Clear existing reviews
            reviews.forEach(review => {
                const li = document.createElement('li');
                li.textContent = review; // Assuming review is a simple text
                reviewList.appendChild(li);
            });
        })
        .catch(error => console.error('Error loading reviews:', error));
}

// Attach event listeners to forms
document.addEventListener('DOMContentLoaded', () => {
    const registrationForm = document.getElementById('registrationForm');
    if (registrationForm) {
        registrationForm.addEventListener('submit', validateRegistrationForm);
    }

    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', validateLoginForm);
    }

    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', submitReview);
    }

    // Load reviews on page load
    loadReviews();
});
