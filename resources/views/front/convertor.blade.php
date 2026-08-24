@extends('layouts.app')

@section('css')
    <style>
        /* Output Containers */
        #encodedOutput,
        #decodedOutput {
            word-break: break-word;
            white-space: pre-wrap;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        /* Layout Container */
        .container {
            max-width: 800px;
            height: auto;
            width: 100%;
            padding: 0 15px;
            margin: 20px auto;
        }

        /* Tab Content Box */
        .tab-content {
            background: #ffffff;
            border-radius: 12px;
            padding: 25px;
            margin-top: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* Nav Tabs Styling */
        .nav-tabs .nav-link {
            font-weight: 500;
            border: 1px solid #ccc;
            border-radius: 10px !important;
            margin-right: 8px;
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #4e54c8, #8f94fb) !important;
            color: #fff !important;
            font-weight: bold !important;
        }

        /* Form Field Styling */
        .form-control {
            border-radius: 10px;
            width: 100%;
        }

        /* Buttons */
        .continue {
            width: 100%;
            max-width: 150px;
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            transition: transform 0.2s;
        }

        .continue:hover {
            transform: scale(1.02);
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            h2 {
                font-size: 1.5rem;
                text-align: center;
            }

            .tab-content {
                padding: 20px;
            }

            .continue {
                width: 100%;
            }

            .nav-tabs {
                flex-wrap: wrap;
                justify-content: center;
            }

            .nav-tabs .nav-link {
                margin-bottom: 8px;
            }
        }

        /* Fix for Active Nav Tab on Mobile */
        .nav .nav-item button.active {
            color: #fff !important;
        }
    </style>
@endsection


@section('content')
    <section id="planrow">
        <div class="container">
            <h2 class="text-center mb-4">Encode / Decode Text</h2>


            <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="encode-tab" data-bs-toggle="tab" data-bs-target="#encode"
                        type="button" role="tab">Encode</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="decode-tab" data-bs-toggle="tab" data-bs-target="#decode" type="button"
                        role="tab">Decode</button>
                </li>
            </ul>


            <div class="tab-content mb-2" id="myTabContent">

                <div class="tab-pane fade show active" id="encode" role="tabpanel" aria-labelledby="encode-tab">
                    <div class="mb-3">
                        <label for="encodeText" class="form-label">Enter Text to Encode:</label>
                        <input type="text" class="form-control" id="encodeText" placeholder="Type your text here...">
                    </div>
                    <button class="btn1 continue" onclick="stringEncode()">Encode</button>

                    <div class="mt-2" id="copyEncodeBtn" style="display: none;">
                        <p class="mt-3 fw-semibold" id="encodedOutput"></p>
                        <button class="continue" onclick="copyText('encodedOutput')">Copy</button>
                    </div>
                </div>

                <div class="tab-pane fade" id="decode" role="tabpanel" aria-labelledby="decode-tab">
                    <div class="mb-3">
                        <label for="decodeText" class="form-label">Paste Encoded Text:</label>
                        <textarea class="form-control" id="decodeText" rows="4" placeholder="Paste encoded string here..."></textarea>
                    </div>
                    <button class="btn1 continue" onclick="stringDecode()">Decode</button>

                    <div class="mt-2" id="copyDecodeBtn" style="display: none;">
                        <p class="mt-3 fw-semibold" id="decodedOutput"></p>
                        <button class="continue" onclick="copyText('decodedOutput')">Copy</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    @include('front.partial.callapi')
    <script>
        function stringEncode() {
            let inputField = document.getElementById('encodeText').value;

            CallAPI("{{ route('convertstring') }}", inputField, "encrypt")
                .then(response => {
                    console.log(response);
                    if (response.status == 1) {
                        document.getElementById('encodedOutput').textContent = response.string;
                        document.getElementById('copyEncodeBtn').style.display = 'block';
                    } else {
                        alert(response.message);
                    }
                });
        }

        function stringDecode() {
            let inputField = document.getElementById('decodeText').value;
            console.log(inputField);
            CallAPI("{{ route('convertstring') }}", inputField, "decrypt")
                .then(response => {
                    console.log(response);
                    if (response.status == 1) {
                        document.getElementById('decodedOutput').textContent = response.string;
                        document.getElementById('copyDecodeBtn').style.display = 'block';
                    } else {
                        alert(response.message);
                    }
                });
        }


        function copyText(outputId) {
            const text = document.getElementById(outputId).textContent.trim();
            navigator.clipboard.writeText(text)
                .then(() => {
                    alert('Text copied to clipboard!');
                })
                .catch(err => {
                    console.error('Failed to copy text: ', err);
                    alert('Failed to copy text.');
                });
        }
    </script>
@endsection
