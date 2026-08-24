function getCarQuoteStream() {
    const source = new EventSource("{{ route('car.getcarquote') }}");

    source.onmessage = function(event) {
        if (event.data === "[DONE]") {
            source.close();
            $('#loaderquotes').hide();
            return;
        }

        const data = JSON.parse(event.data);
        console.log(data);
        handleQuoteData(data);
    };

    source.onerror = function(error) {
        console.error("SSE error:", error);
        source.close();
    };
}

function getCacheCarQuoteStream() {
    $('#addquots').empty();
    const source = new EventSource("{{ route('car.getcachecarquote') }}");

    source.onmessage = function(event) {
        if (event.data === "[DONE]") {
            source.close();
            $('#loaderquotes').hide();
            return;
        }

        const data = JSON.parse(event.data);
        console.log(data);
        handleQuoteData(data);
    };

    source.onerror = function(error) {
        console.error("SSE error:", error);
        source.close();
    };
}

function handleQuoteData(addDetails) {
    const idvValues = addDetails.quote.idv;
    $('#currentValue').val(idvValues);

    let minRange = Math.floor(idvValues + (idvValues * 0.15));
    let maxRange = Math.floor(idvValues + (idvValues * 0.20));

    $('#customRange1').attr({
        'min': minRange,
        'max': maxRange,
        'value': idvValues
    });

    document.getElementById('minrangespan').innerText = minRange;
    document.getElementById('maxrangespan').innerText = maxRange;

    let rangeInput = document.getElementById('customRange1');
    let currentValueDisplay = document.getElementById('currentValue');

    if (rangeInput && currentValueDisplay) {
        const updateValues = (value) => {
            currentValueDisplay.value = Number(value).toLocaleString();
        };

        const initialValue = currentValueDisplay.value.replace(/,/g, '') || rangeInput.value;
        rangeInput.value = initialValue;
        updateValues(initialValue);

        rangeInput.addEventListener('input', function() {
            updateValues(rangeInput.value);
        });

        currentValueDisplay.addEventListener('input', function() {
            let numericValue = currentValueDisplay.value.replace(/,/g, '');
            if (!isNaN(numericValue) && numericValue !== '') {
                numericValue = parseInt(numericValue, 10);
                if (numericValue >= rangeInput.min && numericValue <= rangeInput.max) {
                    rangeInput.value = numericValue;
                    updateValues(numericValue);
                } else {
                    alert(`Value must be between ${Number(rangeInput.min).toLocaleString()} and ${Number(rangeInput.max).toLocaleString()}`);
                    currentValueDisplay.value = '';
                }
            } else {
                currentValueDisplay.value = '';
            }
        });
    }

    addQuote(addDetails);
}

function addQuote(addDetails) {
    let quoteHTML = `
    <div class="col-md-12 col-lg-4 mb-4">
        <form action="${addDetails.quote.route}" method="post">
            @csrf
            <div class="insurance-card">
                <h5>${addDetails.quote.title ? addDetails.quote.title.toUpperCase() : ''} INSURANCE</h5>
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRwP0Q4zNV5jU9HSpJpyCcp4AfG6yHhsGMLIA&s" alt="ICICI Lombard" class="logo1">
                <p class="subtitle">Cover value (IDV) <span class="idv">₹${addDetails.quote.idv}</span></p>
                <input type="hidden" value="${addDetails.quote.price}" name="premium">
                <input type="hidden" value="${addDetails.quote.idv}" name="idv">
                <button type="submit" class="price-box">
                    <div>
                        <p class="buy-now mb-0">BUY NOW</p>
                        <p class="price mb-0">₹ ${addDetails.quote.price}</p>
                    </div>
                </button>
                <div class="plan-links" style="display:flex; justify-content: space-evenly;">
                    <a href="#" class="plan-details">Plan Details</a>
                    <a href="#" class="plan-details addonprice-details" 
                      data-addon='${JSON.stringify(addDetails.quote.addons) || "No addons available"}'>Addons</a>

                </div>
                <div class="details">
                    <p class="mb-0"><span>Basic Price</span> <span class="amount">₹ ${addDetails.quote.basicPrice || 2220}</span></p>
                    <p class="mb-0"><span>Personal accident cover</span> <span class="amount">₹ ${addDetails.quote.pac || 350}</span></p>
                </div>
            </div>
        </form>
    </div>`;

    $('#addquots').append(quoteHTML);

    // Addon Details Click Handler
    $(document).ready(function() {
        $('.addonprice-details').off('click').on('click', function(e) {
            e.preventDefault();

            let addonData = $(this).data('addon');

            // console.log("Addon Data:", addonData);
            // console.log("Addons Full Name:", addonsFullName);

            let addonPriceHTML = '';

            for (const [key, value] of Object.entries(addonData)) {
                let addonName = addonsFullName[key] || `Addon ${key}`;
                addonPriceHTML += `<li><span class="addon-name">${addonName}</span><span class="addon-price">₹${value}</span></li>`;

            }

            $('#importAddonprice').html(addonPriceHTML);

            $('#addonsPriceModal').modal('show');
        });
    });

}


