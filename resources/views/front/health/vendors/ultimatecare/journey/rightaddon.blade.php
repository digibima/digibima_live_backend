@php
//dd($addonlist);

    // $selectedAddon = $data['addon'];
    // dd($selectedAddon);
    // $addon = $data['addon'];

    $addon = is_array($addon) ? $addon : explode(',', $addon);
    // dd($addon);
    $data1 = session('ultimatedata');
    $selectedAddOns = $data1['addon'] ?? [];
    $isPed1 = in_array('ped1', $selectedAddOns);
    $isPed2 = in_array('ped2', $selectedAddOns);
    // dd($data1);
     //dd($data['addonslist']);
    //$addonlist = $data1['addonslist'] ?? [];
    //$addonlist = $data['addonslist'] ?? [];
    $coverage = session()->get('coverage');
    $coverage = $coverage == 100 ? '1' : $coverage;
    // dd($coverage);
    $tenure = session()->get('tenure');
    $basepremium = session()->get('basepremium1');
    //$compulsoryaddon = session()->has('compulsoryaddon') ? json_decode(session()->get('compulsoryaddon'), true) : [];
    //$compulsoryaddon = $data['compulsoryaddon'] ?? [];
    $coveragelistIndexed = array_values($data1['coveragelist']);
    //$coveragelistIndexed = array_values($data1['coveragelist']);
    $coveragelistJson = json_encode($coveragelistIndexed);
    // dd(gettype($addon));
@endphp

<div class="row">


    <div class="col-md-12">

        <!-- Cover Start -->
        <div class="row shadow-sm coverblock addDisabled">
            <div class="col-md-8 col-lg-6 col-xl-7">
                <h5>Cover Amount</h5>
                <p>Is this cover amount sufficient?</p>
            </div>



            @foreach (['Coverage' => ['5 ' . config('constant.MONEY.Lac'), '7 ' . config('constant.MONEY.Lac'), '10 ' . config('constant.MONEY.Lac'), '15 ' . config('constant.MONEY.Lac'), '25 ' . config('constant.MONEY.Lac'), '50 ' . config('constant.MONEY.Lac'), '1 Cr']] as $label => $options)
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <select name="{{ strtolower(preg_replace('/\s+/', '', $label)) }}" onchange="filterPlan()"
                        id="coverage">
                        @foreach ($options as $option)
                            <option value="{{ $option }}" @selected(explode(' ', $option)[0] == $coverage)>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endforeach

        </div>
        <!-- Cover End -->

        <!-- Policy Start -->
        <div class="row shadow-sm coverblock addDisabled" id="policyperiod">

            <div class="col-md-12 col-lg-12">
                <h5>Policy Period</h5>
                <p class="mb-3">Choosing a multi-year plan saves your money and the trouble of
                    remembering yearly renewals.</p>
            </div>
            <div class="col-md-4 col-lg-4 mb-2">
                {{-- <div class="policyselect policyloading1">
                    <div class="myradio ">
                        <div class="dot-pulse"></div>
                    </div>
                </div> --}}
                <div class="policyselect  policydata1"
                {{ !in_array(1, $data['tenureList'] ?? []) ? 'style=pointer-events:none;opacity:0.5;cursor:not-allowed;' : '' }}>
                    <div class="myradio ">
                        <input type="radio" name="radioTab1" id="tab1" value="active"
                            {{ $tenure == '1' ? 'checked' : '' }}
                            onclick="updatePremium(document.getElementById('plan1').value, 1)">

                        <label for="tab1" style="position: relative;">1 Year @ <span><i
                                    class="fa-solid fa-indian-rupee-sign rupee"></i></span>
                            <div class="" style="display: inline;">
                                <span class="fw-bold plane1show dot-pulse"></span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 mb-2">
                {{-- <div class="policyselect policyloading2">
                    <div class="myradio ">
                        <div class="dot-pulse"></div>
                    </div>
                </div> --}}
                <input type="hidden" name="" value="" id="plan1">
                <input type="hidden" name="" value="" id="plan2">
                <input type="hidden" name="" value="" id="plan3">
                <div class="policyselect policydata2"
                {{ !in_array(2, $data['tenureList'] ?? []) ? 'style=pointer-events:none;opacity:0.5;cursor:not-allowed;' : '' }}>
                    <div class="myradio ">
                        <input type="radio" name="radioTab1" id="tab2" value="active"
                            {{ $tenure == '2' ? 'checked' : '' }}
                            onclick="updatePremium(document.getElementById('plan2').value, 2)">

                        <label for="tab2" style="position: relative;">2 Year @ <span><i
                                    class="fa-solid fa-indian-rupee-sign rupee"></i></span>
                            <div class="" style="display: inline;">
                                <span class="fw-bold plane2show dot-pulse">445454</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 mb-2">
                {{-- <div class="policyselect policyloading3">
                    <div class="myradio ">
                        <div class="dot-pulse"></div>
                    </div>
                </div> --}}
                <div class="policyselect policydata3 "
                {{ !in_array(3, $data['tenureList'] ?? []) ? 'style=pointer-events:none;opacity:0.5;cursor:not-allowed;' : '' }}>
                    <div class="myradio ">
                        <input type="radio" name="radioTab1" id="tab3" value="active"
                            {{ $tenure == '3' ? 'checked' : '' }}
                            onclick="updatePremium(document.getElementById('plan3').value, 3)">

                        <label for="tab3" style="position: relative;">3 Year @ <span><i
                                    class="fa-solid fa-indian-rupee-sign rupee"></i></span>
                            <div class="" style="display: inline;">
                                <span class="fw-bold plane3show dot-pulse">445454</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row shadow-sm coverblock" id="policyerrorstatus" style="display: none;">

                <div class="col-md-12 col-lg-12">
                    <h5>Policy Period</h5>
                    <p class="mb-3">Choosing a multi-year plan saves your money and the trouble of
                        remembering yearly renewals.</p>
                </div>
            </div> --}}
        <!-- Policy End -->

        <!-- Rider Start -->
        <div id="ridercol" class="row shadow-sm coverblock addDisabled">
            <div class="col-md-12 col-lg-9">
                <h5>Add-On</h5>
                <p class="offer mb-3">You should get these additional benefits to enhance your current plan.</p>

            </div>
            <div class="col-md-12 col-lg-3">

                {{-- <button id="editbtn" class="btnsm mb-2" style="float: right;">Enable</button> --}}
                <button id="applybtn" class="btnsm applybtn mb-2" style="float: right;">Apply</button>
            </div>

            <div class="col-md-12 col-sm-12">

                @foreach ($data1['addOn_Value'] as $key => $value)
                    <!-- Check for 'ped' key to render custom PED div -->
                    @if ($key == 'ped')
                        <div class="row addrow" id="{{ $key }}"
                            style="{{ in_array($key, $data['compulsoryaddon'] ?? []) ? 'display:none;' : '' }}">

                            <div class="col-md-12 col-lg-8 col-xl-8">
                                <h6>{{ $addonlist[$key] ?? strtoupper($key) }}</h6>
                                <p>You can reduce your PED wait period by opting for this benefit. Select Reduction
                                    Period</p>
                            </div>

                            <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                                <p>Premium</p>
                                <select class="mt-1" name="pedaddonvalue" id="pedaddonvalue">
                                    <option value="" disabled {{ !$isPed1 && !$isPed2 ? 'selected' : '' }}>Select
                                    </option>
                                    <option value="ped1" {{ $isPed1 ? 'selected' : '' }}>1 year</option>
                                    <option value="ped2" {{ $isPed2 ? 'selected' : '' }}>2 years</option>
                                </select>
                            </div>

                            <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                                <div class="item-container item-container1">
                                    <span>
                                        <input type="checkbox" name="addon" id="pwpm-cb" class="addon-checkbox"
                                            {{ $isPed1 || $isPed2 ? 'checked' : '' }} data-addon="ped">
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- Default Addon Row for other keys -->
                    @php
                        //dd((in_array('aa',$data1['compulsoryaddon'])));
                        // dd($data1);
                    @endphp
                    @if (in_array($key, array_keys($addonlist)))
                        <div class="row addrow" id="{{ $key }}"
                            style="{{ in_array($key, $data['compulsoryaddon'] ?? []) ? 'display:none;' : '' }}">
                            <div class="col-md-12 col-lg-8 col-xl-8">
                                <h6>{{ $addonlist[$key] ?? strtoupper($key) }}</h6>
                                {{-- <p>{{ getAddonDescription($key) }}</p> --}}
                            </div>

                            <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                                <p>Premium</p>
                                <h6>₹{{ $value }}</h6>
                            </div>

                            <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                                <div class="item-container item-container1">
                                    <span>
                                        <input type="checkbox" name="addon[]" id="{{ $key }}-cb"
                                            class="addon-checkbox" data-addon="{{ $key }}"
                                            {{ in_array($key, $data['compulsoryaddon'] ?? []) ? 'disabled' : '' }}>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach




                {{-- Condition to create the PED Wait Period Modification section only once --}}










            </div>
            <div class="col-md-12 col-lg-12">

                <button id="applybtn" class="btnsm applybtn mb-2" style="float: right;">Apply</button>
            </div>
        </div>

        <!-- Rider End -->

        <!-- Cover Start -->
        <div class="row shadow-sm coverblock" id="coveredmember">

            <div class="col-md-12 col-lg-12">
                <h5>Members Covered</h5>
            </div>
            <div class="col-md-12 col-lg-12">
                <table width="100%" class="mbtm">
                    <tbody>
                        <tr>
                            <td>
                                <p>{{ Auth::User()->name }}({{ $nom }})</p>
                            </td>
                            <td class="text-end"><a href="javascript:void(0)" class="smlink text-primary"
                                    onclick="openNav()">Edit
                                    Members</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Cover End -->
    </div>
