{{-- <style>
    /* .btn-check:checked+.btn,
    .btn.active,
    .btn.show,
    .btn:first-child:active,
    :not(.btn-check)+.btn:active {
        color: #0B4C8D;
        background-color: #E5E6E8;
        border-color: transparent;
    }

    .btn-check:focus+.btn,
    .btn:focus {
        outline: 0;
        box-shadow: none !important;
        background: transparent !important;
    }

    .user {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #E5E6E8;
        float: right;
        margin-top: -6px;
        margin-left: 10px;
        position: relative;
    }

    #logout-dropdown {
        border-radius: 50% !important;
        padding: 10px 10px;
        color: #0B4C8D;
    }


    .btn-check:checked+.btn,
    .btn.active,
    .btn.show,
    .btn:first-child:active,
    :not(.btn-check)+.btn:active {
        border-color: transparent;

    }

    #tophead .user a {
        color: #0b4c8d;
        font-size: 15px !important;
        text-decoration: none;
        font-weight: 500 !important;
        margin-left: 0px !important;
    }

    .dropdown-item.active,
    .dropdown-item:active {
        color: #212529 !important;
        text-decoration: none !important;
        background-color: #f8f9fa !important;
    }

    .gradient-heading {
        background: linear-gradient(90deg, #1C5FA8, #34A8D8);
        
        -webkit-background-clip: text;
        color: transparent;
        font-size: 22px;
        font-weight: 600;
    } */

    body {
        font-family: Arial, sans-serif;
    }

    /* Top Bar */
    .top-bar {
        background-color: #0B4C8D;
        color: white;
        padding: 8px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        flex-wrap: wrap;
    }

    .top-bar a {
        color: white;
        text-decoration: none;
        font-weight: bold;
    }

    /* Header */
    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 20px;
        background-color: white;
        border-bottom: 1px solid #e0e0e0;
        flex-wrap: wrap;
        position: relative;
    }

    /* Logo */
    .logo img {
        height: 35px;
    }

    /* User Info */
    .user-container {
        position: relative;
    }

    .user-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 25px;
        cursor: pointer;
        background: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: background 0.2s;
    }

    .user-toggle:hover {
        background: #f0f0f0;
    }

    .user-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #b2bec3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: bold;
        color: white;
    }
    .user-icon img{
            width: 100%;
            height: 100%;
        }
    .user-name {
        font-size: 14px;
        color: #333;
        white-space: nowrap;
    }

    .arrow {
        font-size: 14px;
        color: #555;
        transition: transform 0.3s;
    }

    /* Dropdown Menu */
    .dropdown {
        display: none;
        position: absolute;
        top: 45px;
        right: 0;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 6px;
        overflow: hidden;
        min-width: 150px;
        z-index: 100;
    }

    .dropdown.active {
        display: block;
    }

    .dropdown a {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: black;
        font-size: 14px;
        transition: background 0.2s;
    }

    .dropdown a:hover {
        background: #f0f0f0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {

        /* Top Bar */
        .top-bar {
            flex-direction: row;
            padding: 10px;
        }

        /* Header */
        .header {
            flex-direction: row;
            padding: 10px;
        }

        .logo img {
            height: 30px;
        }

        /* User Info */
        .user-container {
            margin-top: 0px;


        }

        .user-toggle {
            padding: 6px 8px;
            gap: 5px;
        }

        .user-name {
            font-size: 12px;
        }

        /* Dropdown */
        .dropdown {
            top: 40px;
            right: auto;
            left: 50%;
            transform: translateX(-50%);
            min-width: 90%;
            text-align: center;
        }

        .dropdown a {
            padding: 12px;
        }
    }

    @media (max-width: 480px) {

        /* Smaller User Icon */
        .user-icon {
            width: 28px;
            height: 28px;
        }
        

        .user-name {
            font-size: 12px;
        }

        /* Dropdown Menu */
        .dropdown {
            min-width: 95%;
        }

        .dropdown a {
            font-size: 13px;
        }
    }

    #username {
        padding: 4px 10px;
        background-color: #34A8D8;
        color: #fff;
        border-radius: 50px;
        margin-left: 10px;
    }
