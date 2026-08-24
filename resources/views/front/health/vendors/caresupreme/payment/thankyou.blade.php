<?php
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThankYou</title>
    <style>
        /* .tablehead tr th{
            text-align: center!important;
        } */
        .fa-download {
            font-size: 18px;
            color: #88bbfd;
            cursor: pointer;
        }

        span {
            margin-left: 10px
        }

        .center-text {
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hightlight h6 {
            margin-bottom: 1rem;
        }

        .hightlight {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .getstarted {
            margin-top: 10px;
        }

        .content p {
            display: inline
        }


        .tbl {
            margin-bottom: 10px;
        }

        .tbl p {
            margin-bottom: 0;
        }

        .colbggrey {
            background: #f9f9f9;
        }

        .tbl td,
        .tbl th {
            border: 1px solid #ccc;
            font-size: 13px;
            padding: 5px 10px;
            background: #f9f9f9;
        }

        .tbl table.dataTable {
            border-collapse: collapse !important;
        }

        .tbl .table-bordered {
            border: 1px solid #dee2e6;
        }

        .tbl .table-bordered td,
        .tbl .table-bordered th {
            border: 0.5px solid #eeeeee;
        }

        .tbl #tbwidd td:nth-child(3) {
            width: 150px;
        }

        .tbl #tablenotify td {
            background: #fff;
        }

        .tbl #tablenotify .myradio__label {
            font-size: 14px;
            padding: 6px 10px 4px 22px;
            cursor: pointer;
        }

        .tbl .dataTables_length,
        .tbl .dataTables_info,
        .tbl .dataTables_filter {
            display: none;
        }

        .tbl table.dataTable thead .sorting:after,
        .tbl table.dataTable thead .sorting_desc:after,
        .tbl table.dataTable thead .sorting_asc:after {
            opacity: 0.8;
            content: "\21C5";
            display: none;
        }

        .tbl div.dataTables_wrapper div.dataTables_paginate {
            cursor: pointer;
            margin-bottom: 8px;
            margin-top: 20px;
        }

        .tbl .dataTables_paginate .paginate_button {
            margin-bottom: 5px;
            color: #000000;
            border: 1px solid #ddd;
            padding: 4px 10px;
            text-decoration: none;
            margin: 0 4px;
            font-size: 15px;
        }

        .tbl .dataTables_paginate .current {
            background-color: #0D6EFD;
            border: 1px solid #0D6EFD;
            color: #FFFFFF;
        }

        .tbl th {
            background: #fff;
            padding: 6px 10px;
            color: #000;
            font-weight: 500;
            font-size: 14px;
            text-transform: capitalize;
        }


        @media (min-width: 576px) and (max-width: 767.98px) {
            main {
                padding: 1.5rem !important;
            }

            .sidepera {
                margin-top: 1.8rem;
            }


            #slide3 h5 {
                font-size: 25px;
                padding: 0px 35px !important;
            }

        }

        @media (min-width: 0px) and (max-width: 575.98px) {
            main {
                padding: 1rem !important;
            }

            .sidepera {
                margin-top: 1.8rem;
            }

            #slide3 h5 {
                font-size: 23px !important;
                padding: 0px 25px !important;
            }

            .col-lg-6,
            .col-md-6,
            .col-sm-12 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
    @include('front.partial.csslink')

</head>

