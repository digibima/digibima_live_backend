<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Top Plans</title>
    <style>
        #paCoverAccordion,
        #irdaiNotice {
            transition: all 0.3s ease;
        }

        .fa-ban {
            color: red;
            font-size: 17px;
        }

        .checkbox-list {
            list-style-type: none;
            padding-left: 0;

        }

        .checkbox-list li {
            display: flex;
            align-items: center;
            margin-bottom: 10px;

        }

        .checkbox-list input[type="checkbox"] {
            margin-right: 10px;
            flex-shrink: 0;
        }

        .checkbox-list label {
            flex-grow: 1;
            word-wrap: break-word;
            cursor: pointer;
            font-size: 14px;
        }

        #pacoverModal .opt-out ul {
            padding-left: 0px;
        }

        #pacoverModal .opt-out {
            padding: 2px 15px;
        }

        #pacoverModal .filblock {
            padding: 1rem 2rem !important;
        }

        main {

            padding: 0rem 0rem !important;
        }

        select {
            cursor: pointer;
        }

        select:hover {
            background-color: #f0f0f0;
        }

        option {
            cursor: default;
        }

        @-webkit-keyframes placeHolderShimmer {
            0% {
                background-position: -468px 0;
            }

            100% {
                background-position: 468px 0;
            }
        }

        @keyframes placeHolderShimmer {
            0% {
                background-position: -468px 0;
            }

            100% {
                background-position: 468px 0;
            }
        }

        .animated-background {
            animation-duration: 1s;
            animation-fill-mode: forwards;
            animation-iteration-count: infinite;
            animation-name: placeHolderShimmer;
            animation-timing-function: linear;
            background: #f6f7f8;
            background: linear-gradient(to right, #eeeeee 8%, #dddddd 18%, #eeeeee 33%);
            background-size: 800px 104px;
            height: 366px;
            width: 300px;
            position: relative;
        }

        .placeholder,
        .placeholder1 {
            opacity: 1 !important;
            cursor: pointer !important;
            top: -12px !important;
            font-size: 0.8rem !important;
        }

        select {
            color: #000 !important;
            appearance: auto !important;
            padding: 6px !important;
        }



        #addAddonsModal .modal-dialog,
        #pacoverModal .modal-dialog {
            width: 100%;
            max-width: 1050px;
            background: white;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
        }

        #pacoverModal .modal-dialog {
            max-width: 600px;
        }

        #pacoverModal .modal-body,
        #pacoverModal .modal-body h5 {
            text-align: left !important;
        }

        #idvModal .modal-dialog {
            width: 100%;
            max-width: 500px;
            background: white;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin: 7rem auto;
        }

        #premiumModal .modal-dialog,
        #addonsPriceModal .modal-dialog {
            width: 100%;
            max-width: 800px;
            background: white;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin: 5rem auto;
        }

        /* #premiumDetailsTable tbody {
            max-height: 300px;
            overflow-y: auto;
            display: block;
            height: 300px;
        }

        #premiumDetailsTable thead,
        #premiumDetailsTable tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;

        }

        #premiumDetailsTable tr {
            text-align: left;
        }

        #premiumModal .table>:not(caption)>*>* {
            padding: .6rem .2rem;
        } */

        /* Box Styling */
        .addon-box {
            text-align: center;
        }

        h2 {
            color: #333;
        }

        p {
            font-size: 14px;
            color: #666;
        }

        .modal-content {
            padding: 0px 0px !important;
            border: none !important;
        }

        .modal-header {
            border-bottom: none !important;
            text-align: left;
        }

        /* Addon Grid */
        /* .addonlist {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin: 15px 0;
        } */


        .addonlist label {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #eef2ff;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .addonlist label:hover {
            background: #d8e0ff;
        }

        .addonlist label span {
            font-size: 15px;
        }

        .addonlist input[type="checkbox"] {
            accent-color: #4a90e2;
            width: 16px;
            height: 16px;
        }


        /* Responsive Design */
        @media (max-width: 500px) {
            .addon-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }

            .input-group {
                flex-direction: column;
            }
        }

        /* tooltip css  */

        .info {
            cursor: pointer;
        }

        /* tooltip css  */

        /* plans css  */
        .insurance-card {
            background: white;
            width: 100%;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logo1 {
            width: 100px;
            margin-bottom: 10px;
        }

        .insurance-card h5 {
            font-size: 15px;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
        }

        .idv {
            font-weight: bold;
            color: #000;
        }

        .price-box {
            background-color: #f5f7fa;
            padding: 10px 70px;
            margin: 5px 0;
            border-radius: 8px;
            text-align: center;
            transition: background 0.3s ease-in-out;
            border: none;
        }

        .price-box:hover {
            background-color: #e0e5ec;
        }

        .buy-now {
            font-size: 14px;
            color: #000;
            font-weight: 500;
        }

        .price {
            font-size: 18px;
            font-weight: bold;
            color: #222;
        }

        .plan-details {
            display: block;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 15px;
        }



        /* plans css  */


        /* Main Container */
        .insurance-container {

            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Option Boxes */
        .option {
            display: flex;
            flex: 1;
            min-width: 150px;
        }

        .option select {
            width: 100%;
        }

        .option label {
            margin-top: 8px;
            margin-right: 10px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .option select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            cursor: pointer;
            background: #f9f9f9;
            transition: all 0.3s ease;
        }

        .option select:hover,
        .option select:focus {
            border-color: #007bff;
            background: #fff;
        }

        /* Checkbox Styling */
        .checkbox-option {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            min-width: 150px;
        }

        .checkbox-option input {
            transform: scale(1.2);
            cursor: pointer;
        }

        .checkbox-option label {
            font-weight: 600;
            color: #333;
        }

        /* Tooltip Styling */
        .tooltip-icon {
            color: steelblue;
            top: 25px;
            margin-top: 3px;
        }

        .tooltip-text {
            visibility: hidden;
            position: absolute;
            z-index: 2;
            width: 360px;
            color: rgb(71, 61, 61);
            font-size: 14px;
            background-color: #adf1ec;
            border-radius: 10px;
            padding: 19px;
            font-weight: 400;
        }

        .tooltip-text::before {
            /* content: ""; */
            position: absolute;
            transform: rotate(45deg);
            background-color: #adf1ec;
            padding: 9px;
            z-index: 1;
        }

        .hover-text i {
            font-size: 14px !important;
            color: #bebcbc;
        }

        .hover-text:hover .tooltip-text {
            visibility: visible;
        }

        #top {
            top: -40px;
            left: -50%;
        }

        #top::before {
            top: 80%;
            left: 45%;
        }

        #bottom {
            top: 50px;
            left: 0%;
        }

        #bottom::before {
            top: -4%;
            left: 4%;
        }

        #left {
            top: -8px;
            right: 120%;
        }

        #left::before {
            top: 35%;
            left: 94%;
        }

        #right {
            top: -8px;
            left: 120%;
        }

        #right::before {
            top: 35%;
            left: -2%;
        }

        .hover-text {
            position: relative;
            display: inline-block;
            text-align: left;
        }

        .redspan {
            font-weight: bold;
            color: #d9534f;
        }

        .opt-out {
            background: #fff;
            padding: 15px;
            border-left: 5px solid #d9534f;
            border-radius: 5px;
            margin: 15px 0;
        }

        .opt-out ul {
            padding-left: 20px;
        }

        .opt-out li {
            margin-bottom: 8px;
            color: #333;
        }

        .tooltip-text h3 {
            font-size: 15px;
            font-weight: 800;
        }

        .addonlist1 {
            gap: 0px;
            margin: 0px 0;
        }

        .tooltip-text::before {


            /* Tooltip arrow */
            content: "";
            position: absolute;
            top: -12px;
            left: 50%;
        }

        .bottom1 {
            width: 600px;
        }

        .bottom1::before {
            top: -2% !important;
            left: 4% !important;
        }

        @media (max-width:620px) {
            .bottom1 {
                width: 300px;
                font-size: 12px !important;
            }

            .bottom1::before {
                top: -1% !important;
                left: 4% !important;
            }

            .bottom1 p {
                font-size: 12px !important;
            }

            .opt-out {
                padding: 5px;
                margin: 5px 0;
            }

        }
















        /* range slider_one css Start  */
        /* Modal Container */
        .idv-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Modal Title */
        .idv-title {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            text-align: left;
            padding: 1rem 0rem;
        }

        /* IDV Input Box */
        .idv-input {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: left;
            margin: 10px 0;
        }

        .idv-input input {
            width: 100px;
            padding: 8px;
            font-size: 16px;
            text-align: center;
            border: 2px solid #007bff;
            border-radius: 5px;
            outline: none;
        }

        /* Slider Container */
        .slider-container {
            position: relative;
            margin-top: 10px;
        }

        /* Range Slider */
        .slider_one {
            width: 100%;
            -webkit-appearance: none;
            height: 8px;
            border-radius: 5px;
            background: linear-gradient(90deg, #00A0B0, #0078A8, #00A0B0);
            outline: none;
            opacity: 0.9;
            transition: opacity 0.2s;
        }

        .slider_one:hover {
            opacity: 1;
        }

        /* Min & Max Labels */
        .idv-labels {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #555;
            margin-top: 5px;
        }

        /* Remove slider_one Default Shadow */
        .slider_one:before {
            position: inherit !important;
            content: none !important;
        }

        /* Update Button */
        .update-btn {
            width: 100%;
            height: 35px;
            padding: 1px 10px;
            color: #fff;
            /* background: #1c5fa8; */
            background: linear-gradient(135deg, #4e54c8, #8f94fb) !important;
            border-radius: 5px;
            border: none;
        }



        @media (min-width: 767px) and (max-width: 991px) {
            .bottom1 {
                width: 350px;
                font-size: 12px !important;
            }

            .bottom1 p {
                font-size: 12px !important;
            }

            .opt-out {
                padding: 5px;
                margin: 5px 0;
            }
        }

        .addon-price {
            font-weight: bold;
            color: #28a745;
        }

        .addon-name {
            font-weight: bold;
            color: #444;
        }

        #addonsPriceModal ul {
            padding-left: 0rem !important;
        }

        #addonsPriceModal ul li {
            padding: 8px 12px;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
        }

        #addonsPriceModal ul li:nth-child(odd) {
            background: #f9f9f9;
            border-radius: 8px;
        }

        .nav .nav-item button.active::after {

            border-bottom: none !important;


        }

        .slider_one {
            width: 100%;
            left: 10px !important;
            height: 8px;
            background: linear-gradient(90deg, #1a5dab, #17a2b8);
            border-radius: 5px;
            cursor: pointer;
        }

        .slider_one::-webkit-slider-thumb {
            width: 18px;
            height: 18px;
            background: #1a5dab;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid white;
        }

        .slider-values {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .idv_input {
            display: flex !important;
            justify-content: center;
            margin-bottom: 15px;
        }



        .update_btn {
            width: 70px;
            height: 35px;
            padding: 1px 10px;
            color: #fff;
            /* background: #1c5fa8; */
            background: linear-gradient(135deg, #4e54c8, #8f94fb) !important;
            border-radius: 5px;
            border: none;
            margin-left: 10px;

        }

        /* addon price model css start  */
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

        .card_info span,
        .prihead {
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

        #featuremodal ul li:nth-child(2n+1) {
            clear: both;
        }

        #featuremodal ul li {
            width: 50%;
            float: left;
            background: url("{{ config('constant.BASE_URL') }}front/images/check_icon.png") no-repeat scroll 0 6px;

        }

        #featuremodal ul li {
            padding: 0 0 12px 20px;
            margin: 0 0px;
            list-style: none;
            color: #000;
            font-size: 14px;
            font-family: tahoma-regular;
            line-height: 1.5;
        }

        #featuremodal ul {
            list-style-type: none;
            padding-left: 0;
        }

        #featuremodal ul li p {
            display: inline;
            margin-left: 5px;
        }

        #featuremodal ul li span i {
            vertical-align: middle;
            margin-right: 10px !important;
        }

        #featuremodal p {
            font-size: 14px !important;
        }

        .featureheading {
            font-size: 16px !important;
            padding-left: 50px;
        }


        /* addon price model css end */
    </style>
    @include('front.partial.csslink')