</style> --}}
<style>
    body {
        font-family: Arial, sans-serif;
        padding-top: 120px; /* Adjust this based on combined height of .top-bar + .header */
    }

    /* Top Bar */
    .top-bar {
        background-color: #0B4C8D;
        color: white;
        padding: 8px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        flex-wrap: wrap;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1030;
    }

    .top-bar a {
        color: white;
        text-decoration: none;
        font-weight: bold;
    }

    /* Header */
    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 20px;
        background-color: white;
        border-bottom: 1px solid #e0e0e0;
        flex-wrap: wrap;
        position: fixed;
        top: 37px; 
        left: 0;
        width: 100%;
        z-index: 1020;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.05);
    }

    .logo img {
        height: 35px;
    }

    .user-container {
        position: relative;
    }

    .user-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 25px;
        cursor: pointer;
        background: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: background 0.2s;
    }

    .user-toggle:hover {
        background: #f0f0f0;
    }

    .user-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #b2bec3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: bold;
        color: white;
    }

    .user-icon img {
        width: 100%;
        height: 100%;
    }

    .user-name {
        font-size: 14px;
        color: #333;
        white-space: nowrap;
    }

    .arrow {
        font-size: 14px;
        color: #555;
        transition: transform 0.3s;
    }

    .dropdown {
        display: none;
        position: absolute;
        top: 45px;
        right: 0;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 6px;
        overflow: hidden;
        min-width: 150px;
        z-index: 100;
    }

    .dropdown.active {
        display: block;
    }

    .dropdown a {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: black;
        font-size: 14px;
        transition: background 0.2s;
    }

    .dropdown a:hover {
        background: #f0f0f0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .top-bar,
        .header {
            flex-direction: row;
            padding: 10px;
        }

        .logo img {
            height: 30px;
        }

        .user-toggle {
            padding: 6px 8px;
            gap: 5px;
        }

        .user-name {
            font-size: 12px;
        }

        .dropdown {
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            min-width: 90%;
            text-align: center;
        }

        .dropdown a {
            padding: 12px;
        }
    }

    @media (max-width: 480px) {
        .user-icon {
            width: 28px;
            height: 28px;
        }

        .user-name {
            font-size: 12px;
        }

        .dropdown {
            min-width: 95%;
        }

        .dropdown a {
            font-size: 13px;
        }
    }

    #username {
        padding: 4px 10px;
        background-color: #34A8D8;
        color: #fff;
        border-radius: 50px;
        margin-left: 10px;
    }

    .gradient-heading {
        background: linear-gradient(90deg, #1C5FA8, #34A8D8);
        -webkit-background-clip: text;
        color: transparent;
        font-size: 22px;
        font-weight: 600;
    }
</style>

<link href="{{ config('constant.BASE_URL') }}front/css/login.css" rel="stylesheet">
<div class="MainErrorBox" style="float: right;display:none;">
    <span class="error__icon">
        <i class="fa-solid fa-circle-exclamation"></i>
    </span>
    <p class="error__title mb-0" style="margin-right:10px;"></p>
    <span class="error__close">
        <i class="fa-solid fa-xmark"></i>
    </span>
</div>

<div class="MainverifiyedBox" style="float: right;display:none;">
    <span class="verifiy__icon">
        <i class="fa-solid fa-circle-check"></i>
    </span>
    <p class="verifiyed__title mb-0" style="margin-right:10px;"></p>
    <span class="verifiyed__close">
        <i class="fa-solid fa-xmark"></i>
    </span>
</div>

<!-- <header>
    <div id="tophead" class="container-fluid mb-2 shadow-sm">
        <div class="row py-1 align-items-center">
          
            <div class="col-6 d-flex justify-content-start">
                <div class="">
                    <a href="https://digibima.com" target="_blank">
                        <img src="{{ config('constant.BASE_URL') }}front/images/logo.png"
                            width="180"
                            class="img-fluid"
                            alt="Logo" />
                    </a>
                </div>
            </div>
        
            <div class="col-6 d-flex justify-content-end align-items-center">

                <a href="tel:+918058584972" class="d-flex align-items-center">
                    <span class="bi bi-telephone-outbound me-1"></span>
                    <span class="d-none d-sm-inline">+91 8058 584 972</span>
                </a>
                @auth
                <span id="username"> {{!empty(Auth::user()->name)?Auth::user()->name:Auth::user()->mobile}} </span>
                @endauth
                <div class="user me-3">
                    <button class="btn d-flex align-items-center" id="logout-dropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="fa-regular fa-user"></span>
                    </button>


                    <div class="dropdown-menu dropdown-menu-end border-0 shadow-sm"
                        aria-labelledby="logout-dropdown">
                        @auth
                        <a class="dropdown-item" href="{{ route('userroot') }}">Profile</a>
                        <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                        @endauth
                        @guest
                        <a class="dropdown-item" href="#" onclick="openModal();">Login</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</header> -->
<!-- Top Bar -->
<div class="top-bar">
    <span><i class="bi bi-envelope"></i> <a href="mailto:info@digibima.com">info@digibima.com</a></span>
    <span><i class="bi bi-telephone-outbound me-1"></i> <a href="tel:+919119173733">+91 9119 173 733</a></span>
</div>

<!-- Header -->
<div class="header">
    <div class="logo">
        <a href="https://digibima.com" target="_blank">
            <img src="{{ config('constant.BASE_URL') }}front/images/logo.png" class="img-fluid"
                alt="Logo" />
        </a>
        <!-- <img src="https://test.digibima.com//public/front/images/logo.png" alt="Digibima Logo"> -->
    </div>
    <div class="user-container">
        <div class="user-toggle" onclick="toggleDropdown()">
            <div class="user-icon"><img src="{{ config('constant.BASE_URL') }}front/images/user.png" alt=""></div>
            <!-- <div class="user-icon"><i class="bi bi-person-circle"></i></div> -->
           <!-- @auth 
           <span class="user-name">Hi, {{!empty(Auth::user()->name)?Auth::user()->name:Auth::user()->mobile}}!</span>
           @endauth -->
           @auth
                <span class="user-name">Hi, {{ Auth::user()->name ?? Auth::user()->mobile }}!</span>
            @endauth

            <span class="arrow">&#9662;</span>
        </div>
        <div id="dropdownMenu" class="dropdown">
                      @auth
                        <a class="dropdown-item" href="{{ route('userroot') }}">Profile</a>
                        <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                        @endauth
                        @guest
                        <a class="dropdown-item" href="#" onclick="openModal();">Login</a>
                        @endguest
        </div>
    </div>
</div>

<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog rounded-3">
        <div class="modal-content rounded-3">
            <div class="modal-header border-0">
                <!-- <h5 class="modal-title" id="exampleModalLabel">Sign in to DigiBima</h5> -->
                <!-- <p>Login using your mobile number</p> -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 " style="padding: 0px 20px;">
                        <img src="{{ config('constant.BASE_URL') }}front/images/logo.png" width="180" alt="Logo" />
                    </div>
                    <!-- <div class="col-lg-12 mb-2" style="padding: 0px 20px;">
                        
                    </div> -->
                    <div class="col-lg-12 mb-2">

                        <div class="mobilesection ">
                            <h2 class="gradient-heading" style="text-align: left!important;padding: 15px 20px;">Sign in </h2>
                            <!-- <p class="" style="text-align: left!important;">Login using your mobile number</p> -->
                            <div class="mobile-input mb-2">
                                <input type="text" id="mobileNumber" placeholder="Enter your mobile number"
                                    maxlength="10" oninput="validatePhoneNumber(event)" required>
                            </div>
                            <button class="getstartedOne" id="getOTP">Get OTP</button>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <div class="otpsection" style="display: none;">
                            <h2 class="gradient-heading">OTP Authentication</h2>
                            <p>Enter the 6 digit OTP sent to your mobile number.</p>
                            <div class="otp-input">
                                <input type="text" required maxlength="1" oninput="moveFocus(event, 1)"
                                    pattern="[0-9]*">
                                <input type="text" required maxlength="1" oninput="moveFocus(event, 2)"
                                    pattern="[0-9]*">
                                <input type="text" required maxlength="1" oninput="moveFocus(event, 3)"
                                    pattern="[0-9]*">
                                <input type="text" required maxlength="1" oninput="moveFocus(event, 4)"
                                    pattern="[0-9]*">
                                <input type="text" required maxlength="1" oninput="moveFocus(event, 5)"
                                    pattern="[0-9]*">
                                <input type="text" required maxlength="1" oninput="moveFocus(event, 6)"
                                    pattern="[0-9]*">
                            </div>
                            <p class="mb-0" id="timer"></p>
                            <div class="mb-2">
                                <p id="resend" style="font-size: 14px; display: none;">Didn't receive an OTP:
                                    <span><a href="#" id="resendgetOTP">Resend</a></span>
                                </p>

                            </div>
                            <button class="getstartedOne" id="loginverifyOTP">Verify</button>

                            <!-- <button id="resendButton" onclick="resendOTP()" disabled>Resend Code</button> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0"></div>
        </div>
    </div>
</div>


<!-- <script src="{{ config('constant.BASE_URL') }}front/js/login.js"></script> -->
<script>
    function toggleDropdown() {
        let dropdown = document.getElementById('dropdownMenu');
        let arrow = document.querySelector('.arrow');

        dropdown.classList.toggle('active');
        arrow.style.transform = dropdown.classList.contains('active') ? "rotate(180deg)" : "rotate(0deg)";
    }

    // Close dropdown when clicking outside
    document.addEventListener("click", function(event) {
        let userToggle = document.querySelector(".user-toggle");
        let dropdown = document.getElementById("dropdownMenu");

        if (!userToggle.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove("active");
            document.querySelector('.arrow').style.transform = "rotate(0deg)";
        }
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