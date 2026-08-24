<?php
use App\Models\Proposal;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Know Bike</title>
    @include('front.partial.csslink')
    <style>
        #knowvehicleinfo h5 {
            float: left;
        }

        .select2-search--dropdown .select2-search__field:focus-visible {
            color: #aaa !important;
            border: 1px solid #aaa !important;
        }

        .bonus-box {
            width: 70px !important;
            border-radius: 50px !important;
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
    </style>
</head>

<body>
    @php
        // dd($data);
    @endphp
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

            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 head text-center mb-3">
                        Bike insurance provides essential coverage against accidents.
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="image">
                            <img src="{{ config('constant.BASE_URL') }}front/images/bike2.jpg" alt="true">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('shriram.knowbikesteptwo') }}" method="POST" id="knowbikeslideTwo"
                            name="knowbikeslideTwo">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row mb-2" id="knowvehicleinfo">
                                        <div class="col-lg-12 mb-3">
                                            <label for="registered" class="largeLabel">{{$data['regnumber']}}</label><br>
                                            <label for="registered" class="largeLabel">{{$data['brand']}}</label><br>
                                            <label for="registered" class="largeLabel">{{$data['model']}}</label>
                                        </div>

                                        <div class="col-lg-8 mb-2">
                                            <label for="registered" class="smallLabel">Registered Under</label>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <small class="smallLabel">{{ $data['under'] }}</small>
                                        </div>

                                        <div class="col-lg-8 mb-2">
                                            <label for="dobregistration" class="smallLabel">Date of
                                                Registration</label>
                                        </div>
                                        <div class="col-lg-4 mb-2 ">
                                            <small class="smallLabel">{{$data['date']}}</small>
                                        </div>
                                        <div class="col-lg-8 mb-2">
                                            <label for="dobcity" class="smallLabel">Policy Status</label>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <small class="smallLabel">Not Expired</small>
                                        </div>
                                    </div>
                                    <div class="row" id="knowsection">
                                        <div class="col-lg-12 col-md-12 col-sm-12" id="ownershipTransferDiv">
                                            <div class="row">
                                                <div class="col-lg-6 mb-3 thirdpartyhide">
                                                    <p class="characters mb-2">Was there any ownership transfer in the
                                                        previous year?</p>
                                                    <div class="radiolabel">
                                                        <label class="switch">
                                                            <input type="checkbox" id="ownershiptoggle"
                                                                name="ownershiptoggle" value="ownershiptoggle"
                                                                onchange="toggleOwnershipDiv()" checked>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>
                                                <div class="col-lg-6 mb-2 thirdpartyhide" id="thirdpartyMain"
                                                    style="display: none;">
                                                    <p class="characters mb-2">Did you make a claim in your previous
                                                        policy
                                                        period? </p>
                                                    <div class="radiolabel">
                                                        <label class="switch">
                                                            <input type="checkbox" id="policytoggle" name="policytoggle"
                                                                value="policytoggle" onchange="policytoggleDiv()">
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
                            <input type="submit" class="d-none" id="knowbikestepTwoSubmit">
                        </form>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <input type="submit" class="getstarted" value="Continue"
                                onClick="knowBikeStarted(event);">
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
        function toggleOwnershipDiv() {
            const checkbox = document.getElementById('ownershiptoggle');
            // const ownershipDiv = document.getElementById('ownershipDiv');
            const thirdpartyhide = document.getElementById('thirdpartyMain');
            if (checkbox.checked) {
                // ownershipDiv.style.display = 'none';
                thirdpartyhide.style.display = 'none';
            } else {
                // ownershipDiv.style.display = 'block';
                thirdpartyhide.style.display = 'block';
            }
        }

        function policytoggleDiv() {
            const policycheckbox = document.getElementById('policytoggle');
            const ownershipDiv = document.getElementById('ownershipDiv');
            if (policycheckbox.checked) {
                ownershipDiv.style.display = 'block';
            } else {
                ownershipDiv.style.display = 'none';
            }
        }

        function knowBikeStarted(event) {
            event.preventDefault();
            $('#knowbikestepTwoSubmit').click();
            return false;
        }
    </script>
</body>

</html>