getCarQuoteStream();






//   Open Addons Modal Fuction Start 

document.addEventListener('DOMContentLoaded', function() {
    const addOnsCheckbox = document.querySelector('#addonsCheckbox');
    const addAddonsModal = new bootstrap.Modal(document.getElementById('addAddonsModal'));

    addOnsCheckbox.addEventListener('change', function() {
        if (addOnsCheckbox.checked) {
            addAddonsModal.show();
        }
    });
    document.getElementById('addAddonsModal').addEventListener('hidden.bs.modal', function() {
        addOnsCheckbox.checked = false;
    });


    // ------------------------------------


    const accessoriesCheckbox = document.querySelector('#accessoriescheckbox');
    const electricalCheckbox = document.querySelector('#electricalcheckbox');
    const nonelectricalCheckbox = document.querySelector('#nonelectricalcheckbox');
    const lpgcngCheckbox = document.querySelector('#lpgcngcheckbox');

    const AccessoriesTypeDiv = document.getElementById('AccessoriesTypeDiv');
    const electricaltypeDiv = document.getElementById('electricaltypeDiv');
    const nonelectricaltypeDiv = document.getElementById('nonelectricaltypeDiv');
    const fueltypeDiv = document.getElementById('fueltypeDiv');

    // Toggle visibility for each checkbox
    accessoriesCheckbox.addEventListener('change', function() {
        AccessoriesTypeDiv.style.display = accessoriesCheckbox.checked ? 'flex' : 'none';
    });

    electricalCheckbox.addEventListener('change', function() {
        electricaltypeDiv.style.display = electricalCheckbox.checked ? 'flex' : 'none';
    });

    nonelectricalCheckbox.addEventListener('change', function() {
        nonelectricaltypeDiv.style.display = nonelectricalCheckbox.checked ? 'flex' : 'none';
    });

    lpgcngCheckbox.addEventListener('change', function() {
        fueltypeDiv.style.display = lpgcngCheckbox.checked ? 'flex' : 'none';
    });
});
//   Open Addons Modal Fuction End 


// Error And Success Message function start 
const errorBox = document.querySelector('.MainErrorBox');
const errorTitleElement = errorBox?.querySelector('.error__title');
const verifiedBox = document.querySelector('.MainverifiyedBox');
const verifiedTitle = verifiedBox?.querySelector('.verifiyed__title');

function showError(message, focusSelector) {
    errorTitleElement.innerText = message;
    document.querySelector(focusSelector).focus();
    errorBox.style.display = "flex";
    setTimeout(() => {
        errorBox.style.display = 'none';
    }, 3000);
}


function showVerified(message) {
    if (verifiedBox && verifiedTitle) {
        verifiedBox.style.display = "flex";
        verifiedTitle.innerText = message;
        setTimeout(() => {
            verifiedBox.style.display = 'none';
        }, 3000);
    }
}

// Error And Success Message function end



//   addons add  Fuction start



const addAddonsRoute = window.addAddonsRoute;

