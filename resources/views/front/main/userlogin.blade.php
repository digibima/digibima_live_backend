<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User login</title>
    @include('front.partial.csslink')
    <link href="{{ config('constant.BASE_URL') }}front/css/login.css" rel="stylesheet">
    <style>
 
        .MainErrorBox {
            font-family: system-ui, sans-serif;
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 12px;
            display: flex;
            align-items: center;
            background: #EF665B;
            border-radius: 8px;
            box-shadow: 0px 0px 5px -3px #111;
            z-index: 1000;
        }

        .error__icon,
        .error__close {
            color: #fff;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .error__title {
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            margin: 0 10px 0 0;
        }

        .alredyacount {
            background: #fff;
            padding: 0.3rem 0.5rem;
            border-radius: 10px;
        }

        .alredyacount a {
            text-decoration: none;
            color: #1C5FA8;
        }

        @media (max-width: 991.98px) {
            .vehicle-container {
                flex-direction: column;
                align-items: center;
            }

            .vehicle-box {
                width: 100px;
                margin-bottom: 0.5rem;
            }
        }

        @media (max-width: 767.98px) {
            .sidepera {
                margin-top: 1.8rem;
            }

            #findtopplan .image {
                width: 100%;
                max-width: 500px;
                height: auto;
            }
        }

        #backgroundSection {
            height: 100vh;
            background: url('{{ config('constant.BASE_URL') }}front/images/loginbackground.png') center/cover no-repeat;
        }
    </style>
</head>