</div>
<script src="{{ config('constant.BASE_URL') }}front/js/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // When the 'Enable' button is clicked
        $('#editbtn').click(function() {
            // console.log("Ram");
            // Enable all checkboxes by removing the 'disabled' attribute
            $('.addon-checkbox').prop('disabled', false);
        });
    })



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
    document.addEventListener('DOMContentLoaded', function() {
        const icCheckbox = document.getElementById('ic-cb');
        const pwpmCheckbox = document.getElementById('pwpm-cb');
        const pedAddonValue = document.getElementById('pedaddonvalue');

        // --- Initial state setup ---
        if (icCheckbox.checked) {
            pwpmCheckbox.disabled = true;
        }

        if (pwpmCheckbox.checked) {
            icCheckbox.disabled = true;
        }

        // Disable select if PED checkbox isn't checked
        pedAddonValue.disabled = !pwpmCheckbox.checked;

        // --- Event listeners ---

        icCheckbox.addEventListener('change', function() {
            pwpmCheckbox.disabled = this.checked;

            // If user unchecks IC, re-enable PED checkbox
            if (!this.checked) {
                pwpmCheckbox.disabled = false;
            }
        });

        pwpmCheckbox.addEventListener('change', function() {
            icCheckbox.disabled = this.checked;
            pedAddonValue.disabled = !this.checked;

            // Optional: reset select if unchecked
            if (!this.checked) {
                pedAddonValue.value = '';
            }
        });
    });
</script>
