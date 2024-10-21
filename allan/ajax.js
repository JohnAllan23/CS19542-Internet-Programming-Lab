// ajax.js

// Function to submit a review via AJAX
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
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', submitReview);
    }

    // Load reviews on page load
    loadReviews();
});
