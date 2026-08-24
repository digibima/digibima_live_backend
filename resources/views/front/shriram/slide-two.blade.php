<?php
use App\Models\Proposal;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slide Two</title>
    @include('front.partial.csslink')
    <style>
        .getotp {
            width: 180px;
            height: 35px;
            color: #fff;
            background: #1c5fa8;
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
            background-color: #1C5FA8;
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

        .vehicle-box {
            width: 120px;
            height: 35px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .vehicle-label input[type="radio"] {
            display: none;
        }

        .vehicle-label input[type="radio"]:checked+.vehicle-box {
            background-color: #1C5FA8;
            color: #fff;
            border-color: #1C5FA8;
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

        .MainErrorBox {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            position: fixed;
            top: 10px;
            right: 10px;
            width: auto;
            padding: 12px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: start;
            background: #EF665B;
            border-radius: 8px;
            box-shadow: 0px 0px 5px -3px #111;
            z-index: 1000;
        }

        .error__icon {
            width: 20px;
            height: 20px;
            transform: translateY(-2px);
            margin-right: 8px;
            color: #fff;
        }


        .error__title {
            font-weight: 500;
            font-size: 14px;
            color: #fff;
        }

        .error__close {
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

            .vehicle-container {
                flex-direction: column;
                align-items: center;
            }

            .vehicle-box {
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

        .motoricon {
            margin-left: 20px !important;
        }
    </style>
</head>

<body>
    @include('front.partial.header')
    <div class="MainErrorBox" style="float: right;display:none;"><span class="error__icon"><i
                class="fa-solid fa-circle-exclamation"></i></span>
        <p class="error__title mb-0 " style="margin-right:10px;">Self cannot be combined with Father or Mother.</p><span
            class="error__close "><i class="fa-solid fa-xmark  mr-3"></i></span>
    </div>
    <main id="slider-container">
        <section class="slide" id="findtopplan">
            <div class="row">
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
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 head text-center">
                        Motor insurance provides essential coverage against accidents.
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="image">
                            <img src="{{ config('constant.BASE_URL') }}front/images/DIGIBIMA-2.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('insureview') }}" method="POST" id="indexformID" name="indexForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                    <p class="sidepera mb-1">Slide two</p>

                                    <div class="row " id="newsection">

                                        <div class="row" id="knowcarrow">
                                            <p class="sidepera mb-1 mt-3">Insure New</p>
                                            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                                <div class="havevehnumb mb-2">
                                                    <label for="havevehtype">Manufacture</label>
                                                    <select name="havevehtype" id="havevehtype">
                                                        <option value="">Manufacture Vehicle</option>
                                                        <option value="pccv">PCCV</option>
                                                        <option value="gccv">GCCV</option>
                                                    </select>
                                                </div>
                                                @error('carnumber')
                                                    <div class="error" id="carnumberError">{{ $message }}</div>
                                                @enderror
                                                <div class="havevehnumb">
                                                    <label for="havevehtype">Manufacture</label>
                                                    <div class="input-group1 datepickerdiv readonly">
                                                        <input type="text" name="customerpancardDob"
                                                            class="input form-control datepicker"
                                                            id="customerpancardDob" autocomplete="off"
                                                            placeholder="Register Date" spellcheck="false"
                                                            maxlength="10"
                                                            oninput="clearErrorOne('customerpancardDobError')"
                                                            onclick="clearErrorOne('customerpancardDobError')">

                                                        <button class="btn calendarButton" type="button"
                                                            style="height: 46px">
                                                            <i class="fa-solid fa-calendar-days"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                                <div class="havevehnumb mb-2">
                                                    <label for="havevehtype">Model & Variant</label>
                                                    <select name="havevehtype" id="havevehtype">
                                                        <option value="">Model Vehicle</option>
                                                        <option value="pccv">PCCV</option>
                                                        <option value="gccv">GCCV</option>
                                                    </select>
                                                </div>
                                                @error('knowcarmobile')
                                                    <div class="error" id="mobileError">{{ $message }}</div>
                                                @enderror
                                                <div class="havevehnumb mb-2">
                                                    <label for="havevehtype">Year Of Manufacture</label>
                                                    <select name="havevehtype" id="havevehtype">
                                                        <option value="">Model Vehicle</option>
                                                        <option value="pccv">PCCV</option>
                                                        <option value="gccv">GCCV</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-5">
                                                    <div>
                                                        <input type="radio" name="knowcar" value="knowcar" checked>
                                                        <label for="knowcar">Select Register Under</label>
                                                    </div>

                                                </div>
                                                <div class="col-lg-6">
                                                    <div>
                                                        <input type="radio" name="knowcar" value="knowcar">
                                                        <label for="knowcar">Individual Company</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="dontknowcarrow" style="display: none">
                                            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                                <div class="carnumber mb-2">
                                                    <label for="dontknowcarcity">City Name</label>
                                                    <input type="text" id="dontknowcarcity" name="dontknowcarcity"
                                                        value="" placeholder="Enter City Name">
                                                </div>
                                                @error('dontknowcarcity')
                                                    <div class="error" id="dontknowcarcityError">{{ $message }}
                                                    </div>
                                                @enderror

                                                <div class="otp mb-2" id="dontknowcarotpSection"
                                                    style="display: none;">
                                                    <label for="dontknowcarotpmobile">Enter OTP</label>
                                                    <input type="text" id="dontknowcarotpmobile" name="otp"
                                                        class="mobileotpInput" value="" placeholder="Enter OTP"
                                                        oninput="validatedontknowCarOtpInput(this, 6)" maxlength="6"
                                                        required style="width:70%;">
                                                    <button class="getotp dontknowcargetotp"
                                                        style="width:25%;">Submit</button>
                                                </div>

                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                                <div class="mobile mb-2">
                                                    <label for="dontknowcarmobile">Mobile Number</label>
                                                    <input type="text" id="dontknowcarmobile"
                                                        name="dontknowcarmobile" class="mobiletenInput"
                                                        value="{{ old('dontknowcarmobile') }}"
                                                        placeholder="Enter Mobile Number"
                                                        oninput="validatedontknowcarInput(this, 10)" maxlength="10"
                                                        required style="width:70%;">
                                                    <button id="verifydontcarButton" class="getotp"
                                                        style="width:25%;" disabled
                                                        onclick="showdontknowcarOtpSection()">Verify</button>
                                                </div>
                                                @error('dontknowcarmobile')
                                                    <div class="error" id="dontknowcarmobileError">{{ $message }}
                                                    </div>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="row" id="newcarrow" style="display: none">
                                            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                                <div class="carnumber mb-2">
                                                    <label for="newcarcity">City Name</label>
                                                    <input type="text" id="newcarcity" name="newcarcity"
                                                        value="" placeholder="Enter City Name">
                                                </div>
                                                @error('newcarcity')
                                                    <div class="error" id="newcarcityError">{{ $message }}</div>
                                                @enderror

                                                <div class="otp mb-2" id="newcarotpSection" style="display: none;">
                                                    <label for="newcarotpmobile">Enter OTP</label>
                                                    <input type="text" id="newcarotpmobile" name="otp"
                                                        class="mobileotpInput" value="" placeholder="Enter OTP"
                                                        oninput="validateNewCarOtpInput(this, 6)" maxlength="6"
                                                        required style="width:70%;">
                                                    <button class="getotp newcargetotp"
                                                        style="width:25%;">Submit</button>
                                                </div>

                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                                <div class="mobile mb-2">
                                                    <label for="newcarmobile">Mobile Number</label>
                                                    <input type="text" id="newcarmobile" name="newcarmobile"
                                                        class="mobiletenInput" value="{{ old('newcarmobile') }}"
                                                        placeholder="Enter Mobile Number"
                                                        oninput="validateNewcarInput(this, 10)" maxlength="10"
                                                        required style="width:70%;">
                                                    <button id="verifynewcarButton" class="getotp" style="width:25%;"
                                                        disabled onclick="showNewcarOtpSection()">Verify</button>
                                                </div>
                                                @error('newcarmobile')
                                                    <div class="error" id="newcarmobileError">{{ $message }}</div>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-none" id="dontknowsection">

                                        <div class="row" id="dontknowrow">
                                            <p class="sidepera mb-1 ">I dont't know Vehicle</p>
                                            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                                <div class="havevehnumb mb-2">
                                                    <label for="dontknowmanuveh">Manufacture</label>
                                                    <select name="dontknowmanuveh" id="dontknowmanuveh">
                                                        <option value="">Manufacture Vehicle</option>
                                                        <option value="pccv">PCCV</option>
                                                        <option value="gccv">GCCV</option>
                                                    </select>
                                                </div>
                                                @error('dontknowmanuveh')
                                                    <div class="error" id="dontknowmanuvehError">{{ $message }}</div>
                                                @enderror
                                                <div class="havevehnumb">
                                                    <label for="dontknowregveh">Register Date</label>
                                                    <div class="input-group1 datepickerdiv ">
                                                        <input type="text" name="dontknowregveh"
                                                            class="input form-control datepicker"
                                                            id="dontknowregveh" autocomplete="off"
                                                            placeholder="Register Date" spellcheck="false"
                                                            maxlength="10"
                                                        >

                                                        <button class="btn calendarButton" type="button"
                                                            style="height: 46px">
                                                            <i class="fa-solid fa-calendar-days"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                                <div class="havevehnumb mb-2">
                                                    <label for="dontknowmodelveh">Model & Variant</label>
                                                    <select name="dontknowmodelveh" id="dontknowmodelveh">
                                                        <option value="">Model Vehicle</option>
                                                        <option value="pccv">PCCV</option>
                                                        <option value="gccv">GCCV</option>
                                                    </select>
                                                </div>
                                                @error('dontknowmodelveh')
                                                    <div class="error" id="dontknowmodelvehError">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-5">
                                                    <div>
                                                        <input type="radio" name="dontregisterunder" value="knowcar" checked>
                                                        <label for="dontregisterunder">Select Register Under</label>
                                                    </div>

                                                </div>
                                                <div class="col-lg-6">
                                                    <div>
                                                        <input type="radio" name="dontregisterunder" value="individual">
                                                        <label for="individual">Individual Company</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                      
                                    </div>
                                    
                                </div>
                        </form>
                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                            <input type="submit" class="getstarted" value="Continue" onClick="handleGetStarted();">
                            <p class="formbtn">Already bought a policy from DigiBima? <a href="#">Renew Now</a>
                            </p>
                        </div>
                    </div>
                </div>
        </section>


    </main>
    @include('front.partial.footer')
    @include('front.partial.jslink')
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            })
            $('.calendarButton').click(function() {
                var datepickerInput = $(this).siblings('.datepicker');
                datepickerInput.datepicker('show');
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[name="button-group"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    console.log('Ram'); // Should print when changing radio
                    // Hide all details first
                    document.querySelectorAll(
                        '#carInsureDetails, #bikeInsureDetails, #commercialInsureDetails'
                    ).forEach(function(detail) {
                        detail.style.display = 'none';
                    });

                    // Show the corresponding details based on the checked radio button
                    switch (this.value) {
                        case 'car':
                            document.getElementById('carInsureDetails').style.display = 'flex';
                            break;
                        case 'bike':
                            document.getElementById('bikeInsureDetails').style.display = 'flex';
                            break;
                        case 'commercial':
                            document.getElementById('commercialInsureDetails').style.display =
                                'flex';
                            break;
                    }
                });
            });
        });


        function toggleCarDetails() {
            const knowcar = document.querySelector('input[name="knowcar"][value="knowcar"]');
            const dontknowcar = document.querySelector('input[name="knowcar"][value="dontknowcar"]');
            const newcar = document.querySelector('input[name="knowcar"][value="newcar"]');
            const knowcarsection = document.getElementById('knowcarrow');
            const dontknowcarsection = document.getElementById('dontknowcarrow');
            const newcarsection = document.getElementById('newcarrow');

            if (knowcar.checked) {
                knowcarsection.style.display = 'flex';
                dontknowcarsection.style.display = 'none';
                newcarsection.style.display = 'none';
            } else if (dontknowcar.checked) {
                dontknowcarsection.style.display = 'flex';
                knowcarsection.style.display = 'none';
                newcarsection.style.display = 'none';
            } else if (newcar.checked) {
                newcarsection.style.display = 'flex';
                dontknowcarsection.style.display = 'none';
                knowcarsection.style.display = 'none';
            }
        }

        // Attach event listeners to radio buttons
        document.querySelectorAll('input[name="knowcar"]').forEach(radio => {
            radio.addEventListener('change', toggleCarDetails);
        });


        //  ---------------------------   car insurance js start --------------------------//

        // car know section start 
        function showknowcarOtpSection() {
            const otpSection = document.getElementById('knowcarotpSection');
            // $('#carmobile').prop('disabled', true);
            otpSection.style.display = 'block';
        }

        function validateknowcarInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.getElementById('verifycarButton');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }

        function validateknowCarOtpInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.querySelector('.knowcargetotp');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }
        // car dontknow section start 
        function showdontknowcarOtpSection() {
            const otpSection = document.getElementById('dontknowcarotpSection');
            // $('#carmobile').prop('disabled', true);
            otpSection.style.display = 'block';
        }

        function validatedontknowcarInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.getElementById('verifydontcarButton');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }

        function validatedontknowCarOtpInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.querySelector('.dontknowcargetotp');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }
        // car new section start 
        function showNewcarOtpSection() {
            const otpSection = document.getElementById('newcarotpSection');
            // $('#carmobile').prop('disabled', true);
            otpSection.style.display = 'block';
        }

        function validateNewcarInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.getElementById('verifynewcarButton');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }

        function validateNewCarOtpInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.querySelector('.newcargetotp');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }


        //  ---------------------------   car insurance js end --------------------------//


        //  ---------------------------   bike insurance js start --------------------------//

        function togglebikeDetails() {
            const knowbike = document.querySelector('input[name="knowbike"][value="knowbike"]');
            const dontknowbike = document.querySelector('input[name="knowbike"][value="dontknowbike"]');
            const newbike = document.querySelector('input[name="knowbike"][value="newbike"]');
            const knowbikesection = document.getElementById('knowbikerow');
            const dontknowbikesection = document.getElementById('dontknowbikerow');
            const newbikesection = document.getElementById('newbikerow');

            if (knowbike.checked) {
                knowbikesection.style.display = 'flex';
                dontknowbikesection.style.display = 'none';
                newbikesection.style.display = 'none';
            } else if (dontknowbike.checked) {
                dontknowbikesection.style.display = 'flex';
                knowbikesection.style.display = 'none';
                newbikesection.style.display = 'none';
            } else if (newbike.checked) {
                newbikesection.style.display = 'flex';
                dontknowbikesection.style.display = 'none';
                knowbikesection.style.display = 'none';
            }
        }
        document.querySelectorAll('input[name="knowbike"]').forEach(radio => {
            radio.addEventListener('change', togglebikeDetails);
        });

        function showknowBikeOtpSection() {
            const otpSection = document.getElementById('knowbikeotpSection');
            // $('#carmobile').prop('disabled', true);
            otpSection.style.display = 'block';
        }

        function validateknowBikeInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.getElementById('verifybikeButton');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }

        function validateknowBikeOtpInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.querySelector('.knowbikegetotp');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }
        // dontknow js 
        function showdontknowBikeOtpSection() {
            const otpSection = document.getElementById('dontknowbikeotpSection');
            // $('#carmobile').prop('disabled', true);
            otpSection.style.display = 'block';
        }

        function validatedontknowBikeInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.getElementById('verifydontbikeButton');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }

        function validatedontknowBikeOtpInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.querySelector('.dontknowbikegetotp');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }
        // car new section start 
        function showNewBikeOtpSection() {
            const otpSection = document.getElementById('newbikeotpSection');
            // $('#carmobile').prop('disabled', true);
            otpSection.style.display = 'block';
        }

        function validateNewBikeInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.getElementById('verifynewbikeButton');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }

        function validateNewBikeOtpInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.querySelector('.newbikegetotp');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }

        //  ---------------------------   car insurance js end --------------------------//

        //  ---------------------------  commercial insurance js start --------------------------//
        function togglecommerDetails() {
            const havevehicle = document.querySelector('input[name="commercial"][value="havevehicle"]');
            const newvehicle = document.querySelector('input[name="commercial"][value="newvehicle"]');
            const havevehiclesection = document.getElementById('havevehiclerow');
            const newvehiclesection = document.getElementById('newvehiclerow');

            if (havevehicle.checked) {
                havevehiclesection.style.display = 'flex';
                newvehiclesection.style.display = 'none';
            } else if (newvehicle.checked) {
                newvehiclesection.style.display = 'flex';
                havevehiclesection.style.display = 'none';
            }
        }
        document.querySelectorAll('input[name="commercial"]').forEach(radio => {
            radio.addEventListener('change', togglecommerDetails);
        });

        function showHaveVehOtpSection() {
            const otpSection = document.getElementById('havevehotpSection');
            // $('#carmobile').prop('disabled', true);
            otpSection.style.display = 'block';
        }

        function validateHaveVehInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.getElementById('verifyhavevehButton');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }

        function validateHaveVehOtpInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.querySelector('.havevehgetotp');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }


        // new vehicle 
        function showNewvehcOtpSection() {
            const otpSection = document.getElementById('newvehicleotpSection');
            // $('#carmobile').prop('disabled', true);
            otpSection.style.display = 'block';
        }

        function validateNewvehcInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.getElementById('verifynewvehButton');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }

        function validateNewvehcOtpInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.querySelector('.newvehcgetotp');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }

        //  ---------------------------   commercial insurance js end --------------------------//


        const errorBox = document.querySelector('.MainErrorBox');
        const errorTitleElement = errorBox?.querySelector('.error__title');


        function validateInput(input, maxLength) {
            var pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
            const verifyButton = document.getElementById('verifyButton');

            if (input.value.length === maxLength) {
                verifyButton.disabled = false;
                verifyButton.style.opacity = 1;
                verifyButton.style.cursor = 'pointer';
            } else {
                verifyButton.disabled = true;
                verifyButton.style.opacity = 0.5;
                verifyButton.style.cursor = 'not-allowed';
            }
        }

        function showOtpSection() {
            const otpSection = document.getElementById('otpSection');
            // $('#carmobile').prop('disabled', true);
            otpSection.style.display = 'block';
        }

        function handleGetStarted() {
            var name = document.getElementById('findname').value.trim();
            // var email = document.getElementById('findemail').value.trim();
            var mobile = document.getElementById('carmobile').value.trim();
            var pincodeinput = document.getElementById('findpincode').value.trim();

            if (name === '') {
                errorTitleElement.innerText = `Please enter your Name.`;
                document.getElementById('findname').focus();
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                errorBox.style.display = "flex";
                return;
            }

            if (mobile.length !== 10 || !/^\d+$/.test(mobile)) {
                errorTitleElement.innerText = `Please enter a Mobile Number.`;
                document.getElementById('carmobile').focus();
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                errorBox.style.display = "flex";
                return;
            }

            // const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            // if (!emailRegex.test(email)) {
            //     errorTitleElement.innerText = `Please enter a valid Email Address.`;
            //     document.getElementById('findemail').focus();
            //     setTimeout(() => {
            //         errorBox.style.display = 'none';
            //     }, 3000);
            //     errorBox.style.display = "flex";
            //     return;
            // }

            var pincodeMatch = pincodeinput.match(/^\d{6}/);

            if (pincodeMatch) {
                var pincode = pincodeMatch[0];
                if (pincode.length === 6 && /^\d{6}$/.test(pincode)) {
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
        }

        function acpincode(element) {
            const cityListDiv = document.getElementById("city-list");
            const pincodeInput = document.getElementById("findpincode");
            validateInput(element, 6);
            isNumber(element, 6);
            var pincode = element.value;
            var sUrl = "{{ route('acpincode') }}";

            if (pincode.length > 4) {
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
                                        `<li><a href="#" class="city-link" data-pincode="${key}" data-city="${data[key]}">${key} ${cityText}</a></li>`;
                                }
                            }
                            htmlContent += '</ul>';
                            cityListDiv.innerHTML = htmlContent;
                            document.querySelectorAll('.city-link').forEach(link => {
                                link.addEventListener('click', function(event) {
                                    event.preventDefault();
                                    const pincode = this.getAttribute('data-pincode');
                                    const city = this.getAttribute('data-city');
                                    const finalValue = pincode + (city ? ` (${city})` : '');
                                    pincodeInput.value = finalValue;
                                    cityListDiv.style.display = 'none';
                                    // Optionally display the pincode input field
                                    // pincodeInput.parentElement.style.display = 'block';
                                });
                            });
                        } else {
                            cityListDiv.innerHTML = '<p>No cities found for this pincode.</p>';
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        cityListDiv.innerHTML = '<p>An error occurred while fetching the data.</p>';
                    });
            }
        }
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


        // bike insureance section js start

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[name="button-group"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    console.log('Ram'); // Should print when changing radio
                    // Hide all details first
                    document.querySelectorAll(
                        '#carInsureDetails, #bikeInsureDetails, #commercialInsureDetails'
                    ).forEach(function(detail) {
                        detail.style.display = 'none';
                    });

                    // Show the corresponding details based on the checked radio button
                    switch (this.value) {
                        case 'car':
                            document.getElementById('carInsureDetails').style.display = 'block';
                            break;
                        case 'bike':
                            document.getElementById('bikeInsureDetails').style.display =
                                'block';
                            break;
                        case 'commercial':
                            document.getElementById('commercialInsureDetails').style.display =
                                'block';
                            break;
                    }
                });
            });
        });

        function showBikeOtpSection() {
            const otpSection = document.getElementById('bikeotpSection');
            // $('#carmobile').prop('disabled', true);
            otpSection.style.display = 'block';
        }
        // bike insureance section js end
    </script>
</body>

</html>
