<?php
use App\Models\Proposal;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Car</title>
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
                        <form action="{{ route('shriram.newcarsteptwo') }}" method="POST" id="newcarslide2"
                            name="carslide2Form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                    <p class="sidepera mb-1">New Car Insurance</p>

                                    <div class="row" id="knowcarrow">

                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                            <div class="havevehnumb mb-2">
                                                <label for="newcarmanu">Manufacture</label>

                                                <select name="newcarmanu" id="newcarmanu"
                                                    class="form-control js-example-basic-single" style="width: 100%;">
                                                    <option value="" style="display: none;" selected disabled>
                                                    </option>

                                                </select>
                                            </div>
                                            @error('newcarmanu')
                                                <div class="error" id="newcarmanuError">{{ $message }}</div>
                                            @enderror

                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                            <div class="havevehnumb mb-2">
                                                <label for="newcarmodel">Model & Variant</label>

                                                <select name="newcarmodel" id="newcarmodel"
                                                    class="form-control js-example-basic-single" style="width: 100%;">

                                                </select>
                                            </div>
                                            @error('newcarmodel')
                                                <div class="error" id="newcarmodelError">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                            <div class="havevehnumb">
                                                <label for="newcarregdate">Register Date</label>
                                                <div class="input-group1 datepickerdiv readonly">
                                                    <input type="text" name="newcarregdate"
                                                        class="input form-control " id="newcarregdate"
                                                        autocomplete="off" spellcheck="false" maxlength="10" readonly>

                                                    <button class="btn calendarButton readonly" type="button"
                                                        style="height: 46px ">
                                                        <i class="fa-solid fa-calendar-days"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-2">

                                            <div class="havevehnumb mb-2">
                                                <label for="newcarmanuyear">Year Of Manufacture</label>
                                                <select name="newcarmanuyear" id="newcarmanuyear">
                                                    <option value="">Select Year</option>
                                                    <option value="2023">{{ date('Y') - 1 }}</option>
                                                    <option value="2024">{{ date('Y') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                            <div class="row">
                                                <div class="col-lg-6 mb-2">
                                                    <div class="myradio radiolabel activeradio mb-2">
                                                        <input type="radio" id="newcarindividual" name="newcarowner"
                                                            value="newcarindividual" onchange="toggleNewCarIndi()"
                                                            checked>
                                                        <label for="newcarindividual">Individual</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-2">
                                                    <div class="myradio radiolabel mb-2">
                                                        <input type="radio" id="newcarcompany" name="newcarowner"
                                                            value="newcarcompany" onchange="toggleNewCarIndi()">
                                                        <label for="newcarcompany">Company</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 newcarcompanyDiv mb-3" style="display: none;">
                                            <div class="carnumber">
                                                <label for="newcarcmpnynme">Company Name</label>
                                                <input type="text" id="newcarcmpnynme" name="newcarcmpnynme"
                                                    placeholder="Enter Company Name">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 newcarcompanyDiv mb-3" style="display: none;">
                                            <div class="carnumber">
                                                <label for="newcargstno">GST Number</label>
                                                <input type="text" id="newcargstno" name="newcargstno"
                                                    placeholder="Enter GST Number">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <input type="submit" class="d-none" id="newcarstepTwoSubmit">
                        </form>
                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                            <input type="submit" class="getstarted" value="Continue"
                                onClick="NewCarStarted(event);">
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
        $(document).ready(function() {
            $('#newcarmanu').select2({
                placeholder: 'Manufacture Vehicle',
                allowClear: true,
            });
            $('#newcarmodel').select2({
                placeholder: 'Select Model',
                allowClear: true,
            });

        });

        function toggleNewCarIndi() {
            const Individual = document.querySelector('input[name="newcarowner"][value="newcarindividual"]');
            const Company = document.querySelector('input[name="newcarowner"][value="newcarcompany"]');
            const companySections = document.querySelectorAll('.newcarcompanyDiv');
            const newcarindividualDiv = Individual.parentElement;
            const newcarcompanyDiv = Company.parentElement;

            if (Individual.checked) {
                newcarindividualDiv.classList.add('activeradio');
                newcarcompanyDiv.classList.remove('activeradio');
                companySections.forEach(section => {
                    section.style.display = 'none';
                });
            } else if (Company.checked) {
                newcarcompanyDiv.classList.add('activeradio');
                newcarindividualDiv.classList.remove('activeradio');
                companySections.forEach(section => {
                    section.style.display = 'inline-block';
                });
            }
        }

        // Attach event listeners to the radio buttons
        document.querySelectorAll('input[name="newcarowner"]').forEach((radio) => {
            radio.addEventListener('change', toggleNewCarIndi);
        });

        // Ensure this function is called after the DOM is loaded
        document.addEventListener("DOMContentLoaded", function() {
            toggleNewCarIndi(); // Initial call to set state correctly
        });


        function NewCarStarted(event) {
            event.preventDefault();

            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox ? errorBox.querySelector('.error__title') : null;
            const verifiedBox = document.querySelector('.MainverifiyedBox');

            const newcarmanuElement = document.getElementById('newcarmanu');
            const newcarmodeltypeElement = document.getElementById('newcarmodel');
            const newcarmanuyearElement = document.getElementById('newcarmanuyear');

            // Check if elements exist before accessing their values
            const newcarmanu = newcarmanuElement ? newcarmanuElement.value.trim() : '';
            const newcarmodeltype = newcarmodeltypeElement ? newcarmodeltypeElement.value.trim() : '';
            const newcarmanuyear = newcarmanuyearElement ? newcarmanuyearElement.value.trim() : '';

            let valid = true;

            verifiedBox.style.display = 'none';
            errorBox.style.display = 'none';

            if (!newcarmanuyear) {
                showError(`New Car Year Of Manufacture must be selected.`, '#newcarmanuyear');
                valid = false;
            }

            if (!newcarmodeltype) {
                showError(`New Car Model & Variant must be selected.`, '#newcarmodel');
                valid = false;
            }

            if (!newcarmanu) {
                showError(`New Car Manufacture Vehicle must be selected.`, '#newcarmanu');
                valid = false;
            }

            if (valid) {
                verifiedBox.style.display = 'flex';
                verifiedBox.querySelector('.verifiyed__title').innerText = `Form submitted successfully!`;
                setTimeout(() => {
                    verifiedBox.style.display = 'none';
                }, 3000);
                $('#newcarstepTwoSubmit').click();
                return false;
            }

            function showError(message) {
                if (errorTitleElement) {
                    errorTitleElement.innerText = message;
                    errorBox.style.display = "flex";
                    setTimeout(() => {
                        errorBox.style.display = 'none';
                    }, 5000);
                }
            }

        }

        async function getBrand() {
            let brands = await getBrandName('CAR');
            if (brands && brands.date) {
                $('#newcarmanu').empty();
                $('#newcarmanu').append(
                    '<option value="" style="display: none;" selected disabled>Select a brand</option>');

                if (brands.brand && Object.keys(brands.brand).length > 0) {
                    for (let key in brands.brand) {
                        const brandValue = brands.brand[key];
                        $('#newcarmanu').append(`<option value="${brandValue}">${brandValue}</option>`);
                    }
                }
                $('#newcarregdate').val(brands.date);

                $('#newcarmanu').on('change', function() {
                    const selectedBrand = $(this).val();
                    getModel(selectedBrand);
                });
            }
        }

        async function getModel(selectedBrand) {

            const models = await getModelName(selectedBrand);

            if (models && Array.isArray(models)) {
                $('#newcarmodel').empty();
                $('#newcarmodel').append(
                    '<option value="" style="display: none;" selected disabled>Select a brand</option>'
                );
                models.forEach((modelObj) => {
                    const model = modelObj.model;
                    $('#newcarmodel').append(`<option value="${model}">${model}</option>`);
                });
            }
        }
        getBrand();
    </script>
</body>

</html>
