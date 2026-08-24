<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Top Plans</title>
    <style>
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

        .getstarted {
            background: linear-gradient(135deg, #4e54c8, #8f94fb) !important;
            width: 140px !important;
            color: white;
            padding: 8px 16px;
            border-radius: 12px !important;
            font-size: 16px;
            font-weight: 600;
            display: inline-block;
        }

        #planrowblock img {
            width: 100px;
            margin-top: 25px;
        }

        .plan-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            padding: 25px 30px;
            transition: all 0.3s ease-in-out;
            position: relative;
            margin-bottom: 24px;
        }

        .plan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 45px rgba(0, 0, 0, 0.08);
        }

        .card_info span,
        .prihead {
            font-size: 14px;
            font-weight: 500;
            color: #10508F;
        }

        #viewAddon .modal-dialog {
            max-width: 550px;
            margin: 1rem auto;
        }

        #planrowblock {
            cursor: pointer;
            opacity: 1;
        }

        #planrowblock.disabled {

            pointer-events: auto ! important;
            cursor: not-allowed !important;
            opacity: 0.5;
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
            padding-left: 0
        }

        #featuremodal ul li p {
            display: inline;
            margin-left: 5px;
        }

        #featuremodal ul li span i {
            vertical-align: middle;
            margin-right: 10px !important;
        }

        #applybtn {
            padding: 0px 0px;
            font-size: 12.5px !important;
            color: #fff;
            width: 70px !important;
        }

        #featuremodal p {
            font-size: 14px !important;
        }

        .featureheading {
            font-size: 16px !important;
            padding-left: 50px;
        }

        .btn_one {
            align-items: center;
            cursor: pointer;
            border: 1px solid #1C5FA8;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            color: #000;
            letter-spacing: 1px;
            transition: all 0.3s ease-in-out;
            justify-content: space-between;
        }

        .btn_one select[name="children"] {
            width: 120px !important;
        }

        .btn_one label {
            cursor: pointer;
            margin-left: 8px;
            font-size: 14px;
        }

        select {
            background-color: #F0FAFC;
        }

        .disabled {
            pointer-events: none;
            opacity: 0.6;
        }

        .col-lg-12 .Agebox {
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
            height: 50px;
            max-height: 50px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            right: 0;
            background-color: #fff;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidebar a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #000;
            display: block;
            transition: 0.3s;
        }

        /* .sidebar a:hover {
            color: #f1f1f1;
        } */

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }


        .backstep {
            position: absolute;
            top: 25px;
            left: 15px;
            cursor: pointer;
            color: #0b4c8d;
            font-size: 18px;
            font-weight: 600;
        }

        .FirstDiv,
        .SecondDiv,
        .thirdDiv {
            width: 100%;
            height: 100%;
            background-color: #fff;
        }

        .editHeading {
            padding: 5px 0px;
        }

        .Continue button {
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            transition: background .5s ease-in-out;
            height: 48px;
            /* background: #0F9AE9; */
            background: linear-gradient(135deg, #4e54c8, #8f94fb) !important;
            color: #fff !important;
            font-size: 16px;
            border: none;
            width: 100% !important;
        }


        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            right: 0;
            background-color: #fff;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidebar a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #000;
            display: block;
            transition: 0.3s;
        }

        /* .sidebar a:hover {
            color: #f1f1f1;
        } */

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }


        .backstep {
            position: absolute;
            top: 25px;
            left: 15px;
            cursor: pointer;
            color: #0b4c8d;
            font-size: 18px;
            font-weight: 600;
        }

        .FirstDiv,
        .SecondDiv,
        .thirdDiv {
            width: 100%;
            height: 100%;
            background-color: #fff;
        }

        .editHeading {
            padding: 5px 0px;
        }

        #MainDiv span {
            display: flex;
            height: 38px;
            width: 38px;
            background: #f4f5f7;
            color: #0b4c8d;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            flex: 0 0 38px;
            margin: 10px 14px 0 0px;
        }

        #MainDiv span i {
            color: #0b4c8d;
            font-size: 18px;
        }

        #MainDiv p {
            font-size: 12px;
            color: #505f79;
        }

        .bg-white {
            position: relative;
            cursor: pointer;
            padding: 1rem;
        }

        .fa-chevron-right {
            position: absolute;
            right: 3%;
            top: 45%;
            height: 15px;
            width: 38px;
            color: #0b4c8d;
        }

        #filterrow select {
            height: 25px;
        }

        .input-container .placeholder {
            cursor: pointer;
            opacity: 1;
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
            height: 150px;
            width: 300px;
            position: relative;
        }
    </style>
    @include('front.partial.csslink')
</head>

