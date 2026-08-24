@php
use App\Models\JourneyUsers;
use Illuminate\Support\Facades\Request;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Top Plans</title>
    @include('front.partial.csslink')


</head>
<style>
    .quespera,
    .medicalhead p,
    .questionfont {
        color: #000;
    }

    #userprofile {
        width: 40px;
        height: 40px;
        padding: 10px;
        background: antiquewhite;
        border-radius: 50%;
        font-size: 16px !important;
        margin-right: 10px;
    }

    #premimumchange .modal-dialog {
        max-width: 550px;
        margin: 3rem auto;
    }

    .Productsimg {
        width: 240px;
        height: 150px;
    }

    .tbl {
        margin-bottom: 10px;
    }

    .tbl p {
        margin-bottom: 0;
    }

    .colbggrey {
        background: #f9f9f9;
    }

    .tbl td,
    .tbl th {
        border: 1px solid #ccc;
        font-size: 13px;
        padding: 5px 10px;
        background: #f9f9f9;
    }

    .tbl table.dataTable {
        border-collapse: collapse !important;
    }

    .tbl .table-bordered {
        border: 1px solid #dee2e6;
    }

    .tbl .table-bordered td,
    .tbl .table-bordered th {
        border: 0.5px solid #eeeeee;
    }

    .tbl #tbwidd td:nth-child(3) {
        width: 150px;
    }

    .tbl #tablenotify td {
        background: #fff;
    }

    .tbl #tablenotify .myradio__label {
        font-size: 14px;
        padding: 6px 10px 4px 22px;
        cursor: pointer;
    }

    .tbl .dataTables_length,
    .tbl .dataTables_info,
    .tbl .dataTables_filter {
        display: none;
    }

    .tbl table.dataTable thead .sorting:after,
    .tbl table.dataTable thead .sorting_desc:after,
    .tbl table.dataTable thead .sorting_asc:after {
        opacity: 0.8;
        content: "\21C5";
        display: none;
    }

    .tbl div.dataTables_wrapper div.dataTables_paginate {
        cursor: pointer;
        margin-bottom: 8px;
        margin-top: 20px;
    }

    .tbl .dataTables_paginate .paginate_button {
        margin-bottom: 5px;
        color: #000000;
        border: 1px solid #ddd;
        padding: 4px 10px;
        text-decoration: none;
        margin: 0 4px;
        font-size: 15px;
    }

    .tbl .dataTables_paginate .current {
        background-color: #0D6EFD;
        border: 1px solid #0D6EFD;
        color: #FFFFFF;
    }

    .tbl th {
        background: #fff;
        padding: 6px 10px;
        color: #000;
        font-weight: 500;
        font-size: 14px;
        text-transform: capitalize;
    }

    .pointer {
        cursor: pointer;
    }

    #sameAddress {
        margin-bottom: 4px;
        margin-bottom: .5rem;
    }

    #addAddonsHeaddiv {
        display: none;
    }

    #hideAddonsHeaddiv {
        display: block;
    }

    .editAddons {
        display: none;
    }

    #addAddons .modal-confirm .modal-content {
        text-align: left;
    }

    .fs-500 {
        font-weight: 500;
    }

    .rupee {
        margin-right: 3px;
        margin-left: 2px;
        font-size: 12px;
    }

    #coveredmember,
    .gotoproposal {
        display: none
    }

    .remove {
        cursor: pointer;
        color: #0F9AE9;
    }

    .fa-circle-plus {
        cursor: pointer;
    }


    .fa-check,
    .fa-spinner {
        margin-left: 10px !important;
    }

    .infos {
        width: 100%;
        height: 38px;
        border-radius: 7px !important;
        border: 1px solid #ced4da;
    }

    .infos:focus-visible {
        border: none !important;
    }

    .placeholder,
    .placeholder1 {
        opacity: 1 !important;
        cursor: pointer !important;
    }

    /*
    .btn:hover {
        color: #6B6A75 !important;
        background-color: #e9ecef !important;
    } */

    .btn-check:checked+.btn,
    .btn.active,
    .btn.show,
    .btn:first-child:active,
    :not(.btn-check)+.btn:active {
        border-color: #fff !important;
    }

    select {
        -webkit-appearance: auto;
        -moz-appearance: auto;
        appearance: auto !important;
    }

    .btn {
        color: #6B6A75 !important;
    }

 
    .error-message {
        color: red;
        font-size: 0.8em;
        /* float: right; */
        margin-bottom: 0.8em !important;
    }

    .input {
        padding: 10px !important;
    }

    .btn_one {
        align-items: center;
        cursor: pointer;
        border: 1.3px solid rgba(0, 0, 0, .4);
        border-radius: 5px;
        padding: 10px;
        width: 100%;
        color: #000;
        letter-spacing: 1px;
        transition: all 0.3s ease-in-out;
        justify-content: space-between;
    }

    .btn_one select[name="children"] {
        width: 160px !important;
    }

    .btn_one label {
        cursor: pointer;
        margin-left: 8px;
        font-size: 14px;
    }

    select {
        background-color: #f0fafc;
    }

    .disabled {
        pointer-events: none;
        opacity: 0.6;
    }

    .col-lg-6 .Agebox {
        width: 110px !important;
        margin-left: 10px;
    }

    .Agebox {
        height: 50px;
        max-height: 50px;
        padding: 5px;
    }

    .Agebox option {
        height: 50px;
        max-height: 50px;
        overflow-y: scroll;
    }

    select {
        height: 40px;
        max-height: 40px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .slide2-img {
            text-align: center;
            margin-bottom: 20px;
        }
    }

    @media (max-width: 320px) {
        .d-flex {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn_one {
            margin-left: 0 !important;
        }

        .btn_one.ml-2.parent-div {
            width: 100%;
        }
    }

    /* Extra Small Devices (phones, 0px and up) */
    @media (min-width: 0px) and (max-width: 575.98px) {
        .input-container {
            width: 100%;
        }

        .MainErrorBox {
            right: 15px !important;
            top: 40% !important;
            z-index: 1 !important;
            width: 370px !important;
        }

        #formStepThree tbody .input-container1 {
            width: 200px !important;
        }

    }


    /* import file css start  */
    .fileUploadInput {
        position: relative;
    }

    .fileUploadInput .fileNameDisplay {
        font-family: 'Poppins', sans-serif;
        color: rgba(0, 0, 0, 0.65);
        padding: 7px;
        cursor: pointer;
        width: 100%;
    }

    .fileUploadInput .fileInput {
        display: none;
    }

    .fileUploadInput button {
        height: 40px;
        width: 50px;
        line-height: 0;
        color: white;
        background-color: #323262;
        border: none;
        border-radius: 0 3px 3px 0;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        position: absolute;
    }

    .importfileStore {
        position: absolute;
        top: 0%;
        right: 0%;
        padding: .435rem .75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        text-align: center;
        white-space: nowrap;
        background-color: #e9ecef;
        border-top-right-radius: 7px !important;
        border-bottom-right-radius: 7px !important;
    }

    .importfileStore .fa-image {
        color: #979797 !important;
    }

    .MainErrorBox,
    .MainverifiyedBox {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        position: fixed;
        right: 30px;
        top: 1%;
        width: auto;
        padding: 12px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: start;
        background: #EF665B;
        border-radius: 8px;
        box-shadow: 0px 0px 5px -3px #111;
        z-index: 100000;
    }

    .MainverifiyedBox {
        background: #4EB14F;
    }

    .error__icon,
    .verifiy__icon {
        width: 20px;
        height: 20px;
        transform: translateY(-2px);
        margin-right: 8px;
        color: #fff;
    }


    .error__title,
    .verifiyed__title {
        font-weight: 500;
        font-size: 14px;
        color: #fff;
    }

    .error__close,
    .verifiyed__close {
        width: 20px;
        height: 20px;
        cursor: pointer;
        margin-left: auto;
        color: #fff;
    }

    .fa-circle-exclamation,
    .fa-circle-check {
        color: #fff;
    }


    #city-list {
        position: absolute;
        top: 40px;
        font-family: "Poppins", sans-serif;
        width: 375px;
        max-height: 90px;
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

    #enterOtp .modal-confirm {
        width: 350px;
        max-width: 350px;
        margin: 8rem auto !important;
    }

    #addAddons .modal-confirm {
        width: 850px;
        max-width: 850px;
        margin: 1rem auto !important;
    }

    /* #editOtp {
        position: absolute;
        right: 7px;
        top: 5px;
    } */
    .datepicker {
        padding: 4px;
        -webkit-border-radius: 8px !important;
        -moz-border-radius: 8px !important;
        border-radius: 8px !important;
        direction: ltr
    }

    .form-control {
        color: #212529 !important;
        border-radius: 7px !important;
    }

    .form-label,
    .step-circle {
        color: #212529 !important;
    }

    .error {
        border: 1px solid red!important;
    }



    #errorModal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.6);
 }

 #errorModal .modal-content {
    background: white;
    margin: 15% auto;
    padding: 20px;
    width: 90%;
    max-width: 400px;
    border-radius: 8px;
    text-align: center;
    position: relative;
 }

 #errorModal .close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    cursor: pointer;
    font-size: 18px;
 }
</style>


