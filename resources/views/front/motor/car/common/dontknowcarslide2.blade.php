<?php

use App\Models\Proposal;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dont Car</title>
    @include('front.partial.csslink')
    <style>
        .bonus-box {
            width: 70px !important;
            border-radius: 50px !important;
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
                        Motor insurance provides essential coverage against accidents.
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="image">
                            <img src="{{ config('constant.BASE_URL') }}front/images/Car-1.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="" method="POST" id="dontknowcarslideTwo" name="dontknowcarslideTwo">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <p class="sidepera mb-1">I dont't know Vehicle</p>
                                    <div class="row" id="dontknowsection">
                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                            <div class="havevehnumb mb-2">
                                                <label for="dontknowmanuveh">Manufacture</label>
                                                <select name="dontknowmanuveh" id="dontknowmanuveh"
                                                    class="form-control js-example-basic-single" style="width: 100%;">
                                                    <option value="" style="display: none;" selected disabled>
                                                    </option>

                                                </select>
                                            </div>
                                            @error('dontknowmanuveh')
                                            <div class="error" id="dontknowmanuvehError">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                            <div class="havevehnumb mb-2">
                                                <label for="dontknowmodelveh">Model & Variant</label>
                                                <select name="dontknowmodelveh" id="dontknowmodelveh"
                                                    class="form-control js-example-basic-single" style="width: 100%;">
                                                    <option value="" style="display: none;" selected disabled>
                                                    </option>

                                                </select>
                                            </div>
                                            @error('dontknowmodelveh')
                                            <div class="error" id="dontknowmodelvehError">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                            <div class="havevehnumb">
                                                <label for="dontknowregveh">Register Date</label>
                                                <div class="input-group1 datepickerdiv ">
                                                    <input type="text" name="dontknowregveh"
                                                        class="input form-control datepicker" id="dontknowregveh"
                                                        autocomplete="off" spellcheck="false" maxlength="10">

                                                    <button class="btn calendarButton" type="button"
                                                        style="height: 46px">
                                                        <i class="fa-solid fa-calendar-days"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                            <div class="havevehnumb mb-2">
                                                <label for="dontknowregveh">Select registration under</label>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 mt-2">
                                                    <div class="myradio radiolabel mb-2">
                                                        <input type="radio" id="individual"
                                                            name="dontregisterunder" value="individual" checked>
                                                        <label for="individual">Individual</label>
                                                    </div>

                                                </div>
                                                <div class="col-lg-6 mt-2">
                                                    <div class="myradio radiolabel mb-2">
                                                        <input type="radio" id="company"
                                                            name="dontregisterunder" value="company">
                                                        <label for="company">Company</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 carcompanysection" style="display: none;">
                                            <div class="carnumber mb-2">
                                                <label for="dontcarcmpnynme">Company Name</label>
                                                <input type="text" id="dontcarcmpnynme" name="dontcarcmpnynme"
                                                    value="" placeholder="Enter Company Name">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 carcompanysection" style="display: none;">
                                            <div class="carnumber mb-2">
                                                <label for="dontcargstno">GST Number</label>
                                                <input type="text" id="dontcargstno" name="dontcargstno"
                                                    value="" placeholder="Enter GST Number">
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
                                                        <input type="radio" id="owndamage" name="prepolitype"
                                                            value="owndamage" onchange="togglePrexpdateSection()">
                                                        <label for="owndamage">Own Damage</label>
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
                            <input type="submit" class="d-none" id="dontknowcarstepTwoSubmit">
                        </form>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <input type="submit" class="getstarted" value="Continue"
                                onClick="DontknowCarStarted(event);">
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
            const Individual = document.querySelector('input[name="dontregisterunder"][value="individual"]');
            const Company = document.querySelector('input[name="dontregisterunder"][value="company"]');
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
                    section.style.display = 'inline-block';
                });
            }
        }
        document.querySelectorAll('input[name="dontregisterunder"]').forEach(radio => {
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
            if (owndamagecheckbox.checked) {
                owndamagecheckbox.parentNode.classList.add('activeradio');
                Prepolexpdatesec.style.display = 'none';
                thirdpartyHides.forEach(hide => hide.style.display = 'block');
                ownershipDiv.style.display = 'block';
            } else if (thirdPartycheckbox.checked) {
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
            $('#dontknowmanuveh').select2({
                placeholder: 'Manufacture Vehicle',
                allowClear: true,
            });
            $('#dontknowmodelveh').select2({
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

        function DontknowCarStarted(event) {
            event.preventDefault();

            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox?.querySelector('.error__title');
            const verifiedBox = document.querySelector('.MainverifiyedBox');

            const dontknowmanuveh = document.getElementById('dontknowmanuveh').value.trim();
            const dontknowmodelveh = document.getElementById('dontknowmodelveh').value.trim();
            const dontknowregveh = document.getElementById('dontknowregveh').value.trim();
            const prePolicy = document.querySelector('input[name="prepolitype"]:checked');
            const Comprehensivedate = document.getElementById('Prepolexpdate').value.trim();

            const companycheckbox = document.querySelector('input[name="dontregisterunder"]:checked');
            const dontcarcmpnynme = document.getElementById('dontcarcmpnynme').value.trim();
            const dontcargstno = document.getElementById('dontcargstno').value.trim();

            let valid = true;
            verifiedBox.style.display = 'none';
            errorBox.style.display = 'none';

            if (prePolicy?.value === 'thirdparty' && !Comprehensivedate) {
                valid = showError(`Previous Policy Date must be filled.`, '#Prepolexpdate') && (valid = false);
            }

            if (companycheckbox?.value === 'company' && !dontcargstno) {
                valid = showError(`GST Number  must be filled.`, '#dontcargstno') && (valid = false);
            }
            if (companycheckbox?.value === 'company' && !dontcarcmpnynme) {
                valid = showError(`Company Name must be filled.`, '#dontcarcmpnynme') && (valid = false);
            }

            if (prePolicy?.value === 'comprehensive' && !Comprehensivedate) {
                valid = showError(`Previous Policy Date must be filled.`, '#Prepolexpdate') && (valid = false);
            }

            if (!dontknowregveh) {
                valid = showError(`Don't Know Date must be selected.`, '#dontknowregveh') && (valid = false);
            }

            if (!dontknowmodelveh) {
                valid = showError(`Don't Know Model & Variant must be selected.`, '#dontknowmodelveh') && (valid = false);
            }

            if (!dontknowmanuveh) {
                valid = showError(`Don't Know Manufacture Vehicle must be selected.`, '#dontknowmanuveh') && (valid =
                    false);
            }

            if (valid) {
                verifiedBox.style.display = 'flex';
                verifiedBox.querySelector('.verifiyed__title').innerText = `Form submitted successfully!`;
                setTimeout(() => {
                    verifiedBox.style.display = 'none';
                }, 3000);
                $('#dontknowcarstepTwoSubmit').click();
                return false;
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
                $('#dontknowmanuveh').empty();
                $('#dontknowmanuveh').append(
                    '<option value="" style="display: none;" selected disabled>Select a brand</option>');

                if (brands.brand && Object.keys(brands.brand).length > 0) {
                    for (let key in brands.brand) {
                        const brandValue = brands.brand[key];
                        $('#dontknowmanuveh').append(`<option value="${brandValue}">${brandValue}</option>`);
                    }
                }
                $('#dontknowregveh').val(brands.date);

                $('#dontknowmanuveh').on('change', function() {
                    const selectedBrand = $(this).val();
                    getModel(selectedBrand);
                });
            }
        }

        async function getModel(selectedBrand) {

            const models = await getModelName(selectedBrand);

            if (models && Array.isArray(models)) {
                $('#dontknowmodelveh').empty();
                $('#dontknowmodelveh').append(
                    '<option value="" style="display: none;" selected disabled>Select a brand</option>'
                );
                models.forEach((modelObj) => {
                    const model = modelObj.model;
                    $('#dontknowmodelveh').append(`<option value="${model}">${model}</option>`);
                });
            }
        }
        getBrand();
    </script>
</body>

</html>