<body>
    <div class="MainErrorBox" style="display:none;">
        <span class="error__icon"><i class="fa-solid fa-circle-exclamation"></i></span>
        <p class="error__title mb-0">Self cannot be combined with Father or Mother.</p>
        <span class="error__close"><i class="fa-solid fa-xmark"></i></span>
    </div>

    <main id="backgroundSection"></main>

    <div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-3">
            <div class="modal-content rounded-3">
                <div class="modal-header border-0 justify-content-end">
                    <a href="https://digibima.com/" class="float:right;"><button type="button" class="btn-close"></button></a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12" style="padding: 0px 20px;">
                            <img src="{{ config('constant.BASE_URL') }}front/images/logo.png" width="180"
                                alt="Logo" />
                        </div>
                        <div class="col-lg-12 mb-2">
                            <div class="mobilesection">
                                <h2 class="gradient-heading" style="text-align: left!important;padding: 15px 20px;">Sign
                                    in</h2>
                                <div class="mobile-input mb-2">
                                    <input type="text" id="mobileNumber" placeholder="Enter your mobile number"
                                        maxlength="10" required>
                                </div>
                                <button class="getstartedOne" id="getOTP">Get OTP</button>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-2">
                            <div class="otpsection" style="display: none;">
                                <h2 class="gradient-heading">OTP Authentication</h2>
                                <p>Enter the 6 digit OTP sent to your mobile number.</p>
                                <div class="otp-input">
                                    <input type="text" maxlength="1" pattern="[0-9]*">
                                    <input type="text" maxlength="1" pattern="[0-9]*">
                                    <input type="text" maxlength="1" pattern="[0-9]*">
                                    <input type="text" maxlength="1" pattern="[0-9]*">
                                    <input type="text" maxlength="1" pattern="[0-9]*">
                                    <input type="text" maxlength="1" pattern="[0-9]*">
                                </div>
                                <p class="mb-0" id="timer"></p>
                                <p id="resend" style="font-size: 14px; display: none;">Didn't receive an OTP:
                                    <span><a href="#" id="resendgetOTP">Resend</a></span>
                                </p>
                                <button class="getstartedOne" id="loginverifyOTP">Verify</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0"></div>
            </div>
        </div>
    </div>

    @include('front.partial.footer')
    @include('front.partial.jslink')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
            
        });

        const inputs = document.querySelectorAll(".otp-input input");


        // Move focus to next input after entering a digit
        function moveFocus(event, nextInputIndex) {
            const currentInput = event.target;
            const currentValue = currentInput.value;

            if (!/^[0-9]$/.test(currentValue)) {
                currentInput.value = '';
                return;
            }

            const nextInput = document.querySelectorAll('.otp-input input')[nextInputIndex];
            if (nextInput && currentValue) {
                nextInput.focus();
            }
            if (event.target.value.length > 1) {
                event.target.value = event.target.value.slice(0, 1);
            }

            function moveBackspace(event, prevInputIndex) {
                if (event.key === "Backspace") {
                    if (event.target.value === "") {
                        if (prevInputIndex >= 0) {
                            document.querySelectorAll(".otp-input input")[prevInputIndex].focus();
                        }
                    }
                }
            }
            if (event.target.value.length === 1 && nextInputIndex < 6) {
                document.querySelectorAll(".otp-input input")[nextInputIndex].focus();
            }
        }


        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length > 1) {
                    e.target.value = e.target.value.slice(0, 1);
                }
                if (e.target.value.length === 1) {
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value) {
                    if (index > 0) {
                        inputs[index - 1].focus();
                    }
                }
                if (e.key === 'e') {
                    e.preventDefault();
                }
            });
        });

        function validatePhoneNumber(event) {
            const input = event.target;
            input.value = input.value.replace(/[^0-9]/g, '');
        }

        function openModal() {
            var myModalElement = document.getElementById('loginModal');
            var myModal = new bootstrap.Modal(myModalElement, {
                backdrop: 'static',
                keyboard: false
            });
            myModal.show();
        }

        const errorBox1 = document.querySelector('.MainErrorBox');
        const errorTitleElement1 = errorBox1?.querySelector('.error__title');
        const successBox1 = document.querySelector('.MainverifiyedBox');
        const successTitleElement1 = successBox1?.querySelector('.verifiyed__title');

        function displayAlert(message) {
            if (errorTitleElement1) {
                errorTitleElement1.innerText = message;
                errorBox1.style.display = 'flex';
                errorBox1.style.zIndex = '9999';

                // Automatically hide after 3 seconds
                setTimeout(() => {
                    errorBox1.style.display = 'none';
                }, 3000);
            }
        }

        function displaySuccess(messageverifiy) {
            if (successTitleElement1) {
                successTitleElement1.innerText = messageverifiy;
                successBox1.style.display = 'flex';
                successBox1.style.zIndex = '9999';

                // Automatically hide after 3 seconds
                setTimeout(() => {
                    successBox1.style.display = 'none';
                }, 3000);
            }
        }
        // Function to handle manual close button for error
        const errorCloseButton = document.querySelector('.error__close');
        if (errorCloseButton) {
            errorCloseButton.addEventListener('click', () => {
                errorBox1.style.display = 'none';
            });
        }

        // Function to handle manual close button for success
        const successCloseButton = document.querySelector('.verifiyed__close');
        if (successCloseButton) {
            successCloseButton.addEventListener('click', () => {
                successBox1.style.display = 'none';
            });
        }


        let resendTimer1;
        let otpSent1 = false;

        document.getElementById('getOTP').addEventListener('click', function() {
            const mobile = document.getElementById('mobileNumber').value;

            if (!/^[6-9]\d{9}$/.test(mobile)) {
                displayAlert('Please enter a valid 10-digit Mobile Number.');
                errorTitleElement1.innerText = `Please enter a valid 10-digit Mobile Number.`;
                document.getElementById('mobileNumber').focus();
                return;
            }

            if (!otpSent1) {
                loginsendOTP(mobile);
            }
        });

        function loginsendOTP(mobile) {
            const timerAlert = document.getElementById('timer');
            const loginsendOTPButton = document.getElementById('loginverifyOTP');
            const resendOtpButton = document.getElementById('resend');
            document.querySelector(".mobilesection").style.display = "none";
            document.querySelector(".otpsection").style.display = "block";
            smobile = mobile;

            // Clear any existing timer
            if (resendTimer1) {
                clearInterval(resendTimer1);
            }

            fetch("{{ route('sendotp') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        'mobile': smobile
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to send OTP');
                    }
                    return response.json();
                })
                .then(data => {
                    otpSent1 = true;
                    let timeLeft = 20;
                    timerAlert.textContent = `Resend in ${timeLeft} seconds`;

                    resendTimer1 = setInterval(() => {
                        timeLeft--;
                        timerAlert.textContent = `Resend in ${timeLeft} seconds`;

                        if (timeLeft <= 0) {
                            clearInterval(resendTimer1);
                            timerAlert.style.display = 'none';
                            resendOtpButton.style.display = 'block';
                            const getOtpButton = document.getElementById('resendgetOTP');
                            getOtpButton.removeEventListener('click', handleResendOtp);
                            getOtpButton.addEventListener('click', handleResendOtp);
                        }
                    }, 1000);

                    function handleResendOtp() {
                        loginsendOTP(mobile);
                        timerAlert.style.display = 'block';
                    }
                })

                .catch(error => {
                    console.error('Error sending OTP:', error);
                    displayAlert('Failed to send OTP. Please try again.');

                });
        }
        document.getElementById('loginverifyOTP').addEventListener('click', function() {
            const mobile = document.getElementById('mobileNumber').value;
            const otpInputs = document.querySelectorAll('.otp-input input');
            let otp = '';
            otpInputs.forEach(input => {
                otp += input.value;
            });

            if (otp.length !== 6 || !/^\d{6}$/.test(otp)) {
                displayAlert('Please enter a valid 6-digit OTP.');
                return;
            }
            if (!/^[6-9]\d{9}$/.test(mobile)) {
                displayAlert('Please enter a valid mobile number.');
                return;
            }
            loginverifyOTP(mobile, otp);
        });



        function loginverifyOTP(mobile, otp) {
            console.log(otp);
            console.log(mobile);
            fetch("{{ route('verifyotp') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        'otp': otp,
                        'mobile': mobile
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.status === '1') {
                        displaySuccess('OTP verified successfully!');
                        clearInterval(resendTimer1);
                        document.getElementById('timer').style.display = 'none';
                        document.querySelectorAll('.otp-input input').forEach(input => {
                            input.readOnly = true;
                        });
                        const verifyButton = document.getElementById('loginverifyOTP');
                        const resendOtpButton = document.getElementById('resend');
                        verifyButton.innerHTML = 'Verified';
                        verifyButton.style.opacity = '0.5';
                        verifyButton.disabled = true;
                        resendOtpButton.style.display = 'none';
                        fetch("{{ route('login') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    'mobile': mobile
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === '1') {
                                    window.location.href = "{{ route('userroot') }}";
                                }
                                console.log(data);
                            });

                    } else {
                        displayAlert('Invalid OTP. Please try again.');

                    }
                })
                .catch(error => {
                    displayAlert('An error occurred. Please try again.');
                });
        }
    </script>
</body>

</html>
