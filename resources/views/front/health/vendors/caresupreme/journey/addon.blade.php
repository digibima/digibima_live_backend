<style>
    #animation {
        position: relative;
    }

    .chipCardShimmer .shimmerBG {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        animation: shimmerBG 5s infinite linear;
        background: linear-gradient(120deg, transparent 8%, #F8F9FA 18%, transparent 33%);
        background-size: 775px 100%;
    }

    @keyframes shimmerBG {
        0% {
            background-position: -800px 0;
        }

        100% {
            background-position: 800px 0;
        }
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
        background-color: #192733;
        padding: 5px;
        z-index: 1;
    }

    .hover-text i {
        font-size: 11px !important;
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
        top: 25px;
        right: 0%;
    }

    #bottom::before {
        top: -5%;
        left: 45%;
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
        /* margin: 40px;
  font-family: Arial; */
        text-align: left;
    }
</style>
<div class="row">
    @php
        $basepremium = session()->get('basepremium');

        $compulsoryaddon = session()->has('compulsoryaddon')
            ? json_decode(session()->get('compulsoryaddon'), true)
            : [];

        $addonList = getconstant('HEALTH.CARESUPREME.ADDON');
        $addOn_Value = $addOn_Value;
        // dd($addon);
    @endphp

    <div class="col-md-12">
        <div class="mb-2 shadow-sm">
            <div id="qtblock" class="row px-0">
                <div class="col-md-12 col-lg-12 mb-2">
                    <h5>Summary</h5>
                </div>
                <div class="col-md-12 col-lg-12 mb-2">
                    <table width="100%" class="mbtm">
                        <tbody>
                            <tr>
                                <td>
                                    <p class="smlink">Base Premium -
                                        <span>{{ session()->get('tenure') }}</span> year
                                    </p>
                                </td>
                                <td class="text-end">
                                    <h6><span><i class="fa-solid fa-indian-rupee-sign rupee"
                                                style="margin-right: 5px;"></i></span><span
                                            id="premium-amount">{{ $basepremium }}</span>
                                    </h6>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 col-lg-12 mb-2">
                    <table width="100%" class="mbtm">
                        <tbody>
                            <tr>
                                <td>
                                    <p class="smlink">Coverage
                                        {{-- <span>{{ session()->get('coverage') }}</span> year --}}
                                    </p>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex " style="float: right;">
                                        <div class="hover-text pr-2" id="showtooltipcvrge"
                                            style="cursor: pointer; display: none;">
                                            <span class="smlink">
                                                <i class="fa-solid fa-circle-info"></i>
                                            </span>
                                            <span class="tooltip-text" id="bottom">
                                                The PIN code in your address ( <span class="prepin"></span> ) is
                                                different from the PIN code you chose while taking the quote (<span
                                                    class="currepin"></span>). Hence, the minimum SI is revised from
                                                <span id="precvr"></span> to <span id="currecvr"></span> ..


                                            </span>
                                        </div>
                                        <h6 class="mb-0 ms-2">
                                            <span id="coverage-amount">
                                                @if (session()->get('coverage') == 100)
                                                    1 Cr
                                                @else
                                                    {{ session()->get('coverage') }} Lac
                                                @endif
                                            </span>
                                        </h6>
                                    </div>

                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 col-lg-12 mb-3">
                    <div id="addAddonsHeaddiv">
                        <h6 style="float: left">Add-On(s)</h6>
                        <h6 style="float: right">ADD <span data-toggle="modal" data-target="#addAddons"><i
                                    class="fa-solid fa-circle-plus"></i></span></h6>
                    </div>
                    <div id="hideAddonsHeaddiv">
                        <h6 style="float: left">Add-On(s) benefits</h6>

                    </div>
                    <div class="border border-light border-1 border-dashed px-2 py-1">
                        {{-- <table width="100%" class="mbtm">
                            <tbody>
                                <tr>
                                    <td><small>Missing out on benefits</small></td>
                                    <td class="text-end"><a href="#ridercol" class="smlink text-primary">ADD Add-On</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table> --}}
                        <table width="100%" class="mbtm">
                            {{-- <tbody>
                                @foreach ($compulsoryaddon as $key)
                                    @if (array_key_exists($key, $addonList))
                                        <tr>
                                            <td><small class="fw-bold">{{ $addonList[$key] }}</small></td>

                            </tr>
                            @endif
                            @endforeach
                            </tbody> --}}
                            <tbody id="complsry">

                                {{-- @foreach ($compulsoryaddon as $key)
                                    @if (array_key_exists($key, $addonList))
                                        <tr>
                                            <td><small class="fw-bold">{{ $addonList[$key] }}</small></td>

                                </tr>
                                @endif
                                @endforeach --}}
                            </tbody>




                            <tbody id="selectAddonList">

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-12 col-lg-12 px-3">
                    <table width="100%" class="mbtm" id="showtooltipmsg" style="display: none;">
                        <tbody>
                            <tr>
                                <td class="text-end">
                                    <div class="hover-text pr-2" style="cursor: pointer;">
                                        <p class="smlink">Why Price Change <span>
                                                <i class="fa-solid fa-circle-info"></i>
                                            </span></p>
                                        <span class="tooltip-text" id="bottom">
                                            {{-- The Date of Birth of eldest member to
                                                be insured is different from the age you selected while taking quote. Hence,
                                                the total premium is revised from ₹&nbsp;{{ session()->get('premium') }} to
                                            ₹ <span id="showtotalprimsg"></span>. --}}






                                            The PIN code in your address ( <span class="prepin"></span> ) is different
                                            from the PIN code you chose while taking quote (<span
                                                class="currepin"></span>). Hence, the total premium is revised from
                                            ₹&nbsp;{{ session()->get('premium') }} to ₹ <span
                                                id="showtotalprimsg"></span>.
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="col-md-12 col-lg-12 mb-4 bg-light py-2 px-3">
                    <table width="100%" class="mbtm">
                        <tbody>
                            <tr id="animation">
                                <td>
                                    <h6 class="mb-0">Total Premium
                                        <div class="chipCardShimmer">
                                            <div class="shimmerBG"></div>
                                        </div>
                                    </h6>
                                </td>
                                <td class="text-end">
                                    <h6>
                                        <span><i class="fa-solid fa-indian-rupee-sign rupee"
                                                style="margin-right: 5px;"></i></span><span
                                            id="totalpremium">{{ session()->get('premium') }}</span>
                                        <div class="chipCardShimmer">
                                            <div class="shimmerBG"></div>
                                        </div>
                                    </h6>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="col-md-12 col-lg-12 gotoproposal">
                    {{-- <a href="{{ route('proposal') }}" id="gotoproposal"><button class="getstarted mb-2 w-100">Proceed To
                        Proposal</button></a> --}}
                    <a href="#" id="gotoproposal">
                        <button class="getstarted mb-2 w-100" id="proceedButton">Proceed To Proposal</button>
                    </a>

                    <a href="{{ route('proposal') }}" id="gotoproposalpage" class="d-none"></a>
                </div>
                <div class="col-md-12 col-lg-12 gotopaynow" style="display: none;">
                    <a href="#" onclick="goToPayment()"><button class="getstarted mb-2 w-100">Pay Now</button></a>
                </div>
                {{-- <div class="col-md-12 col-lg-12">
                    <button data-toggle="modal" data-target="#errorModal" id="Errorbtn" style="display: none">Open
                        Modal</button>

                </div> --}}
            </div>
        </div>
    </div>
