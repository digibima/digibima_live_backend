<?php
use App\Models\Proposal;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Mototor Vichele Plans</title>
    <style>
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
        $verified = $data['verified'];
        // dd($data);
        ['addon' => ['addonprice' => ['pac' => $pac, 'mp' => $mp, 'pacf' => $pacf]]] = $data;
        $totalpremium = $data['totalpremium'];
        $insured = $data['insured'];
        $addon = $data['addon']['addonprice'];

    @endphp
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
                                                <h5>Pick a plan, and get started today!</h5>

                                            </div>
                                            <div class="col-md-6 col-lg-6 ">
                                                <h6 class="mb-2">Comprehensive Cover</h6>
                                                <div class="policyselect1">
                                                    <div class="myradio1 ">
                                                        <input type="radio" name="Comprehentab" id="Comprehentab"
                                                            value="tab1" @checked($data['cover']['selectedcover'] != 'tab2')>

                                                        <p>Covers damage to your bike and to third- party<span
                                                                style="float: right"><i
                                                                    class="fa-solid fa-indian-rupee-sign"></i><b>{{ $data['cover']['cover1amt'] }}</b></span>
                                                        </p>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6 ">
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
                                            </div>

                                        </div>
                                        <!-- Policy End -->

                                        <div class="row shadow-sm coverblock">
                                            <div class="col-md-12 col-lg-12 mb-3">
                                                <h5>How long do you want to insure your bike for?</h5>
                                            </div>
                                            <div class="col-md-4 col-lg-4">
                                                <div class="policyselect ">
                                                    <div class="myradio1">
                                                        <input type="radio" name="yearpolicy" id="oneyearpolicy"
                                                            value="1" @checked($data['premium']['selectedpremium'] != '2' && $data['premium']['selectedpremium'] != '3')>
                                                        <label for="oneyearpolicy">One Year</label>
                                                        <p>Premium<span style="float: right"><i
                                                                    class="fa-solid fa-indian-rupee-sign"></i><b>{{ $data['premium']['yearoneprice'] }}</b></span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-4">
                                                <div class="policyselect">
                                                    <div class="myradio1">
                                                        <input type="radio" name="yearpolicy" id="twoyearpolicy"
                                                            value="2" @checked($data['premium']['selectedpremium'] == '2')>
                                                        <label for="twoyearpolicy">Two Year</label>
                                                        <p>Premium<span style="float: right"><i
                                                                    class="fa-solid fa-indian-rupee-sign"></i><b>{{ $data['premium']['yeartwoprice'] }}</b></span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-4">
                                                <div class="policyselect">
                                                    <div class="myradio1">
                                                        <input type="radio" name="yearpolicy" id="threeyearpolicy"
                                                            value="3" @checked($data['premium']['selectedpremium'] == '3')>
                                                        <label for="threeyearpolicy">Three Year</label>
                                                        <p>Premium<span style="float: right"><i
                                                                    class="fa-solid fa-indian-rupee-sign"></i><b>{{ $data['premium']['yearthreeprice'] }}</b></span>
                                                        </p>
                                                    </div>
                                                </div>
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
                                                        value="{{ $data['insured'] ? number_format($data['insured']) : '' }}"
                                                        maxlength="6" style="width:100px;">
                                                    {{-- {{ dd($data['insured']) }} --}}
                                                </div>
                                                <div>
                                                    <small style="float: left;">At Risk</small><small
                                                        style="float: right;">Risk
                                                        Free</small>
                                                    <input type="range" class="form-range" id="customRange1"
                                                        min="10800" max="15200"
                                                        value="{{ $data['insured'] ? $data['insured'] : '' }}">
                                                    <small style="float: left;"><i
                                                            class="fa-solid fa-indian-rupee-sign"></i><b>{{ $data['minrange'] }}</b></small>
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
                                    <div class="row addrow" id="thirdpartshow">
                                        <div class="col-md-12 col-lg-8 col-xl-8">
                                            <h6 class="mt-2">PA Cover For Passengers (Un-Named Persons)</h6>
                                        </div>
                                        <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                                            <p>Premium</p>
                                            <h6>₹{{ $pacf }}</h6>
                                        </div>
                                        <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                                            <button
                                                class="btnsm add-btn @if (in_array('pacf', $addedaddon)) disabled @endif"
                                                data-addon="pacf" onclick="addAddon('pacf')">Add</button>
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
                                                                <h6>{{ $verified['status'] }}</h6>
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


    </main>
    @include('front.partial.footer')
    @include('front.partial.jslink')
    <script>
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

            // Function to handle the visibility of the third-party cover section
            function updateThirdPartyVisibility() {
                const thirdPartyDiv = document.getElementById('thirdpartshow');
                const motorDiv = document.getElementById('thirdparthide');
                if (document.getElementById('thirdpartytab').checked) {
                    thirdPartyDiv.style.display = 'flex';
                    motorDiv.style.display = 'none';
                } else {
                    // thirdPartyDiv.style.display = 'none';
                    motorDiv.style.display = 'flex';
                }
            }

            // Setup radio buttons for year policy and comprehensive cover
            setupRadioButtons('yearpolicy', 'policyselect');
            setupRadioButtons('Comprehentab', 'policyselect1', updateThirdPartyVisibility);

            // Trigger the change event on page load to set the initial state
            const checkedRadio = document.querySelector('input[name="Comprehentab"]:checked');
            if (checkedRadio) {
                checkedRadio.dispatchEvent(new Event('change'));
            }
        });

        const rangeInput = document.getElementById('customRange1');
        const currentValueDisplay = document.getElementById('currentValue');

        const updateValues = (value) => {
            const formattedValue = Number(value).toLocaleString();
            currentValueDisplay.value = formattedValue;
        };


        let initialValue = @json($data['insured']) || rangeInput.value;
        if (initialValue) {
            const numericValue = initialValue;
            rangeInput.value = numericValue;
            updateValues(numericValue);
        }

        rangeInput.addEventListener('input', function() {
            updateValues(rangeInput.value);
        });

        currentValueDisplay.addEventListener('input', function() {
            const numericValue = currentValueDisplay.value.replace(/,/g, '');
            if (!isNaN(numericValue) && numericValue >= rangeInput.min && numericValue <= rangeInput.max) {
                rangeInput.value = numericValue;
                updateValues(numericValue);
            }
        });


        function gotoJourney() {
            $('#checkoutLogin').click();
            // window.location.href = 'http://localhost/insurance/motor/dontcar';

        }

        var addedaddon = @json($addedaddon);
        // console.log(addedaddon);
        var addonprice = @json($addonprice);
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
                        identity: 'bike'
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
            addAddonList(value);
        }



        function addAddonList(value) {
            const list = document.querySelector("#addonSelectList");
            list.innerHTML = "";

            const addonMappings = {
                "pac": "Personal Accidental Cover",
                "mp": "Motor Protection",
                "pacf": "PA Cover For Passengers"
            };
            const addonPrices = {
                "pac": '315',
                "mp": '400',
                "pacf": '600'
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

                // Set up the click event to call removeAddon with the current addon
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
                        identity: 'bike'
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
