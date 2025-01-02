let isUsernameAvailable = false; // Track username availability status

// Validate Username Pattern and Minimum Length
function validateUsernamePattern() {
    const usernameField = document.getElementById("username");
    const usernamePatternError = document.getElementById("usernamePatternError");
    const usernameMinLengthError = document.getElementById("usernameMinLengthError");
    const username = usernameField.value;

    // Reset error messages
    usernamePatternError.style.display = "none";
    usernameMinLengthError.style.display = "none";
    hideAvailabilityMessages();

    const pattern = /^[a-z0-9]+$/;

    if (!pattern.test(username)) {
        usernamePatternError.style.display = "block";
        return;
    }

    // Show minimum length error if username is too short
    if (username.length < 4) {
        usernameMinLengthError.style.display = "block";
        return;
    }

    // Proceed with availability check if both validations pass
    checkUsernameAvailability(username);
}

// Hide all availability messages
function hideAvailabilityMessages() {
    document.getElementById("usernameAvailabilityError").style.display = "none";
    document.getElementById("usernameAvailabilitySuccess").style.display = "none";
}

// Check Username Availability via AJAX
let debounceTimer;
function checkUsernameAvailability(username) {
    if (!username) {
        console.log("Username is undefined or empty.");
        return; // Exit if username is undefined
    }

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(function () {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../../models/check_username.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        console.log("Sending username:", username);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                const response = xhr.responseText.trim();
                console.log("Response from server:", response);

                if (response === "taken") {
                    document.getElementById("usernameAvailabilityError").style.display = "block";
                    document.getElementById("usernameAvailabilitySuccess").style.display = "none";
                    isUsernameAvailable = false;
                } else if (response === "available") {
                    document.getElementById("usernameAvailabilityError").style.display = "none";
                    document.getElementById("usernameAvailabilitySuccess").style.display = "block";
                    isUsernameAvailable = true;
                } else {
                    console.log("Unexpected response:", response);
                }
            }
        };
        xhr.send("username=" + encodeURIComponent(username));
    }, 500);
}



// Check Email Availability via AJAX
function checkEmailAvailability() {
    const emailField = document.getElementById("email");
    const emailError = document.getElementById("emailError");
    const email = emailField.value.trim();

    // Check for built-in email format validity
    if (!emailField.checkValidity()) {
        emailError.textContent = "Please enter a valid email address.";
        emailError.style.color = "red";
        return;
    } else {
        emailError.textContent = ""; // Clear any previous error messages
    }

    // Proceed with availability check if format is valid
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../../models/check_email.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);

            if (response.success) {
                emailError.textContent = "Email is available.";
                emailError.style.color = "green";
            } else {
                emailError.textContent = response.message || "Email is already in use.";
                emailError.style.color = "red";
            }
        }
    };
    xhr.send("email=" + encodeURIComponent(email));
}

// Function to request OTP via AJAX
async function requestOTP() {
    const email = document.getElementById("email").value;
    const emailError = document.getElementById("emailError");

    // Basic email format check before requesting OTP
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        emailError.textContent = "Please enter a valid email address.";
        return;
    }

    // Send OTP request to the backend
    try {
        const response = await fetch("../../models/send_otp.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `email=${encodeURIComponent(email)}`,
        });

        if (!response.ok) {
            throw new Error("Network response was not ok");
        }

        const data = await response.json();
        if (data.success) {
            emailError.textContent = "OTP sent to your email!";
            document.getElementById("otp").disabled = false; // Enable OTP input
            document.getElementById("otpTimerDisplay").textContent = "You have 3 minutes to enter the OTP.";
            startOTPTimer();
        } else {
            emailError.textContent = data.message || "An error occurred while sending OTP.";
        }
    } catch (error) {
        emailError.textContent = "Failed to send OTP. Please try again.";
    }
}

let otpTimer = 180; // 3 minutes in seconds
let otpTimerInterval;
function startOTPTimer() {
    otpTimerInterval = setInterval(function () {
        otpTimer--;
        const minutes = Math.floor(otpTimer / 60);
        const seconds = otpTimer % 60;
        document.getElementById("otpTimerDisplay").textContent = `Time left: ${minutes}:${seconds < 10 ? "0" + seconds : seconds}`;
        if (otpTimer <= 0) {
            clearInterval(otpTimerInterval);
            document.getElementById("otpTimerDisplay").textContent = "OTP expired.";
            document.getElementById("otp").disabled = true; // Disable OTP input
        }
    }, 1000);
}

// Set today's date as the minimum date for the move-in date input
window.onload = function() {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
    var yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd;

    // Set the min attribute of the input field to today's date
    document.getElementById('move_in_date').setAttribute('min', today);
};

// Validate form before submission
function validateForm() {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;
    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }
    if (!isUsernameAvailable) {
        alert("Username is not available. Please choose another.");
        return false;
    }
    return true; // Allow form submission if everything is valid
}

// make Department field selection required for Students & Teachers
function toggleDepartmentField() {
    const role = document.getElementById("role").value;
    const departmentField = document.getElementById("department-field");

    if (role === "student" || role === "teacher") {
        departmentField.style.display = "block";
    } else {
        departmentField.style.display = "none";
        document.getElementById("department").value = ""; // Clear the department selection if not required
    }
}

// Reset Role Field on Page Load
window.onload = function() {
    document.getElementById("role").selectedIndex = 0; // Set role to default option
    toggleDepartmentField(); // Reset department requirement on page load
};