</div>

<div id="addAddons" class="modal fade">
    <div class="modal-dialog modal-confirm ">
        <form method="" enctype="multipart/form-data">
            <div class="modal-content px-4 modal-content">
                <div class="modal-header flex-column">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                @include('front.health.vendors.caresupreme.journey.rightaddon')


            </div>
        </form>
    </div>
</div>



<script>
    let addOn_Value = {!! json_encode($addOn_Value) !!};
    let compulsoryaddon = {!! json_encode($compulsoryaddon) !!};
    let addonList = {!! json_encode($addonList) !!};
    // console.log(addOn_Value);
    // console.log(compulsoryaddon);
    // console.log(addonList);

    // Step 1: Initialize finalAddonValue by processing addOn_Value
    let finalAddonValue = {};
    for (let key in addOn_Value) {
        if (addOn_Value.hasOwnProperty(key)) {
            // Process the key to remove 'field_' and convert to lowercase
            let newKey = key.toLowerCase().replace('field_', '');
            finalAddonValue[newKey] = addOn_Value[key];
        }
    }

    // Step 2: Map compulsoryaddon keys to their full names from addonList
    let fullAddonNames = {};

    // Check if compulsoryaddon is an array or object, and adjust loop accordingly
    if (Array.isArray(compulsoryaddon)) {
        // If compulsoryaddon is an array, loop through its values
        compulsoryaddon.forEach(key => {
            if (addonList[key]) {
                fullAddonNames[key] = addonList[key]; // Add the full name if it exists in addonList
            }
        });
    } else if (typeof compulsoryaddon === 'object') {
        // If compulsoryaddon is an object, loop through its keys
        for (let key in compulsoryaddon) {
            if (compulsoryaddon.hasOwnProperty(key) && addonList[key]) {
                fullAddonNames[key] = addonList[key]; // Map each key to its full name
            }
        }
    }

    // Step 3: Combine finalAddonValue and fullAddonNames to create the combinedAddon object
    let combinedAddon = {};
    for (let key in finalAddonValue) {
        if (finalAddonValue.hasOwnProperty(key)) {
            let value = finalAddonValue[key]; // Numeric value from addOn_Value
            let fullName = fullAddonNames[key]; // Full name from fullAddonNames
            if (fullName) {
                combinedAddon[value] = fullName; // Create the final combinedAddon object
            }
        }
    }

    // Step 4: Render the combinedAddon to the HTML
    console.log(finalAddonValue);
    console.log(compulsoryaddon);
    console.log(combinedAddon); // Check the final output in the console

    let str1 = '';
    for (let key in combinedAddon) {
        if (combinedAddon.hasOwnProperty(key)) {
            let value = combinedAddon[key];
            // console.log(value);
            let row = `
            <tr>
                <td><small class="fw-bold">${value}</small></td>
                <td class="text-end"><small class="fw-bold" style="display: inline-block;">₹${key}</small></td>
                
            </tr>
        `;
            str1 += row;
            console.log(value)
        };
    }


    document.getElementById('complsry').innerHTML += str1;
</script>
