<?php
use App\Models\Proposal;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script> -->

<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- <link rel="shortcut icon" href="Icons/CELITIX FAVICON2.png" type="image/x-icon"> -->

<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"
    rel="stylesheet"> -->

<!-- <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> -->

<!-- Google tag (gtag.js) -->
<!-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-N25JVKV4MH"></script> -->
<!-- <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());

    gtag('config', 'G-N25JVKV4MH');
</script> -->

<!-- <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet"> -->
<!-- <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
    rel="stylesheet"> -->

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" /> -->

<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> -->
 <style>
input::placeholder {
  color:#B2B4B5 !important;
}

 </style>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @include('front.partial.csslink')
    <link href="{{ config('constant.BASE_URL') }}front/css/login.css" rel="stylesheet">

</head>

<body>
    @include('front.partial.header')
   
    <div id="loader">
        <div class="loader-container">
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
            <div class="dot dot-3"></div>
            <div class="dot dot-4"></div>
        </div>
    </div>


    <div class="MainErrorBox" style="float: right;display:none;" id="errorBox1">
        <span class="error__icon"><i class="fa-solid fa-circle-exclamation"></i></span>
        <p class="error__title  mb-0" style="margin-right:10px;">hello</p> <span class="error__close"><i
                class="fa-solid fa-xmark"></i></span>
    </div>

    <div class="MainErrorBox mainErrorBox2" style="float: right;display:none;" id="errorBox2">
        <span class="error__icon"><i class="fa-solid fa-circle-exclamation"></i></span>
        <p class="error__title  mb-0" style="margin-right:10px;">hello</p> <span class="error__close"><i
                class="fa-solid fa-xmark"></i></span>
    </div>


    <div id="" class="login-container">
        <div class="container" id="formhide">
            <div class="row px-0 py-0">
                <div class="col-md-6 col-lg-6 thmfont" style="display: block;">
                    <div class="">
                        <div class="formcol form-signin" id="loginForm" style="display:block;">
                            <form action="javascript:void(0);" return false;>
                                <div class="form-col-mid text-center py-3">

                                    <h1 class="head  thmfont"> <span class="redtxt head-txt"> Sign In</span>
                                    </h1>
                                    
                                </div>
                                <input type="text" class="form-control validate email" id="email"
                                    placeholder="Enter Mobile Number" name="email">
                            

                                <div class="input-group">

                                    <input type="password" class="form-control validate " id="password-demo"
                                        placeholder="Enter OTP" name="password" maxlength="8">

                                    <div class="input-group-append" style="display: block;">
                                        <span class="input-group-text"><i class="bi bi-eye-fill" style="font-size: 16px"
                                                onclick="togglePasswordVisibility()"></i></span>
                                    </div>

                                </div>

                                <!-- <div class="row w-100 my-2">
                                    <div
                                        class="col-md-12 col-lg-12   align-items-center justify-content-end d-flex thmfont">
                                        <a href="forgot-password.php"
                                            style="color:#000; text-decoration:none; font-size: 14px; ">Forgot
                                            Password?</a>
                                    </div>
                                </div> -->

                                <div class="mt-0">
                                    <div class="cf-turnstile" data-sitekey="0x4AAAAAAAyeN-Atpt0ypkQQ"
                                        data-theme="light"></div>
                                </div>


                                <input type="submit" class="btn thmbtn" value="Submit">
                            </form>
                        </div>
                    </div>
                    <div>
                        <div class="formcol text-center " id="mobileInputSection" style="display:none;">
                            <form id="mobileForm" class="form-mobile" action="javascript:void(0);"
                                onsubmit="requestOTP(); return false;">
                                <h2 class="head ">Sign In</h2>
                                <h6 class="head ">Enter Your Registered Mobile Number </h6>
                                <!-- <input type="tel" id="mobile" name="mobile" pattern="0" min="0" max="9999999999" required><br> -->
                                <input type="tel" id="mobile" class="form-control d-inline-block" name="mobile"
                                    pattern="[0-9]*" maxlength="10" style="width: 80%;" placeholder="Mobile Number"
                                    required>
                                <button type="button" class="btn thmbtn" id="requestOTPButton" disabled>Request
                                    OTP</button>
                            </form>
                        </div>
                    </div>
                    <div>
                        <form class="formcol text-center" id="otpForm" style="display: none;">
                            <div class="form-otp">
                                <h2 class="head ">Sign In</h2>
                                <h6 class="head ">Enter the OTP </h6>
                                
                                <div class="modal-confirm">
                                    <div class="modal-body">
                                        <div class="otp-inputs">
                                            <input type="text" class="otp-input" id="otp1" maxlength="1"
                                                pattern="[0-9]*" />
                                            <input type="text" class="otp-input" id="otp2" maxlength="1"
                                                pattern="[0-9]*" />
                                            <input type="text" class="otp-input" id="otp3" maxlength="1"
                                                pattern="[0-9]*" />
                                            <input type="text" class="otp-input" id="otp4" maxlength="1"
                                                pattern="[0-9]*" />
                                            <input type="text" class="otp-input" id="otp5" maxlength="1"
                                                pattern="[0-9]*" />
                                            <input type="text" class="otp-input" id="otp6" maxlength="1"
                                                pattern="[0-9]*" />
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-around my-2 w-100">

                                    <button type="button" id="verifyOTPButton" class="btn thmbtn">Verify OTP</button>
                                    <button type="button" id="resendOTPButton" class="btn thmbtn"
                                        style="display:none;">Resend
                                        OTP</button>
                                </div>
                                <div id="countdown">Resend OTP in 20 seconds</div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="right-container thmfont">
                    <a href="https://digibima.com" target="_blank">
                    <img src="https://insurance.digibima.com//public/front/images/logo.png" class="mb-2 mt-1" width="250"
                    alt="Logo">
                    </a>
                        <p>
                            Welcome to the Future of Customer Communication - Your Engagement Journey Begins Here.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('front.partial.footer')
    @include('front.partial.jslink')


    <script>
                   window.addEventListener("load", function() {
            document.getElementById("loader").style.display = "none";
        });

        window.onbeforeunload = function() {
            document.getElementById("loader").style.display = "flex";
        };

        window.addEventListener("pageshow", function(event) {
            if (event.persisted) {
                document.getElementById("loader").style.display = "none";
            }
        });

    </script>
</body>

</html>
