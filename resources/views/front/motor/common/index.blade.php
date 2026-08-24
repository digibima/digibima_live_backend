<?php

use App\Models\Proposal;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\SystemController;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Mototor Vichele Plans</title>
    @include('front.partial.csslink')
    <style>
        .info-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }

        .info-card h2 {
            margin-bottom: 10px;
            color: #333;
            font-size: 18px;
            font-weight: bold;
        }

        .card_info {
            margin: 10px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* border-bottom: 1px solid #10508F; */
        }

        .card_info span {
            font-size: 14px;
            font-weight: 500;
            color: #10508F;
        }

        .edit-icon {
            cursor: pointer;
            color: #007bff;
            font-size: 16px;
        }

        .highlight {
            color: #000 !important;

        }

        hr {
            color: 10508F !important;
            border: 1px solid #10508F !important;
            background: #000 !important;
        }







        .btn-check:checked+.btn,
        .btn.active,
        .btn.show,
        .btn:first-child:active,
        :not(.btn-check)+.btn:active {
            color: var(--bs-btn-active-color);
            background-color: var(--bs-btn-active-bg);
            border-color: #f1f1f1;
        }


        .modalbtn {
            padding: 7px 15px;
            font-size: 13.5px;
            color: #fff;
            background: #0b4c8d;
            font-weight: 300;
            border-radius: 7px;
            text-decoration: none;
        }

        .modalbtn:hover {
            color: #fff !important;
        }

        #showcar-details .modal-dialog {
            max-width: 650px;
            margin: 6rem auto;
        }

        #editcar-details .modal-dialog {
            max-width: 750px;
            padding: 10px;
            margin: 6rem auto;
        }

        .vehicle-box1 {
            width: 190px !important;
        }

        .editMobile {
            color: #000;
            margin-left: 10px;
            cursor: pointer;
        }

        .city-list {
            width: 265px;
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
            width: 16rem;
            height: 3rem;
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
            width: 19.3rem;
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
            /* background-color: #1C5FA8; */
            background: linear-gradient(135deg, #4e54c8, #8f94fb) !important;
            color: #fff;
            border-color: linear-gradient(135deg, #4e54c8, #8f94fb) !important;
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
            z-index: 9999;
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

            #findtopplan .head {
                font-size: 24px;
                line-height: 36px;
            }

            .col-lg-6,
            .col-md-6,
            .col-sm-12 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .vehicle-box {
                width: 80px;
            }

            .vehicle-box1 {
                width: 150px !important;
            }
        }

        .motoricon {
            margin-left: 20px !important;
        }
    </style>

</head>

<body>
    @include('front.partial.header')
    @php
        // dd($data);
    @endphp

    <div id="loader">
        <div class="loader-container">
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
            <div class="dot dot-3"></div>
            <div class="dot dot-4"></div>
        </div>
    </div>
    {{--
    {{ $mobile }} --}}

    <div class="MainErrorBox" style="float: right;display:none;"><span class="error__icon"><i
                class="fa-solid fa-circle-exclamation"></i></span>
        <p class="error__title mb-0 " style="margin-right:10px;">Self cannot be combined with Father or Mother.</p><span
            class="error__close "><i class="fa-solid fa-xmark  mr-3"></i></span>
    </div>
    <div class="MainverifiyedBox" style="float: right;display:none;"><span class="verifiy__icon"><i
                class="fa-solid fa-circle-check"></i></span>
        <p class="verifiyed__title  mb-0" style="margin-right:10px;">hello</p> <span class="verifiyed__close"><i
                class="fa-solid fa-xmark"></i></span>
    </div>
    <main id="slider-container">
        <section class="slide" id="findtopplan">

            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 head text-center mb-2">
                        Motor insurance provides essential coverage against accidents.
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="image">
                            <img src="{{ config('constant.BASE_URL') }}front/images/motorMain.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 mt-2">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 mb-2 ">

                                <div class="vehicle-container mb-3 mt-2">
                                    <label class="vehicle-label mb-2">
                                        <input type="radio" name="button-group" value="car" checked>
                                        <div class="vehicle-box">Car<i class="fa-solid fa-car motoricon"></i></div>
                                    </label>
                                    <label class="vehicle-label mb-2">
                                        <input type="radio" name="button-group" value="bike">
                                        <div class="vehicle-box">Bike<i class="fa-solid fa-motorcycle motoricon"></i>
                                        </div>
                                    </label>
                                    <label class="vehicle-label mb-2">
                                        <input type="radio" name="button-group" value="commercial">
                                        <div class="vehicle-box vehicle-box1">Commercial<i
                                                class="fa-solid fa-tractor motoricon"></i></div>
                                    </label>

                                </div>
                                <div class="row" id="carInsureDetails">
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                                                <div class="radiolabel">
                                                    <input type="radio" id="knowcar" name="knowcar" value="knowcar"
                                                        checked>
                                                    <label for="knowcar">Know car No.</label>
                                                </div>
                                            </div>
                                            <!-- <div class="col-lg-4 col-md-8 col-sm-12 mb-2">
                                                <div class="radiolabel">
                                                    <input type="radio" id="dontknowcar" name="knowcar"
                                                        value="dontknowcar">
                                                    <label for="dontknowcar">Don't Know car No.</label>
                                                </div>
                                            </div> -->
                                            <div class="col-lg-3 col-md-4 col-sm-12 mb-2">
                                                <div class="radiolabel">
                                                    <input type="radio" id="newcar" name="knowcar" value="newcar">
                                                    <label for="newcar">New car</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="knowcarrow" style="display: flex">
                                        <form action="{{ route('car.knowcar') }}" id="knowcarForm" method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                                    <div class="carnumber mb-2">
                                                        <label for="carregnumber">Car Registration Number</label>
                                                        <input type="text" id="carregnumber" class="registrnobInput"
                                                            name="carregnumber"
                                                            value="{{ $data['carnumber'] ? $data['carnumber'] : '' }}"
                                                            placeholder="Enter Registration Number"
                                                            oninput="registrNumbInput()">
                                                    </div>

                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">

                                                    <div class="mobile mb-2" id="knowcargetOtpSection">


                                                        <label for="knowcarmobile">Mobile Number</label>
                                                        <input type="text" id="knowcarmobile" name="knowcarmobile"
                                                            class="mobiletenInput" value="{{ $mobile }}"
                                                            placeholder="Enter Mobile Number"
                                                            oninput="validateInput(this, 10, 'verifycarButton')"
                                                            maxlength="10" readonly disabled>
                                                        <!-- <button type="button" id="verifycarButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showknowcarOtpSection()">Verify</button> -->


                                                    </div>
                                                </div>
                                                <input type="submit" class="d-none" id="knowcarFormSubmit">
                                            </div>
                                        </form>
                                    </div>

                                    <div class="row" id="dontknowcarrow" style="display: none">
                                        <form action="" id="dontknowcarForm" method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                                    <div class="carnumber mb-2">
                                                        <label for="dontknowcarcity">City Name</label>
                                                        <input type="text" id="dontknowcarcity"
                                                            name="dontknowcarcity" value=""
                                                            placeholder="Enter City Name" oninput="getCityName()">
                                                        <div class="city-list" class="mb-5"></div>
                                                    </div>

                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12  mb-2">

                                                    <div class="mobile mb-2 " id="dontknowcargetOtpSection">

                                                        <label for="dontknowcarmobile">Mobile Number</label>
                                                        <input type="text" id="dontknowcarmobile"
                                                            name="dontknowcarmobile" class="mobiletenInput"
                                                            value="{{ $mobile }}"
                                                            placeholder="Enter Mobile Number"
                                                            oninput="validatedontknowcarInput(this, 10)"
                                                            maxlength="10" readonly disabled>



                                                    </div>
                                                </div>

                                                <input type="submit" class="d-none" value="clk"
                                                    id="dontknowcarFormSubmit">
                                            </div>
                                        </form>
                                    </div>

                                    <div class="row" id="newcarrow" style="display: none">
                                        <form action="{{ route('car.newcar') }}" id="newcarForm" method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                                    <div class="carnumber mb-2">
                                                        <label for="newcarcity">City Name</label>
                                                        <input type="text" id="newcarcity" name="newcarcity"
                                                            value="" placeholder="Enter City Name"
                                                            oninput=getCityName()>
                                                        <div class="city-list" class="mb-5"></div>
                                                    </div>

                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">

                                                    <div class="mobile mb-2" id="newcargetOtpSection">

                                                        <label for="newcarmobile">Mobile Number</label>
                                                        <input type="text" id="newcarmobile" name="newcarmobile"
                                                            class="mobiletenInput" value="{{ $mobile }}"
                                                            placeholder="Enter Mobile Number"
                                                            oninput="validateNewcarInput(this, 10)" maxlength="10"
                                                            readonly disabled>
                                                        <!-- <button type="button" id="verifynewcarButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showNewcarOtpSection()">Verify</button> -->


                                                    </div>
                                                </div>
                                                <input type="submit" class="d-none" id="newcarFormSubmit">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row" id="bikeInsureDetails" style="display:none;">
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 col-sm-12 mb-2">
                                                <div class="radiolabel">
                                                    <input type="radio" id="knowbike" name="knowbike"
                                                        value="knowbike" checked>
                                                    <label for="knowbike">Know Bike No.</label>
                                                </div>
                                            </div>
                                            <!-- <div class="col-lg-4 col-md-8 col-sm-12 mb-2">
                                                <div class="radiolabel">
                                                    <input type="radio" id="dontknowbike" name="knowbike"
                                                        value="dontknowbike">
                                                    <label for="dontknowbike">Don't Know Bike No.</label>
                                                </div>
                                            </div> -->
                                            <div class="col-lg-3 col-md-4 col-sm-12 mb-2">
                                                <div class="radiolabel">
                                                    <input type="radio" id="newbike" name="knowbike"
                                                        value="newbike">
                                                    <label for="newbike">New Bike</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="knowbikerow" style="display: flex;">
                                        <form action="{{ route('bike.knowbike') }}" id="knowbikeForm" method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                                    <div class="carnumber mb-2">
                                                        <label for="bikeregnumber">Bike Registration Number</label>
                                                        <input type="text" id="bikeregnumber"
                                                            class="registrnobInput" name="bikeregnumber"
                                                            placeholder="Enter Registration Number"
                                                            value="{{ $data['bikenumber'] ? $data['bikenumber'] : '' }}"
                                                            oninput="registrNumbInput()">
                                                    </div>

                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">

                                                    <div class="mobile mb-2" id="knowbikegetOtpSection">

                                                        <label for="knowbikemobile">Mobile Number</label>
                                                        <input type="text" id="knowbikemobile"
                                                            name="knowbikemobile" class="mobiletenInput"
                                                            placeholder="Enter Mobile Number"
                                                            value="{{ $mobile }}"
                                                            oninput="validateInput(this, 10, 'verifybikeButton')"
                                                            maxlength="10" readonly disabled>
                                                        <!-- <button type="button" id="verifybikeButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showknowbikeOtpSection()">Verify</button> -->


                                                    </div>
                                                </div>
                                                <input type="submit" class="d-none" id="knowbikeFormSubmit">
                                            </div>
                                        </form>
                                    </div>

                                    <div class="row" id="dontknowbikerow" style="display: none;">
                                        <form action="{{ route('bike.newbike') }}" id="dontknowbikeForm"
                                            method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                                    <div class="carnumber mb-2">
                                                        <label for="dontknowbikecity">City Name</label>
                                                        <input type="text" id="dontknowbikecity"
                                                            name="dontknowbikecity" placeholder="Enter City Name"
                                                            oninput="getCityName()">
                                                        <div class="city-list" class="mb-5"></div>
                                                    </div>

                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">

                                                    <div class="mobile mb-2" id="dontknowbikegetOtpSection">


                                                        <label for="dontknowbikemobile">Mobile Number</label>
                                                        <input type="text" id="dontknowbikemobile"
                                                            name="dontknowbikemobile" class="mobiletenInput"
                                                            placeholder="Enter Mobile Number"
                                                            value="{{ $mobile }}"
                                                            oninput="validatedontknowBikeInput(this, 10)"
                                                            maxlength="10" readonly disabled>
                                                        <!-- <button type="button" id="verifydontbikeButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showdontknowbikeOtpSection()">Verify</button> -->

                                                    </div>
                                                </div>
                                                <input type="submit" class="d-none" id="dontknowbikeFormSubmit">
                                            </div>
                                        </form>

                                    </div>

                                    <div class="row" id="newbikerow" style="display: none;">
                                        <form action="{{ route('bike.newbike') }}" id="newbikeForm" method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                                    <div class="carnumber mb-2">
                                                        <label for="newbikecity">City Name</label>
                                                        <input type="text" id="newbikecity" name="newbikecity"
                                                            placeholder="Enter City Name" oninput="getCityName()">
                                                        <div class="city-list" class="mb-5"></div>
                                                    </div>

                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">

                                                    <div class="mobile mb-2" id="newbikegetOtpSection">


                                                        <label for="newbikemobile">Mobile Number</label>
                                                        <input type="text" id="newbikemobile" name="newbikemobile"
                                                            class="mobiletenInput" placeholder="Enter Mobile Number"
                                                            value="{{ $mobile }}"
                                                            oninput="validateNewBikeInput(this, 10)" maxlength="10"
                                                            readonly disabled>
                                                        <!-- <button type="button" id="verifynewbikeButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showNewbikeOtpSection()">Verify</button> -->

                                                    </div>
                                                </div>
                                                <input type="submit" class="d-none" id="newbikeFormSubmit">
                                            </div>
                                        </form>

                                    </div>
                                </div>

                                <div class="row" id="commercialInsureDetails" style="display:none;">
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                                        <div class="row">
                                            <div class="col-lg-5 col-md-12 col-sm-12 mb-2">
                                                <div class="radiolabel">
                                                    <input type="radio" id="havevehicle" name="commercial"
                                                        value="havevehicle" checked>
                                                    <label for="havevehicle">Have Vehicle Reg No.</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-5 col-md-12 col-sm-12 mb-2">
                                                <div class="radiolabel">
                                                    <input type="radio" id="newvehicle" name="commercial"
                                                        value="newvehicle">
                                                    <label for="newvehicle">New Vehicle Insurance</label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row" id="havevehiclerow" style="display: flex">
                                        <form action="{{ route('commercial.root') }}" id="havevehicleForm"
                                            method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12">
                                                    <div class="havevehnumb mb-2">
                                                        <label for="havevehnumb">Registration Number</label>
                                                        <input type="text" id="havevehnumb"
                                                            class="registrnobInput" name="havevehnumb" value=""
                                                            placeholder="Enter Registration Number"
                                                            oninput="registrNumbInput()">
                                                    </div>
                                                    @error('havevehnumb')
                                                        <div class="error" id="havevehnumbError">{{ $message }}
                                                        </div>
                                                    @enderror

                                                    <div class="havevehnumb mb-2">
                                                        <label for="havevehtype">Vehicle Type</label>
                                                        <select id="havevehtype">
                                                            <option value="">Vehicle Type</option>
                                                            <option value="pccv">Passenger Carrying Commercial
                                                                Vehicle
                                                            </option>
                                                            <option value="gccv">Goods Carrying Commercial Vehicles
                                                            </option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12">

                                                    <div class="mobile mb-2" id="havevehiclegetOtpSection">


                                                        <label for="havevehmobile">Mobile Number</label>
                                                        <input type="text" id="havevehmobile" name="havevehmobile"
                                                            class="mobiletenInput" value="{{ $mobile }}"
                                                            placeholder="Enter Mobile Number"
                                                            oninput="validateHaveVehInput(this, 10)" maxlength="10"
                                                            readonly disabled>
                                                        <!-- <button type="button" id="verifyhavevehButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showHaveVehOtpSection()">Verify</button> -->


                                                    </div>
                                                    @error('havevehmobile')
                                                        <div class="error" id="havevehmobileError">{{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>
                                                <input type="submit" class="d-none" id="havevehicleFormSubmit">
                                            </div>
                                        </form>

                                    </div>
                                    <div class="row" id="newvehiclerow" style="display: none">
                                        <form action="{{ route('newcommercial.root') }}" id=""
                                            method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12">
                                                    <div class="carnumber mb-2">
                                                        <label for="newvehiclecity">City Name</label>
                                                        <input type="text" id="newvehiclecity"
                                                            name="newvehiclecity" value=""
                                                            placeholder="Enter City Name" oninput="getCityName()">
                                                        <div class="city-list" class="mb-5"></div>
                                                    </div>
                                                    @error('newvehiclecity')
                                                        <div class="error" id="newvehiclecityError">{{ $message }}
                                                        </div>
                                                    @enderror

                                                    <div class="newvehiclenum mb-2">
                                                        <label for="newvehiclenum">Vehicle Type</label>
                                                        <select name="newvehicletype" id="newvehicletype">
                                                            <option value="">Vehicle Type</option>
                                                            <option value="pccv">Passenger Carrying Commercial
                                                                Vehicle
                                                            </option>
                                                            <option value="gccv">Goods Carrying Commercial Vehicles
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12">

                                                    <div class="mobile mb-2" id="newvehiclegetOtpSection">


                                                        <label for="newvehiclemob">Mobile Number</label>
                                                        <input type="text" id="newvehiclemob" name="newvehiclemob"
                                                            class="mobiletenInput" value="{{ $mobile }}"
                                                            placeholder="Enter Mobile Number"
                                                            oninput="validateNewvehcInput(this, 10)" maxlength="10"
                                                            readonly disabled>
                                                        <!-- <button type="button" id="verifynewvehButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showHaveNewVehOtpSection()">Verify</button> -->

                                                    </div>
                                                    @error('newvehiclemob')
                                                        <div class="error" id="newvehiclemobError">{{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>
                                                <input type="submit" class="d-none" id="havenewvehicleFormSubmit">
                                            </div>
                                        </form>

                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                    <input type="submit" id="mainsubmit" class="getstarted" value="Continue"
                                        onClick="handleGetStarted(event);">

                                    <p class="formbtn">Already bought a policy from DigiBima? <a href="#">Renew
                                            Now</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
        </section>

        <!-- Car Details Modal -->
        <div class="modal fade" id="showcar-details" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog rounded-3">
                <div class="modal-content rounded-3" style="text-align: left;">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">We fetched the details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="modal-body">
                        <h5 style="text-align: left;">Confirm Your Car Details</h5>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 ">
                                <div class="card_info">
                                    <span>Manufacture:</span>
                                    <span class="highlight">vfgg</span>
                                </div>

                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="card_info">
                                    <span>Model & Variant:</span>
                                    <span class="highlight">vfgg</span>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="card_info">
                                    <span>Register Date:</span>
                                    <span class="highlight">vfgg</span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="card_info">
                                    <span>Year Of Manufacture:</span>
                                    <span class="highlight">vfgg</span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <a href="#" id="openEditCarModal" class="modalbtn" data-bs-toggle="modal"
                            data-bs-target="#editcar-details">Edit</a>
                        <a href="{{ route('car.knowcar') }}" id="closeCarDetails" class="modalbtn">Continue</a>
                        <!-- <button type="button" id="closeCarDetails" class="modalbtn" data-bs-dismiss="modal">Close</button> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Car Details Modal -->
        <div class="modal fade" id="editcar-details" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog rounded-3">
                <div class="modal-content rounded-3" style="text-align: left;">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Edit your car details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('car.knowcar') }}" method="POST" id="editcarinfo"
                            name="editcarinfoForm">
                            @csrf
                            <div class="row" id="knowcarrow">

                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                    <div class="havevehnumb mb-2">
                                        <label for="brand">Manufacture</label>

                                        <select name="brand" id="brand"
                                            class="form-control js-example-basic-single" style="width: 100%;">
                                            <option value="" style="display: none;" selected disabled>
                                            </option>

                                        </select>
                                    </div>
                                    @error('brand')
                                        <div class="error" id="brandError">{{ $message }}</div>
                                    @enderror

                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                    <div class="havevehnumb mb-2">
                                        <label for="model">Model & Variant</label>

                                        <select name="model" id="model"
                                            class="form-control js-example-basic-single" style="width: 100%;">

                                        </select>
                                    </div>
                                    @error('model')
                                        <div class="error" id="modelError">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                    <div class="havevehnumb">
                                        <label for="newcarregdate">Register Date</label>
                                        <div class="input-group1 datepickerdiv readonly">
                                            <input type="text" name="newcarregdate"
                                                class="input form-control datepicker" value="10-12-2025"
                                                id="newcarregdate" autocomplete="off" spellcheck="false"
                                                maxlength="10">

                                            <button class="btn calendarButton readonly" type="button"
                                                style="height: 43px ">
                                                <i class="fa-solid fa-calendar-days"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2">

                                    <div class="havevehnumb mb-2">
                                        <label for="brandyear">Year Of Manufacture</label>
                                        <select name="brandyear" id="brandyear">
                                            <option value="">Select Year</option>
                                            <option value="2023">{{ date('Y') - 1 }}</option>
                                            <option value="2024">{{ date('Y') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                    <div class="row ">
                                        <div class="havevehnumb mb-1">
                                            <label for="brandyear">Car Register Under</label>
                                        </div>

                                        <div class="col-lg-6 mb-2">
                                            <div class="myradio radiolabel activeradio mb-2">
                                                <input type="radio" id="newcarindividual" name="newcarowner"
                                                    value="newcarindividual" onchange="toggleNewCarIndi()" checked>
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
                                <div class="col-lg-12 newcarcompanyDiv" style="display: none;">
                                    <div class="row">
                                        <div class="col-lg-6  mb-3">
                                            <div class="carnumber">
                                                <label for="newcarcmpnynme">Company Name</label>
                                                <input type="text" id="newcarcmpnynme" name="newcarcmpnynme"
                                                    placeholder="Enter Company Name">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="carnumber">
                                                <label for="newcargstno">GST Number</label>
                                                <input type="text" id="newcargstno" name="newcargstno"
                                                    placeholder="Enter GST Number">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="submit" class="d-none" id="editcarFormSubmit">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer border-0">
                        <a href="#" class="modalbtn" data-bs-dismiss="modal">Close</a>
                        <a href="{{ route('car.knowcar') }}" id="closeCarDetails" class="modalbtn"
                            onClick="NewCarStarted(event);">Continue</a>

                        <!-- <button type="button" id="saveCarDetails" class="modalbtn">Save</button>
                        <button type="button" class="modalbtn" data-bs-dismiss="modal">Close</button> -->
                    </div>
                </div>
            </div>
        </div>



    </main>
    @include('front.partial.chatwidget')
    @include('front.partial.footer')
    @include('front.partial.jslink')
    <script src="{{ config('constant.BASE_URL') }}front/js/validateFields.js"></script>
    <script>
        window.addEventListener("load", function() {
            document.getElementById("loader").style.display = "none";
        });

        window.onbeforeunload = function() {
            document.getElementById("loader").style.display = "flex";
        };
        $(document).ready(function() {
            // Initialize Select2 when modal opens to prevent issues
            $('#editcar-details').on('shown.bs.modal', function() {
                $('#brand').select2({
                    placeholder: 'Manufacture Vehicle',
                    allowClear: true,
                    dropdownParent: $('#editcar-details') // Fixes modal display issues
                });

                $('#model').select2({
                    placeholder: 'Select Model',
                    allowClear: true,
                    dropdownParent: $('#editcar-details') // Fixes modal display issues
                });
            });



            function toggleNewCarIndi() {
                if ($('#newcarcompany').is(':checked')) {
                    $('.newcarcompanyDiv').show();
                } else {
                    $('.newcarcompanyDiv').hide();
                }
            }
            $('input[name="newcarowner"]').change(toggleNewCarIndi);
            toggleNewCarIndi();
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



        //$('#dontknowcarFormSubmit').click();
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[name="button-group"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
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


        function validateNewcarInput(input, maxLengnewvehicleotpSectionth) {
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

        async function handleGetStarted(event) {
            event.preventDefault();
            const regRegex = /^[A-Z]{2}[0-9]{2}[A-Z]{1,2}[0-9]{4}$/;

            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox?.querySelector('.error__title');
            const verifiedBox = document.querySelector('.MainverifiyedBox');

            const vehicleGroup = document.querySelector('input[name="button-group"]:checked');
            const knowCarGroup = document.querySelector('input[name="knowcar"]:checked');
            const knowBikeGroup = document.querySelector('input[name="knowbike"]:checked');
            const CommercialGroup = document.querySelector('input[name="commercial"]:checked');
            const carRegNumber = document.getElementById('carregnumber').value.trim();

            // console.log(vehicleGroup);
            if (vehicleGroup.value === "bike") {
                knowCarGroup.value = "";
            } else if (vehicleGroup.value === "car") {
                knowBikeGroup.value = "";
            } else if (vehicleGroup.value === "commercial") {
                knowCarGroup.value = "";
                knowBikeGroup.value = "";
            }



            // console.log(carRegNumber);
            // console.log(mobileNumber);
            // console.log(knowCarGroup.value);
            // console.log(knowBikeGroup);
            //return false;
            //console.log(vehicleGroup);
            const dontknowcarCity = document.getElementById('dontknowcarcity').value.trim();

            const newcarCity = document.getElementById('newcarcity').value.trim();


            const KnowBikeRegNumber = document.getElementById('bikeregnumber').value.trim();

            const dontknowBikeCity = document.getElementById('dontknowbikecity').value.trim();

            const newBikeCity = document.getElementById('newbikecity').value.trim();


            // commercial

            const commerhaveRegNumber = document.getElementById('havevehnumb').value.trim();
            const commerhavetype = document.getElementById('havevehtype').value.trim();

            const commernewCity = document.getElementById('newvehiclecity').value.trim();
            const commernewType = document.getElementById('newvehicletype').value.trim();

            const mobileRegex = /^[0-9]{10}$/;
            let valid = true;

            verifiedBox.style.display = 'none';
            errorBox.style.display = 'none';

            if (vehicleGroup && knowCarGroup) {
                if (vehicleGroup.value === 'car') {

                    if (knowCarGroup.value === 'knowcar') {
                        if (!carRegNumber) {
                            showError('Car Registration Number is required.', '#carregnumber');
                            valid = false;
                        }

                        if (!regRegex.test(carRegNumber)) {
                            showError('Car Registration Number is invalid.', '#carregnumber');
                            valid = false;
                        }

                        if (carRegNumber) {
                            // Wait for API call result
                            try {
                                const response = await CallAPI("{{ route('motor.verifyrto') }}", carRegNumber, "");
                                const status = response.status;
                                // console.log(response);

                                if (status == 1) {
                                    // console.log(response.message);
                                    displaySuccess(response.message);
                                    valid = true; // Set valid to true if the API call is successful
                                } else {
                                    showError(response.message, '#carregnumber');
                                    valid = false;
                                }
                            } catch (error) {
                                // console.error("API call failed", error);
                                showError("An error occurred while verifying the registration number.",
                                    '#carregnumber');
                                valid = false;
                            }

                            // Now call form submission only if validation is successful

                        }
                        handleFormSubmission();
                    } else if (knowCarGroup.value === 'dontknowcar') {
                        if (!dontknowcarCity) {
                            showError(`City Name must be filled.`, '#dontknowcarcity');
                            valid = false;
                        }


                    } else if (knowCarGroup.value === 'newcar') {
                        if (!newcarCity) {
                            showError(`New City Name must be filled.`, '#newcarcity');
                            valid = false;
                        } else {
                            if (!validCities.includes(newcarCity)) {
                                showError(`City Name doesn't match.`, '#newcarcity');
                                valid = false;
                            }
                        }

                    }
                }
                if (vehicleGroup.value === 'bike') {
                    if (knowBikeGroup.value === 'knowbike') {
                        if (!KnowBikeRegNumber) {
                            showError(`Know Bike Registration Number is required.`, '#bikeregnumber');
                            valid = false;
                        }
                        if (!regRegex.test(KnowBikeRegNumber)) {
                            showError(`Know Bike Registration Number is invalid.`, '#bikeregnumber');
                            valid = false;
                        }
                        if (KnowBikeRegNumber) {
                            // Wait for API call result
                            try {
                                const response = await CallAPI("{{ route('motor.bikeverifyrto') }}", KnowBikeRegNumber,
                                    "");
                                const status = response.status;
                                // console.log(response);

                                if (status == 1) {
                                    // console.log(response.message);
                                    displaySuccess(response.message);
                                    valid = true; // Set valid to true if the API call is successful
                                } else {
                                    showError(response.message, '#bikeregnumber');
                                    valid = false;
                                }
                            } catch (error) {
                                // console.error("API call failed", error);
                                showError("An error occurred while verifying the registration number.",
                                    '#bikeregnumber');
                                valid = false;
                            }

                            // Now call form submission only if validation is successful
                            handleFormSubmission();
                        }
                    } else if (knowBikeGroup.value === 'dontknowbike') {
                        if (!dontknowBikeCity) {
                            showError(`Dont Know City Name must be filled.`, '#dontknowbikecity');
                            valid = false;
                        }

                    } else if (knowBikeGroup.value === 'newbike') {
                        if (!newBikeCity) {
                            showError(`New City Name must be filled.`, '#newbikecity');
                            valid = false;
                        } else {
                            if (!validCities.includes(newBikeCity)) {
                                showError(`City Name doesn't match.`, '#newbikecity');
                                valid = false;
                            }
                        }


                    }
                }
                if (vehicleGroup.value === 'commercial') {
                    if (CommercialGroup.value === 'havevehicle') {
                        if (!commerhavetype) {
                            showError(`Commercial have Vehicle Type must be selected.`, '#havevehtype');
                            valid = false;
                        }

                        if (!commerhaveRegNumber) {
                            showError(`Commercial have Registration Number is required.`, '#havevehnumb');
                            valid = false;
                        } else if (!regRegex.test(commerhaveRegNumber)) {
                            showError(`Commercial have Registration Number is invalid.`, '#havevehnumb');
                            valid = false;
                        }


                    } else if (CommercialGroup.value === 'newvehicle') {
                        if (!commernewType) {
                            showError(`Commercial New Vehicle Type must be selected.`, '#newvehicletype');
                            valid = false;
                        }

                        if (!commernewCity) {
                            showError(`Commercial have Commercial New City Name must be filled.`, '#newvehiclecity');
                            valid = false;
                        }
                    }
                }

                if (valid) {
                    // verifiedBox.style.display = 'flex';
                    // verifiedBox.querySelector('.verifiyed__title').innerText = `Form submitted successfully!`;
                    // setTimeout(() => {
                    //     verifiedBox.style.display = 'none';
                    // }, 3000);
                    handleFormSubmission();
                }
            }

            function handleFormSubmission() {
                if (valid) {
                    if (knowCarGroup.value === 'knowcar') {


                        $('#knowcarFormSubmit').click();
                    } else if (knowCarGroup.value === 'dontknowcar') {
                        $('#dontknowcarFormSubmit').click();
                    } else if (knowCarGroup.value === 'newcar') {
                        $('#newcarFormSubmit').click();
                    } else if (knowBikeGroup.value === 'knowbike') {
                        $('#knowbikeFormSubmit').click();
                    } else if (knowBikeGroup.value === 'dontknowbike') {
                        $('#dontknowbikeFormSubmit').click();
                    } else if (knowBikeGroup.value === 'newbike') {
                        $('#newbikeFormSubmit').click();
                    } else if (CommercialGroup.value === 'havevehicle') {
                        $('#havevehicleFormSubmit').click();
                    } else if (CommercialGroup.value === 'newvehicle') {
                        $('#havenewvehicleFormSubmit').click();
                    }
                } else {
                    // console.log("Validation failed, cannot submit.");
                }

                return false; // Prevent default form submission
            }

            // Call this function on the appropriate event, e.g., a button click


            function showError(message, focusSelector) {
                errorTitleElement.innerText = message;
                document.querySelector(focusSelector).focus();
                errorBox.style.display = "flex";
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
            }
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



        // Global variables
        var resendTimer;


        function handleCarSelection() {
            const selectedValue = document.querySelector('input[name="knowcar"]:checked')?.value;
            document.getElementById('knowcarrow').style.display = selectedValue === 'knowcar' ? 'flex' : 'none';
            document.getElementById('dontknowcarrow').style.display = selectedValue === 'dontknowcar' ? 'flex' : 'none';
            document.getElementById('newcarrow').style.display = selectedValue === 'newcar' ? 'flex' : 'none';
        }

        function handleBikeSelection() {
            const selectedValue = document.querySelector('input[name="knowbike"]:checked')?.value;
            document.getElementById('knowbikerow').style.display = selectedValue === 'knowbike' ? 'flex' : 'none';
            document.getElementById('dontknowbikerow').style.display = selectedValue === 'dontknowbike' ? 'flex' : 'none';
            document.getElementById('newbikerow').style.display = selectedValue === 'newbike' ? 'flex' : 'none';
        }

        function handleCommercialSelection() {
            const selectedValue = document.querySelector('input[name="commercial"]:checked')?.value;
            document.getElementById('havevehiclerow').style.display = selectedValue === 'havevehicle' ? 'flex' : 'none';
            document.getElementById('newvehiclerow').style.display = selectedValue === 'newvehicle' ? 'flex' : 'none';
        }

        function handleVehicleTypeSelection() {
            const selectedValue = document.querySelector('input[name="button-group"]:checked')?.value;
            document.getElementById('carInsureDetails').style.display = selectedValue === 'car' ? 'flex' : 'none';
            document.getElementById('bikeInsureDetails').style.display = selectedValue === 'bike' ? 'flex' : 'none';
            document.getElementById('commercialInsureDetails').style.display = selectedValue === 'commercial' ? 'flex' :
                'none';
        }

        function initializeFormState() {
            handleVehicleTypeSelection();
            handleCarSelection();
            handleBikeSelection();
            handleCommercialSelection();
        }


        window.addEventListener('pageshow', function() {
            initializeFormState();

            document.querySelectorAll('input[name="knowcar"]').forEach((radio) => {
                radio.addEventListener('change', handleCarSelection);
            });
            document.querySelectorAll('input[name="knowbike"]').forEach((radio) => {
                radio.addEventListener('change', handleBikeSelection);
            });
            document.querySelectorAll('input[name="commercial"]').forEach((radio) => {
                radio.addEventListener('change', handleCommercialSelection);
            });
            document.querySelectorAll('input[name="button-group"]').forEach((radio) => {
                radio.addEventListener('change', handleVehicleTypeSelection);
            });
        });



        function displaySuccess(message) {
            const successBox = document.querySelector('.MainverifiyedBox');

            // Check if the success box exists before trying to set innerText
            if (successBox) {
                const successTitleElement = successBox.querySelector('.verifiyed__title');
                if (successTitleElement) {
                    successTitleElement.innerText = message;
                    successBox.style.display = "flex";
                    setTimeout(() => {
                        successBox.style.display = 'none';
                    }, 3000);
                } else {
                    console.error("Error: Success title element not found.");
                }
            } else {
                console.error("Error: Success box element not found.");
            }
        }


        function displayAlert(message) {
            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox?.querySelector('.error__title');
            errorTitleElement.innerText = message;
            errorBox.style.display = "flex";
            setTimeout(() => {
                errorBox.style.display = 'none';
            }, 3000);
        }





        let validCities = [];

        function getCityName(inputSelector) {
            let cityInput = $(inputSelector);
            let city = cityInput.val();


            if (typeof city !== 'string') {
                return;
            }

            const cityListDiv = cityInput.next('.city-list');

            cityInput.val(city.replace(/[^A-Za-z\s]/g, '').substring(0, 16));
            city = cityInput.val();

            if (city.length > 1) {
                $.ajax({
                    url: "{{ route('motor.getcity') }}",
                    type: 'POST',
                    data: {
                        'city': city,
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // console.log(response);
                        cityListDiv.empty();


                        if (Object.keys(response).length > 0) {
                            let htmlContent = '<ul class="cityList">';
                            $.each(response, function(key, value) {
                                const cityText = value ? value : '';
                                validCities.push(cityText);
                                htmlContent +=
                                    `<li><a href="#" class="city-link" data-city="${cityText}">${cityText}</a></li>`;
                            });
                            htmlContent += '</ul>';
                            cityListDiv.append(htmlContent);

                            $('.city-link').off('click').on('click', function(event) {
                                event.preventDefault();
                                const selectedCity = $(this).data(
                                    'city');
                                cityInput.val(selectedCity);
                                cityListDiv.empty()
                                    .hide();
                            });

                            cityListDiv.show();
                        } else {
                            cityListDiv.html('<p>No cities found for this input.</p>').show();
                        }

                    },
                    error: function(error) {
                        console.error('Error:', error);
                        cityListDiv.html('<p>An error occurred while fetching the data.</p>').show();
                        validateCity(cityInput, []);
                    }
                });
            } else {
                cityListDiv.empty().hide();
            }
        }

        function validateCity(cityInput, validCities) {
            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox?.querySelector('.error__title');

            const city = cityInput.val().trim();
            if (validCities.length > 0 && !validCities.includes(city)) {
                errorBox.style.display = 'flex';
                errorTitleElement.innerText = "Please enter a valid city name.";
                cityInput.addClass('error'); // Add error class to input
                setTimeout(() => {
                    errorBox.style.display = 'none';
                    cityInput.removeClass('error'); // Remove error class after a short delay
                }, 3000);
            }
        }
        const inputSelectors = [
            "#dontknowcarcity",
            "#newcarcity",
            "#dontknowbikecity",
            "#newbikecity",
            "#newvehiclecity"
        ];

        inputSelectors.forEach(selector => {
            $(selector).on('input', function() {
                const city = $(this).val();
                const cityListDiv = $(this).next('.city-list');
                if (city.length > 1) {
                    cityListDiv.show();
                } else {
                    cityListDiv.empty().hide();
                }
            });

            $(selector).on('input', function() {

                getCityName(selector);
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
            toggleNewCarIndi();
        });

        dobpartenallowed('.datepicker');

        async function getBrand() {
            let brands = await getBrandName('CAR');
            if (brands && brands.date) {
                $('#brand').empty();
                $('#brand').append(
                    '<option value="" style="display: none;" selected disabled>Select a brand</option>');

                if (brands.brand && Object.keys(brands.brand).length > 0) {
                    for (let key in brands.brand) {
                        const brandValue = brands.brand[key];
                        $('#brand').append(`<option value="${brandValue}">${brandValue}</option>`);
                    }
                }
                $('#newcarregdate').val(brands.date);

                $('#brand').on('change', function() {
                    const selectedBrand = $(this).val();
                    getModel(selectedBrand);
                });
            }
        }

        async function getModel(selectedBrand) {

            const models = await getModelName(selectedBrand);

            if (models && Array.isArray(models)) {
                $('#model').empty();
                $('#model').append(
                    '<option value="" style="display: none;" selected disabled>Select a brand</option>'
                );
                models.forEach((modelObj) => {
                    const model = modelObj.model;
                    $('#model').append(`<option value="${model}">${model}</option>`);
                });
            }
        }
        getBrand();

        function NewCarStarted(event) {
            event.preventDefault();
            const newcarcompany = document.querySelector('#newcarcompany:checked');

            const fields = [{
                    id: "brand",
                    type: "New Car Year Of Manufacture"
                },
                {
                    id: "model",
                    type: "New Car Model & Variant"
                },
                {
                    id: "newcarregdate",
                    type: "New Car Register Date"
                },
                {
                    id: "brandyear",
                    type: "New Car Manufacture Vehicle"
                }
            ];

            // fields only if "newcarcompany" is  checked
            if (newcarcompany) {
                const commFields = [{
                        id: "newcarcmpnynme",
                        type: "New Car Company Name"
                    },
                    {
                        id: "newcargstno",
                        type: "New Car GST Number"
                    }
                ];

                commFields.forEach(field => {
                    if (document.getElementById(field.id)) {
                        fields.push(field);
                    }
                });
            }

            for (let field of fields) {
                let inputElement = document.getElementById(field.id);
                if (!inputElement) continue;

                let fieldValue = inputElement.value.trim();
                let validationResult = validateField(field.id, field.type, fieldValue);

                if (validationResult == '0') {
                    return false;
                }
            }

            document.getElementById('knowcarFormSubmit').click();
            document.getElementById('editcarFormSubmit').click();

            return true;
        }
    </script>
</body>

</html>
