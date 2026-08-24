{{-- var smobile = "";
var resendTimer; // Declare resendTimer globally

function sendOTP(mobile) {
    const timerAlert = document.getElementById('timerAlert');
    const sendOtpButton = document.getElementById('verifyButton');
    smobile = mobile;

    // Clear existing timer if it exists
    if (resendTimer) {
        clearInterval(resendTimer);
    }

    sendOtpButton.disabled = true;

    // Send the OTP
    fetch("{{ route('sendotp') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ mobile: smobile })
    })
    .then(response => response.json())
    .then(data => {
        showOtpSection();
        sendOtpButton.style.display = 'none';
        timerAlert.style.display = 'block';

        let timeLeft = 20;
        timerAlert.innerHTML = `Resend in ${timeLeft} seconds`;

        resendTimer = setInterval(() => {
            timeLeft--;
            timerAlert.innerHTML = `Resend in ${timeLeft} seconds`;
            if (timeLeft <= 0) {
                clearInterval(resendTimer);
                timerAlert.style.display = 'none';
                sendOtpButton.value = 'Resend';
                sendOtpButton.style.display = 'inline';
                sendOtpButton.disabled = false;
            }
        }, 1000);
    })
    .catch(error => {
        console.error('Error sending OTP:', error);
        sendOtpButton.disabled = false;
        sendOtpButton.value = 'Resend';
    });
}

document.getElementById('submitOtp').addEventListener('click', () => {
    verifyOTP(document.getElementById('otpmobile').value);
});

function verifyOTP(otp) {
    fetch("{{ route('verifyotp') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ otp: otp, mobile: smobile })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === '1') {
            displaySuccess('OTP verified successfully!');
            clearInterval(resendTimer);
            document.getElementById('timerAlert').style.display = 'none';
            document.getElementById('findmobile').readOnly = true;

            const verifyButton = document.getElementById('verifyButton');
            verifyButton.style.display = 'inline';
            verifyButton.value = 'Verified';
            verifyButton.disabled = true;

            document.getElementById('otpSection').style.display = 'none';

            const mainsubmitButton = document.getElementById('mainsubmit');
            if (mainsubmitButton) {
                mainsubmitButton.disabled = false;
                mainsubmitButton.style.opacity = '1';
            }
        } else {
            handleError('Invalid OTP. Please try again.');
        }
    })
    .catch(() => {
        handleError('An error occurred. Please try again.');
    });
}

function handleError(message) {
    displayAlert(message);
    document.getElementById('otpSection').style.display = 'block';
    const mainsubmitButton = document.getElementById('mainsubmit');
    if (mainsubmitButton) {
        mainsubmitButton.disabled = true;
        mainsubmitButton.style.opacity = '0.5';
    }
}

// Validate input for OTP
function validateInput(input, maxLength) {
    const pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
    if (!pattern.test(input.value)) {
        input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
    }
    
    const verifyButton = document.getElementById('verifyButton');
    verifyButton.disabled = input.value.length !== maxLength;
    verifyButton.style.opacity = input.value.length === maxLength ? '1' : '0.5';
    verifyButton.style.cursor = input.value.length === maxLength ? 'pointer' : 'not-allowed';
} --}}
<script>
    async function getBrandName(brand) {
        try {
            const response = await $.ajax({
                url: "{{ route('car.getbrand') }}",
                type: "POST",
                data: {
                     brand: brand,
                    _token: "{{ csrf_token() }}",
                },
            });
            return response;
        } catch (error) {
            console.error(error);
            return null;
        }
    }

    async function getModelName(model) {
        try {
            const response = await $.ajax({
                url: "{{ route('car.getmodel') }}",
                type: "POST",
                data: {
                    brand: model,
                    _token: "{{ csrf_token() }}",
                },
            });
            return response;
        } catch (error) {
            console.error(error);
            return null;
        }
    }
</script>
