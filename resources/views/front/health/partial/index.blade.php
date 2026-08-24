<?php
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Top Plans</title>
    @include('front.partial.csslink')
    <style>
        .cityList li:hover {
            background-color: #26A9E1;
        }

        .cityList li:hover a,
        .cityList li:hover .fa-location-dot {
            color: #fff;
        }

        .fa-location-dot {
            color: #0B4C8D;
            margin-right: 10px;
        }

        #timerAlert {
            font-size: 12px;
            font-weight: 500;
            color: Red;
        }

        .getotp {
            width: 180px;
            height: 35px;
            color: #fff;
            /* background: #1c5fa8; */
            background: linear-gradient(135deg, #4e54c8, #8f94fb) !important;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            font-family: "Poppins", sans-serif;
            opacity: 0.5;
        }

        .custom-button {
            text-decoration: none;
            background-color: white;
            color: black;
            font-size: 16px;
            font-weight: 600;
            width: 10rem;
            height: 2rem;
            border-radius: 1.25rem;
            font-family: sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            transition: background-color 0.3s ease;
            border: 1px solid #000;
        }

        .button-icon {
            color: #fff;
            /* background-color: #1C5FA8; */
             background: linear-gradient(135deg, #4e54c8, #8f94fb) !important;
            border-radius: 0.75rem;
            height: 1.5rem;
            width: 25%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            left: 0.25rem;
            top: 0.2rem;
            transition: width 0.5s ease;
            z-index: 10;
        }

        .conti {

            display: none;
            margin-right: 10px;
        }

        .custom-button:hover .button-icon {
            width: 9.3rem;
            color: #fff;
        }

        .custom-button:hover .conti {
            display: block;
            color: #fff;
        }

        .custom-button:hover .button-text {
            color: #000;
        }

        .button-text {
            margin-left: 0.5rem;
            color: #000;
        }

        .error {
            color: red;
        }

        .gender-box {
            width: 100px;
            height: 35px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .gender-label input[type="radio"] {
            display: none;
        }

        .gender-label input[type="radio"]:checked+.gender-box {
            background-color: #1C5FA8;
            color: #fff;
            /* border-color: #1C5FA8; */
            background: linear-gradient(135deg, #4e54c8, #8f94fb) !important;
            /* border-radius: 12px !important; */
        }

        #city-list {
            position: absolute;
            font-family: "Poppins", sans-serif;
            width: 275px;
            max-height: 120px;
            height: auto;
            overflow-y: scroll;
            background: #fff;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            z-index: 1;
        }

        .city {
            margin: 5px 0px;
        }

        ul {
            padding-left: 0rem;
        }

        .cityList {
            list-style: none;
        }

        .cityList li {
            padding: 8px 15px;
        }

        .cityList li a {
            text-decoration: none;
            color: #000;
        }

        .MainErrorBox,
        .MainverifiyedBox {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            position: fixed;
            right: 30px;
            top: 1%;
            width: auto;
            padding: 12px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: start;
            background: #EF665B;
            border-radius: 8px;
            box-shadow: 0px 0px 5px -3px #111;
            z-index: 100000;
        }

        .MainverifiyedBox {
            background: #4EB14F;
        }

        .error__icon,
        .verifiy__icon {
            width: 20px;
            height: 20px;
            transform: translateY(-2px);
            margin-right: 8px;
            color: #fff;
        }


        .error__title,
        .verifiyed__title {
            font-weight: 500;
            font-size: 14px;
            color: #fff;
        }

        .error__close,
        .verifiyed__close {
            width: 20px;
            height: 20px;
            cursor: pointer;
            margin-left: auto;
            color: #fff;
        }

        .fa-circle-exclamation {
            color: #fff;
        }

        .alredyacount {
            width: auto;
            background: #fff;
            padding: 0.3rem 0.5rem;
            border-radius: 10px;
        }

        .alredyacount a {
            text-decoration: none;
            color: #1C5FA8;
        }

        @media (max-width: 1200px) {
            #findtopplan .image {
                width: 100%;
            }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            main {
                padding: 2rem 2rem;
            }

            .gender-container {
                flex-direction: column;
                align-items: center;
            }

            .gender-box {
                width: 100px;
                text-align: center;
                margin-bottom: 0.5rem;
            }

            #findtopplan .image {
                width: 650px;
                max-width: 100%;
                height: auto;
            }
        }

        @media (min-width: 576px) and (max-width: 767.98px) {
            main {
                padding: 1.5rem;
            }

            .sidepera {
                margin-top: 1.8rem;
            }

            #findtopplan .image {
                width: 500px;
                max-width: 100%;
                height: auto;
            }
        }

        @media (min-width: 0px) and (max-width: 575.98px) {
            main {
                padding: 1rem;
            }

            .sidepera {
                margin-top: 1.8rem;
            }

            #findtopplan .image {
                width: 400px;
                max-width: 100%;
                height: auto;
            }

            .col-lg-6,
            .col-md-6,
            .col-sm-12 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* @media (max-width: 375px) {
            #findtopplan .image {
                width: 250px;
                max-width: 100%;
                height: auto;
            }

            .gender-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .gender-box {
                width: 100px;
                text-align: center;
                margin-bottom: 0.5rem;
            }

            .getstarted {
                width: 100%;
                padding: 0.15rem;
            }
        }

        @media (max-width: 320px) {
            .gender-box {
                font-size: 0.9rem;
            }

            .sidepera {
                font-size: 0.8rem;
            }

            .formbtn {
                font-size: 0.7rem;
            }
        } */
    </style>