</head>

<body class="planlistbg">
    @php
        // dd($data);
        $under = $data['under'];
        // dd($data['vendor']);
        // dd( session('motortype'));
        $planeType = $data['plantype'];
        // dd($planeType);
         $selectedplaneType = $data['selectedplantype'] ?? [];
        // dd($selectedplaneType);
        $selected = in_array($selectedplaneType, array_keys($planeType)) ? $selectedplaneType : null;

        if (!$selected) {
            if (!array_key_exists(1, $planeType)) {
                $selected = 2;
            } else {
                $selected = $selectedplaneType;
            }
        }
        $addons = $data['addons'];
        $selectedAddons = $data['selectedaddons'] ?? [];
        $vehicledetails = $data['vehicledetails'];
        $pacover = $data['pacover'];

    @endphp
    @include('front.partial.header')
    @include('front.motor.bike.common.bikeloader')
    <section class="filterrow mt-4 px-5">
        <div class="mb-2">
            <a href="{{ session('motortype') == 'newbike' ? route('bike.newbike', 'back') : route('bike.knowbiketwo', 'back') }}"
                class="smlink prev-step mb-2"><span><i class="bi bi-arrow-left"></i></span>Go
                back to Previous</a>
        </div>
        <div class="row insurance-container ">
            <div class="col-lg-3 col-md-6   mb-2">
                <div class="option">
                    <table style="width: 100%;">
                        <tr>
                            <td>
                                <label for="planType">Plan Type</label>
                            </td>
                            <td>
                                <select id="planType" class="form-select" name="covertype">
                                    {{-- If there is only one option, select it by default --}}
                                    @if (count($planeType) === 1)
                                        @foreach ($planeType as $key => $type)
                                            <option value="{{ $key }}" selected>{{ $type }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="" selected disabled>Select Cover</option>
                                        @foreach ($planeType as $key => $type)
                                            <option value="{{ $key }}"
                                                {{ $selected == $key ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

            <div class="col-lg-2 col-md-6 mb-2">
                <div class="addonlist addonlist1 ">
                    <label class="hover-text">
                        <table style="width: 100%;">
                            <tr>
                                <td><input type="checkbox" name="pacoverCheckbox" id="pacoverCheckbox"
                                        {{ !empty($pacover) && $pacover == '1' ? 'checked' : 'checked' }}></td>
                                <td><span>PA Cover</span><i class="fas fa-info-circle tooltip-icon"
                                        style="float: right;"></i>

                                </td>
                            </tr>
                        </table>

                        <span class="tooltip-text bottom1" id="bottom" style="font-size: 13px;">
                            <p>
                                As per the <strong>Insurance Regulatory and Development Authority of India
                                    (IRDAI)</strong> notice,
                                <span class="redspan">Personal Accident (PA) Cover is mandatory</span> if the car is
                                owned by an individual who does not have a
                                <span class="redspan">Personal Accident cover of ₹15 Lakhs</span>. Please opt for
                                'Personal Accident (PA) Cover'.
                            </p>

                            <div class="opt-out">
                                <h3>You can opt out if...</h3>
                                <ul>
                                    <li>The car is registered in a company's name.</li>
                                    <li>You already have a PA cover of ₹15 Lakhs (from another vehicle owned by you or
                                        from a separate standalone PA Cover Policy).</li>
                                    <li>The registered owner does not have a valid driving license.</li>
                                </ul>
                            </div>

                            <h3>What is PA Cover?</h3>
                            <p style="font-size: 13px;">
                                This policy covers the owner for death or disability due to an accident.
                                The owner (in case of disability) or the nominee (in case of death) will receive ₹15
                                Lakhs.
                            </p>

                            <div class="footer">Stay protected with the right insurance coverage.</div>
                        </span>

                    </label>

                </div>
            </div>

            <!-- <div class="col-lg-2 col-md-6 mb-2">
                <div class="addonlist addonlist1">
                    <table style="width: 100%;">
                        <tr>
                            <td><input type="checkbox" name="ZeroDepreciation" id="addonsCheckbox"></td>
                                <td><span>Add Ons</span></td>
                          
                        </tr>
                    </table>
                </div>
            </div> -->

            <div class="col-lg-4 col-md-6  mb-2">
                <div class="option">
                    <table class="" style="width: 100%;">
                        <tr>
                            <td>
                                <div class="mb-2 d-flex">
                                    <label for="idvinsurevalue">IDV :</label>


                                    <input type="text" name="idvinsurevalue" id="currentValue"
                                        class="form-control text-center" value="" maxlength="7"
                                        style="width:100px; border: 2px solid #1a5dab;" readonly>
                                    <button class="update_btn" data-bs-toggle="modal"
                                        data-bs-target="#idvModal">Update</button>
                                    <button class="update_btn" data-bs-toggle="modal"
                                        data-bs-target="#addAddonsModal">Addons</button>

                                    <!-- <a href="#" class="update_btn" data-bs-toggle="modal" data-bs-target="#idvModal">
                                            Update
                                        </a> -->

                                </div>
                            </td>




                        </tr>

                    </table>

                </div>
            </div>



        </div>
    </section>
    <section id="planrow">
        <div class="container-fluid">
            <div class="row filblock">
                <!-- Left Col Start -->
                <div class="col-md-8 col-lg-8 col-xl-9 mb-2">
                    <div class="row" id="addquots">
                        <div class="col-md-12 col-lg-4 mb-4 animated-background" id="loaderquotes">
                            <!-- Plan Start -->


                        </div>
                    </div>
                </div>

                <!-- Left Col End -->
                <div class="col-md-4 col-lg-4 col-xl-3 mb-2">
                    <div class="row">
                        @include('front.motor.bike.common.infobike')
                    </div>
                </div>


            </div>
        </div>
    </section>


    <!-- Add Addons Modal -->

    <div class="modal fade" id="addAddonsModal" tabindex="-1" aria-labelledby="addAddonsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="text-align: left;">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddonsModalLabel">Add Addons</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-2" id="addonsTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="addons-tab" data-bs-toggle="tab"
                                data-bs-target="#detailogs" type="button" role="tab" aria-controls="detailogs"
                                aria-selected="true">Addons</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="accessories-tab" data-bs-toggle="tab"
                                data-bs-target="#summarylog" type="button" role="tab"
                                aria-controls="summarylog" aria-selected="false">Accessories</button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <!-- Addons Tab -->
                        <div class="tab-pane fade show active" id="detailogs" role="tabpanel"
                            aria-labelledby="addons-tab">
                            <p>Select the addons you'd like to add.</p>
                            <div class="row mb-3" style="max-height: 250px; overflow-y: auto;">
                                @foreach ($addons as $key => $type)
                                    <div class="col-lg-4 mb-2 addonlist">
                                        <label class="hover-text d-flex align-items-center">
                                            <input type="checkbox" class="addon-checkbox me-2"
                                                name="{{ $key }}"
                                                id="{{ preg_replace('/[^a-zA-Z0-9_-]/', '', strtolower($type)) }}"
                                                data-addon="{{ $key }}"
                                                @if (in_array((string) $key, $selectedAddons, true)) checked @endif>
                                            <span>{{ $type }}</span>
                                            @php
                                                $tooltips = [
                                                    'ZERO DEPRECIATION' =>
                                                        'Zero depreciation or nil depreciation or bumper-to-bumper cover means that if your car gets damaged following a collision, you will receive the entire cost of the car parts from the insurer.',
                                                    'CONSUMABLE' =>
                                                        'Covers the cost of items like engine oil, gearbox oil, nuts and bolts, washers, grease, and brake oil after an accident.',
                                                    'ROAD SIDE ASSISTANCE' =>
                                                        'Provides immediate help for vehicle breakdowns, including towing, jump-starting batteries, changing flat tires, fuel delivery, and lockout assistance.',
                                                    'ENGINE PROTECTOR' =>
                                                        'Protects your vehicle’s engine from water ingress, lubricant leakage, and internal mechanical failures not covered by standard insurance.',
                                                    'KEY REPLACEMENT' =>
                                                        'Covers the cost of replacing lost or stolen vehicle keys, including the cost of new locks if necessary.',
                                                    'TYRE SECURE' =>
                                                        'Covers the cost of repairing or replacing damaged tyres due to punctures, cuts, bursts or other accidental damage not typically covered by standard insurance.',
                                                ];
                                            @endphp
                                            @if (isset($tooltips[$type]))
                                                <div class="tooltip-container ms-2">
                                                    <i class="fas fa-info-circle tooltip-icon"></i>
                                                    <span class="tooltip-text"
                                                        id="bottom">{{ $tooltips[$type] }}</span>
                                                </div>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="getstarted text-left" id="applybtn">Save
                                changes</button>
                        </div>

                        <!-- Accessories Tab -->
                        <div class="tab-pane fade" id="summarylog" role="tabpanel"
                            aria-labelledby="accessories-tab">
                            <div class="mb-2">
                                <p class="mb-1">Choose Your Additional Accessories</p>
                                <label>
                                    <input type="checkbox" name="accessories" id="accessoriescheckbox">
                                    <span>Additional Accessories</span>
                                </label>
                            </div>

                            <div class="row" id="AccessoriesTypeDiv" style="display: none;">
                                <form action="" id="formaccessories">
                                    <div class="row">
                                        <!-- Electrical -->
                                        <div class="col-lg-4">
                                            <div class="addonlist mb-3">
                                                <label>
                                                    <input type="checkbox" id="electricalcheckbox" name="electrical">
                                                    <span>Electrical</span>
                                                </label>
                                            </div>
                                            <div class="input-container mb-2 mt-2" id="electricaltypeDiv"
                                                style="display: none;">
                                                <input type="text" name="eleaccessamount"
                                                    class="input form-control numeric-input" id="eleaccessamount"
                                                    autocomplete="off" spellcheck="false" maxlength="6">
                                                <span class="placeholder">Enter accessories amount</span>
                                                <span class="error-message" id="eleaccessamountError"></span>
                                            </div>
                                        </div>

                                        <!-- Non-Electrical -->
                                        <div class="col-lg-4">
                                            <div class="addonlist mb-3">
                                                <label>
                                                    <input type="checkbox" id="nonelectricalcheckbox"
                                                        name="nonelectrical">
                                                    <span>Non-Electrical</span>
                                                </label>
                                            </div>
                                            <div class="input-container mb-2 mt-2" id="nonelectricaltypeDiv"
                                                style="display: none;">
                                                <input type="text" name="noneleaccessamount"
                                                    class="input form-control numeric-input" id="noneleaccessamount"
                                                    autocomplete="off" spellcheck="false" maxlength="6">
                                                <span class="placeholder">Enter accessories amount</span>
                                                <span class="error-message" id="noneleaccessamountError"></span>
                                            </div>
                                        </div>

                                        <!-- LPG/CNG -->
                                        <div class="col-lg-4">
                                            <div class="addonlist mb-3">
                                                <label>
                                                    <input type="checkbox" id="lpgcngcheckbox" name="lpg_cng">
                                                    <span>LPG/CNG</span>
                                                </label>
                                            </div>
                                            <div class="row mt-2" id="fueltypeDiv" style="display: none;">
                                                <div class="col-md-6">
                                                    <div class="input-container mb-2">
                                                        <select name="fueltype" class="input form-control"
                                                            id="fueltype">
                                                            <option value="">Select Type</option>
                                                            <option value="cng">CNG</option>
                                                            <option value="lpg">LPG</option>
                                                        </select>
                                                        <span class="placeholder">Fuel Type</span>
                                                        <span class="error-message" id="fueltypeError"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-container mb-2">
                                                        <input type="text" name="fueltypeprice"
                                                            class="input form-control numeric-input"
                                                            id="fueltypeprice" autocomplete="off" spellcheck="false"
                                                            maxlength="16">
                                                        <span class="placeholder">Fuel Type Price</span>
                                                        <span class="error-message" id="fueltypepriceError"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <button type="button" class="getstarted text-left" id="accessoriesbtn">Save
                                changes</button>
                        </div> <!-- End Accessories Tab -->
                    </div> <!-- End Tab Content -->
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="addonsPriceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-3">
            <div class="modal-content rounded-3" style="text-align: left;">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="exampleModalLabel">Addons Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- <ul id="importAddonprice">

                        </ul> -->
                    <div class="row" id="importAddonprice" style="max-height:420px;overflow-y:auto;">

                    </div>

                </div>
                <div class="modal-footer border-0"></div>

            </div>
        </div>



    </div>



    <!-- Modal Structure -->
    <div class="modal fade" id="pacoverModal" tabindex="-1" aria-labelledby="pacoverModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pacoverModalLabel">Personal Accident (PA) Cover</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-left" style="padding: 10px!important;">
                    <button class="btn btn-link p-0" type="button"
                        onclick="toggleAccordion('irdaiNotice', 'irdaiIcon')" style="text-decoration: none;">
                        IRDAI Notice <span id="irdaiIcon">+</span>
                    </button>

                    <!-- IRDAI Notice Content -->
                    <div id="irdaiNotice" style="display: none; margin-top: 10px;">
                        <p style="font-size: 13px;">
                            As per the <strong>Insurance Regulatory and Development Authority of India (IRDAI)</strong>
                            notice,
                            <span class="text-danger">Personal Accident (PA) Cover is mandatory</span> if the car is
                            owned by an individual who does not have a
                            <span class="text-danger">Personal Accident cover of ₹15 Lakhs</span>. Please opt for
                            'Personal Accident (PA) Cover'.
                        </p>
                    </div>

                    <div class="opt-out">
                        <h5>You can opt out if...</h5>
                        <ul class="checkbox-list">
                            <li>
                                <input type="checkbox" name="1"
                                    value="The car is registered in a company’s name." id="pacompanycar" />
                                <label for="pacompanycar">The car is registered in a company’s name.</label>
                            </li>
                            <li>
                                <input type="checkbox" name="2" value="Not have eff. DL With Decl. Letter."
                                    id="pacover" />
                                <label for="pacover">Not have eff. DL With Decl. Letter.</label>
                            </li>
                            <li>
                                <input type="checkbox" name="3" value="Already CPA Policy Exists."
                                    id="pavalidlicense" />
                                <label for="pavalidlicense">Already CPA Policy Exists.</label>
                            </li>
                        </ul>

                    </div>

                    <!-- Accordion Button -->
                    <button class="btn btn-link p-0 mb-2" type="button"
                        onclick="toggleAccordion('paCoverAccordion', 'accordionIcon')" style="text-decoration: none;">
                        What is PA Cover? <span id="accordionIcon">+</span>
                    </button>

                    <!-- Accordion Content -->
                    <div id="paCoverAccordion" style="display: none; margin-top: 10px;">
                        <h5>What is PA Cover?</h5>
                        <p style="font-size: 13px;">
                            This policy covers the owner for death or disability due to an accident.
                            The owner (in case of disability) or the nominee (in case of death) will receive ₹15 Lakhs.
                        </p>
                        <p>Stay protected with the right insurance coverage.</p>
                    </div>
                    <div>
                        <button type="button" class="getstarted text-left" id="changepacover" style="width: 120px;"
                            onclick="validatePaCheckbox()">Save</button>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="idvModal" tabindex="-1" aria-labelledby="idvModalLabel" aria-hidden="true">
        <div class="modal-dialog rounded-3">
            <div class="modal-content rounded-3">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Car insured value (IDV)</h5> <!-- Applied idv-title -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 1rem!important;">
                    <!-- IDV Input -->
                    <div class="idv-input text-center">
                        <label for="idvinsurevalue" class="form-label fw-bold">Your IDV:</label>
                        <input type="text" name="idvinsurevalue" id="currentValue_one"
                            class="form-control text-center" maxlength="7" readonly>
                    </div>

                    <!-- Slider -->
                    <div class="slider-container">
                        <input type="range" class="slider_one" id="customRange1" min="" max=""
                            value="">

                        <!-- Min & Max Range -->
                        <div class="idv-labels">
                            <small>₹<b id="minrangespan"></b></small>
                            <small>₹<b id="maxrangespan"></b></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="update-btn" id="updateIdv"
                        onclick="updateIdv(currentValue_one.value)">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Premium Details -->
    <div id="premiumModal" class="modal fade" tabindex="-1" aria-labelledby="premiumModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="premiumModalLabel">Premium Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" id="premiumBackupContent" style="max-height: 430px; overflow-y: auto;"></div>
                </div>

            </div>
        </div>
    </div>
    @include('front.partial.footer')
    @include('front.partial.jslink')
    <script src="{{ config('constant.BASE_URL') }}front/js/validateFields.js"></script>
</body>
<script>
    window.addEventListener("load", function() {
        document.getElementById("loader").style.display = "none";
    });

    window.onbeforeunload = function() {
        document.getElementById("loader").style.display = "flex";
    };

    window.addEventListener("pageshow", function(event) {
        if (event.persisted) {
            document.getElementById("loader").style.display = "none";
        }
    });

    $(document).ready(function() {
        toggleCheckboxState();
        if ('{{ $under }}' == 'company') {
            $('#pacoverCheckbox').prop('checked', false).prop('disabled', true);
        }

        $('#planType').change(function() {
            toggleCheckboxState();
        });

        $('#pacoverCheckbox').change(function() {
            if (!$(this).prop('disabled') && !$(this).prop('checked')) {
                $('#pacoverModal').modal('show');
            }
        });

        function toggleCheckboxState() {
            if ($('#planType').val() == '1' || '{{ $under }}' == 'individual') {
                $('#pacoverCheckbox').prop('disabled', false);
            } else if ($('#planType').val() == '1' && '{{ $under }}' == 'company') {
                $('#pacoverCheckbox').prop('disabled', true);
            } else if ($('#planType').val() == '2' && '{{ $under }}' == 'company') {
                $('#pacoverCheckbox').prop('disabled', true);
            } else if ($('#planType').val() == '3' && '{{ $under }}' == 'company') {
                $('#pacoverCheckbox').prop('disabled', true);
            } else {
                $('#pacoverCheckbox').prop('disabled', false);
            }
        }
        $(window).click(function(event) {
            if ($(event.target).is('#pacoverModal')) {
                $('#pacoverModal').modal('hide');
            }
        });
    });

    function toggleAccordion(contentId, iconId) {
        const content = document.getElementById(contentId);
        const icon = document.getElementById(iconId);

        if (content.style.display === "none" || content.style.display === "") {
            content.style.display = "block";
            icon.innerText = "−";
        } else {
            content.style.display = "none";
            icon.innerText = "+";
        }
    }

    // function getBikeQuoteStream() {
    //     const source = new EventSource("{{ route('bike.getbikequote') }}");

    //     source.onmessage = function(event) {
    //         if (event.data === "[DONE]") {
    //             source.close();
    //             console.log("Stream finished.");
    //             // $('#loaderquotes').hide();
    //             return;
    //         }

    //         const data = JSON.parse(event.data);
    //         // console.log("Ram", data);
    //         // return false;
    //         let addDetails = data;
    //         console.log(addDetails);

    //         let quotes = `
    //                  <div class="col-md-12 col-lg-4 mb-4">
    //         <form action="${addDetails.quote.route ? addDetails.quote.route : ''}" method="post">
    //             @csrf
    //             <div class="insurance-card">
    //                 <h5>${addDetails.quote.title ? addDetails.quote.title.toUpperCase() : ''} INSURANCE</h5>
    //                 <img src="{{ config('constant.BASE_URL') }}front/images/shriramimage.png" alt="ICICI Lombard" class="logo1">
    //                 <p class="subtitle">Cover value (IDV) <span class="idv">₹${addDetails.quote.idv}</span></p>
    //                 <input type="hidden" value="${addDetails.quote.price}" name="premium">
    //                 <input type="hidden" value="${addDetails.quote.idv}" name="idv">
    //                 <button type="submit" class="price-box">
    //                     <div>
    //                         <p class="buy-now mb-0">BUY NOW</p>
    //                         <p class="price mb-0">₹ ${addDetails.quote.price}</p>
    //                     </div>
    //                 </button>
    //                 <div class="plan-links" style="display:flex; justify-content: space-evenly;">
    //                     <a href="#" class="plan-details" data-bs-toggle="modal" data-bs-target="#featuremodal">Plan Details</a>


    //   <div class="modal fade" id="featuremodal" tabindex="-1" aria-hidden="true">
    //   <div class="modal-dialog rounded-3">
    //     <div class="modal-content rounded-3" style="text-align:left!important;">
    //         <div class="modal-header border-0">
    //             <h5 class="modal-title" id="exampleModalLabel">Plan Features</h5>
    //             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    //         </div>
    //         <div class="modal-body">
    //             <div class="row p-3">
    //                 <div class="col-lg-12 mb-3">
    //                     <p class="featureheading mb-2"><strong>CARE SUPREME PLAN</strong></p>
    //                     <ul>
    //                         <li>
    //                             <p>Premium based on the city of Residence</p>
    //                         </li>
    //                         <li>
    //                             <p>7x cover with NCB Super</p>
    //                         </li>
    //                         <li>
    //                             <p>No reduction in Accumulated NCB/NCB Super due to claim</p>
    //                         </li>
    //                         <li>
    //                             <p>Unlimited Automatic Recharge</p>
    //                         </li>
    //                         <li>
    //                             <p>30% Discount on Renewal on meeting Active Days Criteria</p>
    //                         </li>
    //                         <li>
    //                             <p>New Age benefits</p>
    //                         </li>
    //                         <li>
    //                             <p>Unlimited General Physician e-Consultation</p>
    //                         </li>
    //                         <li>
    //                             <p>Unlimited Access to AI based Fitness Coaching e-Sessions</p>
    //                         </li>
    //                         <li>
    //                             <p>Unlimited Access to Nutritionist/wellness expert e-consultation session</p>
    //                         </li>
    //                     </ul>
    //                 </div>

    //                 <div class="col-lg-12 mb-3">
    //                     <p class="featureheading mb-2"><strong>CARE SUPREME HIGHLIGHTS</strong></p>
    //                     <ul>
    //                         <li>
    //                             <p>60 Days Pre Hospitalization & 180 Days Post Hospitalization</p>
    //                         </li>
    //                         <li>
    //                             <p>Upto 100% increase in SI with NCB</p>
    //                         </li>
    //                         <li>
    //                             <p>Upto 500% Increase in SI with Optional Benefit NCB Super</p>
    //                         </li>
    //                         <li>
    //                             <p>Unlimited Automatic Recharge for related and Unrelated illness</p>
    //                         </li>
    //                         <li>
    //                             <p>No Sub-Limits on Modern Treatments like Robotic Surgery etc.</p>
    //                         </li>
    //                         <li>
    //                             <p>No Sub-limits on AYUSH, Domiciliary Hospitalization, Road Ambulance, and Organ Donor Cover</p>
    //                         </li>
    //                         <li>
    //                             <p>Upto 30% Discount on Renewal Premium under WELLNESS BENEFIT optional Cover</p>
    //                         </li>
    //                     </ul>
    //                 </div>
    //             </div>
    //         </div>
    //         <div class="modal-footer border-0"></div>
    //     </div>
    //     </div>
    //     </div>

    //                     <a href="#" class="plan-details addonprice-details" 
    //                       data-addon='${JSON.stringify(addDetails.quote.addons) || "No addons available"}'>Addons</a>

    //                 </div>
    //                 <div class="details">
    //                     <p class="mb-0"><span>Basic Price</span> <span class="amount">₹ ${addDetails.quote.basicPrice || 2220}</span></p>
    //                     <p class="mb-0"><span>Personal accident cover</span> <span class="amount">₹ ${addDetails.quote.pac || 350}</span></p>
    //                 </div>
    //             </div>
    //         </form>
    //     </div>`;

    //         console.log("Received quote:", data);
    //         $('#addquots').append(quotes);
    //     };

    //     source.onerror = function(error) {
    //         console.error("SSE error:", error);
    //         source.close();
    //     };
    // }


    async function getBikeQuoteStream() {
        //const source = new EventSource("{{ route('bike.getbikequote') }}");
        let vendors = @json($data['vendor']);
        //console.log(vendors);

        for (let i = 0; i < vendors.length; i++) {
            if (vendors[i]['isActive'] == 0) {
                continue;
            }
            const source = await CallAPI("{{ route('bike.getbikequote') }}", vendors[i], "").then((response) => {
                console.log(response);
                if (response.status == '1') {
                    let aData = response.data;
                    aData.logo = vendors[i]['logo'];
                    handleQuoteData(aData);
                }
            });
        }
        $('#loaderquotes').hide();
    };


    async function getCacheBikeQuoteStream() {
        $('#addquots').empty();
        let vendors = @json($data['vendor']);
        for (var i = 0; i < vendors.length; i++) {
            if (vendors[i]['isActive'] == 0) {
                continue;
            }
            const oResponse = await CallAPI("{{ route('bike.getcachebikequote') }}", vendors[i], "").then((
                response) => {
                // console.log(response);
                if (response.status == '1') {
                    const data = response.data;
                    data.logo = vendors[i]['logo'];
                    handleQuoteData(data);
                }
            })
        };
        $('#loaderquotes').hide();
    }


    function handleQuoteData(addDetails) {
        console.log(addDetails);
        const premiumBackup = addDetails.premiumBackup;
        // const idvValues = addDetails.idv;
         const idvValues = addDetails.selectedvalue;
        const minrange = addDetails.minrange;
        const maxrange = addDetails.maxrange;


        $('#currentValue').val(idvValues);
        $('#currentValue_one').val(idvValues);

        document.getElementById('minrangespan').innerText = minrange;
        document.getElementById('maxrangespan').innerText = maxrange;

        let rangeInput = document.getElementById('customRange1');
        let currentValueDisplay = document.getElementById('currentValue_one');

        if (rangeInput && currentValueDisplay) {
            const updateValues = (value) => {
                rangeInput.min = minrange;
                rangeInput.max = maxrange;
                rangeInput.value = value;


                currentValueDisplay.value = Number(value).toLocaleString();
            };


            rangeInput.value = idvValues;
            updateValues(idvValues);

            rangeInput.addEventListener('input', function() {
                updateValues(rangeInput.value); // Update both the slider and currentValue_one
            });

            currentValueDisplay.addEventListener('input', function() {
                let numericValue = currentValueDisplay.value.replace(/,/g, ''); // Remove commas
                if (!isNaN(numericValue) && numericValue !== '') {
                    numericValue = parseInt(numericValue, 10);
                    if (numericValue >= rangeInput.min && numericValue <= rangeInput.max) {
                        rangeInput.value = numericValue;
                        updateValues(numericValue); // Update both slider and input
                    } else {
                        alert(
                            `Value must be between ₹${Number(rangeInput.min).toLocaleString()} and ₹${Number(rangeInput.max).toLocaleString()}`
                        );
                        currentValueDisplay.value = '';
                    }
                } else {
                    currentValueDisplay.value = '';
                }
            });
        }


        addQuote(addDetails);
        // addPremium(premiumBackup);
    }


    function addQuote(addDetails) {
        if ('{{ $under }}' == 'individual') {
            let quoteHTML = `
        <div class="col-md-12 col-lg-4 mb-4">
            <form action="${addDetails.route}" method="post">
                @csrf
                <div class="insurance-card">
                    <h5>${addDetails.title ? addDetails.title.toUpperCase() : ''} INSURANCE</h5>
                    <img src="{{ config('constant.BASE_URL') }}front/logo/${addDetails.logo}" alt="hello Lombard" class="logo1">
                    <p class="subtitle">Cover value (IDV) <span class="idv">₹${addDetails.idv}</span></p>
                    <input type="hidden" value="${addDetails.price}" name="premium">
                    <input type="hidden" value="${addDetails.idv}" name="idv">
                    <button type="submit" class="price-box mb-3">
                        <div>
                            <p class="buy-now mb-0"><b>BUY NOW</b></p>
                            <p class="price mb-0">₹ ${addDetails.price}</p>
                        </div>
                    </button>
                    <div class="plan-links">
                        <a href="#" class="plan-details addonprice-details" 
                          data-addon='${JSON.stringify(addDetails.addons) || "No addons available"}' data-validaddon='${JSON.stringify(addDetails.validaddon) || "No addons available"}'>Addons</a>
                         <a href="#" class="plan-details premium-details" 
                       data-premium='${JSON.stringify(addDetails.premiumBackup) || "No Premium available"}'>Premium Break-up</a>


                    </div>
                  
                </div>
            </form>
        </div>`;

            $('#addquots').append(quoteHTML);
        } else if ('{{ $under }}' == 'company') {

            let quoteHTML = `
        <div class="col-md-12 col-lg-4 mb-4">
            <form action="${addDetails.route}" method="post">
                @csrf
                <div class="insurance-card">
                    <h5>${addDetails.title ? addDetails.title.toUpperCase() : ''} INSURANCE</h5>
                    <img src="{{ config('constant.BASE_URL') }}front/logo/${addDetails.logo}" alt="ICICI Lombard" class="logo1">
                    <p class="subtitle">Cover value (IDV) <span class="idv">₹${addDetails.idv}</span></p>
                    <input type="hidden" value="${addDetails.price}" name="premium">
                    <input type="hidden" value="${addDetails.idv}" name="idv">
                    <button type="submit" class="price-box mb-3">
                        <div>
                            <p class="buy-now mb-0"><b>BUY NOW</b></p>
                            <p class="price mb-0">`;

            // Calculate the new price if the premium backup exists
            if (addDetails.premiumBackup.hasOwnProperty('GR36A-PA FOR OWNER DRIVER')) {
                let newPrice = addDetails.price - addDetails.premiumBackup['GR36A-PA FOR OWNER DRIVER'];
                quoteHTML += `₹ ${newPrice}`;
                console.log("New Price after deduction:", newPrice);
            } else {
                quoteHTML += `₹ ${addDetails.price}`;
            }

            quoteHTML += `
                        </p>
                    </div>
                </button>
                <div class="plan-links">
                    <a href="#" class="plan-details addonprice-details" 
                        data-addon='${JSON.stringify(addDetails.addons) || "No addons available"}' 
                        data-validaddon='${JSON.stringify(addDetails.validaddon) || "No addons available"}'>Addons</a>
                    <a href="#" class="plan-details premium-details" 
                        data-premium='${JSON.stringify(addDetails.premiumBackup) || "No Premium available"}'>Premium Break-up</a>
                </div>
            </div>
        </form>
    </div>`;

            // Append the generated HTML to the DOM
            $('#addquots').append(quoteHTML);
        }
        // console.log("full Details", addDetails);


        // Addon Details Click Handler
        $(document).on('click', '.addonprice-details', function(e) {
            e.preventDefault();

            let addonData = $(this).data('addon');
            let validaddonData = $(this).data('validaddon');
            let addonsFullName = @json($addons); // Pass $addons from Blade
            let finalAddon = {};

            for (const [key, value] of Object.entries(addonData)) {
                if (validaddonData.hasOwnProperty(key)) {
                    if (validaddonData[key] === 'n') {
                        finalAddon[key] = 'n';
                    } else {
                        finalAddon[key] = value;
                    }
                } else {
                    finalAddon[key] = value;
                }
            }

            // console.log("finalAddon:", finalAddon);

            let finalAddonHTML = '';
            for (const [key, value] of Object.entries(finalAddon)) {
                let addonName = addonsFullName[key] || `Addon ${key}`;
                finalAddonHTML += `
                <div class="col-md-6">
                <div class="card_info">
                    <span>${addonName}: </span>
                    <span class="highlight">${value === 'n' ? '<i class="fa-solid fa-ban"></i>' : '₹' + value}</span>
                </div>
                 </div>`;
            }

            $('#importAddonprice').html(finalAddonHTML);
            $('#addonsPriceModal').modal('show');
        });

        // Premium Details Click Handler
        $(document).on('click', '.premium-details', function(e) {
            e.preventDefault();
            let premiumBackup = $(this).data('premium');
            addPremium(premiumBackup);
            $('#premiumModal').modal('show');
        });
    }

    function addPremium(premiumBackup) {
        // console.log("Premium Backup Data:", premiumBackup);

        if (premiumBackup && typeof premiumBackup === 'object' && Object.keys(premiumBackup).length > 0) {
            let premiumBackupHTML = '';

            for (const [key, value] of Object.entries(premiumBackup)) {
                premiumBackupHTML += `
                  <div class="col-md-6">
                          <div div class="card_info">
                    <span>${key}</span>
                        <span class="highlight">₹${value}</span>
                </div>
                        </div>
                
            `;
            }

            $('#premiumBackupContent').html(premiumBackupHTML);

        } else {
            $('#premiumBackupContent').html('<p>No premium backup data available.</p>');
        }
    }


    getBikeQuoteStream();

    //   Open Addons Modal Fuction Start 

    document.addEventListener('DOMContentLoaded', function() {
        const accessoriesCheckbox = document.querySelector('#accessoriescheckbox');
        const electricalCheckbox = document.querySelector('#electricalcheckbox');
        const nonelectricalCheckbox = document.querySelector('#nonelectricalcheckbox');
        const lpgcngCheckbox = document.querySelector('#lpgcngcheckbox');

        const AccessoriesTypeDiv = document.getElementById('AccessoriesTypeDiv');
        const electricaltypeDiv = document.getElementById('electricaltypeDiv');
        const nonelectricaltypeDiv = document.getElementById('nonelectricaltypeDiv');
        const fueltypeDiv = document.getElementById('fueltypeDiv');

        // Toggle visibility for each checkbox
        accessoriesCheckbox.addEventListener('change', function() {
            AccessoriesTypeDiv.style.display = accessoriesCheckbox.checked ? 'flex' : 'none';
        });

        electricalCheckbox.addEventListener('change', function() {
            electricaltypeDiv.style.display = electricalCheckbox.checked ? 'flex' : 'none';
        });

        nonelectricalCheckbox.addEventListener('change', function() {
            nonelectricaltypeDiv.style.display = nonelectricalCheckbox.checked ? 'flex' : 'none';
        });

        lpgcngCheckbox.addEventListener('change', function() {
            fueltypeDiv.style.display = lpgcngCheckbox.checked ? 'flex' : 'none';
        });
    });
    //   Open Addons Modal Fuction End 



    function showError(message, focusSelector) {
        errorTitleElement.innerText = message;
        document.querySelector(focusSelector).focus();
        errorBox.style.display = "flex";
        setTimeout(() => {
            errorBox.style.display = 'none';
        }, 3000);
    }


    function showVerified(message) {
        if (verifiedBox && verifiedTitle) {
            verifiedBox.style.display = "flex";
            verifiedTitle.innerText = message;
            setTimeout(() => {
                verifiedBox.style.display = 'none';
            }, 3000);
        }
    }

    // Error And Success Message function end



    //   addons add  Fuction start



    const addAddonsRoute = window.addAddonsRoute;
    let selectedAddOns = @json($selectedAddons);
    let isAddOnsModified = false;
    let applyClicked = false;

    // console.log("Initial selected add-ons:", selectedAddOns);

    document.querySelectorAll('.addon-checkbox').forEach((checkbox) => {
        const addon = checkbox.getAttribute('data-addon');

        checkbox.checked = selectedAddOns.includes(addon);
        checkbox.addEventListener('change', function() {
            updateSelectedAddons(this);
            applyClicked = false;
        });
    });

    document.querySelectorAll('#applybtn').forEach((button) => {
        button.addEventListener('click', function() {
            handleAddButtonClick();
        });
    });

    document.querySelectorAll('#accessoriesbtn').forEach((button) => {
        button.addEventListener('click', function() {
            const form = document.getElementById('formaccessories');
            if (!form) return alert("Form not found!");

            let additionalacces = document.getElementById('accessoriescheckbox');
            let electricalcheckbox = document.getElementById('electricalcheckbox');
            let nonelectricalcheckbox = document.getElementById('nonelectricalcheckbox');
            let lpgcngcheckbox = document.getElementById('lpgcngcheckbox');
            let electricalinput = document.getElementById('eleaccessamount');
            let nonelectricalinput = document.getElementById('noneleaccessamount');
            let lpgcnginput = document.getElementById('fueltypeprice');
            let lpgcngselect = document.getElementById('fueltype');

            // Final data array to store selected accessories
            let accessoriesData = [];

            // Validate selections
            if (additionalacces.checked) {
                if (
                    !electricalcheckbox.checked &&
                    !nonelectricalcheckbox.checked &&
                    !lpgcngcheckbox.checked
                ) {
                    showError(`Please select at least one Additional Accessory`,
                        '#accessoriescheckbox');
                    return false;
                }

                if (electricalcheckbox.checked) {
                    if (electricalinput.value.trim() === "") {
                        showError(`Please enter Electrical Accessories Amount`, '#eleaccessamount');
                        return false;
                    }
                    accessoriesData.push({
                        type: 'electrical',
                        amount: electricalinput.value.trim()
                    });
                }

                if (nonelectricalcheckbox.checked) {
                    if (nonelectricalinput.value.trim() === "") {
                        showError(`Please enter Non Electrical Accessories Amount`,
                            '#noneleaccessamount');
                        return false;
                    }
                    accessoriesData.push({
                        type: 'non-electrical',
                        amount: nonelectricalinput.value.trim()
                    });
                }

                if (lpgcngcheckbox.checked) {
                    if (lpgcnginput.value.trim() === "" || lpgcngselect.value === "") {
                        showError(`Please enter LPG/CNG Accessories Amount and select a fuel type.`,
                            '#fueltypeprice');
                        return false;
                    }
                    accessoriesData.push({
                        type: lpgcngselect.value,
                        amount: lpgcnginput.value.trim()
                    });
                }
            }

            // Now pass structured data
            AddAccessories(accessoriesData);
        });
    });

    function handleAddButtonClick() {
        console.log("Final selected add-ons before API call:", selectedAddOns);

        let aResponse = CallAPI("{{ route('bike.addaddon') }}", selectedAddOns, "").then(response => {
            var status = response.status;
            console.log(status);
            if (status == 1) {
                $('#addAddonsModal').modal('hide');
                getCacheBikeQuoteStream();
            }
            console.log("API response result:", response);
        }).catch(error => {
            console.error("API error:", error);
        });
    }

    function updateSelectedAddons(checkbox) {
        const addon = checkbox.getAttribute('data-addon');

        if (checkbox.checked) {
            if (!selectedAddOns.includes(addon)) {
                selectedAddOns.push(addon);
                isAddOnsModified = true;
            }
        } else {
            const index = selectedAddOns.indexOf(addon);
            if (index !== -1) {
                selectedAddOns.splice(index, 1);
                isAddOnsModified = true;
            }
        }
        window.globalSelectedAddOns = [...selectedAddOns];
        // console.log("Updated selected add-ons:", selectedAddOns);
    }

    //  addons add Fuction End



    //  change Plan Type Fuction start
    let selectedValues = {};
    const updateArray = () => {
        const pacoverCheckbox = document.getElementById("pacoverCheckbox");
        const planTypeSelect = document.getElementById("planType");

        selectedValues = {
            pacover: pacoverCheckbox.checked ? "1" : "0",
            planetype: planTypeSelect.value || "none"
        };
    };

    const changePlanType = async () => {
        updateArray();

        let aPlaneResponse = "";
        try {
            aPlaneResponse = await CallAPI("{{ route('bike.changeplantype') }}", selectedValues, "");
        } catch (error) {
            console.error("API error:", error);
            return;
        }

        if (aPlaneResponse) {
            const status = aPlaneResponse.status;
            console.log("API response status:", status);
            if (status == 1) {
                console.log("Final result:", aPlaneResponse);
                getCacheBikeQuoteStream();
            } else {
                console.log("API response result: status 0");
            }
        }
    };


    let pacoverConfirmed = false;

    function validatePaCheckbox() {
        const checkboxes = document.querySelectorAll('.opt-out input[type="checkbox"]');
        let isChecked = false;
        let selectedOptions = [];

        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                isChecked = true;
                selectedOptions.push({
                    [checkbox.name]: checkbox.value
                });
            }
        });

        if (!isChecked) {
            showError(`Please select at least one option to proceed.`, 'input[type="checkbox');
            return false;
        }

        const pacoverCheckbox = document.getElementById("pacoverCheckbox");
        selectedOptions.push({
            pacover: pacoverCheckbox.checked ? "1" : "0"
        });

        console.log("Selected Options: ", selectedOptions);
        // return false;

        const changePacover = async () => {
            let aPlaneResponse = "";
            try {
                aPlaneResponse = await CallAPI("{{ route('bike.pacoverreason') }}", selectedOptions, "");
            } catch (error) {
                console.error("API error:", error);
            }

            if (aPlaneResponse) {
                const status = aPlaneResponse.status;
                console.log("API response status:", status);
                if (status == 1) {
                    $('#pacoverModal').modal('hide');
                    pacoverConfirmed = true;
                    getCacheCarQuoteStream();

                } else {
                    console.log("API response result: status 0");
                    showError(`API response result: status 0`, '');
                }
            } else {
                pacoverCheckbox.checked = true;
            }
        };


        changePacover();
    }
    $('#pacoverModal').on('hidden.bs.modal', function() {
        const pacoverCheckbox = document.getElementById("pacoverCheckbox");

        // If user closed modal without confirming
        if (!pacoverConfirmed) {
            pacoverCheckbox.checked = true;
        }

        // Reset for next time
        pacoverConfirmed = false;
    });

    document.addEventListener("DOMContentLoaded", () => {
        const pacoverCheckbox = document.getElementById("pacoverCheckbox");
        const planTypeSelect = document.getElementById("planType");

        updateArray();

        pacoverCheckbox.addEventListener("change", changePlanType);
        planTypeSelect.addEventListener("change", changePlanType);

    });


    //  change Plan Type Fuction End

    // updateIdv function start 
    function updateIdv(value) {
        var idvValue = value;
        // console.log("Final Update IdvValue", idvValue);


        let aResponse = CallAPI("{{ route('bike.updateidv') }}", idvValue, "").then(response => {
            var status = response.status;
            console.log(status);
            if (status == 1) {
                window.location.reload();
            }
            // console.log("API response result:", response);
        }).catch(error => {
            console.error("API error:", error);
        });
        // $.get('{{ route('car.updateidv') }}', function(response) {
        //     console.log(response);
        // });

    }
    // updateIdv function end 
    $('.checkbox-list input[type="checkbox"]').on('change', function() {
        $('.checkbox-list input[type="checkbox"]').not(this).prop('checked', false);
    });


    document.addEventListener('DOMContentLoaded', function() {
        const allInputs = document.querySelectorAll('.numeric-input');
        allInputs.forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
            });
        });
    });
</script>

</html>
