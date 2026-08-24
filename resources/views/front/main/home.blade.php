<?php

use App\Models\Proposal;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    @include('front.partial.csslink')
    <style>
        main {
            min-height: 70vh !important;
        }

        #findtopplan {
            min-height: 60vh !important;
        }

        #findtopplan .head {
            text-shadow: 0px 4px 4px rgba(0, 0, 0, 0)
        }

        .logoImage {
            width: 100%;
            height: 170px;
        }

        .logoImage img {
            opacity: 0.8;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            width: 100%;
            height: 100%;
        }

        .mainbox {
            cursor: pointer;
            border-radius: 20px;
            background: #F0F0F0;
            width: 100%;
            /* height: 250px; */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .getstarted1 {
            margin-top: 8px;
            width: 200px;
            height: 35px;
            background: #fff;
            border-radius: 15px;
            cursor: pointer;
            border: none;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .mainbox:hover .getstarted1 {
            background: #1c5fa8;
            color: #fff;
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
    <div class="MainverifiyedBox" style="float: right;display:none;"><span class="verifiy__icon"><i
                class="fa-solid fa-circle-check"></i></span>
        <p class="verifiyed__title  mb-0" style="margin-right:10px;">hello</p> <span class="verifiyed__close"><i
                class="fa-solid fa-xmark"></i></span>
    </div>
    <main id="slider-container">
        <section class="slide" id="findtopplan">

            <div class="col-lg-12 col-md-12 col-sm-12">
                <!-- <a href="{{ route('userroot') }}">
                    <button class="getstarted1 mb-2">Go to Dashboard</button>
                </a> -->
                <div class="row">

                    <div class="col-lg-6 col-md-6 col-sm-12 head">
                        At DigiBima, we understand the complexities of the insurance industry. Our software is built on
                        years of expertise and is designed to adapt to your evolving needs.

                    </div>


                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mainbox">
                                    <div class="logoImage mb-1">
                                        <img src="{{ config('constant.BASE_URL') }}front/images/healtmainimage.jpg"
                                            alt="">
                                    </div>
                                    <a href="{{ route('health.root') }}">
                                        <button class="getstarted1 mb-2">Health</button>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mainbox">
                                    <div class="logoImage mb-1">
                                        <img src="{{ config('constant.BASE_URL') }}front/images/carmainimage.jpg"
                                            alt="">
                                    </div>
                                    <a href="{{ route('motor.root') }}">
                                        <button class="getstarted1 mb-2">Vehicle</button>
                                    </a>
                                </div>
                            </div>
                        </div>


                    </div>


                </div>
            </div>
        </section>


    </main>
    @include('front.partial.footer')
    @include('front.partial.jslink')

</body>

</html>