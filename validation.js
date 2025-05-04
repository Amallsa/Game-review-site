// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get the game form if it exists
    const gameForm = document.getElementById('game-form');

    // Get the contact form if it exists
    const contactForm = document.getElementById('contact-form');

    // Add event listener to game form
    if (gameForm) {
        gameForm.addEventListener('submit', validateGameForm);
    }

    // Add event listener to contact form
    if (contactForm) {
        contactForm.addEventListener('submit', validateContactForm);
    }
});

// Function to validate game form
function validateGameForm(event) {
    // Prevent form from submitting
    event.preventDefault();

    // Get form fields
    const title = document.getElementById('title').value.trim();
    const developer = document.getElementById('developer').value.trim();
    const description = document.getElementById('description').value.trim();
    const rating = document.getElementById('rating').value.trim();
    const playtime = document.getElementById('playtime').value.trim();
    const releaseYear = document.getElementById('release_year').value.trim();
    const category = document.getElementById('category_id').value;

    // Array to store error messages
    let errors = [];

    // Validate title
    if (title === '') {
        errors.push('Game title is required');
    } else if (title.length < 3) {
        errors.push('Game title must be at least 3 characters long');
    }

    // Validate developer
    if (developer === '') {
        errors.push('Developer name is required');
    } else if (developer.length < 3) {
        errors.push('Developer name must be at least 3 characters long');
    }

    // Validate description
    if (description === '') {
        errors.push('Review description is required');
    } else if (description.length < 30) {
        errors.push('Review should be more detailed (at least 30 characters)');
    }

    // Validate rating
    if (rating === '') {
        errors.push('Rating is required');
    } else if (isNaN(rating) || parseFloat(rating) < 0 || parseFloat(rating) > 10) {
        errors.push('Rating must be a number between 0 and 10');
    }

    // Validate playtime
    if (playtime !== '' && (isNaN(playtime) || parseInt(playtime) < 1)) {
        errors.push('Playtime must be a positive number');
    }

    // Validate release year
    if (releaseYear !== '' && (isNaN(releaseYear) || parseInt(releaseYear) < 1970 || parseInt(releaseYear) > new Date().getFullYear())) {
        errors.push('Release year must be a valid year (between 1970 and current year)');
    }

    // Validate category
    if (category === '') {
        errors.push('Please select a genre');
    }

    // Display errors or submit form
    if (errors.length > 0) {
        displayErrors(errors);
    } else {
        // If no errors, submit the form
        document.getElementById('game-form').submit();
    }
}

// Function to validate contact form
function validateContactForm(event) {
    // Prevent form from submitting
    event.preventDefault();

    // Get form fields
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const subject = document.getElementById('subject').value.trim();
    const message = document.getElementById('message').value.trim();

    // Array to store error messages
    let errors = [];

    // Validate name
    if (name === '') {
        errors.push('Name is required');
    } else if (name.length < 2) {
        errors.push('Name must be at least 2 characters long');
    }

    // Validate email
    if (email === '') {
        errors.push('Email is required');
    } else if (!isValidEmail(email)) {
        errors.push('Please enter a valid email address');
    }

    // Validate subject
    if (subject === '') {
        errors.push('Subject is required');
    } else if (subject.length < 5) {
        errors.push('Subject must be at least 5 characters long');
    }

    // Validate message
    if (message === '') {
        errors.push('Message is required');
    } else if (message.length < 20) {
        errors.push('Message must be at least 20 characters long');
    }

    // Display errors or submit form
    if (errors.length > 0) {
        displayErrors(errors);
    } else {
        // If no errors, submit the form
        document.getElementById('contact-form').submit();
    }
}

// Function to validate email format
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Function to display error messages
function displayErrors(errors) {
    // Get or create error message container
    let errorContainer = document.querySelector('.error-message');

    if (!errorContainer) {
        errorContainer = document.createElement('div');
        errorContainer.className = 'error-message';

        // Get the form
        const form = document.querySelector('form');

        // Insert error container before the form
        form.parentNode.insertBefore(errorContainer, form);
    }

    // Clear previous error messages
    errorContainer.innerHTML = '<h3>Please fix the following errors:</h3><ul></ul>';

    // Get the list element
    const errorList = errorContainer.querySelector('ul');

    // Add each error as a list item
    errors.forEach(error => {
        const li = document.createElement('li');
        li.textContent = error;
        errorList.appendChild(li);
    });

    // Show the error container
    errorContainer.classList.add('show');

    // Scroll to the top of the form
    window.scrollTo({
        top: errorContainer.offsetTop - 20,
        behavior: 'smooth'
    });
}
