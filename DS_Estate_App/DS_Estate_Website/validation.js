//add an event listener to the document that fires when the DOM content is fully loaded
document.addEventListener('DOMContentLoaded', function () {

    //select the registration form using a CSS selector
    const registerForm = document.querySelector('#registrationForm form');

    //add a submit event listener to the registration form
    registerForm.addEventListener('submit', function (event) {
        // Initialize variables to track form validity and error messages
        let isValid = true;
        let errorMessage = '';

        //validate first name
        const firstName = document.getElementById('firstname').value.trim();
        //check if the first name contains only letters
        if (!/^[a-zA-Z]+$/.test(firstName)) {
            isValid = false; 
            errorMessage += 'First name must contain only letters.\n'; //display error message
        }

        //validate last name
        const lastName = document.getElementById('lastname').value.trim();
        //check if the last name contains only letters
        if (!/^[a-zA-Z]+$/.test(lastName)) {
            isValid = false; 
            errorMessage += 'Last name must contain only letters.\n'; //display error message
        }

        //validate username
        const username = document.getElementById('username').value.trim();
        //check if the username contains only letters and numbers
        if (!/^[a-zA-Z0-9]+$/.test(username)) {
            isValid = false; 
            errorMessage += 'Username must contain only letters and numbers.\n'; //display error message
        }

        //validate password
        const password = document.getElementById('password').value.trim();
        //check if the password is between 4 and 10 characters long and contains at least one number
        if (password.length < 4 || password.length > 10 || !/\d/.test(password)) {
            isValid = false; 
            errorMessage += 'Password must be 4-10 characters long and include at least one number.\n'; //display error message
        }

        //validate email
        const email = document.getElementById('email').value.trim();
        //check if the email address is in a valid format
        if (!/\S+@\S+\.\S+/.test(email)) {
            isValid = false; 
            errorMessage += 'Invalid email address.\n'; //display error message
        }

        //if the form is not valid, prevent the form from submitting and display the error messages
        if (!isValid) {
            event.preventDefault(); //prevent form submission
            alert(errorMessage); //show error messages
        }

    });

});