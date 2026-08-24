<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Top Plans</title>
    <style>
        .offeryear {
            float: right !important;
            background: #FEF4CC;
            color: #000;
            padding: 10px;
            border-top-left-radius: 12px;
            /* Float the element to the right */
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
            height: 96px;
            position: relative;
        }
    </style>
    @include('front.partial.csslink')
</head>

<body class="planlistbg">
    @include('front.partial.header')
    <section id="filterrow">
        <div class="container-fluid">

            <div class="row filblock">
                @foreach (['Plan Type' => ['Base', '1 Cr Cover', 'Super Top Up'], 'Coverage' => ['3 Lac', '5 Lac', '10 Lac'], 'Insurers' => ['Select', 'Care Health', 'TATA AIG'], 'Features' => ['Select', 'Maternity Cover', 'Restoration Benefits', 'OPD Benefit'], 'Tenure' => ['Select', '1 Year', '2 Year', '3 Year'], 'Sort By' => ['Select', 'Relevance', 'Low to High', 'High to Low']] as $label => $options)
                <div class="col-md-6 col-lg-3 col-xl-1">
                    <div class="row filtercol">
                        <div class="col-md-6 col-xl-6 p-0"><label
                                class="filterlabel d-inline">{{ $label }}</label></div>
                        <div class="col-md-6 col-xl-6 p-0">
                            <select>
                                @foreach ($options as $option)
                                <option>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <section id="planrow">
        <div class="container-fluid">
            <div class="row filblock">
                <!-- Left Col Start -->
                <div class="col-md-8 col-lg-8 col-xl-9">
                    <div class="row" id="addquots">

                        <div class="col-md-12">
                            <!-- Plan Start -->
                            <div id="loaderquotes" class="row animated-background "></div>

                        </div>
                    </div>
                </div>
                <!-- Left Col End -->

                <!-- Right Col Start -->
                <div class="col-md-4 col-lg-4 col-xl-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2 shadow-sm">
                                <div id="quoteblock" class="row">
                                    <div class="col-md-12 col-lg-3"></div>
                                    <div class="col-md-12 col-lg-3"></div>
                                    <div class="col-md-12 col-lg-3"></div>
                                    <div class="col-md-12 col-lg-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right Col End -->
            </div>
        </div>
    </section>

    <div class="modal fade" id="featuremodal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-3">
            <div class="modal-content rounded-3">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="exampleModalLabel">Plan Features</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('front.partial.footer')
                </div>
                <div class="modal-footer border-0"></div>

            </div>
        </div>
    </div>
    @include('front.partial.footer')
    @include('front.partial.jslink')
</body>
<script>
    function getCarQuoteStream() {
        const source = new EventSource("{{ route('car.getcarquote') }}");

        source.onmessage = function(event) {
            if (event.data === "[DONE]") {
                source.close();
                console.log("Stream finished.");
                $('#loaderquotes').hide();
                return;
            }

            const data = JSON.parse(event.data);
            let addDetails = data;
            console.log(addDetails);

            let quotes = `
                            <div class="col-md-12">
                                
                                <!-- Plan Start -->
                                <form action="{{ route('shriram.carcheckout') }}" method="post">
                                    @csrf
                                        <div id="planrowblock" class="row shadow-sm">
                                           
                                    <div class="col-md-12 col-lg-12 col-xl-12">
                                        <span class="offeryear mb-2">${addDetails.quote.coverd}&nbsp;Year</span>
                                     </div>
                                     <input type="hidden" name="idv" value="${addDetails.quote.idv}">
                                     <input type="hidden" name="coverd" value="${addDetails.quote.coverd}">
                                     <input type="hidden" name="price" value="${addDetails.quote.price}">
                                     
                                    <div class="col-md-12 col-lg-3 col-xl-2">
                                        <img src="{{ config('constant.BASE_URL') }}front/images/motor.png" />
                                    </div>
                                    <div class="col-md-8 col-lg-6 col-xl-4">
                                         
                                       <h5>${addDetails.quote.title ? addDetails.quote.title.toUpperCase() : ''}&nbspINSURANCE</h5>
                                        <p class="offer">IDV*${addDetails.quote.idv}</p>
                                        <p class="offer"><a href="#" data-bs-toggle="modal" data-bs-target="#featuremodal">View Features >></a></p>
                                    </div>
                                    <div class="col-md-4 col-lg-3 col-xl-3"></div>
                                    <div class="col-md-12 col-lg-12 col-xl-3 text-center">
                                       
                                        <a href="{{ route('shriram.carcheckout') }}" rel="noopener noreferrer">
                                            <button class="getstarted mb-2">${addDetails.quote.price} /${addDetails.quote.coverd}
                                                </button>
                                        </a>
                                        <p class="muterate">${addDetails.quote.price}</p> <!-- Use template literal -->
                                    
                                       
                                    </div>
                                </div>
                                        </form>
                                        <input type="submit" class="d-none" onclick= "${addDetails.quote.idv}()">
                                <!-- Plan End -->
                            </div>`;

            console.log("Received quote:", data);
            $('#addquots').append(quotes);
        };

        source.onerror = function(error) {
            console.error("SSE error:", error);
            source.close();
        };
    }


    getCarQuoteStream();
</script>

</html>