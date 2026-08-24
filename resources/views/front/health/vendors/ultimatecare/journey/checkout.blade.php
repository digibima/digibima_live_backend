@php
    use Illuminate\Support\Facades\Request;
    use Illuminate\Database\Eloquent\Collection;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Top Plans</title>
    @include('front.partial.csslink')
    <style>
        .cityList li:hover {
            background-color: #26A9E1;
        }

        .cityList li:hover a,
        .cityList li:hover .fa-location-dot {
            color: #fff;
        }

        /* input[type="radio"]::after {
            content: "";
            position: absolute;
            inset: 0.18rem;
            opacity: 1;
            scale: 0;
            transition:
                opacity 150ms ease-in-out,
                scale 150ms ease-in-out;
            background-color: #f33195!important;
            border-radius: 50%;
        }




        input[type="radio"]:checked::after {
            opacity: 1;
            scale: 1;
        } */
        .fa-location-dot {
            color: #0B4C8D;
            margin-right: 0px;
        }

        .item-container1 span input {
            float: none;
        }

        .dot-pulse {
            position: absolute;
            left: -9899px;
            top: 30%;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            /* background-color: #1C5FA8; */
             background: linear-gradient(135deg, #4e54c8, #8f94fb) !important;
            color: #1C5FA8;
            animation: dot-pulse 1.5s infinite linear;
            animation-delay: 0.25s;
        }

        .dot-pulse::before,
        .dot-pulse::after {
            content: "";
            display: inline-block;
            position: absolute;
            top: 10%;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            /* background-color: #1C5FA8; */
             background: linear-gradient(135deg, #4e54c8, #8f94fb) !important;
            color: #1C5FA8;
        }

        .dot-pulse::before {
            box-shadow: 9984px 0 0 -5px;
            /* First dot position */
            animation: dot-pulse-before 1.5s infinite linear;
            animation-delay: 0s;
        }

        .dot-pulse::after {
            box-shadow: 10014px 0 0 -5px;
            /* Second dot position */
            animation: dot-pulse-after 1.5s infinite linear;
            animation-delay: 0.5s;
        }

        @keyframes dot-pulse-before {
            0% {
                box-shadow: 9984px 0 0 -5px;
            }

            30% {
                box-shadow: 9984px 0 0 2px;
            }

            60%,
            100% {
                box-shadow: 9984px 0 0 -5px;
            }
        }

        @keyframes dot-pulse {
            0% {
                box-shadow: 9999px 0 0 -5px;
            }

            30% {
                box-shadow: 9999px 0 0 2px;
            }

            60%,
            100% {
                box-shadow: 9999px 0 0 -5px;
            }
        }

        @keyframes dot-pulse-after {
            0% {
                box-shadow: 10014px 0 0 -5px;
            }

            30% {
                box-shadow: 10014px 0 0 2px;
            }

            60%,
            100% {
                box-shadow: 10014px 0 0 -5px;
            }
        }



        #addAddonsHeaddiv {
            display: none;
        }

        #hideAddonsHeaddiv {
            display: block;
        }

        .rupee {
            margin-right: 3px;
            margin-left: 2px;
            font-size: 12px;
        }

        .placeholder {
            cursor: pointer;
        }

        .editAddons {
            cursor: pointer;
            color: #0F9AE9;
            font-size: 13px;
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

        .Continue button {
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            transition: background .5s ease-in-out;
            height: 48px;
            background: #0F9AE9;
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

        .citybox h4 {
            font-weight: 500;
            font-size: 14px;
            width: 100%;
            color: #253858;
            margin-bottom: 12px;
        }

        .popular-cities {
            display: flex;
            flex-wrap: wrap;
            justify-content: unset;
        }

        .popular-cities span {
            font-size: 14px;
            font-weight: 500;
            background: #fff;
            border: 1px solid #97a0af;
            border-radius: 40px;
            color: #253858;
            box-shadow: none;
            height: 36px;
            width: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .3s ease-in-out;
            cursor: pointer;
            margin: 0 12px 12px 0;
            padding: 0 22px;
        }

        .selectcity {
            border: 1px solid #0b4c8d !important;
            color: 0b4c8d !important;
            background: #F0FAFC !important;
        }

        .input-container .placeholder {
            opacity: 1;
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

        #city-list {
            position: absolute;
            font-family: "Poppins", sans-serif;
            width: 100%;
            max-height: 120px;
            height: auto;
            overflow-y: scroll;
            background: #fff;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            z-index: 1;
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
            padding: 0px !important;
            text-decoration: none;
            font-size: 15px;
        }

        .citylocation {
            color: #0B4C8D;
            margin-right: 10px;
        }
    </style>


</head>
@include('front.partial.header')

<body class="planlistbg">
    @php

        // $data1 = session('ultimatedata');
        // dd($data,$data1);
        $addonvalue = $data['addOn_Value'] ?? [];
        $addonlist = $data['addonslist'] ?? [];
        // dd($addonvalue,$addonlist);

        use Illuminate\Support\Facades\Auth;
        use App\Models\Insure;

        $aInsureData = $data['aInsureData'];
        $child = $data['child'];

        $addon = Auth::user()->addon ? json_decode(Auth::user()->addon, true) : [];
        // dd($addon);
        $checkedItems = $data['peddata'] == null ? [] : $data['peddata'];
        $insure_data = Insure::where('proposalid', Auth::id())->get();

        $nom = $insure_data->count();

        $nameParts = explode(' ', Auth::user()->name);
        $membersAll = [];
        foreach ($insure_data as $item) {
            $membersAll[] = $item->name;
        }

        $fmember = implode(',', $membersAll);

        // Use dd() but still output the string directly
        // dd(str_replace('"', '', $fmember));

        // dd(session()->get('coverage'));
        $firstName = $nameParts[0] ?? '';
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
    {{-- {{dd(getconstant('CAREDISEASE'))}} --}}
    <div class="MainErrorBox" style="float: right;display:none;"><span class="error__icon"><i
                class="fa-solid fa-circle-exclamation"></i></span>
        <p class="error__title mb-0 " style="margin-right:10px;">Self cannot be combined with Father or Mother.</p><span
            class="error__close "><i class="fa-solid fa-xmark"></i></span>
    </div>

    <section id="planrow">

        <div class="container-fluid">
            <div class="row filblock">

                <!-- Left Col Start -->

                <div class="col-md-12 col-lg-12 p-0 mb-2">
                    <a href="{{ route('plans') }}" class="smlink"><span><i class="bi bi-arrow-left"></i></span> Go back
                        to
                        quotes</a>
                </div>

                <div class="col-md-8 col-lg-8 col-xl-8 ">
                    @include('front.health.vendors.ultimatecare.journey.rightaddon')
                </div>
                <!-- Left Col End -->

                <!-- Right Col Start -->
                <div class="col-md-4 col-lg-4 col-xl-4 ">
                    @include('front.health.vendors.ultimatecare.journey.addon')
                </div>
                <!-- Right Col End -->

            </div>
        </div>
    </section>


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
                            <p class="clipData">
                                {{ $firstName }}
                                {{ $firstName && trim($fmember) ? ' ,' : '' }}
                                {{ implode(
                                    ' , ',
                                    array_map(
                                        'ucwords',
                                        array_filter(explode(',', $fmember), fn($item) => trim($item) !== '' && strtoupper(trim($item)) !== 'SELF'),
                                    ),
                                ) }}
                            </p>




                            {{-- <p class="clipData">  {{ $firstName . ' ,' . strtoupper(str_replace(',', ' ', str_replace('self,', '', $fmember))) }}</p> --}}
                        </div>
                    </td>
                    <td><i class="fa-solid fa-chevron-right"></i></td>
                </tr>
                <tr class="bg-white border-bottom d-flex" onclick="toggleServices()">
                    <td><span><i class="fa-solid fa-location-dot"></i></span></td>
                    <td>
                        <div>
                            <p class="editHeading mb-0">Pincode</p>
                            <p class="clipData">{{ Auth::User()->pincode }}</p>
                        </div>
                    </td>
                    <td><i class="fa-solid fa-chevron-right"></i></td>
                </tr>
                <tr class="bg-white border-bottom d-flex" onclick="toggleClients()">
                    <td><span><i class="fa-solid fa-prescription-bottle-medical"></i></span></td>
                    <td>
                        <div>
                            <p class="editHeading mb-0">Existing illness</p>
                            <p class="clipData">
                                @foreach ($checkedItems as $key => $item)
                                    {{ ucfirst($item) }} @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach

                            </p>
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
                                                    $aInsureData->where('name', $member)->first()->age == $i) selected @endif>
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
                                                    $aInsureData->where('name', str_replace('-', '', $member))->first()->age == $i) selected @endif>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    @endforeach

                </div>

                <input type="submit" class="d-none" id="continue">

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
            <form action="{{ route('ultimate.updatepincode') }}" method="POST">
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

        <div class="thirdDiv px-2" id="thirdDiv" style="display: none;">
            <h6 class="backstep" onclick="toggleThird()"><span><i class="fa-solid fa-chevron-left"
                        style="margin-right: 5px;"></i></span>Edit illness detail</h6>
            <a href="javascript:void(0)" class="closebtn" onclick="toggleThird()">×</a>

            <form id="illenessForm" action="{{ route('ultimate.addon') }}" method="post">
                <div class="py-2 px-2 mb-2 bg-white row" style="height: 520px; overflow-y: scroll;">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-lg-12 mb-2">
                        <div class="item-container">
                            <label for="Diabetes">Diabetes
                                <span>
                                    <input class="Diabetes" id="Diabetes" name="ped[]" value="diabetes"
                                        type="checkbox" {{ in_array('diabetes', $checkedItems) ? 'checked' : '' }}>
                                </span>
                            </label>
                        </div>

                        <div class="item-container">
                            <label for="bloodpressure">Blood Pressure
                                <span>
                                    <input class="bloodpressure" id="bloodpressure" name="ped[]"
                                        value="bloodpressure" type="checkbox"
                                        {{ in_array('bloodpressure', $checkedItems) ? 'checked' : '' }}>
                                </span>
                            </label>
                        </div>
                        <div class="item-container">
                            <label for="asthma">Asthma
                                <span>
                                    <input class="asthma" id="asthma" name="ped[]" value="asthma"
                                        type="checkbox" {{ in_array('asthma', $checkedItems) ? 'checked' : '' }}>
                                </span>
                            </label>
                        </div>
                        <div class="item-container">
                            <label for="Thyroid">Thyroid
                                <span>
                                    <input class="Thyroid" id="Thyroid" name="ped[]" value="thyroid"
                                        type="checkbox" {{ in_array('thyroid', $checkedItems) ? 'checked' : '' }}>
                                </span>
                            </label>
                        </div>
                        <div class="item-container">
                            <label for="heartdisease">Heart Disease
                                <span>
                                    <input class="heartdisease" id="heartdisease" name="ped[]" value="heart"
                                        type="checkbox" {{ in_array('heart', $checkedItems) ? 'checked' : '' }}>
                                </span>
                            </label>
                        </div>
                        <div class="item-container">
                            <label for="otherdisease">Other Disease
                                <span>
                                    <input class="otherdisease" id="otherdisease" name="ped[]" value="other"
                                        type="checkbox" {{ in_array('other', $checkedItems) ? 'checked' : '' }}>
                                </span>
                            </label>
                        </div>

                        <div class="item-container">
                            <label for="existingdisease">No Existing Disease
                                <span>
                                    <input class="existingdisease" id="existingdisease" name="ped[]"
                                        value="no" type="checkbox"
                                        {{ in_array('no', $checkedItems) ? 'checked' : '' }}>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 Continue">
                    <button id="illnessesbtn" class="d-none"></button>
                </div>
            </form>
            <div class="col-lg-12 Continue">
                <button id="illnessesbtn" onClick="checkSelection()">Continue</button>
            </div>

        </div>

    </div>
    @include('front.partial.chatwidget')
    @include('front.partial.footer')
    @include('front.partial.jslink')
    @include('front.health.vendors.ultimatecare.journey.healthaddon')

    <!-- Bootstrap JavaScript bundle -->
    <script>
        // window.addEventListener("load", function() {
        //             document.getElementById("loader").style.display = "none";
        //         });

        //         window.onbeforeunload = function() {
        //             document.getElementById("loader").style.display = "flex";
        //         };

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

        var selectedAddOns;

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
        const SecondDiv = document.getElementById('SecondDiv');
        const ThirdDiv = document.getElementById('thirdDiv');

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

        function toggleClients() {
            MainDiv.style.display = 'none';
            ThirdDiv.style.display = 'block';
        }

        function toggleThird() {
            MainDiv.style.display = 'block';
            ThirdDiv.style.display = 'none';
        }


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



        function updatePremium(amount, tenure) {
            console.log(amount, tenure);
            console.log(selectedAddOns);

            document.getElementById('premium-amount').textContent = amount;

            fetch("{{ route('ultimate.setpremium') }}", {
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
                    console.log(data);
                    document.getElementById('premium-amount').textContent = amount;
                    window.location.reload();
                })
                .catch(error => console.error('Error:', error));
        }






        function filterPlan() {
            var coverage = $("#coverage").val();
            // console.log(coverage);
            //return false;
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
                .then(response => response.json()) // Parse JSON response
                .then(data => {
                    if (data.status == 'ok') {
                        // console.log(data);
                        window.location.reload();
                    } else if (data.status == 'fail') {
                        $('.addDisabled').css({
                            'pointer-events': 'none',
                            'opacity': '0.5',
                            'cursor': 'not-allowed'
                        });
                    }
                    //coverage = $coverage;
                    // console.log(data);
                    //retutn false;

                });
            // .catch(error => console.error('Error:', error)); // Handle errors
        }



        // illness detail part script Start 

        document.addEventListener('DOMContentLoaded', function() {
            const illnessForm = document.getElementById('illenessForm');
            const otherDiseaseCheckbox = illnessForm.querySelector('#existingdisease');
            const checkboxes = illnessForm.querySelectorAll('input[type="checkbox"]');
            const itemContainers = illnessForm.querySelectorAll('.item-container');

            function updateCheckboxes() {
                const isOtherDiseaseChecked = otherDiseaseCheckbox.checked;

                checkboxes.forEach(checkbox => {
                    if (checkbox !== otherDiseaseCheckbox) {
                        checkbox.disabled = isOtherDiseaseChecked;
                        const container = checkbox.closest('.item-container');
                        if (container) {
                            if (isOtherDiseaseChecked) {
                                container.classList.add('disabled');
                            } else {
                                container.classList.remove('disabled');
                            }
                        }
                    }
                });
            }

            otherDiseaseCheckbox.addEventListener('change', updateCheckboxes);

            checkboxes.forEach(checkbox => {
                if (checkbox !== otherDiseaseCheckbox) {
                    checkbox.addEventListener('change', function() {
                        if (checkbox.checked) {
                            otherDiseaseCheckbox.disabled = true;
                            const container = otherDiseaseCheckbox.closest('.item-container');
                            if (container) {
                                container.classList.add('disabled');
                            }
                        } else {
                            const anyOtherChecked = Array.from(checkboxes)
                                .some(cb => cb !== otherDiseaseCheckbox && cb.checked);
                            otherDiseaseCheckbox.disabled = anyOtherChecked;
                            const container = otherDiseaseCheckbox.closest('.item-container');
                            if (container) {
                                container.classList.toggle('disabled', anyOtherChecked);
                            }
                        }
                        updateCheckboxes();
                    });
                }
            });

            updateCheckboxes();
        });

        function checkSelection() {
            let checkboxes = document.querySelectorAll('#illenessForm input[type="checkbox"]');

            let ped = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            console.log(ped);



            if (ped.length == 0) {
                if (errorBox) {
                    errorTitleElement.innerText = 'Please select at least one checkbox.';
                    errorBox.style.display = 'flex';
                    setTimeout(() => {
                        errorBox.style.display = 'none';
                    }, 3000);
                }
                return;
            }

            errorBox.style.display = 'none';
            changePed(ped);


        }
        let aPedResponse = "";
        const changePed = async (ped) => {
            try {
                aPedResponse = await CallAPI("{{ route('saveillnesses') }}", ped, "");
            } catch (error) {
                console.error("API error:", error);
            }
            var status = aPedResponse;
            console.log(status);
            if (aPedResponse?.status == 1) {
                $("#illnessesbtn").click();
            } else {
                console.log("API response result: status 0");
            }
        };
        window.selectedAddOns = @json($data['addon']);
        const dbaddonsArray1 = window.selectedAddOns;
        // console.log('dbarrayaddons', dbaddonsArray1);
        // console.log('updateaddons',selectedAddOns);
        window.addAddonsRoute = "{{ route('ultimate.addaddon') }}";
        window.csrfToken = "{{ csrf_token() }}";



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

                                    const continueButton = document.getElementById('citycontinue');
                                    const pincode = this.getAttribute('data-pincode');
                                    const city = this.getAttribute('data-city');
                                    const finalValue = pincode + (city ? ` (${city})` : '');
                                    pincodeInput.value = finalValue;
                                    checkInput();
                                    cityListDiv.style.display = 'none';
                                    // continueButton.classList.remove('disabled');
                                    // continueButton.disabled = false;
                                });
                            });
                        } else {
                            cityListDiv.innerHTML = '<p>No cities found for this pincode.</p>';

                            const continueButton = document.getElementById('citycontinue');
                            continueButton.classList.add('disabled');
                            continueButton.disabled = true;
                            // console.log("No cities found for this pincode");
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        cityListDiv.innerHTML = '<p>An error occurred while fetching the data.</p>';

                        const continueButton = document.getElementById('citycontinue');
                        continueButton.classList.add('disabled');
                        continueButton.disabled = true;
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




        function getPolicyData() {

            //const source = new EventSource("{{ route('addon', ['id' => '1']) }}");
            const source = new EventSource("{{ route('ultimate.addon', ['id' => '1']) }}");

            source.onmessage = function(event) {

                // console.log(event.data);
                let obj = JSON.parse(event.data);
                // console.log(obj.value);
                if (obj.status == '1') {
                    $('#plan1').val(obj.value);
                    $('.plane1show').text(obj.value);
                    // $('.policyloading1').addClass('d-none');
                    $('.plane1show').removeClass('dot-pulse');
                    // console.log(obj.value);
                } else if (obj.status == '2') {
                    $('#plan2').val(obj.value);
                    $('.plane2show').text(obj.value);
                    // $('.policyloading2').addClass('d-none');
                    $('.plane2show').removeClass('dot-pulse');
                    // console.log(obj.value); 
                } else if (obj.status == '3') {
                    $('#plan3').val(obj.value);
                    $('.plane3show').text(obj.value);
                    $('.policyloading3').addClass('d-none');
                    $('.plane3show').removeClass('dot-pulse');
                    // console.log(obj.value);
                } else {
                    source.close();
                    // console.log("Stream finished.");
                    // $('#loaderquotes').hide();
                    return;
                }

                // const data = JSON.parse(event.data);
                // let addDetails = data;
                // console.log(addDetails);
                // $('.policydata').hide();
            }
        }
        getPolicyData();


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

        function showError(message) {
            errorBox.style.display = "flex";
            errorTitleElement.innerText = message;
            setTimeout(() => {
                errorBox.style.display = 'none';
            }, 3000);
        }
    </script>
</body>

</html>