</head>

<body>
    @include('front.partial.header')
    {{-- {{ $otp }} --}}
    @php

        // dd($name);
    @endphp
    <div class="MainErrorBox" style="float: right;display:none;"><span class="error__icon"><i
                class="fa-solid fa-circle-exclamation"></i></span>
        <p class="error__title  mb-0" style="margin-right:10px;">hello</p> <span class="error__close"><i
                class="fa-solid fa-xmark"></i></span>
    </div>
    <div class="MainverifiyedBox" style="float: right;display:none;"><span class="verifiy__icon"><i
                class="fa-solid fa-circle-check"></i></span>
        <p class="verifiyed__title  mb-0" style="margin-right:10px;">hello</p> <span class="verifiyed__close"><i
                class="fa-solid fa-xmark"></i></span>
    </div>
    <main id="slider-container">
        <section class="slide" id="findtopplan">
            {{-- <div class="row">
                <div class="col-md-3 alredyacount">
                    @auth
                        <a href="{{ route('insureview', ['id' => Auth::user()->id]) }}" class="custom-button">
                            <div class="button-icon">
                                <span class="conti">Continue</span><i class="fa-solid fa-arrow-right"></i>
                            </div>
                            <p class="button-text mb-0">{{ explode(' ', Auth::user()->name)[0] }}</p>
                        </a>
                    @endauth
                </div>
            </div> --}}
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 head text-center mb-2">
                        Find Top Plans For You
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                        <div class="image">
                            <img src="{{ config('constant.BASE_URL') }}front/images/DIGIBIMA-2.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('insureview') }}" method="POST" id="indexformID" name="indexForm">
                            @csrf
                            <div class="row">
                                <!-- <div class="col-lg-12 col-md-12 col-sm-12">
                                    <p class="sidepera mb-1">Lorem ipsum dolor sit amet consectetur dolor sit</p>
                                </div> -->
                                <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                                    <div class="gender-container">
                                        <label class="gender-label">
                                            <input type="radio" name="gender" value="male"
                                                {{ $data['gender'] == 'male' ? 'checked' : '' }}>
                                            <div class="gender-box">Male</div>
                                        </label>
                                        <label class="gender-label">
                                            <input type="radio" name="gender" value="female"
                                                {{ $data['gender'] == 'female' ? 'checked' : '' }}>
                                            <div class="gender-box">Female</div>
                                        </label>
                                    </div>
                                    @error('gender')
                                        <div class="error" id="genderError">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                    <div class="name mb-4">
                                        <label for="findname">Name</label>
                                        <input type="text" id="findname" name="name"
                                            value="{{ old($data['name']) ?? $data['name'] }}"
                                            placeholder="Enter Full Name">
                                    </div>
                                    @error('name')
                                        <div class="error" id="nameError">{{ $message }}</div>
                                    @enderror
                                    {{-- <div class="email mb-2">
                                        <label for="findemail">Email</label>
                                        <input type="email" id="findemail" name="email" value="{{ old('email') }}"
                                            placeholder="Enter Email-id">
                                    </div>
                                    @error('email')
                                        <div class="error" id="emailError">{{ $message }}</div>
                                    @enderror --}}
                                    <div class="pincode mb-2">
                                        <label for="findpincode">Pincode</label>
                                        <input type="text" id="findpincode" name="pincode" class="pincodesixInput"
                                            value="{{ old($data['pincode']) ?? $data['pincode'] }}"
                                            placeholder="Enter Pincode" oninput="acpincode(this)">
                                        <div id="city-list" class="mb-5"></div>
                                    </div>
                                    @error('pincode')
                                        <div class="error" id="pincodeError">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2">

                                    {{-- <div class="mobile">
                                      

                                        @if ($otp == 1)
                                            <label for="findmobile">
                                                Mobile Number
                                                <span class="editMobile" title="Edit Your Mobile Number"
                                                    onclick="editMobileNumber('findmobile', 'verifyButton')"
                                                    style="cursor: pointer;">
                                                    <i class="fa-solid fa-pen"></i>
                                                </span>
                                            </label>

                                            <input type="text" id="findmobile" name="mobile" class="mobiletenInput"
                                                value="{{ $mobile }}" placeholder="Enter Mobile Number"
                                                oninput="validateInput(this, 10, 'verifyButton')" maxlength="10"
                                                style="width:65%" readonly>
                                            
                                            <button type="button" id="verifyButton" class="getotp"
                                                style="width:30%;background: #1C5FA8;padding:0px;" disabled
                                                onclick="editMobileNumber()">Verified</button>
                                            <span id="timerAlert" style="display: none">HelLo World</span>
                                        @else
                                            <label for="findmobile">Mobile Number</label>
                                            <input type="text" id="findmobile" name="mobile"
                                                class="mobiletenInput" placeholder="Enter Mobile Number"
                                                oninput="validateInput(this, 10, 'verifyButton')" maxlength="10"
                                                style="width:65%;">
                                            <button type="button" id="verifyButton" class="getotp"
                                                style="width:30%;background: #1C5FA8;padding:0px" disabled
                                                onclick="editMobileNumber()">Verify</button>
                                            <span id="timerAlert" style="display: none">HelLo World</span>
                                        @endif
                                    </div> --}}
                                    <div class="mobile">
                                        {{-- <label for="findmobile">Mobile Number</label>
                                        <input type="text" id="findmobile" name="mobile" class="mobiletenInput"
                                            value="{{ old('mobile') }}" placeholder="Enter Mobile Number"
                                            oninput="validateInput(this, 10,'verifyButton')" maxlength="10" required
                                            style="width:65%">

                                        <button type="button" id="verifyButton" class="getotp"
                                            style="width:30%;background: #1C5FA8;padding:0px" disabled
                                            onclick="showknowcarOtpSection()">Verify</button>
                                        <span id="timerAlert" style="display: none">HelLo World</span> --}}



                                        {{-- @if ($otp == 1) --}}
                                        <label for="findmobile">
                                            Mobile Number
                                            {{-- <span class="editMobile" title="Edit Your Mobile Number"
                                                onclick="editMobileNumber('findmobile', 'verifyButton')"
                                                style="cursor: pointer;">
                                                <i class="fa-solid fa-pen"></i>
                                            </span> --}}
                                        </label>

                                        <input type="text" id="findmobile" name="mobile" class="mobiletenInput"
                                            value="{{ $data['mobile'] }}" placeholder="Enter Mobile Number"
                                            oninput="validateInput(this, 10, 'verifyButton')" maxlength="10"
                                            style="width:65%" {{ !empty($mobile) ? 'readonly' : '' }}>
                                        {{-- <input type="text" id="findmobile" name="mobile" class="mobiletenInput"
                                                value="{{ 'XXXXXX' . substr($mobile, -4) }}"
                                                placeholder="Enter Mobile Number"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateInput(this, 10, 'verifyButton')"
                                                maxlength="10" style="width:65%" readonly> --}}
                                        <button type="button" id="verifyButton" class="getotp"
                                            style="width:30%; background: linear-gradient(135deg, #4e54c8, #8f94fb) !important;padding:0px;"
                                            {{ !empty($mobile) ? 'disabled' : 'enabled' }}
                                            onclick="editMobileNumber()">{{ !empty($mobile) ? 'Verified' : 'Verify' }}</button>
                                        <span id="timerAlert" style="display: none">HelLo World</span>
                                        {{-- @else --}}

                                    </div>
                                    @error('mobile')
                                        <div class="error" id="mobileError">{{ $message }}</div>
                                    @enderror
                                    <div class="otp mb-2" id="otpSection" style="display: none;">
                                        <label for="otpmobile">Enter OTP</label>
                                        <input type="text" id="otpmobile" name="otp" class="mobileotpInput"
                                            value="" placeholder="Enter OTP" maxlength="6"
                                            style="width:70%;">
                                        {{-- <button class="getotp" style="width:25%;">Submit</button> --}}
                                        <input type="button" id="submitOtp" class="getotp"
                                            style="width:25%; background: linear-gradient(135deg, #4e54c8, #8f94fb) !important;opacity:1; padding:0px"
                                            value="Submit">
                                    </div>
                                </div>
                                <div class="d-none">
                                    <input type="submit" id="getstarted">
                                </div>
                            </div>
                        </form>
                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                            <input type="submit" id="mainsubmit" class="getstarted" value="Get Started"
                                style="opacity: 0.5;" disabled onClick="handleGetStarted();">
                            <p class="formbtn">Already bought a policy from DigiBima? <a href="#">Renew Now</a>
                            </p>
                        </div>
                    </div>
                </div>
        </section>
    </main>
    @include('front.partial.chatwidget')
    @include('front.partial.footer')
    @include('front.partial.jslink')
    <script>
        const errorBox = document.querySelector('.MainErrorBox');
        const errorTitleElement = errorBox?.querySelector('.error__title');
        const successBox = document.querySelector('.MainverifiyedBox');
        const successTitleElement = successBox.querySelector('.verifiyed__title');

        function displayAlert(message) {
            errorTitleElement.innerText = message;
            errorBox.style.display = 'flex';
            setTimeout(() => {
                errorBox.style.display = 'none';
            }, 3000);
        }

        function displaySuccess(messageverifiy) {
            successTitleElement.innerText = messageverifiy;
            successBox.style.display = 'flex';
            setTimeout(() => {
                successBox.style.display = 'none';
            }, 3000);
        }

        let resendTimer;
        let otpSent = false;
        let smobile = "";

        // document.getElementById('verifyButton').addEventListener('click', function() {
        //     if (!otpSent) {
        //         sendOTP(document.getElementById('findmobile').value);
        //     }
        // });

        document.getElementById('verifyButton').addEventListener('click', function() {
            const mobile = document.getElementById('findmobile').value;
            if (!/^[6-9]\d{9}$/.test(mobile)) {
                errorTitleElement.innerText = `Please enter a valid 10-digit Mobile Number.`;
                document.getElementById('findmobile').focus();
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                errorBox.style.display = "flex";
                return;
            }
            if (!otpSent) {
                sendOTP(mobile);
            }
        });


        function sendOTP(mobile) {
            const timerAlert = document.getElementById('timerAlert');
            const sendOtpButton = document.getElementById('verifyButton');
            smobile = mobile;

            if (resendTimer) {
                clearInterval(resendTimer);
            }

            sendOtpButton.disabled = true;

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
                .then(response => response.json())
                .then(data => {
                    showOtpSection();
                    sendOtpButton.style.display = 'none';
                    timerAlert.style.display = 'block';

                    let timeLeft = 20;
                    timerAlert.innerHTML = `Resend in ${timeLeft} seconds`;

                    resendTimer = setInterval(function() {
                        timeLeft--;
                        timerAlert.innerHTML = `Resend in ${timeLeft} seconds`;
                        if (timeLeft <= 0) {
                            clearInterval(resendTimer);
                            timerAlert.style.display = 'none';
                            sendOtpButton.innerText = 'Resend';
                            sendOtpButton.style.display = 'inline';
                            sendOtpButton.disabled = false;
                        }
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error sending OTP:', error);
                    sendOtpButton.disabled = false;
                    sendOtpButton.innerText = 'Resend';
                });
        }

        document.getElementById('submitOtp').addEventListener('click', function() {
            verifyOTP(document.getElementById('otpmobile').value);
        });

        function verifyOTP(otp) {
            fetch("{{ route('verifyotp') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        'otp': otp,
                        'mobile': smobile
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.status === '1') {
                        displaySuccess('OTP verified successfully!');
                        clearInterval(resendTimer);
                        document.getElementById('timerAlert').style.display = 'none';
                        document.getElementById('findmobile').readOnly = true;
                        const verifyButton = document.getElementById('verifyButton');
                        verifyButton.style.display = 'inline';
                        verifyButton.innerHTML = 'Verified';
                        verifyButton.style.opacity = '0.5';
                        verifyButton.disabled = true;

                        document.getElementById('otpSection').style.display = 'none';

                        const mainsubmitButton = document.getElementById('mainsubmit');
                        if (mainsubmitButton) {
                            mainsubmitButton.disabled = false;
                            mainsubmitButton.style.opacity = '1';
                        } else {
                            console.error('Main submit button not found');
                        }
                    } else {
                        displayAlert('Invalid OTP. Please try again.');
                        document.getElementById('otpSection').style.display = 'block';
                        const mainsubmitButton = document.getElementById('mainsubmit');
                        if (mainsubmitButton) {
                            mainsubmitButton.disabled = true;
                            mainsubmitButton.style.opacity = '0.5';
                        } else {
                            console.error('Main submit button not found');
                        }
                    }
                })
                .catch(error => {
                    displayAlert('An error occurred. Please try again.');
                    document.getElementById('otpSection').style.display = 'block';
                    const mainsubmitButton = document.getElementById('mainsubmit');
                    if (mainsubmitButton) {
                        mainsubmitButton.disabled = true;
                        mainsubmitButton.style.opacity = '0.5';
                    } else {
                        console.error('Main submit button not found');
                    }
                });
        }

        const verifyMobNo = @json($data['mobile']);

        function validateInput(inputElement, maxLength, buttonId) {
            const pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');


            const MobileInput = inputElement;
            const verifyButton = document.getElementById(buttonId);
            const mainsubmitButton = document.getElementById('mainsubmit');

            // Ensure the input contains only numbers and truncate to maxLength
            if (!pattern.test(inputElement.value)) {
                inputElement.value = inputElement.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }

            // Check if the length and value are correct
            if (MobileInput.value.length === maxLength && parseInt(MobileInput.value, 10) === verifyMobNo) {
                verifyButton.disabled = true;
                verifyButton.innerHTML = 'Verified'; // Change button text using innerHTML
                verifyButton.style.opacity = '0.5';

                // Enable the main submit button
                mainsubmitButton.disabled = false;
                mainsubmitButton.style.opacity = '1';

                return false; // Stop further execution
            } else {
                // Reset button states if condition is not met
                verifyButton.disabled = false;
                verifyButton.textContent = 'Verify';
                mainsubmitButton.disabled = true;
                mainsubmitButton.style.opacity = '0.5';
            }

            toggleVerifyButton(buttonId, MobileInput.value.length === maxLength);
        }

        function toggleVerifyButton(buttonId, isEnabled) {
            const button = document.getElementById(buttonId);
            if (button.textContent === 'Verified') {
                button.disabled = true;
                return;
            }
            button.disabled = !isEnabled;
            button.style.opacity = isEnabled ? '1' : '0.5';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const verifyStatus = {{ $data['otp'] }}; // Ensure this is the correct value
            const mainsubmitButton = document.getElementById('mainsubmit');
            if (verifyStatus === 1) {
                verifyButton.textContent = 'Verified';
                verifyButton.disabled = true;
                mainsubmitButton.disabled = false;
                mainsubmitButton.style.opacity = '1';
                showVerified('Your mobile number is already verified.');
                return;
            }
        });

        function editMobileNumber(mobileId, btnId) {
            const mobileInput = document.getElementById(mobileId);
            const verifyButton = document.getElementById(btnId);

            mobileInput.value = '';
            mobileInput.removeAttribute('readonly');

            verifyButton.disabled = false;
            verifyButton.innerHTML = 'Verify';

            mobileInput.focus();

            mobileInput.addEventListener('input', function() {
                if (typeof verifyMobNo === 'string' && verifyMobNo.trim() === mobileInput.value.trim()) {
                    verifyButton.textContent = 'Verified';
                    verifyButton.style.opacity = '0.5';
                    verifyButton.disabled = true;
                } else {
                    verifyButton.textContent = 'Verify';
                    verifyButton.disabled = false;
                }
            });
        }



        // Show OTP section
        function showOtpSection() {
            const otpSection = document.getElementById('otpSection');
            otpSection.style.display = 'block';
        }

        function showVerified(message) {
            const verifiedBox = document.querySelector('.MainverifiyedBox');
            const verifiedTitle = verifiedBox?.querySelector('.verifiyed__title');

            if (verifiedBox && verifiedTitle) {
                verifiedBox.style.display = "flex";
                verifiedTitle.innerText = message;
                setTimeout(() => {
                    verifiedBox.style.display = 'none';
                }, 3000);
            }
        }

        function handleGetStarted() {
            const name = document.getElementById('findname').value.trim();
            const mobile = document.getElementById('findmobile').value.trim();
            const pincodeinput = document.getElementById('findpincode').value.trim();
            const genderMale = document.querySelector('input[name="gender"][value="male"]:checked');
            const genderFemale = document.querySelector('input[name="gender"][value="female"]:checked');



            if (!genderMale && !genderFemale) {
                errorTitleElement.innerText = `Please select your gender.`;
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                errorBox.style.display = "flex";
                return;
            }

            if (name === '') {
                errorTitleElement.innerText = `Please enter your Name.`;
                document.getElementById('findname').focus();
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                errorBox.style.display = "flex";
                return;
            } else if (!/^[a-zA-Z\s]+$/.test(name)) {
                errorTitleElement.innerText = `Please enter a valid Name (only alphabets).`;
                document.getElementById('findname').focus();
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                errorBox.style.display = "flex";
                return;
            }

            if (mobile.length !== 10 || !/^[6-9]\d{9}$/.test(mobile)) {
                errorTitleElement.innerText = `Please enter a valid 10-digit Mobile Number.`;
                document.getElementById('findmobile').focus();
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                errorBox.style.display = "flex";
                return;
            }

            // if (mobile.length !== 10 || !/^\d+$/.test(mobile)) {
            //     errorTitleElement.innerText = `Please enter a valid 10-digit Mobile Number.`;
            //     document.getElementById('findmobile').focus();
            //     setTimeout(() => {
            //         errorBox.style.display = 'none';
            //     }, 3000);
            //     errorBox.style.display = "flex";
            //     return;
            // }

            const pincodeMatch = pincodeinput.match(/^\d{6}/);
            if (pincodeMatch) {
                const pincode = pincodeMatch[0];
                // if (pincode.length === 6 && /^\d{6}$/.test(pincode)) {
                //     console.log('Valid pincode:', pincode);
                // } 
                if (pincode.length === 6 && /^[1-9]\d{5}$/.test(pincode)) {
                    console.log('Valid pincode:', pincode);
                } else {
                    errorTitleElement.innerText = `Please enter a valid 6-digit Pincode.`;
                    document.getElementById('findpincode').focus();
                    setTimeout(() => {
                        errorBox.style.display = 'none';
                    }, 3000);
                    errorBox.style.display = "flex";
                    return;
                }
            } else {
                errorTitleElement.innerText = `Please enter a Pincode in the correct format.`;
                document.getElementById('findpincode').focus();
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                errorBox.style.display = "flex";
                return;
            }

            document.getElementById('getstarted').click();
            console.log('Form submission started');
        }

        function acpincode(element) {
            const cityListDiv = document.getElementById("city-list");
            const pincodeInput = document.getElementById("findpincode");
            const maxLength = 6;

            pincodeInput.value = pincodeInput.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            const pincode = pincodeInput.value;

            if (/^\d{5,}$/.test(pincode)) {
                var sUrl = "{{ route('acpincode') }}";
                fetch(sUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            pincode: pincode,
                            _token: '{{ csrf_token() }}'
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        cityListDiv.innerHTML = '';
                        if (Object.keys(data).length > 0) {
                            let htmlContent = '<ul class="cityList">';
                            for (const key in data) {
                                if (data.hasOwnProperty(key)) {
                                    const cityText = data[key] ? `(${data[key]})` : '';
                                    htmlContent +=
                                        `<li><a href="#" class="city-link" data-pincode="${key}" data-city="${data[key]}"><span><i class="fa-solid fa-location-dot ml-2"></i></span>${key} ${cityText}</a></li>`;
                                }
                            }
                            htmlContent += '</ul>';
                            cityListDiv.innerHTML = htmlContent;

                            document.querySelectorAll('.city-link').forEach(link => {
                                link.addEventListener('click', function(event) {
                                    event.preventDefault();

                                    const continueButton = document.getElementById('mainsubmit');
                                    const pincode = this.getAttribute('data-pincode');
                                    const city = this.getAttribute('data-city');
                                    const finalValue = pincode + (city ? ` (${city})` : '');
                                    pincodeInput.value = finalValue;

                                    checkInput();
                                    cityListDiv.style.display = 'none';
                                    continueButton.classList.remove('disabled');
                                    continueButton.disabled = false;
                                });
                            });
                        } else {
                            cityListDiv.innerHTML = '<p>No cities found for this pincode.</p>';

                            const continueButton = document.getElementById('mainsubmit');
                            continueButton.classList.add('disabled');
                            continueButton.disabled = true;
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        cityListDiv.innerHTML =
                            '<p>An error occurred while fetching the data. Please try again later.</p>';

                        const continueButton = document.getElementById('mainsubmit');
                        continueButton.classList.add('disabled');
                        continueButton.disabled = true;
                    });
            }
        }

        function checkInput() {
            var cityInput = document.getElementById('findpincode');
            var continueButton = document.getElementById('citycontinue');
            if (cityInput && continueButton) {
                if (cityInput.value.trim().length >= 6) {
                    continueButton.classList.remove('disabled');
                    continueButton.disabled = false;
                } else {
                    continueButton.classList.add('disabled');
                    continueButton.disabled = true;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var cityInput = document.getElementById('findpincode');
            if (cityInput) {
                cityInput.addEventListener('input', checkInput);
            }
            checkInput();
        });

        document.getElementById("findpincode").addEventListener('input', function() {
            const cityListDiv = document.getElementById("city-list");
            const pincode = this.value;

            if (pincode.length > 4) {
                cityListDiv.style.display = 'block';
            } else {
                cityListDiv.innerHTML = '';
                cityListDiv.style.display = 'none';
            }
        });

        function isNumber(inputElement, maxlen) {
            const sValue = inputElement.value;
            const nLen = maxlen;

            if (sValue.length > nLen) {
                inputElement.value = sValue.substring(0, nLen);
            }
            inputElement.value = inputElement.value.replace(/\D/g, "");
        }
    </script>
</body>

</html>
