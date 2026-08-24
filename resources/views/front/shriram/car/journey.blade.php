<?php
use App\Models\JourneyUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Top Plans</title>
    @include('front.partial.csslink')
</head>
<style>
    .fa-pen {
        color: #0980FF;
        cursor: pointer;
        font-size: 14px;
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

    .btn:hover {
        color: #6B6A75 !important;
        background-color: #e9ecef !important;
    }

    .btn:active {
        border-color: none !important
    }

    select {
        -webkit-appearance: auto;
        -moz-appearance: auto;
        appearance: auto !important;
    }

    .btn {
        color: #6B6A75 !important;
    }

    .input.error {
        border: 1px solid red;
    }

    .error-message {
        color: red;
        font-size: 0.8em;
        /* float: right; */
        margin-bottom: 0.8em !important;
    }

    .input {
        padding: 8px !important;
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
            right: 0px !important;
            top: 42% !important;
            z-index: 1 !important;
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

    .MainErrorBox {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        position: absolute;
        right: 30px;
        top: 12%;
        width: auto;
        padding: 12px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: start;
        background: #EF665B;
        border-radius: 8px;
        box-shadow: 0px 0px 5px -3px #111;
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
        width: 500px;
        max-width: 500px;
        margin: 8rem auto !important;
    }

    #editOtp {
        position: absolute;
        right: 7px;
        top: 5px;
    }
</style>

<body class="planlistbg">
    @php
        $user = $data['user']??'';
        // $verifiy = $data;
       // dd($user,$data,$data['nominee']);
        $nominee = $data['nominee'][0] ??'';
        
    @endphp
    @include('front.partial.header')
    <section id="planrow">
        <div class="container-fluid">
            <div class="row filblock">


                <!-- Left Col Start -->
                <div class="col-md-12 col-lg-12 p-0 mb-2">
                    <a href="#" id="backPage" class="smlink" style="display: block";><span><i
                                class="bi bi-arrow-left"></i></span> Go back to Previous</a>

                    <a href="#" class="smlink prev-step" id="backSlide" style="display: none";><span><i
                                class="bi bi-arrow-left"></i></span> Go
                        back to Previous</a>
                    <!-- <button type="button" class="Previous prev-step">Previous</button> -->
                    <div class="MainErrorBox" style="float: right;display:none;"><span class="error__icon"><i
                                class="fa-solid fa-circle-exclamation"></i></span>
                        <p class="error__title  mb-0" style="margin-right:10px;">hello</p> <span class="error__close"><i
                                class="fa-solid fa-xmark"></i></span>
                    </div>
                </div>
                <div class="col-md-8 col-lg-8 col-xl-8 ">

                    <div class="row mb-3">

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
                                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                                            <label for="field1" class="form-label">Please Provide Pan
                                                                Card Info</label>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                                            <div class="btn_one ml-2">
                                                                <input type="checkbox" id="panCard" name="customerkyc"
                                                                    value="customerkyc" checked disabled>
                                                                <label for="panCard" style="margin-left: 3px;">PAN
                                                                    Card</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">

                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">

                                                        </div>
                                                        <div id="pancardDetails" class="col-md-12 col-lg-12 col-sm-12">
                                                            <label for="field1" class="form-label">Please Provide Pan
                                                                Card Info</label>
                                                            <div id="panForm">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="input-container mb-3">
                                                                            <input type="text"
                                                                                name="customerpancardno"
                                                                                class="input form-control"
                                                                                id="customerpancardno"
                                                                                value="{{ !empty($user->panid) ? $user->panid : '' }}"
                                                                                autocomplete="off" spellcheck="false"
                                                                                maxlength="10"
                                                                                oninput="clearErrorOne('customerpancardnoError')"
                                                                                style="text-transform: uppercase;">
                                                                            <div class="placeholder">PAN NO.</div>
                                                                            <span class="error-message"
                                                                                id="customerpancardnoError"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-6">
                                                                        <div class="input-container mb-3 ">
                                                                            <div class="input-group1 datepickerdiv">
                                                                                <input type="text"
                                                                                    name="customerpancardDob"
                                                                                    class="input form-control datepicker"
                                                                                    id="customerpancardDob"
                                                                                    value="{{ !empty($user->dob) ? $user->dob : '' }}"
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


                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                                            <label for="field1" class="form-label">Proposer's
                                                                details:</label>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="ownername"
                                                                    class="input form-control"
                                                                    value="{{ !empty($user->name) ? $user->name : '' }}"
                                                                    id="ownername" autocomplete="off"
                                                                    spellcheck="false" maxlength="50"
                                                                    oninput="clearErrorOne('ownernameError')">
                                                                <div class="placeholder">Full Name(as per RC)</div>
                                                                <span class="error-message"
                                                                    id="ownernameError"></span>
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="ownermob"
                                                                    class="input form-control"
                                                                    value="{{ !empty($user->mobile) ? $user->mobile : '' }}"
                                                                    id="ownermob" autocomplete="off"
                                                                    spellcheck="false" maxlength="10"
                                                                    oninput="handleSubmit('ownermobError',this,10)" />
                                                                <div class="placeholder">Mobile Number</div>
                                                                <span class="error-message" id="ownermobError"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                                            <label for="field1" class="form-label">Address:</label>
                                                            <p>It will be used to send physical copy of your policy</p>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2 d-flex">
                                                                <input type="text" name="owneremail"
                                                                    class="input form-control"
                                                                    value="{{ !empty($user->email) ? $user->email : '' }}"
                                                                    id="owneremail" autocomplete="off"
                                                                    spellcheck="false" maxlength="50"
                                                                    oninput="clearErrorOne('owneremailError')">
                                                                <div class="placeholder">Email Address</div>
                                                                <span class="error-message"
                                                                    id="owneremailError"></span>
                                                                <a href="#" id="editOtp" data-toggle="modal"
                                                                    data-target="#enterOtp"><i
                                                                        class="fa-solid fa-key"></i></a>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="addressone"
                                                                    class="input form-control"
                                                                    value="{{ !empty($user->house) ? $user->house : '' }}"
                                                                    id="addressone" autocomplete="off"
                                                                    spellcheck="false" maxlength="50"
                                                                    oninput="clearErrorOne('addressoneError')">
                                                                <div class="placeholder">Address </div>
                                                                <span class="error-message"
                                                                    id="addressoneError"></span>
                                                            </div>
                                                        </div>
                                                        {{-- <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="addresstwo"
                                                                    class="input form-control" value=""
                                                                    id="addresstwo" autocomplete="off"
                                                                    spellcheck="false" maxlength="50"
                                                                    oninput="clearErrorOne('addresstwoError')">
                                                                <div class="placeholder">Address 2</div>
                                                                <span class="error-message"
                                                                    id="addresstwoError"></span>
                                                            </div>
                                                        </div> --}}


                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="pincode"
                                                                    value="{{ !empty($user->pincode) ? $user->pincode : '' }}"
                                                                    class="input form-control" id="pincode"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="6"
                                                                    oninput="handleSubmit('pincodeError',this,6)" />

                                                                <div class="placeholder">Pincode</div>
                                                                <span class="error-message" id="pincodeError"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="cityname"
                                                                    value="{{ !empty($user->city) ? $user->city : '' }}"
                                                                    class="input form-control" id="cityname"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="25"
                                                                    oninput="clearErrorOne('citynameError')">
                                                                <div class="placeholder">City</div>
                                                                <span class="error-message" id="citynameError"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="state"
                                                                    value="{{ !empty($user->state) ? $user->state : '' }}"
                                                                    class="input form-control" id="state"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="6"
                                                                    oninput="clearErrorOne('stateError')">
                                                                <div class="placeholder">State</div>
                                                                <span class="error-message" id="stateError"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                                                            <label for="field1" class="form-label">Nominee:</label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container">
                                                                <input type="text" name="nomineename"
                                                                    class="input form-control" id="nomineename"
                                                                    autocomplete="off" spellcheck="false"
                                                                    maxlength="25"
                                                                    value="{{ !empty($nominee->name) ? $nominee->name : '' }}"
                                                                    oninput="clearErrorOne('nomineenameError')" />
                                                                <div class="placeholder">Enter Nominee Full Name</div>
                                                                <span id="nomineenameError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container">
                                                                <div class="input-group1 datepickerdiv">
                                                                    <input type="text" name="nomineedob"
                                                                        class="input form-control datepicker"
                                                                        id="nomineedob" autocomplete="off"
                                                                        spellcheck="false" maxlength="25"
                                                                        value="{{ !empty($nominee->dob) ? $nominee->dob : '' }}"
                                                                        oninput="clearErrorOne('nomineedobError')" />
                                                                    <div class="placeholder">D.O.B (DD-MM-YYYY)</div>
                                                                    <button class="btn calendarButton" type="button">
                                                                        <i class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                                <span id="nomineedobError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <div class="input-container">
                                                                <select name="nomineerelation" id="nomineerelation"
                                                                    class="input form-control"
                                                                    oninput="clearErrorOne('nomineerelationError')">
                                                                    <option value="">Select</option>
                                                                    <option value="Spouse"
                                                                        @selected($nominee->relation == 'Spouse')>Spouse</option>
                                                                    <option value="Father"@selected($nominee->relation == 'Father')>
                                                                        Father</option>
                                                                    <option value="Mother"@selected($nominee->relation == 'Mother')>
                                                                        Mother</option>
                                                                    <option
                                                                        value="Brother"@selected($nominee->relation == 'Brother')>
                                                                        Brother</option>
                                                                    <option value="Sister"@selected($nominee->relation == 'Sister')>
                                                                        Sister</option>
                                                                    <option value="Son"@selected($nominee->relation == 'Son')>
                                                                        Son</option>
                                                                    <option
                                                                        value="Daughter"@selected($nominee->relation == 'Daughter')>
                                                                        Daughter</option>
                                                                </select>
                                                                <div class="placeholder">Relation</div>
                                                                <span id="nomineerelationError"
                                                                    class="error-message"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <input type="submit" name="" id=""
                                                        class="d-none">
                                                    <button type="button" id="next1"
                                                        class="membersection next-step"
                                                        onclick="validateFormStepOne(event)">Continue </button>
                                                </form>
                                            </div>
                                            <div class="step step-2 ">
                                                <form id="formStepTwo">
                                                    <section class="hightlight mb-3">
                                                        <div class="row">
                                                            <div class="col-lg-10 col-md-10 col-sm-6 mb-2">
                                                                <h6 class="proposerhead">Verify Policy Details</h6>
                                                                <p class="smlink"><span><i class="fa-solid fa-circle"
                                                                            style="color: #008000;"></i></span>
                                                                    RJ26ds4342</p>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-6">
                                                                <button type="button" class="edit"
                                                                    style="float: right;">Edit</button>

                                                                <!-- <a href="#" class="Previous">Edit</a> -->
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                                                                <span class="smallLabel">Make</span>
                                                                <p class="smlink" id="make"></p>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                                                                <span class="smallLabel">Model & Vinmart</span>
                                                                <p class="smlink" id="modelvin"></p>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                                                                <span class="smallLabel">Cubic Capacity</span>
                                                                <p class="smlink" id="cubiccapacity"></p>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                                                                <span class="smallLabel">Date of Registration</span>
                                                                <p class="smlink" id="regdob"></p>
                                                            </div>
                                                            <div class="col-lg-4 col-md-3 col-sm-12 mb-3">
                                                                <span class="smallLabel">Insured Declared
                                                                    Value(IDV)</span>
                                                                <p class="smlink" id="insudeclared"></p>
                                                            </div>

                                                            <div class="col-lg-4 col-md-3 col-sm-12 mb-3">
                                                                <span class="smallLabel">Chassis Number</span>
                                                                <p class="smlink" id="chassisnumber"><span><i
                                                                            class="fa-solid fa-pen"></i></span></p>
                                                            </div>
                                                            <div class="col-lg-4 col-md-3 col-sm-12 mb-3">
                                                                <span class="smallLabel">Engine Number</span>
                                                                <p class="smlink" id="engnumber"><span><i
                                                                            class="fa-solid fa-pen"></i></span></p>
                                                            </div>
                                                        </div>

                                                    </section>

                                                    <div class="row mb-3">
                                                        <div class="col-md-8 col-lg-12 col-sm-12">
                                                            <label for="field1"
                                                                class="form-label">Hypothecation/Loan</label>

                                                            <div style="float: right">
                                                                <label class="switch">
                                                                    <input type="checkbox" id="hypotheloan"
                                                                        name="hypotheloan">
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row mb-3" id="">
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <select name="bankloantype" id="bankloantype"
                                                                    class="input form-control"
                                                                    oninput="clearErrorTwo('bankloantypeError')">
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
                                                                <div class="placeholder">Enter Bank/ Loan provider
                                                                </div>
                                                                <span class="error-message"
                                                                    id="bankloantypeError"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="bankloaninput"
                                                                    class="input form-control" value=""
                                                                    id="bankloaninput" autocomplete="off"
                                                                    spellcheck="false" maxlength="50"
                                                                    oninput="clearErrorTwo('bankloaninputError')">
                                                                <div class="placeholder">Enter Bank/ Loan provider
                                                                </div>
                                                                <span class="error-message"
                                                                    id="bankloaninputError"></span>
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="financierbranch"
                                                                    class="input form-control" value=""
                                                                    id="financierbranch" autocomplete="off"
                                                                    spellcheck="false" maxlength="50"
                                                                    oninput="clearErrorTwo('financierbranchError')">
                                                                <div class="placeholder">Enter Financier Branch</div>
                                                                <span class="error-message"
                                                                    id="financierbranchError"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                                            <label for="field1" class="form-label">Previous Policy
                                                                Details</label>

                                                            <div style="float: right">
                                                                <label class="switch">
                                                                    <input type="checkbox" id="prepolitoggle"
                                                                        name="prepolitoggle" onchange="prePolicyToggle()">
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>
                                                            <p>I don't know my previous policy details</p>
                                                        </div>


                                                    </div>
                                                    <div class="row" id="prepolicysection">
                                                        <div class="col-lg-12">
                                                            <label for="field1" class="form-label">Select previous
                                                                policy type</label>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                                                            <div class="myradio activeradio" style="width: ;">
                                                                <input type="radio" id="previouscompr"
                                                                    name="previouscompr" value="previousval1" checked
                                                                    onchange="togglePrexpdateSection()">
                                                                <label for="previouscompr">Comprehensive</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                                                            <div class="myradio" style="width: ;">
                                                                <input type="radio" id="thirdpartycompr"
                                                                    name="previouscompr" value="previousval2"
                                                                    onchange="togglePrexpdateSection()">
                                                                <label for="thirdpartycompr">Third Party</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3"></div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="comprehensive"
                                                                    value="" class="input form-control"
                                                                    id="comprehensive" autocomplete="off"
                                                                    spellcheck="false" maxlength="6"
                                                                    oninput="clearErrorTwo('comprehensiveError')">
                                                                <div class="placeholder">Insurer</div>
                                                                <span class="error-message"
                                                                    id="comprehensiveError"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <input type="text" name="policynumber"
                                                                    value="" class="input form-control"
                                                                    id="policynumber" autocomplete="off"
                                                                    spellcheck="false" maxlength="25"
                                                                    oninput="clearErrorTwo('policynumberError')">
                                                                <div class="placeholder">Policy Number</div>
                                                                <span class="error-message"
                                                                    id="policynumberError"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                            <div class="input-container mb-2">
                                                                <div class="input-group1 datepickerdiv">
                                                                    <input type="text" name="policyexpdate"
                                                                        value=""
                                                                        class="input form-control datepicker"
                                                                        id="policyexpdate" autocomplete="off"
                                                                        spellcheck="false" maxlength="25"
                                                                        oninput="clearErrorTwo('policyexpdateError')">
                                                                    <div class="placeholder">Policy Expiry Date</div>
                                                                    <button class="btn calendarButton" type="button">
                                                                        <i class="fa-solid fa-calendar-days"></i>
                                                                    </button>
                                                                </div>
                                                                <span class="error-message"
                                                                    id="policyexpdateError"></span>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <input type="submit" name="" id=""
                                                        class="d-none">
                                                    <button type="button" id="next1"
                                                        class="membersection next-step"
                                                        onclick="validateFormStepTwo(event)">Continue </button>
                                                </form>
                                            </div>
                                            <div class="step step-3 ">
                                                <form id="formStepThree">
                                                    <section class="hightlight mb-3">
                                                        form Step Three


                                                    </section>


                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>





                        </div>
                    </div>
                </div>
                <!-- Right Col Start -->
                <div class="col-md-4 col-lg-4 col-xl-4 ">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="mb-2 shadow-sm">
                                <div id="qtblock" class="row px-0">
                                    <div class="col-md-12 col-lg-12 mb-2">
                                        <h5>Comprehensive cover</h5>
                                    </div>
                                    <div class="col-md-12 col-lg-12 mb-2">
                                        <table width="100%" class="mbtm">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p class="smlink">For Yours Kinetic Motors</p>
                                                        <p class="smlink">Honda (98CC - Petrol)</p>
                                                    </td>
                                                    <td class="text-end">
                                                        <h6><span><i
                                                                    class="bi bi-currency-rupee"></i></span>{{ session()->get('premium') }}
                                                        </h6>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 col-lg-12 mb-3">
                                        <h6>Select Rider(s)</h6>
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
                                    </div>
                                    <!--<div class="col-md-12 col-lg-12 mb-3" >
                                        <h6>Select Add-ons</h6>
                                        <div class="border border-light border-1 border-dashed px-2 py-1">
                                            <table width="100%" class="mbtm">
                                                <tbody>
                                                    <tr>
                                                        <td><small>No add-ons selected</small></td>
                                                        <td class="text-end"><a href="#ridercol" class="smlink text-primary">View add-ons</a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>-->
                                    <div class="col-md-12 col-lg-12 mb-4 bg-light py-2 px-3">
                                        <table width="100%" class="mbtm ">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0">Total Premium</h6>
                                                    </td>
                                                    <td class="text-end">
                                                        <h6><span><i
                                                                    class="bi bi-currency-rupee"></i></span>{{ session()->get('premium') }}
                                                        </h6>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 col-lg-12">
                                        <a href="#"><button class="getstarted mb-2 w-100">Proceed</button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right Col End -->
            </div>
            <!-- Left Col End -->



        </div>
        </div>
    </section>

    <div class="modal fade" id="featuremodal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-3">
            <div class="modal-content rounded-3">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="exampleModalLabel">Plan Features</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('front.partial.feature')
                </div>
                <div class="modal-footer border-0">

                </div>
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
                            <h5 class="text-left mb-3 text-dark" style="text-align: left!important"><b>OTP Login
                                    :</b></h5>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-8 col-xs-12 text-left">
                            <div class="input-container mb-2">
                                <input type="text" name="enterotp" class="input form-control" id="otpverifiy"
                                    autocomplete="off" spellcheck="false" maxlength="6">
                                <div class="placeholder">Enter Otp</div>
                                <span class="error-message" id="ownernameError"></span>
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
    <!-- enterOtp   -->

    @include('front.partial.footer')
    @include('front.partial.jslink')
    <!-- Bootstrap JavaScript bundle -->
    <!-- Custom JavaScript -->
    <script>
        // var user = @json($user);
        // var nominee = @json($nominee);
        // console.log = (user);
        // console.log = (nominee);

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





        function togglePrexpdateSection() {
            const previouscheckbox = document.getElementById('previouscompr');
            const thirdPartycheckbox = document.getElementById('thirdpartycompr');
            // Determine which radio button is checked and apply logic
            if (previouscheckbox.checked) {
                previouscheckbox.parentNode.classList.add('activeradio');
                thirdPartycheckbox.parentNode.classList.remove('activeradio');;
            } else if (thirdPartycheckbox.checked) {
                thirdPartycheckbox.parentNode.classList.add('activeradio');
                previouscheckbox.parentNode.classList.remove('activeradio');

            }
        }


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
        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('click', function(event) {
                const target = event.target;
                if (target.matches('.placeholder')) {
                    const container = target.closest('.input-container');
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
                    const container = target.closest('.input-container');
                    if (container) {
                        const placeholder = container.querySelector('.placeholder');
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
                        const container = target.closest('.input-container');
                        if (container) {
                            const placeholder = container.querySelector('.placeholder');
                            if (placeholder) {
                                placeholder.classList.remove('active');
                            }
                        }
                    }
                }
            });
        });

        function prePolicyToggle(){
            var radioButton = document.getElementById('prepolitoggle')
            var prepolicysection = document.getElementById('prepolicysection')
            if(radioButton.checked){
                prepolicysection.style.display = 'none';
            }
            else{ prepolicysection.style.display = 'flex';}
        }

        var currentStep = 1;
        var validOk;
        var totalSteps = $('.step').length;

        $(document).ready(function() {
            updateProgressBar();
            updateNavigationButtons();

            $(".next-step").click(function(event) {
                event.preventDefault();

                if (validateAllFields()) {
                    if (currentStep === 1) {
                        validOk = validateFormStepOne();
                    } else if (currentStep === 2) {
                        validOk = validateFormStepTwo();
                    }
                    if (validOk) {
                        if (currentStep === 1) {
                            saveFormStepOne();
                        }
                        if (currentStep === 2) {
                            saveFormStepTwo();
                        }

                        if (currentStep < totalSteps) {
                            transitionToStep(currentStep + 1, 'next');
                        }
                    } else {
                        console.log('Validation failed for step ' + currentStep);
                    }
                } else {
                    console.log('Validation failed');
                }
            });

            $(".prev-step").click(function() {
                if (currentStep > 1) {
                    transitionToStep(currentStep - 1, 'prev');
                }
            });

            $(".submit-step").click(function(event) {
                event.preventDefault();
                alert("Form submitted!");
            });

            function updateProgressBar() {
                var progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
                $(".progress-bar").css("width", progressPercentage + "%");
            }

            function validateAllFields() {
                return (currentStep === 1 && validateFormStepOne()) ||
                    (currentStep === 2 && validateFormStepTwo());
            }

            function transitionToStep(step, direction) {
                $(".step-" + currentStep).removeClass("active").addClass("animate__animated animate__fadeOut" + (
                    direction === 'next' ? 'Left' : 'Right'));
                currentStep = step;
                setTimeout(function() {
                    $(".step").removeClass("animate__animated animate__fadeOutLeft animate__fadeOutRight");
                    $(".step-" + currentStep).addClass("active animate__animated animate__fadeIn" + (
                        direction === 'next' ? 'Right' : 'Left'));
                    updateProgressBar();
                    updateNavigationButtons();
                }, 500);
            }

            function updateNavigationButtons() {
                $(".prev-step").toggle(currentStep > 1);
                $(".next-step").toggle(currentStep < totalSteps);
                $(".submit-step").toggle(currentStep === totalSteps);

                if (currentStep === 1) {
                    $('#backPage').show();
                    $('#backSlide').hide();
                } else {
                    $('#backPage').hide();
                    $('#backSlide').show();
                }
            }
        });



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
                    id: "ownername",
                    errorId: "ownernameError",
                    inputerror: "Name."
                },
                {
                    id: "ownermob",
                    errorId: "ownermobError",
                    inputerror: "Mobile Number"
                },
                {
                    id: "owneremail",
                    errorId: "owneremailError",
                    inputerror: "Email Address"
                },
                {
                    id: "addressone",
                    errorId: "addressoneError",
                    inputerror: "Address 1"
                },
                // {
                //     id: "addresstwo",
                //     errorId: "addresstwoError",
                //     inputerror: "Address 2"
                // },
                {
                    id: "pincode",
                    errorId: "pincodeError",
                    inputerror: "Pincode"
                },
                {
                    id: "cityname",
                    errorId: "citynameError",
                    inputerror: "City Name"
                },
                {
                    id: "state",
                    errorId: "stateError",
                    inputerror: "State"
                },
                {
                    id: "nomineename",
                    errorId: "nomineenameError",
                    inputerror: "Nominee Name"
                },
                {
                    id: "nomineedob",
                    errorId: "nomineedobError",
                    inputerror: "Nominee Date Of Birth"
                },
                {
                    id: "nomineerelation",
                    errorId: "nomineerelationError",
                    inputerror: "Nominee Relation."
                }
            ];

            const panRegex = /^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const mobileRegex = /^[0-9]{10}$/;
            const dateRegex = /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-\d{4}$/;

            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox?.querySelector('.error__title');
            const errorCloseButton = errorBox?.querySelector('.error__close');

            function displayError(message, inputElement) {
                if (errorTitleElement) {
                    errorTitleElement.innerText = message;
                    errorBox.style.display = "flex";
                    if (inputElement) {
                        errorBox.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        inputElement.focus();
                    }
                    setTimeout(() => {
                        errorBox.style.display = 'none';
                    }, 3000);
                }
            }

            if (errorCloseButton) {
                errorCloseButton.addEventListener('click', () => {
                    errorBox.style.display = "none";
                });
            }

            let isValid = true;

            for (let input of inputs) {
                let inputElement = document.getElementById(input.id);
                let errorSpan = document.getElementById(input.errorId);

                if (!inputElement || !errorSpan) {
                    console.error(`Element or error span with ID ${input.id.toUpperCase()} not found`);
                    continue;
                }

                errorSpan.innerText = "";
                inputElement.classList.remove("error");

                if (!inputElement.value.trim()) {
                    const errorMessage = `Field for ${input.inputerror} cannot be blank`;
                    isValid = false;
                    displayError(errorMessage, inputElement);
                    inputElement.classList.add("error");
                    break;
                }

                switch (input.id) {
                    case "customerpancardno":
                        if (!panRegex.test(inputElement.value.trim())) {
                            isValid = false;
                            displayError("Invalid PAN card number format", inputElement);
                            inputElement.classList.add("error");
                            break;
                        }
                        break;

                    case "ownermob":
                        if (!mobileRegex.test(inputElement.value.trim())) {
                            isValid = false;
                            displayError("Invalid mobile number format", inputElement);
                            inputElement.classList.add("error");
                            break;
                        }
                        break;

                    case "owneremail":
                        if (!emailRegex.test(inputElement.value.trim())) {
                            isValid = false;
                            displayError("Invalid email address format", inputElement);
                            inputElement.classList.add("error");
                            break;
                        }
                        break;

                        // Add more validation rules as needed for other fields
                }

                if (!isValid) {
                    break;
                }
            }

            if (isValid) {
                errorBox.style.display = "none";
            }

            return isValid;
        }

        function clearErrorOne(errorId) {
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
    function formatInputId(inputId) {
        return inputId
            .replace(/_/g, ' ')
            .replace(/\b\w/g, char => char.toUpperCase());
    }

    let inputs = [{
            id: "bankloantype",
            errorId: "bankloantypeError",
            inputerror: "Bank/ Loan provider"
        },
        {
            id: "bankloaninput",
            errorId: "bankloaninputError",
            inputerror: "Bank/ Loan"
        },
        {
            id: "financierbranch",
            errorId: "financierbranchError",
            inputerror: "Financier Branch"
        },
        {
            id: "comprehensive",
            errorId: "comprehensiveError",
            inputerror: "Insurer"
        },
        {
            id: "policynumber",
            errorId: "policynumberError",
            inputerror: "Policy Number"
        },
        {
            id: "policyexpdate",
            errorId: "policyexpdateError",
            inputerror: "Policy Expiry Date"
        }
    ];

    const errorBox = document.querySelector('.MainErrorBox');
    const errorTitleElement = errorBox?.querySelector('.error__title');
    const errorCloseButton = errorBox?.querySelector('.error__close');
    const prepolitoggle = document.getElementById('prepolitoggle'); // Assuming this is the ID of your toggle

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
            console.error(`Element or error span with ID ${input.id.toUpperCase()} not found`);
            continue;
        }

        errorSpan.innerText = "";
        inputElement.classList.remove("error");

        // Check if prepolitoggle is checked before validating specific fields
        if (input.id === "comprehensive" || input.id === "policynumber" || input.id === "policyexpdate") {
            if (prepolitoggle.checked) {
                continue; // Skip validation for these fields
            }
        }

        if (!inputElement.value.trim()) {
            let errorMessage = input.inputerror ?
                `Field for ${input.inputerror} cannot be blank` :
                `Field for ${formatInputId(input.id)} cannot be blank`;

            isValid = false;
            hasError = true;
            displayError(errorMessage, inputElement);
            inputElement.classList.add("error");
            break;
        }
    }

    if (!hasError && errorBox.style.display === "flex") {
        errorBox.style.display = "none";
    }

    return isValid;
}


        function clearErrorTwo(errorId, inputElement) {
            let errorSpan = document.getElementById(errorId);
            if (errorSpan) {
                errorSpan.innerText = "";
                let inputElement = document.getElementById(errorId.replace("Error", ""));
                if (inputElement) {
                    inputElement.classList.remove("error");
                }
            }

        }

        function handleSubmit(error, element, numlen) {
            clearErrorOne(error);
            validateNumber(element, numlen);
        }


        function saveFormStepOne() {
            //return new Promise((resolve, reject) => {
            let formData = new FormData(document.getElementById('formStepOne'));
            formData.append('_token', "{{ csrf_token() }}");

            $.ajax({
                url: "{{ route('shriram.savestepone') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log('AJAX success response:', response);
                    const stepTwoData = response.data.verify_details;
                    // console.log(stepTwoData);
                    $('#make').text(stepTwoData.make);
                    $('#modelvin').text(stepTwoData.model);
                    $('#cubiccapacity').text(stepTwoData.capacity);
                    $('#regdob').text(stepTwoData.regdate);
                    $('#insudeclared').text(stepTwoData.idv);
                    $('#chassisnumber').text(stepTwoData.chassisno); 
                    $('#engnumber').text(stepTwoData.engineno);
                    // const values = Object.values(verify_details);
                    // console.log(values);
                    //resolve(validOk);
                }
                // , error: function(xhr) {
                //     console.error('AJAX error:', xhr.responseText);
                //     reject(xhr);
                // }
            });
            //});
        }

        function saveFormStepTwo() {
            //return new Promise((resolve, reject) => {
            let formData = new FormData(document.getElementById('formStepTwo'));
            formData.append('_token', "{{ csrf_token() }}");

            $.ajax({
                url: "{{ route('shriram.savesteptwo') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log('AJAX success response:', response);
                    // if (response.error != "") {
                    //     validOk = false;
                    //     $("#" + response.id + 'Error').focus();
                    //     $("#" + response.id + 'Error').text(response.error);
                    // } else {
                    //     validOk = true;
                    // }
                    //resolve(validOk);
                }
                // , error: function(xhr) {
                //     console.error('AJAX error:', xhr.responseText);
                //     reject(xhr);
                // }
            });
            //});
        }

        function saveFormStepThree(id, router) {
            //return new Promise((resolve, reject) => {
            let formData = new FormData(document.getElementById(id));
            formData.append('_token', "{{ csrf_token() }}");

            $.ajax({
                url: router,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    //console.log('AJAX success response:', response);
                    if (response.error != "") {
                        validOk = false;
                        $("#" + response.id + 'Error').focus();
                        $("#" + response.id + 'Error').text(response.error);
                    } else {
                        validOk = true;
                    }
                    //resolve(validOk);
                }
                // , error: function(xhr) {
                //     console.error('AJAX error:', xhr.responseText);
                //     reject(xhr);
                // }
            });
            //});
        }
    </script>
</body>

</html>
