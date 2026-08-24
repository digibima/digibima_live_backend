<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Policies</title>
    <meta name="viewport" content="width=device-width">

    @include('front.partial.csslink')
    <link href="{{ config('constant.BASE_URL') }}front/css/dashboard.css" rel="stylesheet">
    <link href="{{ config('constant.BASE_URL') }}front/css/datatable.min.css" rel="stylesheet">
    <style>
        .form-containerOne {
            background: #ffffff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-left: 10px;
            margin-bottom: 10px;
        }

        input[name="insurance"] {
            display: none;
        }

        #maincontrow {
            height: 100vh;
            padding-top: 0%;

        }

        .form-control {
            appearance: auto;
        }

        /* #contentrow {
            background-color: #f4f4fa;
        } */

        #maincontrow td {
            color: #1D8AFF;

        }

        #sidebar {
            background: #fff;
        }

        #maincontrow #tbl td {
            color: #000;
        }

        .greenbtn:hover {
            color: #000;
            background-color: #DCF1E4;
            border-color: #DCF1E4;
        }

        .redbtn:hover {
            color: #000;
            background-color: #E2EAF7;
            border-color: #E2EAF7;
        }

        .fa-eye {
            background-color: transparent;
            color: #28BBB0;
            ;
        }

        .fa-download {
            background-color: transparent;
            color: #1285f7;
        }

        #filterrow select,
        #planrowblock select,
        #planrowblock .btnbg,
        .addrow select,
        .coverblock select {
            border: solid 1.5px #e7e7e7;
            color: #000;
            background-color: #fff;
        }

        #filterrow select:focus-visible {
            border: solid 1.5px #e7e7e7;
        }

        .name input,
        .name select,
        .mobile input,
        .email input,
        .otp input,
        .pincode input,
        .carnumber input,
        .commercialnumber select,
        .commercialnumber input,
        .newvehiclenum select,
        .newvehiclenum input,
        .havevehnumb select,
        .havevehnumb input,
        .select2-container--default .select2-selection--single {
            width: 100%;
            padding: 10px !important;
            /* Increase padding for better touch experience */
            background: #ffffff;

            border: 1px solid #f1f1f1 !important;
            border-radius: 5px;
        }

        .name select {
            height: 45px !important;
        }

        .claimhead {
            text-align: center;
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            font-size: 28px;
            line-height: 42px;
            color: #1c5fa8;
            margin-bottom: 2rem;
            padding: 10px 0px;
        }

        .name .error {
            border: 1px solid red !important;
            /* Red border for invalid inputs */
            background-color: #F8F9FD;
            /* Light red background to highlight the error */
        }

        #contentrow,
        #maincontrow {
            background: #F4F4FA;
        }
    </style>

</head>

