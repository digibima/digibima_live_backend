@php
    $proposalNum = $proposalNumber; // Passed from controller
    $returnURL = 'https://test.digibima.com/return/thankyou.php';
    //echo "Proposal Number: $proposalNum ";
    //echo " Return URL: $returnURL";
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('front.partial.csslink')
    <title>Payment</title>
    <style>
        main {
            width: 100%;
            min-height: 80vh;
        }

        #slide3 {
            min-height: 65vh;
        }

        #slide3 h5 {
            font-size: 25px;
            padding: 0px 165px;
        }

        h1 {
            color: #1C5FA8!important;
        }


        @media (min-width: 768px) and (max-width: 991.98px) {
            main {
                padding: 2rem 2rem;
            }

            .gender-container {
                flex-direction: column;
                align-items: center;
            }

            .gender-box {
                width: 100px;
                text-align: center;
                margin-bottom: 0.5rem;
            }

            #findtopplan .image {
                width: 650px;
                max-width: 100%;
                height: auto;
            }
        }

        @media (min-width: 576px) and (max-width: 767.98px) {
            main {
                padding: 1.5rem;
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
                padding: 1rem;
            }

            .sidepera {
                margin-top: 1.8rem;
            }

            #slide3 h5 {
                font-size: 20px;
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
</head>

<body>
    @include('front.partial.header')


    <main id="slider-container">

        <section id="slide3">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h5>Thank you for choosing us for your health insurance needs. After your payment is processed, you
                        will receive a confirmation email with all relevant details regarding your policy.</h5>
                </div>
                <div class="col-lg-12 text-center">
                    <h1>Proposal: {{ $proposalNum }}</h1>
                    <form action="https://apiuat.careinsurance.com/portalui/PortalPayment.run" name="PAYMENTFORM"
                        method="POST">
                        @csrf
                        <input type="hidden" name="proposalNum" value="{{ $proposalNum }}">
                        <input type="hidden" name="returnURL" value="{{ $returnURL }}">
                        <button type="submit" class="getstarted ">Payment</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
    @include('front.partial.chatwidget')
    @include('front.partial.footer')
    @include('front.partial.jslink')
</body>

</html>