<body class="planlistbg">
    @php
        // dd($vendor);
        $coverage = $coverage;

        $tenure = $tenure;
        // dd($data);
        // var_dump($coveragelist);
        $coveragelistIndexed = array_values($coveragelist);
        // dd( $coveragelistIndexed);

        $coveragelistJson = json_encode($coveragelistIndexed);
        // dd($coveragelistIndexed);
        //$error = $error;
        // dd($addonlist );

        // Use dd() but still output the string directly
        // dd(str_replace('"', '', $fmember));

        // dd(session()->get('coverage'));
        $memberNames = $aInsureData->pluck('name');
        // dd(Auth::user()->name);
        $firstName = $nameParts[0] ?? '';
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
            class="error__close "><i class="fa-solid fa-xmark"></i></span>
    </div>
    <form action="{{ route('health.filterplan') }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <section id="filterrow">
            <div class="container-fluid">

                <div class="row filblock">
                    <a href="{{ route('illnesses', ['id' => 'digiback']) }}" class="smlink prev-step"><span><i
                                class="bi bi-arrow-left"></i></span>Go back to Previous</a>
                    @foreach (['Plan Type' => ['Base', '1 Cr Cover', 'Super Top Up'], 'Coverage' => ['5 ' . config('constant.MONEY.Lac'), '7 ' . config('constant.MONEY.Lac'), '10 ' . config('constant.MONEY.Lac'), '15 ' . config('constant.MONEY.Lac'), '25 ' . config('constant.MONEY.Lac'), '50 ' . config('constant.MONEY.Lac'), '1 Cr'], 'Insurers' => ['Select', 'Care Health', 'TATA AIG'], 'Features' => ['Select', 'Maternity Cover', 'Restoration Benefits', 'OPD Benefit'], 'Tenure' => ['1 ' . config('constant.DAY.Year'), '2 ' . config('constant.DAY.Years'), '3 ' . config('constant.DAY.Years')]] as $label => $options)
                        <div class="col-md-6 col-lg-3 col-xl-1">

                            <div class="row filtercol">

                                <div class="col-md-6 col-xl-6 p-0">
                                    <label class="filterlabel d-inline">{{ $label }}</label>
                                </div>
                                <div class="col-md-6 col-xl-6 p-0">
                                    <select name="{{ strtolower(preg_replace('/\s+/', '', $label)) }}"
                                        {{ in_array($label, ['Plan Type', 'Insurers', 'Features']) ? 'disabled="disabled"' : '' }}>
                                        @foreach ($options as $option)
                                            @php
                                                // Extract the numeric part from the option
                                                $optionNumber = (int) filter_var($option, FILTER_SANITIZE_NUMBER_INT);

                                                // Check if this option is selected for Tenure (comparison with $tenure)
                                                $selectedTenure =
                                                    isset($tenure) && $tenure == $optionNumber ? 'selected' : '';
                                                $selectedCoverage =
                                                    isset($coverage) && $coverage != '' ? $coverage : '50';

                                                if ($selectedCoverage == 100) {
                                                    $selectedCoverage = '1';
                                                }

                                                echo $selectedCoverage;
                                                // For Coverage, handle similarly if needed
                                                $optionValue = explode(' ', $option)[0];

                                            @endphp
                                            <option value="{{ $optionValue }}"
                                                {{ $label == 'Coverage' && $optionValue == $selectedCoverage ? 'selected' : '' }}
                                                {{ $label == 'Tenure' && $selectedTenure ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-md-6 col-lg-3 col-xl-1">
                        <button id="applybtn" class="getstarted ">Apply</button>
                    </div>
                </div>

            </div>
        </section>
    </form>
    <section id="planrow">
        <div class="container-fluid">
            <div class="row filblock">
                <!-- Left Col Start -->
                <div class="col-md-8 col-lg-8 col-xl-9">
                    <div class="row">

                        <div class="col-md-12" id="healthquotes">
                            <!-- Plan Start -->


                            <!-- Plan End -->
                        </div>
                        <div class="col-md-12 col-lg-12 mb-4 animated-background" id="loaderquotes"></div>
                    </div>
                </div>
                <!-- Left Col End -->

                <!-- Right Col Start -->
                <div class="col-md-4 col-lg-4 col-xl-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row shadow-sm coverblock" style="margin: 0rem;">

                                <div class="col-md-12 col-lg-12">
                                    <h5>Members Covered</h5>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <table width="100%" class="mbtm">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p></p>
                                                </td>
                                                <td class="text-end"><a href="javascript:void(0)"
                                                        class="smlink text-primary" onclick="openNav()">
                                                        {{-- <span><i class="fa-solid fa-chevron-right" style="position: static;"></i></span> --}}
                                                        Edit
                                                        Members</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right Col End -->
            </div>
        </div>
    </section>


    <div class="modal fade" id="viewAddon" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-3">
            <div class="modal-content rounded-3" style="text-align: left;">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="exampleModalLabel">Addons Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- <ul id="importAddonprice">

                        </ul> -->
                    <div id="showAddonList" style="padding: 10px!important;max-height: 425px;overflow-y: auto;">

                    </div>

                </div>
                <div class="modal-footer border-0"></div>

            </div>
        </div>



    </div>

    <div class="modal fade" id="featuremodal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-3">
            <div class="modal-content rounded-3" style="text-align:left!important;">
                <div class="modal-header border-0">
                    <h5 class="modal-title
                    " id="exampleModalLabel">Plan Features</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-lg-12 mb-3">
                            <p class="featureheading mb-2"><strong>CARE SUPREME PLAN</strong></p>
                            <ul>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>Premium based on the city of Residence</p>
                                </li>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>7x cover with NCB Super</p>
                                </li>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>No reduction in Accumulated NCB/NCB Super due to claim</p>
                                </li>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>Unlimited Automatic Recharge</p>
                                </li>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>30% Discount on Renewal on meeting Active Days Criteria</p>
                                </li>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>New Age benefits</p>
                                </li>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>Unlimited General Physician e-Consultation</p>
                                </li>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>Unlimited Access to AI based Fitness Coaching e-Sessions</p>
                                </li>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>Unlimited Access to Nutritionst/wellness expert e-consultation session</p>
                                </li>
                            </ul>
                        </div>


                        <div class="col-lg-12 mb-3">

                            <p class="featureheading mb-2"><strong>CARE SUPREME HIGHLIGHTS</strong></p>
                            <ul>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>60 Days Pre Hospitalization & 180 Days Post Hospitalization</p>
                                </li>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>Upto 100% increase in SI with NCB</p>
                                </li>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>Upto 500% Increase in SI with Optional Benefit NCB Super</p>
                                </li>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>Unlimited Automatic Recharge for related and Unrelated illness</p>
                                </li>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>No Sub-Limits on Modern Treatments like Robotic Surgery etc..</p>
                                </li>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>No Sub-limits on AYUSH, Domiciliary Hospitalization, Road Ambulance and Organ
                                        Donor Cover</p>
                                </li>
                                <li>
                                    {{-- <span><i class="fa-solid fa-check"></i></span> --}}
                                    <p>Upto 30% Discount on Renewal Premium under WELLNESS BENEFIT optional Cover</p>
                                </li>

                            </ul>
                        </div>


                    </div>
                </div>
                <div class="modal-footer border-0"></div>
            </div>
        </div>
    </div>

    <div id="mySidebar" class="sidebar">
        <div class="MainDiv" id="MainDiv">
            <h6 class="backstep">Edit your search </h6>
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
            <table width="100%">
                <tr class="bg-white border-bottom d-flex" onclick="toggleAbout()">
                    <td><span><i class="fa-regular fa-user"></i></span></td>
                    <td>
                        <div>
                            <p class="editHeading mb-0">Insured members</p>
                            {{-- <p class="clipData">
                                {{ $firstName }}
                                {{ $firstName && trim($fmember) ? ' ,' : '' }}
                                {{ implode(
                                    ' , ',
                                    array_map(
                                        'strtoupper',
                                        array_filter(explode(',', $fmember), fn($item) => trim($item) !== '' && strtoupper(trim($item)) !== 'SELF'),
                                    ),
                                ) }}
                            </p> --}}
                            <p>
                                @foreach ($memberNames as $member)
                                    @if ($member == 'self')
                                        {{ ucwords(strtolower(explode(' ', Auth::user()->name)[0])) }}
                                    @else
                                        {{ ucwords(strtolower($member)) }}
                                    @endif
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </p>



                            {{-- <p class="clipData">  {{ $firstName . ' ,' . strtoupper(str_replace(',', ' ', str_replace('self,', '', $fmember))) }}</p> --}}
                        </div>
                    </td>
                    <td><i class="fa-solid fa-chevron-right"></i></td>
                </tr>
                <tr class="bg-white border-bottom d-flex" onclick="toggleServices()">
                    <td><span><i class="fa-solid fa-location-dot" style="margin-right: 0px;"></i></span></td>
                    <td>
                        <div>
                            <p class="editHeading mb-0">Pincode</p>
                            <p class="clipData">{{ Auth::User()->pincode }}</p>
                        </div>
                    </td>
                    <td><i class="fa-solid fa-chevron-right"></i></td>
                </tr>

            </table>
        </div>

        <div class="FirstDiv px-2" id="FirstDiv" style="display: none;">
            <h6 class="backstep" onclick="toggleFirst()"><span><i class="fa-solid fa-chevron-left"
                        style="margin-right: 5px;"></i></span>Edit insured members detail</h6>
            <a href="javascript:void(0)" class="closebtn" onclick="toggleFirst()">×</a>

            <form action="{{ route('illnesses', ['id' => 'dgbplan']) }}" method="POST">
                <div class="px-2 mb-2 bg-white row" style="height: 520px; overflow-y: scroll;">
                    @csrf
                    @php
                        $gender = $gender ?? 'male';
                    @endphp

                    @foreach (['self', $gender == 'male' ? 'wife' : 'husband'] as $member)
                        <div class="col-lg-12 mb-2">
                            <div class="d-flex">
                                <div class="btn_one ml-2 parent-div">
                                    <input type="checkbox" id="{{ $member }}box"
                                        @if (!empty($aInsureData) && $aInsureData->contains('name', $member)) checked @endif value="{{ $member }}"
                                        onchange="toggleButtons()">
                                    <label for="{{ $member }}box">{{ ucfirst($member) }}</label>
                                </div>

                                <select name="{{ $member }}" id="{{ $member }}" class="btn_one Agebox">
                                    <option value="">Age</option>
                                    @for ($i = 18; $i <= 99; $i++)
                                        <option value="{{ $i }}"
                                            @if (
                                                !empty($aInsureData) &&
                                                    $aInsureData->contains('name', $member) &&
                                                    $aInsureData->where('name', $member)->first()['age'] == $i) selected @endif>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    @endforeach


                    <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                        <div class="btn_one parent-div" style="width:100%;">
                            <input type="checkbox" id="child" name="children" value="children"
                                @if (!empty($child)) @checked(count($child) > 0) @endif
                                onchange="toggleButtons()">
                            <label for="child" class="mr-2" style="margin-left:8px;">Children</label>
                            <div style="display: inline;float: right;">
                                <span class="minus-box" id="minusButton"><i id="minusButtonIcon"
                                        class="fas fa-minus disabled" onclick="removeChildren()" disabled></i></span>
                                <label id="count">0</label><span class="plus-box" id="plusButton"
                                    style="margin-left: 8px;"><i id="plusButtonIcon" class="fas fa-plus disabled"
                                        onclick="addChildren()" disabled></i></span>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12" id="mainchildContainer" style="display:none">
                        <div class="child-container row" id="childContainer"></div>
                    </div>
                    @foreach (['father', 'mother', 'grandfather', 'grandmother', 'father-in-law', 'mother-in-law'] as $member)
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                            <div class="d-flex">
                                <div class="btn_one ml-2 parent-div">
                                    <input type="checkbox" id="{{ str_replace('-', '', $member) }}box"
                                        value="{{ str_replace('-', '', $member) }}" onchange="toggleButtons()"
                                        @if (!empty($aInsureData) && $aInsureData->contains('name', str_replace('-', '', $member))) checked @endif>
                                    <label
                                        for="{{ str_replace('-', '', $member) }}box">{{ ucfirst($member) }}</label>
                                </div>
                                <select name="{{ str_replace('-', '', $member) }}"
                                    id="{{ str_replace('-', '', $member) }}" class="btn_one Agebox">
                                    <option value="">Age</option>
                                    @for ($i = 18; $i <= 99; $i++)
                                        <option value="{{ $i }}"
                                            @if (
                                                !empty($aInsureData) &&
                                                    $aInsureData->contains('name', str_replace('-', '', $member)) &&
                                                    $aInsureData->where('name', str_replace('-', '', $member))->first()['age'] == $i) selected @endif>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    @endforeach

                </div>
                <div class="d-none">
                    <input type="submit" id="continue">
                </div>
            </form>
            <div class="row">
                <div class="col-lg-12 Continue">
                    <button onclick="validateAndSubmit()">Continue</button>
                </div>
            </div>



        </div>
        <div class="SecondDiv px-2" id="SecondDiv" style="display: none;">
            <h6 class="backstep" onclick="toggleSecond()"><span><i class="fa-solid fa-chevron-left"
                        style="margin-right: 5px;"></i></span>Change Pincode</h6>
            <a href="javascript:void(0)" class="closebtn" onclick="toggleSecond()">×</a>
            <form action="{{ route('health.updatepincode') }}" method="POST">
                @csrf
                <div class="py-2 px-2 bg-white row" style="display: block; height: 505px; overflow-y: scroll;">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                        <div class="input-container">
                            <input type="text" class="input form-control" id="cityname" name="findpincode"
                                value="{{ Auth::User()->pincode }}" autocomplete="off" spellcheck="false"
                                maxlength="25" oninput="acpincode(this)">
                            {{-- <div class="placeholder">Enter city or PIN Code</div> --}}
                            <div class="placeholder">Enter Pincode </div>
                            <div id="city-list" class=""></div>
                            <span class="error-message"></span>
                        </div>
                    </div>
                    {{-- <div class="col-lg-12 col-md-12 col-sm-12  mb-2 citybox">
                        <h4 style="margin-top: 20px;">Popular Cities</h4>
                        <div class="popular-cities">
                            <span class="city-item">Delhi</span>
                            <span class="city-item">Bengaluru</span>
                            <span class="city-item">Hyderabad</span>
                            <span class="city-item">Pune</span>
                            <span class="city-item">Mumbai</span>
                            <span class="city-item">Thane</span>
                            <span class="city-item">Gurgaon</span>
                            <span class="city-item">Ghaziabad</span>
                            <span class="city-item">Lucknow</span>
                            <span class="city-item">Ahmedabad</span>
                        </div>

                    </div> --}}
                </div>

                <div class="row bg-white px-2 ">
                    <div class="col-lg-12 Continue">
                        <button id="citycontinue" class="disabled">Continue</button>
                    </div>
                </div>
            </form>
        </div>


    </div>
    @include('front.partial.chatwidget')
    @include('front.partial.footer')
    @include('front.partial.jslink')

    <script>
        getHealthQuoteStream();
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


        document.addEventListener('DOMContentLoaded', function() {
            const availableCoverage = @json($coveragelistIndexed);
            // console.log(availableCoverage);

            const updatedCoverage = availableCoverage.map(value => {
                if (value === 100) {
                    return 1;
                } else if (value === 200) {
                    return 2;
                }
                return value;
            });

            // console.log(updatedCoverage);

            const coverageDropdown = document.querySelector('[name="coverage"]');
            const allCoverageOptions = coverageDropdown.querySelectorAll('option');

            let coverageSet = false;

            allCoverageOptions.forEach(function(option) {
                const optionValue = parseInt(option.value);
                if (updatedCoverage.includes(optionValue)) {
                    option.disabled = false;

                    if (optionValue === parseInt("{{ $coverage }}") && !coverageSet) {
                        option.selected = true;
                        coverageSet = true;
                    }
                } else {
                    option.disabled = true;
                    option.selected = false;
                }
            });

            coverageDropdown.disabled = false;
        });

        // var error = {!! $error !!};

        // var planRowBlock = document.getElementById('planrowblock');

        // // Check the error value and update the cursor style
        // if (error === 0) {
        //     planRowBlock.classList.add('disabled');
        //     errorBox.style.display = "flex";
        //         errorTitleElement.innerText = '';
        //         errorTitleElement.innerText = `Please select a Correct age `;
        //         setTimeout(() => {
        //             errorBox.style.display = 'none';
        //         }, 3000);
        // } else {
        //     planRowBlock.classList.remove('disabled');
        // }



        const errorBox = document.querySelector('.MainErrorBox');
        const errorTitleElement = errorBox?.querySelector('.error__title');

        function openNav() {
            document.querySelector(".sidebar").style.width = "400px";
        }

        function closeNav() {
            document.querySelector(".sidebar").style.width = "0px";
        }

        const MainDiv = document.getElementById('MainDiv');
        const FirstDiv = document.getElementById('FirstDiv');


        function toggleAbout() {
            MainDiv.style.display = 'none';
            FirstDiv.style.display = 'block';
        }

        function toggleFirst() {
            MainDiv.style.display = 'block';
            FirstDiv.style.display = 'none';
        }

        function toggleServices() {
            MainDiv.style.display = 'none';
            SecondDiv.style.display = 'block';
        }

        function toggleSecond() {
            MainDiv.style.display = 'block';
            SecondDiv.style.display = 'none';
        }







        // function generateAgeOptions(selectElement) {
        //     for (let age = 18; age <= 99; age++) {
        //         let option = document.createElement("option");
        //         // if("{{ !empty($aInsureData) && $aInsureData->contains('name', $member) }}"){
        //         //   option.selected = true;
        //         //   option.value = "";
        //         //   option.textContent = "";
        //         // }
        //         // else{
        //         option.value = age;
        //         option.textContent = age;
        //         //}
        //         selectElement.appendChild(option);
        //     }
        // }

        document.addEventListener("DOMContentLoaded", function() {
            ['self', 'wife', 'father', 'mother', 'grandfather', 'grandmother', 'fatherinlaw', 'motherinlaw'].forEach
                (member => {
                    // generateAgeOptions(document.getElementById(member));
                    const selectElement = document.getElementById(member);
                });

            const parentDivs = document.querySelectorAll('.parent-div');
            parentDivs.forEach(div => {
                div.addEventListener('click', (event) => {
                    if (event.target === div) {
                        const checkbox = div.querySelector('input[type="checkbox"]');
                        checkbox.checked = !checkbox.checked;
                        toggleButtons();
                    }
                });
            });

            toggleButtons();
        });

        let maxCount = 4;
        let currentCount = 0;
        @if (!empty($child))
            @foreach ($child as $rec)
                @php
                    $childData = [];
                @endphp
                @foreach ($rec as $item)

                    @php
                        $childData[] = $item;
                    @endphp
                @endforeach
                ChildrenEdit(@json($childData));
            @endforeach
        @endif
        function toggleButtons() {
            const childCheckbox = document.getElementById('child');
            const plusButton = document.querySelector('#plusButtonIcon');
            const minusButton = document.querySelector('#minusButtonIcon');
            if (childCheckbox && plusButton && minusButton) {
                if (childCheckbox.checked) {
                    plusButton.classList.remove('disabled');
                    minusButton.classList.remove('disabled');
                    plusButton.removeAttribute('disabled');
                    minusButton.removeAttribute('disabled');
                } else {
                    plusButton.classList.add('disabled');
                    minusButton.classList.add('disabled');
                    plusButton.setAttribute('disabled', 'true');
                    minusButton.setAttribute('disabled', 'true');
                    removeAllChildren();
                }
            }
            // Enable or disable selects based on checkboxes
            ['self', 'wife', 'husband', 'father', 'mother', 'grandfather', 'grandmother', 'fatherinlaw', 'motherinlaw']
            .forEach(
                member => {
                    const checkbox = document.getElementById(`${member}box`);
                    const select = document.getElementById(member);
                    if (checkbox && select) {
                        select.disabled = !checkbox.checked;
                        if (!checkbox.checked) {
                            select.value = ''; // Clear dropdown when disabling
                        }
                    }
                });
        }

        function addChildren() {
            if (currentCount < maxCount) {
                currentCount++;
                document.getElementById('mainchildContainer').style.display = currentCount > 0 ? 'block' : 'none';

                const childContainer = document.getElementById('childContainer');
                if (!childContainer) {
                    return;
                }

                const childDiv = document.createElement('div');
                childDiv.className = 'childDiv col-lg-12 col-md-12 col-sm-12 mb-2';
                childDiv.style.display = 'flex';

                const selectChild = document.createElement('select');
                selectChild.name = 'children[]';
                selectChild.className = 'btn_one child-select';
                selectChild.id = `child_${currentCount}`;
                selectChild.style.width = '130px!important';
                selectChild.setAttribute('aria-label', 'Child');
                selectChild.innerHTML = `
            <option value="">Select Child</option>
            <option value="Son">Son</option>
            <option value="Daughter">Daughter</option>
        `;

                const selectAge = document.createElement('select');
                selectAge.className = 'btn_one age-select Agebox';
                selectAge.id = `child_${currentCount}Age`;
                selectAge.name = 'childrenAge[]';
                selectAge.innerHTML = '<option value="">Age</option>';
                // for (let months = 4; months <= 24; months++) {
                //     selectAge.innerHTML += `<option value="${months}">${months} months</option>`;
                // }

                // Add options for ages from 2 years (24 months) to 24 years
                for (let years = 1; years <= 24; years++) {
                    selectAge.innerHTML += `<option value="${years}">${years}</option>`;
                }

                childDiv.appendChild(selectChild);
                childDiv.appendChild(selectAge);
                childContainer.appendChild(childDiv);

                updateCount();
                toggleButtons();
            } else {
                errorBox.style.display = "flex";
                errorTitleElement.innerText = 'Maximum Four Children Add';
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
            }
        }


        function ChildrenEdit(childData) {
            if (currentCount < maxCount) {
                currentCount++;
                document.getElementById('mainchildContainer').style.display = currentCount > 0 ? 'block' : 'none';

                const childContainer = document.getElementById('childContainer');
                const childDiv = document.createElement('div');
                childDiv.className = 'childDiv col-lg-12 col-md-12 col-sm-12 mb-2';
                childDiv.style.display = 'flex';

                const selectChild = document.createElement('select');
                selectChild.name = 'children[]';
                selectChild.className = 'btn_one child-select';
                selectChild.id = `child_${currentCount}`;
                selectChild.style.width = '130px!important';
                selectChild.setAttribute('aria-label', 'Child');
                selectChild.innerHTML = `
            <option value="">Select Child</option>
            <option value="Son" ${childData[0] === 'Son' ? 'selected' : ''}>Son</option>
            <option value="Daughter" ${childData[0] === 'Daughter' ? 'selected' : ''}>Daughter</option>
        `;

                const selectAge = document.createElement('select');
                selectAge.className = 'btn_one age-select Agebox';
                selectAge.id = `child_${currentCount}Age`;
                selectAge.name = 'childrenAge[]';
                selectAge.innerHTML = '<option value="">Age</option>';

                // Adding options for ages from 4 months to 24 months
                // for (let months = 4; months <= 24; months++) {
                //     selectAge.innerHTML +=
                //         `<option value="${months}" ${childData[1] == months ? 'selected' : ''}>${months} months</option>`;
                // }

                // Adding options for ages from 2 years (24 months) to 24 years
                for (let years = 1; years <= 24; years++) {
                    selectAge.innerHTML +=
                        `<option value="${years}" ${childData[1] == years ? 'selected' : ''}>${years}</option>`;
                }

                childDiv.appendChild(selectChild);
                childDiv.appendChild(selectAge);
                childContainer.appendChild(childDiv);

                updateCount();
                toggleButtons();
            } else {
                // Handle max children count error
                errorBox.style.display = "flex";
                errorTitleElement.innerText = 'Maximum Four Children Add';
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
            }
        }


        function removeChildren() {
            if (currentCount > 0) {
                const childContainer = document.getElementById('childContainer');
                childContainer.removeChild(childContainer.lastChild);
                currentCount--;
                updateCount();
                toggleButtons();
            }
            document.getElementById('mainchildContainer').style.display = currentCount === 0 ? 'none' : 'block';
        }

        function removeAllChildren() {
            const childContainer = document.getElementById('childContainer');
            while (childContainer.firstChild) {
                childContainer.removeChild(childContainer.firstChild);
            }
            currentCount = 0;
            updateCount();
            document.getElementById('mainchildContainer').style.display = 'none';
        }

        function updateCount() {
            document.getElementById('count').textContent = currentCount;
        }
        // add children end


        function validateAndSubmit() {
            let isValid = true;
            let adultCount = 0;
            let childCount = 0;
            let selfSelected = false;
            let selfAge = null;
            let spouseSelected = false;
            let spouseAge = null;
            let fatherSelected = false;
            let motherSelected = false;
            let fatherInLawSelected = false;
            let motherInLawSelected = false;
            let childDetails = [];

            const members = [{
                    checkboxId: 'selfbox',
                    selectId: 'self',
                    relationship: 'Self'
                },
                {
                    checkboxId: 'wifebox',
                    selectId: 'wife',
                    relationship: 'Wife'
                },
                {
                    checkboxId: 'husbandbox',
                    selectId: 'husband',
                    relationship: 'Husband'
                },
                {
                    checkboxId: 'fatherbox',
                    selectId: 'father',
                    relationship: 'Father'
                },
                {
                    checkboxId: 'motherbox',
                    selectId: 'mother',
                    relationship: 'Mother'
                },
                {
                    checkboxId: 'grandfatherbox',
                    selectId: 'grandfather',
                    relationship: 'Grandfather'
                },
                {
                    checkboxId: 'grandmotherbox',
                    selectId: 'grandmother',
                    relationship: 'Grandmother'
                },
                {
                    checkboxId: 'fatherinlawbox',
                    selectId: 'fatherinlaw',
                    relationship: 'Father-in-law'
                },
                {
                    checkboxId: 'motherinlawbox',
                    selectId: 'motherinlaw',
                    relationship: 'Mother-in-law'
                }
            ];
            const anySelected = members.some(member => {
                const checkbox = document.getElementById(member.checkboxId);
                return checkbox ? checkbox.checked : false;
            });

            if (!anySelected) {
                errorBox.style.display = "flex";
                errorTitleElement.innerText = "Please select at least one family member.";
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                return false;
            }

            members.forEach(member => {
                const checkbox = document.getElementById(member.checkboxId);
                const select = document.getElementById(member.selectId);

                if (checkbox && select) {
                    if (checkbox.checked && select.value === '') {
                        errorBox.style.display = "flex";
                        errorTitleElement.innerText = `Please select an age for ${member.relationship}.`;
                        setTimeout(() => {
                            errorBox.style.display = 'none';
                        }, 3000);
                        isValid = false;
                    }
                    if (checkbox.checked) {
                        const age = parseInt(select.value, 10);
                        if (age >= 18) adultCount++;

                        switch (member.relationship) {
                            case 'Self':
                                selfSelected = true;
                                selfAge = age;
                                break;
                            case 'Wife':
                            case 'Husband':
                                spouseSelected = true;
                                spouseAge = age;
                                break;
                            case 'Father':
                                fatherSelected = true;
                                fatherAge = age;
                                break;
                            case 'Mother':
                                motherSelected = true;
                                motherAge = age;
                                break;
                            case 'Father-in-law':
                                fatherInLawSelected = true;
                                fatherInLawAge = age;
                                break;
                            case 'Mother-in-law':
                                motherInLawSelected = true;
                                motherInLawAge = age;
                                break;
                        }
                    }
                }
            });

            const childSelects = document.querySelectorAll('.childDiv .age-select');
            childSelects.forEach(childSelect => {
                const selectedAge = parseInt(childSelect.value, 10);
                if (!isNaN(selectedAge)) {
                    childDetails.push({
                        age: selectedAge
                    });
                }
            });

            // Dynamically select spouse's age
            let spouseAge1 = document.getElementById('wife') ? parseInt(document.getElementById('wife').value) :
                document.getElementById('husband') ? parseInt(document.getElementById('husband').value) : null;

            let fatherAge1 = parseInt(document.getElementById('father').value);
            let motherAge1 = parseInt(document.getElementById('mother').value);
            let fatherinlawAge1 = parseInt(document.getElementById('fatherinlaw').value);
            let motherinlawAge1 = parseInt(document.getElementById('motherinlaw').value);
            let grandfatherAge1 = parseInt(document.getElementById('grandfather').value);
            let grandmotherAge1 = parseInt(document.getElementById('grandmother').value);

            // Check age gaps
            if (fatherAge1 - selfAge < 18 || (spouseSelected && fatherAge1 - spouseAge1 < 18)) {
                showError('The gap between Self/Spouse and Father should be at least 18 years.');
                return false;
            }
            if (motherAge1 - selfAge < 18 || (spouseSelected && motherAge1 - spouseAge1 < 18)) {
                showError('The gap between Self/Spouse and Mother should be at least 18 years.');
                return false;
            }
            if (fatherinlawAge1 - selfAge < 18 || (spouseSelected && fatherinlawAge1 - spouseAge1 < 18)) {
                showError('The gap between Self/Spouse and Father-In-Law should be at least 18 years.');
                return false;
            }
            if (motherinlawAge1 - selfAge < 18 || (spouseSelected && motherinlawAge1 - spouseAge1 < 18)) {
                showError('The gap between Self/Spouse and Mother-In-Law should be at least 18 years.');
                return false;
            }
            if (grandfatherAge1 - selfAge < 36 || (spouseSelected && grandfatherAge1 - spouseAge1 < 18)) {
                showError('The gap between Self/Spouse and Grandfather should be at least 36 years.');
                return false;
            }
            if (grandmotherAge1 - selfAge < 36 || (spouseSelected && grandmotherAge1 - spouseAge1 < 18)) {
                showError('The gap between Self/Spouse and Grandmother should be at least 36 years.');
                return false;
            }
            if (grandfatherAge1 - fatherAge1 < 18) {
                showError('The gap between Father and Grandfather should be at least 18 years.');
                return false;
            }
            if (grandmotherAge1 - fatherAge1 < 18) {
                showError('The gap between Father and Grandmother should be at least 18 years.');
                return false;
            }
            if (grandfatherAge1 - motherAge1 < 18) {
                showError('The gap between Mother and Grandfather should be at least 18 years.');
                return false;
            }
            if (grandmotherAge1 - motherAge1 < 18) {
                showError('The gap between Mother and Grandmother should be at least 18 years.');
                return false;
            }

            if (isValid) {
                document.getElementById('continue').click();
            }
        }



        // Function to generate age options from 18 to 99 start
        // function generateAgeOptions() {
        //     const selfSelect = document.getElementById('self');
        //     selfSelect.innerHTML = '<option value="">Age</option>';

        //     for (let age = 18; age <= 99; age++) {
        //         const option = document.createElement('option');
        //         option.value = age;
        //         option.textContent = age;
        //         selfSelect.appendChild(option);
        //     }
        // }

        // function updateFamilyAgeOptions(familyMember, gap) {
        //     const selfSelect = document.getElementById('self');
        //     const familySelect = document.getElementById(familyMember);
        //     const selfAge = parseInt(selfSelect.value);

        //     familySelect.innerHTML = '<option value="">Age</option>';

        //     if (selfAge && selfAge >= 18) {
        //         for (let memberAge = selfAge + gap; memberAge <= 99; memberAge++) {
        //             const option = document.createElement('option');
        //             option.value = memberAge;
        //             option.textContent = memberAge;
        //             familySelect.appendChild(option);
        //         }
        //     } else {
        //         for (let memberAge = 18; memberAge <= 99; memberAge++) {
        //             const option = document.createElement('option');
        //             option.value = memberAge;
        //             option.textContent = memberAge;
        //             familySelect.appendChild(option);
        //         }
        //     }
        // }

        // generateAgeOptions();

        // document.getElementById('self').addEventListener('change', function() {
        //     updateFamilyAgeOptions('father', 18);
        //     updateFamilyAgeOptions('mother', 18);
        //     updateFamilyAgeOptions('grandfather', 36);
        //     updateFamilyAgeOptions('grandmother', 36);
        //     updateFamilyAgeOptions('fatherinlaw', 18);
        //     updateFamilyAgeOptions('motherinlaw', 18);
        // });

        // document.addEventListener('DOMContentLoaded', function() {
        //     const selfSelect = document.getElementById('self');
        //     if (!selfSelect.value) {
        //         const familyMembers = ['father', 'mother', 'grandfather', 'grandmother', 'fatherinlaw',
        //             'motherinlaw'
        //         ];
        //         familyMembers.forEach(function(member) {
        //             updateFamilyAgeOptions(member, 18);
        //         });
        //     }
        // });
        // Function to generate age options from 18 to 99 End



        // Function to generate age options from 18 to 99 start
        // Function to update the family age options
        function updateFamilyAgeOptions(familyMember, gap, selectedAge = null) {
            const familySelect = document.getElementById(familyMember);
            familySelect.innerHTML = '<option value="">Age</option>';

            let selfAge = document.getElementById('self').value;
            selfAge = parseInt(selfAge);

            if (selectedAge === null && selfAge && selfAge >= 18) {
                selectedAge = selfAge + gap;
            }

            if (selfAge && selfAge >= 18) {
                let hasValidAge = false;
                for (let memberAge = selectedAge; memberAge <= 99; memberAge++) {
                    const option = document.createElement('option');
                    option.value = memberAge;
                    option.textContent = memberAge;
                    if (memberAge === selectedAge) {
                        option.selected = true;
                        hasValidAge = true;
                    }
                    familySelect.appendChild(option);
                }

                if (!hasValidAge) {
                    familySelect.value = "";
                }
            } else {
                for (let memberAge = 18; memberAge <= 99; memberAge++) {
                    const option = document.createElement('option');
                    option.value = memberAge;
                    option.textContent = memberAge;
                    if (memberAge === selectedAge) {
                        option.selected = true;
                    }
                    familySelect.appendChild(option);
                }
            }
        }

        function handleDisabledSelect() {
            const familyMembers = ['self', 'wife', 'father', 'mother', 'grandfather', 'grandmother', 'fatherinlaw',
                'motherinlaw'
            ];

            familyMembers.forEach((member, index) => {
                const currentSelect = document.getElementById(member);
                const nextSelect = getNextSelect(member);

                if (currentSelect.disabled && nextSelect && !nextSelect.disabled) {
                    const selectedAge = currentSelect.value;
                    if (selectedAge) {
                        nextSelect.value =
                            selectedAge;
                    }
                }
            });
        }

        function getNextSelect(currentFamilyMember) {
            const familyMembers = ['self', 'wife', 'father', 'mother', 'grandfather', 'grandmother', 'fatherinlaw',
                'motherinlaw'
            ];
            let currentIndex = familyMembers.indexOf(currentFamilyMember);

            if (currentIndex !== -1 && currentIndex < familyMembers.length - 1) {
                return document.getElementById(familyMembers[currentIndex + 1]);
            }
            return null;
        }

        // document.addEventListener('DOMContentLoaded', function() {
        //     const selfSelect = document.getElementById('self');
        //     const familyMembers = ['self', 'wife', 'father', 'mother', 'grandfather', 'grandmother', 'fatherinlaw',
        //         'motherinlaw'
        //     ];

        //     const preSelectedData = @json($aInsureData);
        //     // console.log(preSelectedData);
        //     let selectedAge = null;

        //     familyMembers.forEach(function(member, index) {

        //         const memberData = preSelectedData.find(data => data.name === member);
        //         selectedAge = memberData ? memberData.age : null;
        //         // console.log(member, memberData.age);
        //         if (selectedAge !== null) {
        //             updateFamilyAgeOptions(member, 18, selectedAge);
        //             selectedAge = null;
        //         } else {
        //             updateFamilyAgeOptions(member, 18);
        //         }
        //     });

        //     handleDisabledSelect();
        // });

        // function resetOtherMembersSelection() {
        //     const familyMembers = ['wife', 'father', 'mother', 'grandfather', 'grandmother', 'fatherinlaw', 'motherinlaw'];

        //     familyMembers.forEach(member => {
        //         const memberSelect = document.getElementById(member);
        //         memberSelect.value = '';
        //     });
        // }
        // document.getElementById('self').addEventListener('change', function() {
        //     const selfAge = parseInt(this.value);
        //     updateFamilyAgeOptions('father', 18, selfAge + 18);
        //     updateFamilyAgeOptions('mother', 18, selfAge + 18);
        //     updateFamilyAgeOptions('grandfather', 36, selfAge + 36);
        //     updateFamilyAgeOptions('grandmother', 36, selfAge + 36);
        //     updateFamilyAgeOptions('fatherinlaw', 18, selfAge + 18);
        //     updateFamilyAgeOptions('motherinlaw', 18, selfAge + 18);

        //     resetOtherMembersSelection();
        //     handleDisabledSelect();
        // });

        // Function to generate age options from 18 to 99 End

        // Define checkInput function globally before any other script
        function checkInput() {
            var cityInput = document.getElementById('cityname');
            var continueButton = document.getElementById('citycontinue');
            if (cityInput && continueButton) {
                if (cityInput.value.trim().length >= 6) {
                    continueButton.classList.remove('disabled');
                    continueButton.disabled = false;
                } else {
                    continueButton.classList.add('disabled');
                    continueButton.disabled = true;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var cityInput = document.getElementById('cityname');
            if (cityInput) {
                cityInput.addEventListener('input', checkInput);
            }
            checkInput();
        });

        function acpincode(element) {
            const cityListDiv = document.getElementById("city-list");
            const pincodeInput = document.getElementById("cityname");
            const maxLength = 6;

            pincodeInput.value = pincodeInput.value.replace(/[^0-9]/g, '').substring(0, maxLength);
            const pincode = pincodeInput.value;

            if (/^\d{5,}$/.test(pincode)) {
                var sUrl = "{{ route('acpincode') }}";

                fetch(sUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            pincode: pincode,
                            _token: '{{ csrf_token() }}'
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        cityListDiv.innerHTML = '';
                        if (Object.keys(data).length > 0) {
                            let htmlContent = '<ul class="cityList">';
                            for (const key in data) {
                                if (data.hasOwnProperty(key)) {
                                    const cityText = data[key] ? `(${data[key]})` : '';
                                    htmlContent +=
                                        `<li><a href="#" class="city-link" data-pincode="${key}" data-city="${data[key]}"><span><i class="fa-solid fa-location-dot ml-2"></i></span>${key} ${cityText}</a></li>`;
                                }
                            }
                            htmlContent += '</ul>';
                            cityListDiv.innerHTML = htmlContent;
                            document.querySelectorAll('.city-link').forEach(link => {
                                link.addEventListener('click', function(event) {
                                    event.preventDefault();
                                    const pincode = this.getAttribute('data-pincode');
                                    const city = this.getAttribute('data-city');
                                    const finalValue = pincode + (city ? ` (${city})` : '');
                                    pincodeInput.value = finalValue;
                                    checkInput();
                                    cityListDiv.style.display = 'none';
                                });
                            });
                        } else {
                            cityListDiv.innerHTML = '<p>No cities found for this pincode.</p>';
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        cityListDiv.innerHTML = '<p>An error occurred while fetching the data.</p>';
                    });
            }
        }

        document.getElementById("cityname").addEventListener('input', function() {
            const cityListDiv = document.getElementById("city-list");
            const pincode = this.value;
            if (pincode.length > 4) {
                cityListDiv.style.display = 'block';
            } else {
                cityListDiv.innerHTML = '';
                cityListDiv.style.display = 'none';
            }
        });

        function isNumber(inputElement, maxlen) {
            const sValue = inputElement.value;
            const nLen = maxlen;
            if (sValue.length > nLen) {
                inputElement.value = sValue.substring(0, nLen);
            }
            inputElement.value = inputElement.value.replace(/\D/g, "");
        }



        let applyClicked = false;
        let filterModified = false;


        const applyButton = document.getElementById('applybtn');
        const checkoutButton = document.getElementById('gotocheckout');

        const selectElements = document.querySelectorAll('select');
        selectElements.forEach(function(select) {
            select.addEventListener('change', function() {
                filterModified = true;
            });
        });


        applyButton.addEventListener('click', function(e) {
            applyClicked = true;
            filterModified = false;
        });

        checkoutButton.addEventListener('click', function(event) {
            if (filterModified && !applyClicked) {
                event.preventDefault();
                errorBox.style.display = "flex";
                errorTitleElement.innerText = 'Please click Apply to save your filter changes before proceeding.';

                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
            } else {
                window.location.href = @json(route('addon'));
            }
        });


        // -------------------data Striming function Start --------------------------
        async function getHealthQuoteStream() {

            let vendors = @json($vendor);
            console.log(vendors);

            for (let i = 0; i < vendors.length; i++) {
                const source = await CallAPI("{{ route('health.quote') }}", vendors[i], "").then((response) => {
                    console.log(response);
                    if (response.status == '1') {
                        let aData = response.data;
                        aData.logo = vendors[i]['logo'];
                        handleQuoteData(aData);
                    }
                });
            }
            $('#loaderquotes').hide();
        }

        function handleQuoteData(data) {
            let premium = (data.premium || "0").toString();
            let monthlyPremium = Math.ceil(premium.replace(/,/g, "") / 12);
            let productName = (data.productName || "").toUpperCase();
            let coverage = data.coverage || "0";
            let route = data.route || "#";
            let addons = data.addons || "No Addons Available";


            let quoteHTML = `
        <div id="planrowblock" class="row plan-card" data-addons="${addons}">
            <div class="col-md-12 col-lg-3 col-xl-2">
                <img src="{{ config('constant.BASE_URL') }}front/logo/${data.logo}" />
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 mt-2">
                <h5>${productName}</h5>
                <div id="featurelist">
                    <table>
                        <tr>
                           <td><span><i class="bi bi-check-circle-fill me-2" style="color: #28a745;"></i></span></td>
                            <td><p>No Room Rent Limit</p></td>
                        </tr>
                        <tr>
                            <td><span><i class="bi bi-check-circle-fill me-2" style="color: #28a745;"></i></span></td>
                            <td><p>7.5 lakh Renewal Bonus</p></td>
                        </tr>
                        <tr>
                            <td><span><i class="bi bi-check-circle-fill me-2" style="color: #28a745;"></i></span></td>
                            <td><p>Unlimited Restoration of cover</p></td>
                        </tr>
                    </table>
                </div>
                   <p class="offer mb-1 mt-2"><a href="#" class="plan-details addonprice-details"
                    data-addon='${JSON.stringify(addons)}'
                    onclick="addData(this)">Addons</a></p>
                          
                <p class="offer"><a href="#" data-bs-toggle="modal" data-bs-target="#featuremodal">View Features >></a></p>
                
            </div>
            <div class="col-md-4 col-lg-3 col-xl-3 mt-2">
                <h6 class="mt-3">Cover</h6>
                <p class="btnbg" id="cover">₹${coverage}{{ config('constant.MONEY.Lac') }}</p>
            </div>
            <div class="col-md-12 col-lg-12 col-xl-3  text-center" style="margin-top:30px;">
                <a href="${route}" id="gotocheckout" rel="noopener noreferrer">
                    <button class="getstarted mb-2">₹${monthlyPremium}/month</button>
                </a>
                <p class="muterate">₹${premium} / Year</p>
            </div>
        </div>
    `;

            $('#healthquotes').prepend(quoteHTML);
        }

        function addData(el) {
            event.preventDefault();
            let raw = el.getAttribute("data-addon");

            let addonData = [];
              addonData = JSON.parse(raw);
            

            console.log("Addon Data:", addonData);

            const html = addonData.map(item =>
                `<div class="card_info">
                <span>${item}</span>
                </div>`
            ).join('');

            document.getElementById('showAddonList').innerHTML = `${html}`;

            const modal = new bootstrap.Modal(document.getElementById('viewAddon'));
            modal.show();
        }





        //-------------------- data Striming function End --------------------------------
    </script>
</body>

</html>