<body>
    <!-- partial:index.partial.html -->
    <!-- overlay -->
    @php

    @endphp
    <div id="sidebar-overlay" class="overlay w-100 vh-100 position-fixed d-none"></div>
    <div id="loader">
        <div class="loader-container">
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
            <div class="dot dot-3"></div>
            <div class="dot dot-4"></div>
        </div>
    </div>
    <!-- Error Box -->
    <div id="MainErrorBox" style="float: right; display: none; margin-right:30px;" class="MainErrorBox">
        <span class="error__icon"><i class="fa-solid fa-circle-exclamation"></i></span>
        <p class="error__title mb-0" style="margin-right: 10px;"></p>
        <span class="error__close" onclick="hideAlert('error')"><i class="fa-solid fa-xmark"></i></span>
    </div>

    <!-- Success Box -->
    <div id="MainVerifiedBox" style="float: right; display: none; margin-right:30px;" class="MainverifiyedBox">
        <span class="verifiy__icon"><i class="fa-solid fa-circle-check"></i></span>
        <p class="verifiyed__title mb-0" style="margin-right: 10px;"></p>
        <span class="verifiyed__close" onclick="hideAlert('success')"><i class="fa-solid fa-xmark"></i></span>
    </div>
    <!-- sidebar -->
    @include('front.main.dashboard.sidebar.usersidebar')



    <div id="contentrow" class="col-md-9 col-lg-10 ms-md-auto px-0">
        <!-- top nav -->
        @include('front.main.dashboard.header.usernavbar')

        <!-- main content -->
        <main id="maincontrow">
            <section class="row ">

                <div id="policyrow" class="col-md-11 col-xs-12 mb-2">
                    <div class="form-containerOne">
                        <h5 class="claimhead">Enter Below Details and Submit Your Claim Request</h5>
                        <form id="claimForm" action="{{ route('userclaim') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div id="filterrow" class="row">
                                <div class="col-md-5 col-xs-12 mb-3">
                                    <div class="name">
                                        <label for="proposername">Proposer Name</label>
                                        <input type="text" id="proposername" name="proposername" value=""
                                            placeholder="Enter Full Name">
                                    </div>
                                </div>
                                <div class="col-md-5 col-xs-12 mb-3">
                                    <div class="name">
                                        <label for="mobnumber">Mobile Number</label>
                                        <input type="text" id="mobnumber" class="numeric-input" name="mobnumber"
                                            value="" placeholder="Enter Mobile Number" maxlength="13">
                                    </div>
                                </div>
                                <div class="col-md-5 col-xs-12 mb-3">
                                    <div class="name">
                                        <label for="emailid">Email Id</label>
                                        <input type="text" id="emailid" name="emailid" value=""
                                            placeholder="Enter Email Id">
                                    </div>
                                </div>
                                <div class="col-md-5 col-xs-12 mb-3">
                                    <div class="name">
                                        <label for="insurancepolicy">Insurance Policy</label>
                                        <select name="insurancepolicy" class="Agebox" id="insurancepolicy">
                                            <option value="">Select Insurance Policy</option>
                                            <option value="health">Health</option>
                                            <option value="Vehicle">Vehicle</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 col-xs-12 mb-3">
                                    <div class="name">
                                        <label for="policynumber">Policy Number</label>
                                        <input type="text" id="policynumber" name="policynumber" value=""
                                            placeholder="Enter Policy Number">
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 mb-3">
                                    <button class="continue">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </section>

        </main>



    </div>

    <!-- partial -->
    @include('front.partial.chatwidget')
    @include('front.partial.jslink')
    <script src="{{ config('constant.BASE_URL') }}front/js/dashboard.js"></script>
    <script src="{{ config('constant.BASE_URL') }}front/js/datatable.min.js"></script>
    <script src="{{ config('constant.BASE_URL') }}front/js/validateFields.js"></script>

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

        document.addEventListener("DOMContentLoaded", function() {
            allowOnlyNumbers(".numeric-input");
        });


        // Function to display error messages
        function displayError(message) {
            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox.querySelector('.error__title');

            if (errorTitleElement) {
                errorTitleElement.innerText = message;
                errorBox.style.display = 'flex';

                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
            }
        }

        // Function to display success (verification) message
        function displaySuccess(message) {
            const verifyBox = document.querySelector('.MainverifiyedBox');
            const verifyTitleElement = verifyBox.querySelector('.verifiyed__title');

            if (verifyTitleElement) {
                verifyTitleElement.innerText = message;
                verifyBox.style.display = 'flex';

                setTimeout(() => {
                    verifyBox.style.display = 'none';
                }, 3000);
            }
        }

        // General validation for fields
        function validateField(inputElement, pattern, errorMessage, isRequired = true) {
            let valid = true;
            inputElement.classList.remove('error'); // Remove previous error class

            // Check if the field is required and if it has a value
            if (isRequired && inputElement.value.trim() === '') {
                displayError(errorMessage);
                inputElement.classList.add('error'); // Add error class
                valid = false;
            } else if (pattern && !inputElement.value.match(pattern)) {
                displayError(errorMessage);
                inputElement.classList.add('error'); // Add error class
                valid = false;
            }

            return valid;
        }

        // Form submission validation
        function validateForm(event) {
            event.preventDefault();
            let valid = true;

            // Validate proposer name
            const proposername = document.getElementById('proposername');
            valid = valid && validateField(proposername, null, 'Proposer name is required');

            // Validate mobile number
            const mobnumber = document.getElementById('mobnumber');
            const mobilePattern = /^[0-9]{10}$/; // Ensure mobile number is 10 digits
            valid = valid && validateField(mobnumber, mobilePattern, 'Please enter a valid 10-digit mobile number');

            // Validate email address
            const emailid = document.getElementById('emailid');
            const emailPattern = /^[^@]+@[^@]+\.[^@]+$/;
            valid = valid && validateField(emailid, emailPattern, 'Please enter a valid email address');

            // Validate insurance policy
            const insurancepolicy = document.getElementById('insurancepolicy');
            valid = valid && validateField(insurancepolicy, null, 'Please select an insurance policy');

            // Validate policy number
            const policynumber = document.getElementById('policynumber');
            valid = valid && validateField(policynumber, null, 'Policy number is required');

            if (valid) {
                displaySuccess('Form submitted successfully!');
                document.getElementById('claimForm').submit(); // Submit form if valid
            }
        }

        // Attach event listener for form submission
        document.getElementById('claimForm').addEventListener('submit', validateForm);

        // Handle closing the error message
        const errorCloseButton = document.querySelector('.error__close');
        if (errorCloseButton) {
            errorCloseButton.addEventListener('click', () => {
                const errorBox = document.querySelector('.MainErrorBox');
                if (errorBox) {
                    errorBox.style.display = "none";
                }
            });
        }

        // Handle closing the success message
        const successCloseButton = document.querySelector('.verifiyed__close');
        if (successCloseButton) {
            successCloseButton.addEventListener('click', () => {
                const successBox = document.querySelector('.MainverifiyedBox');
                if (successBox) {
                    successBox.style.display = "none";
                }
            });
        }
    </script>




</body>

</html>
