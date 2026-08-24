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
        .calendarButton {
            top: 3px !important;
            right: 3px !important;
        }

        .gender-label input[type="radio"]:checked+.gender-box {
            background-color: #1C5FA8;
            color: #fff;
            border-color: #1C5FA8;
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

        input[ name="gender"] {
            display: none;
        }

        #maincontrow {
            height: 100vh;
            padding-top: 0%;

        }

        .form-control {
            appearance: auto;
        }



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





        .form-containerOne {
            background: #ffffff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-left: 10px;
            margin-bottom: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333333;
        }



        .gender-selection label {
            font-size: 16px;
            color: #555555;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            margin-bottom: 5px;
            font-size: 14px;
            color: #555555;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .save-btn {
            /* width: 100%; */
            padding: 10px;
            background-color: #1c5fa8;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .save-btn:hover {
            background-color: #0056b3;
        }

        select {
            height: 45px !important;
        }

        .header-container {
            display: flex;
            align-items: center;
            background-color: #f8f9fd;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: fit-content;
        }

        .icon-wrapper {
            background-color: #eef4ff;
            padding: 10px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .icon-wrapper img {
            width: 24px;
            /* Adjust size */
            height: 24px;
        }

        h2 {
            font-size: 18px;
            color: #333333;
            margin: 0;
        }

        #contentrow,
        #maincontrow {
            background: #F4F4FA;
        }

        .datepicker {
            padding: 10px;
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

        .error {
            border: 1px solid red;
            /* Red border for invalid inputs */
            background-color: #F8F9FD;
            /* Light red background to highlight the error */
        }

        .btn-check:focus+.btn,
        .btn:focus {
            /* outline: 0; */
            /* box-shadow: 0 0 0 !important; */
            background: #fff !important;
        }

        .getstarted1 {
            font-size: 11px;
            text-decoration: none;
            color: #fff;
            background: #1c5fa8;
            border-radius: 5px;

        }

        .getstarted1:hover {
            color: #fff;
            background: #1c5fa8;
        }

        .btn-check:focus+.btn,
        .btn:focus {
            outline: none !important;
            box-shadow: none !important;
            background: white !important;
        }
    </style>

</head>

<body>
    <!-- partial:index.partial.html -->
    <!-- overlay -->
    @php
        $userdata = $data['user'];
        // dd($userdata);
        Auth::user()->name;
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
                <!-- <div class="col-lg-12 col-md-12 col-sm-12">
                    <h5 class="claimhead">Manage your family members, contact details and financial assets</h5>
                </div> -->
                <div id="policyrow" class="col-md-11 col-xs-12 mb-2">
                    <div class="form-containerOne">
                        <form action="{{ route('profileupdate') }}" method="post" id="personalForm">
                            @csrf
                            <div id="filterrow" class="row">

                                <div class="header-container mb-3">
                                    <div class="icon-wrapper">
                                        <img src="{{ config('constant.BASE_URL') }}front/images/profileicon.png"
                                            alt="Profile Icon" />
                                    </div>
                                    <h2>Personal details</h2>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                                    <div class="gender-selection">
                                        <label class="gender-label">
                                            <input type="radio" name="gender" value="male" checked>
                                            <div class="gender-box">Male</div>
                                        </label>
                                        <label class="gender-label">
                                            <input type="radio" name="gender" value="female">
                                            <div class="gender-box">Female</div>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Full Name</label>
                                            <input type="text" id="name" name="name"
                                                value="{{ !empty($userdata['name']) ? $userdata['name'] : '' }}"
                                                placeholder="Enter your name">

                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text" id="mobile" name="mobile"
                                                        placeholder="Enter mobile number"
                                                        value="{{ !empty($userdata['mobile']) ? $userdata['mobile'] : '' }}"
                                                        pattern="\d*" maxlength="13" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-5"
                                                style="display: flex; justify-content: space-between; align-items: center;">
                                                <!-- <button class="getstarted1">Change Number</button> -->
                                                <a href="#" class="getstarted1 py-2 px-2">Change Number</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Email ID</label>
                                            <input type="text" id="email"
                                                value="{{ !empty($userdata['email']) ? $userdata['email'] : '' }}"
                                                name="email" placeholder="Enter email ID">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group ">
                                            <label>Date of Birth</label>
                                            <div class="input-group1 datepickerdiv">
                                                <input type="text" name="dob" class="input datepicker"
                                                    id="dob"
                                                    value="{{ !empty($userdata['dob']) ? $userdata['dob'] : '' }}"
                                                    placeholder="Enter date of birth" autocomplete="off"
                                                    spellcheck="false" maxlength="10">
                                                <button class="btn calendarButton" type="button"
                                                    onfocus="this.style.backgroundColor='white'; this.style.outline='none'; this.style.boxShadow='none';"
                                                    onblur="this.style.backgroundColor=''; this.style.outline=''; this.style.boxShadow='';">
                                                    <i class="fa-solid fa-calendar-days"></i>
                                                </button>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Annual Income</label>
                                            <select id="incom" name="incom">
                                                <option value="" disabled selected>Select income</option>
                                                <option value="low" @selected(old('incom', $userdata['income']) == 'low')>Below ₹5 Lakh
                                                </option>
                                                <option value="medium" @selected(old('incom', $userdata['income']) == 'medium')>₹5-10 Lakh
                                                </option>
                                                <option value="high" @selected(old('incom', $userdata['income']) == 'high')>Above ₹10 Lakh
                                                </option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Marital Status</label>
                                            <select id="martial_status" name="martial_status">
                                                <option value="" disabled selected>Select marital status
                                                </option>
                                                <option value="single" @selected(old('martial_status', $userdata['martial_status']) == 'single')>Single</option>
                                                <option value="married" @selected(old('martial_status', $userdata['martial_status']) == 'married')>Married
                                                </option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>City</label>
                                            <input type="text" id="city" name="city"
                                                value="{{ !empty($userdata['city']) ? $userdata['city'] : '' }}"
                                                placeholder="Enter city">
                                        </div>
                                    </div>
                                </div>

                                <input type="submit" class="d-none" id="userprofilebtn">
                            </div>
                        </form>
                        <button type="submit" class="getstarted" onclick="validateForm()">Save details</button>
                    </div>
                </div>
                <div id="policyrow" class="col-md-4 col-xs-12 mb-2"></div>
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
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            }).on('changeDate', function() {
                const input = this;
                const placeholder = $(input).siblings('.placeholder')[0];
                updatePlaceholder(input, placeholder);
            });
            $('.calendarButton').click(function() {
                var datepickerInput = $(this).siblings('.datepicker');
                datepickerInput.datepicker('show');
            });
        });

        // Function to allow only numeric input
        function allowOnlyNumbers(event) {
            const input = event.target;
            input.value = input.value.replace(/[^0-9]/g, '');
        }


        // form validation function start 

        document.getElementById('mobile').addEventListener('input', allowOnlyNumbers);

        function validateField(inputElement, pattern, errorMessage, isRequired = true) {
            let valid = true;
            inputElement.classList.remove('error');

            if (isRequired && inputElement.value.trim() === '') {
                displayError(errorMessage, inputElement);
                valid = false;
            } else if (pattern && !inputElement.value.match(pattern)) {
                displayError(errorMessage, inputElement);
                valid = false;
            }
            return valid;
        }

        function displayError(message, inputElement) {
            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox.querySelector('.error__title');

            if (errorTitleElement) {
                errorTitleElement.innerText = message;
                errorBox.style.display = 'flex';

                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);

                if (inputElement) {
                    errorBox.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    // inputElement.focus();
                }
                inputElement.classList.add('error');
            }
        }

        document.getElementById('mobile').addEventListener('input', function(event) {
            const input = event.target;
            input.value = input.value.replace(/[^0-9]/g, '');
        });

        function validateForm() {
            // event.preventDefault();
            let valid = true;
            const name = document.getElementById('name');
            valid = valid && validateField(name, null, 'Full name is required');

            const mobile = document.getElementById('mobile');
            const mobilePattern = /^[0-9]{10}$/;
            valid = valid && validateField(mobile, mobilePattern, 'Please enter a valid 10-digit mobile number');

            const email = document.getElementById('email');
            const emailPattern = /^[^@]+@[^@]+\.[^@]+$/;
            valid = valid && validateField(email, emailPattern, 'Please enter a valid email address');

            const dob = document.getElementById('dob');
            valid = valid && validateField(dob, null, 'Date of Birth is required');

            const incom = document.getElementById('incom');
            valid = valid && validateField(incom, null, 'Annual income is required');

            const martial_status = document.getElementById('martial_status');
            valid = valid && validateField(martial_status, null, 'Marital status is required');

            const city = document.getElementById('city');
            valid = valid && validateField(city, null, 'City is required');

            if (valid) {
                document.getElementById('userprofilebtn').click();
            }
        }

        document.getElementById('personalForm').addEventListener('submit', validateForm);

        const errorCloseButton = document.querySelector('.error-close-btn');
        if (errorCloseButton) {
            errorCloseButton.addEventListener('click', () => {
                const errorBox = document.querySelector('.MainErrorBox');
                if (errorBox) {
                    errorBox.style.display = "none";
                }
            });
        }
        dobpartenallowed('.datepicker');
        // form validation function End
    </script>



</body>

</html>
