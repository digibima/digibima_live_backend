<?php
use App\Models\Proposal;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Bike</title>
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
                            <img src="{{ config('constant.BASE_URL') }}front/images/bike2.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('shriram.newbikesteptwo') }}" method="POST" id="newbikeslide2Form" name="newbikeslide2Form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 mb-1">
                                    <p class="sidepera mb-1">Insurance New Bike</p>

                                    <div class="row" id="newbikerow">
                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                            <div class="havevehnumb mb-2">
                                                <label for="newbikemanu">Manufacture</label>
                                                {{-- <select name="newbikemanu" id="newbikemanu">
                                                    <option value="">Manufacture Vehicle</option>
                                                    <option value="Hero">Hero MotoCorp</option>
                                                    <option value="Honda">Honda Motorcycle and Scooter</option>
                                                    <option value="TVS">TVS Motor Company</option>
                                                    <option value="Bajaj">Bajaj Auto</option>
                                                    <option value="Yamaha">Yamaha Motors</option>
                                                    <option value="Suzuki">Suzuki Motorcycle</option>
                                                </select> --}}
                                                <select name="newbikemanu" id="newbikemanu"
                                                    class="form-control js-example-basic-single" style="width: 100%;">
                                                    <option value="" style="display: none;" selected disabled>
                                                    </option>
                                                
                                                </select>
                                            </div>
                                            @error('newbikemanu')
                                                <div class="error" id="newbikemanuError">{{ $message }}</div>
                                            @enderror
                                            <div class="havevehnumb">
                                                <label for="newbikeregdate">Registration Date</label>
                                                <div class="input-group1 datepickerdiv readonly">
                                                    <input type="text" name="newbikeregdate"
                                                        class="input form-control " id="newbikeregdate"
                                                        value="22-02-2000" autocomplete="off"
                                                        placeholder="Registration Date" spellcheck="false" maxlength="10"
                                                        readonly>

                                                    <button class="btn calendarButton readonly" type="button"
                                                        style="height: 46px ">
                                                        <i class="fa-solid fa-calendar-days"></i>
                                                    </button>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                            <div class="havevehnumb mb-2">
                                                <label for="newbikemodel">Model & Variant</label>
                                                {{-- <select name="newbikemodel" id="newbikemodeltype">
                                                    <option value="">Model Vehicle</option>
                                                    <option value="Hero">Hero Splendor Plus</option>
                                                    <option value="Xtreme">Hero Xtreme 125R</option>
                                                    <option value="Honda">Honda Activa 6G</option>
                                                    <option value="Royal">Royal Enfield Hunter 350</option>
                                                    <option value="Yamaha">Yamaha MT 15 V2</option>
                                                </select> --}}
                                                <select name="newbikemodel" id="newbikemodel"
                                                    class="form-control js-example-basic-single" style="width: 100%;">
                                                    <option value="" style="display: none;" selected disabled>
                                                    </option>
                                                   
                                                </select>
                                            </div>
                                            @error('newbikemodeltype')
                                                <div class="error" id="newbiketypeError">{{ $message }}</div>
                                            @enderror
                                            <div class="havevehnumb mb-2">
                                                <label for="newbikemanuyear">Year Of Manufacture</label>
                                                <select name="newbikemanuyear" id="newbikemanuyear">
                                                    <option value="">Manufacture Year</option>
                                                    <option value="2023">2023</option>
                                                    <option value="2024">2024</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class=" ">
                                                    <div class="havevehnumb mb-2">
                                                        <label for="dontknowregveh">Select registration under</label>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 mt-2">
                                                            <div class="myradio radiolabel">
                                                                <input type="radio" id="newbikeindividual"
                                                                    name="dontregisterunder" value="newbikeindividual"
                                                                    checked>
                                                                <label for="newbikeindividual">Individual</label>
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-6 mt-2">
                                                            <div class="myradio radiolabel">
                                                                <input type="radio" id="newbikecompany"
                                                                    name="dontregisterunder" value="newbikecompany">
                                                                <label for="newbikecompany">Company</label>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 bikecompanysection" style="display: none;">
                                                <div class="carnumber mb-2">
                                                    <label for="newbikecmpnynme">Company Name</label>
                                                    <input type="text" id="newbikecmpnynme" name="newbikecmpnynme"
                                                        value="" placeholder="Enter Company Name">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 bikecompanysection" style="display: none;">
                                                <div class="carnumber mb-2">
                                                    <label for="newbikegstno">GST Number</label>
                                                    <input type="text" id="newbikegstno" name="newbikegstno"
                                                        value="" placeholder="Enter GST Number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <input type="submit" class="d-none" id="newbikestepTwoSubmit">
                        </form>
                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                            <input type="submit" class="getstarted" value="Continue"
                                onClick="NewBikeStarted(event);">
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
        function toggleBikeDetails() {
            const individual = document.querySelector('input[name="dontregisterunder"][value="newbikeindividual"]');
            const company = document.querySelector('input[name="dontregisterunder"][value="newbikecompany"]');
            const companySections = document.querySelectorAll('.bikecompanysection');

            if (company.checked) {
                companySections.forEach(section => {
                    section.style.display = 'inline-block';
                });
                individual.parentNode.classList.remove('activeradio');
                company.parentNode.classList.add('activeradio');
            } else if (individual.checked) {
                companySections.forEach(section => {
                    section.style.display = 'none';
                });
                company.parentNode.classList.remove('activeradio');
                individual.parentNode.classList.add('activeradio');
            }
        }
        document.querySelectorAll('input[name="dontregisterunder"]').forEach(radio => {
            radio.addEventListener('change', toggleBikeDetails);
        });
        toggleBikeDetails();

        $(document).ready(function() {
            $('#newbikemanu').select2({
                placeholder: 'Manufacture Vehicle',
                allowClear: true,
            });
            $('#newbikemodel').select2({
                placeholder: 'Select Model',
                allowClear: true,
            });

        });

        function NewBikeStarted(event) {
            event.preventDefault();

            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox?.querySelector('.error__title');
            const verifiedBox = document.querySelector('.MainverifiyedBox');

            const newmanuveh = document.getElementById('newbikemanu').value.trim();
            const newmodelveh = document.getElementById('newbikemodel').value.trim();
            const newmanuyear = document.getElementById('newbikemanuyear').value.trim();

            const companyGroup = document.querySelector('input[name="dontregisterunder"]:checked');
            const newcmpnyName = document.getElementById('newbikecmpnynme').value.trim();
            const newgstNo = document.getElementById('newbikegstno').value.trim();


            let valid = true;

            verifiedBox.style.display = 'none';
            errorBox.style.display = 'none';
            if (companyGroup.value === 'newbikecompany') {
                if (!newgstNo) {
                    showError(`GST Number must be required.`, '#newbikegstno');
                    valid = false;
                }
                if (!newcmpnyName) {
                    showError(`Company must be required.`, '#newbikecmpnynme');
                    valid = false;
                }

            }

            if (!newmanuyear) {
                showError(`New City Name must be filled.`, '#newbikemanuyear');
                valid = false;
            }


            if (!newmodelveh) {
                showError(`New Bike Model & Variant must be selected.`, '#newbikemodel');
                valid = false;
            }
            if (!newmanuveh) {
                showError(`New Bike Manufacture Vehicle must be selected.`, '#newbikemanu');
                valid = false;
            }





            if (valid) {
                verifiedBox.style.display = 'flex';
                verifiedBox.querySelector('.verifiyed__title').innerText = `Form submitted successfully!`;
                setTimeout(() => {
                    verifiedBox.style.display = 'none';
                }, 3000);
                $('#newbikestepTwoSubmit').click();
                return false;
            }

            function showError(message, focusSelector) {
                errorTitleElement.innerText = message;
                document.querySelector(focusSelector).focus();
                errorBox.style.display = "flex";
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
            }
        }
        async function getBrand() {
            let brands = await getBrandName('BIKE');
            if (brands && brands.date) {
                $('#newbikemanu').empty();
                $('#newbikemanu').append(
                    '<option value="" style="display: none;" selected disabled>Select a brand</option>');

                if (brands.brand && Object.keys(brands.brand).length > 0) {
                    for (let key in brands.brand) {
                        const brandValue = brands.brand[key];
                        $('#newbikemanu').append(`<option value="${brandValue}">${brandValue}</option>`);
                    }
                }
                $('#dontknowregveh').val(brands.date);

                $('#newbikemanu').on('change', function() {
                    const selectedBrand = $(this).val();
                    getModel(selectedBrand);
                });
            }
        }

        async function getModel(selectedBrand) {
  
        const models = await getModelName(selectedBrand);

        if (models && Array.isArray(models)) {
            $('#newbikemodel').empty();
            $('#newbikemodel').append(
                '<option value="" style="display: none;" selected disabled>Select a brand</option>'
            );
            models.forEach((modelObj) => {
                const model = modelObj.model;
                $('#newbikemodel').append(`<option value="${model}">${model}</option>`);
            });
        }
        }
        getBrand();
    </script>
</body>

</html>
