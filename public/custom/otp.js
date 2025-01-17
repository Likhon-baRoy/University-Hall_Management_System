let timerInterval;
let otpVerified = false;

document.getElementById('sendOtpBtn').addEventListener('click', async function() {
    const email = document.getElementById('email').value;
    if (!email) {
        alert('Please enter your email first');
        return;
    }

    try {
        const response = await fetch('/send-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',  // Add this
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ email })
        });

        const data = await response.json();

        if (response.ok) {
            // Enable OTP input
            document.getElementById('otpInput').readOnly = false;

            // Start timer
            startTimer();

            // Show success message
            alert('OTP sent successfully! Please check your email.');
        } else {
            // More detailed error handling
            const errorMessage = data.message || data.error || 'Failed to send OTP';
            console.error('Server responded with:', data);
            alert(errorMessage);
        }
    } catch (error) {
        console.error('Error details:', error);
        alert('Network error while sending OTP. Please try again.');
    }
});

document.getElementById('otpInput').addEventListener('input', async function() {
    if (this.value.length === 6) {
        try {
            const response = await fetch('/verify-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email: document.getElementById('email').value,
                    otp: this.value
                })
            });

            const data = await response.json();

            if (response.ok) {
                otpVerified = true;
                document.getElementById('otpError').style.display = 'none';
                clearInterval(timerInterval);
                document.getElementById('otpTimer').style.display = 'none';
            } else {
                otpVerified = false;
                document.getElementById('otpError').textContent = data.message;
                document.getElementById('otpError').style.display = 'block';
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
});

function startTimer() {
    let timeLeft = 5 * 60; // 5 minutes in seconds
    document.getElementById('otpTimer').style.display = 'block';

    clearInterval(timerInterval);

    timerInterval = setInterval(() => {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;

        document.getElementById('timer').textContent =
            `${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            document.getElementById('otpInput').readOnly = true;
            document.getElementById('otpInput').value = '';
            document.getElementById('otpTimer').style.display = 'none';
        }

        timeLeft--;
    }, 1000);
}

// Modify form submit handler
document.querySelector('form').addEventListener('submit', function(e) {
    if (!otpVerified) {
        e.preventDefault();
        alert('Please verify your email with OTP first');
    }
});
