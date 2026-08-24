function getBrandName() {
    //let city = $("#dontknowcarcity").val();
    if (city.length > 1) {
        $.ajax({
            url: "{{route('shriram.getbrand')}}",
            type: "POST",
            data: {
                city: brand,
                _token: "{{ csrf_token() }}",
            },
            success: function (response) {
                console.log(response);
                return response;
            },
        });
    }
}

function shy() {
    console.log('hiiiiii');
    }
function getModelName(model) {
    $.ajax({
        url: "{{route('shriram.getbrand')}}",
        type: "POST",
        data: {
            city: model,
            _token: "{{ csrf_token() }}",
        },
        success: function (response) {
            console.log(response);
        },
    });
}
