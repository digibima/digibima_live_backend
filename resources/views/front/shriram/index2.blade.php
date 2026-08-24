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
    {{-- {{ getconstant('SITE_URL') }} --}}
    @php
    $status = ($otp=='1') ? '1' : '0';
    //echo $mobile;
    //SystemController::appoptimize();
    @endphp
    <div id="loader">
        <div class="loader-container">
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
            <div class="dot dot-3"></div>
            <div class="dot dot-4"></div>
        </div>
    </div>
    {{-- {{ $status }}
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
            {{-- <div class="row">

                <div class="col-md-3 alredyacount">

                    <a href="#" class="custom-button">
                        <div class="button-icon">
                                <span class="conti">Continue</span><i class="fa-solid fa-arrow-right"></i>
                            </div>
                        <p class="button-text mb-0"> Status {{ $status }}</p>

            </a>

            </div>
            </div> --}}
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
                                {{-- <p class="sidepera mb-1">Lorem ipsum dolor sit amet consectetur dolor sit</p> --}}

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
                                            <div class="col-lg-4 col-md-8 col-sm-12 mb-2">
                                                <div class="radiolabel">
                                                    <input type="radio" id="dontknowcar" name="knowcar"
                                                        value="dontknowcar">
                                                    <label for="dontknowcar">Don't Know car No.</label>
                                                </div>
                                            </div>
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
                                                            name="carregnumber" value=""
                                                            placeholder="Enter Registration Number"
                                                            oninput="registrNumbInput()">
                                                    </div>
                                                    <div class="otp mb-2" id="knowcarotpSection" style="">
                                                        <label for="knowcarotpmobile">Enter OTP</label>
                                                        <input type="text" id="knowcarotpmobile" name="otp"
                                                            class="mobileotpInput" value=""
                                                            placeholder="Enter OTP"
                                                            oninput="validateknowCarOtpInput(this, 6)" maxlength="6"
                                                            style="width:70%;">
                                                        <button type="button" class="getotp knowcargetotp"
                                                            style="width:25%;"
                                                            onclick="submitKnowCarOtp()">Submit</button>
                                                        <div class="timerAlert" style="display:none;"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                                    <div class="mobile mb-2" id="knowcargetOtpSection">
                                                        @if ($status == 1)
                                                            <label for="knowcarmobile">
                                                                Mobile Number
                                                                <span class="editMobile"
                                                                    title="Edit Your Mobile Number"
                                                                    onclick="editMobileNumber('knowcarmobile', 'verifycarButton')">
                                                                    <i class="fa-solid fa-pen"></i>
                                                                </span>
                                                            </label>
                                                            <input type="text" id="knowcarmobile"
                                                                name="knowcarmobile" class="mobiletenInput"
                                                                value="{{ $mobile }}"
                                                                placeholder="Enter Mobile Number"
                                                                oninput="validatedontknowcarInput(this, 10)"
                                                                maxlength="10" style="width:70%;" readonly>
                                                            <button type="button" id="verifycarButton"
                                                                class="getotp" style="width:25%;" disabled
                                                                onclick="showknowcarOtpSection()">Verified</button>
                                                        @else
                                                            <label for="knowcarmobile">Mobile Number</label>
                                                            <input type="text" id="knowcarmobile"
                                                                name="knowcarmobile" class="mobiletenInput"
                                                                placeholder="Enter Mobile Number"
                                                                oninput="validateInput(this, 10, 'verifycarButton')"
                                                                maxlength="10" style="width:70%;">
                                                            <button type="button" id="verifycarButton"
                                                                class="getotp" style="width:25%;" disabled
                                                                onclick="showknowcarOtpSection()">Verify</button>
                                                        @endif

                                                    </div>
                                                    <!-- <div class="mobile mb-2" id="knowcargetOtpSection">


                                                        <label for="knowcarmobile">Mobile Number</label>
                                                        <input type="text" id="knowcarmobile"
                                                            name="knowcarmobile" class="mobiletenInput"
                                                            value="" placeholder="Enter Mobile Number"
                                                            oninput="validateInput(this, 10, 'verifycarButton')"
                                                            maxlength="10" style="width:70%;">
                                                        <button type="button" id="verifycarButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showknowcarOtpSection()">Verify</button>


                                                    </div> -->
                                                </div>
                                                <input type="submit"  value =" submit" class="" id="knowcarFormSubmit">
                                            </div>
                                        </form>
                                    </div>

                                    <div class="row" id="dontknowcarrow" style="display: none">
                                        <form action="{{ route('car.dontcarlogin') }}" id="dontknowcarForm"
                                            method="post">
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
                                                    <div class="otp mb-2" id="dontknowcarotpSection"
                                                        style="display: none;">
                                                        <label for="dontknowcarotpmobile">Enter OTP</label>
                                                        <input type="text" id="dontknowcarotpmobile"
                                                            name="otp" class="mobileotpInput" value=""
                                                            placeholder="Enter OTP"
                                                            oninput="validatedontknowCarOtpInput(this, 6)"
                                                            maxlength="6" style="width:70%;">
                                                        <button type="button" class="getotp dontknowcargetotp"
                                                            style="width:25%;"
                                                            onclick="submitDontKnowCarOtp()">Submit</button>
                                                        <div class="timerAlert" style="display:none;"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12  mb-2">
                                                    <!-- <div class="mobile mb-2 " id="dontknowcargetOtpSection">
                                                        @if ($status == 1)
                                                            <label for="dontknowcarmobile">
                                                                Mobile Number
                                                                <span class="editMobile"
                                                                    title="Edit Your Mobile Number"
                                                                    onclick="editMobileNumber('dontknowcarmobile', 'verifydontcarButton')">
                                                                    <i class="fa-solid fa-pen"></i>
                                                                </span>
                                                            </label>
                                                            <input type="text" id="dontknowcarmobile"
                                                                name="dontknowcarmobile" class="mobiletenInput"
                                                                value="{{ $mobile }}"
                                                                placeholder="Enter Mobile Number"
                                                                oninput="validatedontknowcarInput(this, 10)"
                                                                maxlength="10" style="width:70%;" readonly>
                                                            <button type="button" id="verifydontcarButton"
                                                                class="getotp" style="width:25%;"
                                                                disabled>Verified</button>
                                                        @else
                                                            <label for="dontknowcarmobile">Mobile Number</label>
                                                            <input type="text" id="dontknowcarmobile"
                                                                name="dontknowcarmobile" class="mobiletenInput"
                                                                value="" placeholder="Enter Mobile Number"
                                                                oninput="validatedontknowcarInput(this, 10)"
                                                                maxlength="10" style="width:70%;">
                                                            <button type="button" id="verifydontcarButton"
                                                                class="getotp" style="width:25%;" disabled
                                                                onclick="showdontknowcarOtpSection()">Verify</button>
                                                        @endif

                                                    </div> -->
                                                    <div class="mobile mb-2 " id="dontknowcargetOtpSection">

                                                        <label for="dontknowcarmobile">Mobile Number</label>
                                                        <input type="text" id="dontknowcarmobile"
                                                            name="dontknowcarmobile" class="mobiletenInput"
                                                            value="{{ $mobile }}" placeholder="Enter Mobile Number"
                                                            oninput="validatedontknowcarInput(this, 10)"
                                                            maxlength="10" style="width:70%;">
                                                        <button type="button" id="verifydontcarButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showdontknowcarOtpSection()">Verify</button>


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
                                                    <div class="otp mb-2" id="newcarotpSection"
                                                        style="display: none;">
                                                        <label for="newcarotpmobile">Enter OTP</label>
                                                        <input type="text" id="newcarotpmobile" name="otp"
                                                            class="mobileotpInput" value=""
                                                            placeholder="Enter OTP"
                                                            oninput="validateNewCarOtpInput(this, 6)" maxlength="6"
                                                            style="width:70%;">
                                                        <button type="button" class="getotp newcargetotp"
                                                            style="width:25%;"
                                                            onclick="submitNewCarOtp()">Submit</button>
                                                        <div class="timerAlert" style="display:none;"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                                    <!-- <div class="mobile mb-2" id="newcargetOtpSection">
                                                        @if ($status == 1)
                                                            <label for="newcarmobile">
                                                                Mobile Number
                                                                <span class="editMobile"
                                                                    title="Edit Your Mobile Number"
                                                                    onclick="editMobileNumber('newcarmobile', 'verifynewcarButton')">
                                                                    <i class="fa-solid fa-pen"></i>
                                                                </span>
                                                            </label>
                                                            <input type="text" id="newcarmobile"
                                                                name="newcarmobile" class="mobiletenInput"
                                                                value="{{ $mobile }}"
                                                                placeholder="Enter Mobile Number"
                                                                oninput="validateNewcarInput(this, 10)" maxlength="10"
                                                                style="width:70%;" readonly>
                                                            <button type="button" id="verifynewcarButton"
                                                                class="getotp" style="width:25%;" disabled
                                                                onclick="showNewcarOtpSection()">Verified</button>
                                                        @else
                                                            <label for="newcarmobile">Mobile Number</label>
                                                            <input type="text" id="newcarmobile"
                                                                name="newcarmobile" class="mobiletenInput"
                                                                value="" placeholder="Enter Mobile Number"
                                                                oninput="validateNewcarInput(this, 10)" maxlength="10"
                                                                style="width:70%;">
                                                            <button type="button" id="verifynewcarButton"
                                                                class="getotp" style="width:25%;" disabled
                                                                onclick="showNewcarOtpSection()">Verify</button>
                                                        @endif

                                                    </div> -->
                                                    <div class="mobile mb-2" id="newcargetOtpSection">

                                                        <label for="newcarmobile">Mobile Number</label>
                                                        <input type="text" id="newcarmobile"
                                                            name="newcarmobile" class="mobiletenInput"
                                                            value="{{ $mobile }}" placeholder="Enter Mobile Number"
                                                            oninput="validateNewcarInput(this, 10)" maxlength="10"
                                                            style="width:70%;">
                                                        <button type="button" id="verifynewcarButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showNewcarOtpSection()">Verify</button>


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
                                            <div class="col-lg-4 col-md-8 col-sm-12 mb-2">
                                                <div class="radiolabel">
                                                    <input type="radio" id="dontknowbike" name="knowbike"
                                                        value="dontknowbike">
                                                    <label for="dontknowbike">Don't Know Bike No.</label>
                                                </div>
                                            </div>
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
                                        <form action="{{ route('shriram.knowbike') }}" id="knowbikeForm"
                                            method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                                    <div class="carnumber mb-2">
                                                        <label for="bikeregnumber">Bike Registration Number</label>
                                                        <input type="text" id="bikeregnumber"
                                                            class="registrnobInput" name="bikeregnumber"
                                                            placeholder="Enter Registration Number"
                                                            oninput="registrNumbInput()">
                                                    </div>
                                                    <div class="otp mb-2" id="knowbikeotpSection"
                                                        style="display: none;">
                                                        <label for="knowbikeotpmobile">Enter OTP</label>
                                                        <input type="text" id="knowbikeotpmobile" name="otp"
                                                            class="mobileotpInput" placeholder="Enter OTP"
                                                            oninput="validateknowBikeOtpInput(this, 6)" maxlength="6"
                                                            style="width:70%;">
                                                        <button type="button" class="getotp knowbikegetotp"
                                                            style="width:25%;"
                                                            onclick="submitKnowBikeOtp()">Submit</button>
                                                        <div class="timerAlert" style="display:none;"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                                    <div class="mobile mb-2" id="knowbikegetOtpSection">
                                                        @if ($status == 1)
                                                        <label for="knowbikemobile">
                                                            Mobile Number
                                                            <span class="editMobile"
                                                                title="Edit Your Mobile Number"
                                                                onclick="editMobileNumber('knowbikemobile', 'verifybikeButton')">
                                                                <i class="fa-solid fa-pen"></i>
                                                            </span>
                                                        </label>
                                                        <input type="text" id="knowbikemobile"
                                                            name="knowbikemobile" class="mobiletenInput"
                                                            value="{{ $mobile }}"
                                                            placeholder="Enter Mobile Number"
                                                            oninput="validateInput(this, 10, 'verifybikeButton')"
                                                            maxlength="10" style="width:70%;" readonly>
                                                        <button type="button" id="verifybikeButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showknowbikeOtpSection()">Verified</button>
                                                        @else
                                                        <label for="knowbikemobile">Mobile Number</label>
                                                        <input type="text" id="knowbikemobile"
                                                            name="knowbikemobile" class="mobiletenInput"
                                                            placeholder="Enter Mobile Number"
                                                            oninput="validateInput(this, 10, 'verifybikeButton')"
                                                            maxlength="10" style="width:70%;">
                                                        <button type="button" id="verifybikeButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showknowbikeOtpSection()">Verify</button>
                                                        @endif

                                                    </div>
                                                </div>
                                                <input type="submit" class="d-none" id="knowbikeFormSubmit">
                                            </div>
                                        </form>
                                    </div>

                                    <div class="row" id="dontknowbikerow" style="display: none;">
                                        <form action="{{ route('shriram.dontbike') }}" id="dontknowbikeForm"
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
                                                    <div class="otp mb-2" id="dontknowbikeotpSection"
                                                        style="display: none;">
                                                        <label for="dontknowbikeotpmobile">Enter OTP</label>
                                                        <input type="text" id="dontknowbikeotpmobile"
                                                            name="otp" class="mobileotpInput"
                                                            placeholder="Enter OTP"
                                                            oninput="validatedontknowBikeOtpInput(this, 6)"
                                                            maxlength="6" style="width:70%;">
                                                        <button type="button" class="getotp dontknowbikegetotp"
                                                            style="width:25%;"
                                                            onclick="submitDontKnowBikeOtp()">Submit</button>
                                                        <div class="timerAlert" style="display:none;"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                                    <div class="mobile mb-2" id="dontknowbikegetOtpSection">
                                                        @if ($status == 1)
                                                        <label for="dontknowbikemobile">
                                                            Mobile Number
                                                            <span class="editMobile"
                                                                title="Edit Your Mobile Number"
                                                                onclick="editMobileNumber('dontknowbikemobile', 'verifydontbikeButton')">
                                                                <i class="fa-solid fa-pen"></i>
                                                            </span>
                                                        </label>
                                                        <input type="text" id="dontknowbikemobile"
                                                            name="dontknowbikemobile" class="mobiletenInput"
                                                            value="{{ $mobile }}"
                                                            placeholder="Enter Mobile Number"
                                                            oninput="validatedontknowBikeInput(this, 10)"
                                                            maxlength="10" style="width:70%;" readonly>
                                                        <button type="button" id="verifydontbikeButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showdontknowbikeOtpSection()">Verified</button>
                                                        @else
                                                        <label for="dontknowbikemobile">Mobile Number</label>
                                                        <input type="text" id="dontknowbikemobile"
                                                            name="dontknowbikemobile" class="mobiletenInput"
                                                            placeholder="Enter Mobile Number"
                                                            oninput="validatedontknowBikeInput(this, 10)"
                                                            maxlength="10" style="width:70%;">
                                                        <button type="button" id="verifydontbikeButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showdontknowbikeOtpSection()">Verify</button>
                                                        @endif
                                                    </div>
                                                </div>
                                                <input type="submit" class="d-none" id="dontknowbikeFormSubmit">
                                            </div>
                                        </form>

                                    </div>

                                    <div class="row" id="newbikerow" style="display: none;">
                                        <form action="{{ route('shriram.newbike') }}" id="newbikeForm"
                                            method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                                    <div class="carnumber mb-2">
                                                        <label for="newbikecity">City Name</label>
                                                        <input type="text" id="newbikecity" name="newbikecity"
                                                            placeholder="Enter City Name" oninput="getCityName()">
                                                        <div class="city-list" class="mb-5"></div>
                                                    </div>
                                                    <div class="otp mb-2" id="newbikeotpSection"
                                                        style="display: none;">
                                                        <label for="newbikeotpmobile">Enter OTP</label>
                                                        <input type="text" id="newbikeotpmobile" name="otp"
                                                            class="mobileotpInput" placeholder="Enter OTP"
                                                            oninput="validateNewBikeOtpInput(this, 6)" maxlength="6"
                                                            style="width:70%;">
                                                        <button type="button" class="getotp newbikegetotp"
                                                            style="width:25%;"
                                                            onclick="submitNewBikeOtp()">Submit</button>
                                                        <div class="timerAlert" style="display:none;"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                                    <div class="mobile mb-2" id="newbikegetOtpSection">
                                                        @if ($status == 1)
                                                        <label for="newbikemobile">
                                                            Mobile Number
                                                            <span class="editMobile"
                                                                title="Edit Your Mobile Number"
                                                                onclick="editMobileNumber('newbikemobile', 'verifynewbikeButton')">
                                                                <i class="fa-solid fa-pen"></i>
                                                            </span>
                                                        </label>
                                                        <input type="text" id="newbikemobile"
                                                            name="newbikemobile" class="mobiletenInput"
                                                            value="{{ $mobile }}"
                                                            placeholder="Enter Mobile Number"
                                                            oninput="validateNewBikeInput(this, 10)"
                                                            maxlength="10" style="width:70%;" readonly>
                                                        <button type="button" id="verifynewbikeButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showNewbikeOtpSection()">Verified</button>
                                                        @else
                                                        <label for="newbikemobile">Mobile Number</label>
                                                        <input type="text" id="newbikemobile"
                                                            name="newbikemobile" class="mobiletenInput"
                                                            placeholder="Enter Mobile Number"
                                                            oninput="validateNewBikeInput(this, 10)"
                                                            maxlength="10" style="width:70%;">
                                                        <button type="button" id="verifynewbikeButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showNewbikeOtpSection()">Verify</button>
                                                        @endif
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
                                        <form action="{{ route('shriram.havevehicle') }}" id="havevehicleForm"
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
                                                        @if ($status == 1)
                                                        <label for="havevehmobile">
                                                            Mobile Number
                                                            <span class="editMobile"
                                                                title="Edit Your Mobile Number"
                                                                onclick="editMobileNumber('havevehmobile', 'verifyhavevehButton')">
                                                                <i class="fa-solid fa-pen"></i>
                                                            </span>
                                                        </label>
                                                        <input type="text" id="havevehmobile"
                                                            name="havevehmobile" class="mobiletenInput"
                                                            value="{{ $mobile }}"
                                                            placeholder="Enter Mobile Number"
                                                            oninput="validateHaveVehInput(this, 10)"
                                                            maxlength="10" style="width:70%;" readonly>
                                                        <button type="button" id="verifyhavevehButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showHaveVehOtpSection()">Verified</button>
                                                        @else
                                                        <label for="havevehmobile">Mobile Number</label>
                                                        <input type="text" id="havevehmobile"
                                                            name="havevehmobile" class="mobiletenInput"
                                                            value="{{ old('havevehmobile') }}"
                                                            placeholder="Enter Mobile Number"
                                                            oninput="validateHaveVehInput(this, 10)"
                                                            maxlength="10" style="width:70%;">
                                                        <button type="button" id="verifyhavevehButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showHaveVehOtpSection()">Verify</button>
                                                        @endif

                                                    </div>
                                                    @error('havevehmobile')
                                                    <div class="error" id="havevehmobileError">{{ $message }}
                                                    </div>
                                                    @enderror
                                                    <div class="otp mb-2" id="havevehicleotpSection"
                                                        style="display: none;">
                                                        <label for="havevehotpmobile">Enter OTP</label>
                                                        <input type="text" id="havevehotpmobile"
                                                            name="havevehotpmobileotp" class="mobileotpInput"
                                                            value="" placeholder="Enter OTP"
                                                            oninput="validateHaveVehOtpInput(this, 6)" maxlength="6"
                                                            style="width:70%;">
                                                        <button type="button" class="getotp havevehgetotp"
                                                            style="width:25%;"
                                                            onclick="submitHaveVehOtp()">Submit</button>
                                                        <div class="timerAlert" style="display:none;"></div>
                                                    </div>
                                                </div>
                                                <input type="submit" class="d-none" id="havevehicleFormSubmit">
                                            </div>
                                        </form>

                                    </div>
                                    <div class="row" id="newvehiclerow" style="display: none">
                                        <form action="{{ route('shriram.havenewvehicle') }}" id=""
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
                                                        @if ($status == 1)
                                                        <label for="newvehiclemob">
                                                            Mobile Number
                                                            <span class="editMobile"
                                                                title="Edit Your Mobile Number"
                                                                onclick="editMobileNumber('newvehiclemob', 'verifynewvehButton')">
                                                                <i class="fa-solid fa-pen"></i>
                                                            </span>
                                                        </label>
                                                        <input type="text" id="newvehiclemob"
                                                            name="newvehiclemob" class="mobiletenInput"
                                                            value="{{ $mobile }}"
                                                            placeholder="Enter Mobile Number"
                                                            oninput="validateNewvehcInput(this, 10)"
                                                            maxlength="10" style="width:70%;" readonly>
                                                        <button type="button" id="verifynewvehButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showHaveNewVehOtpSection()">Verified</button>
                                                        @else
                                                        <label for="newvehiclemob">Mobile Number</label>
                                                        <input type="text" id="newvehiclemob"
                                                            name="newvehiclemob" class="mobiletenInput"
                                                            value="{{ old('newvehiclemob') }}"
                                                            placeholder="Enter Mobile Number"
                                                            oninput="validateNewvehcInput(this, 10)"
                                                            maxlength="10" style="width:70%;">
                                                        <button type="button" id="verifynewvehButton"
                                                            class="getotp" style="width:25%;" disabled
                                                            onclick="showHaveNewVehOtpSection()">Verify</button>
                                                        @endif
                                                    </div>
                                                    @error('newvehiclemob')
                                                    <div class="error" id="newvehiclemobError">{{ $message }}
                                                    </div>
                                                    @enderror
                                                    <div class="otp mb-2" id="newvehicleotpSection"
                                                        style="display: none;">
                                                        <label for="newvehicleotpmob">Enter OTP</label>
                                                        <input type="text" id="newvehicleotpmob"
                                                            name="newvehicleotpmob" class="mobileotpInput"
                                                            value="" placeholder="Enter OTP"
                                                            oninput="validateNewvehcOtpInput(this, 6)" maxlength="6"
                                                            style="width:70%;">
                                                        <button type="button" class="getotp newvehcgetotp"
                                                            style="width:25%;"
                                                            onclick="submitHaveNewVehOtp()">Submit</button>
                                                        <div class="timerAlert" style="display:none;"></div>
                                                    </div>
                                                </div>
                                                <input type="submit" class="d-none" id="havenewvehicleFormSubmit">
                                            </div>
                                        </form>

                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                    <input type="submit" id="mainsubmit" class="getstarted" value="Continue"
                                        style="opacity: 0.5;" disabled onClick="handleGetStarted(event);">

                                    <p class="formbtn">Already bought a policy from DigiBima? <a href="#">Renew
                                            Now</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
        </section>


    </main>
    @include('front.partial.footer')
    @include('front.partial.jslink')
    <script>
        window.addEventListener("load", function() {
            document.getElementById("loader").style.display = "none";
        });

        window.onbeforeunload = function() {
            document.getElementById("loader").style.display = "flex";
        };
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

        function handleGetStarted(event) {
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
            const mobileNumber = document.getElementById('knowcarmobile').value.trim();
            console.log(vehicleGroup);
            if (vehicleGroup.value === "bike") {
                knowCarGroup.value = "";
            } else if (vehicleGroup.value === "car") {
                knowBikeGroup.value = "";
            } else if (vehicleGroup.value === "commercial") {
                knowCarGroup.value = "";
                knowBikeGroup.value = "";
            }



            console.log(carRegNumber);
            console.log(mobileNumber);
            console.log(knowCarGroup.value);
            console.log(knowBikeGroup);
            //return false;
            //console.log(vehicleGroup);
            const dontknowcarCity = document.getElementById('dontknowcarcity').value.trim();
            const dontknowcarMobileNumber = document.getElementById('dontknowcarmobile').value.trim();

            const newcarCity = document.getElementById('newcarcity').value.trim();
            const newcarMobileNumber = document.getElementById('newcarmobile').value.trim();


            const KnowBikeRegNumber = document.getElementById('bikeregnumber').value.trim();
            const KnowBikemobileNumber = document.getElementById('knowbikemobile').value.trim();

            const dontknowBikeCity = document.getElementById('dontknowbikecity').value.trim();
            const dontknowBikeMobileNumber = document.getElementById('dontknowbikemobile').value.trim();

            const newBikeCity = document.getElementById('newbikecity').value.trim();
            const newBikeMobileNumber = document.getElementById('newbikemobile').value.trim();


            // commercial

            const commerhaveRegNumber = document.getElementById('havevehnumb').value.trim();
            const commerhavemobileNumber = document.getElementById('havevehmobile').value.trim();
            const commerhavetype = document.getElementById('havevehtype').value.trim();

            const commernewCity = document.getElementById('newvehiclecity').value.trim();
            const commernewMobileNumber = document.getElementById('newvehiclemob').value.trim();
            const commernewType = document.getElementById('newvehicletype').value.trim();

            const mobileRegex = /^[0-9]{10}$/;
            let valid = true;

            verifiedBox.style.display = 'none';
            errorBox.style.display = 'none';

            if (vehicleGroup && knowCarGroup) {
                if (vehicleGroup.value === 'car') {

                    if (knowCarGroup.value === 'knowcar') {
                        if (!mobileRegex.test(mobileNumber)) {
                            showError(`Mobile Number must be 10 digits.`, '#knowcarmobile');
                            valid = false;
                        }
                        if (!carRegNumber) {
                            showError(`Car Registration Number is required.`, '#carregnumber');
                            valid = false;
                        } else {

                            if (!regRegex.test(carRegNumber)) {
                                showError(`Car Registration Number is invalid.`, '#carregnumber');
                                valid = false;
                            }
                        }

                        if (!verifycarButton.value === 'Verified') {
                            mainsubmitButton.disabled = true;
                            mainsubmitButton.style.opacity = '0.5';
                            valid = false;
                        }

                    } else if (knowCarGroup.value === 'dontknowcar') {
                        if (!mobileRegex.test(dontknowcarMobileNumber)) {
                            showError(`Mobile Number must be 10 digits.`, '#dontknowcarmobile');
                            valid = false;
                        }
                        if (!dontknowcarCity) {
                            showError(`City Name must be filled.`, '#dontknowcarcity');
                            valid = false;
                        }


                    } else if (knowCarGroup.value === 'newcar') {
                        if (!mobileRegex.test(newcarMobileNumber)) {
                            showError(`New Car Mobile Number must be 10 digits.`, '#newcarmobile');
                            valid = false;
                        }
                        if (!newcarCity) {
                            showError(`New City Name must be filled.`, '#newcarcity');
                            valid = false;
                        }

                    }
                }
                if (vehicleGroup.value === 'bike') {
                    if (knowBikeGroup.value === 'knowbike') {
                        if (!mobileRegex.test(KnowBikemobileNumber)) {
                            showError(`Know Mobile Number must be 10 digits.`, '#knowbikemobile');
                            valid = false;
                        }

                        if (!KnowBikeRegNumber) {
                            showError(`Know Bike Registration Number is required.`, '#bikeregnumber');
                            valid = false;
                        } else if (!regRegex.test(KnowBikeRegNumber)) {
                            showError(`Know Bike Registration Number is invalid.`, '#bikeregnumber');
                            valid = false;
                        }
                    } else if (knowBikeGroup.value === 'dontknowbike') {
                        if (!mobileRegex.test(dontknowBikeMobileNumber)) {
                            showError(`Dont Know Mobile Number must be 10 digits.`, '#dontknowbikemobile');
                            valid = false;
                        }
                        if (!dontknowBikeCity) {
                            showError(`Dont Know City Name must be filled.`, '#dontknowbikecity');
                            valid = false;
                        }

                    } else if (knowBikeGroup.value === 'newbike') {
                        if (!mobileRegex.test(newBikeMobileNumber)) {
                            showError(`New Bike Mobile Number must be 10 digits.`, '#newbikemobile');
                            valid = false;
                        }
                        if (!newBikeCity) {
                            showError(`New City Name must be filled.`, '#newbikecity');
                            valid = false;
                        }

                    }
                }
                if (vehicleGroup.value === 'commercial') {
                    if (CommercialGroup.value === 'havevehicle') {
                        if (!commerhavetype) {
                            showError(`Commercial have Vehicle Type must be selected.`, '#havevehtype');
                            valid = false;
                        }
                        if (!mobileRegex.test(commerhavemobileNumber)) {
                            showError(`Commercial have Mobile Number must be 10 digits.`, '#havevehmobile');
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

                        if (!mobileRegex.test(commernewMobileNumber)) {
                            showError(`Commercial New Mobile Number must be 10 digits.`, '#newvehiclemob');
                            valid = false;
                        }
                        if (!commernewCity) {
                            showError(`Commercial have Commercial New City Name must be filled.`, '#newvehiclecity');
                            valid = false;
                        }
                    }
                }

                if (valid) {
                    verifiedBox.style.display = 'flex';
                    verifiedBox.querySelector('.verifiyed__title').innerText = `Form submitted successfully!`;
                    setTimeout(() => {
                        verifiedBox.style.display = 'none';
                    }, 3000);
                    handleFormSubmission();
                }
            }

            function handleFormSubmission() {
                // console.log(knowBikeGroup.value);
                // return false;
                if (knowCarGroup.value === 'knowcar') {
                    console.log(carRegNumber);
                    console.log(mobileNumber);
                    $('#knowcarFormSubmit').click();
                } else if (knowCarGroup.value === 'dontknowcar') {
                    $('#dontknowcarFormSubmit').click();
                } else if (knowCarGroup.value === 'newcar') {
                    $('#newcarFormSubmit').click();
                } else if (knowBikeGroup.value === 'knowbike') {
                    knowBikeGroup.value = "";
                    $('#knowbikeFormSubmit').click();
                } else if (knowBikeGroup.value === 'dontknowbike') {
                    // console.log(knowBikeGroup.value);
                    // return false;
                    $('#dontknowbikeFormSubmit').click();
                } else if (knowBikeGroup.value === 'newbike') {
                    $('#newbikeFormSubmit').click();
                } else if (CommercialGroup.value === 'havevehicle') {
                    console.log(CommercialGroup.value);
                    // return false;
                    $('#havevehicleFormSubmit').click();
                    return false;
                } else if (CommercialGroup.value === 'newvehicle') {
                    console.log(CommercialGroup.value);
                    // return false;
                    $('#havenewvehicleFormSubmit').click();
                    return false;
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

        // Function to handle radio button change for bike selection
        function handleBikeSelection() {
            const selectedValue = document.querySelector('input[name="knowbike"]:checked')?.value;
            document.getElementById('knowbikerow').style.display = selectedValue === 'knowbike' ? 'flex' : 'none';
            document.getElementById('dontknowbikerow').style.display = selectedValue === 'dontknowbike' ? 'flex' : 'none';
            document.getElementById('newbikerow').style.display = selectedValue === 'newbike' ? 'flex' : 'none';
        }
        // Function to handle visibility of vehicle registration sections
        function handleCommercialSelection() {
            const selectedValue = document.querySelector('input[name="commercial"]:checked')?.value;
            document.getElementById('havevehiclerow').style.display = selectedValue === 'havevehicle' ? 'flex' : 'none';
            document.getElementById('newvehiclerow').style.display = selectedValue === 'newvehicle' ? 'flex' : 'none';
        }

        // Attach event listeners for car and bike radio buttons
        document.querySelectorAll('input[name="knowcar"]').forEach((radio) => {
            radio.addEventListener('change', handleCarSelection);
        });
        document.querySelectorAll('input[name="knowbike"]').forEach((radio) => {
            radio.addEventListener('change', handleBikeSelection);
        });
        document.querySelectorAll('input[name="commercial"]').forEach((radio) => {
            radio.addEventListener('change', handleCommercialSelection);
        });

        handleCarSelection();
        handleBikeSelection();
        handleCommercialSelection();

        function showknowcarOtpSection() {
            $('#knowcarmobile').prop('disabled', true);
            $('#verifycarButton').prop('disabled', true);
            const knowCarOtpSection = document.getElementById('knowcarotpSection');
            knowCarOtpSection.style.display = 'block';
            const mobileNumber = document.getElementById('knowcarmobile').value;
            sendOtp(mobileNumber, 'knowcar');
        }

        function showdontknowcarOtpSection() {
            console.log("showdontknowcarOtpSection called");
            $('#dontknowcarmobile').prop('disabled', true);
            $('#verifydontcarButton').prop('disabled', true);
            const dontKnowCarOtpSection = document.getElementById('dontknowcarotpSection');
            dontKnowCarOtpSection.style.display = 'block';
            const mobileNumber = document.getElementById('dontknowcarmobile').value;
            console.log("Mobile Number: ", mobileNumber);
            sendOtp(mobileNumber, 'dontknowcar');
        }


        function showHaveVehOtpSection() {
            $('#havevehmobile').prop('disabled', true);
            $('#verifyhavevehButton').prop('disabled', true);
            const HaveVehOtpOtpSection = document.getElementById('havevehicleotpSection');
            HaveVehOtpOtpSection.style.display = 'block';
            const mobileNumber = document.getElementById('havevehmobile').value;
            sendOtp(mobileNumber, 'havevehicle');
        }

        function showHaveNewVehOtpSection() {
            $('#newvehiclemob').prop('disabled', true);
            $('#verifynewvehButton').prop('disabled', true);
            const HaveNewVehOtpSection = document.getElementById('newvehicleotpSection');
            HaveNewVehOtpSection.style.display = 'block';
            const mobileNumber = document.getElementById('newvehiclemob').value;
            sendOtp(mobileNumber, 'newvehicle');
        }

        function showNewcarOtpSection() {
            $('#newcarmobile').prop('disabled', true);
            $('#verifynewcarButton').prop('disabled', true);
            const newCarOtpSection = document.getElementById('newcarotpSection');
            newCarOtpSection.style.display = 'block';
            const mobileNumber = document.getElementById('newcarmobile').value;
            sendOtp(mobileNumber, 'newcar');
        }

        function showknowbikeOtpSection() {
            $('#knowbikemobile').prop('disabled', true);
            $('#verifybikeButton').prop('disabled', true);
            const knowBikeOtpSection = document.getElementById('knowbikeotpSection');
            knowBikeOtpSection.style.display = 'block';
            const mobileNumber = document.getElementById('knowbikemobile').value;
            sendOtp(mobileNumber, 'knowbike');
        }

        function showdontknowbikeOtpSection() {
            $('#dontknowbikemobile').prop('disabled', true);
            $('#verifydontbikeButton').prop('disabled', true);
            const dontKnowBikeOtpSection = document.getElementById('dontknowbikeotpSection');
            dontKnowBikeOtpSection.style.display = 'block';
            const mobileNumber = document.getElementById('dontknowbikemobile').value;
            sendOtp(mobileNumber, 'dontknowbike');
        }

        function showNewbikeOtpSection() {
            $('#newbikemobile').prop('disabled', true);
            $('#verifynewbikeButton').prop('disabled', true);
            const newBikeOtpSection = document.getElementById('newbikeotpSection');
            newBikeOtpSection.style.display = 'block';
            const mobileNumber = document.getElementById('newbikemobile').value;
            sendOtp(mobileNumber, 'newbike');
        }

        function validateInput(inputElement, maxLength, buttonId) {
            console.log(inputElement.id);
            const pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            const verifyMobNo = Number(@json($mobile));
            const MobileInput = inputElement;
            const verifyButton = document.getElementById(buttonId);
            var mainsubmitButton = document.getElementById('mainsubmit');

            if (!pattern.test(inputElement.value)) {
                inputElement.value = inputElement.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }

            if (MobileInput.value.length === maxLength && Number(MobileInput.value) === verifyMobNo) {
                verifyButton.disabled = true;
                verifyButton.textContent = 'Verified';
                console.log('Verified');
                verifyButton.style.opacity = '0.5';
                mainsubmitButton.disabled = false;
                mainsubmitButton.style.opacity = '1';
                console.log(mainsubmitButton);
                return false;
            } else {
                verifyButton.disabled = false;
                verifyButton.textContent = 'Verify';
                mainsubmitButton.disabled = true;
                mainsubmitButton.style.opacity = '0.5';

            }
            toggleVerifyButton(buttonId, inputElement.value.length === maxLength);



        }

        function toggleVerifyButton(buttonId, isEnabled) {

            const button = document.getElementById(buttonId);
            console.log(button);
            // If button is already verified, do not change its state
            if (button.textContent === 'Verified') {
                button.disabled = true;
                return;
            }
        }

        function toggleVerifyButton(buttonId, isEnabled) {
            const button = document.getElementById(buttonId);
            console.log(button);
            button.disabled = !isEnabled;
            button.style.opacity = isEnabled ? '1' : '0.5';
        }
        document.addEventListener('DOMContentLoaded', function() {
            const verifyStatus = Number(@json($status));
            var mainsubmitButton = document.getElementById('mainsubmit');
            if (verifyStatus === 1) {
                mainsubmitButton.disabled = false;
                mainsubmitButton.style.opacity = '1';
                showVerified(`Your mobile number is already verified.`);
                return;
            }
        });

        function sendOtp(mobile, section) {
            fetch("{{ route('sendotp') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        mobile: mobile
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === '1') {
                        showVerified('OTP sent successfully!');
                        startResendTimer(section);
                        console.log(section);
                    } else {
                        displayAlert('Failed to send OTP: ' + data.message);
                    }
                });
        }

        function startResendTimer(section) {
            console.log(section);
            const otpSection = document.getElementById(section + 'otpSection');
            console.log(otpSection);
            if (!otpSection) {
                console.error('OTP section not found for:', section);
                return;
            }

            const timerAlert = otpSection.querySelector('.timerAlert');
            if (!timerAlert) {
                console.error('Timer alert not found for:', section);
                return;
            }

            let timeLeft = 20;
            timerAlert.style.display = 'block';
            timerAlert.innerHTML = `Resend in ${timeLeft} seconds`;

            resendTimer = setInterval(() => {
                timeLeft--;
                timerAlert.innerHTML = `Resend in ${timeLeft} seconds`;

                if (timeLeft <= 0) {
                    clearInterval(resendTimer);
                    timerAlert.style.display = 'none';

                    const getOtpSection = document.getElementById(section + 'getOtpSection');
                    if (getOtpSection) {
                        const sendOtpButton = getOtpSection.querySelector('.getotp');
                        if (sendOtpButton) {
                            sendOtpButton.innerHTML = 'Resend';
                            sendOtpButton.style.display = 'inline';
                            sendOtpButton.disabled = false;
                        } else {
                            console.error('Send OTP button not found in:', section + 'getOtpSection');
                        }
                    } else {
                        console.error('Get OTP section not found for:', section + 'getOtpSection');
                    }
                }

            }, 1000);
        }

        function verifyOTP(otp, section) {
            let mobileNumber;
            console.log(otp);
            console.log(section);
            if (section === 'knowcar') {
                mobileNumber = document.getElementById('knowcarmobile').value;
            } else if (section === 'dontknowcar') {
                mobileNumber = document.getElementById('dontknowcarmobile').value;
            } else if (section === 'newcar') {
                mobileNumber = document.getElementById('newcarmobile').value;
            } else if (section === 'knowbike') {
                mobileNumber = document.getElementById('knowbikemobile').value;
            } else if (section === 'dontknowbike') {
                mobileNumber = document.getElementById('dontknowbikemobile').value;
            } else if (section === 'newbike') {
                mobileNumber = document.getElementById('newbikemobile').value;
            } else if (section === 'havevehicle') {
                mobileNumber = document.getElementById('havevehmobile').value;
            } else {
                mobileNumber = document.getElementById('newvehiclemob').value;
            }

            fetch("{{ route('verifyotp') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        otp: otp,
                        mobile: mobileNumber
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('OTP verification response:', data);
                    if (data.status === '1') {
                        showVerified('OTP verified successfully!');
                        clearInterval(resendTimer);
                        document.getElementById(section + 'otpSection').style.display = 'none';

                        const button = document.getElementById(section + 'getOtpSection').querySelector('.getotp');
                        var mainsubmitButton = document.getElementById('mainsubmit');
                        if (button) {
                            button.disabled = true;
                            button.innerHTML = 'Verified';
                            button.style.opacity = '0.5';

                            mainsubmitButton.disabled = false;
                            mainsubmitButton.style.opacity = '1';
                        } else {
                            console.error('Button not found in:', section + 'getOtpSection');
                        }
                    } else {
                        displayAlert('Invalid OTP. Please try again.');
                    }
                })
                .catch(error => console.error('Error verifying OTP:', error));
        }


        function submitKnowCarOtp() {
            const otpInput = document.getElementById('knowcarotpmobile');
            verifyOTP(otpInput.value, 'knowcar');
        }

        function submitDontKnowCarOtp() {
            const otpInput = document.getElementById('dontknowcarotpmobile');
            verifyOTP(otpInput.value, 'dontknowcar');
        }

        function submitNewCarOtp() {
            const otpInput = document.getElementById('newcarotpmobile');
            verifyOTP(otpInput.value, 'newcar');
        }

        function submitKnowBikeOtp() {
            const otpInput = document.getElementById('knowbikeotpmobile');
            verifyOTP(otpInput.value, 'knowbike');
        }

        function submitDontKnowBikeOtp() {
            const otpInput = document.getElementById('dontknowbikeotpmobile');
            verifyOTP(otpInput.value, 'dontknowbike');
        }

        function submitNewBikeOtp() {
            const otpInput = document.getElementById('newbikeotpmobile');
            verifyOTP(otpInput.value, 'newbike');
        }

        function submitHaveVehOtp() {
            const otpInput = document.getElementById('havevehotpmobile');
            verifyOTP(otpInput.value, 'havevehicle');
        }

        function submitHaveNewVehOtp() {
            const otpInput = document.getElementById('newvehicleotpmob');
            verifyOTP(otpInput.value, 'newvehicle');
        }

        function displaySuccess(message) {
            const errorBox = document.querySelector('.MainverifiyedBox');
            const errorTitleElement = errorBox?.querySelector('.error__title');
            errorTitleElement.innerText = message;
            errorBox.style.display = "flex";
            setTimeout(() => {
                errorBox.style.display = 'none';
            }, 3000);
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

        document.getElementById('knowcarmobile').addEventListener('input', function() {
            validateInput(this, 10, 'verifycarButton');
        });
        document.getElementById('dontknowcarmobile').addEventListener('input', function() {
            validateInput(this, 10, 'verifydontcarButton');
        });
        document.getElementById('newcarmobile').addEventListener('input', function() {
            validateInput(this, 10, 'verifynewcarButton');
        });

        document.getElementById('knowbikemobile').addEventListener('input', function() {
            validateInput(this, 10, 'verifybikeButton');
        });
        document.getElementById('dontknowbikemobile').addEventListener('input', function() {
            validateInput(this, 10, 'verifydontbikeButton');
        });
        document.getElementById('newbikemobile').addEventListener('input', function() {
            validateInput(this, 10, 'verifynewbikeButton');
        });
        document.getElementById('havevehmobile').addEventListener('input', function() {
            validateInput(this, 10, 'verifyhavevehButton');
        });
        document.getElementById('newvehiclemob').addEventListener('input', function() {
            validateInput(this, 10, 'verifynewvehButton');
        });

        function validateOtpInput(input, maxLength) {
            const pattern = new RegExp('^[0-9]{0,' + maxLength + '}$');
            if (!pattern.test(input.value)) {
                input.value = input.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            }
        }

        document.getElementById('knowcarotpmobile').addEventListener('input', function() {
            validateOtpInput(this, 6);
        });
        document.getElementById('dontknowcarotpmobile').addEventListener('input', function() {
            validateOtpInput(this, 6);
        });
        document.getElementById('newcarotpmobile').addEventListener('input', function() {
            validateOtpInput(this, 6);
        });

        document.getElementById('knowbikeotpmobile').addEventListener('input', function() {
            validateOtpInput(this, 6);
        });
        document.getElementById('dontknowbikeotpmobile').addEventListener('input', function() {
            validateOtpInput(this, 6);
        });
        document.getElementById('newbikeotpmobile').addEventListener('input', function() {
            validateOtpInput(this, 6);
        });
        document.getElementById('havevehotpmobile').addEventListener('input', function() {
            validateOtpInput(this, 6);
        });
        document.getElementById('newvehicleotpmob').addEventListener('input', function() {
            validateOtpInput(this, 6);
        });


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
                    url: "{{ route('shriram.getcity') }}",
                    type: 'POST',
                    data: {
                        'city': city,
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        cityListDiv.empty();

                        if (Object.keys(response).length > 0) {
                            let htmlContent = '<ul class="cityList">';
                            $.each(response, function(key, value) {
                                const cityText = value ? value : '';
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
                    }
                });
            } else {
                cityListDiv.empty().hide();
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

        function editMobileNumber(mobileId, btnId) {
            var mobileInput = document.getElementById(mobileId);
            var verifyButton = document.getElementById(btnId);

            mobileInput.value = '';
            mobileInput.removeAttribute('readonly');
            verifyButton.disabled = false;
            verifyButton.innerHTML = 'Verify';
            mobileInput.focus();
        }
    </script>
</body>

</html>