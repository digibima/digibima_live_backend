<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <meta name="viewport" content="width=device-width">

    @include('front.partial.csslink')
    <link href="{{ config('constant.BASE_URL') }}front/css/dashboard.css" rel="stylesheet">
    {{-- <script src="{{ config('constant.BASE_URL') }}front/js/tailwindcss.js"></script> --}}

    {{-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> --}}


</head>

<body>
    @php
        $username = Auth::user()->name;
        // dd($username);
    @endphp
    <!-- partial:index.partial.html -->
    <!-- overlay -->

    <div id="sidebar-overlay" class="overlay w-100 vh-100 position-fixed d-none"></div>
    <div id="loader">
        <div class="loader-container">
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
            <div class="dot dot-3"></div>
            <div class="dot dot-4"></div>
        </div>
    </div>
     <!-- Error Box -->
     <div id="MainErrorBox" style="float: right; display: none; margin-right:30px;" class="MainErrorBox">
        <span class="error__icon"><i class="fa-solid fa-circle-exclamation"></i></span>
        <p class="error__title mb-0" style="margin-right: 10px;"></p>
        <span class="error__close" onclick="hideAlert('error')"><i class="fa-solid fa-xmark"></i></span>
    </div>

    <!-- Success Box -->
    <div id="MainVerifiedBox" style="float: right; display: none; margin-right:30px;" class="MainverifiyedBox">
        <span class="verifiy__icon"><i class="fa-solid fa-circle-check"></i></span>
        <p class="verifiyed__title mb-0" style="margin-right: 10px;"></p>
        <span class="verifiyed__close" onclick="hideAlert('success')"><i class="fa-solid fa-xmark"></i></span>
    </div>
    <!-- sidebar -->
    @include('front.main.dashboard.sidebar.usersidebar')



    <div id="contentrow" class="col-md-9 col-lg-10 ms-md-auto px-0">
        <!-- top nav -->
        @include('front.main.dashboard.header.usernavbar')

        <!-- main content -->
        <main id="maincontrow">

            <section class="row mb-2">
                <div class="col-lg-12 mb-2">
                    <div class="card card-custom d-flex flex-row align-items-center p-4">
                        <div class="flex-grow-1">
                            <h5 class="text-highlight">Hello
                                @auth
                                    <span>{{ Auth::user()->name ?? Auth::user()->mobile }}..!</span>
                                @endauth🎉
                            </h5>
                            <p>Welcome to DigiBima Insurance. Your trusted partner in securing your future.</p>

                        </div>
                        <img src="{{ config('constant.BASE_URL') }}/front/images/man-with-laptop-light.png"
                            alt="User Image" class="rounded-circle" style="height: 140px;">
                    </div>
                </div>
            </section>
    

            <section class="row">

                <div class="col-lg-3 col-sm-6">
                    <div class="card-boxone bg-blue">
                        <div class="inner-one">
                            <h3> 00</h3>
                            <p> Total Policies </p>
                        </div>
                        <div class="icon-one">
                            <i class="fa-solid fa-landmark" aria-hidden="true"></i>
                        </div>

                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card-boxone bg-green">
                        <div class="inner-one">
                            <h3> 00</h3>
                            <p>Active Policies </p>
                        </div>
                        <div class="icon-one">
                            <i class="fa-solid fa-file" aria-hidden="true"></i>
                        </div>

                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-boxone bg-orange">
                        <div class="inner-one">
                            <h3> 00 </h3>
                            <p>Expired Policies </p>
                        </div>
                        <div class="icon-one">
                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                        </div>

                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card-boxone bg-red">
                        <div class="inner-one">
                            <h3>00</h3>
                            <p>Total Claims</p>
                        </div>
                        <div class="icon-one">
                            <i class="fa fa-users"></i>
                        </div>

                    </div>
                </div>

            </section>


            <section class="row">
                <div class="col-lg-12 ">
                    <div class="thirdRow">
                        <div class="header1">
                            <h2>Create Quote</h2>
                            <!-- <a href="#">See All</a> -->
                        </div>
                        <div class="insurance-options">
                            <label class="option selected" href="{{ route('health.root') }}">
                                <input type="radio" name="insurance" value="health" checked>
                                ❤️
                                <p>Health</p>
                            </label>
                            <label class="option">
                                <input type="radio" name="insurance" value="two-wheeler">
                                🛵
                                <p>Two Wheeler</p>
                            </label>
                            <label class="option">
                                <input type="radio" name="insurance" value="four-wheeler">
                                🚗
                                <p>Four Wheeler</p>
                            </label>

                            <label class="option">
                                <input type="radio" name="insurance" value="commercial">
                                🚚
                                <p>Commercial</p>
                            </label>
                            <label class="option">
                                <input type="radio" name="insurance" value="travel">
                                ✈️
                                <p>Travel</p>
                            </label>

                        </div>
                    </div>
                </div>
            </section>

        </main>



    </div>


    <!-- partial -->
    <!-- Updated Bootstrap 5 JS -->
    @include('front.partial.chatwidget')
    @include('front.partial.jslink')

    <script src="{{ config('constant.BASE_URL') }}front/js/dashboard.js"></script>


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


        document.querySelectorAll('input[name="insurance"]').forEach(input => {
            input.addEventListener('change', () => {
                // Remove the 'selected' class from all labels and add it to the selected one
                document.querySelectorAll('.option').forEach(label => label.classList.remove('selected'));
                input.closest('label').classList.add('selected');

                // Get the selected value
                const selectedValue = input.value;

                // Define the redirect URLs
                const redirectUrls = {
                    "four-wheeler": "{{ route('motor.root.login') }}",
                    "two-wheeler": "{{ route('motor.root.login') }}",
                    "commercial": "{{ route('motor.root.login') }}",
                    "travel": "https://digibima.com/contact/",
                    "health": "{{ route('health.root') }}"
                };

                // Open the selected URL in a new tab
                if (redirectUrls[selectedValue]) {
                    window.open(redirectUrls[selectedValue], '_blank');
                }
            });
        });

        // Add 'selected' class to the initially checked input
        document.querySelector('input[name="insurance"]:checked')?.closest('label').classList.add('selected');
    </script>



</body>

</html>
