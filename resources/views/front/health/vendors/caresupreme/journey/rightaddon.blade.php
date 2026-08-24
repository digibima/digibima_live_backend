<div class="row">


    <div class="col-md-12">

        <!-- Cover Start -->
        <div class="row shadow-sm coverblock addDisabled">
            <div class="col-md-8 col-lg-6 col-xl-7">
                <h5>Cover Amount</h5>
                <p>Is this cover amount sufficient?</p>
            </div>

            @php
                //dd($data);
                $coverage = session()->get('coverage');
                $coverage = $coverage == 100 ? '1' : $coverage;
                 //dd($coverage);
                $tenure = session()->get('tenure');
                $basepremium = session()->get('basepremium');
                $compulsoryaddon = session()->has('compulsoryaddon')
                    ? json_decode(session()->get('compulsoryaddon'), true)
                    : [];
                $coveragelistIndexed = array_values($coveragelist);
                $coveragelistJson = json_encode($coveragelistIndexed);
                // dd(gettype($addon));
                //dd($addon);
            @endphp

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
                    {{ !in_array(1, $tenureList) ? 'style=pointer-events:none;opacity:0.5;cursor:not-allowed;' : '' }}>
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
                    {{ !in_array(2, $tenureList) ? 'style=pointer-events:none;opacity:0.5;cursor:not-allowed;' : '' }}>
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
                    {{ !in_array(3, $tenureList) ? 'style=pointer-events:none;opacity:0.5;cursor:not-allowed;' : '' }}>
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
                <button id="applybtn" class="btnsm mb-2" style="float: right;">Apply</button>
            </div>

            <div class="col-md-12 col-sm-12">

                <!-- Air Ambulance -->
                <div class="row addrow" id="aa"
                    style="{{ in_array('aa', $compulsoryaddon) ? 'display:none;' : '' }}">
                    <div class="col-md-12 col-lg-8 col-xl-8">
                        <h6>Air Ambulance</h6>
                        <p>Air ambulances covered by insurance aid an insured in many ways. Some of these include</p>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <p>Premium</p>
                        <h6>{{ ceil($addOn_Value['field_AA'] + $addOn_Value['field_AA'] * 0.18) }}</h6>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <div class="item-container item-container1">
                            <span>
                                <input type="checkbox" name="addon" id="aa-cb" class="addon-checkbox"
                                    data-addon="aa" disabled>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Wellness Benefits -->
                <div class="row addrow" id="wb"
                    style="{{ in_array('wb', $compulsoryaddon) ? 'display:none;' : '' }}">
                    <div class="col-md-12 col-lg-8 col-xl-8">
                        <h6>Wellness Benefits</h6>
                        <p>Wellenss programs in health insurance offer a plethora of benefits to policyholders. Here are
                            a few benefits to participating in wellness activities</p>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <p>Premium</p>
                        <h6>{{ ceil($addOn_Value['field_WB'] + $addOn_Value['field_WB'] * 0.18) }}</h6>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <div class="item-container item-container1">
                            <span>
                                <input type="checkbox" name="addon" id="wb-cb" class="addon-checkbox"
                                    {{ in_array('wb', $addon) ? 'checked' : '' }} data-addon="wb">
                            </span>
                        </div>

                    </div>
                </div>

                <!-- No Claim Bonus -->
                <div class="row addrow" id="ncb"
                    style="{{ in_array('ncb', $compulsoryaddon) ? 'display:none;' : '' }}">
                    <div class="col-md-12 col-lg-8 col-xl-8">
                        <h6>Cumulative Bonus Super</h6>
                        <p>Do you know what super NCB in health insurance is? Cumulative Bonus Super, or Super NCB in
                            health insurance, is increased coverage in the sum insured for each claim-free year.</p>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <p>Premium</p>
                        <h6>{{ ceil($addOn_Value['field_NCB'] + $addOn_Value['field_NCB'] * 0.18) }}</h6>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <div class="item-container item-container1">
                            <span>
                                <input type="checkbox" name="addon" id="ncb-cb" class="addon-checkbox"
                                    {{ in_array('ncb', $addon) ? 'checked' : '' }} data-addon="ncb">
                            </span>
                        </div>

                    </div>
                </div>

                <!-- Instant Cover -->
                <div class="row addrow" id="ic"
                    style="{{ in_array('ic', $compulsoryaddon) ? 'display:none;' : '' }}">
                    <div class="col-md-12 col-lg-8 col-xl-8">
                        <h6>Instant Cover</h6>
                        <p>Claim can be made for hospitalization related to Diabetes, Hypertension, Hyperlipidemia &
                            Asthma after initial wait period of 30 days.</p>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <p>Premium</p>
                        <h6>{{ ceil($addOn_Value['field_IC'] + $addOn_Value['field_IC'] * 0.18) }}</h6>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <div class="item-container item-container1">
                            <span>
                                <input type="checkbox" name="addon" id="ic-cb" class="addon-checkbox"
                                    {{ in_array('ic', $addon) ? 'checked' : '' }} data-addon="ic">
                            </span>
                        </div>

                    </div>
                </div>

                <!-- Annual Health Check-up -->
                <div class="row addrow" id="ahc"
                    style="{{ in_array('ahc', $compulsoryaddon) ? 'display:none;' : '' }}">
                    <div class="col-md-12 col-lg-8 col-xl-8">
                        <h6>Annual Health Check-up</h6>
                        <p>Once for all insured every policy year</p>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <p>Premium</p>
                        <h6>{{ ceil($addOn_Value['field_AHC'] + $addOn_Value['field_AHC'] * 0.18) }}</h6>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <div class="item-container item-container1">
                            <span>
                                <input type="checkbox" name="addon" id="ahc-cb" class="addon-checkbox"
                                    {{ in_array('ahc', $addon) ? 'checked' : '' }} data-addon="ahc">
                            </span>
                        </div>

                    </div>
                </div>

                <!-- Claim Shield -->
                <div class="row addrow" id="cs"
                    style="{{ in_array('cs', $compulsoryaddon) ? 'display:none;' : '' }}">
                    <div class="col-md-12 col-lg-8 col-xl-8">
                        <h6>Claim Shield</h6>
                        <p>Get claim for expenses incurred on 68 Non-Payable items as per list of items in policy T&C
                        </p>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <p>Premium</p>
                        <h6>{{ ceil($addOn_Value['field_CS'] + $addOn_Value['field_CS'] * 0.18) }}</h6>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <div class="item-container item-container1">
                            <span>
                                <input type="checkbox" name="addon" id="cs-cb" class="addon-checkbox"
                                    {{ in_array('cs', $addon) ? 'checked' : '' }} data-addon="cs">
                            </span>
                        </div>

                    </div>
                </div>

                <!-- BE-FIT -->
                <div class="row addrow" id="befit"
                    style="{{ in_array('befit', $compulsoryaddon) ? 'display:none;' : '' }}">
                    <div class="col-md-12 col-lg-8 col-xl-8">
                        <h6>Befit Benefit</h6>
                        <p>Unlimited visits to Fitness centres can be availed by Insured members aged above 12 years.
                        </p>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <p>Premium</p>
                        <h6>{{ ceil($addOn_Value['field_Befit'] + $addOn_Value['field_Befit'] * 0.18) }}</h6>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <div class="item-container item-container1">
                            <span>
                                <input type="checkbox" name="addon" id="befit-cb" class="addon-checkbox "
                                    {{ in_array('befit', $addon) ? 'checked' : '' }} data-addon="befit">
                            </span>
                        </div>

                    </div>
                </div>

                <!-- PED Wait Period Modification -->
                <div class="row addrow" id="pwpm"
                    style="{{ in_array('pwpm', $compulsoryaddon) ? 'display:none;' : '' }}">
                    <div class="col-md-12 col-lg-8 col-xl-8">
                        <h6>PED Wait Period Modification</h6>
                        <p>You can reduce your PED wait period by opting for this benefit. Select Reduction Period</p>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <p>Premium</p>
                        <select class="mt-1" name="pedaddonvalue" id="pedaddonvalue">
                            <option value="" selected disabled>Select</option>
                            <option value="1" {{ in_array('1', $addon) ? 'selected' : '' }}>1 year</option>
                            <option value="2" {{ in_array('2', $addon) ? 'selected' : '' }}>2 years</option>
                        </select>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <div class="item-container item-container1">
                            <span>
                                <input type="checkbox" name="addon" id="pwpm-cb" class="addon-checkbox"
                                    {{ in_array('pwpm', $addon) ? 'checked' : '' }} data-addon="pwpm">
                            </span>
                        </div>
                    </div>

                </div>

                <!-- OPD Care -->
                <div class="row addrow" id="opd"
                    style="{{ in_array('opd', $compulsoryaddon) ? 'display:none;' : '' }}">
                    <div class="col-md-12 col-lg-8 col-xl-8">
                        <h6>OPD Care</h6>
                        <p>Get covered for 4 General Physician consultations and any 4 Specialist consultations from the
                            list of 14 specified specialists. You will get a maximum reimbursement of Rs 500 for each
                            consultation.</p>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <p>Premium</p>
                        <h6>{{ ceil($addOn_Value['field_OPD'] + $addOn_Value['field_OPD'] * 0.18) }}</h6>
                    </div>
                    <div class="col-md-12 col-lg-2 col-xl-2 text-center">
                        <div class="item-container item-container1">
                            <span>
                                <input type="checkbox" name="addon" id="opd-cb" class="addon-checkbox"
                                    {{ in_array('opd', $addon) ? 'checked' : '' }} data-addon="opd">
                            </span>
                        </div>

                    </div>
                </div>

            </div>
            <div class="col-md-12 col-lg-12">

                <button id="applybtn" class="btnsm mb-2" style="float: right;">Apply</button>
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

        // const selectedValue = pwpmCheckbox.checked ? pedAddonValue.value : '';
        if (icCheckbox.checked) {
            pwpmCheckbox.disabled = true;
        }

        if (pwpmCheckbox.checked) {
            icCheckbox.disabled = true;
        }

        icCheckbox.addEventListener('change', function() {
            if (icCheckbox.checked) {
                pwpmCheckbox.disabled = true;
            } else {
                pwpmCheckbox.disabled = false;
            }
        });
        pwpmCheckbox.addEventListener('change', function() {
            if (pwpmCheckbox.checked) {
                icCheckbox.disabled = true; // Disable icCheckbox if pwpm-cb is checked
            } else {
                icCheckbox.disabled = false; // Enable icCheckbox if pwpm-cb is unchecked
                pedAddonValue.value = ''; // Clear the value when unchecked
            }
        });
    });
    // ped checkbox checked then slect option enable start
    $('#pwpm-cb').change(function() {
        $('#pedaddonvalue').prop('disabled', !this.checked);
    }).change();
    // ped checkbox checked then slect option enable end
</script>