let isAddOnsModified = false;
let applyClicked = false;

// console.log("Initial selected add-ons:", selectedAddOns);

document.querySelectorAll('.addon-checkbox').forEach((checkbox) => {
    const addon = checkbox.getAttribute('data-addon');

    checkbox.checked = selectedAddOns.includes(addon);
    checkbox.addEventListener('change', function() {
        updateSelectedAddons(this);
        applyClicked = false;
    });
});

document.querySelectorAll('#applybtn').forEach((button) => {
    button.addEventListener('click', function() {
        let additionalacces = document.getElementById('accessoriescheckbox');
        let electricalcheckbox = document.getElementById('electricalcheckbox');
        let nonelectricalcheckbox = document.getElementById('nonelectricalcheckbox');
        let lpgcngcheckbox = document.getElementById('lpgcngcheckbox');
        let electricalinput = document.getElementById('eleaccessamount');
        let nonelectricalinput = document.getElementById('noneleaccessamount');
        let lpgcnginput = document.getElementById('fueltypeprice');
        let lpgcngselect = document.getElementById('fueltype');

        if (additionalacces.checked) {
            if (electricalcheckbox.checked && electricalinput.value.trim() === "") {
                showError(`Please enter Electrical Accessories Amount`, '#eleaccessamount');
                return false;
            }
            if (nonelectricalcheckbox.checked && nonelectricalinput.value.trim() === "") {
                showError(`Please enter Non Electrical Accessories Amount`, '#noneleaccessamount');
                return false;
            }
            if (lpgcngcheckbox.checked && (lpgcnginput.value.trim() === "" || lpgcngselect.value === "")) {
                showError(`Please enter LPG/CNG Accessories Amount and select a fuel type.`, '#fueltypeprice');
                return false;
            }
        }


        handleAddButtonClick();
    });
});

function handleAddButtonClick() {
    console.log("Final selected add-ons before API call:", selectedAddOns);

    let aResponse = CallAPI("{{ route('car.addaddon') }}", selectedAddOns, "").then(response => {
        var status = response.status;
        console.log(status);
        if (status == 1) {
            $('#addAddonsModal').modal('hide');
            getCacheCarQuoteStream();
        }
        console.log("API response result:", response);
    }).catch(error => {
        console.error("API error:", error);
    });
}

function updateSelectedAddons(checkbox) {
    const addon = checkbox.getAttribute('data-addon');

    if (checkbox.checked) {
        if (!selectedAddOns.includes(addon)) {
            selectedAddOns.push(addon);
            isAddOnsModified = true;
        }
    } else {
        const index = selectedAddOns.indexOf(addon);
        if (index !== -1) {
            selectedAddOns.splice(index, 1);
            isAddOnsModified = true;
        }
    }
    window.globalSelectedAddOns = [...selectedAddOns];
    // console.log("Updated selected add-ons:", selectedAddOns);
}

//  addons add Fuction End



//  change Plan Type Fuction start
let selectedValues = {};

document.addEventListener("DOMContentLoaded", () => {
    const pacoverCheckbox = document.getElementById("pacoverCheckbox");
    const planTypeSelect = document.getElementById("planType");

    const updateArray = () => {
        selectedValues = {
            pacover: pacoverCheckbox.checked ? "1" : "0",
            planetype: planTypeSelect.value || "none"
        };
    };
    let aPlaneResponse = "";
    const changePlanType = async () => {
        updateArray();

        try {
            aPlaneResponse = await CallAPI("{{ route('car.changeplantype') }}", selectedValues, "");
        } catch (error) {
            console.error("API error:", error);
        }
        var status = aPlaneResponse.status;
        console.log(status);
        if (status == 1) {
            console.log("Final  result:", aPlaneResponse);
            getCacheCarQuoteStream();
        } else {
            console.log("API response result: status 0");
        }
    };

    pacoverCheckbox.addEventListener("change", changePlanType);
    planTypeSelect.addEventListener("change", changePlanType);

    updateArray();

});

//  change Plan Type Fuction End