<body class="planlistbg">
    @php
    use Illuminate\Support\Facades\Auth;
    use App\Models\Insure;
    $addon = Auth::user()->addon ? json_decode(Auth::user()->addon, true) : [];
    //dd($addon);
    $checkedItems = $peddata == null ? [] : $peddata;
    $data = Insure::where('proposalid', Auth::id())->get();
    $nom = $data->count();
    $userdata = Auth::user();
    // dd($addOn_Value);
    // echo $kyctype;
    // echo $skyc;
    // dd($pincode);
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
    <section id="planrow">
        <div class="container-fluid">
            <div class="row filblock">
                {{-- <button type="button" class=" mt-1" id="" data-toggle="modal"
                    data-target="#premimumchange">premimumchange</button> --}}
                {{-- <div class="col-md-12 col-lg-12 p-0 mb-2">

                    <a href="#" data-bs-toggle="modal" data-bs-target="#premimumchange">premimumchange</a>
                </div> --}}

                <!-- Left Col Start -->
                <div class="col-md-12 col-lg-12 p-0 mb-2">
                    <a href="{{ route('addon') }}" id="backPage" class="smlink" style="display: block" ;><span><i
                                class="bi bi-arrow-left"></i></span> Go back to Previous</a>

                    <a href="#" class="smlink prev-step" id="backSlide" style="display: none" ;><span><i
                                class="bi bi-arrow-left"></i></span> Go
                        back to Previous</a>
                    <!-- <button type="button" class="Previous prev-step">Previous</button> -->
                    <div class="MainErrorBox" style="float: right;display:none;"><span class="error__icon"><i
                                class="fa-solid fa-circle-exclamation"></i></span>
                        <p class="error__title  mb-0" style="margin-right:10px;">hello</p> <span class="error__close"><i
                                class="fa-solid fa-xmark"></i></span>
                    </div>
                    <div class="MainverifiyedBox" style="float: right;display:none;"><span class="verifiy__icon"><i
                                class="fa-solid fa-circle-check"></i></span>
                        <p class="verifiyed__title  mb-0" style="margin-right:10px;">hello</p> <span
                            class="verifiyed__close"><i class="fa-solid fa-xmark"></i></span>
                    </div>
                </div>
                <div class="col-md-8 col-lg-8 col-xl-8">

                    <div class="row">

                        <div class="col-md-12 col-lg-12 col-sm-12 p-0">
                            <div id="container" class="container">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="progress" style="height: 3px;">
                                            <div class="progress-bar" role="progressbar" style="width: 0%;"
                                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="step-container">
                                            <div class="step-circle">1</div>
                                            <div class="step-line"></div>
                                            <div class="step-circle">2</div>
                                            <div class="step-line"></div>
                                            <div class="step-circle">3</div>
                                            <div class="step-line"></div>
                                            <div class="step-circle">4</div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="multi-step-form">
                                            <div class="step step-1 active">
                                                <form id="formStepOne">
                                                    <div class="row mb-3">
                                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                                            <h4>Great! Let’s start with proposer details</h4>
                                                            <h6>Great! Let’s start with proposer details</h6>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-12 col-lg-12 col-sm-12 mb-1">
                                                            <label for="field1" class="form-label"> Select
                                                                Proposer</label>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="input-container mb-2">
                                                                <select id="proposar" name="proposar"
                                                                    class="input form-control"
                                                                    oninput="clearErrorOne('proposarError')">

                                                                    @if (isset($proposar) && $proposar == 'self')
                                                                    <option value="self" selected>SELF</option>
                                                                    @endif

                                                                    @foreach ($proposarList as $proposarItem)
                                                                    @if (!in_array($proposarItem, ['Son', 'Daughter']))
                                                                    <option value="{{ $proposarItem }}">
                                                                        {{ strtoupper($proposarItem) }}
                                                                    </option>
                                                                    @endif
                                                                    @endforeach
                                                                </select>
                                                                <div id="proposarError"
                                                                    style="color:red; display:none;"></div>
                                                                <div class="placeholder active">Select Proposer</div>
                                                            </div>

                                                            <span class="error-message" id="mr_ms_genderError"></span>
                                                        </div>
                                                        <div class="col-lg-6"></div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                                            <label for="field1" class="form-label">Proposer
                                                                KYC</label>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                                            <div class="btn_one ml-2 parent-div">
                                                                <input type="checkbox" id="panCard"
                                                                    name="customerkyc" value="customerkyc"
                                                                    onchange="handleCheckboxChange('panCard')">
                                                                <label for="selfbox1" style="margin-left: 3px;">PAN
                                                                    Card</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                                            <div class="btn_one ml-2 parent-div">
                                                                <input type="checkbox" id="aadhaarCard"
                                                                    name="customerkyc" value="self"
                                                                    onchange="handleCheckboxChange('aadhaarCard')">
                                                                <label for="selfbox2"
                                                                    style="margin-left: 3px;">Aadhaar
                                                                    ( Last 4 digits)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                                            <div class="btn_one ml-2 parent-div">
                                                                <input type="checkbox" id="otherCard"
                                                                    name="customerkyc" value="self"
                                                                    onchange="handleCheckboxChange('otherCard')">
                                                                <label for="selfbox3"
                                                                    style="margin-left: 3px;">Others</label>
                                                            </div>
                                                        </div>
                                                        <div id="pancardDetails" class="col-md-12 col-lg-12 col-sm-12"
                                                            style="display:none;">
                                                            <label for="field1" class="form-label">Please Provide
                                                                Pan
                                                                Card Info</label>
                                                            <div id="panForm">
                                                                <div class="row">
                                                                    <div class="col-lg-4 mb-3">
                                                                        <div class="input-container mb-2">
                                                                            <input type="text"
                                                                                name="customerpancardno"
                                                                                class="input form-control"
                                                                                id="customerpancardno"
                                                                                autocomplete="off" spellcheck="false"
                                                                                maxlength="10"
                                                                                oninput="clearErrorOne('customerpancardnoError')"
                                                                                style="text-transform: uppercase;">
                                                                            <div class="placeholder">PAN NO.</div>
                                                                            <span class="error-message"
                                                                                id="customerpancardnoError"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4 mb-3">
                                                                        <div class="input-container mb-2 ">
                                                                            <div class="input-group1 datepickerdiv">
                                                                                <input type="text"
                                                                                    name="customerpancardDob"
                                                                                    class="input form-control datepicker"
                                                                                    id="customerpancardDob"
                                                                                    autocomplete="off"
                                                                                    spellcheck="false" maxlength="10"
                                                                                    oninput="clearErrorOne('customerpancardDobError')"
                                                                                    onclick="clearErrorOne('customerpancardDobError')">
                                                                                <div class="placeholder">DATE OF BIRTH
                                                                                </div>
                                                                                <button class="btn calendarButton"
                                                                                    type="button">
                                                                                    <i
                                                                                        class="fa-solid fa-calendar-days"></i>
                                                                                </button>
                                                                            </div>
                                                                            <span class="error-message"
                                                                                id="customerpancardDobError"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4 mt-1">
                                                                        <button class="btnsm" id="verifyPanBtn"
                                                                            onclick="verifyPan(event)">VERIFY </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="aadharcardDetails"
                                                            class="col-md-12 col-lg-12 col-sm-12"
                                                            style="display:none;">
                                                            <label for="field1" class="form-label">Please Provide
                                                                Aadhar Card Info</label>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="row">
                                                                        <div class="col-lg-3 mb-3">
                                                                            <div class="input-container mb-2">
                                                                                <select id="customerAadharGender"
                                                                                    name="customerAadharGender"
                                                                                    class="input form-control"
                                                                                    oninput="clearErrorOne('customerAadharGenderError')">
                                                                                    <option value="">Select
                                                                                    </option>
                                                                                    <option value="Mr">Mr</option>
                                                                                    <option value="Ms">Ms</option>
                                                                                </select>
                                                                                <div class="placeholder">Mr/Ms</div>
                                                                                <span class="error-message"
                                                                                    id="customerAadharGenderError"></span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-9 mb-3">
                                                                            <div class="input-container mb-2">
                                                                                <input type="text"
                                                                                    name="customerAadharno"
                                                                                    class="input form-control"
                                                                                    id="customerAadharno"
                                                                                    autocomplete="off"
                                                                                    spellcheck="false" maxlength="4"
                                                                                    oninput="handleSubmit('customerAadharnoError',this,4)" />
                                                                                <div class="placeholder">AADHAR NO.
                                                                                    (LAST 4 DIGIT)</div>
                                                                                <span class="error-message"
                                                                                    id="customerAadharnoError"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 mb-3">
                                                                    <div class="input-container mb-2">
                                                                        <input type="text"
                                                                            name="customerAadharName"
                                                                            class="input form-control"
                                                                            id="customerAadharName" autocomplete="off"
                                                                            spellcheck="false"
                                                                            oninput="clearErrorOne('customerAadharNameError')" />
                                                                        <div class="placeholder">FULL NAME AS PER
                                                                            AADHAR</div>
                                                                        <span class="error-message"
                                                                            id="customerAadharNameError"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 mb-3">
                                                                    <div class="input-container mb-2">
                                                                        <div class="input-group1 datepickerdiv">
                                                                            <input type="text"
                                                                                name="customerAadharDob"
                                                                                class="input form-control datepicker"
                                                                                id="customerAadharDob"
                                                                                autocomplete="off" spellcheck="false"
                                                                                maxlength="10"
                                                                                oninput="clearErrorOne('customerAadharDobError')" />
                                                                            <div class="placeholder">D.O.B (DD-MM-YYYY)
                                                                            </div>
                                                                            <button class="btn calendarButton"
                                                                                type="button">
                                                                                <i
                                                                                    class="fa-solid fa-calendar-days"></i>
                                                                            </button>
                                                                        </div>
                                                                        <span class="error-message"
                                                                            id="customerAadharDobError"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3 mb-3 mt-1">
                                                                    <button class="btnsm" id="verifyAadhaarBtn"
                                                                        onclick="verifyAdhar(event)">VERIFY<span><i
                                                                                class="fa fa-spinner fa-spin"
                                                                                style="display: none"></i></span></button>
                                                                </div>
                                                                <div class="col-lg-3 mb-3"></div>
                                                            </div>
                                                        </div>
                                                        <div id="othercardDetails"
                                                            class="col-md-12 col-lg-12   col-sm-12"
                                                            style="display:none;">
                                                            <label for="field1" class="form-label">Please Provide
                                                                Other Card Info</label>
                                                            <div class="row">
                                                                <div class="col-lg-6 mb-2">
                                                                    <p>IDENTITY PROOF TYPE</p>
                                                                    <div class="input-container mb-2">
                                                                        <select name="identityTypeProof"
                                                                            id="identityTypeProof"
                                                                            class="input form-control">
                                                                            <option value="">Select Type</option>
                                                                            <option value="aadhar">AADHAR</option>
                                                                            <option value="pan">PAN</option>
                                                                            <option value="passport">PASSPORT</option>
                                                                            <option value="DrivingLicense">Driving
                                                                                License</option>
                                                                            <option value="VoterID">Voter ID Card
                                                                            </option>
                                                                            <option value="Form60">Form 60</option>
                                                                        </select>
                                                                        <div class="placeholder">Select Type</div>
                                                                        <span id="identityTypeProofError"
                                                                            class="error-message"></span>
                                                                    </div>

                                                                </div>
                                                                <div class="col-lg-6 mb-2">
                                                                    <p>ADDRESS PROOF TYPE</p>
                                                                    <div class="input-container mb-2">
                                                                        <select name="addressTypeProof"
                                                                            id="addressTypeProof"
                                                                            class="input form-control">
                                                                            <option value="">Select Type</option>
                                                                            <option value="aadhar">AADHAR</option>

                                                                            <option value="passport">PASSPORT</option>
                                                                            <option value="DrivingLicense">Driving
                                                                                License</option>
                                                                            <option value="VoterID">Voter ID Card
                                                                            </option>
                                                                            <option value="Form60">Form 60</option>
                                                                        </select>
                                                                        <div class="placeholder">Select Type</div>
                                                                    </div>
                                                                    <span id="identityTypeProofError"
                                                                        class="error-message"></span>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <div class="identityTypeAadhar row"
                                                                                style="display:none;">
                                                                                <div class="divOne col-lg-12 mb-2">
                                                                                    <div
                                                                                        class="fileUploadInput input-container mb-2">
                                                                                        <label for="identity_aadhar"
                                                                                            class="fileNameDisplay">Please
                                                                                            upload Identity Aadhaar
                                                                                            Proof</label>
                                                                                        <input type="file"
                                                                                            name="identityfront"
                                                                                            id="identity_aadhar"
                                                                                            class="fileInput"
                                                                                            onchange="updateFileName('identity_aadhar')" />
                                                                                        <span
                                                                                            class="importfileStore"><i
                                                                                                class="fa-solid fa-image"></i></span>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="divtwo col-lg-12 mb-2">
                                                                                    {{-- <div
                                                                                class="fileUploadInput input-container mb-2">
                                                                                <label for="identity_aadharBack"
                                                                                    class="fileNameDisplay">Please
                                                                                    upload Identity Aadhaar (Back)
                                                                                    Proof</label>
                                                                                <input type="file"
                                                                                    name="identityback"
                                                                                    id="identity_aadharBack"
                                                                                    class="fileInput"
                                                                                    onchange="updateFileName('identity_aadharBack')" />
                                                                                <span class="importfileStore"><i
                                                                                        class="fa-solid fa-image"></i></span>
                                                                            </div> --}}
                                                                                </div>

                                                                            </div>
                                                                            <div class="identityTypePan row"
                                                                                style="display:none;">

                                                                                <div class="PandivOne col-lg-12 mb-2">
                                                                                    <div
                                                                                        class="fileUploadInput input-container mb-2">
                                                                                        <label for="identity_pancard"
                                                                                            class="fileNameDisplay">Please
                                                                                            upload Identity pan</label>
                                                                                        <input type="file"
                                                                                            name="identityfront"
                                                                                            id="identity_pancard"
                                                                                            class="fileInput"
                                                                                            onchange="updateFileName('identity_pancard')" />
                                                                                        <span
                                                                                            class="importfileStore"><i
                                                                                                class="fa-solid fa-image"></i></span>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                            <div class="identityTypePassport row"
                                                                                style="display:none;">

                                                                                <div
                                                                                    class="PassportdivOne col-lg-12 mb-2">
                                                                                    <div
                                                                                        class="fileUploadInput input-container mb-2">
                                                                                        <label
                                                                                            for="identitypassportcard"
                                                                                            class="fileNameDisplay">Please
                                                                                            upload Identity
                                                                                            Passport</label>
                                                                                        <input type="file"
                                                                                            name="identityfront"
                                                                                            id="identitypassportcard"
                                                                                            class="fileInput"
                                                                                            onchange="updateFileName('identitypassportcard')" />
                                                                                        <span
                                                                                            class="importfileStore"><i
                                                                                                class="fa-solid fa-image"></i></span>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                            <div class="identityTypeDL row"
                                                                                style="display:none;">
                                                                                <div class="divOne col-lg-12 mb-2">
                                                                                    <div
                                                                                        class="fileUploadInput input-container mb-2">
                                                                                        <label for="DrivingFront"
                                                                                            class="fileNameDisplay">Please
                                                                                            upload Identity DL
                                                                                            Proof</label>
                                                                                        <input type="file"
                                                                                            name="identityfront"
                                                                                            id="DrivingFront"
                                                                                            class="fileInput"
                                                                                            onchange="updateFileName('DrivingFront')" />
                                                                                        <span
                                                                                            class="importfileStore"><i
                                                                                                class="fa-solid fa-image"></i></span>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="divtwo col-lg-12 mb-2">
                                                                                    {{-- <div
                                                                                class="fileUploadInput input-container mb-2">
                                                                                <label for="DrivingBack"
                                                                                    class="fileNameDisplay">Please
                                                                                    upload Identity DL(Back)
                                                                                    Proof</label>
                                                                                <input type="file"
                                                                                    name="identityfront"
                                                                                    id="DrivingBack" class="fileInput"
                                                                                    onchange="updateFileName('DrivingBack')" />
                                                                                <span class="importfileStore"><i
                                                                                        class="fa-solid fa-image"></i></span>
                                                                            </div> --}}
                                                                                </div>
                                                                            </div>
                                                                            <div class="identityTypeVoterID row"
                                                                                style="display:none;">


                                                                                <div class="divOne col-lg-12 mb-2">
                                                                                    <div
                                                                                        class="fileUploadInput input-container mb-2">
                                                                                        <label for="VoterFront"
                                                                                            class="fileNameDisplay">Please
                                                                                            upload Identity Voter ID
                                                                                            Proof</label>
                                                                                        <input type="file"
                                                                                            name="identityfront"
                                                                                            id="VoterFront"
                                                                                            class="fileInput"
                                                                                            onchange="updateFileName('VoterFront')" />
                                                                                        <span
                                                                                            class="importfileStore"><i
                                                                                                class="fa-solid fa-image"></i></span>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="divtwo col-lg-12 mb-2">
                                                                                    {{-- <div
                                                                                class="fileUploadInput input-container mb-2">
                                                                                <label for="VoterBack"
                                                                                    class="fileNameDisplay">Please
                                                                                    upload Identity Voter ID(Back)
                                                                                    Proof</label>
                                                                                <input type="file"
                                                                                    name="identityfront"
                                                                                    id="VoterBack" class="fileInput"
                                                                                    onchange="updateFileName('VoterBack')" />
                                                                                <span class="importfileStore"><i
                                                                                        class="fa-solid fa-image"></i></span>
                                                                            </div> --}}
                                                                                </div>

                                                                            </div>
                                                                            <div class="identityTypeForm60 row"
                                                                                style="display:none;">
                                                                                <div class="divOne col-lg-12 mb-2">
                                                                                    <div
                                                                                        class="fileUploadInput input-container mb-2">
                                                                                        <label for="Form60"
                                                                                            class="fileNameDisplay">Please
                                                                                            upload Identity Form
                                                                                            60</label>
                                                                                        <input type="file"
                                                                                            name="identityfront"
                                                                                            id="Form60"
                                                                                            class="fileInput"
                                                                                            onchange="updateFileName('Form60')" />
                                                                                        <span
                                                                                            class="importfileStore"><i
                                                                                                class="fa-solid fa-image"></i></span>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <div class="addressTypeAadhar row"
                                                                                style="display:none;">
                                                                                <div class="divOne col-lg-12 mb-2">
                                                                                    <div
                                                                                        class="fileUploadInput input-container mb-2">
                                                                                        <label
                                                                                            for="addressidentity_aadhar"
                                                                                            class="fileNameDisplay">Please
                                                                                            upload Address Aadhaar
                                                                                            Proof</label>
                                                                                        <input type="file"
                                                                                            name="addressfront"
                                                                                            id="addressidentity_aadhar"
                                                                                            class="fileInput"
                                                                                            onchange="updateFileName('addressidentity_aadhar')" />
                                                                                        <span
                                                                                            class="importfileStore"><i
                                                                                                class="fa-solid fa-image"></i></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="divtwo col-lg-12 mb-2">
                                                                                    {{-- <div
                                                                                class="fileUploadInput input-container mb-2">
                                                                                <label for="addressidentity_aadharBack"
                                                                                    class="fileNameDisplay">Please
                                                                                    upload Address Aadhaar (Back)
                                                                                    Proof</label>
                                                                                <input type="file"
                                                                                    name="addressback"
                                                                                    id="addressidentity_aadharBack"
                                                                                    class="fileInput"
                                                                                    onchange="updateFileName('addressidentity_aadharBack')" />
                                                                                <span class="importfileStore"><i
                                                                                        class="fa-solid fa-image"></i></span>
                                                                            </div> --}}
                                                                                </div>
                                                                            </div>
                                                                            <div class="addressTypePan row"
                                                                                style="display:none;">
                                                                                <div class="PandivOne col-lg-12 mb-2">
                                                                                    <div
                                                                                        class="fileUploadInput input-container mb-2">
                                                                                        <label for="addresspancard"
                                                                                            class="fileNameDisplay">Please
                                                                                            upload Address pan</label>
                                                                                        <input type="file"
                                                                                            name="addressfront"
                                                                                            id="addresspancard"
                                                                                            class="fileInput"
                                                                                            onchange="updateFileName('addresspancard')" />
                                                                                        <span
                                                                                            class="importfileStore"><i
                                                                                                class="fa-solid fa-image"></i></span>
                                                                                    </div>
                                                                                </div>


                                                                            </div>
                                                                            <div class="addressTypePassport row"
                                                                                style="display:none;">

                                                                                <div
                                                                                    class="PassportdivOne col-lg-12 mb-2">
                                                                                    <div
                                                                                        class="fileUploadInput input-container mb-2">
                                                                                        <label
                                                                                            for="addresspassportcard"
                                                                                            class="fileNameDisplay">Please
                                                                                            upload Address
                                                                                            Passport</label>
                                                                                        <input type="file"
                                                                                            name="addressfront"
                                                                                            id="addresspassportcard"
                                                                                            class="fileInput"
                                                                                            onchange="updateFileName('addresspassportcard')" />
                                                                                        <span
                                                                                            class="importfileStore"><i
                                                                                                class="fa-solid fa-image"></i></span>
                                                                                    </div>
                                                                                </div>


                                                                            </div>
                                                                            <div class="addressTypeDL row"
                                                                                style="display:none;">

                                                                                <div class="divOne col-lg-12 mb-2">
                                                                                    <div
                                                                                        class="fileUploadInput input-container mb-2">
                                                                                        <label for="addressDlFront"
                                                                                            class="fileNameDisplay">Please
                                                                                            upload Address DL
                                                                                            Proof</label>
                                                                                        <input type="file"
                                                                                            name="addressfront"
                                                                                            id="addressDlFront"
                                                                                            class="fileInput"
                                                                                            onchange="updateFileName('addressDlFront')" />
                                                                                        <span
                                                                                            class="importfileStore"><i
                                                                                                class="fa-solid fa-image"></i></span>
                                                                                    </div>
                                                                                </div>


                                                                                <div class="divtwo col-lg-12 mb-2">
                                                                                    {{-- <div
                                                                                class="fileUploadInput input-container mb-2">
                                                                                <label for="addressDlBack"
                                                                                    class="fileNameDisplay">Please
                                                                                    upload Address DL(Back)
                                                                                    Proof</label>
                                                                                <input type="file"
                                                                                    name="addressfront"
                                                                                    id="addressDlBack"
                                                                                    class="fileInput"
                                                                                    onchange="updateFileName('addressDlBack')" />
                                                                                <span class="importfileStore"><i
                                                                                        class="fa-solid fa-image"></i></span>
                                                                            </div> --}}
                                                                                </div>


                                                                            </div>
                                                                            <div class="addressTypeVoterID row"
                                                                                style="display:none;">
                                                                                <div class="divOne col-lg-12 mb-2">
                                                                                    <div
                                                                                        class="fileUploadInput input-container mb-2">
                                                                                        <label for="addressVoterFront"
                                                                                            class="fileNameDisplay">Please
                                                                                            upload Address Voter ID
                                                                                            Proof</label>
                                                                                        <input type="file"
                                                                                            name="addressfront"
                                                                                            id="addressVoterFront"
                                                                                            class="fileInput"
                                                                                            onchange="updateFileName('addressVoterFront')" />
                                                                                        <span
                                                                                            class="importfileStore"><i
                                                                                                class="fa-solid fa-image"></i></span>
                                                                                    </div>
                                                                                </div>


                                                                                <div class="divtwo col-lg-12 mb-2">
                                                                                    {{-- <div
                                                                                class="fileUploadInput input-container mb-2">
                                                                                <label for="addressVoterBack"
                                                                                    class="fileNameDisplay">Please
                                                                                    upload Address Voter ID(Back)
                                                                                    Proof</label>
                                                                                <input type="file"
                                                                                    name="addressfront"
                                                                                    id="addressVoterBack"
                                                                                    class="fileInput"
                                                                                    onchange="updateFileName('addressVoterBack')" />
                                                                                <span class="importfileStore"><i
                                                                                        class="fa-solid fa-image"></i></span>
                                                                            </div> --}}
                                                                                </div>



                                                                            </div>
                                                                            <div class="addressTypeForm60 row"
                                                                                style="display:none;">
                                                                                <div class="divOne col-lg-12 mb-2">
                                                                                    <div
                                                                                        class="fileUploadInput input-container mb-2">
                                                                                        <label for="addressForm60"
                                                                                            class="fileNameDisplay">Please
                                                                                            upload Address Form
                                                                                            60</label>
                                                                                        <input type="file"
                                                                                            name="addressfront"
                                                                                            id="addressForm60"
                                                                                            class="fileInput"
                                                                                            onchange="updateFileName('addressForm60')" />
                                                                                        <span
                                                                                            class="importfileStore"><i
                                                                                                class="fa-solid fa-image"></i></span>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>




                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <button class="btnsm" id="uploadbtn"
                                                                        onclick="uploadDocument()">Upload</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3" id="personalDetail" style="display: none">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                                            <label for="field1" class="form-label">Proposer's
                                                                details:</label>
                                                        </div>
                                                        {{-- {{ $proposardata->mr_mrs[1]}} --}}
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="row">
                                                                <div class="col-lg-3">
                                                                    <div class="input-container mb-2">

                                                                        <select id="mr_ms_gender" name="mr_ms_gender"
                                                                            class="input form-control"
                                                                            oninput="clearErrorOne('mr_ms_genderError')">
                                                                            <option value="">Select Gender
                                                                            </option>

                                                                            <option id="MR" value="Mr"
                                                                                @selected($proposardata->mr_mrs === 'Mr')>
                                                                                Mr
                                                                            </option>

                                                                            <option id="MS" value="Ms"
                                                                                @selected($proposardata->mr_mrs === 'Ms')>
                                                                                Ms
                                                                            </option>
                                                                        </select>
                                                                        <div id="mr_ms_genderError"
                                                                            style="color:red; display:none;"></div>

                                                                        <div class="placeholder">Mr/Ms</div>
                                                                        <span class="error-message"
                                                                            id="mr_ms_genderError"></span>
                                                                    </div>

                                                                </div>
                                                                <div class="col-lg-9">
                                                                    <div class="input-container mb-2">
                                                                        <input type="text" name="proposername"
                                                                            class="input form-control"
                                                                            value="{{ !empty($proposardata) ? $proposardata->kyc_name : '' }}"
                                                                            id="proposername" autocomplete="off"
                                                                            spellcheck="false" maxlength="50"
                                                                            oninput="clearErrorOne('proposernameError')">
                                                                        <div class="placeholder">Full Name as per your
                                                                            ID Card</div>
                                                                        <span class="error-message"
                                                                            id="proposernameError"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <div class="input-group1 datepickerdiv">

                                                                    <input type="text" name="proposerdob1"
                                                                        value="{{ !empty($proposardata) ? $proposardata->dob : '' }}"
                                                                        class="input form-control" id="proposerdob1"
                                                                        autocomplete="off" spellcheck="false"
                                                                        maxlength="10"
                                                                        oninput="clearErrorOne('proposerdobError1')">
                                                                    <div class="placeholder">
                                                                        D.O.B (DD-MM-YYYY)
                                                                    </div>
                                                                    {{-- <button class="btn calendarButton" type="button">
                                                                        <i class="fa-solid fa-calendar-days"></i>
                                                                    </button> --}}
                                                                    <span id="proposerdobError1"
                                                                        class="error-message"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                                            <label for="field1" class="form-label">Permanent
                                                                Address:</label>

                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="house"
                                                                    value="{{ !empty($proposardata) ? $proposardata->house : '' }}"
                                                                    class="input form-control" id="house"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="60"
                                                                    oninput="clearErrorOne('houseError')">
                                                                <div class="placeholder">Address line 1
                                                                </div>
                                                                <span class="error-message" id="houseError"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="colony"
                                                                    value="{{ !empty($proposardata) ? $proposardata->colony : '' }}"
                                                                    class="input form-control" id="colony"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="60"
                                                                    oninput="clearErrorOne('colonyError')">
                                                                <div class="placeholder">Address line 2</div>
                                                                <span class="error-message" id="colonyError"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="Landmark"
                                                                    value="{{ !empty($proposardata) ? $proposardata->landmark : '' }}"
                                                                    class="input form-control" id="Landmark"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="29"
                                                                    oninput="clearErrorOne('LandmarkError')">
                                                                <div class="placeholder">Landmark</div>
                                                                <span class="error-message" id="LandmarkError"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="City"
                                                                    value="{{ strtoupper($pincode->city) }}"
                                                                    class="input form-control" id="City"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="25"
                                                                    oninput="clearErrorOne('CityError')">
                                                                <div class="placeholder">City</div>
                                                                <span class="error-message" id="CityError"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="State"
                                                                    value="{{ strtoupper($pincode->state) }}"
                                                                    class="input form-control" id="State"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="25" readonly
                                                                    oninput="clearErrorOne('StateError')">
                                                                <div class="placeholder">State</div>
                                                                <span class="error-message" id="StateError"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="Pincode"
                                                                    value="{{ !empty($proposardata) ? $proposardata->pincode : '' }}"
                                                                    class="input form-control" id="Pincode"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="6" oninput="acpincode(this)" readonly>
                                                                <div class="placeholder">Pincode</div>
                                                                <span class="error-message" id="PincodeError"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">

                                                            <div class="d-flex">
                                                                <label for="field1" class="form-label"
                                                                    style="margin-right: 10px">Communication
                                                                    Address:</label>
                                                                <input type="checkbox" class="p-0" value="0"
                                                                    name="sameAddress" id="sameAddress"
                                                                    style="margin-right: 10px">
                                                                <label for="sameAddress"
                                                                    class="form-label pointer">Same As
                                                                    Permanent
                                                                    Address</label>

                                                            </div>

                                                        </div>


                                                    </div>
                                                    <div class="row" id="sameAddsection">
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="commcurrenthouse"
                                                                    value="{{ !empty($communication) ? $communication->comhouse : '' }}"
                                                                    class="input form-control" id="commcurrenthouse"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="60"
                                                                    oninput="clearErrorOne('commcurrenthouseError')">
                                                                <div class="placeholder">Address line 1
                                                                </div>
                                                                <span class="error-message"
                                                                    id="commcurrenthouseError"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="commcurrentcolony"
                                                                    value="{{ !empty($communication) ? $communication->comcolony : '' }}"
                                                                    class="input form-control" id="commcurrentcolony"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="60"
                                                                    oninput="clearErrorOne('commcurrentcolonyError')">
                                                                <div class="placeholder">Address line 2</div>
                                                                <span class="error-message"
                                                                    id="commcurrentcolonyError"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="commcurrentLandmark"
                                                                    value="{{ !empty($communication) ? $communication->comlandmark : '' }}"
                                                                    class="input form-control"
                                                                    id="commcurrentLandmark" autocomplete="off"
                                                                    spellcheck="false" maxlength="29"
                                                                    oninput="clearErrorOne('commcurrentLandmarkError')">
                                                                <div class="placeholder">Landmark</div>
                                                                <span class="error-message"
                                                                    id="commcurrentLandmarkError"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="commcurrentCity"
                                                                    value="{{ !empty($communication) ? $communication->comcity : '' }}"
                                                                    class="input form-control" id="commcurrentCity"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="25"
                                                                    oninput="clearErrorOne('commcurrentCityError')">
                                                                <div class="placeholder">City</div>
                                                                <span class="error-message"
                                                                    id="commcurrentCityError"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="commcurrentState"
                                                                    value="{{ !empty($communication) ? $communication->comstate : '' }}"
                                                                    class="input form-control" id="commcurrentState"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="25"
                                                                    oninput="clearErrorOne('commcurrentStateError')">
                                                                <div class="placeholder">State</div>
                                                                <span class="error-message"
                                                                    id="commcurrentStateError"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="commcurrentPincode"
                                                                    value="{{ !empty($communication) ? $communication->compincode : '' }}"
                                                                    class="input form-control" id="commcurrentPincode"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="6" oninput="acpincode(this)">
                                                                <div class="placeholder">Pincode</div>
                                                                <span class="error-message"
                                                                    id="commcurrentPincodeError"></span>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                                                            <label for="field1" class="form-label">Contact
                                                                Details:</label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="row">
                                                                <div class="col-lg-12 d-flex">
                                                                    <div class="input-container "
                                                                        style="width: 100%;margin-right:10px;">
                                                                        <input type="email" name="contactemail"
                                                                            class="input form-control"
                                                                            value="{{ !empty($proposardata) ? $proposardata->email : '' }}"
                                                                            id="contactemail" autocomplete="off"
                                                                            spellcheck="false" maxlength="55"
                                                                            oninput="clearErrorOne('contactemailError')">
                                                                        <div class="placeholder">Email Address</div>

                                                                    </div>

                                                                    {{-- <button type="button" class="btnsm mt-1"
                                                                        id="editOtp" data-toggle="modal"
                                                                        data-target="#enterOtp">VERIFY</button> --}}
                                                                </div>
                                                                {{-- <div class="col-lg-3 p-0">
                                                                    <button type="button" class="btnsm mt-1"
                                                                        id="editOtp" data-toggle="modal"
                                                                        data-target="#enterOtp">VERIFY</button>
                                                                </div> --}}
                                                            </div>
                                                            <span class="error-message" id="contactemailError"></span>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="contactmobile"
                                                                    class="input form-control" id="contactmobile"
                                                                    value="{{ !empty($proposardata) ? 'XXXXXX' . substr($proposardata->mobile, 6, 4) : '' }}"
                                                                    @auth {{ 'readonly' }} @endauth
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="10"
                                                                    oninput="handleSubmit('contactemobileError',this,10)"
                                                                    title="Please enter a mobile number (digits only)">
                                                                <div class="placeholder">Mobile Number</div>
                                                                <span class="error-message"
                                                                    id="contactmobileError"></span>
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="contactemergency"
                                                                    value="{{ !empty($proposardata) ? $proposardata->emergency_mobile : '' }}"
                                                                    class="input form-control" id="contactemergency"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="10"
                                                                    oninput="handleSubmit('contactemergencyError',this,10)"
                                                                    pattern="\d{10}"
                                                                    title="Please enter a 10-digit emergency contact number (digits only)">
                                                                <div class="placeholder">Emergency Mobile No.</div>
                                                                <span class="error-message"
                                                                    id="contactemergencyError"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                                <button type="button" id="next1" class="membersection next-step"
                                                    onclick="validateFormStepOne(event)">Continue to member
                                                    section</button>
                                            </div>


                                            <div class="step step-2">
                                                <form action="" id="formStepTwo">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                                            <label for="field1" class="form-label">Member
                                                                Details:</label>
                                                            <h4>Tell us the details about the members to be insured</h4>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                                            <p>Please provide us with some additional details</p>
                                                            <label for="field1" class="form-label">Self:</label>

                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <div class="input-group1">
                                                                    @php
                                                                    // echo($proposardata);
                                                                    @endphp
                                                                    <input type="text" name="proposername"
                                                                        class="input form-control" id="proposername1"
                                                                        autocomplete="off" spellcheck="false"
                                                                        maxlength="25"
                                                                        value="{{ !empty($proposardata) ? $proposardata->kyc_name : '' }}"
                                                                        oninput="clearErrorTwo('proposername1Error')"
                                                                        readonly />
                                                                    <div class="placeholder">Name</div>
                                                                    {{-- <button class="btn calendarButton" type="button">
                                                                        <i class="fa-solid fa-calendar-days"></i>
                                                                    </button> --}}
                                                                </div>
                                                                <span id="proposername1Error"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <div class="input-group1 datepickerdiv">
                                                                    <input type="text" name="proposerdob2"
                                                                        class="input form-control datepicker" id="proposerdob2"
                                                                        autocomplete="off" spellcheck="false"
                                                                        maxlength="10"
                                                                        value="{{ !empty($proposardata) ? $proposardata->dob : '' }}"
                                                                        oninput="clearErrorTwo('proposerdob2Error')"
                                                                        readonly />
                                                                    <div class="placeholder">D.O.B (DD-MM-YYYY)
                                                                    </div>
                                                                    <button class="btn calendarButton"
                                                                        type="button">
                                                                        <i
                                                                            class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                                <span id="proposerdob2Error"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <select name="proposeroccupation"
                                                                    id="proposeroccupation" class="input form-control"
                                                                    onchange="clearErrorTwo('proposeroccupationError')">
                                                                    <option value="">Select</option>
                                                                    @foreach (['Salaried', 'Self Employed', 'Student', 'Retired'] as $data)
                                                                    <option value="{{ $data }}"
                                                                        {{ $data == $proposardata->occupation ? 'selected' : '' }}>
                                                                        {{ $data }}
                                                                    </option>
                                                                    @endforeach
                                                                    @if ($proposardata->gender == 'female')
                                                                    <option value="House wife"
                                                                        {{ 'House wife' == $proposardata->occupation ? 'selected' : '' }}>
                                                                        House wife</option>
                                                                    @endif
                                                                </select>
                                                                <div class="placeholder">Occupation</div>
                                                                <span id="proposeroccupationError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-3 col-md-3 col-sm-6 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="proposerheight"
                                                                    class="input form-control single-digit"
                                                                    id="proposerheight" autocomplete="off"
                                                                    spellcheck="false" maxlength="1"
                                                                    placeholder="height(Feet)"
                                                                    value="{{ !empty($proposardata) ? $proposardata->height : '' }}"
                                                                    oninput="handleSubmitTwo('proposerheightError',this,1)">
                                                                {{-- <select name="proposerheight" id="proposerheight"
                                                                    class="input form-control"
                                                                    onchange="clearErrorTwo('proposerheightError')">
                                                                    <option value="">Select</option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <!-- Add more options as needed -->
                                                                </select> --}}
                                                                <div class="placeholder">Height (Feet)</div>
                                                                <span id="proposerheightError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-6 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="proposerinches"
                                                                    class="input form-control double-digit"
                                                                    id="proposerinches" autocomplete="off"
                                                                    spellcheck="false" maxlength="2"
                                                                    placeholder="height(Inches)"
                                                                    value="{{ !empty($proposardata) ? $proposardata->inch : '' }}"
                                                                    oninput="handleSubmitTwo('proposerinchesError',this,2)">
                                                                {{-- <select name="proposerinches" id="proposerinches"
                                                                    class="input form-control"
                                                                    onchange="clearErrorTwo('proposerinchesError')">
                                                                    <option value="">Select</option>
                                                                    <option value="1 inch">1 inch</option>
                                                                    <option value="2 inches">2 inches</option>
                                                                    <!-- Add more options as needed -->
                                                                </select> --}}
                                                                <div class="placeholder">Height (Inches)</div>
                                                                <span id="proposerinchesError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="proposerweight"
                                                                    class="input form-control" id="proposerweight"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="3"
                                                                    value="{{ !empty($proposardata) ? $proposardata->weight : '' }}"
                                                                    oninput="handleSubmitTwo('proposerweightError',this,3)" />
                                                                <div class="placeholder">Weight (KG)</div>
                                                                <span id="proposerweightError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <div class="input-group1">
                                                                    <input type="text" name="proposarbankaccount"
                                                                        class="input form-control"
                                                                        id="proposarbankaccount" autocomplete="off"
                                                                        spellcheck="false" maxlength="25"
                                                                        value=""
                                                                        oninput="clearErrorTwo('proposarbankaccountError')" />
                                                                    <div class="placeholder">Bank Account</div>
                                                                    {{-- <button class="btn calendarButton" type="button">
                                                                        <i class="fa-solid fa-calendar-days"></i>
                                                                    </button> --}}
                                                                </div>
                                                                <span id="proposarbankaccountError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <div class="input-group1">
                                                                    <input type="text" name="proposarbankifsc"
                                                                        class="input form-control"
                                                                        id="proposarbankifsc" autocomplete="off"
                                                                        spellcheck="false" maxlength="11"
                                                                        value=""
                                                                        oninput="clearErrorTwo('proposarbankifscError')" />
                                                                    <div class="placeholder">Bank Ifsc</div>
                                                                    {{-- <button class="btn calendarButton" type="button">
                                                                        <i class="fa-solid fa-calendar-days"></i>
                                                                    </button> --}}
                                                                </div>
                                                                <span id="proposarbankifscError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @foreach ($prposaralldata as $data)
                                                    @if ($data->name == 'wife' || $data->name == 'husband')
                                                    @php
                                                    $spousedata = JourneyUsers::where(
                                                    'proposalid',
                                                    Auth::id(),
                                                    )
                                                    ->where('relation', $data->name)
                                                    ->first();

                                                    @endphp
                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                                            <label for="field1"
                                                                class="form-label">Spouse:</label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="spousename"
                                                                    class="input form-control" id="spousename"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="25"
                                                                    value="{{ !empty($spousedata->name) ? $spousedata->name : '' }}"
                                                                    oninput="clearErrorTwo('spousenameError')" />
                                                                <div class="placeholder">Enter Spouse's Full
                                                                    Name</div>
                                                                <span id="spousenameError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <div class="input-group1 datepickerdiv">
                                                                    <input type="text" name="spousedob"
                                                                        class="input form-control datepicker"
                                                                        id="spousedob" autocomplete="off"
                                                                        spellcheck="false" maxlength="10"
                                                                        value="{{ !empty($spousedata->dob) ? $spousedata->dob : '' }}"
                                                                        oninput="clearErrorTwo('spousedobError')" />
                                                                    <div class="placeholder">D.O.B (DD-MM-YYYY)
                                                                    </div>
                                                                    <button class="btn calendarButton"
                                                                        type="button">
                                                                        <i
                                                                            class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                                <span id="spousedobError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <select name="spouseoccupation"
                                                                    id="spouseoccupation"
                                                                    class="input form-control"
                                                                    onchange="clearErrorTwo('spouseoccupationError')">
                                                                    <option value="">Select</option>
                                                                    @foreach (['Salaried', 'Self Employed', 'Student', 'Retired'] as $occupation)
                                                                    <option value="{{ $occupation }}"
                                                                        @selected(!empty($spousedata->occupation) && $spousedata->occupation == $occupation)>
                                                                        {{ $occupation }}
                                                                    </option>
                                                                    @endforeach
                                                                    @if ($proposardata->gender == 'male')
                                                                    <option value="House wife"
                                                                        @selected(!empty($spousedata->occupation) && $spousedata->occupation == 'House wife')>
                                                                        House wife
                                                                    </option>
                                                                    @endif
                                                                </select>
                                                                <div class="placeholder">Occupation</div>
                                                                <span id="spouseoccupationError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="spouseheight"
                                                                    class="input form-control single-digit"
                                                                    id="spouseheight" autocomplete="off"
                                                                    spellcheck="false" maxlength="1"
                                                                    placeholder="height(Feet)"
                                                                    value="{{ !empty($spousedata->height) ? $spousedata->height : '' }}"
                                                                    oninput="handleSubmitTwo('spouseheightError',this,1)">
                                                                {{-- <select name="spouseheight" id="spouseheight"
                                                                            class="input form-control"
                                                                            onchange="clearErrorTwo('spouseheightError')">
                                                                            <option value="">Select</option>
                                                                            <option value="1">1</option>
                                                                            <option value="2">2</option>
                                                                            <!-- Add more options as needed -->
                                                                        </select> --}}
                                                                <div class="placeholder">Height (Feet)</div>
                                                                <span id="spouseheightError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="spouseinches"
                                                                    class="input form-control double-digit"
                                                                    id="spouseinches" autocomplete="off"
                                                                    spellcheck="false" maxlength="2"
                                                                    placeholder="height(Inches)"
                                                                    value="{{ !empty($spousedata->inch) ? $spousedata->inch : '' }}"
                                                                    oninput="handleSubmitTwo('spouseinchesError',this,2)">
                                                                {{-- <select name="spouseinches" id="spouseinches"
                                                                            class="input form-control"
                                                                            onchange="clearErrorTwo('spouseinchesError')">
                                                                            <option value="">Select</option>
                                                                            <option value="1 inch">1 inch</option>
                                                                            <option value="2 inches">2 inches</option>
                                                                            <!-- Add more options as needed -->
                                                                        </select> --}}
                                                                <div class="placeholder">Height (Inches)</div>
                                                                <span id="spouseinchesError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="spouseweight"
                                                                    class="input form-control"
                                                                    id="spouseweight" autocomplete="off"
                                                                    spellcheck="false" maxlength="3"
                                                                    value="{{ !empty($spousedata->weight) ? $spousedata->weight : '' }}"
                                                                    oninput="handleSubmitTwo('spouseweightError',this,3)" />
                                                                <div class="placeholder">Weight (KG)</div>
                                                                <span id="spouseweightError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endforeach
                                                    @if (count($child) > 0)
                                                    @php
                                                    $count = 0;
                                                    @endphp
                                                    @foreach ($child as $rec)
                                                    @php
                                                    $i = ++$count;
                                                    @endphp
                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-mb-12 col-sm-12 mt-2">
                                                            <label for="field{{ $i }}"
                                                                class="form-label">Child:</label>
                                                        </div>
                                                        <div class="col-lg-6 col-mb-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text"
                                                                    name="childname{{ $i }}"
                                                                    class="input form-control"
                                                                    id="childname{{ $i }}"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="25"
                                                                    oninput="clearErrorTwo('childname{{ $i }}Error')" />
                                                                <div class="placeholder">Enter Child's Full
                                                                    Name</div>
                                                                <span id="childname{{ $i }}Error"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-mb-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <div class="input-group1 datepickerdiv">
                                                                    <input type="text"
                                                                        name="childdob{{ $i }}"
                                                                        class="input form-control datepicker"
                                                                        id="childdob{{ $i }}"
                                                                        autocomplete="off" spellcheck="false"
                                                                        maxlength="10"
                                                                        oninput="clearErrorTwo('childdob1Error')" />
                                                                    <div class="placeholder">D.O.B
                                                                        (DD-MM-YYYY)
                                                                    </div>
                                                                    <button class="btn calendarButton"
                                                                        type="button">
                                                                        <i
                                                                            class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                                <span id="childdob{{ $i }}Error"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-mb-3 col-sm-6 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text"
                                                                    name="childheight{{ $i }}"
                                                                    class="input form-control single-digit"
                                                                    id="childheight{{ $i }}"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="1"
                                                                    placeholder="height(Feet)"
                                                                    oninput="handleSubmitTwo('childheight{{ $i }}Error',this,1)">
                                                                {{-- <select name="childheight{{ $i }}"
                                                                id="childheight{{ $i }}"
                                                                class="input form-control"
                                                                onchange="clearErrorTwo('childheight{{ $i }}Error')">
                                                                <option value="">Select</option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <!-- Add more options as needed -->
                                                                </select> --}}
                                                                <div class="placeholder">Height (Feet)</div>
                                                                <span
                                                                    id="childheight{{ $i }}Error"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-mb-3 col-sm-6 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text"
                                                                    name="childinches{{ $i }}"
                                                                    class="input form-control double-digit"
                                                                    id="childinches{{ $i }}"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="2"
                                                                    placeholder="height(Inches)"
                                                                    oninput="handleSubmitTwo('childinches{{ $i }}Error',this,2)">
                                                                {{-- <select name="childinches{{ $i }}"
                                                                id="childinches{{ $i }}"
                                                                class="input form-control"
                                                                onchange="clearErrorTwo('childinches{{ $i }}Error')">
                                                                <option value="">Select</option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <!-- Add more options as needed -->
                                                                </select> --}}
                                                                <div class="placeholder">Height (Inches)</div>
                                                                <span
                                                                    id="childinches{{ $i }}Error"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-mb-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text"
                                                                    name="childweight{{ $i }}"
                                                                    class="input form-control"
                                                                    id="childweight{{ $i }}"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="3"
                                                                    oninput="handleSubmitTwo('childweight{{ $i }}Error',this,3)" />
                                                                <div class="placeholder">Weight (KG)</div>
                                                                <span
                                                                    id="childweight{{ $i }}Error"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-mb-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <select
                                                                    name="childrelation{{ $i }}"
                                                                    id="childrelation{{ $i }}"
                                                                    class="input form-control"
                                                                    onchange="clearErrorTwo('childrelation{{ $i }}Error')">
                                                                    <option value="">Select Relation
                                                                    </option>
                                                                    <option value="Son">Son</option>
                                                                    <option value="Daughter">Daughter</option>
                                                                </select>
                                                                <div class="placeholder">Child Relation</div>
                                                                <span
                                                                    id="childrelation{{ $i }}Error"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @endif

                                                    @foreach ($prposaralldata as $data)
                                                    @if ($data->name == 'grandmother')
                                                    @php
                                                    $grandmotherdata = JourneyUsers::where(
                                                    'proposalid',
                                                    Auth::id(),
                                                    )
                                                    ->where('relation', $data->name)
                                                    ->first();
                                                    // echo $data->id;
                                                    @endphp
                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                                            <label for="field1"
                                                                class="form-label">GrandMother:</label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text"
                                                                    name="grandMothernameOne"
                                                                    class="input form-control"
                                                                    id="grandMothernameOne"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="25"
                                                                    value="{{ !empty($grandmotherdata->name) ? $grandmotherdata->name : '' }}"
                                                                    oninput="clearErrorTwo('grandMothernameOneError')" />
                                                                <div class="placeholder">Enter GrandMother's
                                                                    Full Name
                                                                </div>
                                                                <span id="grandMothernameOneError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <div class="input-group1 datepickerdiv">
                                                                    <input type="text"
                                                                        name="grandMotherdobOne"
                                                                        class="input form-control datepicker"
                                                                        id="grandMotherdobOne"
                                                                        autocomplete="off"
                                                                        spellcheck="false" maxlength="10"
                                                                        value="{{ !empty($grandmotherdata->dob) ? $grandmotherdata->dob : '' }}"
                                                                        oninput="clearErrorTwo('grandMotherdobOneError')" />
                                                                    <div class="placeholder">D.O.B
                                                                        (DD-MM-YYYY)
                                                                    </div>
                                                                    <button class="btn calendarButton"
                                                                        type="button">
                                                                        <i
                                                                            class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                                <span id="grandMotherdobOneError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="input-container mb-2">
                                                                <input type="text"
                                                                    name="grandMotherheightOne"
                                                                    class="input form-control single-digit"
                                                                    id="grandMotherheightOne"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="1"
                                                                    placeholder="height(Feet)"
                                                                    value="{{ !empty($grandmotherdata->height) ? $grandmotherdata->height : '' }}"
                                                                    oninput="handleSubmitTwo('grandMotherheightOneError',this,1)">
                                                                {{-- <select name="grandMotherheightOne"
                                                                            id="grandMotherheightOne"
                                                                            class="input form-control"
                                                                            onchange="clearErrorTwo('grandMotherheightOneError')">
                                                                            <option value="">Select</option>
                                                                            <option value="1">1</option>
                                                                            <option value="2">2</option>
                                                                            <!-- Include all options -->
                                                                        </select> --}}
                                                                <div class="placeholder">Height (Feet)</div>
                                                                <span id="grandMotherheightOneError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="input-container mb-2">
                                                                <input type="text"
                                                                    name="grandMotherinchesOne"
                                                                    class="input form-control double-digit"
                                                                    id="grandMotherinchesOne"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="2"
                                                                    value="{{ !empty($grandmotherdata->inch) ? $grandmotherdata->inch : '' }}"
                                                                    placeholder="height(Inches)"
                                                                    oninput="handleSubmitTwo('grandMotherinchesOneError',this,2)">
                                                                {{-- <select name="grandMotherinchesOne"
                                                                            id="grandMotherinchesOne"
                                                                            class="input form-control"
                                                                            onchange="clearErrorTwo('grandMotherinchesOneError')">
                                                                            <option value="">Select</option>
                                                                            <option value="1 inch">1 inch</option>
                                                                            <option value="1">1</option>
                                                                            <!-- Include all options -->
                                                                        </select> --}}
                                                                <div class="placeholder">Height (Inches)</div>
                                                                <span id="grandMotherinchesOneError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                            <div class="input-container mb-2">
                                                                <input type="text"
                                                                    name="grandMotherweightOne"
                                                                    class="input form-control"
                                                                    id="grandMotherweightOne"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="3"
                                                                    value="{{ !empty($grandmotherdata->weight) ? $grandmotherdata->weight : '' }}"
                                                                    oninput="handleSubmitTwo('grandMotherweightOneError',this,2)" />
                                                                <div class="placeholder">Weight (KG)</div>
                                                                <span id="grandMotherweightOneError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endforeach

                                                    @foreach ($prposaralldata as $data)
                                                    @if ($data->name == 'grandfather')
                                                    @php
                                                    $grandfatherdata = JourneyUsers::where(
                                                    'proposalid',
                                                    Auth::id(),
                                                    )
                                                    ->where('relation', $data->name)
                                                    ->first();
                                                    // echo $data->id;
                                                    @endphp
                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                                            <label for="field1"
                                                                class="form-label">Grandfather:</label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="Grandfathername"
                                                                    class="input form-control"
                                                                    id="Grandfathername" autocomplete="off"
                                                                    spellcheck="false" maxlength="25"
                                                                    value="{{ !empty($grandfatherdata->name) ? $grandfatherdata->name : '' }}"
                                                                    oninput="clearErrorTwo('GrandfathernameError')" />
                                                                <div class="placeholder">Enter Grandfather's
                                                                    Full Name
                                                                </div>
                                                                <span id="GrandfathernameError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <div class="input-group1 datepickerdiv">
                                                                    <input type="text"
                                                                        name="Grandfatherdob"
                                                                        class="input form-control datepicker"
                                                                        id="Grandfatherdob"
                                                                        autocomplete="off"
                                                                        spellcheck="false" maxlength="10"
                                                                        value="{{ !empty($grandfatherdata->dob) ? $grandfatherdata->dob : '' }}"
                                                                        oninput="clearErrorTwo('GrandfatherdobError')" />
                                                                    <div class="placeholder">D.O.B
                                                                        (DD-MM-YYYY)
                                                                    </div>
                                                                    <button class="btn calendarButton"
                                                                        type="button">
                                                                        <i
                                                                            class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                                <span id="GrandfatherdobError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="input-container mb-2">
                                                                <input type="text"
                                                                    name="Grandfatherheight"
                                                                    class="input form-control single-digit"
                                                                    id="Grandfatherheight"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="1"
                                                                    placeholder="height(Feet)"
                                                                    value="{{ !empty($grandmotherdata->height) ? $grandmotherdata->height : '' }}"
                                                                    oninput="handleSubmitTwo('GrandfatherheightError',this,1)">
                                                                {{-- <select name="Grandfatherheight"
                                                                            id="Grandfatherheight"
                                                                            class="input form-control"
                                                                            onchange="clearErrorTwo('GrandfatherheightError')">
                                                                            <option value="">Select</option>
                                                                            <option value="1">1</option>
                                                                            <option value="2">2</option>
                                                                            <!-- Include all options -->
                                                                        </select> --}}
                                                                <div class="placeholder">Height (Feet)</div>
                                                                <span id="GrandfatherheightError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="input-container mb-2">
                                                                <input type="text"
                                                                    name="Grandfatherinches"
                                                                    class="input form-control double-digit"
                                                                    id="Grandfatherinches"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="2"
                                                                    value="{{ !empty($grandmotherdata->inch) ? $grandmotherdata->inch : '' }}"
                                                                    placeholder="height(Inches)"
                                                                    oninput="handleSubmitTwo('GrandfatherinchesError',this,2)">
                                                                {{-- <select name="Grandfatherinches"
                                                                            id="Grandfatherinches"
                                                                            class="input form-control"
                                                                            onchange="clearErrorTwo('GrandfatherinchesError')">
                                                                            <option value="">Select</option>
                                                                            <option value="1 inch">1 inch</option>
                                                                            <option value="1">1</option>
                                                                            <!-- Include all options -->
                                                                        </select> --}}
                                                                <div class="placeholder">Height (Inches)</div>
                                                                <span id="GrandfatherinchesError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                            <div class="input-container mb-2">
                                                                <input type="text"
                                                                    name="Grandfatherweight"
                                                                    class="input form-control"
                                                                    id="Grandfatherweight"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="3"
                                                                    value="{{ !empty($grandmotherdata->weight) ? $grandmotherdata->weight : '' }}"
                                                                    oninput="handleSubmitTwo('GrandfatherweightError',this,3)" />
                                                                <div class="placeholder">Weight (KG)</div>
                                                                <span id="GrandfatherweightError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endforeach

                                                    @foreach ($prposaralldata as $data)
                                                    @if ($data->name == 'mother')
                                                    @php
                                                    $motherdata = JourneyUsers::where(
                                                    'proposalid',
                                                    Auth::id(),
                                                    )
                                                    ->where('relation', $data->name)
                                                    ->first();
                                                    @endphp
                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                                            <label for="field1"
                                                                class="form-label">Mother:</label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="mothername"
                                                                    class="input form-control"
                                                                    id="mothername" autocomplete="off"
                                                                    spellcheck="false" maxlength="25"
                                                                    value="{{ !empty($motherdata->name) ? $motherdata->name : '' }}"
                                                                    oninput="clearErrorTwo('mothernameError')" />
                                                                <div class="placeholder">Enter Mother's Full
                                                                    Name
                                                                </div>
                                                                <span id="mothernameError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <div class="input-group1 datepickerdiv">
                                                                    <input type="text" name="motherdob"
                                                                        class="input form-control datepicker"
                                                                        id="motherdob" autocomplete="off"
                                                                        spellcheck="false" maxlength="10"
                                                                        value="{{ !empty($motherdata->dob) ? $motherdata->dob : '' }}"
                                                                        oninput="clearErrorTwo('motherdobError')" />
                                                                    <div class="placeholder">D.O.B
                                                                        (DD-MM-YYYY)
                                                                    </div>
                                                                    <button class="btn calendarButton"
                                                                        type="button">
                                                                        <i
                                                                            class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                                <span id="motherdobError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="motherheight"
                                                                    class="input form-control single-digit"
                                                                    id="motherheight" autocomplete="off"
                                                                    spellcheck="false" maxlength="1"
                                                                    placeholder="height(Feet)"
                                                                    value="{{ !empty($motherdata->height) ? $motherdata->height : '' }}"
                                                                    oninput="handleSubmitTwo('motherheightError',this,1)">
                                                                {{-- <select name="motherheight"
                                                                            id="motherheight"
                                                                            class="input form-control"
                                                                            onchange="clearErrorTwo('motherheightError')">
                                                                            <option value="">Select</option>
                                                                            <option value="1">1</option>
                                                                            <option value="2">2</option>
                                                                            <!-- Include all options -->
                                                                        </select> --}}
                                                                <div class="placeholder">Height (Feet)</div>
                                                                <span id="motherheightError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="motherinches"
                                                                    class="input form-control double-digit"
                                                                    id="motherinches" autocomplete="off"
                                                                    spellcheck="false" maxlength="2"
                                                                    placeholder="height(Inches)"
                                                                    value="{{ !empty($motherdata->inch) ? $motherdata->inch : '' }}"
                                                                    oninput="handleSubmitTwo('motherinchesError',this,2)">
                                                                {{-- <select name="motherinches"
                                                                            id="motherinches"
                                                                            class="input form-control"
                                                                            onchange="clearErrorTwo('motherinchesError')">
                                                                            <option value="">Select</option>
                                                                            <option value="1 inch">1 inch</option>
                                                                            <option value="1">1</option>
                                                                            <!-- Include all options -->
                                                                        </select> --}}
                                                                <div class="placeholder">Height (Inches)</div>
                                                                <span id="motherinchesError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="motherweight"
                                                                    class="input form-control"
                                                                    id="motherweight" autocomplete="off"
                                                                    spellcheck="false" maxlength="3"
                                                                    value="{{ !empty($motherdata->weight) ? $motherdata->weight : '' }}"
                                                                    oninput="handleSubmitTwo('motherweightError',this,3)" />
                                                                <div class="placeholder">Weight (KG)</div>
                                                                <span id="motherweightError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endforeach

                                                    @foreach ($prposaralldata as $data)
                                                    @if ($data->name == 'father')
                                                    @php
                                                    $fatherdata = JourneyUsers::where(
                                                    'proposalid',
                                                    Auth::id(),
                                                    )
                                                    ->where('relation', $data->name)
                                                    ->first();
                                                    @endphp
                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                                            <label for="field1"
                                                                class="form-label">Father:</label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="fathername"
                                                                    class="input form-control"
                                                                    id="fathername" autocomplete="off"
                                                                    spellcheck="false" maxlength="25"
                                                                    value="{{ !empty($fatherdata->name) ? $fatherdata->name : '' }}"
                                                                    oninput="clearErrorTwo('fathernameError')" />
                                                                <div class="placeholder">Enter Father's Full
                                                                    Name
                                                                </div>
                                                                <span id="fathernameError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <div class="input-group1 datepickerdiv">
                                                                    <input type="text" name="fatherdob"
                                                                        class="input form-control datepicker"
                                                                        id="fatherdob" autocomplete="off"
                                                                        spellcheck="false" maxlength="10"
                                                                        value="{{ !empty($fatherdata->dob) ? $fatherdata->dob : '' }}"
                                                                        oninput="clearErrorTwo('fatherdobError')" />
                                                                    <div class="placeholder">D.O.B
                                                                        (DD-MM-YYYY)
                                                                    </div>
                                                                    <button class="btn calendarButton"
                                                                        type="button">
                                                                        <i
                                                                            class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                                <span id="fatherdobError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="fatherheight"
                                                                    class="input form-control single-digit"
                                                                    id="fatherheight" autocomplete="off"
                                                                    spellcheck="false" maxlength="1"
                                                                    placeholder="height(Feet)"
                                                                    value="{{ !empty($fatherdata->height) ? $fatherdata->height : '' }}"
                                                                    oninput="handleSubmitTwo('fatherheightError',this,1)">
                                                                {{-- <select name="fatherheight"
                                                                            id="fatherheight"
                                                                            class="input form-control"
                                                                            onchange="clearErrorTwo('fatherheightError')">
                                                                            <option value="">Select</option>
                                                                            <option value="1">1</option>
                                                                            <option value="2">2</option>
                                                                            <!-- Include all options -->
                                                                        </select> --}}
                                                                <div class="placeholder">Height (Feet)</div>
                                                                <span id="fatherheightError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="fatherinches"
                                                                    class="input form-control double-digit"
                                                                    id="fatherinches" autocomplete="off"
                                                                    spellcheck="false" maxlength="2"
                                                                    placeholder="height(Inches)"
                                                                    value="{{ !empty($fatherdata->inch) ? $fatherdata->inch : '' }}"
                                                                    oninput="handleSubmitTwo('fatherinchesError',this,2)">
                                                                {{-- <select name="fatherinches"
                                                                            id="fatherinches"
                                                                            class="input form-control"
                                                                            onchange="clearErrorTwo('fatherinchesError')">
                                                                            <option value="">Select</option>
                                                                            <option value="1 inch">1 inch</option>
                                                                            <option value="1">1</option>
                                                                            <!-- Include all options -->
                                                                        </select> --}}
                                                                <div class="placeholder">Height (Inches)</div>
                                                                <span id="fatherinchesError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="fatherweight"
                                                                    class="input form-control"
                                                                    id="fatherweight" autocomplete="off"
                                                                    spellcheck="false" maxlength="3"
                                                                    value="{{ !empty($fatherdata->weight) ? $fatherdata->weight : '' }}"
                                                                    oninput="handleSubmitTwo('fatherweightError',this,3)" />
                                                                <div class="placeholder">Weight (KG)</div>
                                                                <span id="fatherweightError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endforeach

                                                    @foreach ($prposaralldata as $data)
                                                    @if ($data->name == 'fatherinlaw')
                                                    @php
                                                    $fatherinlawdata = JourneyUsers::where(
                                                    'proposalid',
                                                    Auth::id(),
                                                    )
                                                    ->where('relation', $data->name)
                                                    ->first();
                                                    @endphp
                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                                            <label for="field1"
                                                                class="form-label">Father-in-law:</label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="fatherlawname"
                                                                    class="input form-control"
                                                                    id="fatherlawname" autocomplete="off"
                                                                    spellcheck="false" maxlength="25"
                                                                    value="{{ !empty($fatherinlawdata->name) ? $fatherinlawdata->name : '' }}"
                                                                    oninput="clearErrorTwo('fatherlawnameError')" />
                                                                <div class="placeholder">Enter Father-in-law's
                                                                    Full
                                                                    Name</div>
                                                                <span id="fatherlawnameError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <div class="input-group1 datepickerdiv">
                                                                    <input type="text"
                                                                        name="fatherlawdob"
                                                                        class="input form-control datepicker"
                                                                        id="fatherlawdob" autocomplete="off"
                                                                        spellcheck="false" maxlength="10"
                                                                        value="{{ !empty($fatherinlawdata->dob) ? $fatherinlawdata->dob : '' }}"
                                                                        oninput="clearErrorTwo('fatherlawdobError')" />
                                                                    <div class="placeholder">D.O.B
                                                                        (DD-MM-YYYY)
                                                                    </div>
                                                                    <button class="btn calendarButton"
                                                                        type="button">
                                                                        <i
                                                                            class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                                <span id="fatherlawdobError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="fatherlawheight"
                                                                    class="input form-control single-digit"
                                                                    id="fatherlawheight" autocomplete="off"
                                                                    spellcheck="false" maxlength="1"
                                                                    placeholder="height(Feet)"
                                                                    value="{{ !empty($fatherinlawdata->height) ? $fatherinlawdata->height : '' }}"
                                                                    oninput="handleSubmitTwo('fatherlawheightError',this,1)">
                                                                {{-- <select name="fatherlawheight"
                                                                            id="fatherlawheight"
                                                                            class="input form-control"
                                                                            oninput="clearErrorTwo('fatherlawheightError')">
                                                                            <option value="">Select</option>
                                                                            <option value="1">1</option>
                                                                            <option value="2">2</option>
                                                                            <!-- Include all options -->
                                                                        </select> --}}
                                                                <div class="placeholder">Height (Feet)</div>
                                                                <span id="fatherlawheightError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="fatherlawinches"
                                                                    class="input form-control double-digit"
                                                                    id="fatherlawinches" autocomplete="off"
                                                                    spellcheck="false" maxlength="2"
                                                                    placeholder="height(Inches)"
                                                                    value="{{ !empty($fatherinlawdata->inch) ? $fatherinlawdata->inch : '' }}"
                                                                    oninput="handleSubmitTwo('fatherlawinchesError',this,2)">
                                                                {{-- <select name="fatherlawinches"
                                                                            id="fatherlawinches"
                                                                            class="input form-control"
                                                                            oninput="clearErrorTwo('fatherlawinchesError')">
                                                                            <option value="">Select</option>
                                                                            <option value="1 inch">1 inch</option>
                                                                            <option value="1">1</option>
                                                                            <!-- Include all options -->
                                                                        </select> --}}
                                                                <div class="placeholder">Height (Inches)</div>
                                                                <span id="fatherlawinchesError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                            <div class="input-container mb-2">
                                                                <input type="text"
                                                                    class="input form-control"
                                                                    id="fatherlawweight"
                                                                    name="fatherlawweight"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="3"
                                                                    value="{{ !empty($fatherinlawdata->weight) ? $fatherinlawdata->weight : '' }}"
                                                                    oninput="handleSubmitTwo('fatherlawweightError',this,3)" />
                                                                <div class="placeholder">Weight (KG)</div>
                                                                <span id="fatherlawweightError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endforeach

                                                    @foreach ($prposaralldata as $data)
                                                    @if ($data->name == 'motherinlaw')
                                                    @php
                                                    $motherinlawdata = JourneyUsers::where(
                                                    'proposalid',
                                                    Auth::id(),
                                                    )
                                                    ->where('relation', $data->name)
                                                    ->first();
                                                    @endphp
                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                                            <label for="field1"
                                                                class="form-label">Mother-in-law:</label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="Motherlawname"
                                                                    class="input form-control"
                                                                    id="Motherlawname" autocomplete="off"
                                                                    spellcheck="false" maxlength="25"
                                                                    value="{{ !empty($motherinlawdata->name) ? $motherinlawdata->name : '' }}"
                                                                    oninput="clearErrorTwo('MotherlawnameError')" />
                                                                <div class="placeholder">Enter Mother-in-law's
                                                                    Full
                                                                    Name</div>
                                                                <span id="MotherlawnameError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <div class="input-group1 datepickerdiv">
                                                                    <input type="text"
                                                                        name="Motherlawdob"
                                                                        class="input form-control datepicker"
                                                                        id="Motherlawdob" autocomplete="off"
                                                                        spellcheck="false" maxlength="10"
                                                                        value="{{ !empty($motherinlawdata->dob) ? $motherinlawdata->dob : '' }}"
                                                                        oninput="clearErrorTwo('MotherlawdobError')" />
                                                                    <div class="placeholder">D.O.B
                                                                        (DD-MM-YYYY)
                                                                    </div>
                                                                    <button class="btn calendarButton"
                                                                        type="button">
                                                                        <i
                                                                            class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                                <span id="MotherlawdobError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="Motherlawheight"
                                                                    class="input form-control single-digit"
                                                                    id="Motherlawheight" autocomplete="off"
                                                                    spellcheck="false" maxlength="1"
                                                                    placeholder="height(Feet)"
                                                                    value="{{ !empty($motherinlawdata->height) ? $motherinlawdata->height : '' }}"
                                                                    oninput="handleSubmitTwo('MotherlawheightError',this,1)">
                                                                {{-- <select name="Motherlawheight"
                                                                            id="Motherlawheight"
                                                                            class="input form-control"
                                                                            onchange="clearErrorTwo('MotherlawheightError')">
                                                                            <option value="">Select</option>
                                                                            <option value="1">1</option>
                                                                            <option value="2">2</option>
                                                                            <!-- Include all options -->
                                                                        </select> --}}
                                                                <div class="placeholder">Height (Feet)</div>
                                                                <span id="MotherlawheightError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="Motherlawinches"
                                                                    class="input form-control double-digit"
                                                                    id="Motherlawinches" autocomplete="off"
                                                                    spellcheck="false" maxlength="2"
                                                                    placeholder="height(Feet)"
                                                                    value="{{ !empty($motherinlawdata->inch) ? $motherinlawdata->inch : '' }}"
                                                                    oninput="handleSubmitTwo('MotherlawinchesError',this,2)">
                                                                {{-- <select name="Motherlawinches"
                                                                            id="Motherlawinches"
                                                                            class="input form-control"
                                                                            onchange="clearErrorTwo('MotherlawinchesError')">
                                                                            <option value="">Select</option>
                                                                            <option value="1 inch">1 inch</option>
                                                                            <option value="1">1</option>
                                                                            <!-- Include all options -->
                                                                        </select> --}}
                                                                <div class="placeholder">Height (Inches)</div>
                                                                <span id="MotherlawinchesError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="Motherlawweight"
                                                                    class="input form-control"
                                                                    id="Motherlawweight" autocomplete="off"
                                                                    spellcheck="false" maxlength="3"
                                                                    value="{{ !empty($motherinlawdata->weight) ? $motherinlawdata->weight : '' }}"
                                                                    oninput="handleSubmitTwo('MotherlawweightError',this,3)" />
                                                                <div class="placeholder">Weight (KG)</div>
                                                                <span id="MotherlawweightError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endforeach

                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                                                            <label for="field1"
                                                                class="form-label">Nominee:</label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="nomineename"
                                                                    class="input form-control" id="nomineename"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="25"
                                                                    value="{{ !empty($nominee->name) ? $nominee->name : '' }}"
                                                                    oninput="clearErrorTwo('nomineenameError')" />
                                                                <div class="placeholder">Enter Nominee Full Name</div>
                                                                <span id="nomineenameError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <div class="input-group1 datepickerdiv">
                                                                    <input type="text" name="nomineedob"
                                                                        class="input form-control datepicker"
                                                                        id="nomineedob" autocomplete="off"
                                                                        spellcheck="false" maxlength="10"
                                                                        value="{{ !empty($nominee->dob) ? $nominee->dob : '' }}"
                                                                        oninput="clearErrorTwo('nomineedobError')" />
                                                                    <div class="placeholder">D.O.B (DD-MM-YYYY)</div>
                                                                    <button class="btn calendarButton"
                                                                        type="button">
                                                                        <i class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                                <span id="nomineedobError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <div class="input-container mb-2">
                                                                <select name="nomineerelation" id="nomineerelation"
                                                                    class="input form-control"
                                                                    oninput="clearErrorTwo('nomineerelationError')">
                                                                    <option value="">Select </option>
                                                                    <option value="Spouse"
                                                                        {{ !empty($nominee->relation) && $nominee->relation == 'Spouse' ? 'selected' : '' }}>
                                                                        Spouse</option>
                                                                    <option value="Father"
                                                                        {{ !empty($nominee->relation) && $nominee->relation == 'Father' ? 'selected' : '' }}>
                                                                        Father</option>
                                                                    <option value="Mother"
                                                                        {{ !empty($nominee->relation) && $nominee->relation == 'Mother' ? 'selected' : '' }}>
                                                                        Mother</option>
                                                                    <option value="Brother"
                                                                        {{ !empty($nominee->relation) && strpos($nominee->relation, 'Brother') !== false ? 'selected' : '' }}>
                                                                        Brother</option>
                                                                    <option value="Sister"
                                                                        {{ !empty($nominee->relation) && $nominee->relation == 'Sister' ? 'selected' : '' }}>
                                                                        Sister</option>
                                                                    <option value="Son"
                                                                        {{ !empty($nominee->relation) && $nominee->relation == 'Son' ? 'selected' : '' }}>
                                                                        Son</option>
                                                                    <option value="Daughter"
                                                                        {{ !empty($nominee->relation) && $nominee->relation == 'Daughter' ? 'selected' : '' }}>
                                                                        Daughter</option>
                                                                </select>

                                                                <div class="placeholder">Relation</div>
                                                                <span id="nomineerelationError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- <button type="button" id="next2" class="Previous prev-step">Previous</button> -->
                                                    <button type="button" class="medicalquestions next-step"
                                                        onclick="validateFormStepTwo()">
                                                        Proceed to medical questions
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="step step-3">
                                                <form action="" id="formStepThree">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-mb-12 col-sm-12 mb-2">
                                                            <h3>Help us know the medical condition, if any</h3>
                                                        </div>
                                                        <div class="col-lg-12 col-mb-12 col-sm-12 mb-2">

                                                            <table>
                                                                <thead class="">
                                                                    <tr>
                                                                        <td>
                                                                            <label for="field3"
                                                                                class="form-label">Medical
                                                                                History:</label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="medicalhead">
                                                                            <p> 1. Does any person(s) to be insured
                                                                                currently or in past Diagnosed/Suffered/
                                                                                Treated/Taken Medication for any medical
                                                                                condition? </p>
                                                                        </td>
                                                                        <td class="vertical">
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    id="medicalonetoggle"
                                                                                    name="medicalonetoggle">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                </thead>

                                                                <tbody class="medicalonesub" style="display: none;">
                                                                    <tr>
                                                                        <td class="questionfont"
                                                                            style="margin-bottom: 20px!important;">
                                                                            <p class="quespera"> 1.1 Cancer or Tumor
                                                                                of any kind?</p>
                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="cancer" name="cancer">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>

                                                                    <tr id="cancermain">
                                                                        <td class="cancersub"
                                                                            style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.2 Any heart related or circulatory
                                                                                system
                                                                                disorders?
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="heart">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="heartmain">
                                                                        <td class="heartsub" style="display: none;">
                                                                            <div class="row">
                                                                            </div>
                                                                        </td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.3 Hypertension/High Blood
                                                                                Pressure/Cholesterol disorder?
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="hypertension">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="hypertensionmain">
                                                                        <td class="hypertensionsub"
                                                                            style="display: none;">
                                                                            <div class="row">
                                                                            </div>
                                                                        </td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.4 Breathing/Respiratory issues
                                                                                (E.g.TB,Asthma,etc.) ?
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="breathing">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="breathingmain">
                                                                        <td class="breathingsub"
                                                                            style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.5 Endocrine disorders(E.g.Thyroid
                                                                                related
                                                                                disoders,etc)?
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="endocrine">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="endocrinemain">
                                                                        <td class="endocrinesub"
                                                                            style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.6 Diabetes/High blood sugar?
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="diabetes">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="diabetesmain">
                                                                        <td class="diabetessub"
                                                                            style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.7 Muscles or Nervous system related
                                                                                disorder or Stroke/Epilepsy/ Paralysis
                                                                                of
                                                                                other brain related disorders?
                                                                            </p>

                                                                        </td>
                                                                        <td class="vertical">
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="muscles">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="musclesmain">
                                                                        <td class="musclessub"
                                                                            style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.8 Liver/gallbladder or any other
                                                                                Gastro-Intestinal Disease?
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="liver">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="livermain">
                                                                        <td class="liversub" style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.9 Kidney failure/Stone/ Dialysis/
                                                                                Gynaecological/ Prostate disease?
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="kidney">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="kidneymain">
                                                                        <td class="kidneysub"
                                                                            style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.10 Auto-immune or Blood related
                                                                                disorders
                                                                                (Rheumatoid arthritis,
                                                                                Thalessemia,etc.)?
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="auto">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="automain">
                                                                        <td class="autosub" style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="questionfont"
                                                                            style="margin-bottom: 20px!important;">
                                                                            <p class="quespera"> 1.11 Any Congenital
                                                                                Disorder?</p>
                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="congenital"
                                                                                    name="congenital">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>

                                                                    <tr id="congenitalmain">
                                                                        <td class="congenitalsub"
                                                                            style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="questionfont"
                                                                            style="margin-bottom: 20px!important;">
                                                                            <p class="quespera"> 1.12 HIV/ AIDS/ STD?
                                                                            </p>
                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="hivaids" name="hivaids">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>

                                                                    <tr id="hivaidsmain">
                                                                        <td class="hivaidssub"
                                                                            style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>





                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.13 Any other disease/health
                                                                                adversity/injury/ condition/treatment
                                                                                not
                                                                                mentioned above?
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="any">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="anymain">
                                                                        <td class="anysub" style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.14 Has any of the Proposed to be
                                                                                Insured
                                                                                consulted/taken treatment or recommended
                                                                                to
                                                                                take investigations/ medication/ surgery
                                                                                other than for childbirth/ minor
                                                                                injuries?
                                                                            </p>

                                                                        </td>
                                                                        <td class="vertical">
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="has">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="hasmain">
                                                                        <td class="hassub" style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.15 Has any of the Proposed to be
                                                                                Insured
                                                                                been hospitalized or has been under any
                                                                                prolonged treatment for any
                                                                                illness/injury
                                                                                or has undergone surgery other than for
                                                                                childbirth/minor injuries?
                                                                            </p>

                                                                        </td>
                                                                        <td class="vertical">
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="hasany">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="hasanymain">
                                                                        <td class="hasanysub"
                                                                            style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                            <table>
                                                                <thead class="medicaltwo">
                                                                    <tr>
                                                                        <td class="medicalhead">
                                                                            <p class="quespera">
                                                                                2. Details of previous or existing
                                                                                health
                                                                                insurance ?
                                                                            </p>

                                                                        </td>
                                                                        <td class="vertical">
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    id="medicalonetoggletwo">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>

                                                                </thead>

                                                                <tbody class="medicalonesubtwo"
                                                                    style="display: none;">
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                2.1 Has any of the new person(s) to be
                                                                                insured ever filed a claim with their
                                                                                current / previous insurer?
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="insurer">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="insurermain">
                                                                        <td class="insurersub"
                                                                            style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                2.2 Has any proposal for Health
                                                                                Insurance of
                                                                                the new person(s) to be insured, been
                                                                                declined, cancelled or charged a higher
                                                                                premium ?
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="premium">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="premiummain">
                                                                        <td class="premiumsub"
                                                                            style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                2.3 Is any of the person(s) to be
                                                                                insured,
                                                                                already covered under any other health
                                                                                insurance policy of Care Health
                                                                                Insurance ?
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="insurance">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="insurancemain">
                                                                        <td class="insurancesub"
                                                                            style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                2.4 Have any of the above mentioned
                                                                                person(s) to be insured been diagnosed /
                                                                                hospitalized for any illness / injury
                                                                                during the last 48 months?
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="diagnosed">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="diagnosedmain">
                                                                        <td class="diagnosedsub"
                                                                            style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-lg-12 col-mb-12 col-sm-12 mb-2">

                                                            <table>
                                                                <thead class="lifestyletwo">
                                                                    <tr>
                                                                        <td>
                                                                            <label for="field3"
                                                                                class="form-label">Lifestyle
                                                                                History:</label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="lifestylehead">
                                                                            <p class="quespera">
                                                                                1. Personal habit of smoking/
                                                                                alcohol/gutkha/ tobacco/paan?
                                                                            </p>

                                                                        </td>
                                                                        <td class="vertical">
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    id="lifestyletoggletwo">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>

                                                                </thead>

                                                                <tbody class="lifestylesub" style="display: none;">
                                                                    {{-- <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.1 Smoking Cigarettes
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="cigarettes">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr> --}}
                                                                    <tr id="cigarettesmain">
                                                                        <td class="cigarettessub"
                                                                            style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>

                                                                    </tr>
                                                                    {{-- <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.2 Smoking Bidis
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="bidis">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="bidismain">
                                                                        <td class="bidissub" style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.3 Tobacco, Gutkha or Pan
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-1">
                                                                                <input type="checkbox"
                                                                                    class="Pan">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="panmain">
                                                                        <td class="pansub" style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.4 Whisky
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="whisky">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="whiskymain">
                                                                        <td class="whiskysub"
                                                                            style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.5 Wine
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-3">
                                                                                <input type="checkbox"
                                                                                    class="wine">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="winemain">
                                                                        <td class="winesub" style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.6 Beer
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-1">
                                                                                <input type="checkbox"
                                                                                    class="beer">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="beermain">
                                                                        <td class="beersub" style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td class="questionfont">
                                                                            <p class="quespera">
                                                                                1.7 Any other type of Drugs
                                                                            </p>

                                                                        </td>
                                                                        <td>
                                                                            <label class="switch mb-1">
                                                                                <input type="checkbox"
                                                                                    class="drugs">
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="drugsmain">
                                                                        <td class="drugssub" style="display: none;">
                                                                            <div class="row">

                                                                            </div>
                                                                        </td>

                                                                    </tr> --}}
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <span class="questionfont mb-2"> <input type="checkbox"
                                                                id="agreeTerms">
                                                            I hereby agree to the <a href="#">Terms &
                                                                Conditions</a> of the purchase of this policy. *</span>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                                                        <span class="questionfont"> <input type="checkbox"
                                                                id="addStandingInstruction">
                                                            I would also like to add Standing Instruction on my credit
                                                            card for automatic future renewal premiums. <a
                                                                href="#">Terms & Conditions.</a></span>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                                                        <span class="questionfont"> <input type="checkbox"
                                                                id="emiInstruction">
                                                            I would like to opt for the EMI (Equated Monthly
                                                            Installment) option for payment of premiums.</span>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                                                        <span class="questionfont"> <input type="checkbox"
                                                                id="debitInstruction"> I authorize the auto-debit of
                                                            premiums from my bank account for automatic payment.</span>
                                                    </div>
                                                    <!-- <button type="button" class="Previous prev-step">Previous</button> -->
                                                    <button type="button" class="medicalquestions next-step"
                                                        id="medicalquestions"
                                                        onclick="validateFormStepThree()">Next</button>
                                                </form>
                                            </div>

                                            <div class="step step-4">
                                                <form action="" id="formStepFour">
                                                    <h3>Proposal Summary</h3>
                                                    <div class="row">
                                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                                            <section class="hightlight mb-3">
                                                                <div class="row">
                                                                    <div class="col-lg-10 col-md-10 col-sm-6">
                                                                        <h6 class="proposerhead">Products Details</h6>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-sm-6">
                                                                        <button type="button" class="edit"
                                                                            style="float: right;">Edit</button>
                                                                        <!-- <a href="#" class="Previous">Edit</a> -->
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                                                        <div class="Productsimg">
                                                                            <img src="{{ config('constant.BASE_URL') }}front/images/digibima-banner.png"
                                                                                alt="">
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="col-lg-7 col-md-7 col-sm-12 text-center mt-2">
                                                                        <h4>Care Supreme - for
                                                                            ₹ <span
                                                                                id="headtotal">{{ session()->get('premium') }}</span>
                                                                            Coverage</h4>
                                                                        <button type="button" class="Previous">View
                                                                            All benefit</button>
                                                                        <!-- <a href="#" class="Previous">View All benefit </a> -->
                                                                    </div>
                                                                </div>

                                                            </section>
                                                            <section class="hightlight mb-3">
                                                                <div class="row">
                                                                    <div class="col-lg-10 col-md-10 col-sm-6">
                                                                        <h6 class="proposerhead">Proposer
                                                                            Details</h6>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-sm-6">
                                                                        <button type="button" class="edit"
                                                                            style="float: right;"
                                                                            onclick="gotoStepOne()">Edit</button>

                                                                        <!-- <a href="#" class="Previous">Edit</a> -->
                                                                    </div>
                                                                </div>
                                                                <div class="row" id="proposerdetails">

                                                                </div>


                                                            </section>
                                                            <section class="hightlight mb-3">
                                                                <div class="row">
                                                                    <div class="col-lg-10 col-md-10 col-sm-6">
                                                                        <h6 class="proposerhead">Address</h6>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-sm-6">
                                                                        <button type="button" class="edit"
                                                                            style="float: right;"
                                                                            onclick="gotoStepOne()">Edit</button>
                                                                        <!-- <a href="#" class="Previous">Edit</a> -->
                                                                    </div>
                                                                </div>
                                                                <div class="row" id="proposeraddress">

                                                                </div>

                                                            </section>
                                                            <section class="hightlight mb-3">
                                                                <div class="row">
                                                                    <div class="col-lg-10 col-md-10 col-sm-6">
                                                                        <h6 class="proposerhead">
                                                                            Insured Members Details</h6>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-sm-6">
                                                                        <button type="button" class="edit"
                                                                            style="float: right;"
                                                                            onclick="gotoStepTwo()">Edit</button>
                                                                        <!-- <a href="#" class="Previous">Edit</a> -->
                                                                    </div>
                                                                </div>
                                                                <div class="row" id="insuredetails">

                                                                </div>

                                                            </section>
                                                            <section class="hightlight mb-3">
                                                                <div class="row">
                                                                    <div class="col-lg-10 col-md-10 col-sm-6">
                                                                        <h6 class="proposerhead">Nominee Details</h6>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-sm-6">
                                                                        <button type="button" class="edit"
                                                                            style="float: right;"
                                                                            onclick="gotoStepTwo()">Edit</button>
                                                                        <!-- <a href="#" class="Previous">Edit</a> -->
                                                                    </div>
                                                                </div>
                                                                <div class="row" id="nomineedetails">

                                                                </div>

                                                            </section>
                                                            <section class="hightlight mb-3">
                                                                <div class="row">
                                                                    <div class="col-lg-10 col-md-10 col-sm-6">
                                                                        <h6 class="proposerhead">Health Details</h6>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-sm-6">
                                                                        <button type="button" class="edit"
                                                                            style="float: right;"
                                                                            onclick="gotoStepThree()">Edit</button>
                                                                        <!-- <a href="#" class="Previous">Edit</a> -->
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <h6 class="proposerdata">Medical History</h6>
                                                                    <div id="medicalhistory"
                                                                        class="col-lg-12 col-md-12 col-sm-12">

                                                                    </div>
                                                                    <div id="medicalhistoryTwo"
                                                                        class="col-lg-12 col-md-12 col-sm-12">

                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <h6 class="proposerdata">Lifestyle History
                                                                    </h6>
                                                                    <div id="lifestylehistory"
                                                                        class="col-lg-12 col-md-12 col-sm-12 mt-2">

                                                                    </div>

                                                                </div>

                                                            </section>
                                                        </div>
                                                    </div>
                                                    <!-- <button type="button" class="Previous prev-step">Previous</button>
                                                <button type="submit" class="btn btn-success">Submit</button> -->
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Left Col End -->

                <!-- Right Col Start -->
                <div class="col-md-4 col-lg-4 col-xl-4 ">
                    @include('front.health.vendors.caresupreme.journey.addon')

                </div>
                <!-- Right Col End -->

            </div>
        </div>
    </section>

    <div class="modal fade" id="featuremodal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-3">
            <div class="modal-content rounded-3">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="exampleModalLabel">Plan Features</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('front.partial.feature')
                </div>
                <div class="modal-footer border-0">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="premimumchange" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-3">
            <div class="modal-content rounded-3">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="exampleModalLabel">Premimum Updated!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <p class="featureheading mb-2">Based on the given birthdate of insured members, premium of the
                            policy has changed</p>
                        <div class="col-lg-12 ">
                            <form id="selfagechangepre">
                                <div class="d-flex mb-3"
                                    style="background-color: aliceblue; padding: 30px;    border: 1px dashed;">
                                    <span id="userprofile"><i class="fa-regular fa-user"></i></span>
                                    <h6 class="mt-2">{{ Auth::user()->name }}</h6>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 mb-2">
                                        <p>Prev. age: <strong id="self-preage">`${selfAge}` years</strong></p>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <p>New age: <strong id="self-newage">43 years </strong></p>
                                    </div>
                                    <div class="col-lg-12 mb-2">
                                        <p>Old premium: <strong>₹{{ session()->get('premium') }}</strong></p>
                                        <p>New premium: <strong>₹10,490</strong> </p>
                                    </div>
                                    <div class="col-lg-12 mb-2">
                                        <p>I agree with the change in premium</p>
                                    </div>
                                </div>
                                <button id="applypremium"></button>
                            </form>

                            <div class="row">
                                <div class="col-lg-12 mb-2 text-center">
                                    <button class="btnsm" id="cancelpremium" style="margin-right: 10px;">Cancel</button>
                                    <button class="btnsm" id="updatechangepre">Accept</button>
                                </div>
                            </div>
                        </div>






                    </div>
                </div>
                <div class="modal-footer border-0"></div>
            </div>
        </div>
    </div>

    <!-- enterOtp    -->
    <div id="enterOtp" class="modal fade">
        <div class="modal-dialog modal-confirm ">
            <form method="" enctype="multipart/form-data">
                <div class="modal-content px-4 modal-content">
                    <div class="modal-header flex-column">
                        <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <h5 class="text-left text-dark" style="text-align: left!important"><b>OTP
                                    Verification</b></h5>
                            <p class="text-left mb-3 text-dark" style="text-align: left!important">Enter the OTP you
                                received at <br><b>ga******gmail.com</b></p>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-8 col-xs-12 text-left">
                            <div class="input-container mb-2">
                                <input type="text" name="enterotp" class="input form-control" id="otpverifiy"
                                    autocomplete="off" spellcheck="false" maxlength="6">
                                <div class="placeholder">Enter Otp</div>
                                <span class="error-message" id="proposernameError"></span>
                            </div>
                        </div>
                    </div>


                    <div class="modal-footer justify-content-center">
                        <button class="btnsm">Verify</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-3">
            <div class="modal-content rounded-3">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="exampleModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="errorMessage"></p>
                </div>
                <div class="modal-footer border-0">

                </div>
            </div>
        </div>
    </div>
   
    <!-- enterOtp   -->
    @include('front.partial.chatwidget')
    @include('front.partial.footer')
    @include('front.partial.jslink')
    @include('front.health.vendors.caresupreme.journey.healthaddon')
    <!-- Bootstrap JavaScript bundle -->
    <!-- Custom JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function handlePlaceholderAnimation(input, placeholder) {
                // Function to update placeholder based on input value
                function updatePlaceholder() {
                    if (input.value.trim() !== "") {
                        placeholder.classList.add("active");
                    } else {
                        placeholder.classList.remove("active");
                    }
                }

                // Click event on placeholder
                placeholder.addEventListener("click", () => {
                    input.focus();
                });

                // Focus and blur events on input
                input.addEventListener("focus", () => {
                    placeholder.classList.add("active");
                });

                input.addEventListener("blur", () => {
                    updatePlaceholder();
                });

                // Input event to handle value changes
                input.addEventListener("input", updatePlaceholder);

                // Initial check for value on page load
                updatePlaceholder();
            }

            // Select all input elements
            const inputs = document.querySelectorAll(".input, .input1");

            // Iterate over each input
            inputs.forEach((input) => {
                const placeholder = input
                    .nextElementSibling; // Assuming placeholder is the next sibling element

                // Apply placeholder animation for current input
                handlePlaceholderAnimation(input, placeholder);
            });
        });



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
            const genderValue = $("#mr_ms_gender").val();
            if (genderValue) {
                $("#mr_ms_gender").siblings('.placeholder').addClass("active");
            }
        });
        document.getElementById('sameAddress').addEventListener('change', function() {
            const isChecked = this.checked;
            const inputs = document.querySelectorAll('#sameAddsection input');
            const placeholders = document.querySelectorAll('#sameAddsection .placeholder');
            if (isChecked) {
                inputs.forEach(input => {
                    input.value = '';
                });
                placeholders.forEach(placeholder => {
                    placeholder.classList.remove('active');
                });
            }
        });


        var validOk;
        $(document).ready(function() {
            function updatePlaceholder(input, placeholder) {
                if (input.value.trim() !== "") {
                    placeholder.classList.add("active");
                } else {
                    placeholder.classList.remove("active");
                }
            }
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

        function handleCheckboxChange(clickedId) {
            disableCheckbox(clickedId);
            var checkbox = document.getElementById(clickedId);
            var detailsDivId = '';

            // Determine the details section ID based on clicked checkbox
            switch (clickedId) {
                case 'panCard':
                    detailsDivId = 'pancardDetails';
                    break;
                case 'aadhaarCard':
                    detailsDivId = 'aadharcardDetails';
                    break;
                case 'otherCard':
                    detailsDivId = 'othercardDetails';
                    break;
                default:
                    break;
            }

            // Toggle the display of the details section
            if (checkbox.checked) {
                document.getElementById(detailsDivId).style.display = 'block';
            } else {
                document.getElementById(detailsDivId).style.display = 'none';
            }

            // Uncheck other checkboxes and hide their details sections
            var checkboxes = document.querySelectorAll('input[name="customerkyc"]');
            checkboxes.forEach(function(box) {
                var boxId = box.id;
                if (boxId !== clickedId) {
                    box.checked = false;
                    switch (boxId) {
                        case 'panCard':
                            document.getElementById('pancardDetails').style.display = 'none';
                            document.getElementById('personalDetail').style.display = 'none';
                            break;
                        case 'aadhaarCard':
                            document.getElementById('aadharcardDetails').style.display = 'none';
                            document.getElementById('personalDetail').style.display = 'none';
                            break;
                        case 'otherCard':
                            document.getElementById('othercardDetails').style.display = 'none';
                            document.getElementById('personalDetail').style.display = 'none';
                            break;
                        default:
                            break;
                    }
                }
            });
        }

        function disableCheckbox(clickedId) {
            const checkboxes = ['panCard', 'aadhaarCard', 'otherCard'];
            const checkbox = document.getElementById(clickedId);
            const isChecked = checkbox.checked;
            // checkboxes.forEach(id => {
            //     const box = document.getElementById(id);
            //     const parentDiv = box.closest('.parent-div');
            //     box.disabled = isChecked && id !== clickedId;
            //     if (parentDiv) {
            //         parentDiv.style.opacity = isChecked && id !== clickedId ? 0.5 : 1;
            //         parentDiv.style.pointerEvents = isChecked && id !== clickedId ? 'none' : 'auto';
            //     }
            // });
        }
        $(document).ready(function() {
            $('#identityTypeProof').change(function() {
                var identityType = $(this).val();

                // Hide all identity type sections first
                $('.identityTypeAadhar, .identityTypePan, .identityTypePassport, .identityTypeDL, .identityTypeVoterID, .identityTypeForm60')
                    .hide();
                if (identityType === 'aadhar') {
                    $('.identityTypeAadhar').show();
                } else if (identityType === 'pan') {
                    $('.identityTypePan').show();
                } else if (identityType === 'passport') {
                    $('.identityTypePassport').show();
                } else if (identityType === 'DrivingLicense') {
                    $('.identityTypeDL').show();
                } else if (identityType === 'VoterID') {
                    $('.identityTypeVoterID').show();
                } else if (identityType === 'Form60') {
                    $('.identityTypeForm60').show();
                }
            });
        });
        $(document).ready(function() {
            $('#addressTypeProof').change(function() {
                var identityType = $(this).val();

                // Hide all identity type sections first
                $('.addressTypeAadhar, .addressTypePan, .addressTypePassport, .addressTypeDL, .addressTypeVoterID, .addressTypeForm60')
                    .hide();
                if (identityType === 'aadhar') {
                    $('.addressTypeAadhar').show();
                } else if (identityType === 'pan') {
                    $('.addressTypePan').show();
                } else if (identityType === 'passport') {
                    $('.addressTypePassport').show();
                } else if (identityType === 'DrivingLicense') {
                    $('.addressTypeDL').show();
                } else if (identityType === 'VoterID') {
                    $('.addressTypeVoterID').show();
                } else if (identityType === 'Form60') {
                    $('.addressTypeForm60').show();
                }
            });
        });

        const parentDivs = document.querySelectorAll('.parent-div');
        parentDivs.forEach(div => {
            div.addEventListener('click', (event) => {
                const checkbox = div.querySelector('input[name="customerkyc"]');
                if (event.target !== checkbox) {
                    checkbox.checked = !checkbox.checked;
                    handleCheckboxChange(checkbox.id);
                }
            });
        });

        // 1 //
        $(document).ready(function() {
            $('#medicalonetoggle').change(function() {
                if ($(this).is(':checked')) {
                    $('.medicalonesub').css('display',
                        'table-row-group');
                } else {
                    $('.medicalonesub').css('display', 'none');
                }
            });
        });

        // 1 //

        // 2 //
        $(document).ready(function() {
            $('#medicalonetoggletwo').change(function() {
                if ($(this).is(':checked')) {
                    $('.medicalonesubtwo').css('display',
                        'table-row-group');
                } else {
                    $('.medicalonesubtwo').css('display', 'none');
                }
            });
        });

        // 2 //
        // 3 //
        $(document).ready(function() {
            $('#lifestyletoggletwo').change(function() {
                if ($(this).is(':checked')) {
                    $('.lifestylesub').css('display',
                        'table-row-group');
                    $('.cigarettessub').css('display',
                        'table-cell');
                } else {
                    $('.lifestylesub').css('display', 'none');
                }
            });
        });

        // 3 //

        // Cancer or Tumor //

        $(document).ready(function() {
            $('.cancer').change(function() {
                if ($(this).is(':checked')) {
                    $('.cancersub').css('display', 'block');
                } else {
                    $('.cancersub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.cancermain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#cancer1').css('display', 'block');
                } else {
                    $('#cancer1').css('display', 'none');
                }
            });

            $('.cancermain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#cancer2').css('display', 'block');
                } else {
                    $('#cancer2').css('display', 'none');
                }
            });
            $('.cancermain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#cancer3').css('display', 'block');
                } else {
                    $('#cancer3').css('display', 'none');
                }
            });
            $('.cancermain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#cancer4').css('display', 'block');
                } else {
                    $('#cancer4').css('display', 'none');
                }
            });
        });

        //Cancer or Tumor //
        //heart //
        $(document).ready(function() {
            $('.heart').change(function() {
                if ($(this).is(':checked')) {
                    $('.heartsub').css('display', 'block');
                } else {
                    $('.heartsub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.heartmain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#heart1').css('display', 'block');
                } else {
                    $('#heart1').css('display', 'none');
                }
            });

            $('.heartmain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#heart2').css('display', 'block');
                } else {
                    $('#heart2').css('display', 'none');
                }
            });
            $('.heartmain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#heart3').css('display', 'block');
                } else {
                    $('#heart3').css('display', 'none');
                }
            });
            $('.heartmain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#heart4').css('display', 'block');
                } else {
                    $('#heart4').css('display', 'none');
                }
            });
        });

        //heart //

        // hypertension //
        $(document).ready(function() {
            $('.hypertension').change(function() {
                if ($(this).is(':checked')) {
                    $('.hypertensionsub').css('display', 'block');
                } else {
                    $('.hypertensionsub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.hypertensionmain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#hypertension1').css('display', 'block');
                } else {
                    $('#hypertension1').css('display', 'none');
                }
            });

            $('.hypertensionmain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#hypertension2').css('display', 'block');
                } else {
                    $('#hypertension2').css('display', 'none');
                }
            });
            $('.hypertensionmain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#hypertension3').css('display', 'block');
                } else {
                    $('#hypertension3').css('display', 'none');
                }
            });
            $('.hypertensionmain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#hypertension4').css('display', 'block');
                } else {
                    $('#hypertension4').css('display', 'none');
                }
            });
        });
        // hypertension //

        // breathing //
        $(document).ready(function() {
            $('.breathing').change(function() {
                if ($(this).is(':checked')) {
                    $('.breathingsub').css('display', 'block');
                } else {
                    $('.breathingsub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.breathingmain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#breathing1').css('display', 'block');
                } else {
                    $('#breathing1').css('display', 'none');
                }
            });

            $('.breathingmain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#breathing2').css('display', 'block');
                } else {
                    $('#breathing2').css('display', 'none');
                }
            });
            $('.breathingmain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#breathing3').css('display', 'block');
                } else {
                    $('#breathing3').css('display', 'none');
                }
            });
            $('.breathingmain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#breathing4').css('display', 'block');
                } else {
                    $('#breathing4').css('display', 'none');
                }
            });
        });
        // breathing //
        // endocrine //
        $(document).ready(function() {
            $('.endocrine').change(function() {
                if ($(this).is(':checked')) {
                    $('.endocrinesub').css('display', 'block');
                } else {
                    $('.endocrinesub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.endocrinemain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#endocrine1').css('display', 'block');
                } else {
                    $('#endocrine1').css('display', 'none');
                }
            });

            $('.endocrinemain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#endocrine2').css('display', 'block');
                } else {
                    $('#endocrine2').css('display', 'none');
                }
            });
            $('.endocrinemain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#endocrine3').css('display', 'block');
                } else {
                    $('#endocrine3').css('display', 'none');
                }
            });
            $('.endocrinemain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#endocrine4').css('display', 'block');
                } else {
                    $('#endocrine4').css('display', 'none');
                }
            });
        });
        // endocrine //
        // diabetes //
        $(document).ready(function() {
            $('.diabetes').change(function() {
                if ($(this).is(':checked')) {
                    $('.diabetessub').css('display', 'block');
                } else {
                    $('.diabetessub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.diabetesmain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#diabetes1').css('display', 'block');
                } else {
                    $('#diabetes1').css('display', 'none');
                }
            });

            $('.diabetesmain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#diabetes2').css('display', 'block');
                } else {
                    $('#diabetes2').css('display', 'none');
                }
            });
            $('.diabetesmain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#diabetes3').css('display', 'block');
                } else {
                    $('#diabetes3').css('display', 'none');
                }
            });
            $('.diabetesmain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#diabetes4').css('display', 'block');
                } else {
                    $('#diabetes4').css('display', 'none');
                }
            });
        });
        // diabetes //
        // muscles //
        $(document).ready(function() {
            $('.muscles').change(function() {
                if ($(this).is(':checked')) {
                    $('.musclessub').css('display', 'block');
                } else {
                    $('.musclessub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.musclesmain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#muscles1').css('display', 'block');
                } else {
                    $('#muscles1').css('display', 'none');
                }
            });

            $('.musclesmain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#muscles2').css('display', 'block');
                } else {
                    $('#muscles2').css('display', 'none');
                }
            });
            $('.musclesmain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#muscles3').css('display', 'block');
                } else {
                    $('#muscles3').css('display', 'none');
                }
            });
            $('.musclesmain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#muscles4').css('display', 'block');
                } else {
                    $('#muscles4').css('display', 'none');
                }
            });
        });
        // muscles //
        // liver //
        $(document).ready(function() {
            $('.liver').change(function() {
                if ($(this).is(':checked')) {
                    $('.liversub').css('display', 'block');
                } else {
                    $('.liversub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.livermain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#liver1').css('display', 'block');
                } else {
                    $('#liver1').css('display', 'none');
                }
            });

            $('.livermain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#liver2').css('display', 'block');
                } else {
                    $('#liver2').css('display', 'none');
                }
            });
            $('.livermain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#liver3').css('display', 'block');
                } else {
                    $('#liver3').css('display', 'none');
                }
            });
            $('.livermain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#liver4').css('display', 'block');
                } else {
                    $('#liver4').css('display', 'none');
                }
            });
        });
        // liver //
        // kidney //
        $(document).ready(function() {
            $('.kidney').change(function() {
                if ($(this).is(':checked')) {
                    $('.kidneysub').css('display', 'block');
                } else {
                    $('.kidneysub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.kidneymain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#kidney1').css('display', 'block');
                } else {
                    $('#kidney1').css('display', 'none');
                }
            });

            $('.kidneymain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#kidney2').css('display', 'block');
                } else {
                    $('#kidney2').css('display', 'none');
                }
            });
            $('.kidneymain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#kidney3').css('display', 'block');
                } else {
                    $('#kidney3').css('display', 'none');
                }
            });
            $('.kidneymain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#kidney4').css('display', 'block');
                } else {
                    $('#kidney4').css('display', 'none');
                }
            });
        });
        // kidney //
        // auto //
        $(document).ready(function() {
            $('.auto').change(function() {
                if ($(this).is(':checked')) {
                    $('.autosub').css('display', 'block');
                } else {
                    $('.autosub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.automain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#auto1').css('display', 'block');
                } else {
                    $('#auto1').css('display', 'none');
                }
            });

            $('.automain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#auto2').css('display', 'block');
                } else {
                    $('#auto2').css('display', 'none');
                }
            });
            $('.automain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#auto3').css('display', 'block');
                } else {
                    $('#auto3').css('display', 'none');
                }
            });
            $('.automain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#auto4').css('display', 'block');
                } else {
                    $('#auto4').css('display', 'none');
                }
            });
        });
        // auto //
        // any //
        $(document).ready(function() {
            $('.any').change(function() {
                if ($(this).is(':checked')) {
                    $('.anysub').css('display', 'block');
                } else {
                    $('.anysub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.anymain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#any1').css('display', 'block');
                } else {
                    $('#any1').css('display', 'none');
                }
            });

            $('.anymain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#any2').css('display', 'block');
                } else {
                    $('#any2').css('display', 'none');
                }
            });
            $('.anymain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#any3').css('display', 'block');
                } else {
                    $('#any3').css('display', 'none');
                }
            });
            $('.anymain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#any4').css('display', 'block');
                } else {
                    $('#any4').css('display', 'none');
                }
            });
        });
        // any //

        // congenital

        $(document).ready(function() {
            $('.congenital').change(function() {
                if ($(this).is(':checked')) {
                    $('.congenitalsub').css('display', 'block');
                } else {
                    $('.congenitalsub').css('display', 'none');
                }
            });
        });


        // congenital

        // hivaids

        $(document).ready(function() {
            $('.hivaids').change(function() {
                if ($(this).is(':checked')) {
                    $('.hivaidssub').css('display', 'block');
                } else {
                    $('.hivaidssub').css('display', 'none');
                }
            });
        });


        // hivaids



        // has //
        $(document).ready(function() {
            $('.has').change(function() {
                if ($(this).is(':checked')) {
                    $('.hassub').css('display', 'block');
                } else {
                    $('.hassub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.hasmain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#has1').css('display', 'block');
                } else {
                    $('#has1').css('display', 'none');
                }
            });

            $('.hasmain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#has2').css('display', 'block');
                } else {
                    $('#has2').css('display', 'none');
                }
            });
            $('.hasmain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#has3').css('display', 'block');
                } else {
                    $('#has3').css('display', 'none');
                }
            });
            $('.hasmain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#has4').css('display', 'block');
                } else {
                    $('#has4').css('display', 'none');
                }
            });
        });
        // has //
        // hasany //
        $(document).ready(function() {
            $('.hasany').change(function() {
                if ($(this).is(':checked')) {
                    $('.hasanysub').css('display', 'block');
                } else {
                    $('.hasanysub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.hasanymain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#hasany1').css('display', 'block');
                } else {
                    $('#hasany1').css('display', 'none');
                }
            });

            $('.hasanymain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#hasany2').css('display', 'block');
                } else {
                    $('#hasany2').css('display', 'none');
                }
            });
            $('.hasanymain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#hasany3').css('display', 'block');
                } else {
                    $('#hasany3').css('display', 'none');
                }
            });
            $('.hasanymain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#hasany4').css('display', 'block');
                } else {
                    $('#hasany4').css('display', 'none');
                }
            });
        });
        // hasany //
        // insurer //
        $(document).ready(function() {
            $('.insurer').change(function() {
                if ($(this).is(':checked')) {
                    $('.insurersub').css('display', 'block');
                } else {
                    $('.insurersub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.insurermain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#insurer1').css('display', 'block');
                } else {
                    $('#insurer1').css('display', 'none');
                }
            });

            $('.insurermain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#insurer2').css('display', 'block');
                } else {
                    $('#insurer2').css('display', 'none');
                }
            });
            $('.insurermain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#insurer3').css('display', 'block');
                } else {
                    $('#insurer3').css('display', 'none');
                }
            });
            $('.insurermain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#insurer4').css('display', 'block');
                } else {
                    $('#insurer4').css('display', 'none');
                }
            });
        });
        // insurer //
        // premium //
        $(document).ready(function() {
            $('.premium').change(function() {
                if ($(this).is(':checked')) {
                    $('.premiumsub').css('display', 'block');
                } else {
                    $('.premiumsub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.premiummain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#premium1').css('display', 'block');
                } else {
                    $('#premium1').css('display', 'none');
                }
            });

            $('.premiummain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#premium2').css('display', 'block');
                } else {
                    $('#premium2').css('display', 'none');
                }
            });
            $('.premiummain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#premium3').css('display', 'block');
                } else {
                    $('#premium3').css('display', 'none');
                }
            });
            $('.premiummain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#premium4').css('display', 'block');
                } else {
                    $('#premium4').css('display', 'none');
                }
            });
        });
        // premium //
        // insurance //
        $(document).ready(function() {
            $('.insurance').change(function() {
                if ($(this).is(':checked')) {
                    $('.insurancesub').css('display', 'block');
                } else {
                    $('.insurancesub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.insurancemain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#insurance1').css('display', 'block');
                } else {
                    $('#insurance1').css('display', 'none');
                }
            });

            $('.insurancemain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#insurance2').css('display', 'block');
                } else {
                    $('#insurance2').css('display', 'none');
                }
            });
            $('.insurancemain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#insurance3').css('display', 'block');
                } else {
                    $('#insurance3').css('display', 'none');
                }
            });
            $('.insurancemain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#insurance4').css('display', 'block');
                } else {
                    $('#insurance4').css('display', 'none');
                }
            });
        });
        // insurance //

        $(document).ready(function() {
            $('.diagnosed').change(function() {
                if ($(this).is(':checked')) {
                    $('.diagnosedsub').css('display', 'block');
                } else {
                    $('.diagnosedsub').css('display', 'none');
                }
            });
        });

        // cigarettes //
        $(document).ready(function() {
            $('.cigarettes').change(function() {
                if ($(this).is(':checked')) {
                    $('.cigarettessub').css('display', 'block');
                } else {
                    $('.cigarettessub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.cigarettesmain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#cigarettes1,#cigarettes12').css('display', 'block');
                } else {
                    $('#cigarettes1,#cigarettes12').css('display', 'none');
                }
            });

            $('.cigarettesmain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#cigarettes2,#cigarettes21').css('display', 'block');
                } else {
                    $('#cigarettes2,#cigarettes21').css('display', 'none');
                }
            });
            $('.cigarettesmain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#cigarettes3,#cigarettes31').css('display', 'block');
                } else {
                    $('#cigarettes3,#cigarettes31').css('display', 'none');
                }
            });
            $('.cigarettesmain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#cigarettes4,#cigarettes41').css('display', 'block');
                } else {
                    $('#cigarettes4,#cigarettes41').css('display', 'none');
                }
            });
        });
        // cigarettes //
        // bidis //
        $(document).ready(function() {
            $('.bidis').change(function() {
                if ($(this).is(':checked')) {
                    $('.bidissub').css('display', 'block');
                } else {
                    $('.bidissub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.bidismain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#bidis1,#bidis11').css('display', 'block');
                } else {
                    $('#bidis1,#bidis11').css('display', 'none');
                }
            });

            $('.bidismain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#bidis2,#bidis21').css('display', 'block');
                } else {
                    $('#bidis2,#bidis21').css('display', 'none');
                }
            });
            $('.bidismain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#bidis3,#bidis31').css('display', 'block');
                } else {
                    $('#bidis3,#bidis31').css('display', 'none');
                }
            });
            $('.bidismain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#bidis4,#bidis41').css('display', 'block');
                } else {
                    $('#bidis4,#bidis41').css('display', 'none');
                }
            });
        });
        // bidis //
        // Pan //
        $(document).ready(function() {
            $('.Pan').change(function() {
                if ($(this).is(':checked')) {
                    $('.pansub').css('display', 'block');
                } else {
                    $('.pansub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.Panmain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#Pan1,#Pan11').css('display', 'block');
                } else {
                    $('#Pan1,#Pan11').css('display', 'none');
                }
            });

            $('.Panmain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#Pan2,#Pan21').css('display', 'block');
                } else {
                    $('#Pan2,#Pan21').css('display', 'none');
                }
            });
            $('.Panmain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#Pan3,#Pan31').css('display', 'block');
                } else {
                    $('#Pan3,#Pan31').css('display', 'none');
                }
            });
            $('.Panmain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#Pan4,#Pan41').css('display', 'block');
                } else {
                    $('#Pan4,#Pan41').css('display', 'none');
                }
            });
        });
        // Pan //
        // whisky //
        $(document).ready(function() {
            $('.whisky').change(function() {
                if ($(this).is(':checked')) {
                    $('.whiskysub').css('display', 'block');
                } else {
                    $('.whiskysub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.whiskymain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#whisky1,#whisky11').css('display', 'block');
                } else {
                    $('#whisky1,#whisky11').css('display', 'none');
                }
            });

            $('.whiskymain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#whisky2,#whisky21').css('display', 'block');
                } else {
                    $('#whisky2,#whisky21').css('display', 'none');
                }
            });
            $('.whiskymain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#whisky3,#whisky31').css('display', 'block');
                } else {
                    $('#whisky3,#whisky31').css('display', 'none');
                }
            });
            $('.whiskymain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#whisky4,#whisky41').css('display', 'block');
                } else {
                    $('#whisky4,#whisky41').css('display', 'none');
                }
            });
        });
        // whisky //
        // wine //
        $(document).ready(function() {
            $('.wine').change(function() {
                if ($(this).is(':checked')) {
                    $('.winesub').css('display', 'block');
                } else {
                    $('.winesub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.winemain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#wine1,#wine11').css('display', 'block');
                } else {
                    $('#wine1,#wine11').css('display', 'none');
                }
            });

            $('.winemain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#wine2,#wine21').css('display', 'block');
                } else {
                    $('#wine2,#wine21').css('display', 'none');
                }
            });
            $('.winemain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#wine3,#wine31').css('display', 'block');
                } else {
                    $('#wine3,#wine31').css('display', 'none');
                }
            });
            $('.winemain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#wine4,#wine41').css('display', 'block');
                } else {
                    $('#wine4,#wine41').css('display', 'none');
                }
            });
        });
        // wine //
        // beer //
        $(document).ready(function() {
            $('.beer').change(function() {
                if ($(this).is(':checked')) {
                    $('.beersub').css('display', 'block');
                } else {
                    $('.beersub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.beermain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#beer1,#beer11').css('display', 'block');
                } else {
                    $('#beer1,#beer11').css('display', 'none');
                }
            });

            $('.beermain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#beer2,#beer21').css('display', 'block');
                } else {
                    $('#beer2,#beer21').css('display', 'none');
                }
            });
            $('.beermain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#beer3,#beer31').css('display', 'block');
                } else {
                    $('#beer3,#beer31').css('display', 'none');
                }
            });
            $('.beermain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#beer4,#beer41').css('display', 'block');
                } else {
                    $('#beer4,#beer41').css('display', 'none');
                }
            });
        });
        // beer //
        // drugs //
        $(document).ready(function() {
            $('.drugs').change(function() {
                if ($(this).is(':checked')) {
                    $('.drugssub').css('display', 'block');
                } else {
                    $('.drugssub').css('display', 'none');
                }
            });
        });


        $(document).ready(function() {
            $('.drugsmain1').change(function() {
                if ($(this).is(':checked')) {
                    $('#drugs1,#drugs11').css('display', 'block');
                } else {
                    $('#drugs1,#drugs11').css('display', 'none');
                }
            });

            $('.drugsmain2').change(function() {
                if ($(this).is(':checked')) {
                    $('#drugs2,#drugs21').css('display', 'block');
                } else {
                    $('#drugs2,#drugs21').css('display', 'none');
                }
            });
            $('.drugsmain3').change(function() {
                if ($(this).is(':checked')) {
                    $('#drugs3,#drugs31').css('display', 'block');
                } else {
                    $('#drugs3,#drugs31').css('display', 'none');
                }
            });
            $('.drugsmain4').change(function() {
                if ($(this).is(':checked')) {
                    $('#drugs4,#drugs41').css('display', 'block');
                } else {
                    $('#drugs4,#drugs41').css('display', 'none');
                }
            });
        });
        // drugs //
        $(document).ready(function() {
            $('#sameAddress').change(function() {
                if ($(this).is(':checked')) {
                    $('#sameAddsection').hide();
                    $('#sameAddress').val('1');
                } else {
                    $('#sameAddsection').show();
                    $('#sameAddress').val('0');
                }
            });
        });



        var currentStep = 1;
        var totalSteps = $('.step').length;
        var validOk = true;

        $(".next-step").click(function(event) {
            event.preventDefault();

            if (validateAllFields()) {
                $("#loader").show();
                saveFormStepOne("formStepOne", "{{ route('proposalStepOne') }}").then(function(response) {
                      $("#loader").hide();
                    if (validOk) {
                        if (currentStep < totalSteps) {

                            $(".step-" + currentStep).removeClass("active").addClass(
                                "animate__animated animate__fadeOutLeft");

                            currentStep++;

                            setTimeout(function() {
                                $(".step").removeClass("animate__animated animate__fadeOutLeft");
                                $(".step-" + currentStep).addClass(
                                    "active animate__animated animate__fadeInRight");
                                updateProgressBar();
                                updateNavigationButtons();
                            }, 500);
                        } else {
                            //console.log('No more steps to transition to.');
                        }
                    } else {
                        //console.log('ValidOk status is false, cannot proceed.');
                    }
                }).catch(function(xhr) {
                      $("#loader").hide();
                    //console.error('Error handling response:', xhr.responseText);
                });
            } else {
                //  console.log('Validation failed');
            }
        });

        $(".prev-step").click(function() {
            if (currentStep > 1) {
                $(".step-" + currentStep).removeClass("active").addClass("animate__animated animate__fadeOutRight");
                currentStep--;
                setTimeout(function() {
                    $(".step").removeClass("animate__animated animate__fadeOutRight");
                    $(".step-" + currentStep).addClass("active animate__animated animate__fadeInLeft");
                    updateProgressBar();

                    updateNavigationButtons();
                }, 500);
            }
        });

        function gotoStepOne() {
            // Navigate to Step 1
            if (currentStep !== 1) {
                $(".step-" + currentStep).removeClass("active").addClass("animate__animated animate__fadeOutRight");
                currentStep = 1;
                setTimeout(function() {
                    $(".step").removeClass("animate__animated animate__fadeOutRight");
                    $(".step-" + currentStep).addClass("active animate__animated animate__fadeInLeft");
                    updateProgressBar();
                    updateNavigationButtons();
                }, 500);
            }
        }

        function gotoStepTwo() {
            if (currentStep !== 2) {
                $(".step-" + currentStep).removeClass("active").addClass("animate__animated animate__fadeOutRight");
                currentStep = 2;
                setTimeout(function() {
                    $(".step").removeClass("animate__animated animate__fadeOutRight");
                    $(".step-" + currentStep).addClass("active animate__animated animate__fadeInLeft");
                    updateProgressBar();
                    updateNavigationButtons();
                }, 500);
            }
        }

        function gotoStepThree() {
            if (currentStep !== 3) {
                $(".step-" + currentStep).removeClass("active").addClass("animate__animated animate__fadeOutRight");
                currentStep = 3;
                setTimeout(function() {
                    $(".step").removeClass("animate__animated animate__fadeOutRight");
                    $(".step-" + currentStep).addClass("active animate__animated animate__fadeInLeft");
                    updateProgressBar();
                    updateNavigationButtons();
                }, 500);
            }
        }


        function updateProgressBar() {
            var progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
            $(".progress-bar").css("width", progressPercentage + "%");
            // console.log("Ram slider pr phuc gya");
        }

        function validateAllFields() {
            let isValid = true;

            if (currentStep === 1) {
                isValid = validateFormStepOne();

            } else if (currentStep === 2) {

                if (isValid) {
                    isValid = validateFormStepTwo();

                    var flag = null;

                    saveFormStepTwo().then((response) => {
                        // console.log('Fetch completed', response);

                        if (response === undefined) {
                            flag = true;
                        } else {
                            flag = false;
                        }

                        // console.log(flag);

                        if (flag === false) {
                            // console.log('Ram', flag);
                            // console.log("Date of Birth is Wrong");
                            return false;
                        } else if (flag === true) {
                            // console.log('Shyam', flag);
                            // console.log("Date of Birth is Right");
                            return true;
                        }


                    });

                }
            } else if (currentStep === 3) {
                isValid = validateFormStepThree();
                if (isValid) {
                    saveFormStepThree(); // Save data or perform actions for step 3
                }
            } else if (currentStep === 4) {

            }

            return isValid; // Return whether the current step is valid
        }
        // async function errorData() {
        //     try {
        //         await saveFormStepTwo(); 
        //         console.log('Fetch completed');
        //         return false; 
        //     } catch (error) {
        //         console.error('Error occurred:', error);
        //         return false; 
        //     }
        // }

        function updateNavigationButtons() {
            if (currentStep === 1) {
                $('#backPage').show();
                $('#backSlide').hide();
            } else if (currentStep === 4) {
                $('.gotopaynow').show();
                $('#backPage').hide();
                $('#backSlide').show(); // Assuming you want to show backSlide when at step 4
            } else {
                $('#backPage').hide();
                $('#backSlide').show();
                $('.gotopaynow').hide(); // Hides the button on steps other than 4
            }
        }

        var aMembersAge = [];

        function saveFormStepOne(id, router) {
            // console.log(router);
            return new Promise((resolve, reject) => {
                let formData = new FormData(document.getElementById(id));
                formData.append('_token', "{{ csrf_token() }}");

                $.ajax({
                    url: router,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // console.log(response);


                        if (response.pincode.status == 1) {

                            var oldpremium = "{{ session()->get('premium') }}";
                            var oldcoverage = "{{ session()->get('coverage') }}";

                            // Now you can compare it as a number
                            var newpremium = response.premium.original.totalpremium;
                            var newcoverage = response.premium.original.coverage;
                            // console.log(oldcoverage);
                            // console.log(newcoverage);
                            if (oldpremium == newpremium) {
                                $('#showtooltipmsg').hide();
                            } else {
                                $('#showtooltipmsg').show();
                                $('.prepin').text(response.pincode.ppincode);
                                $('.currepin').text(response.pincode.cpincode);
                            }
                            if (oldcoverage == newcoverage) {
                                $('#showtooltipcvrge').hide();
                                // let precoverage = "{{ session()->get('coverage') }}";
                                // if (precoverage == 100) {
                                //     $('#precvr').text("1 Cr");
                                // } else {
                                //     $('#precvr').text(precoverage + " Lac");
                                // }

                                // let curCoverage = response.premium.original.coverage; 
                                // if (curCoverage == 100) {
                                //     $('#currecvr').text("1 Cr");
                                // } else {
                                //     $('#currecvr').text(curCoverage + " Lac");
                                // }

                            } else {
                                $('#showtooltipcvrge').show();
                                $('.prepin').text(response.pincode.ppincode);
                                $('.currepin').text(response.pincode.cpincode);

                                let precoverage = "{{ session()->get('coverage') }}";
                                if (precoverage == 100) {
                                    $('#precvr').text("1 Cr");

                                } else {
                                    $('#precvr').text(precoverage + " Lac");

                                }

                                let curCoverage = response.premium.original.coverage;
                                if (curCoverage == 100) {
                                    $('#currecvr').text("1 Cr");
                                    $('#coverage-amount').text("1 Cr");
                                } else {
                                    $('#currecvr').text(curCoverage + " Lac");
                                    $('#coverage-amount').text(curCoverage + " Lac");
                                }
                            }
                            // console.log(response.premium.original.basepremium);
                            $('#premium-amount').text(response.premium.original.basepremium);
                            $('#totalpremium').text(response.premium.original.totalpremium);
                            $('#showtotalprimsg').text(response.premium.original.totalpremium);
                        }




                        // let storetotalpremium = response.premium.original.totalpremium;
                        // let storebasepremium = response.premium.original.totalpremium;
                        // // console.log(response.premium.original);
                        // let spantotalpremium = document.getElementById('totalpremium');
                        // let spanbasepremium = document.getElementById('premium-amount');

                        // spantotalpremium.textContent = storetotalpremium;
                        // spanbasepremium.textContent = storebasepremium;

                        let childCount = 1;
                        let members = response.members;
                        // Iterate over the members array
                        members.forEach(member => {
                            let formattedMember =
                                `${member.name}:${member.age}`; // Default formatted member

                            if (member.name === 'Son' || member.name === 'Daughter') {
                                // Replace "Son" or "Daughter" with "child1", "child2", etc.
                                formattedMember = `child${childCount}:${member.age}`;
                                childCount++; // Increment the child count
                            }

                            // Check if the member is already in the array and replace if necessary
                            const index = aMembersAge.findIndex(entry => entry.split(':')[0] ===
                                formattedMember.split(':')[0]);

                            if (index !== -1) {
                                // Replace the existing entry at that index
                                aMembersAge[index] = formattedMember;
                            } else {
                                // Otherwise, add the new entry
                                aMembersAge.push(formattedMember);
                            }
                        });


                        // console.log(aMembersAge);




                        $('#proposername1').val(response.self[0].kyc_name).prop('readonly', false);
                        $('#proposerdob2').val(response.self[0].dob).prop('readonly', false);
                        $("#proposername1").siblings('.placeholder').addClass("active");
                        $("#proposerdob2").siblings('.placeholder').addClass("active");


                        // console.log(aMembersAge);



                        if (response.error != "") {
                            validOk = true;
                            $("#" + response.id + 'Error').focus();
                            $("#" + response.id + 'Error').text(response.error);
                        } else {
                            validOk = false;
                        }
                        resolve(validOk);
                    }
                    // , error: function(xhr) {
                    //     console.error('AJAX error:', xhr.responseText);
                    //     reject(xhr);
                    // }
                });
            });
        }

        function handleSubmit(error, element, numlen) {
            clearErrorOne(error);
            validateNumber(element, numlen);
        }

        function handleSubmitTwo(error, element, numlen) {
            clearErrorTwo(error);
            validateNumber(element, numlen);
        }


        function validateFormStepOne() {

            function formatInputId(inputId) {
                return inputId
                    .replace(/_/g, ' ')
                    .replace(/\b\w/g, char => char.toUpperCase());
            }

            let inputs = [{
                    id: "customerpancardno",
                    errorId: "customerpancardnoError",
                    inputerror: "Customer Pan Card No."
                },
                {
                    id: "customerpancardDob",
                    errorId: "customerpancardDobError",
                    inputerror: "Customer Pan Card DOB."
                },
                {
                    id: "customerAadharGender",
                    errorId: "customerAadharGenderError",
                    inputerror: "Customer Aadhar Gender."
                },
                {
                    id: "customerAadharno",
                    errorId: "customerAadharnoError",
                    inputerror: "Customer Aadhar No."
                },
                {
                    id: "customerAadharName",
                    errorId: "customerAadharNameError",
                    inputerror: "Customer Aadhar Name"
                },
                {
                    id: "customerAadharDob",
                    errorId: "customerAadharDobError",
                    inputerror: "Customer Aadhar DOB."
                },
                {
                    id: "mr_ms_gender",
                    errorId: "mr_ms_genderError",
                    inputerror: "Proposer's Gender"
                },
                {
                    id: "proposername",
                    errorId: "proposernameError",
                    inputerror: "Proposer's Name"
                },
                {
                    id: "proposerdob1",
                    errorId: "proposerdobError1",
                    inputerror: "Proposer's DOB."
                },
                {
                    id: "contactemail",
                    errorId: "contactemailError",
                    inputerror: "Contact Email Address."
                },
                {
                    id: "contactmobile",
                    errorId: "contactmobileError",
                    inputerror: "Contact Mobile Number."
                },
                // {
                //     id: "contactemergency",
                //     errorId: "contactemergencyError",
                //     inputerror: "Contact Emergency Mobile Number."
                // },
                {
                    id: "house",
                    errorId: "houseError",
                    inputerror: "House"
                },
                {
                    id: "colony",
                    errorId: "colonyError",
                    inputerror: "Colony"
                },
                // {
                //     id: "Landmark",
                //     errorId: "LandmarkError",
                //     inputerror: "Landmark"
                // },
                {
                    id: "City",
                    errorId: "CityError",
                    inputerror: "City"
                },
                {
                    id: "State",
                    errorId: "StateError",
                    inputerror: "State"
                },
                {
                    id: "Pincode",
                    errorId: "PincodeError",
                    inputerror: "Pincode"
                },
                {
                    id: "commcurrenthouse",
                    errorId: "commcurrenthouseError",
                    inputerror: "Communication Address Line1"
                },
                {
                    id: "commcurrentcolony",
                    errorId: "commcurrentcolonyError",
                    inputerror: "Communication Address Line2"
                },
                // {
                //     id: "commcurrentLandmark",
                //     errorId: "commcurrentLandmarkError",
                //     inputerror: "Communication Landmark"
                // },
                {
                    id: "commcurrentCity",
                    errorId: "commcurrentCityError",
                    inputerror: "Communication City"
                },
                {
                    id: "commcurrentState",
                    errorId: "commcurrentStateError",
                    inputerror: "Communication State"
                },
                {
                    id: "commcurrentPincode",
                    errorId: "commcurrentPincodeError",
                    inputerror: "Communication Pincode"
                }
            ];

            const pincodeRegex = /^[1-9]\d{5}$/;
            const addressRegex = /^[A-Za-z0-9-./#&, ]*$/;
            const panRegex = /^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const mobileRegex = /^[0-9]{10}$/;
            const dateRegex = /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-\d{4}$/;

            const isPanChecked = document.getElementById('panCard')?.checked || false;
            const isAadhaarChecked = document.getElementById('aadhaarCard')?.checked || false;
            const isOtherChecked = document.getElementById('otherCard')?.checked || false;
            const isSamaaddressChecked = document.querySelector('input[name="sameAddress"]:checked');

            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox?.querySelector('.error__title');
            const errorCloseButton = errorBox?.querySelector('.error__close');

            function displayError(message, inputElement) {
                if (errorTitleElement) {
                    errorTitleElement.innerText = message;
                    setTimeout(() => {
                        errorBox.style.display = 'none';
                    }, 3000);
                    errorBox.style.display = "flex";
                    if (inputElement) {
                        errorBox.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        inputElement.focus();
                    }
                }
            }

            if (errorCloseButton) {
                errorCloseButton.addEventListener('click', () => {
                    if (errorBox) {
                        errorBox.style.display = "none";
                    }
                });
            }

            let isValid = true;
            let hasError = false;

            for (let input of inputs) {
                let inputElement = document.getElementById(input.id);
                let errorSpan = document.getElementById(input.errorId);

                if (!inputElement || !errorSpan) {
                    // console.error(`Element or error span with ID ${input.id.toUpperCase()} not found`);
                    continue;
                }

                errorSpan.innerText = "";
                inputElement.classList.remove("error");

                if (isSamaaddressChecked && input.id.startsWith('commcurrent')) {
                    continue;
                }
                if (!inputElement.value.trim()) {
                    let errorMessage = input.inputerror ?
                        `Field for ${input.inputerror} cannot be blank` :
                        `Field for ${formatInputId(input.id)} cannot be blank`;

                    if (input.id.includes("customer")) {
                        if ((input.id.includes("pancard") && isPanChecked) ||
                            (input.id.includes("Aadhar") && isAadhaarChecked)) {
                            isValid = false;
                            hasError = true;
                            displayError(errorMessage, inputElement);
                            inputElement.classList.add("error");
                            break;
                        }
                    } else {
                        isValid = false;
                        hasError = true;
                        displayError(errorMessage, inputElement);
                        inputElement.classList.add("error");
                        break;
                    }
                } else {
                    switch (input.id) {
                        case "customerpancardno":
                            if (isPanChecked && !panRegex.test(inputElement.value.trim())) {
                                isValid = false;
                                hasError = true;
                                displayError("Invalid PAN card number format", inputElement);
                                inputElement.classList.add("error");
                                break;
                            }
                            break;
                        case "customerAadharGender":
                            if (isAadhaarChecked && !inputElement.value.trim()) {
                                isValid = false;
                                hasError = true;
                                displayError("Select Gender", inputElement);
                                inputElement.classList.add("error");
                                break;
                            }
                            break;
                        case "customerAadharno":
                            if (isAadhaarChecked && !/^\d{4}$/.test(inputElement.value.trim())) {
                                isValid = false;
                                hasError = true;
                                displayError("Invalid Aadhaar number format (last 4 digits)", inputElement);
                                inputElement.classList.add("error");
                                break;
                            }
                            break;
                        case "customerAadharName":
                            if (isAadhaarChecked && !inputElement.value.trim()) {
                                isValid = false;
                                hasError = true;
                                displayError("Aadhaar Name cannot be blank", inputElement);
                                inputElement.classList.add("error");
                                break;
                            }
                            break;
                        case "contactemail":
                            if (!emailRegex.test(inputElement.value.trim())) {
                                isValid = false;
                                hasError = true;
                                displayError("Invalid email format", inputElement);
                                inputElement.classList.add("error");
                                break;
                            }
                            break;
                        case "Pincode":
                            if (!pincodeRegex.test(inputElement.value.trim())) {
                                isValid = false;
                                hasError = true;
                                displayError("Invalid pincode format", inputElement);
                                inputElement.classList.add("error");
                                break;
                            }
                            break;
                        case "commcurrentPincode":
                            if (!pincodeRegex.test(inputElement.value.trim())) {
                                isValid = false;
                                hasError = true;
                                displayError("Invalid Communication Address pincode format", inputElement);
                                inputElement.classList.add("error");
                                break;
                            }
                            break;
                        case "house":
                            if (!addressRegex.test(inputElement.value.trim())) {
                                isValid = false;
                                hasError = true;
                                displayError("Invalid Permanent Address Line1 format", inputElement);
                                inputElement.classList.add("error");
                                break;
                            }
                            break;
                        case "colony":
                            if (!addressRegex.test(inputElement.value.trim())) {
                                isValid = false;
                                hasError = true;
                                displayError("Invalid Permanent Address Line2 format", inputElement);
                                inputElement.classList.add("error");
                                break;
                            }
                            break;
                        case "commcurrenthouse":
                            if (!addressRegex.test(inputElement.value.trim())) {
                                isValid = false;
                                hasError = true;
                                displayError("Communication Address Line1 format", inputElement);
                                inputElement.classList.add("error");
                                break;
                            }
                            break;
                        case "commcurrentcolony":
                            if (!addressRegex.test(inputElement.value.trim())) {
                                isValid = false;
                                hasError = true;
                                displayError("Invalid Communication Address Line2 format", inputElement);
                                inputElement.classList.add("error");
                                break;
                            }
                            break;
                            // case "contactemergency":
                            //     if (!/^[6-9]\d{9}$/.test(inputElement.value.trim())) {
                            //         isValid = false;
                            //         hasError = true;
                            //         displayError("Please enter a valid 10-digit Emergency Mobile Number.Invalid", inputElement);
                            //         inputElement.classList.add("error");
                            //         break;
                            //     }
                            //     break;

                    }
                }

                if (!isValid && errorBox.style.display === "flex") {
                    break;
                }
            }


            if (isOtherChecked && $('#othercardDetails').css('display') === 'block') {

                const identityValue = $('#identityTypeProof').val();
                const addressValue = $('#addressTypeProof').val();



                if (!addressValue) {
                    displayError('Please select an Address Proof Type.');
                    $('#addressTypeProof').addClass('error');
                    isValid = false;
                    hasError = true;
                }
                if (!identityValue) {
                    displayError('Please select an Identity Proof Type.');
                    $('#identityTypeProof').addClass('error');
                    isValid = false;
                    hasError = true;
                }

                // Check for the address proof inputs
                if (addressValue === 'aadhar') {
                    if (!$('#addressidentity_aadhar').val()) {
                        isValid = false;
                        hasError = true;
                        displayError('Please upload Address Aadhaar Proof.');
                    }
                    // else if (!$('#addressidentity_aadharBack').val()) {
                    //     isValid = false;
                    //     errorMessage = 'Please upload Address Aadhaar (Back) Proof.';
                    // }
                } else if (addressValue === 'pan' && !$('#addresspancard').val()) {
                    isValid = false;
                    hasError = true;
                    displayError('Please upload Address PAN Proof.');
                } else if (addressValue === 'passport' && !$('#addresspassportcard').val()) {
                    isValid = false;
                    displayError('Please upload Address Passport Proof.');

                } else if (addressValue === 'DrivingLicense') {
                    if (!$('#addressDlFront').val()) {
                        isValid = false;
                        hasError = true;
                        displayError('Please upload Address Driving LicenseProof.');
                    }
                    // else if (!$('#addressDlBack').val()) {
                    //     isValid = false;
                    //     errorMessage = 'Please upload Address Driving License (Back) Proof.';
                    // }
                } else if (addressValue === 'VoterID') {
                    if (!$('#addressVoterFront').val()) {
                        isValid = false;
                        hasError = true;
                        displayError('Please upload Address Voter ID (Front) Proof.');
                    }
                    // else if (!$('#addressVoterBack').val()) {
                    //     isValid = false;
                    //     errorMessage = 'Please upload Address Voter ID (Back) Proof.';
                    // }
                } else if (addressValue === 'Form60' && !$('#addressForm60').val()) {
                    isValid = false;
                    hasError = true;
                    displayError('Please upload Address Form 60 Proof.');
                }

                // Check for the identity proof inputs
                if (identityValue === 'aadhar') {
                    if (!$('#identity_aadhar').val()) {
                        isValid = false;
                        hasError = true;
                        displayError('Please upload Identity Aadhaar Proof.');
                    }
                    //  else if (!$('#identity_aadharBack').val()) {
                    //     isValid = false;
                    //     errorMessage = 'Please upload Identity Aadhaar (Back) Proof.';
                    // }
                } else if (identityValue === 'pan' && !$('#identity_pancard').val()) {
                    isValid = false;
                    hasError = true;
                    displayError('Please upload Identity PAN Proof.');
                } else if (identityValue === 'passport' && !$('#identitypassportcard').val()) {
                    isValid = false;
                    displayError('Please upload Identity Passport Proof.');
                } else if (identityValue === 'DrivingLicense') {
                    if (!$('#DrivingFront').val()) {
                        isValid = false;
                        hasError = true;
                        displayError('Please upload Identity Driving License Proof.');
                    }
                    //  else if (!$('#DrivingBack').val()) {
                    //     isValid = false;
                    //     errorMessage = 'Please upload Identity Driving License (Back) Proof.';
                    // }
                } else if (identityValue === 'VoterID') {
                    if (!$('#VoterFront').val()) {
                        isValid = false;
                        hasError = true;
                        displayError('Please upload Identity Voter ID Proof.');
                    }
                    // else if (!$('#VoterBack').val()) {
                    //     isValid = false;
                    //     errorMessage = 'Please upload Identity Voter ID (Back) Proof.';
                    // }
                } else if (identityValue === 'Form60' && !$('#Form60').val()) {
                    isValid = false;
                    hasError = true;
                    displayError('Please upload Identity Form 60 Proof.');
                }


                return isValid;
            }

            if (!isPanChecked && !isAadhaarChecked && !isOtherChecked) {
                isValid = false;
                hasError = true;
                displayError("Please select exactly verify one card type (PAN, Aadhar, or Other).", null);
            }

            if (!hasError && errorBox.style.display === "flex") {
                errorBox.style.display = "none";
            }
            return isValid;
        }






        // Function to clear error message and styling for a specific input field
        function clearErrorOne(errorId, inputElement) {
            let errorSpan = document.getElementById(errorId);
            if (errorSpan) {
                errorSpan.innerText = "";
                let inputElement = document.getElementById(errorId.replace("Error", ""));
                if (inputElement) {
                    inputElement.classList.remove("error");
                }
            }

        }


        function validateFormStepTwo() {
            const inputs = [{
                    id: "proposerdob",
                    errorId: "proposerdobError",
                    inputerror: "Proposer DOB."
                },

                {
                    id: "proposername1",
                    errorId: "proposername1Error",
                    alphabetregex: /^[a-zA-Z\s]+$/,
                    inputerror: "Proposer Name."
                },
                {
                    id: "proposerdob2",
                    errorId: "proposerdob2Error",
                    inputerror: "Proposer DOB."
                },
                {
                    id: "proposeroccupation",
                    errorId: "proposeroccupationError",
                    inputerror: "Proposer Occupation."
                },

                {
                    id: "proposerheight",
                    errorId: "proposerheightError",
                    inputerror: "Proposer Height."
                },
                {
                    id: "proposerinches",
                    errorId: "proposerinchesError",
                    inputerror: "Proposer Inches."
                },
                {
                    id: "proposerweight",
                    errorId: "proposerweightError",
                    inputerror: "Proposer Weight."
                },
                {
                    id: "proposarbankaccount",
                    errorId: "proposarbankaccountError",
                    inputerror: "Proposer Bank Account Number."
                },
                {
                    id: "proposarbankifsc",
                    errorId: "proposarbankifscError",
                    inputerror: "Proposer IFSC Code."
                },
                {
                    id: "spousename",
                    errorId: "spousenameError",
                    alphabetregex: /^[a-zA-Z\s]+$/,
                    inputerror: "Spouse Name."
                },
                {
                    id: "spousedob",
                    errorId: "spousedobError",
                    inputerror: "Spouse DOB."
                },
                {
                    id: "spouseoccupation",
                    errorId: "spouseoccupationError",
                    inputerror: "Spouse Occupation."
                },
                {
                    id: "spouseheight",
                    errorId: "spouseheightError",
                    inputerror: "Spouse Height."
                },
                {
                    id: "spouseinches",
                    errorId: "spouseinchesError",
                    inputerror: "Spouse Inches."
                },
                {
                    id: "spouseweight",
                    errorId: "spouseweightError",
                    inputerror: "Spouse Weight."
                },
                {
                    id: "childname1",
                    errorId: "childname1Error",
                    alphabetregex: /^[a-zA-Z\s]+$/,
                    inputerror: "Child 1 Name."
                },
                {
                    id: "childdob1",
                    errorId: "childdob1Error",
                    inputerror: "Child 1 DOB."
                },
                {
                    id: "childheight1",
                    errorId: "childheight1Error",
                    inputerror: "Child 1 Height."
                },
                {
                    id: "childinches1",
                    errorId: "childinches1Error",
                    inputerror: "Child 1 Inches."
                },
                {
                    id: "childweight1",
                    errorId: "childweight1Error",
                    inputerror: "Child 1 Weight."
                },
                {
                    id: "childrelation1",
                    errorId: "childrelation1Error",
                    inputerror: "Child 1 Relation."
                },
                {
                    id: "childname2",
                    errorId: "childname2Error",
                    alphabetregex: /^[a-zA-Z\s]+$/,
                    inputerror: "Child 2 Name."
                },
                {
                    id: "childdob2",
                    errorId: "childdob2Error",
                    inputerror: "Child 2 DOB."
                },
                {
                    id: "childheight2",
                    errorId: "childheight2Error",
                    inputerror: "Child 2 Height."
                },
                {
                    id: "childinches2",
                    errorId: "childinches2Error",
                    inputerror: "Child 2 Inches."
                },
                {
                    id: "childweight2",
                    errorId: "childweight2Error",
                    inputerror: "Child 2 Weight."
                },
                {
                    id: "childrelation2",
                    errorId: "childrelation2Error",
                    inputerror: "Child 2 Relation."
                },
                {
                    id: "childname3",
                    errorId: "childname3Error",
                    alphabetregex: /^[a-zA-Z\s]+$/,
                    inputerror: "Child 3 Name."
                },
                {
                    id: "childdob3",
                    errorId: "childdob3Error",
                    inputerror: "Child 3 DOB."
                },
                {
                    id: "childheight3",
                    errorId: "childheight3Error",
                    inputerror: "Child 3 Height."
                },
                {
                    id: "childinches3",
                    errorId: "childinches3Error",
                    inputerror: "Child 3 Inches."
                },
                {
                    id: "childweight3",
                    errorId: "childweight3Error",
                    inputerror: "Child 3 Weight."
                },
                {
                    id: "childrelation3",
                    errorId: "childrelation3Error",
                    inputerror: "Child 3 Relation."
                },
                {
                    id: "childname4",
                    errorId: "childname4Error",
                    alphabetregex: /^[a-zA-Z\s]+$/,
                    inputerror: "Child 4 Name."
                },
                {
                    id: "childdob4",
                    errorId: "childdob4Error",
                    inputerror: "Child 4 DOB."
                },
                {
                    id: "childheight4",
                    errorId: "childheight4Error",
                    inputerror: "Child 4 Height."
                },
                {
                    id: "childinches4",
                    errorId: "childinches4Error",
                    inputerror: "Child 4 Inches."
                },
                {
                    id: "childweight4",
                    errorId: "childweight4Error",
                    inputerror: "Child 4 Weight."
                },
                {
                    id: "childrelation4",
                    errorId: "childrelation4Error",
                    inputerror: "Child 4 Relation."
                },
                {
                    id: "grandMothernameOne",
                    errorId: "grandMothernameOneError",
                    alphabetregex: /^[a-zA-Z\s]+$/,
                    inputerror: "Grand Mother Name."
                },
                {
                    id: "grandMotherdobOne",
                    errorId: "grandMotherdobOneError",
                    inputerror: "Grand Mother DOB."
                },
                {
                    id: "grandMotherheightOne",
                    errorId: "grandMotherheightOneError",
                    inputerror: "Grand Mother Height."
                },
                {
                    id: "grandMotherinchesOne",
                    errorId: "grandMotherinchesOneError",
                    inputerror: "Grand Mother Inches."
                },
                {
                    id: "grandMotherweightOne",
                    errorId: "grandMotherweightOneError",
                    inputerror: "Grand Mother Weight."
                },
                {
                    id: "Grandfathername",
                    errorId: "GrandfathernameError",
                    alphabetregex: /^[a-zA-Z\s]+$/,
                    inputerror: "Grand Father Name."
                },
                {
                    id: "Grandfatherdob",
                    errorId: "GrandfatherdobError",
                    inputerror: "Grand Father DOB."
                },
                {
                    id: "Grandfatherheight",
                    errorId: "GrandfatherheightError",
                    inputerror: "Grand Father Height."
                },
                {
                    id: "Grandfatherinches",
                    errorId: "GrandfatherinchesError",
                    inputerror: "Grand Father Inches."
                },
                {
                    id: "Grandfatherweight",
                    errorId: "GrandfatherweightError",
                    inputerror: "Grand Father Weight."
                },
                {
                    id: "mothername",
                    errorId: "mothernameError",
                    alphabetregex: /^[a-zA-Z\s]+$/,
                    inputerror: "Mother Name."
                },
                {
                    id: "motherdob",
                    errorId: "motherdobError",
                    inputerror: "Mother DOB."
                },
                {
                    id: "motherheight",
                    errorId: "motherheightError",
                    inputerror: "Mother Height."
                },
                {
                    id: "motherinches",
                    errorId: "motherinchesError",
                    inputerror: "Mother Inches."
                },
                {
                    id: "motherweight",
                    errorId: "motherweightError",
                    inputerror: "Mother Weight."
                },
                {
                    id: "fathername",
                    errorId: "fathernameError",
                    alphabetregex: /^[a-zA-Z\s]+$/,
                    inputerror: "Father Name."
                },
                {
                    id: "fatherdob",
                    errorId: "fatherdobError",
                    inputerror: "Father DOB."
                },
                {
                    id: "fatherheight",
                    errorId: "fatherheightError",
                    inputerror: "Father Height."
                },
                {
                    id: "fatherinches",
                    errorId: "fatherinchesError",
                    inputerror: "Father Inches."
                },
                {
                    id: "fatherweight",
                    errorId: "fatherweightError",
                    inputerror: "Father Weight."
                },
                {
                    id: "fatherlawname",
                    errorId: "fatherlawnameError",
                    alphabetregex: /^[a-zA-Z\s]+$/,
                    inputerror: "Father Law Name."
                },
                {
                    id: "fatherlawdob",
                    errorId: "fatherlawdobError",
                    inputerror: "Father Law DOB."
                },
                {
                    id: "fatherlawheight",
                    errorId: "fatherlawheightError",
                    inputerror: "Father Law Height."
                },
                {
                    id: "fatherlawinches",
                    errorId: "fatherlawinchesError",
                    inputerror: "Father Law Inches."
                },
                {
                    id: "fatherlawweight",
                    errorId: "fatherlawweightError",
                    inputerror: "Father Law Weight."
                },
                {
                    id: "Motherlawname",
                    errorId: "MotherlawnameError",
                    alphabetregex: /^[a-zA-Z\s]+$/,
                    inputerror: "Mother Law Name."
                },
                {
                    id: "Motherlawdob",
                    errorId: "MotherlawdobError",
                    inputerror: "Mother Law DOB."
                },
                {
                    id: "Motherlawheight",
                    errorId: "MotherlawheightError",
                    inputerror: "Mother Law Height."
                },
                {
                    id: "Motherlawinches",
                    errorId: "MotherlawinchesError",
                    inputerror: "Mother Law Inches."
                },
                {
                    id: "Motherlawweight",
                    errorId: "MotherlawweightError",
                    inputerror: "Mother Law Weight."
                },
                {
                    id: "nomineename",
                    errorId: "nomineenameError",
                    inputerror: "Nominee Name.",
                    nomineeregex: /^[a-zA-Z\s]+$/
                },
                {
                    id: "nomineedob",
                    errorId: "nomineedobError",
                    inputerror: "Nominee DOB."
                },
                {
                    id: "nomineerelation",
                    errorId: "nomineerelationError",
                    inputerror: "Nominee Relation."
                }
            ];

            const heightWeightFields = [
                "proposerheight", "proposerinches", "proposerweight",
                "spouseheight", "spouseinches", "spouseweight",
                "childheight1", "childinches1", "childweight1",
                "childheight2", "childinches2", "childweight2",
                "childheight3", "childinches3", "childweight3",
                "childheight4", "childinches4", "childweight4",
                "grandMotherheightOne", "grandMotherinchesOne", "grandMotherweightOne",
                "Grandfatherheight", "Grandfatherinches", "Grandfatherweight",
                "motherheight", "motherinches", "motherweight",
                "fatherheight", "fatherinches", "fatherweight",
                "fatherlawheight", "fatherlawinches", "fatherlawweight",
                "Motherlawheight", "Motherlawinches", "Motherlawweight"
            ];
            let isValid = true;

            inputs.some(input => {
                let inputElement = document.getElementById(input.id);
                let errorSpan = document.getElementById(input.errorId);
                if (inputElement) {
                    clearErrorTwo(input.errorId);



                    // Check for empty fields first
                    if (inputElement.value.trim() === "") {
                        isValid = false;
                        displayError("Field cannot be blank", input.inputerror, input.id);
                        inputElement.classList.add("error");
                        return true; // Stop checking further
                    } else {
                        inputElement.classList.remove("error");
                    }


                    // Additional check for height and weight fields
                    if (heightWeightFields.includes(input.id)) {
                        let value = parseFloat(inputElement.value.trim());

                        // Check if it's a height or weight field and if the value is zero
                        if (!(input.id.includes("inches")) && value === 0) {
                            isValid = false;
                            displayError("Value cannot be 0", input.inputerror, input.id);
                            inputElement.classList.add("error");
                            return true; // Stop checking further
                        }
                    }
                    // Check for nominee name regex
                    if (input.id === 'nomineename') {
                        const isRegexValid = input.nomineeregex && input.nomineeregex.test(inputElement.value
                            .trim());

                        if (!isRegexValid) {
                            isValid = false;
                            displayError("Invalid format", input.inputerror, input.id);
                            inputElement.classList.add("error");
                            return false; // Stop checking further
                        } else {
                            inputElement.classList.remove("error");
                        }
                    }

                    // Check for valid names
                    const validNames = ['proposername1', 'spousename', 'childname1', 'childname2', 'childname3',
                        'Grandfathername', 'grandMothernameOne', 'mothername', 'fathername', 'fatherlawname',
                        'Motherlawname'
                    ];

                    if (validNames.includes(input.id)) {
                        const isAlphaRegexValid = input.alphabetregex && input.alphabetregex.test(inputElement.value
                            .trim());

                        if (!isAlphaRegexValid) {
                            isValid = false;
                            displayError("Invalid format", input.inputerror, input.id);
                            // console.log(input.id);
                            inputElement.classList.add("error");
                            return true;
                        } else {
                            inputElement.classList.remove("error");
                        }
                    }

                }

                let memberInputMapping = {
                    'self': 'proposerdob2',
                    'wife': 'spousedob',
                    'child1': 'childdob1',
                    'child2': 'childdob2',
                    'child3': 'childdob3',
                    'child4': 'childdob4',
                    'grandmother': 'grandMotherdobOne',
                    'grandfather': 'Grandfatherdob',
                    'mother': 'motherdob',
                    'father': 'fatherdob',
                    'fatherinlaw': 'fatherlawdob',
                    'motherinlaw': 'Motherlawdob',
                    'Nominee': 'nomineedob',
                    // Add more mappings here as needed
                };

                // First, validate 'self' explicitly
                let selfMemberData = aMembersAge.find(memberData => memberData.startsWith('self'));

                if (selfMemberData) {
                    let [memberId, age] = selfMemberData.split(':').map(item => item.trim());
                    let inputId = memberInputMapping[memberId];
                    let inputElement = document.getElementById(inputId);

                    if (inputElement) {
                        let memberAge = parseInt(age);
                        let inputValue = inputElement.value;
                        let dateArr = inputValue.split('-');
                        // let objdate = new Date(dateArr[2], dateArr[1], dateArr[0]);
                        let objdate = new Date(dateArr[2], dateArr[1] - 1, dateArr[0]);
                        let currentdate = new Date();
                        let dateDiffAge = calculateAge(objdate, currentdate)
                        const memberpreAge = document.getElementById('self-preage');
                        const ageElement = document.getElementById('self-newage');



                        // console.log('memberage:', memberAge);
                        // console.log('inputValue:', inputValue);
                        // console.log('year:', dateDiffAge);
                        // console.log('exptyr:', new Date().getFullYear() - memberAge);
                        // document.getElementById('self-preage').textContent = `${memberAge} years`;
                        memberpreAge.textContent = `${memberAge} years`;
                        ageElement.textContent = `${dateDiffAge} years`;
                        if (dateDiffAge !== memberAge) {
                            isValid = false;
                            // displayError(
                            //     `Invalid Age for ${memberId}. Expected Age Year ${new Date().getFullYear()-memberAge}`,
                            //     ''
                            // );


                            // $('#premimumchange').modal('show');

                            displayError(
                                `Invalid Age for ${memberId}. Expected Age ${memberAge} Year as per quotation.`,
                                ''
                            );
                            inputElement.classList.add("error");
                            return false;
                        }
                        // console.log('SelfAge', memberAge);
                        // console.log('SelfFullDob', memberfulldob);
                    } else {
                        console.error(`Element with ID ${inputId} not found.`);
                    }
                }


                // After validating 'self', process the rest of the members
                aMembersAge.forEach((memberData) => {
                    let [memberId, age] = memberData.split(':').map(item => item.trim());

                    // Skip 'self' as it's already validated
                    if (memberId === 'self') return;

                    let inputId = memberInputMapping[memberId];
                    let inputElement = document.getElementById(inputId);

                    if (inputElement) {

                        let fmemberAge = parseInt(age);
                        let finputValue = inputElement.value;
                        let fdateArr = finputValue.split('-');
                        // let fobjdate = new Date(fdateArr[2], fdateArr[1], fdateArr[0]);
                        let fobjdate = new Date(fdateArr[2], fdateArr[1] - 1, fdateArr[0]);
                        let fcurrentdate = new Date();
                        let fdateDiffAge = othercalculateAge(fobjdate, fcurrentdate);


                        // console.log('fothermember:');
                        // console.log('fmemberage:', fmemberAge);
                        // console.log('finputValue:', finputValue);
                        // console.log('fyear:', fdateDiffAge);
                        // console.log('fexptyr:', new Date().getFullYear() - fmemberAge);


                        if (fdateDiffAge !== fmemberAge) {
                            isValid = false;
                            displayError(
                                `Invalid Age for ${memberId}.  Expected Age ${fmemberAge} Year as per quotation.`,
                                ''
                            );

                            inputElement.classList.add("error");
                            return false;
                        }

                    } else {
                        console.error(`Element with ID ${inputId} not found.`);
                    }
                });


                return false;

            });

            // Hide error box if no errors found
            if (isValid) {
                const errorBox = document.querySelector('.MainErrorBox');
                if (errorBox) {
                    errorBox.style.display = "none";
                }
            }
            return isValid;
        }

        // Function to display errors
        function displayError(message, inputerror, id) {
            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox.querySelector('.error__title');

            if (errorBox && errorTitleElement) {
                errorTitleElement.innerText = `${message} ${inputerror}`;
                errorBox.style.display = "flex";
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                const inputElement = document.getElementById(id);

                if (inputElement) {
                    inputElement.classList.add('highlight-error');
                    setTimeout(() => {
                        inputElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        inputElement.focus();
                    }, 100);
                }
            }
        }

        function clearErrorTwo(errorId) {
            let errorSpan = document.getElementById(errorId);
            if (errorSpan) {
                errorSpan.innerText = "";
                let inputElement = document.getElementById(errorId.replace("Error", ""));
                if (inputElement) {
                    inputElement.classList.remove("error");
                }
            }
        }

        // Assuming you have a function to go to the next step
        function goToNextStep() {
            if (validateFormStepTwo()) {
                // Code to show the next step
            }
        }


        let firstName = "";
        let lastName = "";
        let dob = "";
        let permLine1 = "",
            permLine2 = "",
            permLine3 = "",
            permCity = "",
            permState = "",
            permPin = "";

        // Function to verify PAN card details
        function verifyPan(e) {
            e.preventDefault();
            
            var paninput = $("#customerpancardno");
            var dobinput = $("#customerpancardDob");
            var panid = $("#customerpancardno").val();
            var paniddob = $("#customerpancardDob").val();
            var formData = new FormData();

            // Get the error and success boxes
            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox.querySelector('.error__title');
            const successBox = document.querySelector('.MainverifiyedBox');
            const successTitleElement = successBox.querySelector('.verifiyed__title');

            // Regex for PAN card and Date of Birth (DOB)
            var panRegex = /^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/;
            var dateRegex = /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-\d{4}$/;

            // Validate PAN format
            if (!panRegex.test(panid)) {
                errorBox.style.display = 'flex';
                errorTitleElement.innerText = "Invalid PAN card number format";
                paninput.addClass("error");
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                return; // Stop if PAN is invalid
            }

            // Validate DOB format
            if (!dateRegex.test(paniddob)) {
                errorBox.style.display = 'flex';
                errorTitleElement.innerText = "Invalid Date of Birth format.";
                dobinput.addClass("error");
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                return; 
            }
            paninput.removeClass("error");
            dobinput.removeClass("error");
            $('#verifyPanBtn').prop('disabled', true).html('VERIFYING <i class="fa fa-spinner fa-spin"></i>');

            formData.append('customerpancardno', panid);
            formData.append('customerpancardDob', paniddob);
            formData.append('_token', "{{ csrf_token() }}");

            errorBox.style.display = 'none';
            successBox.style.display = 'none';

            $.ajax({
                url: "{{ route('verifyPAN') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    try {
                        let data = JSON.parse(response.pandata);
                        kyc = JSON.parse(response.kyc);
                        let status = data.responseData.status;
                        let personalData = data.getCkycEkycInputIO.kycDetails.personalIdentifiableData
                            .personalDetails;
                        // console.log(personalData);

                        // Capture the personal details
                        firstName = personalData.firstName;
                        lastName = personalData.lastName || "";
                        dob = personalData.dob;
                        let prefix = personalData.prefix;

                        // Set the gender
                        let genderValue = (prefix === "MR") ? "Mr" : (prefix === "MS") ? "Ms" : "";
                        $("#mr_ms_gender").val(genderValue).change();
                        $("#mr_ms_gender").siblings('.placeholder').addClass("active");
                        $("#proposername").siblings('.placeholder').addClass("active");

                        // Permanent Address Details
                        permLine1 = personalData.permLine1 || '';
                        permLine2 = personalData.permLine2 || '';
                        permLine3 = personalData.permLine3 || '';
                        permCity = personalData.permCity || '';
                        permState = personalData.permState || '';
                        permPin = personalData.permPin || '';

                        // Update the form fields with the retrieved data
                        $("#house").val(permLine1);
                        $("#colony").val(permLine2);
                        $("#Landmark").val(permLine3);
                        $("#City").val(permCity);
                        $("#State").val(permState);
                        $('#Pincode').val(permPin).prop('readonly', false);

                        // Trigger placeholder activation manually after setting value
                        $("#house").siblings('.placeholder').addClass("active");
                        $("#colony").siblings('.placeholder').addClass("active");
                        $("#Landmark").siblings('.placeholder').addClass("active");
                        $("#City").siblings('.placeholder').addClass("active");
                        $("#State").siblings('.placeholder').addClass("active");
                        $('#Pincode').siblings('.placeholder').addClass("active");

                        if (status === "1") {
                            getStatusTotalPremium();
                            $("#next1").prop("disabled", false);
                            $('#verifyPanBtn').html('VERIFIED <i class="fa-solid fa-check ml-2"></i>').prop(
                                'disabled', true);

                            successBox.style.display = "flex";
                            successTitleElement.innerText =
                                `PAN verification successful! You can now continue.`;
                            $('#personalDetail').show();
                            // Pre-fill form fields
                            lastName = (lastName === undefined || lastName === "") ? "" : lastName;
                            $('#proposername').val(`${firstName} ${lastName}`).prop('readonly', true);
                            $('#proposerdob1').val(`${dob}`).prop('readonly', true);
                            $("#proposerdob1").siblings('.placeholder').addClass("active");
                        } else {
                            $("#next1").prop("disabled", true);
                            $('#verifyPanBtn').prop('disabled', false).html('VERIFY');

                            errorBox.style.display = "flex";
                            errorTitleElement.innerText =
                                `${response.message}`;
                        }
                    } catch (error) {
                        $("#next1").prop("disabled", true);
                        $('#verifyPanBtn').prop('disabled', false).html('VERIFY');

                        errorBox.style.display = "flex";
                        errorTitleElement.innerText =
                            `${response.message}.`;
                    }

                    setTimeout(() => {
                        errorBox.style.display = 'none';
                        successBox.style.display = 'none';
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    $("#next1").prop("disabled", true);
                    $('#verifyPanBtn').prop('disabled', false).html('VERIFYING');

                    errorBox.style.display = "flex";
                    errorTitleElement.innerText =
                        `Error occurred. Please correct your PAN number and Date of Birth.`;

                    setTimeout(() => {
                        errorBox.style.display = 'none';
                    }, 3000);
                }
            });
        }


        // Function to verify Aadhaar card details
        function verifyAdhar(e) {
            e.preventDefault();
            $('#verifyAadhaarBtn').prop('disabled', true).html('VERIFYING <i class="fa fa-spinner fa-spin"></i>');

            var adhargender = $("#customerAadharGender").val();
            var adharid = $("#customerAadharno").val();
            var adharname = $("#customerAadharName").val();
            var adhardob = $("#customerAadharDob").val();
            var formData = new FormData();

            // Get the error and success boxes
            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox.querySelector('.error__title');
            const successBox = document.querySelector('.MainverifiyedBox');
            const successTitleElement = successBox.querySelector('.verifiyed__title');

            formData.append('customerAadharGender', adhargender);
            formData.append('customerAadharno', adharid);
            formData.append('customerAadharName', adharname);
            formData.append('customerAadharDob', adhardob);
            formData.append('_token', "{{ csrf_token() }}");

            errorBox.style.display = 'none';
            successBox.style.display = 'none';

            $.ajax({
                url: "{{ route('verifyAdhar') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // console.log(response.adhardata);
                    try {
                        let data = JSON.parse(response.adhardata);
                        kyc = JSON.parse(response.kyc);
                        let status = data.responseData.status;
                        let personalData = data.aadharCKYCDetailsIO.kycDetails.personalIdentifiableData
                            .personalDetails;

                        // Capture the personal details
                        firstName = personalData.firstName;
                        lastName = personalData.lastName;
                        dob = personalData.dob;
                        let prefix = personalData.prefix;

                        // Set the gender
                        let genderValue = (prefix === "Mr") ? "Mr" : (prefix === "Ms") ? "Ms" : "";
                        $("#mr_ms_gender").val(genderValue).change();
                        $("#mr_ms_gender").siblings('.placeholder').addClass("active");
                        $("#proposername").siblings('.placeholder').addClass("active");

                        // Permanent Address Details
                        permLine1 = personalData.permLine1 || '';
                        permLine2 = personalData.permLine2 || '';
                        permLine3 = personalData.permLine3 || '';
                        permCity = personalData.permCity || '';
                        permState = personalData.permState || '';
                        permPin = personalData.permPin || '';

                        // Update the form fields with the retrieved data
                        $("#house").val(permLine1);
                        $("#colony").val(permLine2);
                        $("#Landmark").val(permLine3);
                        $("#City").val(permCity);
                        $("#State").val(permState);
                        $("#Pincode").val(permPin);

                        $("#house").siblings('.placeholder').addClass("active");
                        $("#colony").siblings('.placeholder').addClass("active");
                        $("#Landmark").siblings('.placeholder').addClass("active");
                        $("#City").siblings('.placeholder').addClass("active");
                        $("#State").siblings('.placeholder').addClass("active");
                        $("#Pincode").siblings('.placeholder').addClass("active");

                        if (status === "1") {
                            getStatusTotalPremium();
                            $("#verifyAadhaarBtn").html('VERIFIED <i class="fa-solid fa-check ml-2"></i>').prop(
                                'disabled', true);

                            successBox.style.display = "flex";
                            successTitleElement.innerText =
                                `Aadhaar verification successful! You can now continue.`;

                            // Pre-fill form fields
                            $('#personalDetail').show();
                            $('#proposername').val(`${firstName} ${lastName}`).prop('readonly', true);
                            $("#proposername").siblings('.placeholder').addClass("active");
                            $('#proposerdob1').val(`${dob}`).prop('readonly', true);
                            $("#proposerdob1").siblings('.placeholder').addClass("active");
                        } else {
                            $('#verifyAadhaarBtn').prop('disabled', false).html('VERIFY');

                            errorBox.style.display = "flex";
                            errorTitleElement.innerText =
                                `${response.message}`;
                        }
                    } catch (error) {
                        $('#verifyAadhaarBtn').prop('disabled', false).html('VERIFY');

                        errorBox.style.display = "flex";
                        errorTitleElement.innerText =
                            `${response.message}`;
                    }

                    setTimeout(() => {
                        errorBox.style.display = 'none';
                        successBox.style.display = 'none';
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    $("#verifyAadhaarBtn").prop('disabled', false);

                    errorBox.style.display = "flex";
                    errorTitleElement.innerText = `An error occurred. Please try again later.`;

                    setTimeout(() => {
                        errorBox.style.display = 'none';
                    }, 3000);
                }
            });
        }

        function getStatusTotalPremium() {
            $.ajax({
                url: "{{ route('gettotalpremium') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    try {
                        // console.log('Response:', response);
                        let totalPremium = response.totalpremium;
                        console.log('Total Premium:', totalPremium);
                        $('#totalPremium').text(totalPremium);
                        $('#headtotal').text(totalPremium);
                    } catch (error) {
                        console.error('Error parsing the total premium response', error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching total premium', error);
                }
            });
        }
        // Function to check KYC status on page load
        window.onload = function() {
            verifyAlready();
        };


        // Function to verify KYC status on page load
        function verifyAlready() {
            let kyc = @JSON($skyc); // Server-side KYC status
            let kycDataType = @JSON($kyctype); // Server-side KYC type ('p' for PAN, 'a' for Aadhaar)
            let userData = @json($userdata);


            // Check if KYC is already verified
            if (kyc == 1) {
                console.log("KYC check in progress...");
                showVerified('Your KYC is already verified.');

                if (kycDataType == 'p') {
                    console.log("KYC check in progress2...");
                    $('#panCard').prop('checked', true);
                    // $('#aadhaarCard').closest('.parent-div').css({
                    //     'pointer-events': 'none',
                    //     'opacity': '0.5'
                    // });
                    // $('#otherCard').closest('.parent-div').css({
                    //     'pointer-events': 'none',
                    //     'opacity': '0.5'
                    // });
                    $('#personalDetail').show();

                    // Pre-fill the form fields with the data received
                    $('#proposername').val(userData.kyc_name).prop('readonly', true);
                    $("#proposername").siblings('.placeholder').addClass("active");
                    $('#proposerdob1').val(userData.dob).prop('readonly', true);
                    $("#proposerdob1").siblings('.placeholder').addClass("active");

                    // Assuming these are all string or nullable string fields
                    $("#customerpancardno").val(userData.panid || '');
                    $("#customerpancardDob").val(userData.dob || '');
                    $("#house").val(userData.house || '');
                    $("#colony").val(userData.colony || '');
                    $("#Landmark").val(userData.landmark || '');
                    $("#City").val(userData.city || '');
                    $("#State").val(userData.state || '');
                    $("#Pincode").val(userData.pincode).prop('readonly', false) || '';

                    $("#customerpancardno").siblings('.placeholder').addClass("active");
                    $("#customerpancardDob").siblings('.placeholder').addClass("active");
                    $("#house").siblings('.placeholder').addClass("active");
                    $("#colony").siblings('.placeholder').addClass("active");
                    $("#Landmark").siblings('.placeholder').addClass("active");
                    $("#City").siblings('.placeholder').addClass("active");
                    $("#State").siblings('.placeholder').addClass("active");
                    $("#Pincode").siblings('.placeholder').addClass("active");


                } else if (kycDataType == 'a') {
                    $('#aadhaarCard').prop('checked', true);
                    // $('#panCard').closest('.parent-div').css({
                    //     'pointer-events': 'none',
                    //     'opacity': '0.5'
                    // });
                    // $('#otherCard').closest('.parent-div').css({
                    //     'pointer-events': 'none',
                    //     'opacity': '0.5'
                    // });


                    $('#personalDetail').show();


                    // Pre-fill the form fields with the data received

                    $("#customerAadharGender").val(userData.mr_mrs).change();
                    $("#customerAadharGender").siblings('.placeholder').addClass("active");
                    $("#customerAadharno").val(userData.adharid || '');
                    $("#customerAadharName").val(userData.kyc_name || '');
                    $("#customerAadharDob").val(userData.dob || '');





                    $('#proposername').val(userData.kyc_name).prop('readonly', true);
                    $('#proposerdob1').val(userData.dob).prop('readonly', true);
                    $("#proposerdob1").siblings('.placeholder').addClass("active");

                    // Assuming these are all string or nullable string fields
                    $("#customerpancardno").val(userData.adharid || '');
                    $("#customerpancardDob").val(userData.dob || '');
                    $("#house").val(userData.house || '');
                    $("#colony").val(userData.colony || '');
                    $("#Landmark").val(userData.landmark || '');
                    $("#City").val(userData.city || '');
                    $("#State").val(userData.state || '');
                    $("#Pincode").val(userData.pincode || '');
                } else if (kycDataType == 'o') {
                    $('#otherCard').prop('checked', true);
                    // $('#aadhaarCard').closest('.parent-div').css({
                    //     'pointer-events': 'none',
                    //     'opacity': '0.5'
                    // });
                    // $('#panCard').closest('.parent-div').css({
                    //     'pointer-events': 'none',
                    //     'opacity': '0.5'
                    // });


                    $('#personalDetail').show();



                }
            } else {
                // KYC not verified, show a message or allow verification
                // console.log("KYC not verified yet.");
            }
        }

        // Function to show a message when KYC is verified
        function showVerified(message) {
            const successBox = document.querySelector('.MainverifiyedBox');
            const successTitleElement = successBox.querySelector('.verifiyed__title');
            successBox.style.display = "flex";
            successTitleElement.innerText = message;
        }


        var aMember = [];

        let severvalid;

        async function saveFormStepTwo() {

            if (!validateFormStepTwo()) {
                return false; // Prevent form submission if validation fails
            }
            var formData = new FormData(document.getElementById('formStepTwo'));
            // console.log(formData);

            formData.append('_token', "{{ csrf_token() }}");
            try {
                const response = await $.ajax({
                    url: "{{ route('proposalStepTwo') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                });

                // console.log(response);

                if (response.status == "0") {
                    // console.log(response.error);
                    displayError("Error: " + (response.error || "An unknown error occurred."),
                        "Form Submission", "formStepTwo");
                    return false;
                }
                var members = response.member;
                aMember = members.map(member => ({
                    id: member.id,
                    data: [],
                    age: member.age,
                    dob: member.dob
                }));

                // Code to generate HTML content
                var str = "";
                var smokingStr = "";

                ['cancermain', 'heartmain', 'hypertensionmain', 'breathingmain', 'endocrinemain',
                    'diabetesmain', 'musclesmain', 'livermain', 'kidneymain', 'automain', 'congenitalmain',
                    'hivaidsmain', 'anymain',
                    'hasmain', 'hasanymain', 'insurermain', 'premiummain', 'insurancemain', 'diagnosedmain'
                ].forEach((data, index) => {
                    var sectionStr = "";
                    var str2 = "";
                    members.forEach((member, mindex) => {
                        var checkboxId = `${data}${mindex + 1}Checkbox`;
                        var containerId = `${data}${mindex + 1}Container`;

                        if (index <= 14) {
                            if (data === 'anymain') {
                                // For 'anymain', use a textarea
                                sectionStr += `
                                    <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                                        <div class="mb-2">
                                            <!-- Checkbox and Label -->
                                            <input type="checkbox" data-id="${index + 1}" srno="1" user-id="${member.id}" id="${checkboxId}" class="${data}${mindex + 1}" name="${data}${mindex + 1}">
                                            <label for="${checkboxId}" class="pointer"> ${member.name.split(' ')[0]}</label>
                                        </div>
                                        <div class="input-container1 mt-2 ${data}${mindex + 1}" id="${containerId}" style="display: none;">
                                            <!-- Input Field -->
                                            <input type="tel" class="input form-control" srno="1" data-dob="${member.dob}" data-age="${member.age}" id="${data}${mindex + 1}date" data-id="${index + 1}" user-id="${member.id}" autocomplete="off" spellcheck="false" pattern="^(0[1-9]|1[0-2])\/[0-9]{4}$" title="Format: MM/YYYY" maxlength="7" oninput="filterDigits(this)" onkeyup="setDate('date',this,this.dataset.age)">
                                            <div class="placeholder1">Existing Since (MM/YYYY)</div>
                                            <!-- Textarea for "anymain" -->
                                            
                                        </div>
                                       <div id="${containerId}" style="display: none;">
                                         <textarea class="anymaintextarea form-control mt-2" 
                                                srno="1" data-dob="${member.dob}" 
                                                data-age="${member.age}" 
                                                id="${data}${mindex + 1}textarea" 
                                                data-id="${index + 1}" 
                                                user-id="${member.id}"  placeholder="Enter Description"
                                                rows="2" 
                                                autocomplete="off" 
                                                spellcheck="false" 
                                                maxlength="500" 
                                                onkeyup="setDate('des',this, this.dataset.age)"></textarea>
                                        </div>
                                    </div>
                                `;
                            } else {
                                // For other cases, maintain the existing input field
                                sectionStr += `
                                    <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                                        <div class="mb-2">
                                            <input type="checkbox" data-id="${index + 1}" srno="1" user-id="${member.id}" id="${checkboxId}" class="${data}${mindex + 1}" name="${data}${mindex + 1}">
                                            <label for="${checkboxId}" class="pointer"> ${member.name.split(' ')[0]}</label>
                                        </div>
                                        <div class="input-container1 mt-2 ${data}${mindex + 1}" id="${containerId}" style="display: none;">
                                            <input type="tel" class="input form-control" srno="1" data-dob="${member.dob}" data-age="${member.age}" id="${data}${mindex + 1}date" data-id="${index + 1}" user-id="${member.id}" autocomplete="off" spellcheck="false" pattern="^(0[1-9]|1[0-2])\/[0-9]{4}$" title="Format: MM/YYYY" maxlength="7" oninput="filterDigits(this)" onkeyup="setDate('date',this,this.dataset.age)">
                                            <div class="placeholder1">Existing Since (MM/YYYY)</div>
                                        </div>
                                    </div>
                                `;
                            }
                            $('#' + data + ' td .row').html(sectionStr);
                        } else {
                            str2 += `
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                                    <div class="mb-2">
                                        <input type="checkbox" data-id="${index - 14}" srno="2" user-id="${member.id}" id="${checkboxId}" class="${data}${mindex + 1}" name="${data}${mindex + 1}">
                                        <label for="${checkboxId}" class="pointer"> ${member.name.split(' ')[0]}</label>
                                    </div>
                                    <div class="input-container1 mt-2 ${data}${mindex + 1}" id="${containerId}" style="display: none;">
                                        <input type="text" class="input form-control" data-dob="${member.dob}" data-age="${member.age}" srno="2" id="${data}${mindex + 1}date" data-id="${index - 14}" user-id="${member.id}" autocomplete="off" spellcheck="false" pattern="^(0[1-9]|1[0-2])\/[0-9]{4}$" title="Format: MM/YYYY" maxlength="7" onkeyup="setDate('date',this,this.dataset.age)" oninput="filterDigits(this)">
                                        <div class="placeholder1">Existing Since (MM/YYYY)</div>
                                    </div>
                                </div>
                            `;
                            $('#' + data + ' td .row').html(str2);
                        }
                    });
                });

                // Ensure that updatePlaceholder is defined before usage
                var updatePlaceholder = {
                    cigarettesmain: "Daily Packets/30 ml pegs ",
                };

                var smokingSections = ['cigarettesmain'];

                smokingSections.forEach((smokdata, smokIndex) => {
                    var smokingStr = members.map((member, mindex) => {
                        var smokcheckboxId = `${smokdata}${mindex + 1}smokCheckbox`;
                        var smokcontainerId = `${smokdata}${mindex + 1}smokContainer`;

                        // Use the placeholder for this section, with a fallback if not defined
                        var placeholderText = updatePlaceholder[smokdata] || 'Default Placeholder';

                        return `
                            <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                                <div class="mb-2">
                                    <input type="checkbox" 
                                        class="${smokdata}${mindex + 1}" 
                                        srno="3" 
                                        data-id="${smokIndex + 1}" 
                                        user-id="${member.id}" 
                                        id="${smokcheckboxId}" 
                                        name="${smokdata}${mindex + 1}">
                                    <label for="${smokcheckboxId}" class="pointer">${member.name.split(' ')[0]}</label>
                                </div>
                                <div class="input-container1 mt-2 ${smokdata}${mindex + 1}" id="${smokcontainerId}" style="display: none;">
                                    <input type="text" 
                                        class="input form-control" 
                                        data-id="${smokIndex + 1}"
                                        data-age="${member.age}" 
                                        srno="3" 
                                        id="${smokdata}${mindex + 1}smok" 
                                        user-id="${member.id}" 
                                        autocomplete="off" 
                                        spellcheck="false" 
                                        maxlength="2" onkeyup="setDate('quantity',this,this.dataset.age)" 
                                        oninput="filterDigits(this)">
                                    <div class="placeholder1">${placeholderText}</div>
                                </div>
                                <div class="input-container1 container2 mt-3 ${smokdata}${mindex + 1}" id="${smokcontainerId}-since" style="display: none;">
                                    <input type="text" 
                                        data-id="${smokIndex + 1}"  data-dob="${member.dob}"
                                        data-age="${member.age}" 
                                        user-id="${member.id}" 
                                        srno="3" 
                                        id="${smokdata}${mindex + 1}" 
                                        class="input form-control" 
                                        autocomplete="off" 
                                        spellcheck="false" 
                                        pattern="^(0[1-9]|1[0-2])\/[0-9]{4}$" 
                                        title="Format: MM/YYYY" 
                                        maxlength="7" 
                                        onkeyup="setDate('date',this,this.dataset.age)" 
                                        oninput="filterDigits(this)">
                                    <div class="placeholder1">Existing Since (MM/YYYY)</div>
                                </div>
                            </div>
                        `;
                    }).join('');

                    // Insert the generated HTML into the appropriate section
                    $('#' + smokdata + ' td .row').html(smokingStr);
                });



                // Restore the state of checkboxes
                // restoreCheckboxState();

                // Handle checkbox change event
                $('input[type="checkbox"]').on('change', function() {
                    var checkboxId = $(this).attr('id');
                    var dataid = $(this).attr('data-id');
                    var userid = $(this).attr('user-id');
                    var srno = $(this).attr('srno');

                    if ($(this).is(':checked')) {
                        // console.log(aMember);
                        // console.log(aMember[0].data);
                        var foundMemberIndex = aMember.findIndex(member => member.id == userid);
                        if (foundMemberIndex !== -1) {
                            if (!Array.isArray(aMember[foundMemberIndex].data)) {
                                aMember[foundMemberIndex].data = [];
                            }
                            aMember[foundMemberIndex].data.push({
                                did: `${srno}.${dataid}`,
                                date: 0,
                                des: '',
                                quantity: 0
                            });
                        }
                    } else {
                        var foundMemberIndex = aMember.findIndex(member => member.id == userid);
                        if (foundMemberIndex !== -1) {
                            aMember[foundMemberIndex].data = aMember[foundMemberIndex].data.filter(
                                item => item.did !== `${srno}.${dataid}`
                            );
                        }
                    }

                    // Toggle visibility of input fields yha se aa rhi hai problem
                    var containerId = checkboxId.replace('Checkbox', 'Container');
                    // console.log(containerId);

                    if (checkboxId === 'agreeTerms' || checkboxId === 'addStandingInstruction' ||
                        checkboxId === 'emiInstruction' || checkboxId === 'debitInstruction') {
                        $('#' + containerId).toggle($(this).is(':checked'));
                        // console.log(containerId);
                        $('#agreeTerms').show();
                        $('#addStandingInstruction').show();
                        $('#emiInstruction').show();
                        $('#debitInstruction').show();
                    } else {
                        $('#' + containerId + ', #' + containerId + '-since').toggle($(this).is(
                            ':checked'));
                        $('#agreeTerms').show();
                        $('#addStandingInstruction').show();
                    }

                    saveCheckboxState();
                });
            } catch (error) {
                console.error("Error submitting form: ", error);
                return false; // Stop further execution if there is an error
            }
        }

        function saveCheckboxState() {
            var checkboxState = {};
            $('#agreeTerms').show();
            $('#addStandingInstruction').show();
            $('#emiInstruction').show();
            $('#debitInstruction').show();
            $('input[type="checkbox"]').each(function() {
                checkboxState[$(this).attr('id')] = $(this).is(':checked');
                $('#agreeTerms').show();
                $('#addStandingInstruction').show();
                $('#emiInstruction').show();
                $('#debitInstruction').show();
            });
            localStorage.setItem('checkboxState', JSON.stringify(checkboxState));
        }

        // function restoreCheckboxState() {
        //     var checkboxState = JSON.parse(localStorage.getItem('checkboxState')) || {};
        //     for (var checkboxId in checkboxState) {

        //         $('#' + checkboxId).prop('checked', checkboxState[checkboxId]);
        //         var containerId = checkboxId.replace('Checkbox', 'Container');
        //         $('#' + containerId + ', #' + containerId + '-since').toggle(checkboxState[checkboxId]);
        //     }
        // }


        function updateVisibilityBasedOnStates() {
            for (const [checkboxId, isChecked] of Object.entries(checkboxStates)) {
                $('#' + checkboxId).prop('checked', isChecked);
                var containerId = checkboxId.replace('Checkbox', 'Container');
                $('#' + containerId + ', #' + containerId + '-since').toggle(isChecked);
            }
        }


        // function setDate(input, age) {
        //     const errorBox = document.querySelector('.MainErrorBox');
        //     const errorTitleElement = errorBox?.querySelector('.error__title');
        //     const agreeTermsCheckbox = document.getElementById('agreeTerms');
        //     const successBox = document.querySelector('.MainverifiyedBox');
        //     const successTitleElement = successBox.querySelector('.verifiyed__title');

        //     let birthYear = new Date().getFullYear() - age;
        //     console.log(birthYear); 

        //     let value = input.value;
        //     var placeholder = $(input).siblings('.placeholder1');

        //     // Format the input value for MM/YYYY format
        //     if (value.length === 2 && !value.includes('/')) {
        //         input.value = value + '/';
        //     }

        //     if (value.length === 7) {
        //         var dataid = $(input).attr('data-id');
        //         var userid = $(input).attr('user-id');
        //         var srno = $(input).attr('srno');
        //         var foundMemberIndex = aMember.findIndex(member => member.id == userid);
        //         var dateIndex = aMember[foundMemberIndex].data.findIndex(item => item.did === `${srno}.${dataid}`);

        //         if (dateIndex !== -1) {
        //             if (srno == 3) {
        //                 var quantity = $(input).attr('id') + 'smok';
        //                 aMember[foundMemberIndex].data[dateIndex].quantity = $('#' + quantity).val();
        //                 aMember[foundMemberIndex].data[dateIndex].date = value;
        //             } else {
        //                 aMember[foundMemberIndex].data[dateIndex].date = value;
        //             }
        //         }

        //         if (value.trim() !== '') {
        //             placeholder.addClass('active');
        //         } else {
        //             placeholder.removeClass('active');
        //         }

        //         if (value.includes('/')) {
        //             let year = value.split('/')[1]; 


        //             if (parseInt(year) < birthYear) {
        //                 errorBox.style.display = "flex";
        //                 errorTitleElement.innerText = `Age mismatch.`;

        //                 setTimeout(() => {
        //                     errorBox.style.display = 'none';
        //                 }, 3000);
        //             } else {
        //                 successBox.style.display = "flex";
        //                 successTitleElement.innerText =
        //                     `Age matches`;
        //                 setTimeout(() => {
        //                     successBox.style.display = 'none';
        //                 }, 3000);
        //             }
        //         } else {
        //             console.log('Invalid format, please enter MM/YYYY');
        //         }
        //     }
        // }

        function setDate(slug, input, age) {
            var dataid = $(input).attr('data-id');
            var userid = $(input).attr('user-id');
            var srno = $(input).attr('srno');




            // console.log(slug);
            if (slug == 'date') {
                let value = input.value;
                var placeholder = $(input).siblings('.placeholder1');
                if (value.length === 2 && !value.includes('/')) {
                    input.value = value + '/';
                }

                // Check if input value has a length of 7 (MM/YYYY format)
                if (value.length === 7) {
                    var foundMemberIndex = aMember.findIndex(member => member.id == userid);
                    var dateIndex = aMember[foundMemberIndex].data.findIndex(item => item.did === `${srno}.${dataid}`);

                    if (dateIndex !== -1) {
                        //aMember[foundMemberIndex].data[dateIndex].des = $('#anymain1textarea').val();
                        if (srno == 3) {
                            var quantity = $(input).attr('id') + 'smok';
                            aMember[foundMemberIndex].data[dateIndex].quantity = $('#' + quantity).val();
                            aMember[foundMemberIndex].data[dateIndex].date = value;
                        } else {
                            aMember[foundMemberIndex].data[dateIndex].date = value;
                        }
                    }


                    if (value.trim() !== '') {
                        placeholder.addClass('active');
                    } else {
                        placeholder.removeClass('active');
                    }

                }
            } else if (slug == 'des') {
                var foundMemberIndex = aMember.findIndex(member => member.id == userid);
                var dateIndex = aMember[foundMemberIndex].data.findIndex(item => item.did === `${srno}.${dataid}`);

                if (dataid == 13) {
                    var inputid = $(input).attr('id')
                    aMember[foundMemberIndex].data[dateIndex].des = $('#' + inputid).val();
                }
            }
        }

        function filterDigits(input) {
            let value = input.value.replace(/\D/g, '');
            value = value.substring(0, 6);
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }
            input.value = value;
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('click', function(event) {
                const target = event.target;
                if (target.matches('.placeholder1')) {
                    const container = target.closest('.input-container1');
                    if (container) {
                        const input = container.querySelector('.input');
                        if (input) {
                            input.focus();
                            target.classList.add('active');
                        }
                    }
                }
            });

            document.body.addEventListener('focusin', function(event) {
                const target = event.target;
                if (target.matches('.input')) {
                    const container = target.closest('.input-container1');
                    if (container) {
                        const placeholder = container.querySelector('.placeholder1');
                        if (placeholder) {
                            placeholder.classList.add('active');
                        }
                    }
                }
            });

            document.body.addEventListener('focusout', function(event) {
                const target = event.target;
                if (target.matches('.input')) {
                    if (target.value === '') {
                        const container = target.closest('.input-container1');
                        if (container) {
                            const placeholder = container.querySelector('.placeholder1');
                            if (placeholder) {
                                placeholder.classList.remove('active');
                            }
                        }
                    }
                }
            });
        });

        function validateFormStepThree() {
            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox?.querySelector('.error__title');
            const agreeTermsCheckbox = document.getElementById('agreeTerms');
            // const emiInstructionCheckbox = document.getElementById('emiInstruction');
            // const debitInstructionCheckbox = document.getElementById('debitInstruction');
            // const addStandingInstructionCheckbox = document.getElementById('addStandingInstruction');

            if (!agreeTermsCheckbox?.checked) {
                if (errorTitleElement) {
                    errorTitleElement.innerText =
                        'Please agree to the terms and conditions before proceeding.';
                    errorBox.style.display = 'flex';
                    setTimeout(() => {
                        errorBox.style.display = 'none';
                    }, 3000);
                }
                // console.log('Validation failed. Please check the checkboxes.');
                return false;
            }

            const inputContainers = document.querySelectorAll('.input-container1, .container2');
            let validationFailed = false;
            const currentYear = new Date().getFullYear(); // Get the current year


            inputContainers.forEach(container => {
                if (getComputedStyle(container).display === 'none') {
                    return;
                }

                const inputFields = container.querySelectorAll('input');
                let hasError = false;

                // inputFields.forEach(inputField => {
                //     const inputValue = inputField.value.trim();

                //     // Check if the field is empty
                //     if (inputValue === '') {
                //         if (!hasError) {
                //             if (errorTitleElement) {
                //                 // console.log(inputField.getAttribute('data-age'));
                //                 errorTitleElement.innerText = 'Please fill in all required fields.';
                //                 errorBox.style.display = 'flex';
                //                 setTimeout(() => {
                //                     errorBox.style.display = 'none';
                //                 }, 3000);
                //             }
                //             inputField.classList.add("error");
                //             inputField.focus();
                //             hasError = true;
                //         }
                //         validationFailed = true;
                //     } else {
                //         inputField.classList.remove("error");
                //     }

                //     // Split the input value into month and year (assuming format is MM/YYYY)
                //     const dateParts = inputValue.split('/');
                //     if (inputValue.length > 2) {
                //         if (dateParts.length === 2) {
                //             const month = parseInt(dateParts[0], 10);
                //             const year = dateParts[1];

                //             // Get current year and month
                //             const currentYear = new Date().getFullYear();
                //             const currentMonth = new Date().getMonth() + 1; // Month is 0-indexed, so add 1

                //             // Validate the month (1 <= month <= 12)
                //             if (isNaN(month) || month < 1 || month > 12) {
                //                 if (!hasError) {
                //                     if (errorTitleElement) {
                //                         errorTitleElement.innerText = 'Month should be between 1 and 12.';
                //                         errorBox.style.display = 'flex';
                //                         setTimeout(() => {
                //                             errorBox.style.display = 'none';
                //                         }, 3000);
                //                     }
                //                     inputField.classList.add("error");
                //                     inputField.focus();
                //                     hasError = true;
                //                 }
                //                 validationFailed = true;
                //             }



                //             const age = parseInt(inputField.getAttribute('data-age'), 10);
                //             let dob = inputField.getAttribute('data-dob');

                //             let Birthmonth = null;

                //             if (dob && dob.includes('-')) {
                //                 const dobParts = dob.split('-');

                //                 Birthmonth = parseInt(dobParts[1], 10);
                //                 Birthmonth = Birthmonth.toString().padStart(2,
                //                     '0');
                //                 // console.log(Birthmonth);
                //             }



                //             const birthYear = new Date().getFullYear() - age;
                //             const yearNumber = parseInt(year, 10);

                //             if (yearNumber > currentYear || (yearNumber === currentYear && month >
                //                     currentMonth)) {
                //                 if (!hasError) {
                //                     if (errorTitleElement) {
                //                         errorTitleElement.innerText =
                //                             `Date cannot be in the future. The month should not be greater than ${currentMonth} of ${currentYear}.`;
                //                         errorBox.style.display = 'flex';
                //                         setTimeout(() => {
                //                             errorBox.style.display = 'none';
                //                         }, 3000);
                //                     }
                //                     inputField.classList.add("error");
                //                     inputField.focus();
                //                     hasError = true;
                //                 }
                //                 validationFailed = true;
                //             }

                //             // Validate the year format
                //             if (year.length !== 4 || isNaN(yearNumber) || yearNumber > currentYear) {
                //                 if (!hasError) {
                //                     if (errorTitleElement) {
                //                         errorTitleElement.innerText =
                //                             `Year should be in the format YYYY and before ${currentYear}.`;
                //                         errorBox.style.display = 'flex';
                //                         setTimeout(() => {
                //                             errorBox.style.display = 'none';
                //                         }, 3000);
                //                     }
                //                     inputField.classList.add("error");
                //                     inputField.focus();
                //                     hasError = true;
                //                 }
                //                 validationFailed = true;
                //             } else if (yearNumber < birthYear || (yearNumber === birthYear && month <
                //                     Birthmonth)) {

                //                 if (!hasError) {
                //                     if (errorTitleElement) {
                //                         errorTitleElement.innerText =
                //                             `Date should not be before the calculated Birth month/Birth year ${Birthmonth} /${birthYear}.`;
                //                         errorBox.style.display = 'flex';
                //                         setTimeout(() => {
                //                             errorBox.style.display = 'none';
                //                         }, 3000);
                //                     }
                //                     inputField.classList.add("error");
                //                     inputField.focus();
                //                     hasError = true;
                //                 }
                //                 validationFailed = true;
                //             }

                //         } else {
                //             if (!hasError) {
                //                 if (errorTitleElement) {
                //                     errorTitleElement.innerText =
                //                         'Please enter the date in the format MM/YYYY.';
                //                     errorBox.style.display = 'flex';
                //                     setTimeout(() => {
                //                         errorBox.style.display = 'none';
                //                     }, 3000);
                //                 }
                //                 inputField.classList.add("error");
                //                 inputField.focus();
                //                 hasError = true;
                //             }
                //             validationFailed = true;
                //         }
                //     }
                // });
                inputFields.forEach(inputField => {
                    const inputValue = inputField.value.trim();

                    // Check if the field is empty
                    if (inputValue === '') {
                        if (!hasError) {
                            if (errorTitleElement) {
                                errorTitleElement.innerText = 'Please fill in all required fields.';
                                errorBox.style.display = 'flex';
                                setTimeout(() => {
                                    errorBox.style.display = 'none';
                                }, 3000);
                            }
                            inputField.classList.add("error");
                            inputField.focus();
                            hasError = true;
                        }
                        validationFailed = true;
                    } else {
                        inputField.classList.remove("error");
                    }

                    // Split the input value into month and year (assuming format is MM/YYYY)
                    const dateParts = inputValue.split('/');
                    if (inputValue.length > 2) {
                        if (dateParts.length === 2) {
                            const month = parseInt(dateParts[0], 10);
                            const year = dateParts[1];

                            // Get current year and month
                            const currentYear = new Date().getFullYear();
                            const currentMonth = new Date().getMonth() + 1; // Month is 0-indexed, so add 1

                            // Validate the month (1 <= month <= 12)
                            if (isNaN(month) || month < 1 || month > 12) {
                                if (!hasError) {
                                    if (errorTitleElement) {
                                        errorTitleElement.innerText = 'Month should be between 1 and 12.';
                                        errorBox.style.display = 'flex';
                                        setTimeout(() => {
                                            errorBox.style.display = 'none';
                                        }, 3000);
                                    }
                                    inputField.classList.add("error");
                                    inputField.focus();
                                    hasError = true;
                                }
                                validationFailed = true;
                            }

                            // Retrieve birth details
                            const age = parseInt(inputField.getAttribute('data-age'), 10);
                            let dob = inputField.getAttribute('data-dob');

                            let Birthmonth = null;
                            if (dob && dob.includes('-')) {
                                const dobParts = dob.split('-');
                                Birthmonth = parseInt(dobParts[1], 10).toString().padStart(2, '0');
                            }

                            // Corrected birth year calculation
                            const birthYear = (new Date().getMonth() + 1 >= parseInt(Birthmonth, 10)) ? currentYear - age : currentYear - age - 1;
                            const yearNumber = parseInt(year, 10);

                            // Validate input date range: >= birth date and < current date
                            if (yearNumber < birthYear || (yearNumber === birthYear && month < parseInt(Birthmonth, 10))) {
                                if (!hasError) {
                                    if (errorTitleElement) {
                                        errorTitleElement.innerText = `Date should not be before the birth month/year: ${Birthmonth}/${birthYear}.`;
                                        errorBox.style.display = 'flex';
                                        setTimeout(() => {
                                            errorBox.style.display = 'none';
                                        }, 3000);
                                    }
                                    inputField.classList.add("error");
                                    inputField.focus();
                                    hasError = true;
                                }
                                validationFailed = true;
                            }

                            if (yearNumber > currentYear || (yearNumber === currentYear && month > currentMonth)) {
                                if (!hasError) {
                                    if (errorTitleElement) {
                                        errorTitleElement.innerText = `Date cannot be in the future. The month should not be greater than ${currentMonth} of ${currentYear}.`;
                                        errorBox.style.display = 'flex';
                                        setTimeout(() => {
                                            errorBox.style.display = 'none';
                                        }, 3000);
                                    }
                                    inputField.classList.add("error");
                                    inputField.focus();
                                    hasError = true;
                                }
                                validationFailed = true;
                            }

                            // Validate year format
                            if (year.length !== 4 || isNaN(yearNumber)) {
                                if (!hasError) {
                                    if (errorTitleElement) {
                                        errorTitleElement.innerText = `Year should be in the format YYYY.`;
                                        errorBox.style.display = 'flex';
                                        setTimeout(() => {
                                            errorBox.style.display = 'none';
                                        }, 3000);
                                    }
                                    inputField.classList.add("error");
                                    inputField.focus();
                                    hasError = true;
                                }
                                validationFailed = true;
                            }

                        } else {
                            if (!hasError) {
                                if (errorTitleElement) {
                                    errorTitleElement.innerText = 'Please enter the date in the format MM/YYYY.';
                                    errorBox.style.display = 'flex';
                                    setTimeout(() => {
                                        errorBox.style.display = 'none';
                                    }, 3000);
                                }
                                inputField.classList.add("error");
                                inputField.focus();
                                hasError = true;
                            }
                            validationFailed = true;
                        }
                    }
                });
            });

            return !validationFailed;


        }

        function resetFormData() {
            $('#proposerdetails').html('');
            $('#proposeraddress').html('');
            $('#insuredetails').html('');
            $('#nomineedetails').html('');
            $('#lifestylehistory').html('');
            $('#medicalhistory').html('');
            $('#medicalhistoryTwo').html('');
        }


        function saveFormStepThree() {
            resetFormData();
            var formData = aMember.filter(rec => rec != null);

            $.ajax({
                url: "{{ route('proposalStepThree') }}",
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    var proposer = response.proposar;
                    console.log(response);
                    var proposerStr = `


                      <div class="col-lg-12 col-md-12 col-sm-12">

                     <div id="tbl" class="table-responsive table-bordered tbl">
                        <table id="datasort" width="100%" data-ordering="false">
                            <thead class="tablehead">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Phone No.</th>
                                    <th scope="col">Date of Birth</th>
                                    <th scope="col">E-mail ID</th>
                                    <th scope="col">Emergency No.</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                      <td><p>${proposer.name}</p></td>
                                    <td><p>${proposer.mobile}</p></td>
                                    <td><p>${proposer.dob || 'N/A'}</p></td>
                                    <td><p>${proposer.email}</p></td>
                                    <td><p>${proposer.emergency_mobile}</p></td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                    
                </div>
                `;

                    $('#proposerdetails').html(proposerStr);

                    var proaddressStr = `
                <div class="col-lg-12 col-md-12 col-sm-12">

                     <div id="tbl" class="table-responsive table-bordered tbl">
                        <table id="datasort" width="100%" data-ordering="false">
                            <thead class="tablehead">
                                <tr>
                                    <th scope="col">Permanent Address</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> <p>${proposer.address}</p> </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                    
                </div>`;

                    $('#proposeraddress').html(proaddressStr);




                    var insurances = response.insures;

                    var container = $('#insuredetails');

                    var accumulatedHTML = `
                    
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div id="tbl" class="table-responsive table-bordered tbl">
                    <table id="datasort" width="100%" data-ordering="false">
                        <thead class="tablehead">
                            <tr>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Height</th>
                                <th>Weight</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

                    insurances.forEach((insure) => {
                        // var insureHeight = `${insure.height || 'N/A'}' ${insure.inch || 'N/A'}"`;
                        var insureHeight = `${insure.height || 'N/A'}' ${insure.inch !== undefined && insure.inch !== null ? insure.inch : 0}"`;
                        var rowHTML = `
                    <tr class="insure_name proposerdata">
                        <td><p>${insure.name}</p></td>
                        <td><p>${insure.age}</p></td>
                        <td><p>${insureHeight}</p></td>
                        <td><p>${insure.weight}</p></td>
                    </tr>
                `;
                        accumulatedHTML += rowHTML;
                    });

                    accumulatedHTML += `
                        </tbody>
                    </table>
                </div>
                </div>
            `;

                    container.html(accumulatedHTML);


                    var nominee = response.nominee;
                    var nomineeStr = `
             
                                <div class="col-md-12">
                    
                    <div id="tbl" class="table-responsive table-bordered tbl">
                        <table id="datasort" width="100%" data-ordering="false">
                            <thead class="tablehead">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Relation</th>
                                    <th scope="col">Nominee DOB</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <p>${nominee.name}</p>
                                    </td>
                                    <td>
                                        <p>${nominee.relation}</p>
                                    </td>
                                    <td>
                                        <p>${nominee.dob}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                `;

                    $('#nomineedetails').html(nomineeStr);

                    response.insures.forEach(insure => {
                        const medicalHistory = insure.ped;
                        const patientName = insure.name;
                        // console.log(medicalHistory);
                        if (medicalHistory && medicalHistory !== 'null') {
                            try {
                                const pedData = JSON.parse(medicalHistory);
                                const dids = pedData.map(item => item.did);

                                let lifestyleGroups = {};
                                let medicalGroups = {};
                                let medicalGroupsTwo = {};
                                dids.forEach(did => {
                                    if (did.startsWith("3.")) {
                                        // Lifestyle related diseases
                                        let diseaseId = did.replace(/\./g, '');
                                        let lifestyleData = @json(getconstant('LIFESTYLE'));
                                        let fdiseaseName = lifestyleData[diseaseId] || '';
                                        if (!lifestyleGroups[diseaseId]) {
                                            lifestyleGroups[diseaseId] = {
                                                diseaseName: fdiseaseName || diseaseId,
                                                patients: []
                                            };
                                        }

                                        pedData.forEach(entry => {
                                            if (entry.did === did) {
                                                const patientFirstName = patientName
                                                    .split(' ')[0] || 'N/A';
                                                const patientDate = entry.date;
                                                const patientquantity = entry.quantity;

                                                let patientGroup = lifestyleGroups[
                                                    diseaseId].patients.find(
                                                    patient => patient.name ===
                                                    patientFirstName && patient
                                                    .date === patientDate
                                                );

                                                if (!patientGroup) {
                                                    lifestyleGroups[diseaseId].patients
                                                        .push({
                                                            name: patientFirstName,
                                                            date: patientDate,
                                                            quantity: patientquantity,
                                                        });
                                                }
                                            }
                                        });
                                    } else if (did.startsWith("1.")) {
                                        // Medical related diseases (group 1.)
                                        let diseaseId = did.replace(/\./g, '');
                                        var diseaseName = @json(getconstant('CAREDISEASE'));
                                        var fdiseaseName = diseaseName[diseaseId] || '';
                                        if (!medicalGroups[diseaseId]) {
                                            medicalGroups[diseaseId] = {
                                                diseaseName: fdiseaseName || diseaseId,
                                                patients: []
                                            };
                                        }
                                        pedData.forEach(entry => {
                                            if (entry.did === did) {
                                                const patientFirstName = patientName
                                                    .split(' ')[0] || 'N/A';
                                                const patientDate = entry.date;
                                                const patientdes = entry.des;

                                                let patientGroup = medicalGroups[
                                                    diseaseId].patients.find(
                                                    patient => patient.name ===
                                                    patientFirstName && patient
                                                    .date === patientDate
                                                );

                                                if (!patientGroup) {
                                                    medicalGroups[diseaseId].patients
                                                        .push({
                                                            name: patientFirstName,
                                                            date: patientDate,
                                                            description: patientdes,
                                                        });
                                                }
                                            }
                                        });
                                    } else if (did.startsWith("2.")) {
                                        // Medical related diseases (group 2.)
                                        let diseaseId = did.replace(/\./g, '');
                                        var diseaseName = @json(getconstant('CAREDISEASE'));
                                        var fdiseaseName = diseaseName[diseaseId] || '';
                                        if (!medicalGroupsTwo[diseaseId]) {
                                            medicalGroupsTwo[diseaseId] = {
                                                diseaseName: fdiseaseName || diseaseId,
                                                patients: []
                                            };
                                        }

                                        pedData.forEach(entry => {

                                            if (entry.did === did) {
                                                const patientFirstName = patientName
                                                    .split(' ')[0] || 'N/A';
                                                const patientDate = entry.date;

                                                let patientGroup = medicalGroupsTwo[
                                                    diseaseId].patients.find(
                                                    patient => patient.name ===
                                                    patientFirstName && patient
                                                    .date === patientDate
                                                );

                                                if (!patientGroup) {
                                                    medicalGroupsTwo[diseaseId].patients
                                                        .push({
                                                            name: patientFirstName,
                                                            date: patientDate,
                                                        });
                                                }
                                            }
                                        });
                                    }
                                });

                                // Generate lifestyleStr for lifestyle-related diseases
                                let lifestyleStr = '';
                                for (let diseaseId in lifestyleGroups) {
                                    let group = lifestyleGroups[diseaseId];
                                    lifestyleStr += `
                                        <p class="mb-0">${group.diseaseName}</p>
                                        <div id="tbl" class="table-responsive table-bordered tbl">
                                            <table id="datasort" width="100%" data-ordering="false">
                                                <thead class="tablehead">
                                                    <tr>
                                                        <th scope="col">Patient Name</th>
                                                        <th scope="col">Quantity</th>
                                                        <th scope="col">Date of Disease</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                    `;
                                    group.patients.forEach(patient => {
                                        // console.log(patient);
                                        lifestyleStr += `
                                            <tr>
                                                <td><p>${patient.name}</p></td>
                                               <td><p>${patient.quantity || 'N/A'}</p></td> 
                                                <td><p>${patient.date}</p></td>
                                            </tr>
                                        `;
                                    });

                                    lifestyleStr += `
                                        </tbody>
                                    </table>
                                </div>
                                    `;
                                }

                                // Generate medicalStr for general medical diseases (group 1.)
                                let medicalStr = '';
                                for (let diseaseId in medicalGroups) {
                                    let group = medicalGroups[diseaseId];
                                    let showDescriptionColumn = false;

                                    group.patients.forEach(patient => {
                                        if (patient.description != null) {
                                            showDescriptionColumn = true;
                                        }
                                    });

                                    medicalStr += `
                                            <p class="mb-0">${group.diseaseName}</p>
                                            <div id="tbl" class="table-responsive table-bordered tbl">
                                                <table id="datasort" width="100%" data-ordering="false">
                                                    <thead class="tablehead">
                                                        <tr>
                                                            <th scope="col">Patient Name</th>
                                                            <th scope="col">Date of Disease</th>
                                                            ${showDescriptionColumn ? '<th scope="col">Description</th>' : ''} 
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                        `;

                                    group.patients.forEach(patient => {
                                        // console.log(patient);

                                        if (patient.description == null) {
                                            medicalStr += `
                                                    <tr>
                                                        <td><p>${patient.name}</p></td>
                                                        <td><p>${patient.date}</p></td>
                                                        ${showDescriptionColumn ? '<td><p>N/A</p></td>' : ''} <!-- Add placeholder if the column is visible -->
                                                    </tr>
                                                `;
                                        } else {
                                            medicalStr += `
                                                    <tr>
                                                        <td><p>${patient.name}</p></td>
                                                        <td><p>${patient.date}</p></td>
                                                        <td><p>${patient.description}</p></td>
                                                    </tr>
                                                `;
                                        }
                                    });

                                    medicalStr += `
                                            </tbody>
                                        </table>
                                        </div>
                                        `;

                                }

                                let medicalStrTwo = '';
                                for (let diseaseId in medicalGroupsTwo) {
                                    let group = medicalGroupsTwo[diseaseId];
                                    medicalStrTwo += `
                                        <p class="mb-0">${group.diseaseName}</p>
                                        <div id="tbl" class="table-responsive table-bordered tbl">
                                            <table id="datasort" width="100%" data-ordering="false">
                                                <thead class="tablehead">
                                                    <tr>
                                                        <th scope="col">Patient Name</th>
                                                        <th scope="col">Date of Disease</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                    `;
                                    group.patients.forEach(patient => {
                                        medicalStrTwo += `
                                            <tr>
                                                <td><p>${patient.name}</p></td>
                                                <td><p>${patient.date}</p></td>
                                            </tr>
                                        `;
                                    });
                                    medicalStrTwo += `
                                        </tbody>
                                    </table>
                                </div>
                                    `;
                                }

                                // Append to respective divs
                                if (lifestyleStr) {
                                    $('#lifestylehistory').append(lifestyleStr);
                                } else {
                                    $('#lifestylehistory').append('<p class="fs-500">N/A</p>');
                                }

                                if (medicalStr) {
                                    $('#medicalhistory').append(medicalStr);
                                } else {
                                    $('#medicalhistory').append('<p class="fs-500">N/A</p>');
                                }

                                if (medicalStrTwo) {
                                    $('#medicalhistoryTwo').append(medicalStrTwo);
                                } else {
                                    $('#medicalhistoryTwo').append('<p class="fs-500">N/A</p>');
                                }

                            } catch (error) {
                                console.error("Error parsing medical history (ped):", error);
                                $('#medicalhistory').append('<p class="fs-500">N/A</p>');
                                $('#lifestylehistory').append('<p class="fs-500">N/A</p>');
                            }
                        }
                    });





                },
            });
        }
        window.selectedAddOns = @json($addon);
        console.log("addon:",window.selectedAddOns);
        window.addAddonsRoute = "{{ route('addaddon') }}";
        window.csrfToken = "{{ csrf_token() }}";

        function updatePremium(amount, tenure) {
            document.getElementById('premium-amount').textContent = amount;
            fetch("{{ route('setpremium') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        premium: amount,
                        tenure: tenure,
                        addon: selectedAddOns
                    })
                })
                .then(response => response.json())
                .then(data => {
                    window.location.reload();
                })
                .catch(error => console.error('Error:', error));
        }

        function filterPlan() {
            var coverage = $("#coverage").val();
            fetch("{{ route('filterplan', ['slug' => 'cover']) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        coverage: coverage,
                        addon: selectedAddOns
                    })
                })
                .then(response => response.json())
                .then(data => {
                    window.location.reload();
                })
                .catch(error => console.error('Error:', error));
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

        function goToPayment() {
            // console.log("Ram Is Back");
            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox?.querySelector('.error__title');
            const successBox = document.querySelector('.MainverifiyedBox');
            const successTitleElement = successBox.querySelector('.verifiyed__title');
            document.getElementById("loader").style.display = "flex";

            fetch("{{ route('createpolicy') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    // console.log(data);

                    document.getElementById("loader").style.display = "none";

                    if (data.status === '1') {
                        successBox.style.display = "flex";
                        successTitleElement.innerText =
                            `Payment Process Started!`;
                        setTimeout(() => {
                            successBox.style.display = 'none';
                        }, 3000);
                        // alert("Payment Process Started!");
                        window.location.href = "{{ route('carepayment') }}";
                    } else {
                        const errMsg = data.error[0]?.errDescription || "An unknown error occurred.";
                        document.getElementById('errorMessage').innerText = errMsg;
                        // document.getElementById('Errorbtn').style.display = 'block';
                        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                        errorModal.show();
                        console.log(data.error[0].errDescription);

                        // errorTitleElement.innerText = `${data.error[0].errDescription}`;
                        // errorBox.style.display = 'flex';
                        // setTimeout(() => {
                        //     errorBox.style.display = 'none';
                        // }, 3000);
                        // alert("Error: " + data.error);
                    }
                })
                .catch(error => {
                    console.log(error);
                    document.getElementById("loader").style.display = "none";
                    // alert("An error occurred. Please try again.");
                    errorTitleElement.innerText = `An error occurred. Please try again.`;
                    errorBox.style.display = 'flex';
                    setTimeout(() => {
                        errorBox.style.display = 'none';
                    }, 3000);
                });
        }

        // fetch state and district function start 
        function acpincode(element) {
            const pincodeInput = element;
            const maxLength = 6;

            pincodeInput.value = pincodeInput.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            const pincode = pincodeInput.value;

            if (pincode.length === 6) {
                var sUrl = "{{ route('acdetails') }}";
                fetch(sUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            pincode: pincode,
                            _token: '{{ csrf_token() }}',
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            const state = data[0].state;
                            const district = data[0].district;

                            if (pincodeInput.id === "Pincode") {
                                $('#City').val(district);
                                $('#City').siblings('.placeholder').addClass("active");
                                $('#State').val(state);
                                $('#State').siblings('.placeholder').addClass("active");
                            } else if (pincodeInput.id === "commcurrentPincode") {
                                $('#commcurrentCity').val(district);
                                $('#commcurrentCity').siblings('.placeholder').addClass("active");
                                $('#commcurrentState').val(state);
                                $('#commcurrentState').siblings('.placeholder').addClass("active");
                            }
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            }
        }

        // fetch state and district function start 

        function uploadDocument() {
            event.preventDefault(); // Prevent default form submission

            // Get selected dropdown values
            const identityValue = $('#identityTypeProof').val();
            const addressValue = $('#addressTypeProof').val();

            // Validate that both dropdowns have values
            if (!identityValue) {
                errorTitleElement.innerText = `Please select an Identity Proof Type.`;
                errorBox.style.display = 'flex';
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                // alert('Please select an Identity Proof Type.');
                return false;
            }

            if (!addressValue) {
                errorTitleElement.innerText = `Please select an Address Proof Type.`;
                errorBox.style.display = 'flex';
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                // alert('Please select an Address Proof Type.');
                return false;
            }

            // Dynamically validate shown input fields based on dropdown selection
            let isValid = true;
            let errorMessage = '';


            // Check for the address proof inputs
            if (addressValue === 'aadhar') {
                if (!$('#addressidentity_aadhar').val()) {
                    isValid = false;
                    errorMessage = 'Please upload Address Aadhaar (Front) Proof.';
                }
                // else if (!$('#addressidentity_aadharBack').val()) {
                //     isValid = false;
                //     errorMessage = 'Please upload Address Aadhaar (Back) Proof.';
                // }
            } else if (addressValue === 'pan' && !$('#addresspancard').val()) {
                isValid = false;
                errorMessage = 'Please upload Address PAN Proof.';
            } else if (addressValue === 'passport' && !$('#addresspassportcard').val()) {
                isValid = false;
                errorMessage = 'Please upload Address Passport Proof.';
            } else if (addressValue === 'DrivingLicense') {
                if (!$('#addressDlFront').val()) {
                    isValid = false;
                    errorMessage = 'Please upload Address Driving License (Front) Proof.';
                }
                // else if (!$('#addressDlBack').val()) {
                //     isValid = false;
                //     errorMessage = 'Please upload Address Driving License (Back) Proof.';
                // }
            } else if (addressValue === 'VoterID') {
                if (!$('#addressVoterFront').val()) {
                    isValid = false;
                    errorMessage = 'Please upload Address Voter ID (Front) Proof.';
                }
                // else if (!$('#addressVoterBack').val()) {
                //     isValid = false;
                //     errorMessage = 'Please upload Address Voter ID (Back) Proof.';
                // }
            } else if (addressValue === 'Form60' && !$('#addressForm60').val()) {
                isValid = false;
                errorMessage = 'Please upload Address Form 60 Proof.';
            }

            // Check for the identity proof inputs
            if (identityValue === 'aadhar') {
                if (!$('#identity_aadhar').val()) {
                    isValid = false;
                    errorMessage = 'Please upload Identity Aadhaar (Front) Proof.';
                }
                //  else if (!$('#identity_aadharBack').val()) {
                //     isValid = false;
                //     errorMessage = 'Please upload Identity Aadhaar (Back) Proof.';
                // }
            } else if (identityValue === 'pan' && !$('#identity_pancard').val()) {
                isValid = false;
                errorMessage = 'Please upload Identity PAN Proof.';
            } else if (identityValue === 'passport' && !$('#identitypassportcard').val()) {
                isValid = false;
                errorMessage = 'Please upload Identity Passport Proof.';
            } else if (identityValue === 'DrivingLicense') {
                if (!$('#DrivingFront').val()) {
                    isValid = false;
                    errorMessage = 'Please upload Identity Driving License (Front) Proof.';
                }
                //  else if (!$('#DrivingBack').val()) {
                //     isValid = false;
                //     errorMessage = 'Please upload Identity Driving License (Back) Proof.';
                // }
            } else if (identityValue === 'VoterID') {
                if (!$('#VoterFront').val()) {
                    isValid = false;
                    errorMessage = 'Please upload Identity Voter ID (Front) Proof.';
                }
                // else if (!$('#VoterBack').val()) {
                //     isValid = false;
                //     errorMessage = 'Please upload Identity Voter ID (Back) Proof.';
                // }
            } else if (identityValue === 'Form60' && !$('#Form60').val()) {
                isValid = false;
                errorMessage = 'Please upload Identity Form 60 Proof.';
            }



            // If validation fails, show the error message
            if (!isValid) {

                alert(errorMessage);
                return false;
            }


            const fileInputs = document.querySelectorAll('.fileInput');
            let formData = new FormData();


            fileInputs.forEach(input => {
                if (input.files && input.files.length > 0) {
                    formData.append(input.name, input.files[0]);
                    console.log(`File uploaded in ${input.id}: ${input.files[0].name}`);
                }
            });


            formData.append('_token', '{{ csrf_token() }}');

            var sUrl = "{{ route('uploadfile') }}";
            console.log(formData);
            // return false;
            fetch(sUrl, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => {
                    console.log(response);
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);

                    if (data.kyc === "1") {
                        const errorBox = document.querySelector('.MainErrorBox');
                        const errorTitleElement = errorBox.querySelector('.error__title');
                        const successBox = document.querySelector('.MainverifiyedBox');
                        const successTitleElement = successBox.querySelector('.verifiyed__title');

                        successBox.style.display = "flex";
                        successTitleElement.innerText =
                            `Others verification successful! You can now continue.`;
                        setTimeout(() => {
                            successBox.style.display = 'none';
                        }, 3000);

                        $('#uploadbtn').text('Uploaded');
                        $('#uploadbtn').prop('disabled', true);
                        $('#personalDetail').show();
                        // $('#proposername').val(`${firstName} ${lastName}`).prop('readonly', true);
                        // $("#proposername").siblings('.placeholder').addClass("active");
                        // $('#proposerdob1').val(`${dob}`).prop('readonly', true);
                        // $("#proposerdob1").siblings('.placeholder').addClass("active");
                    } else {


                        errorBox.style.display = "flex";
                        errorTitleElement.innerText =
                            `${response.message}`;
                    }

                })

        }



        // uploadDocument function start 


        function updateFileName(inputId) {
            const fileInput = document.getElementById(inputId);

            if (fileInput.files && fileInput.files.length > 0) {
                let fileName = fileInput.files[0].name;

                const maxLength = 20;

                if (fileName.length > maxLength) {
                    fileName = fileName.substring(0, maxLength) + '...';
                }

                const label = fileInput.previousElementSibling;
                if (label && label.classList.contains('fileNameDisplay')) {
                    label.textContent = `Selected: ${fileName}`;
                }
                console.log(`File selected in ${inputId}: ${fileName}`);
            }
        }
















        // if (inputElement) {
        //                 let memberAge = parseInt(age);
        //                 document.getElementById('self-preage').textContent = `${memberAge} years`;
        //                 let currentYear = new Date().getFullYear();
        //                 let currentDate = new Date();
        //                 let calculatedDobYear = currentYear - memberAge;

        //                 // Check if the input field (DOB) is not empty
        //                 if (inputElement) {
        //                 let memberAge = parseInt(age); // The existing age (could be useful for comparison)
        //                 document.getElementById('self-preage').textContent = `${memberAge} years`; // Set the text with the current age

        //                 let currentYear = new Date().getFullYear();
        //                 let currentDate = new Date();

        //                 // The input date (Date of Birth) entered by the user
        //                 if (inputElement) {
        //                 let memberAge = parseInt(age); // The existing age (could be useful for comparison)
        //                 document.getElementById('self-preage').textContent = `${memberAge} years`; // Set the text with the current age

        //                 let currentYear = new Date().getFullYear();
        //                 let currentDate = new Date();

        //                 // The input date (Date of Birth) entered by the user
        //                 if (inputElement.value) {
        //                     let enteredDate = new Date(inputElement.value);
        //                     let enteredYear = enteredDate.getFullYear();

        //                     if (isNaN(enteredDate)) {
        //                         isValid = false;
        //                         displayError(`Invalid date format for ${memberId}`, inputId);
        //                         inputElement.classList.add("error");
        //                         return false;
        //                     }

        //                     if (enteredDate > currentDate) {
        //                         isValid = false;
        //                         displayError("Date of Birth cannot be in the future", inputId);
        //                         inputElement.classList.add("error");
        //                         return false;
        //                     }

        //                     let enteredMonth = enteredDate.getMonth();
        //                     let enteredDay = enteredDate.getDate();
        //                     let currentMonth = currentDate.getMonth();
        //                     let currentDay = currentDate.getDate();

        //                     // Adjust the calculated DOB year if the birthday hasn't occurred yet this year
        //                     if (currentMonth < enteredMonth || (currentMonth === enteredMonth && currentDay < enteredDay)) {
        //                         calculatedDobYear = currentYear - memberAge - 1;
        //                     }

        //                     // Calculate the age based on the Date of Birth entered
        //                     let calculatedAge = currentYear - enteredYear;

        //                     // Adjust if the birthday hasn't occurred yet this year
        //                     if (currentMonth < enteredMonth || (currentMonth === enteredMonth && currentDay < enteredDay)) {
        //                         calculatedAge--; // Subtract 1 year if the birthday hasn't passed yet
        //                     }

        //                     // Check if the element with id 'self-newage' exists before setting its textContent
        //                     const ageElement = document.getElementById('self-newage');
        //                     if (ageElement) {
        //                         ageElement.textContent = `${calculatedAge} years`; // Display the calculated age
        //                     } else {
        //                         console.error("Element with id 'self-newage' not found.");
        //                     }

        //                     if (enteredYear !== calculatedDobYear) {
        //                         // console.log(enteredYear);
        //                         // console.log(calculatedDobYear);

        //                         $('#premimumchange').modal('show');
        //                         // displayError(`Invalid Age for ${memberId}. Expected ${calculatedDobYear} year.`, '');
        //                         // inputElement.classList.add("error");
        //                         isValid = false;
        //                         return false;
        //                     }
        //                 } else {
        //                     isValid = false;
        //                     displayError("Date of Birth cannot be empty", inputId);
        //                     inputElement.classList.add("error");
        //                     return false;
        //                 }
        //             }

        //                 else {
        //                     isValid = false;
        //                     displayError("Date of Birth cannot be empty", inputId);
        //                     inputElement.classList.add("error");
        //                     return false;
        //                 }
        //             }

        //                 else {
        //                     isValid = false;
        //                     displayError("Date of Birth cannot be empty", inputId);
        //                     inputElement.classList.add("error");
        //                     return false;
        //                 }
        //             } 

        // window load pincode fetch city start 
        window.addEventListener('DOMContentLoaded', function() {
            const pincodeInput = document.getElementById('Pincode');
            const compincodeInput = document.getElementById('commcurrentPincode');
            if (pincodeInput.value.trim().length === 6) {
                acpincode(pincodeInput);
            }
            if (compincodeInput.value.trim().length === 6) {
                acpincode(compincodeInput);
            }
        });



        // window load pincode fetch city end

        function calculateAge(birthDate, otherDate) {
            birthDate = new Date(birthDate);
            otherDate = new Date(otherDate);

            var years = (otherDate.getFullYear() - birthDate.getFullYear());

            if (otherDate.getMonth() < birthDate.getMonth() ||
                otherDate.getMonth() == birthDate.getMonth() && otherDate.getDate() < birthDate.getDate()) {
                years--;
            }
            return years;
        }

        function othercalculateAge(fbirthDate, fotherDate) {
            fbirthDate = new Date(fbirthDate);
            fotherDate = new Date(fotherDate);

            var fyears = (fotherDate.getFullYear() - fbirthDate.getFullYear());

            if (fotherDate.getMonth() < fbirthDate.getMonth() ||
                fotherDate.getMonth() == fbirthDate.getMonth() && fotherDate.getDate() < fbirthDate.getDate()) {
                fyears--;
            }
            return fyears;
        }


        var ApplyButton = document.querySelector("#updatechangepre");
        ApplyButton.addEventListener("click", function() {
            $('#applypremium').click();
            // $('#premimumchange').modal('hide');
        });
        var closeButton = document.querySelector("#cancelpremium");
        closeButton.addEventListener("click", function() {
            $('#premimumchange').modal('hide');
        });




        // Generalize the formatDateInput function dob input type
        function formatDateInput(event) {
            let input = event.target;
            let value = input.value;

            value = value.replace(/\D/g, '');

            if (value.length >= 3) {
                value = value.slice(0, 2) + '-' + value.slice(2);
            }
            if (value.length >= 6) {
                value = value.slice(0, 5) + '-' + value.slice(5);
            }

            input.value = value;


            let errorId = input.id + 'Error';
            clearErrorTwo(errorId);
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
    </script>


</body>

</html>