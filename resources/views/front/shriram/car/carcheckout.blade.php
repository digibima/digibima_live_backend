<?php
use App\Models\Proposal;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Checkout</title>
    <style>
        #electricalModel .modal-confirm {
            width: 650px;
            max-width: 650px;
            margin: 2rem auto !important;
        }

        #nonelectricalModel .modal-confirm,
        #lpgcngModel .modal-confirm {
            width: 600px;
            max-width: 600px;
            margin: 8rem auto !important;
        }


        .leftside {
            text-align: left;
            margin-bottom: 25px !important;
        }

        .rightside {
            text-align: right;
            margin-bottom: 25px !important;
        }

        .placeholder {
            opacity: 1 !important;
            cursor: pointer !important;
        }


        .nav-tabs {
            --bs-nav-tabs-border-width: 0 !important;
        }

        .nav-link {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9f9f9 !important;
            color: #1c5fa8 !important;
            height: 50px;
            border-radius: 5px;
            margin: 3px;
        }

        #reporttab1 .nav-link.active {
            background-color: #1c5fa8 !important;
            color: #fff !important;
        }

        .remove {
            cursor: pointer;
        }

        #currentValue {
            font-weight: 500;
        }

        .fa-indian-rupee-sign {
            margin-right: 4px;
        }

        input[type="radio"].myradio1 {
            box-shadow: 0 0.125rem 0.25rem rgba(255, 255, 255, 255) !important;

        }

        .activeradio {
            border: 1px solid #6f82f0 !important;
        }

        .myradio1 input[type="radio"] {
            display: none;
        }

        .policyselect,
        .policyselect1 {
            padding: 0px 8px !important;
        }

        /* .policyselect span {
            font-size: 13px;
        } */
    </style>
    @include('front.partial.csslink')

</head>

