@include('front.motor.car.common.partial.common')
<script src="{{ config('constant.BASE_URL') }}front/js/jquery.min.js"></script>
<script src="{{ config('constant.BASE_URL') }}front/js/customscript.js"></script>
<script src="{{ config('constant.BASE_URL') }}front/js/bootstrap.min.js"></script>
<script src="{{ config('constant.BASE_URL') }}front/js/bootstrap.bundle.min.js"></script>
<script src="{{ config('constant.BASE_URL') }}front/js/digibima.js"></script>
<script src="{{ config('constant.BASE_URL') }}front/js/bs-datepicker.js"></script>
<script src="{{ config('constant.BASE_URL') }}front/js/select2.js"></script>
<script src="{{ config('constant.BASE_URL') }}front/js/flatpickr.js"></script>
<script src="{{ config('constant.BASE_URL') }}library/ckeditor/ckeditor.js"></script>

<!-- <script src="{{ config('constant.BASE_URL') }}front/css/fontawesomeicon/js/all.min.js"></script> -->

{{-- ------------------------pwa ----------------------------- --}}
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/public/service-worker.js')
                .then((registration) => {
                    //console.log('ServiceWorker registration successful:', registration);
                })
                .catch((error) => {
                    //console.error('ServiceWorker registration failed:', error);
                });
        });
    }

    async function CallAPI(route, data, others) {
        try {
            const response = await fetch(route, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    'data': data
                })
            });
            return await response.json();
        } catch (error) {
            console.error("Error:", error);
            return error;
        }
    }

    async function CallAPIGetPincodeData(route, data, others) {
        //console.log(route, data, others);
        // retrun false;
        try {
            const response = await fetch(route, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    [others]: data
                })
            });
            return await response.json();
        } catch (error) {
            console.error("Error:", error);
            return error;
        }
    }
    async function UploadDocumentCallAPI(route, formData) {
        try {
            console.log("Sending FormData to API:", formData);

            const response = await fetch(route, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: formData
            });

            const result = await response.json();
            return result;
        } catch (error) {
            console.error("API Error:", error);
            return error;
        }
    }


    // CallAPI("{{ route('car.addaddon') }}", selectedAddOns, "").then(response => {
    //     console.log("API response data:", response);
    // }).catch(error => {
    //     console.error("API call failed:", error);
    // });

   var errorBoxone = document.querySelector('.MainErrorBox');
    var errorTitleElementone = errorBoxone.querySelector('.error__title');
    var verifiedBoxOne = document.getElementById('MainVerifiedBox');
    var verifiedTitleOne = verifiedBoxOne?.querySelector('.verifiyed__title');

    function showErrorOne(message) {
        if (!errorBoxone || !errorTitleElementone) return;

        errorTitleElementone.innerText = message;
        errorBoxone.style.display = "flex";

        setTimeout(() => {
            errorBoxone.style.display = 'none';
        }, 3000);
    }
    
    function showSuccessOne(message) {
    if (!verifiedBoxOne || !verifiedTitleOne) return;

    verifiedTitleOne.innerText = message;
    verifiedBoxOne.style.display = "flex";

    setTimeout(() => {
        verifiedBoxOne.style.display = 'none';
    }, 3000);
}

if("{{session()->has('success')}}")
{
    showSuccessOne("{{session('success')}}")
}
if("{{session()->has('error')}}")
{
    showErrorOne("{{session('error')}}")
}


</script>
