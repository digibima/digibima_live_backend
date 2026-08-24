<?php
use App\Models\Proposal;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Have New Vehicle</title>
    @include('front.partial.csslink')
    <style>
        /* input[type="radio"]{} */
        .bonus-box {
            width: 70px !important;
            border-radius: 50px !important;
        }

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

        .bonus-box {
            width: 120px;
            height: 35px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .bonus-label input[type="radio"] {
            display: none;
        }

        .bonus-label input[type="radio"]:checked+.bonus-box {
            background-color: #adcbf0;
            color: #000;
            border-color: #adcbf0;
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

            .bonus-box {
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

            #findtopplan .head {
                font-size: 24px;
                line-height: 36px;
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
        <p class="error__title mb-0 " style="margin-right:10px;"></p><span class="error__close "><i
                class="fa-solid fa-xmark  mr-3"></i></span>
    </div>
    <div class="MainverifiyedBox" style="float: right;display:none;"><span class="verifiy__icon"><i
                class="fa-solid fa-circle-check"></i></span>
        <p class="verifiyed__title  mb-0" style="margin-right:10px;"></p> <span class="verifiyed__close"><i
                class="fa-solid fa-xmark"></i></span>
    </div>
    <div id="loder">
        <div class="loader-container">
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
            <div class="dot dot-3"></div>
            <div class="dot dot-4"></div>
        </div>

    </div>
    <main id="slider-container">
        <section class="slide" id="findtopplan">
            {{-- <div class="row">
                <div class="col-md-3 alredyacount">
                    @auth
                        <a href="#" class="custom-button">
                            <div class="button-icon">
                                <span class="conti">Continue</span><i class="fa-solid fa-arrow-right"></i>
                            </div>
                            <p class="button-text mb-0"></p>
                        </a>
                    @endauth
                </div>
            </div> --}}
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 head text-center">
                        Passenger Carrying Vehicle Insurance
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="image">
                            <img src="{{ config('constant.BASE_URL') }}front/images/commercial.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('commercial.plan') }}" method="POST" id="havenewvehSlideTwo"
                            name="havenewvehSlideTwo">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <p class="sidepera mb-1">It is simple, quick, and easy</p>
                                    <div class="row" id="havevehsection">
                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                            <div class="havevehnumb mb-2">
                                                <label for="havenewvehmake">Make</label>
                                                {{-- <select name="havenewvehmake" id="havenewvehmake">
                                                    <option value="">Manufacture Vehicle</option>
                                                    <option value=""> Maruti Suzuki</option>
                                                    <option value="">Hyundai Motor India</option>
                                                    <option value="">Mahindra & Mahindra</option>
                                                    <option value="">Force Motors</option>
                                                </select> --}}
                                                <select name="havenewvehmake" id="havenewvehmake"
                                                    class="form-control js-example-basic-single" style="width: 100%;">
                                                    <option value="" style="display: none;" selected disabled>
                                                    </option>

                                                </select>
                                            </div>
                                            @error('havenewvehmake')
                                                <div class="error" id="havevehgccmakeError">{{ $message }}</div>
                                            @enderror
                                           

                                            <div class="carnumber mb-2">
                                                <label for="havenewvehbodytype">Type of Body</label>
                                                <input type="text" id="havenewvehbodytype" name="havenewvehbodytype"
                                                    value="" placeholder="Enter Type of Body">
                                            </div>
                                            @error('havenewvehbodytype')
                                                <div class="error" id="havevehbodytypeError">{{ $message }}</div>
                                            @enderror
                                            <div class="havevehnumb">
                                                <label for="havenewvehregveh">Register Date</label>
                                                <div class="input-group1 datepickerdiv ">
                                                    <input type="text" name="havenewvehregveh"
                                                        class="input form-control datepicker" id="havenewvehregveh"
                                                        autocomplete="off" spellcheck="false" maxlength="10">

                                                    <button class="btn calendarButton" type="button"
                                                        style="height: 46px">
                                                        <i class="fa-solid fa-calendar-days"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="havevehnumb mb-2">
                                                    <label for="havenewvehgvehunder">Select registration under</label>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="myradio radiolabel mb-2">
                                                        <input type="radio" id="individual" name="havenewvehgvehunder"
                                                            value="individual" checked>
                                                        <label for="individual">Individual</label>
                                                    </div>

                                                </div>
                                                <div class="col-lg-6 ">
                                                    <div class="myradio radiolabel mb-2">
                                                        <input type="radio" id="company" name="havenewvehgvehunder"
                                                            value="company">
                                                        <label for="company">Company</label>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="carnumber mb-2">
                                                <label for="havenewvehcubiccap">Cubic Capacity</label>
                                                <input type="text" id="havenewvehcubiccap" name="havenewvehcubiccap"
                                                    value="" placeholder="Enter Type of Body">
                                            </div>
                                            @error('havenewvehcubiccap')
                                                <div class="error" id="havenewvehcubiccapError">{{ $message }}</div>
                                            @enderror
                                            <div class="havevehnumb carcompanysection" style="display: none;">
                                                <div class="carnumber mb-2">
                                                    <label for="havenewvehgstno">GST Number</label>
                                                    <input type="text" id="havenewvehgstno" name="havenewvehgstno"
                                                        value="" placeholder="Enter GST Number">
                                                </div>
                                            </div>


                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-2">

                                            <div class="havevehnumb mb-2">
                                                <label for="havenewvehmodelveh">Model & Variant</label>
                                                {{-- <select name="havenewvehmodelveh" id="havenewvehmodelveh">
                                                    <option value="">Select Model</option>
                                                    <option value="toyota-corolla">Toyota Corolla</option>
                                                    <option value="honda-civic">Honda Civic</option>
                                                    <option value="ford-fusion">Ford Fusion</option>
                                                    <option value="chevrolet-malibu">Chevrolet Malibu</option>
                                                    <option value="tesla-model3">Tesla Model 3</option>
                                                </select> --}}
                                                <select name="havenewvehmodelveh" id="havenewvehmodelveh"
                                                    class="form-control js-example-basic-single" style="width: 100%;">
                                                    <option value="" style="display: none;" selected disabled>
                                                    </option>
                                                   
                                                </select>
                                            </div>
                                            @error('havenewvehmodelveh')
                                                <div class="error" id="havevehmodelvehError">{{ $message }}</div>
                                            @enderror


                                            <div class=" mb-2">
                                                <div class="carnumber mb-2">
                                                    <label for="havenewvehcategory">Vehicle Category</label>
                                                    <input type="text" id="havenewvehcategory" name="havenewvehcategory"
                                                        value="" placeholder="Enter Company Name">
                                                </div>
                                                @error('havenewvehcategory')
                                                    <div class="error" id="havevehcategoryError">{{ $message }}
                                                    </div>
                                                @enderror
                                                <div class="carnumber mb-2">
                                                    <label for="havenewvehcity">City</label>
                                                    <input type="text" id="havenewvehcity" name="havenewvehcity"
                                                        value="" placeholder="Enter Company Name">
                                                </div>
                                                @error('havenewvehcity')
                                                    <div class="error" id="havevehcityError">{{ $message }}
                                                    </div>
                                                @enderror
                                                <div class="carnumber mb-2">
                                                    <label for="havenewvehcatweight">Seating Capacity</label>
                                                    <input type="text" id="havenewvehcatweight"
                                                        name="havenewvehcatweight" value=""
                                                        placeholder="Enter Company Name">
                                                </div>
                                                @error('havenewvehcatweight')
                                                    <div class="error" id="havevehcatweightError">{{ $message }}
                                                    </div>
                                                @enderror
                                               


                                            </div>
                                            <div class="havevehnumb carcompanysection" style="display: none;">
                                                <div class="carnumber mb-2">
                                                    <label for="havenewvehcmpnynme">Company Name</label>
                                                    <input type="text" id="havenewvehcmpnynme" name="havenewvehcmpnynme"
                                                        value="" placeholder="Enter Company Name">
                                                </div>
                                            </div>

                                        </div>


                                        <div class="col-lg-12 col-md-12 col-sm-12" id="ownershipTransferDiv">
                                            <div class="row">

                                                <p class="mb-2">Select your previous policy type</p>
                                                <div class="col-lg-4 mb-3">
                                                    <div class="myradio radiolabel activeradio">
                                                        <input type="radio" id="comprehensive" name="prepolitype"
                                                            value="comprehensive" onchange="togglePrexpdateSection()"
                                                            checked>
                                                        <label for="comprehensive">Comprehensive</label>
                                                    </div>

                                                </div>
                                                
                                                <div class="col-lg-4 mb-3">
                                                    <div class="myradio radiolabel">
                                                        <input type="radio" id="thirdparty" name="prepolitype"
                                                            value="thirdparty" onchange="togglePrexpdateSection()">
                                                        <label for="thirdparty">Third Party</label>
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 mb-3" id="PrexpdateSection">
                                                    <div class="havevehnumb">
                                                        <div class="input-group1 datepickerdiv ">
                                                            <input type="text" name="Prepolexpdate"
                                                                class="input form-control datepicker"
                                                                id="Prepolexpdate" autocomplete="off"
                                                                placeholder="Previous Policy Expiry Date"
                                                                spellcheck="false" maxlength="10">

                                                            <button class="btn calendarButton" type="button"
                                                                style="height: 46px">
                                                                <i class="fa-solid fa-calendar-days"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-3 thirdpartyhide">
                                                    <p class="characters mb-2">Was there any ownership transfer in the
                                                        previous year?</p>
                                                    <div class="radiolabel">
                                                        <label class="switch">
                                                            <input type="checkbox" id="ownershiptoggle"
                                                                name="ownershiptoggle" onchange="toggleOwnershipDiv()"
                                                                checked>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>
                                                <div class="col-lg-6 mb-2 thirdpartyhide">
                                                    <p class="characters mb-2">Did you make a claim in your previous
                                                        policy
                                                        period? </p>
                                                    <div class="radiolabel">
                                                        <label class="switch">
                                                            <input type="checkbox" id="policytoggle"
                                                                name="policytoggle">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>
                                                <div class="col-lg-12" id="ownershipDiv" style="display: none">
                                                    <div class="row">
                                                        <div class="col-lg-6 mb-2">
                                                            <p class="characters mb-2">How much was the No Claim Bonus
                                                                in
                                                                the previous policy?
                                                            </p>

                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="vehicle-container mb-3">
                                                                <label class="bonus-label mb-2">
                                                                    <input type="radio" name="bonus-button"
                                                                        value="zero" checked>
                                                                    <div class="bonus-box">0%
                                                                    </div>
                                                                </label>
                                                                <label class="bonus-label mb-2">
                                                                    <input type="radio" name="bonus-button"
                                                                        value="twenty">
                                                                    <div class="bonus-box">20%
                                                                    </div>

                                                                </label>
                                                                <label class="bonus-label mb-2">
                                                                    <input type="radio" name="bonus-button"
                                                                        value="twentyfive">
                                                                    <div class="bonus-box">25%
                                                                    </div>
                                                                </label>
                                                                <label class="bonus-label mb-2">
                                                                    <input type="radio" name="bonus-button"
                                                                        value="thirtyfive">
                                                                    <div class="bonus-box">35%
                                                                    </div>
                                                                </label>
                                                                <label class="bonus-label mb-2">
                                                                    <input type="radio" name="bonus-button"
                                                                        value="fortyfive">
                                                                    <div class="bonus-box">45%
                                                                    </div>
                                                                </label>
                                                                <label class="bonus-label mb-2">
                                                                    <input type="radio" name="bonus-button"
                                                                        value="fifty">
                                                                    <div class="bonus-box">50%
                                                                    </div>
                                                                </label>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                            <input type="submit" id="havevehFormTwoSub" class="d-none" value="clk">
                        </form>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <input type="submit" class="getstarted" value="Continue"
                                onClick="HaveVehicleSlideTwo(event)">
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
        window.addEventListener('load', function() {
            $('main').hide();
            $('#loder').show();
            setTimeout(() => {
                $('#loder').hide();
                $('main').show();
            }, 200);

        });

        function toggleCarDetails() {
            const Individual = document.querySelector('input[name="havenewvehgvehunder"][value="individual"]');
            const Company = document.querySelector('input[name="havenewvehgvehunder"][value="company"]');
            const ownershipDiv = document.getElementById('ownershipTransferDiv');
            const companySections = document.querySelectorAll('.carcompanysection');

            const individualDiv = document.querySelector('div[for="individual"]');
            const companyDiv = document.querySelector('div[for="company"]');
            if (Individual.checked) {
                ownershipDiv.style.display = 'flex';
                Individual.parentNode.classList.add('activeradio');
                Company.parentNode.classList.remove('activeradio');
                companySections.forEach(section => {
                    section.style.display = 'none';
                });
            } else if (Company.checked) {
                ownershipDiv.style.display = 'flex';
                Company.parentNode.classList.add('activeradio');
                Individual.parentNode.classList.remove('activeradio');
                companySections.forEach(section => {
                    section.style.display = 'block';
                });
            }
        }
        document.querySelectorAll('input[name="havenewvehgvehunder"]').forEach(radio => {
            radio.addEventListener('change', toggleCarDetails);
        });
        toggleCarDetails();


        function toggleOwnershipDiv() {
            const checkbox = document.getElementById('ownershiptoggle');
            const ownershipDiv = document.getElementById('ownershipDiv');
            if (checkbox.checked) {
                ownershipDiv.style.display = 'none';
            } else {
                ownershipDiv.style.display = 'block';
            }
        }

        function togglePrexpdateSection() {
            const owndamagecheckbox = document.getElementById('owndamage');
            const thirdPartycheckbox = document.getElementById('thirdparty');
            const Prepolexpdatesec = document.getElementById('PrexpdateSection');
            const thirdpartyHides = document.querySelectorAll('.thirdpartyhide');
            const ownershipDiv = document.getElementById('ownershipDiv');

            // Clear activeradio class from all radio parent divs
            const radioContainers = document.querySelectorAll('#ownershipTransferDiv .myradio');
            radioContainers.forEach(container => {
                container.classList.remove('activeradio');
            });

            // Determine which radio button is checked and apply logic
            if (thirdPartycheckbox.checked) {
                thirdPartycheckbox.parentNode.classList.add('activeradio');
                Prepolexpdatesec.style.display = 'block';
                thirdpartyHides.forEach(hide => hide.style.display = 'none');
                ownershipDiv.style.display = 'none';
            } else {
                document.getElementById('comprehensive').parentNode.classList.add('activeradio');
                Prepolexpdatesec.style.display = 'block';
                thirdpartyHides.forEach(hide => hide.style.display = 'block');
                ownershipDiv.style.display = 'block';
            }
        }


        $(document).ready(function() {
            $('#havenewvehmake').select2({
                placeholder: 'Manufacture Vehicle',
                allowClear: true,
            });
            $('#havenewvehmodelveh').select2({
                placeholder: 'Select Model',
                allowClear: true,
            });

            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });

            $('.calendarButton').click(function() {
                var datepickerInput = $(this).siblings('.datepicker');
                datepickerInput.datepicker('show');
            });
        });

        function HaveVehicleSlideTwo(event) {
            event.preventDefault();

            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox?.querySelector('.error__title');
            const verifiedBox = document.querySelector('.MainverifiyedBox');

            const havenewvehmake = document.getElementById('havenewvehmake').value.trim();
            const havenewvehmodelveh = document.getElementById('havenewvehmodelveh').value.trim();
            const havenewvehcategory = document.getElementById('havenewvehcategory').value.trim();
            const havenewvehbodytype = document.getElementById('havenewvehbodytype').value.trim();
            const havenewvehcatweight = document.getElementById('havenewvehcatweight').value.trim();
            const havenewvehcubiccap = document.getElementById('havenewvehcubiccap').value.trim();
            const havenewvehcity = document.getElementById('havenewvehcity').value.trim();
            const prePolicy = document.querySelector('input[name="prepolitype"]:checked');
            const Comprehensivedate = document.getElementById('Prepolexpdate').value.trim();

            const companycheckbox = document.querySelector('input[name="havenewvehgvehunder"]:checked');
            const havenewvehcmpnynme = document.getElementById('havenewvehcmpnynme').value.trim();
            const havenewvehgstno = document.getElementById('havenewvehgstno').value.trim();

            let valid = true;
            verifiedBox.style.display = 'none';
            errorBox.style.display = 'none';

            if (prePolicy?.value === 'thirdparty' && !Comprehensivedate) {
                valid = showError(`Previous Policy Date must be filled.`, '#Prepolexpdate') && (valid = false);
            }

            if (companycheckbox?.value === 'company' && !havenewvehgstno) {
                valid = showError(`GST Number  must be filled.`, '#havenewvehgstno') && (valid = false);
            }
            if (companycheckbox?.value === 'company' && !havenewvehcmpnynme) {
                valid = showError(`Company Name must be filled.`, '#havenewvehcmpnynme') && (valid = false);
            }
            if (prePolicy?.value === 'comprehensive' && !Comprehensivedate) {
                valid = showError(`Previous Policy Date must be filled.`, '#Prepolexpdate') && (valid = false);
            }

          
           

            if (!havenewvehcubiccap) {
                valid = showError(`Cubic Capacity must be filled.`, '#havenewvehcubiccap') && (valid = false);
            }
            if (!havenewvehcatweight) {
                valid = showError(`Seating Capacity must be filled.`, '#havenewvehcatweight') && (valid = false);
            }
            if (!havenewvehcity) {
                valid = showError(`City must be filled.`, '#havenewvehcity') && (valid = false);
            }
            if (!havenewvehregveh) {
                valid = showError(`Vehicle Register Date must be selected.`, '#havenewvehregveh') && (valid = false);
            }
          
            if (!havenewvehcategory) {
                valid = showError(`Vehicle Category must be filled.`, '#havenewvehcategory') && (valid = false);
            }
            if (!havenewvehbodytype) {
                valid = showError(`Vehicle Type of Body must be filled.`, '#havenewvehbodytype') && (valid = false);
            }
            if (!havenewvehmodelveh) {
                valid = showError(`Vehicle Model & Variant must be selected.`, '#havenewvehmodelveh') && (valid = false);
            }

            if (!havenewvehmake) {
                valid = showError(`Vehicle Make must be selected.`, '#havenewvehmake') && (valid =
                    false);
            }
         


            if (valid) {
                verifiedBox.style.display = 'flex';
                verifiedBox.querySelector('.verifiyed__title').innerText = `Form submitted successfully!`;
                setTimeout(() => {
                    verifiedBox.style.display = 'none';
                }, 3000);
                gotoNextPage();

            }

            function gotoNextPage() {
                //console.log('hhh');
                //return false;
                $('#havevehFormTwoSub').click();
                //console.log('#havevehFormTwoSub');
                return false;
                // window.location.href = @json(getconstant('SITE_URL') . 'motor/checkout');
            }

            function showError(message, focusSelector) {
                errorTitleElement.innerText = message;
                document.querySelector(focusSelector).focus();
                errorBox.style.display = "flex";
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                return false;
            }
        }

        async function getBrand() {
            let brands = await getBrandName('CAR');
            if (brands && brands.date) {
                $('#havenewvehmake').empty();
                $('#havenewvehmake').append(
                    '<option value="" style="display: none;" selected disabled>Select a brand</option>');

                if (brands.brand && Object.keys(brands.brand).length > 0) {
                    for (let key in brands.brand) {
                        const brandValue = brands.brand[key];
                        $('#havenewvehmake').append(`<option value="${brandValue}">${brandValue}</option>`);
                    }
                }
                $('#havenewvehregveh').val(brands.date);

                $('#havenewvehmake').on('change', function() {
                    const selectedBrand = $(this).val();
                    getModel(selectedBrand);
                });
            }
        }

        async function getModel(selectedBrand) {

            const models = await getModelName(selectedBrand);

            if (models && Array.isArray(models)) {
                $('#havenewvehmodelveh').empty();
                $('#havenewvehmodelveh').append(
                    '<option value="" style="display: none;" selected disabled>Select a brand</option>'
                );
                models.forEach((modelObj) => {
                    const model = modelObj.model;
                    $('#havenewvehmodelveh').append(`<option value="${model}">${model}</option>`);
                });
            }
        }
        getBrand();
    </script>
</body>

</html>
