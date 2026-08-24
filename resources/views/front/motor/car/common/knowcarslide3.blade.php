<?php

use App\Models\Proposal;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Know Car</title>
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

        .btn-check:checked+.btn,
        .btn.active,
        .btn.show,
        .btn:first-child:active,
        :not(.btn-check)+.btn:active {
            /* color: var(--bs-btn-active-color); */
            /* background-color: var(--bs-btn-active-bg); */
            border-color: #F1F1F1 !important;
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

        .error {
            border: 1px solid red !important;
        }
    </style>
</head>

<body>
    @php
        // dd($data);
        // dd($data['policytype']);
        // dd($data['selected_plan_type']);
        $policytype = $data['policy_type'];
        // dd($policytype);

        // dd(gettype((int) $data['brandyear']), (int) $data['brandyear'] - 1);
        // dd( $data['brandyear']);
    @endphp
    @include('front.partial.header')
    @include('front.motor.car.common.loader')
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
                    <div class="col-lg-12 col-md-12 head text-center mb-3">
                        Motor insurance provides essential coverage against accidents.
                    </div>

                    {{-- {{dd($data)}} --}}
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="image">
                            <img src="{{ config('constant.BASE_URL') }}front/images/Car-1.jpg" alt="true">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('car.knowcarsteptwo') }}" method="POST" id="knowcarslideTwo"
                            name="knowcarslideTwo">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">

                                    <div class="row" id="knowsection">
                                        <div class="col-lg-12 col-md-12 col-sm-12" id="ownershipTransferDiv">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                                    <div class="havevehnumb mb-2">
                                                        <label for="prepolitype">Select your previous
                                                            policy type</label>
                                                        <select name="prepolitype" id="prepolitype"
                                                            onchange="togglePrexpdateSection()">
                                                            <option value="">Select Policy</option>

                                                            <option value="bundled"
                                                                {{ isset($data['prepolitype']) && $data['prepolitype'] == 'bundled' ? 'selected' : '' }}>
                                                                Bundled (1 Year OD + 3 Years TP)
                                                            </option>

                                                            <option value="comprehensive"
                                                                {{ isset($data['prepolitype']) && $data['prepolitype'] == 'comprehensive' ? 'selected' : '' }}>
                                                                Comprehensive (1 Year OD + 1 Year TP)
                                                            </option>

                                                            <option value="odonly"
                                                                {{ isset($data['prepolitype']) && $data['prepolitype'] == 'odonly' ? 'selected' : '' }}>
                                                                OD Only
                                                            </option>

                                                            <option value="tponly"
                                                                {{ isset($data['prepolitype']) && $data['prepolitype'] == 'tponly' ? 'selected' : '' }}>
                                                                TP Only
                                                            </option>
                                                        </select>

                                                    </div>
                                                </div>



                                                <div class="col-lg-12 mb-3" id="bundleddiv"
                                                    style="{{ isset($data['prepolitype']) && $data['prepolitype'] == 'bundled' ? 'display: block;' : 'display: none;' }}">
                                                    <div class="row mb-2">

                                                        <div class="col-lg-6 mb-2">
                                                            <div class="havevehnumb">
                                                                <label for="brandyear">From Date</label>
                                                                <div class="input-group1 datepickerdiv ">
                                                                    <input type="text" name="bdfromdate"
                                                                        class="input form-control datepicker"
                                                                        id="bdfromdate"
                                                                        value="{{ isset($data['bdfromdate']) ? $data['bdfromdate'] : '' }}"
                                                                        autocomplete="off"
                                                                        placeholder="Expiry From Date"
                                                                        spellcheck="false" maxlength="10">

                                                                    <button class="btn calendarButton" type="button"
                                                                        style="height: 43px">
                                                                        <i class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 mb-2">
                                                            <div class="havevehnumb">
                                                                <label for="fromdate">To Date</label>
                                                                <div class="input-group1 datepickerdiv ">
                                                                    <input type="text" name="bdtodate"
                                                                        class="input form-control datepicker"
                                                                        id="bdtodate"
                                                                        value="{{ isset($data['bdtodate']) ? $data['bdtodate'] : '' }}"
                                                                        autocomplete="off" placeholder="Expiry To Date"
                                                                        spellcheck="false" maxlength="10">

                                                                    <button class="btn calendarButton" type="button"
                                                                        style="height: 43px">
                                                                        <i class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">


                                                            <p class="mb-2" style="font-weight: 600; ">
                                                                Third Party policy Date
                                                            </p>

                                                            <div class="row mb-2">

                                                                <div class="col-lg-6">
                                                                    <div class="havevehnumb">
                                                                        <label for="brandyear">From Date</label>
                                                                        <div class="input-group1 datepickerdiv ">
                                                                            <input type="text" name="bdtpfromdate"
                                                                                class="input form-control datepicker"
                                                                                id="bdtpfromdate"
                                                                                value="{{ isset($data['bdtpfromdate']) ? $data['bdtpfromdate'] : '' }}"
                                                                                autocomplete="off"
                                                                                placeholder="Expiry From Date"
                                                                                spellcheck="false" maxlength="10">

                                                                            <button class="btn calendarButton"
                                                                                type="button" style="height: 43px">
                                                                                <i
                                                                                    class="fa-solid fa-calendar-days"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="havevehnumb">
                                                                        <label for="fromdate">To Date</label>
                                                                        <div class="input-group1 datepickerdiv ">
                                                                            <input type="text" name="bdtptodate"
                                                                                class="input form-control datepicker"
                                                                                id="bdtptodate"
                                                                                value="{{ isset($data['bdtptodate']) ? $data['bdtptodate'] : '' }}"
                                                                                autocomplete="off"
                                                                                placeholder="Expiry To Date"
                                                                                spellcheck="false" maxlength="10">

                                                                            <button class="btn calendarButton"
                                                                                type="button" style="height: 43px">
                                                                                <i
                                                                                    class="fa-solid fa-calendar-days"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 mb-3" id="comprensivdatediv"
                                                    style="display: {{ isset($data['prepolitype']) && $data['prepolitype'] == 'comprehensive' ? 'block' : 'none' }};">
                                                    <div class="row mb-2">
                                                        <div class="col-lg-6">
                                                            <div class="havevehnumb">
                                                                <label for="brandyear">From Date</label>
                                                                <div class="input-group1 datepickerdiv ">
                                                                    <input type="text" name="compfromdate"
                                                                        class="input form-control datepicker"
                                                                        id="compfromdate"
                                                                        value="{{ isset($data['compfromdate']) ? $data['compfromdate'] : '' }}"
                                                                        autocomplete="off"
                                                                        placeholder="Expiry From Date"
                                                                        spellcheck="false" maxlength="10">

                                                                    <button class="btn calendarButton" type="button"
                                                                        style="height: 43px">
                                                                        <i class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="havevehnumb">
                                                                <label for="fromdate">To Date</label>
                                                                <div class="input-group1 datepickerdiv ">
                                                                    <input type="text" name="comptodate"
                                                                        class="input form-control datepicker"
                                                                        id="comptodate"
                                                                        value="{{ isset($data['comptodate']) ? $data['comptodate'] : '' }}"
                                                                        autocomplete="off"
                                                                        placeholder="Expiry To Date"
                                                                        spellcheck="false" maxlength="10">

                                                                    <button class="btn calendarButton" type="button"
                                                                        style="height: 43px">
                                                                        <i class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-lg-12 mb-3" id="oddiv"
                                                    style="{{ isset($data['prepolitype']) && $data['prepolitype'] == 'odonly' ? 'display: block;' : 'display: none;' }}">
                                                    <div class="row mb-2">

                                                        <div class="col-lg-6 mb-2">
                                                            <div class="havevehnumb">
                                                                <label for="brandyear">From Date</label>
                                                                <div class="input-group1 datepickerdiv ">
                                                                    <input type="text" name="odfromdate"
                                                                        class="input form-control datepicker"
                                                                        id="odfromdate"
                                                                        value="{{ isset($data['odfromdate']) ? $data['odfromdate'] : '' }}"
                                                                        autocomplete="off"
                                                                        placeholder="Expiry From Date"
                                                                        spellcheck="false" maxlength="10">

                                                                    <button class="btn calendarButton" type="button"
                                                                        style="height: 43px">
                                                                        <i class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 mb-2">
                                                            <div class="havevehnumb">
                                                                <label for="fromdate">To Date</label>
                                                                <div class="input-group1 datepickerdiv ">
                                                                    <input type="text" name="odtodate"
                                                                        class="input form-control datepicker"
                                                                        id="odtodate"
                                                                        value="{{ isset($data['odtodate']) ? $data['odtodate'] : '' }}"
                                                                        autocomplete="off"
                                                                        placeholder="Expiry To Date"
                                                                        spellcheck="false" maxlength="10">

                                                                    <button class="btn calendarButton" type="button"
                                                                        style="height: 43px">
                                                                        <i class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">


                                                            <p class="mb-2" style="font-weight: 600; ">
                                                                Third Party policy Date
                                                            </p>

                                                            <div class="row mb-2">

                                                                <div class="col-lg-6">
                                                                    <div class="havevehnumb">
                                                                        <label for="brandyear">From Date</label>
                                                                        <div class="input-group1 datepickerdiv ">
                                                                            <input type="text" name="odtpfromdate"
                                                                                class="input form-control datepicker"
                                                                                id="odtpfromdate"
                                                                                value="{{ isset($data['odtpfromdate']) ? $data['odtpfromdate'] : '' }}"
                                                                                autocomplete="off"
                                                                                placeholder="Expiry From Date"
                                                                                spellcheck="false" maxlength="10">

                                                                            <button class="btn calendarButton"
                                                                                type="button" style="height: 43px">
                                                                                <i
                                                                                    class="fa-solid fa-calendar-days"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="havevehnumb">
                                                                        <label for="fromdate">To Date</label>
                                                                        <div class="input-group1 datepickerdiv ">
                                                                            <input type="text" name="odtptodate"
                                                                                class="input form-control datepicker"
                                                                                id="odtptodate"
                                                                                value="{{ isset($data['odtptodate']) ? $data['odtptodate'] : '' }}"
                                                                                autocomplete="off"
                                                                                placeholder="Expiry To Date"
                                                                                spellcheck="false" maxlength="10">

                                                                            <button class="btn calendarButton"
                                                                                type="button" style="height: 43px">
                                                                                <i
                                                                                    class="fa-solid fa-calendar-days"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 mb-3" id="tpdatediv"
                                                    style="{{ isset($data['prepolitype']) && $data['prepolitype'] == 'tponly' ? 'display: block;' : 'display: none;' }}">

                                                    <div class="row mb-2">

                                                        <div class="col-lg-6">
                                                            <div class="havevehnumb">
                                                                <label for="brandyear">From Date</label>
                                                                <div class="input-group1 datepickerdiv ">
                                                                    <input type="text" name="tpfromdate"
                                                                        class="input form-control datepicker"
                                                                        id="tpfromdate"
                                                                        value="{{ isset($data['tpfromdate']) ? $data['tpfromdate'] : '' }}"
                                                                        autocomplete="off"
                                                                        placeholder="Expiry From Date"
                                                                        spellcheck="false" maxlength="10">

                                                                    <button class="btn calendarButton" type="button"
                                                                        style="height: 43px">
                                                                        <i class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="havevehnumb">
                                                                <label for="fromdate">To Date</label>
                                                                <div class="input-group1 datepickerdiv ">
                                                                    <input type="text" name="tptodate"
                                                                        class="input form-control datepicker"
                                                                        id="tptodate"
                                                                        value="{{ isset($data['tptodate']) ? $data['tptodate'] : '' }}"
                                                                        autocomplete="off"
                                                                        placeholder="Expiry To Date"
                                                                        spellcheck="false" maxlength="10">

                                                                    <button class="btn calendarButton" type="button"
                                                                        style="height: 43px">
                                                                        <i class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="col-lg-12 mb-3 thirdpartyhide">
                                                    <div class="row">
                                                        <div class="col-lg-6 mb-3 thirdpartyhide">
                                                            <p class="characters mb-2">Was there any ownership transfer
                                                                in the previous year?</p>
                                                            <div class="radiolabel">
                                                                <label class="switch">
                                                                    <input type="checkbox" id="ownershiptoggle"
                                                                        onchange="toggleOwnershipDiv()" />
                                                                    <span class="slider round"></span>
                                                                </label>
                                                                <input type="hidden" name="ownershiptoggle"
                                                                    id="ownershiptoggle_hidden" value="0" />
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 mb-2 thirdpartyhide" id="thirdpartyMain">
                                                            <p class="characters mb-2">Did you make a claim in your
                                                                previous
                                                                policy
                                                                period? </p>
                                                            <div class="radiolabel">
                                                                <label class="switch">
                                                                    <input type="checkbox" id="policytoggle"
                                                                        name="policytoggle" value="policytoggle"
                                                                        onchange="policytoggleDiv()">
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12" id="ownershipDiv">
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
                                                                        value="0"
                                                                        @if (!isset($data['bonus-button']) || $data['bonus-button'] == '0') checked @endif>
                                                                    <div class="bonus-box">0%
                                                                    </div>
                                                                </label>
                                                                <label class="bonus-label mb-2">
                                                                    <input type="radio" name="bonus-button"
                                                                        value="20"
                                                                        @if (!isset($data['bonus-button']) || $data['bonus-button'] == '20') checked @endif>
                                                                    <div class="bonus-box">20%
                                                                    </div>

                                                                </label>
                                                                <label class="bonus-label mb-2">
                                                                    <input type="radio" name="bonus-button"
                                                                        value="25"
                                                                        @if (!isset($data['bonus-button']) || $data['bonus-button'] == '25') checked @endif>
                                                                    <div class="bonus-box">25%
                                                                    </div>
                                                                </label>
                                                                <label class="bonus-label mb-2">
                                                                    <input type="radio" name="bonus-button"
                                                                        value="35"
                                                                        @if (!isset($data['bonus-button']) || $data['bonus-button'] == '35') checked @endif>
                                                                    <div class="bonus-box">35%
                                                                    </div>
                                                                </label>
                                                                <label class="bonus-label mb-2">
                                                                    <input type="radio" name="bonus-button"
                                                                        value="45"
                                                                        @if (!isset($data['bonus-button']) || $data['bonus-button'] == '45') checked @endif>
                                                                    <div class="bonus-box">45%
                                                                    </div>
                                                                </label>
                                                                <label class="bonus-label mb-2">
                                                                    <input type="radio" name="bonus-button"
                                                                        value="50"
                                                                        @if (!isset($data['bonus-button']) || $data['bonus-button'] == '50') checked @endif>
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
                            <input type="submit" class="d-none" id="knowcarstepTwoSubmit">
                        </form>

                        <div class="col-lg-12 col-md-12 col-sm-12 ">
                            <a href="{{ route('car.knowcar', ['id' => 1]) }}"><button class="continue"
                                    style="width: 100px;">Back</button></a>
                            <button id="continue-button" class="continue mt-2" style="width: 125px;"
                                onClick="DontknowCarStarted(event);">Continue</button>
                            <p class="formbtn">Already bought a policy from DigiBima? <a href="#">Renew Now</a>
                            </p>
                        </div>
                    </div>
                </div>
        </section>
    </main>
    @include('front.partial.chatwidget')
    @include('front.partial.footer')
    @include('front.partial.jslink')
    <script src="{{ config('constant.BASE_URL') }}front/js/validateFields.js"></script>
    <script>
        let prepolytype = @json($data['selected_plan_type']);
        window.addEventListener("load", function() {
            document.getElementById("loader").style.display = "none";
        });

        window.onbeforeunload = function() {
            document.getElementById("loader").style.display = "flex";
        };
        $(document).ready(function() {
            $('#brand').select2({
                placeholder: 'Manufacture Vehicle',
                allowClear: true,
            });
            $('#model').select2({
                placeholder: 'Select Model',
                allowClear: true,
            });

            getBrand();
        });



        function allowOnlyNumbers(inputField) {
            inputField.value = inputField.value.replace(/\D/g, "");
        }
        //console.log(@json($data));
        function toggleOwnershipDiv() {
            const checkbox = document.getElementById('ownershiptoggle');
            const hiddenInput = document.getElementById('ownershiptoggle_hidden');

            hiddenInput.value = checkbox.checked ? '1' : '0';

            // (Optional) If you're showing/hiding UI parts:
            const ownershipDiv = document.getElementById('ownershipDiv');
            const thirdpartyhide = document.getElementById('thirdpartyMain');

            if (checkbox.checked) {
                // ownershipDiv.style.display = 'none';
                // thirdpartyhide.style.display = 'none';
            } else {
                // ownershipDiv.style.display = 'block';
                // thirdpartyhide.style.display = 'block';
            }
        }


        function policytoggleDiv() {
            const policycheckbox = document.getElementById('policytoggle');
            const ownershipDiv = document.getElementById('ownershipDiv');
            if (policycheckbox.checked) {
                ownershipDiv.style.display = 'none';
            } else {
                ownershipDiv.style.display = 'block';
            }
        }

        function togglePrexpdateSection() {
            const prepolicyType = document.getElementById('prepolitype').value;

            // Define all sections
            const $bundledDiv = $('#bundleddiv');
            const $compDiv = $('#comprensivdatediv');
            const $odDiv = $('#oddiv');
            const $tpDiv = $('#tpdatediv');

            // Reset visibility & disable all input fields
            $bundledDiv.hide().find('input').prop('disabled', true).val('');
            $compDiv.hide().find('input').prop('disabled', true).val('');
            $odDiv.hide().find('input').prop('disabled', true).val('');
            $tpDiv.hide().find('input').prop('disabled', true).val('');

            // Show based on policy type
            if (prepolicyType === 'bundled') {
                $bundledDiv.show().find('input').prop('disabled', false);
                $compDiv.hide().find('input').prop('disabled', true).val('');
                $odDiv.hide().find('input').prop('disabled', true).val('');
                $tpDiv.hide().find('input').prop('disabled', true).val('');

            } else if (prepolicyType === 'comprehensive') {
                $compDiv.show().find('input').prop('disabled', false);
                $bundledDiv.hide().find('input').prop('disabled', true).val('');

                $odDiv.hide().find('input').prop('disabled', true).val('');
                $tpDiv.hide().find('input').prop('disabled', true).val('');

            } else if (prepolicyType === 'odonly') {
                $odDiv.show().find('input').prop('disabled', false);
                $bundledDiv.hide().find('input').prop('disabled', true).val('');
                $compDiv.hide().find('input').prop('disabled', true).val('');
                $tpDiv.hide().find('input').prop('disabled', true).val('');

            } else if (prepolicyType === 'tponly') {
                $tpDiv.show().find('input').prop('disabled', false);
                $bundledDiv.hide().find('input').prop('disabled', true).val('');
                $compDiv.hide().find('input').prop('disabled', true).val('');
                $odDiv.hide().find('input').prop('disabled', true).val('');
            }
        }








        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });

            $('.calendarButton').click(function() {
                $(this).siblings('.datepicker').datepicker('show');
            });


            if (prepolytype == "1") {
                // $('#tpfromdate, #tptodate')
                //     .prop('readonly', true)
                //     .css('pointer-events', 'none');

                // $('#tpfromdate, #tptodate')
                //     .siblings('.calendarButton')
                //     .css('pointer-events', 'none');
            }
        });




        // Attach event listeners to the radio buttons
        // document.querySelectorAll('input[name="carowner"]').forEach((radio) => {
        //     radio.addEventListener('change', toggleNewCarIndi);
        // });

        // // Ensure this function is called after the DOM is loaded
        // document.addEventListener("DOMContentLoaded", function() {
        //     toggleNewCarIndi();
        // });




        function DontknowCarStarted(event) {
            event.preventDefault();
            var prepolicyType = document.getElementById('prepolitype');


            const comprehensiveCheckbox = document.getElementById('comprehensive');
            const tpdCheckbox = document.getElementById('thirdparty');
            const prepolexpdate = document.getElementById('prepolexpdate');
            const prepolfromdate = document.getElementById('prepolfromdate');
            // const carcompany = document.querySelector('#company:checked');



            const policycheckbox = document.getElementById('policytoggle');
            const ownershipcheckbox = document.getElementById('ownershiptoggle');
            const owndamagecheckbox = document.getElementById('owndamage');
            const selectpolicy = document.getElementById('policytype');


            const odtodate = document.getElementById('odtodate');
            const odfromdate = document.getElementById('odfromdate');

            const comtodate = document.getElementById('comptodate');
            const comfromdate = document.getElementById('compfromdate');

            const tppolicyno = document.getElementById('tppolicyno');
            const tptodate = document.getElementById('tptodate');
            const tpfromdate = document.getElementById('tpfromdate');



            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox?.querySelector('.error__title');

            // const fields = [{
            //         id: "brand",
            //         type: "New Car Manufacture"
            //     },
            //     {
            //         id: "model",
            //         type: "New Car Model & Variant"
            //     },
            //     {
            //         id: "carregdate",
            //         type: "New Car Register Date"
            //     },
            //     {
            //         id: "brandyear",
            //         type: "New Car Manufacture Vehicle"
            //     }
            // ];




            // for (let field of fields) {
            //     let inputElement = document.getElementById(field.id);
            //     if (!inputElement || inputElement.offsetParent === null) continue;

            //     let fieldValue = inputElement.value.trim();
            //     let validationResult = validateField(field.id, field.type, fieldValue);

            //     if (validationResult == '0') {
            //         return false;
            //     }
            // }


            let dateField = document.getElementById("carregdate");
            if (dateField && dateField.offsetParent !== null) {
                let dateParts = dateField.value.split("-");

                if (dateParts.length !== 3 || dateParts[0].length !== 2 || dateParts[1].length !== 2 || dateParts[2]
                    .length !== 4) {
                    errorBox.style.display = "flex";
                    errorTitleElement.innerText = "Invalid date format. Please use dd-mm-yyyy.";
                    setTimeout(() => {
                        errorBox.style.display = "none";
                    }, 3000);
                    return false;
                }

                let enteredDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                let currentDate = new Date();

                currentDate.setHours(0, 0, 0, 0);
                enteredDate.setHours(0, 0, 0, 0);

                if (enteredDate > currentDate) {
                    errorBox.style.display = "flex";
                    errorTitleElement.innerText = "Future dates are not allowed.";
                    setTimeout(() => {
                        errorBox.style.display = "none";
                    }, 3000);
                    return false;
                }
            }


            let brandyear = document.getElementById("brandyear");
            let dateField1 = document.getElementById("carregdate");

            if (brandyear && dateField1 && brandyear.offsetParent !== null && dateField1.offsetParent !== null) {
                let brandYearValue = parseInt(brandyear.value.trim());
                let dateParts = dateField1.value.split("-");

                if (dateParts.length !== 3 || isNaN(brandYearValue)) {
                    errorBox.style.display = "flex";
                    errorTitleElement.innerText = "Invalid Year Of Manufacture or register date format.";
                    setTimeout(() => {
                        errorBox.style.display = "none";
                    }, 3000);
                    return false;
                }
            }
            const underIndividualRadio = document.getElementById('individual');
            const underCompanyRadio = document.getElementById('company');

            // if (!underIndividualRadio.checked && !underCompanyRadio.checked) {
            //     errorBox.style.display = "flex";
            //     errorTitleElement.innerText = "Please select either Individual or Company.";
            //     setTimeout(() => {
            //         errorBox.style.display = "none";
            //     }, 3000);
            //     return false;
            // }


            if (prepolicyType.value.trim() === "") {
                // prepolfromdate.classList.add("error");
                if (errorBox) {
                    errorBox.style.display = "flex";
                    errorTitleElement.innerText = "Please Select Previous Policy Type";
                    setTimeout(() => {
                        errorBox.style.display = "none";
                    }, 3000);
                }
                return false;
            }

            // if (owndamagecheckbox.checked && selectpolicy.value === "bundled") {
            //     // return false;
            //     if (odfromdate.value == "") {
            //         errorBox.style.display = "flex";
            //         errorTitleElement.innerText = "From Date is required";
            //         setTimeout(() => {
            //             errorBox.style.display = "none";
            //         }, 3000);
            //         return false;
            //     } else if (odtodate.value == "") {
            //         errorBox.style.display = "flex";
            //         errorTitleElement.innerText = "To Date is required";
            //         setTimeout(() => {
            //             errorBox.style.display = "none";
            //         }, 3000);
            //         return false;
            //     }
            //     // else {
            //     //     const [fromDay, fromMonth, fromYear] = odfromdate.value.split("-").map(Number);
            //     //     const [toDay, toMonth, toYear] = odtodate.value.split("-").map(Number);

            //     //     const fromDate = new Date(fromYear, fromMonth - 1, fromDay);
            //     //     const toDate = new Date(toYear, toMonth - 1, toDay);

            //     //     const today = new Date();
            //     //     today.setHours(0, 0, 0, 0);
            //     //     fromDate.setHours(0, 0, 0, 0);
            //     //     // toDate.setHours(0, 0, 0, 0);

            //     //     if (fromDate >= today) {
            //     //         errorBox.style.display = "flex";
            //     //         errorTitleElement.innerText = "From Date cannot be today or in the future.";
            //     //         setTimeout(() => {
            //     //             errorBox.style.display = "none";
            //     //         }, 3000);
            //     //         return false;
            //     //     }

            //     //     const expectedToDate = new Date(fromDate);
            //     //     expectedToDate.setFullYear(expectedToDate.getFullYear() + 3);
            //     //     expectedToDate.setDate(expectedToDate.getDate() - 1);

            //     //     if (toDate.getTime() !== expectedToDate.getTime()) {
            //     //         errorBox.style.display = "flex";
            //     //         errorTitleElement.innerText = "To Date should be exactly 3   year after From Date, minus one day.";
            //     //         setTimeout(() => {
            //     //             errorBox.style.display = "none";
            //     //         }, 3000);
            //     //         return false;
            //     //     }
            //     // }
            // }
            // if (owndamagecheckbox.checked && selectpolicy.value === "odonly") {
            //     // return false;
            //     if (odtodate.value == "") {
            //         errorBox.style.display = "flex";
            //         errorTitleElement.innerText = "To Date is required";
            //         setTimeout(() => {
            //             errorBox.style.display = "none";
            //         }, 3000);
            //         return false;
            //     }
            //     if (tppolicyno.value == "") {
            //         errorBox.style.display = "flex";
            //         errorTitleElement.innerText = "Third Party Policy Number is required";
            //         setTimeout(() => {
            //             errorBox.style.display = "none";
            //         }, 3000);
            //         return false;
            //     } else if (tpfromdate.value == "") {
            //         errorBox.style.display = "flex";
            //         errorTitleElement.innerText = "From Date is required";
            //         setTimeout(() => {
            //             errorBox.style.display = "none";
            //         }, 3000);
            //         return false;
            //     } else if (tptodate.value == "") {
            //         errorBox.style.display = "flex";
            //         errorTitleElement.innerText = "To Date is required";
            //         setTimeout(() => {
            //             errorBox.style.display = "none";
            //         }, 3000);
            //         return false;
            //     }
            //     // else {
            //     //     const [fromDay, fromMonth, fromYear] = odfromdate.value.split("-").map(Number);
            //     //     const [toDay, toMonth, toYear] = odtodate.value.split("-").map(Number);

            //     //     const fromDate = new Date(fromYear, fromMonth - 1, fromDay);
            //     //     const toDate = new Date(toYear, toMonth - 1, toDay);

            //     //     const today = new Date();
            //     //     today.setHours(0, 0, 0, 0);
            //     //     fromDate.setHours(0, 0, 0, 0);
            //     //     toDate.setHours(0, 0, 0, 0);

            //     //     if (fromDate >= today) {
            //     //         errorBox.style.display = "flex";
            //     //         errorTitleElement.innerText = "From Date cannot be today or in the future.";
            //     //         setTimeout(() => {
            //     //             errorBox.style.display = "none";
            //     //         }, 3000);
            //     //         return false;
            //     //     }

            //     //     const expectedToDate = new Date(fromDate);
            //     //     expectedToDate.setFullYear(expectedToDate.getFullYear() + 1);
            //     //     expectedToDate.setDate(expectedToDate.getDate() - 1);

            //     //     if (toDate.getTime() !== expectedToDate.getTime()) {
            //     //         errorBox.style.display = "flex";
            //     //         errorTitleElement.innerText = "To Date should be exactly 1 year after From Date, minus one day.";
            //     //         setTimeout(() => {
            //     //             errorBox.style.display = "none";
            //     //         }, 3000);
            //     //         return false;
            //     //     }

            //     //     const tpfromdate = document.getElementById('tpfromdate').value;
            //     //     const tptodate = document.getElementById('tptodate').value;
            //     //     const carregDate = document.getElementById('carregdate').value;

            //     //     if (tpfromdate && tptodate && carregDate) {
            //     //         const [tempDay, tempMonth, tempYear] = tptodate.split("-").map(Number);
            //     //         const [carregDay, carregMonth, carregYear] = carregDate.split("-").map(Number);
            //     //         const [fromDay, fromMonth, fromYear] = tpfromdate.split("-").map(Number);

            //     //         const tempDate = new Date(tempYear, tempMonth - 1, tempDay);
            //     //         const carRegDate = new Date(carregYear, carregMonth - 1, carregDay);
            //     //         const fromDate = new Date(fromYear, fromMonth - 1, fromDay);

            //     //         if (fromDate.getTime() !== carRegDate.getTime()) {
            //     //             errorBox.style.display = "flex";
            //     //             errorTitleElement.innerText =
            //     //                 "Third party from date should be the same as Car Registration Date.";
            //     //             setTimeout(() => {
            //     //                 errorBox.style.display = "none";
            //     //             }, 3000);
            //     //             return false;
            //     //         }

            //     //         // Add 3 years to Car Registration Date and subtract 1 day to calculate "To Date"
            //     //         carRegDate.setFullYear(carRegDate.getFullYear() + 3);
            //     //         carRegDate.setDate(carRegDate.getDate() - 1);

            //     //         // Compare the "To Date" with the expected "To Date"
            //     //         if (tempDate.getTime() !== carRegDate.getTime()) {
            //     //             errorBox.style.display = "flex";
            //     //             errorTitleElement.innerText =
            //     //                 "Third party to date should be exactly 3 years after Car Registration Date, minus one day.";
            //     //             setTimeout(() => {
            //     //                 errorBox.style.display = "none";
            //     //             }, 3000);
            //     //             return false;
            //     //         }
            //     //     }

            //     // }
            // }

            // comprehensive case validation --------------------------------

            if (prepolicyType.value.trim() == "comprehensive") {
                if (comfromdate.value === "") {
                    errorBox.style.display = "flex";
                    errorTitleElement.innerText = "From Date is required";
                    setTimeout(() => {
                        errorBox.style.display = "none";
                    }, 3000);
                    return false;
                } else if (comtodate.value === "") {
                    errorBox.style.display = "flex";
                    errorTitleElement.innerText = "To Date is required";
                    setTimeout(() => {
                        errorBox.style.display = "none";
                    }, 3000);
                    return false;
                }
                // else {
                //     const [fromDay, fromMonth, fromYear] = comfromdate.value.split("-").map(Number);
                //     const [toDay, toMonth, toYear] = comtodate.value.split("-").map(Number);

                //     const fromDate = new Date(fromYear, fromMonth - 1, fromDay);
                //     const toDate = new Date(toYear, toMonth - 1, toDay);

                //     const today = new Date();
                //     today.setHours(0, 0, 0, 0);
                //     fromDate.setHours(0, 0, 0, 0);
                //     toDate.setHours(0, 0, 0, 0);

                //     if (fromDate >= today) {
                //         errorBox.style.display = "flex";
                //         errorTitleElement.innerText = "From Date cannot be today or in the future.";
                //         setTimeout(() => {
                //             errorBox.style.display = "none";
                //         }, 3000);
                //         return false;
                //     }

                //     const expectedToDate = new Date(fromDate);
                //     expectedToDate.setFullYear(expectedToDate.getFullYear() + 1);
                //     expectedToDate.setDate(expectedToDate.getDate() - 1);

                //     if (toDate.getTime() !== expectedToDate.getTime()) {
                //         errorBox.style.display = "flex";
                //         errorTitleElement.innerText = "To Date should be exactly 1 year after From Date, minus one day.";
                //         setTimeout(() => {
                //             errorBox.style.display = "none";
                //         }, 3000);
                //         return false;
                //     }
                // }
            }

            // third party case validation ------------------------------------------

            if (prepolicyType.value.trim() == "tponly") {
                // return false;
                if (tpfromdate.value == "") {
                    errorBox.style.display = "flex";
                    errorTitleElement.innerText = "From Date is required";
                    setTimeout(() => {
                        errorBox.style.display = "none";
                    }, 3000);
                    return false;
                } else if (tptodate.value == "") {
                    errorBox.style.display = "flex";
                    errorTitleElement.innerText = "To Date is required";
                    setTimeout(() => {
                        errorBox.style.display = "none";
                    }, 3000);
                    return false;
                }
                // else {
                //     const [fromDay, fromMonth, fromYear] = tpfromdate.value.split("-").map(Number);
                //     const [toDay, toMonth, toYear] = tptodate.value.split("-").map(Number);

                //     const fromDate = new Date(fromYear, fromMonth - 1, fromDay);
                //     const toDate = new Date(toYear, toMonth - 1, toDay);

                //     const today = new Date();
                //     today.setHours(0, 0, 0, 0);
                //     fromDate.setHours(0, 0, 0, 0);
                //     toDate.setHours(0, 0, 0, 0);

                //     if (fromDate >= today) {
                //         errorBox.style.display = "flex";
                //         errorTitleElement.innerText = "From Date cannot be today or in the future.";
                //         setTimeout(() => {
                //             errorBox.style.display = "none";
                //         }, 3000);
                //         return false;
                //     }

                //     const expectedToDate = new Date(fromDate);
                //     expectedToDate.setFullYear(expectedToDate.getFullYear() + 1);
                //     expectedToDate.setDate(expectedToDate.getDate() - 1);

                //     if (toDate.getTime() !== expectedToDate.getTime()) {
                //         errorBox.style.display = "flex";
                //         errorTitleElement.innerText = "To Date should be exactly 1 year after From Date, minus one day.";
                //         setTimeout(() => {
                //             errorBox.style.display = "none";
                //         }, 3000);
                //         return false;
                //     }
                // }
            }

            if (policycheckbox.checked) {
                document.querySelectorAll('input[name="bonus-button"]').forEach((radio) => {
                    radio.checked = false;
                });
            }

            if (ownershipcheckbox.checked) {
                document.querySelectorAll('input[name="bonus-button"], input[name="policytoggle"]').forEach((radio) => {
                    // radio.checked = false;
                });
            }


            $('#knowcarstepTwoSubmit').click();
            return false;
        }





        function clearErrorTwo(errorId) {
            const errorElement = document.getElementById(errorId);
            if (errorElement) {
                errorElement.textContent = '';
            }
        }

        window.addEventListener('DOMContentLoaded', function() {
            const dateInputs = document.querySelectorAll('.datepicker');
            dateInputs.forEach(input => {
                input.addEventListener('input', formatDateInput);
            });
        });
        async function getBrand() {
            let brands = await getBrandName('CAR');

            let defaultSelectedBrand = "{{ $data['brand'] ?? '' }}";
            if (brands && brands.date) {
                $('#brand').empty();
                $('#brand').append(
                    '<option value="" style="display: none;" selected disabled>Select a brand</option>');


                if (brands.brand && Object.keys(brands.brand).length > 0) {
                    for (let key in brands.brand) {
                        const brandValue = brands.brand[key];
                        // console.log(brandValue.id, brandValue.MANUFACTURER);
                        // return false;
                        $('#brand').append(
                            `<option value="${brandValue.MANUFACTURER}" ${brandValue.MANUFACTURER === defaultSelectedBrand ? 'selected' : ''} data-id="${brandValue.id}">
                        ${brandValue.MANUFACTURER}
                    </option>`
                        );
                    }
                }
                // $('#carregdate').val(brands.date);
                $('#brand').trigger('change');

                $('#brand').on('change', function() {
                    const selectedBrand = {
                        brand: $(this).val(),
                        id: $(this).find('option:selected').data('id'),
                        type: "CAR"
                    };
                    console.log(selectedBrand);
                    getModel(selectedBrand);
                });
            }
        }

        async function getModel(selectedBrand) {
            const models = await getModelName(selectedBrand);
            let defaultSelectedModel = "{{ $data['model'] ?? '' }}";
            // console.log(defaultSelectedModel);

            if (models && Array.isArray(models)) {
                $('#model').empty().append(
                    '<option value="" style="display: none;" selected disabled>Select a model</option>'
                );

                models.forEach((modelObj) => {
                    const model = modelObj.model;
                    const isSelected = String(modelObj.id) === String(defaultSelectedModel) ? 'selected' : '';
                    // console.log(modelObj.id);
                    $('#model').append(
                        `<option value="${modelObj.id}" ${isSelected} data-id="${modelObj.id}">${model}</option>`
                    );
                });

                $('#model').trigger('change');
            }
        }

        getBrand();


        // Register Date
        const dateInput = document.getElementById('carregdate');
        const yearDropdown = document.getElementById('brandyear');

        // let selectedYearValue = null;

        // function updateYearDropdown() {
        //     const dateValue = dateInput.value.trim();
        //     const parts = dateValue.split('-');


        //     selectedYearValue = yearDropdown.value;

        //     yearDropdown.innerHTML = '<option value="" selected>Select Year</option>';

        //     if (parts.length === 3) {
        //         const year = parseInt(parts[2]);
        //         if (!isNaN(year)) {
        //             const previousYear = year - 1;

        //             const option1 = document.createElement('option');
        //             option1.value = year;
        //             option1.textContent = year;
        //             yearDropdown.appendChild(option1);

        //             const option2 = document.createElement('option');
        //             option2.value = previousYear;
        //             option2.textContent = previousYear;
        //             yearDropdown.appendChild(option2);

        //             if (selectedYearValue) {
        //                 yearDropdown.value = selectedYearValue;
        //             }
        //         }
        //     }
        // }

        // dateInput.addEventListener('blur', updateYearDropdown);
        // $('.datepicker').on('change', updateYearDropdown);




        // document.querySelectorAll('.datepicker').forEach(function(datepicker) {
        //     datepicker.addEventListener('change', function() {
        //         $('.datepicker').on('change', datepicker);
        //         console.log("Ram2");
        //         const fromDateInput = this;
        //         let toDateInput;
        //         let policytype = document.getElementById('policytype')?.value;

        //         if (fromDateInput.id === 'compfromdate') {
        //             toDateInput = document.getElementById('comptodate');
        //         } else if (fromDateInput.id === 'tpfromdate') {
        //             toDateInput = document.getElementById('tptodate');
        //         } else if (policytype === 'odonly' && fromDateInput.id === 'odfromdate') {
        //             toDateInput = document.getElementById('odtodate');
        //         } else if (policytype === 'bundled' && fromDateInput.id === 'odfromdate') {
        //             toDateInput = document.getElementById('odtodate');
        //         }

        //         if (toDateInput && policytype == 'bundled') {
        //             const [day, month, year] = fromDateInput.value.split('-').map(num => parseInt(num, 10));
        //             if (day && month && year) {
        //                 const fromDate = new Date(year, month - 1, day);
        //                 fromDate.setFullYear(fromDate.getFullYear() + 3);
        //                 fromDate.setDate(fromDate.getDate() - 1);

        //                 const formattedDate =
        //                     `${String(fromDate.getDate()).padStart(2, '0')}-${String(fromDate.getMonth() + 1).padStart(2, '0')}-${fromDate.getFullYear()}`

        //                 toDateInput.value = formattedDate;
        //                 $('#tpfromdate').val(fromDateInput.value);
        //                 $('#tptodate').val(formattedDate);

        //             }
        //         }

        //         if (toDateInput || policytype !== 'bundled') {
        //             const [day, month, year] = fromDateInput.value.split('-').map(num => parseInt(num, 10));
        //             if (day && month && year) {
        //                 const fromDate = new Date(year, month - 1, day);
        //                 fromDate.setFullYear(fromDate.getFullYear() + 1);
        //                 fromDate.setDate(fromDate.getDate() - 1);

        //                 toDateInput.value =
        //                     `${String(fromDate.getDate()).padStart(2, '0')}-${String(fromDate.getMonth() + 1).padStart(2, '0')}-${fromDate.getFullYear()}`;
        //             }
        //         }
        //     });
        // });

        // document.querySelectorAll('.datepicker').forEach(function(datepicker) {
        //     $(datepicker).datepicker({
        //         format: 'dd-mm-yyyy',
        //         autoclose: true
        //     });

        //     $(datepicker).on('changeDate', function() {
        //         handleDateChange(this);
        //     });

        //     datepicker.addEventListener('change', function() {
        //         handleDateChange(this);
        //     });
        // });



        // // console.log(prepolytype);

        // function handleDateChange(fromDateInput) {
        //     let toDateInput;
        //     let policytype = document.getElementById('policytype')?.value;
        //     console.log("Ram");
        //     // if (fromDateInput.id === 'compfromdate') {
        //     //     toDateInput = document.getElementById('comptodate');
        //     // } else if (fromDateInput.id === 'tpfromdate') {
        //     //     toDateInput = document.getElementById('tptodate');
        //     // } else if (policytype === 'odonly' && fromDateInput.id === 'odfromdate') {
        //     //     toDateInput = document.getElementById('odtodate');
        //     // } else if (policytype === 'bundled' && fromDateInput.id === 'odfromdate') {
        //     //     toDateInput = document.getElementById('odtodate');
        //     // }

        //     if (toDateInput) {
        //         const [day, month, year] = fromDateInput.value.split('-').map(num => parseInt(num, 10));
        //         if (day && month && year) {
        //             const fromDate = new Date(year, month - 1, day);

        //             if (policytype == 'bundled' && prepolytype == "1") {
        //                 // console.log(policytype);
        //                 fromDate.setFullYear(fromDate.getFullYear() + 3);
        //             } else {
        //                 // console.log("not bun");
        //                 fromDate.setFullYear(fromDate.getFullYear() + 1);
        //             }


        //             fromDate.setDate(fromDate.getDate() - 1);

        //             const formattedDate =
        //                 `${String(fromDate.getDate()).padStart(2, '0')}-${String(fromDate.getMonth() + 1).padStart(2, '0')}-${fromDate.getFullYear()}`;

        //             toDateInput.value = formattedDate;
        //             if (policytype == 'bundled') {
        //                 $('#tpfromdate').val(fromDateInput.value);
        //                 $('#tptodate').val(formattedDate);
        //             }

        //         }
        //     }

        // }
        function setToDate(fromId, toId) {
            const fromDateStr = document.getElementById(fromId).value;
            if (!fromDateStr) return;

            const [dd, mm, yyyy] = fromDateStr.split("-").map(Number);
            const fromDate = new Date(yyyy, mm - 1, dd);

            const toDate = new Date(fromDate);

            // Define 3-year policies
            const threeYearFields = ['bdtpfromdate', 'tpfromdate', 'odtpfromdate'];

            if (threeYearFields.includes(fromId)) {
                toDate.setFullYear(toDate.getFullYear() + 3);
            } else {
                toDate.setFullYear(toDate.getFullYear() + 1);
            }
            toDate.setDate(toDate.getDate() - 1);

            const formatted =
                String(toDate.getDate()).padStart(2, "0") + "-" +
                String(toDate.getMonth() + 1).padStart(2, "0") + "-" +
                toDate.getFullYear();

            document.getElementById(toId).value = formatted;
        }

        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            }).on('changeDate', function() {
                $(this).trigger('change');
            });

            $('.calendarButton').click(function() {
                $(this).siblings('.datepicker').datepicker('show');
            });

            const datePairs = [
                ['compfromdate', 'comptodate'],
                ['tpfromdate', 'tptodate'],
                ['bdfromdate', 'bdtodate'],
                ['bdtpfromdate', 'bdtptodate'],
                ['odfromdate', 'odtodate'],
                ['odtpfromdate', 'odtptodate']
            ];

            datePairs.forEach(([fromId, toId]) => {
                const $fromInput = $('#' + fromId);
                if ($fromInput.length) {
                    $fromInput.on('change', function() {
                        setToDate(fromId, toId);
                    });
                }
            });
        });
    </script>
</body>

</html>
