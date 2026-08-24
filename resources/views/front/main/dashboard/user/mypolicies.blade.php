<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Policies</title>
    <meta name="viewport" content="width=device-width">

    @include('front.partial.csslink')
    <link href="{{ config('constant.BASE_URL') }}front/css/dashboard.css" rel="stylesheet">
    <link href="{{ config('constant.BASE_URL') }}front/css/datatable.min.css" rel="stylesheet">
    <style>
        input[name="insurance"] {
            display: none;
        }

        #maincontrow {
            height: 100vh;
            padding-top: 0%;

        }

        .form-control {
            appearance: auto;
        }

        /* #contentrow {
            background-color: #f4f4fa;
        } */

        #maincontrow td {
            color: #1D8AFF;

        }

        #sidebar {
            background: #fff;
        }

        #maincontrow #tbl td {
            color: #000;
        }

        .greenbtn:hover {
            color: #000;
            background-color: #DCF1E4;
            border-color: #DCF1E4;
        }

        .redbtn:hover {
            color: #000;
            background-color: #E2EAF7;
            border-color: #E2EAF7;
        }

        .fa-eye {
            background-color: transparent;
            color: #28BBB0;
            ;
        }

        .fa-download {
            background-color: transparent;
            color: #1285f7;
        }

        #filterrow select,
        #planrowblock select,
        #planrowblock .btnbg,
        .addrow select,
        .coverblock select {
            border: solid 1.5px #e7e7e7;
            color: #000;
            background-color: #fff;
        }

        #filterrow select:focus-visible {
            border: solid 1.5px #e7e7e7;
        }

        #contentrow,
        #maincontrow {
            background: #F4F4FA;
        }
    </style>

</head>

<body>
    <!-- partial:index.partial.html -->
    <!-- overlay -->
    @php
        $count = 0;
        $policy = $data['policies'];
        // dd($policy);
    @endphp

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
            <section class="row ">
                <div class="col-md-3 col-xs-12 mb-2">
                    <h5 class=" mb-2" style="text-align: left;">My Policies</h5>
                </div>
                <div class="col-md-9 col-xs-12 floatright mb-2">

                    <!-- <a href="#" class="btn thmbtn px-4 mb-2" data-toggle="modal" data-target="#addintegration">Add
                        New</a> -->
                </div>

                <div class="col-md-12 col-xs-12">
                    <div id="tbl" class="table-responsive table-bordered">
                        <table id="datasort" class="table table-striped" width="100%" data-ordering="false">
                            <thead class="tablehead">
                                <tr>
                                    <th scope="col">S.No.</th>
                                    <th scope="col">Proposer Name</th>
                                    <th scope="col">Policy Name</th>
                                    <th scope="col">Policy Type</th>
                                    <th scope="col">Proposal Number</th>
                                    <th scope="col">Policy Number</th>
                                    <th scope="col">Apply Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($policy as $rec) --}}
                                @foreach ($policy as $item)
                                    @php
                                        // Decode the JSON string into an object
                                        $status_details = json_decode($item->status_details);
                                    @endphp
                                    <tr>
                                        <td>
                                            <p>{{ ++$count . '.' }}</p>
                                        </td>
                                        <td>
                                            <p>
                                                @if (isset($item->proposar_name))
                                                    {{ $item->proposar_name }}
                                                @else
                                                    {{ 'NA' }}
                                                @endif
                                        </td>
                                        <td>
                                            <p>{{ strtoupper($item->policy_name) }}</p>
                                        </td>
                                        <td>
                                            <p>{{ strtoupper($item->policy_type) }}</p>
                                        </td>
                                        <td>
                                            <p>{{ $item->proposal }}</p>
                                        </td>
                                        <td>
                                            <p>

                                                @if (isset($item->policy))
                                                    {{ $item->policy }}
                                                @else
                                                    {{ 'NA' }}
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                @if (isset($status_details->startDate))
                                                    {{ \Carbon\Carbon::parse($status_details->startDate)->format('d-m-Y') }}
                                                @else
                                                    {{ 'NA' }}
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            <a href="#" class="btn greenbtn">Active</a>
                                        </td>
                                        <td>
                                            <table id="actiontbl">
                                                <tr>
                                                    <td>
                                                        <a href="#" data-toggle="modal"
                                                            data-target="#viewintegration" data-placement="bottom"
                                                            title="View"><i class="fa-regular fa-eye"></i></a>
                                                    </td>
                                                    <td>
                                                        <a href="#" data-toggle="modal"
                                                            data-target="#addintegration" data-placement="bottom"
                                                            title="Download"><i class="fa-solid fa-download"></i></a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach


                                {{-- @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </main>



    </div>

    <!-- partial -->
    @include('front.partial.chatwidget')
    @include('front.partial.jslink')
    <script src="{{ config('constant.BASE_URL') }}front/js/dashboard.js"></script>
    <script src="{{ config('constant.BASE_URL') }}front/js/datatable.min.js"></script>

    <script>
        new DataTable('#datasort');
        $('.dt-search input').attr('placeholder', 'Search in table...');

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
        var data = @json($data);
        console.log(data);
    </script>



</body>

</html>