<body>
    @php
        // dd($data);
        $addedaddon = $data['addon']['addedaddon'] ?? [];
        $addonprice = $data['addon']['addonprice'];
        //dd($addedaddon,$addonprice);
        // ['premium' => ['yearoneprice' => $oneyear, 'yeartwoprice' => $twoyear, 'yearthreeprice' => $threeyear]] = $data;
        ['addon' => ['addonprice' => ['pac' => $pac, 'mp' => $mp, 'rsa' => $rsa, 'zd' => $zd]]] = $data;
        $verified = $data['verified'];
        //    dd($data);
        $totalpremium = $data['totalpremium'];
        $insured = $data['insured'];
        $addon = $data['addon']['addonprice'];

    @endphp
    @include('front.partial.header')
    <div id="loader">
        <div class="loader-container">
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
            <div class="dot dot-3"></div>
            <div class="dot dot-4"></div>
        </div>
    </div>
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
        <section id="planrow">
            <div class="container">
                <div class="row filblock ">

                    <!-- Left Col Start -->

                    <div class="col-md-12 col-lg-12 p-0 mb-2">
                        <a href="plans.php" class="smlink"><span><i class="bi bi-arrow-left"></i></span> Go back to
                            quotes</a>
                    </div>

                    <div class="col-md-8 col-lg-8 col-xl-8 ">

                        <div class="row">


                            <div class="row">
                                <form action="{{ route('shriram.journey') }}" id="checkoutForm" method="post">
                                    <div class="col-md-12">
                                        @csrf
                                        <!-- Policy Start -->
                                        <div class="row shadow-sm coverblock">

                                            <div class="col-md-12 col-lg-12 mb-2">
                                                <input type="hidden" name="identity" value="car">
                                                <h5>Pick a plan, and get started today!</h5>

                                            </div>
                                            <div class="col-md-12 col-lg-12 ">
                                                <h6 class="mb-2">Comprehensive Cover</h6>

                                                <div class="py-1 px-4"
                                                    style="height: 50px; background-color: #adcbf0 !important; border-radius:5px;">
                                                    <p>Covers damage to your bike and to third- partyy<br><span
                                                            style="float: right"><i
                                                                class="fa-solid fa-indian-rupee-sign"></i><b>{{ $data['cover']['cover1amt'] }}</b></span>
                                                    </p>
                                                    {{-- <div class="myradio1 ">
                                                        <input type="radio" name="Comprehentab" id="Comprehentab"
                                                            value="tab1" @checked($data['cover']['selectedcover'] != 'tab2')>

                                                        <p>Covers damage to your bike and to third- party<span
                                                                style="float: right"><i
                                                                    class="fa-solid fa-indian-rupee-sign"></i><b>{{ $data['cover']['cover1amt'] }}</b></span>
                                                        </p>

                                                    </div> --}}
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-6 col-lg-6 ">
                                                <h6 class="mb-2">Third-Party Cover</h6>
                                                <div class="policyselect1">
                                                    <div class="myradio1 ">
                                                        <input type="radio" name="Comprehentab" id="thirdpartytab"
                                                            value="tab2" @checked($data['cover']['selectedcover'] == 'tab2')>

                                                        <p>Covers darnage occurred only to third-party and not your bike
                                                            <span style="float: right"><i
                                                                    class="fa-solid fa-indian-rupee-sign"></i><b>{{ $data['cover']['cover2amt'] }}</b></span>
                                                        </p>

                                                    </div>
                                                </div>
                                            </div> --}}

                                        </div>
                                        <!-- Policy End -->

                                        <div class="row shadow-sm coverblock">
                                            <div class="col-md-12 col-lg-12 mb-3">
                                                <h5>Additional Cover</h5>
                                            </div>

                                            <div class="col-md-12 col-lg-12 col-xs-12">
                                                <ul id="reporttab1" class="nav nav-tabs">
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#" data-toggle="modal"
                                                            data-target="#electricalModel">Electrical Accessories</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#" data-toggle="modal"
                                                            data-target="#nonelectricalModel">Non-Electrical
                                                            Accessories</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#" data-toggle="modal"
                                                            data-target="#lpgcngModel">LPG/CNG</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row shadow-sm coverblock">

                                            <div class="col-md-12 col-lg-12 mb-2">
                                                <h5>Insured value (IDV)</h5>
                                                {{-- <p class="mb-3">Choosing a multi-year plan saves your money and the trouble of
                                                    remembering yearly renewals.</p> --}}
                                            </div>
                                            <div class="col-md-12 col-lg-12">
                                                <h6>Insured Value</h6>
                                                <div class="mb-2 d-flex">
                                                    <span style="margin-left:10px; margin-top:8px; "><i
                                                            class="fa-solid fa-indian-rupee-sign"></i></span>
                                                    <input type="text" name="idvinsurevalue" id="currentValue"
                                                        class="form-control"
                                                        value="{{ $data['minrange'] ? $data['minrange'] : '' }}"
                                                        maxlength="7" style="width:100px;" disabled>

                                                </div>
                                                <div>
                                                    <small style="float: left;">At Risk</small><small
                                                        style="float: right;">Risk
                                                        Free</small>
                                                    <input type="range" class="form-range" id="customRange1"
                                                        min="{{ $data['minrange'] }}" max="{{ $data['maxrange'] }}"
                                                        value="{{ $data['insured'] ? $data['insured'] : '' }}">
                                                    <small style="float: left;"><i
                                                            class="fa-solid fa-indian-rupee-sign"></i><b>{{ $data['minrange'] }}</b></small>
                                                    {{-- @php
                                                            dd($data);
                                                        @endphp --}}
                                                    <small style="float: right;"><i
                                                            class="fa-solid fa-indian-rupee-sign"></i><b>{{ $data['maxrange'] }}</b></small>
                                                </div>
                                            </div>

                                        </div>




                                    </div>
                                    <input type="submit" id="checkoutLogin" class="d-none">
                                </form>

                            </div>
                            <div id="ridercol" class="row shadow-sm coverblock">
                                <div class="col-md-12 col-lg-12 mb-2">
                                    <h5>Add Ons</h5>
                                    {{-- <p class="mb-3">Choosing a multi-year plan saves your money and the trouble of
                                        remembering yearly renewals.</p> --}}
                                </div>



                                <div class="col-md-12 col-sm-12">
                                    <div class="row addrow">
                                        <div class="col-md-12 col-lg-8 col-xl-8">
                                            <h6 class="mt-2">Personal Accidental Cover</h6>
                                        </div>
                                        <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                                            <p>Premium</p>
                                            <h6>₹{{ $pac }}</h6>
                                        </div>

                                        <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                                            <button
                                                class="btnsm add-btn @if (in_array('pac', $addedaddon)) disabled @endif"
                                                data-addon="pac" onclick="addAddon('pac')">Add</button>
                                        </div>
                                    </div>
                                    <div class="row addrow" id="thirdparthide">
                                        <div class="col-md-12 col-lg-8 col-xl-8">
                                            <h6 class="mt-2">Road Side Assistance</h6>
                                        </div>
                                        <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                                            <p>Premium</p>
                                            <h6>₹{{ $rsa }}</h6>
                                        </div>
                                        <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                                            <button
                                                class="btnsm add-btn @if (in_array('rsa', $addedaddon)) disabled @endif"
                                                data-addon="rsa" onclick="addAddon('rsa')">Add</button>

                                        </div>
                                    </div>
                                    <div class="row addrow" id="thirdpartshow">
                                        <div class="col-md-12 col-lg-8 col-xl-8">
                                            <h6 class="mt-2">ZERO Depreciation</h6>
                                        </div>
                                        <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                                            <p>Premium</p>
                                            <h6>₹{{ $zd }}</h6>
                                        </div>
                                        <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                                            <button
                                                class="btnsm add-btn @if (in_array('zd', $addedaddon)) disabled @endif"
                                                data-addon="zd" onclick="addAddon('zd')">Add</button>
                                        </div>
                                    </div>
                                    <div class="row addrow" id="thirdpartshow">
                                        <div class="col-md-12 col-lg-8 col-xl-8">
                                            <h6 class="mt-2">Motor Protection</h6>
                                        </div>
                                        <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                                            <p>Premium</p>
                                            <h6>₹{{ $mp }}</h6>
                                        </div>
                                        <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                                            <button
                                                class="btnsm add-btn @if (in_array('mp', $addedaddon)) disabled @endif"
                                                data-addon="mp" onclick="addAddon('mp')">Add</button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                    <!-- Left Col End -->

                    <!-- Right Col Start -->
                    <div class="col-md-4 col-lg-4 col-xl-4 ">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-2 shadow-sm">
                                    <div id="qtblock" class="row px-0">
                                        <div class="col-md-12 col-lg-12 mb-2">
                                            <h5>Here's you</h5>
                                        </div>

                                        <div class="col-md-12 col-lg-12 mb-3">
                                            <h6>Please verify your details and continue</h6>
                                            <div class="border border-light border-1 border-dashed px-2 py-1">
                                                <table width="100%" class="mbtm">
                                                    <tbody>
                                                        <tr>
                                                            <td><small>Make</small></td>
                                                            <td class="text-end">
                                                                <h6>{{ $verified['brand'] }}</h6>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><small>Model & Variant</small></td>
                                                            <td class="text-end">
                                                                <h6>{{ $verified['model'] }}</h6>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><small>Cubic Capacity</small></td>
                                                            <td class="text-end">
                                                                <h6>{{ $verified['cc'] }}</h6>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><small>Registration Date</small></td>
                                                            <td class="text-end">
                                                                <h6>{{ $verified['regdate'] }}</h6>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><small>Policy Status</small></td>
                                                            <td class="text-end">
                                                                <h6>{{ $verified['regdate'] }}</h6>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-2 shadow-sm">
                                    <div id="qtblock" class="row px-0">
                                        <div class="col-md-12 col-lg-12 mb-2">
                                            <h5>Add Ons</h5>
                                        </div>
                                        <div class="col-md-12 col-lg-12 mb-2">
                                            <table width="100%" class="mbtm">
                                                <tbody id="addonSelectList"></tbody>
                                            </table>
                                        </div>
                                        {{-- <div class="col-md-12 col-lg-12 mb-3">
                                            <h6>More Coverage</h6>
                                            <div class="border border-light border-1 border-dashed px-2 py-1">
                                                <table width="100%" class="mbtm">
                                                    <tbody>
                                                        <tr>
                                                            <td><small>Missing out on benefits</small></td>
                                                            <td class="text-end"><a href="#ridercol"
                                                                    class="smlink text-primary">View Riders</a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div> --}}

                                        <div class="col-md-12 col-lg-12 mb-4 bg-light py-2 px-3">
                                            <table width="100%" class="mbtm ">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0">Total Premium</h6>
                                                        </td>
                                                        <td class="text-end">
                                                            <h6><span><i
                                                                        class="fa fa-indian-rupee-sign"></i></span>{{ $totalpremium }}
                                                            </h6>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-12 col-lg-12">
                                            <a href="#" onclick="gotoJourney()"><button
                                                    class="getstarted mb-2 w-100">Continue</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Right Col End -->

                </div>
            </div>
        </section>

        <!-- enterOtp    -->
        <div id="electricalModel" class="modal fade">
            <div class="modal-dialog modal-confirm ">
                <form method="" enctype="multipart/form-data">
                    <div class="modal-content px-4 modal-content">
                        <div class="modal-header flex-column">
                            <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-12 col-xs-12">
                                <h6 class="text-left text-dark" style="text-align: left!important"><b>Enter Electrical
                                        accessories details</b></h6>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12 col-xs-12 text-left">
                                <table style="width: 100%;">
                                    <tr class="mb-2">
                                        <td class="leftside"><b>Add extra coverage</b><span><i
                                                    class="fa-solid fa-circle-info"></i></span></td>
                                    </tr>
                                    <tr class="mb-2">

                                        <td class="leftside">Consumables</td>
                                        <td class="rightside"><button class="btnsm disabled">₹315</button></td>
                                    </tr>
                                    <tr class="mb-2">
                                        <td class="leftside">Daily Reimbursement</td>
                                        <td class="rightside"><button class="btnsm disabled">₹315</button></td>
                                    </tr>
                                    <tr class="mb-2">
                                        <td class="leftside">Emergency Transport & Hotel expenses reimbursment</td>
                                        <td class="rightside"><button class="btnsm disabled">₹315</button></td>
                                    </tr>
                                    <tr class="mb-2">
                                        <td class="leftside">Engine Protector</td>
                                        <td class="rightside"><button class="btnsm disabled">₹315</button></td>
                                    </tr>
                                    <tr class="mb-2">
                                        <td class="leftside">KEY Replacement</td>
                                        <td class="rightside"><button class="btnsm disabled">₹315</button></td>
                                    </tr>
                                    <tr class="mb-2">
                                        <td class="leftside">Legal Liability Coverages For Paid Driver</td>
                                        <td class="rightside"><button class="btnsm disabled">₹315</button></td>
                                    </tr>
                                    <tr class="mb-2">
                                        <td class="leftside">Legal Liability To Employees</td>
                                        <td class="rightside"><button class="btnsm disabled">₹315</button></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <!-- enterOtp   -->

        <!-- nonelectricalModel    -->
        <div id="nonelectricalModel" class="modal fade">
            <div class="modal-dialog modal-confirm ">
                <form method="" enctype="multipart/form-data">
                    <div class="modal-content px-4 modal-content">
                        <div class="modal-header flex-column">
                            <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-12 col-xs-12">
                                <h6 class="text-left text-dark" style="text-align: left!important"><b>Enter Electrical
                                        accessories details</b></h6>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 col-xs-12 text-left">
                                <div class="input-container mb-2">
                                    <input type="text" name="eleaccessname" class="input form-control"
                                        id="eleaccessname" autocomplete="off" spellcheck="false" maxlength="6">
                                    <div class="placeholder">Enter accessories name</div>
                                    <span class="error-message" id="eleaccessnameError"></span>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 text-left">
                                <div class="input-container mb-2">
                                    <input type="text" name="eleaccessamount" class="input form-control"
                                        id="eleaccessamount" autocomplete="off" spellcheck="false" maxlength="6">
                                    <div class="placeholder">Enter accessories amount</div>
                                    <span class="error-message" id="eleaccessamountError"></span>
                                </div>
                            </div>
                        </div>


                        <div class="modal-footer justify-content-center">
                            <button class="btnsm">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- nonelectricalModel   -->


        <!-- lpgcngModel    -->
        <div id="lpgcngModel" class="modal fade">
            <div class="modal-dialog modal-confirm ">
                <form method="" enctype="multipart/form-data">
                    <div class="modal-content px-4 modal-content">
                        <div class="modal-header flex-column">
                            <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-12 col-xs-12">
                                <h6 class="text-left text-dark" style="text-align: left!important"><b>Choose your car
                                        fuel type</b></h6>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 col-xs-12 text-left">
                                <div class="input-container mb-2">
                                    <select name="fueltype" class="input form-control" id="fueltype">
                                        <option value="">Fuel Type</option>
                                        <option value="cng">CNG</option>
                                        <option value="lpg">LPG</option>
                                    </select>

                                    <div class="placeholder">Fuel Type</div>
                                    <span class="error-message" id="eleaccessnameError"></span>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 text-left">
                                <div class="input-container mb-2">
                                    <input type="text" name="fueltypeprice" class="input form-control"
                                        id="fueltypeprice" autocomplete="off" spellcheck="false" maxlength="16">
                                    <div class="placeholder">Fuel Type Price</div>
                                    <span class="error-message" id="eleaccessamountError"></span>
                                </div>
                            </div>
                        </div>


                        <div class="modal-footer justify-content-center">
                            <button class="btnsm">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- lpgcngModel   -->
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
        document.addEventListener('DOMContentLoaded', function() {
            // Function to handle radio buttons with a specific class
            function setupRadioButtons(radioName, policyClass, callback) {
                const radios = document.querySelectorAll(`input[name="${radioName}"]`);
                radios.forEach(radio => {
                    const policyDiv = radio.closest(`.${policyClass}`);

                    radio.addEventListener('change', () => {
                        document.querySelectorAll(`.${policyClass}`).forEach(div => div.classList
                            .remove('activeradio'));
                        policyDiv.classList.add('activeradio');

                        // Call the callback function if provided (e.g., to handle additional logic)
                        if (callback) callback();
                    });

                    policyDiv.addEventListener('click', () => {
                        radio.checked = true;
                        radio.dispatchEvent(new Event('change'));
                    });

                    if (radio.checked) policyDiv.classList.add('activeradio');
                });
            }


        });

        const rangeInput = document.getElementById('customRange1');
        const currentValueDisplay = document.getElementById('currentValue');

        const updateValues = (value) => {
            const formattedValue = Number(value).toLocaleString();
            currentValueDisplay.value = formattedValue;
        };

        const initialValue = currentValueDisplay.value.replace(/,/g, '') || rangeInput.value;
        if (initialValue) {
            // Set the range input value without commas
            rangeInput.value = initialValue;
            updateValues(initialValue);
        }

        // Event listener for the range input
        rangeInput.addEventListener('input', function() {
            updateValues(rangeInput.value);
        });

        // Event listener for the text input
        currentValueDisplay.addEventListener('input', function() {
            // Remove commas for processing
            const numericValue = currentValueDisplay.value.replace(/,/g, '');

            // Check if the value is a valid number and within the range
            if (!isNaN(numericValue) && numericValue >= rangeInput.min && numericValue <= rangeInput.max) {
                rangeInput.value = numericValue; // Update range input value
                updateValues(numericValue); // Update displayed value
            } else if (isNaN(numericValue)) {
                currentValueDisplay.value = ''; // Clear if not a number
            }
        });

        function gotoJourney() {
            $('#checkoutLogin').click();
            // window.location.href = 'http://localhost/insurance/motor/dontcar';
        }

        var addedaddon = @json($addedaddon);
        var addonprice = @json($addonprice);
        console.log(addonprice)
        let selectedAddOns = addedaddon || [];
        let rowCounter = 0;


        function addAddon(value) {
            fetch("{{ route('shriram.addaddon') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        addon: value,

                        identity: 'car'
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    window.location.reload();
                })
                .catch(error => {});
            addAddonList(value);
        }



        function addAddonList(value) {
            const list = document.querySelector("#addonSelectList");
            list.innerHTML = "";

            const addonMappings = {
                "pac": "Personal Accidental Cover",
                "rsa": "Road Side Assistance",
                "mp": "Motor Protection",
                "zd": "ZERO Depreciation"
            };
            const addonPrices = {
                "pac": '315',
                "rsa": '600',
                "mp": '400',
                "zd": '500'
            };
            selectedAddOns.forEach((addon, index) => {

                const fullName = addonMappings[addon] || addon;
                const price = addonPrices[addon] || "N/A";
                const tr = document.createElement("tr");
                tr.setAttribute("data-addon", addon);
                tr.id = `addon-row-${index}`;

                const tdName = document.createElement("td");
                tdName.innerHTML = `<small class="fw-bold">${fullName}</small>`;
                tr.appendChild(tdName);

                const tdRemove = document.createElement("td");
                tdRemove.classList.add("text-end");
                tdRemove.innerHTML =

                    `<small class="fw-bold">₹${price}</small>
                    <span><i class="fa-solid fa-trash remove" onclick="removeAddon('${addon}')"></i></span>`;

                tdRemove.querySelector('.remove').addEventListener('click', () => {
                    removeAddon(addon);
                });

                tr.appendChild(tdRemove);
                list.appendChild(tr);
            });
        }

        function removeAddon(addon) {
            fetch("{{ route('shriram.removeaddon') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        addon: addon,
                        identity: 'car'

                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    // console.log("Server Response:", data);
                    window.location.reload();
                })
                .catch(error => {
                    // console.error('Error:', error);
                });
        }

        window.onload = function() {
            addAddonList();
        };
    </script>
</body>

</html>