<body>
    @php
        // $policy = $policy;
        // dd($policy);
        //$status = $status;
        // $aPolicydata = $policyDetails;
        //dd($data);
        $aPolicydata = $data['policydetails'];
        // dd($aPolicydata);
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
    <main id="slider-container">
        <section id="slide3">
            <div class="container">
                <div class="row">

                    <div class="col-md-12 col-lg-12 col-sm-12 text-center">
                        {{-- <span><i class="fa-regular fa-circle-check mb-2"></i></span> --}}
                        <h5 class=" mb-3 text-center "><span class="">Thank For Purchase</span> </h5>


                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12 text-left">
                        <section class="hightlight mb-3">
                            <div class="col-md-12 col-lg-12 col-sm-12 ">
                                <h6 class="proposerhead"> Details</h6>
                                <div id="tbl" class="table-responsive table-bordered tbl">
                                    <table id="datasort" width="100%" data-ordering="false">
                                        <thead class="tablehead">
                                            <tr>
                                                <th scope="col">Policy Name</th>
                                                <th scope="col">Policy Type</th>
                                                <th scope="col">Proposal Number</th>
                                                <th scope="col">Policy Number</th>
                                                <th scope="col">Coverage</th>
                                                <th scope="col">Tenure</th>
                                                <th scope="col">Start Date</th>
                                                <th scope="col">Maturity Date</th>
                                                <th scope="col">Application Date</th>
                                                <th scope="col">Policy Status</th>
                                                <th scope="col">Download Policy</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>{{$data['policyname'] }}</p>
                                                </td>
                                                <td>
                                                    <p>{{$data['policycovertype']}}</p>
                                                </td>
                                                <td>
                                                    <p>{{ $aPolicydata->proposalNum }}</p>
                                                </td>
                                                <td>
                                                    <p>
                                                        @if (is_null($data['policynum']))
                                                            <p></p>
                                                        @else
                                                            <p>{{ $data['policynum'] }}</p>
                                                        @endif
                                                    </p>
                                                </td>
                                                <td>

                                                    <p>
                                                        <?php
                                                        if ($data['coverage'] === 100) {
                                                            echo '1cr';
                                                        } else {
                                                            echo $data['coverage'] . ' lac';
                                                        }
                                                        ?>
                                                    </p>
                                                    {{-- <p>{{$aPolicydata['coverage']}}</p> --}}
                                                </td>
                                                <td>
                                                    <p>{{ $data['tenure'] . ' Year' }}</p>
                                                </td>
                                                <td>
                                                    <p>{{ $aPolicydata->policyCommencementDt }}</p>
                                                </td>
                                                <td>
                                                    <p>{{ $aPolicydata->policyMaturityDt }}</p>
                                                </td>
                                                <td>
                                                    <p>{{ $aPolicydata->applicationDate ?? 'now'->format('d-m-Y') }}</p>
                                                </td>
                                                <td>
                                                    <p>{{ $aPolicydata->policyStatus }}</p>
                                                </td>
                                                <td>
                                                    <p><i class="fa-solid fa-download"></i></p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                        {{-- <section class="hightlight mb-3">
                            <div class="col-md-12 col-lg-12 col-sm-12 ">
                                <h6 class="proposerhead">proposal Details</h6>
                                <div id="tbl" class="table-responsive table-bordered tbl">
                                    <table id="datasort" width="100%" data-ordering="false">
                                        <thead class="tablehead">
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Phone No.</th>
                                                <th scope="col">Date of Birth</th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>${proposer.name}</p>
                                                </td>
                                                <td>
                                                    <p>${proposer.mobile}</p>
                                                </td>
                                                <td>
                                                    <p>${proposer.dob || 'N/A'}</p>
                                                </td>

                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section> --}}
                        {{-- <section class="hightlight mb-3">
                            <div class="col-md-12 col-lg-12 col-sm-12 ">
                                <h6 class="proposerhead">Address</h6>
                                <div id="tbl" class="table-responsive table-bordered tbl">
                                    <table id="datasort" width="100%" data-ordering="false">
                                        <thead class="tablehead">
                                            <tr>
                                                <th scope="col">Permanent Address</th>
                                                <th scope="col">Communication Address</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>${proposer.address}</p>
                                                </td>
                                                <td>
                                                    <p>${proposer.address}</p>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section> --}}
                        {{-- <section class="hightlight mb-3">
                            <div class="col-md-12 col-lg-12 col-sm-12 ">
                                <h6 class="proposerhead">Insured Members</h6>
                                <div id="tbl" class="table-responsive table-bordered tbl">
                                    <table id="datasort" width="100%" data-ordering="false">
                                        <thead class="tablehead">
                                            <tr>
                                                <th>Name</th>
                                                <th>Date Of Birth</th>
                                                <th>Age</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>${proposer.address}</p>
                                                </td>
                                                <td>
                                                    <p>${proposer.mobile}</p>
                                                </td>
                                                <td>
                                                    <p>${proposer.dob || 'N/A'}</p>
                                                </td>

                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section> --}}
                        {{-- <section class="hightlight mb-3">
                            <div class="col-md-12">

                                <div id="tbl" class="table-responsive table-bordered tbl">
                                    <table id="datasort" width="100%" data-ordering="false">
                                        <thead class="tablehead">
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Relation</th>
                                                <th scope="col">Nominee DOB</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p>${nominee.name}</p>
                                                </td>
                                                <td>
                                                    <p>${nominee.relation}</p>
                                                </td>
                                                <td>
                                                    <p>${nominee.dob}</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section> --}}
                       @if ($data['status'] && !empty($data['policynum']))
                       @php
                       $policynum = $data['policynum'];
                       @endphp
                        <button class="getstarted" onclick="downloadPolicyPdf('{{$policynum}}')">Download
                                PDF</button>
                         @else 
                        <button class="getstarted" onclick="gotoDashboard()">Go To Dashboard</button>
                       @endif 
                        
                    </div>
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

        function gotoDashboard(){
            window.location.href = "{{ route('userroot')}}";
        }

        function downloadPolicyPdf(policy) {
            console.log(policy);
            const downloadPolicyUrl = "{{ route('downloadpolicy', ['policy' => '__POLICY__']) }}";
            const url = downloadPolicyUrl.replace('__POLICY__', policy);
            console.log(url);
            const response = fetch(url);
            console.log(response);
            return false;
            // .then(response => response.blob())
            // .then(blob => {
            //     const href = window.URL.createObjectURL(blob);
            //     const link = document.createElement('a');
            //     link.href = href;
            //     link.download = `policy_${policy}.pdf`;
            //     document.body.appendChild(link);
            //     link.click();
            //     document.body.removeChild(link);
            // })
            // .catch(e => console.error('Error downloading policy PDF:', e));
        }
        //downloadPolicyPdf('200265');
    </script>
</body>

</html>
