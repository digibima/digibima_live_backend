<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>illnesses for which</title>
    <style>
        /* Base Styles */
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

        .fa-circle-exclamation {
            color: #fff;
        }




        /* Large Devices (laptops, 992px and up) */
        @media (min-width: 992px) and (max-width: 1199.98px) {
            #slide3 {
                padding: 2rem;
            }

            .item-container {
                margin-bottom: 1rem;
            }

            .col-lg-4 {
                width: 33.33%;
            }

            .continue {
                width: 150px;
            }

            .viewplans {
                width: auto;
                padding: 0.5rem;
            }
        }

        /* Extra Large Devices (large desktops, 1200px and up) */
        @media (min-width: 1200px) {
            #slide3 {
                padding: 2rem;
            }

            .item-container {
                margin-bottom: 1rem;
            }

            .col-lg-4 {
                width: 33.33%;
            }

            .continue {
                width: 150px;
            }

            /* .viewplans {
                width: auto;
                padding: 0.5rem;
            } */
        }

        /* Extra Small Devices (phones, 0px and up) */
        @media (min-width: 0px) and (max-width: 575.98px) {

            main {
                padding: 1rem 2rem !important;
            }

            #slide3 h5 {
                padding: 0px 0px !important;
                font-size: 22px !important;
            }

            #slide3 {
                padding: 1rem;
            }

            .col-lg-12,
            .col-md-12,
            .col-sm-12 {
                padding: 0 0.5rem;
            }

            .item-container {
                margin-bottom: 1rem;
            }

            .col-lg-4 {
                width: 100%;
                margin-bottom: 0rem;
            }

            .continue {
                width: 110px !important;
                height: 32px !important;
            }

            .viewplans {
                width: 100px !important;
                height: 32px !important;
            }

        }

        @media (min-width: 576px) and (max-width: 767.98px) {
            main {
                padding: 1rem 2rem !important;
            }

            #slide3 h5 {
                padding: 0px 0px !important;
                font-size: 25px !important;
            }

            #slide3 {
                padding: 1.5rem;
            }

            .item-container {
                margin-bottom: 1rem;
            }

            .col-lg-4 {
                width: 50%;
                margin-bottom: 1rem;
            }

            .viewplans,
            .continue {
                width: 100%;
            }
        }

        /* Medium Devices (small laptops, 768px and up) */
        @media (min-width: 768px) and (max-width: 991.98px) {
            main {
                padding: 1rem 2rem !important;
            }

            #slide3 h5 {
                padding: 0px 90px !important;
                font-size: 26px !important;
            }

            #slide3 {
                padding: 2rem;
            }

            .item-container {
                margin-bottom: 1rem;
            }


            .col-lg-4 {
                width: 50.33%;
                margin-bottom: 1rem;
            }

            .continue {
                width: 140px;
            }

            .viewplans {
                width: auto;
                padding: 0.5rem;
            }
        }
    </style>
    @include('front.partial.csslink')
</head>

<body>
    @include('front.partial.header')
    <div id="loader">
        <div class="loader-container">
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
            <div class="dot dot-3"></div>
            <div class="dot dot-4"></div>
        </div>
    </div>
    <div class="MainErrorBox" style="float: right;display:none; margin-right:30px;"><span class="error__icon"><i
                class="fa-solid fa-circle-exclamation"></i></span>
        <p class="error__title  mb-0" style="margin-right: 10px"></p> <span class="error__close"><i
                class="fa-solid fa-xmark"></i></span>
    </div>

    <main id="slider-container">

        <section id="slide3">
            <div class="row">
                <div class="col-lg-12">
                    <h5>Do any member(s) have any existing illnesses for which they take regular medication?</h5>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <form action="{{ route('plans') }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-12"></div>
                            <div class="col-lg-8 col-md-8 col-sm-12">
                                @php
                                    $checkedItems = $peddata == null ? [] : $peddata;
                                @endphp

                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12 ">
                                        <div class="item-container">
                                            <label for="Diabetes">Diabetes
                                                <span>
                                                    <input class="Diabetes" id="Diabetes" name="ped[]"
                                                        value="diabetes" type="checkbox"
                                                        {{ in_array('diabetes', $checkedItems) ? 'checked' : '' }}>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 ">
                                        <div class="item-container">
                                            <label for="bloodpressure">Blood Pressure
                                                <span>
                                                    <input class="bloodpressure" id="bloodpressure" name="ped[]"
                                                        value="bloodpressure" type="checkbox"
                                                        {{ in_array('bloodpressure', $checkedItems) ? 'checked' : '' }}>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 ">
                                        <div class="item-container">
                                            <label for="asthma">Asthma
                                                <span>
                                                    <input class="asthma" id="asthma" name="ped[]" value="asthma"
                                                        type="checkbox"
                                                        {{ in_array('asthma', $checkedItems) ? 'checked' : '' }}>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 ">
                                        <div class="item-container">
                                            <label for="Thyroid">Thyroid
                                                <span>
                                                    <input class="Thyroid" id="Thyroid" name="ped[]" value="thyroid"
                                                        type="checkbox"
                                                        {{ in_array('thyroid', $checkedItems) ? 'checked' : '' }}>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 ">
                                        <div class="item-container">
                                            <label for="heartdisease">Heart Disease
                                                <span>
                                                    <input class="heartdisease" id="heartdisease" name="ped[]"
                                                        value="heart" type="checkbox"
                                                        {{ in_array('heart', $checkedItems) ? 'checked' : '' }}>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 ">
                                        <div class="item-container">
                                            <label for="otherdisease">Other Disease
                                                <span>
                                                    <input class="otherdisease" id="otherdisease" name="ped[]"
                                                        value="other" type="checkbox"
                                                        {{ in_array('other', $checkedItems) ? 'checked' : '' }}>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 "></div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 ">
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
                                    <div class="col-lg-4 col-md-6 col-sm-12 "></div>
                                </div>


                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12"></div>
                        </div>
                        <div class="d-none">
                            <input type="submit" id="viewplans">
                        </div>
                    </form>
                </div>
                <div class="col-lg-12 mt-3 text-center">
                    {{-- @php
                        $encodedId = base64_encode(Auth::user()->id);
                    @endphp --}}
                    <a href="{{ route('insureview', ['id' => Auth::user()->id]) }}"><button
                            class="continue">Back</button></a>
                    <a href="#"  onClick="checkSelection()"><button class="continue">View
                            Plans</button></a>
                    {{-- <input type="submit" class="viewplans" value="View Plans" onClick="checkSelection()"> --}}
                </div>
            </div>
        </section>
    </main>
    @include('front.partial.chatwidget')
    @include('front.partial.footer')
    @include('front.partial.jslink')
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


        document.addEventListener('DOMContentLoaded', function() {
            const otherDiseaseCheckbox = document.getElementById('existingdisease');
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const itemContainers = document.querySelectorAll('.item-container');

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
        const errorBox = document.querySelector('.MainErrorBox');
        const errorTitleElement = errorBox?.querySelector('.error__title');

        function checkSelection() {
            let checkboxes = document.querySelectorAll('input[type="checkbox"]');

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
            $("#viewplans").click();
        } else {
            console.log("API response result: status 0");
        }
        };


        // Close button functionality
        document.addEventListener('DOMContentLoaded', () => {
            const closeButton = document.querySelector('.error__close');
            if (closeButton) {
                closeButton.addEventListener('click', () => {
                    const errorBox = document.querySelector('.MainErrorBox');
                    if (errorBox) {
                        errorBox.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>

</html>
