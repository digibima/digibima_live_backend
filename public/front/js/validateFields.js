var errorBox = document.querySelector('.MainErrorBox');
var errorTitleElement = errorBox.querySelector('.error__title');
var verifiedBox = document.querySelector('.MainverifiyedBox');
var verifiedTitle = verifiedBox.querySelector('.verifiyed__title');

/**
 * Display error message and apply error class
 * @param {string} message - The error message to display
 * @param {string} focusSelector - The input field selector to focus
 */
function showError(message, focusSelector) {
   
    errorTitleElement.innerText = message;
    const field = document.querySelector(focusSelector);
    
    if (field) {
        field.classList.add('error'); // Apply error class
        if (field.offsetParent !== null) { 
            field.focus();
        }
    }
    
    errorBox.style.display = "flex";
    setTimeout(() => {
        errorBox.style.display = 'none';
    }, 3000);
}




/**
 * Validate form fields
 * @param {string} id - The ID of the field
 * @param {string} field - The name of the field
 * @param {string} value - The value of the field
 * @returns {boolean} - Returns true if valid, 0 otherwise
 */
function validateField(id, field, value) {
    const element = document.getElementById(id);

    if (!element || element.offsetParent === null) {
        return true;
    }

    if (!value.trim()) {
        showError(`${field} is required`, `#${id}`);
        return 0;
    }

    let isValid = true;

    switch (field.toLowerCase()) {
        case "email":
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = emailPattern.test(value);
            break;
        case "name":
            isValid = value.length >= 2;
            break;
        case "phone":
            const phonePattern = /^[0-9]{10}$/;
            isValid = phonePattern.test(value);
            break;
        case "dob":
            const dob = new Date(value);
            const today = new Date();
            const age = today.getFullYear() - dob.getFullYear();
            isValid = !isNaN(dob.getTime()) && age <= 100;
            break;
        case "address":
            const addressPattern = /^[A-Za-z0-9\s,.-]{5,100}$/;
            isValid = addressPattern.test(value) && /[A-Za-z]/.test(value);
            break;
        default:
            break;
    }

    if (!isValid) {
        showError(`Invalid ${field}`, `#${id}`);
        return 0;
    }
    
    element.classList.remove('error'); // Remove error class if field is valid
    return 1;
}


// Form Submission




// ---------------------------Numeric Allowed Function Start -------------------------------------
/**
 * Restrict input field to only numeric values
 * @param {string} selector - The CSS selector for the input fields
 */
// function allowOnlyNumbers(selector) {
//     document.querySelectorAll(selector).forEach(inputField => {
//         inputField.addEventListener("input", function(event) {
//             this.value = this.value.replace(/\D/g, ""); // Remove non-numeric characters
//         });
//     });
// }
function allowOnlyNumbers() {
    const allInputs = document.querySelectorAll('.numeric-input');
     allInputs.forEach(input => {
        input.addEventListener('input', function() {
            input.value = input.value.replace(/\D/g, ""); 
        });
    });
}
// }

function OnlyNumbers(e) {
    e.target.value = e.target.value.replace(/\D/g, ""); 
}
function allowOnlyAlphabets() {
    const allInputs = document.querySelectorAll('.alphabet-input');

    allInputs.forEach(input => {
        input.addEventListener('input', function() {
            input.value = input.value.replace(/[^a-zA-Z\s]/g, ''); 
        });
    });
}

document.addEventListener('DOMContentLoaded', allowOnlyAlphabets);
document.addEventListener('DOMContentLoaded', allowOnlyNumbers);
// ---------------------------Numeric Allowed Function End -------------------------------------

// ---------------------------Date Of Birth Function Start -------------------------------------

// function dobpartenallowed(selector) {
//     document.querySelectorAll(selector).forEach(input => {
//         input.addEventListener("input", function () {
//             let value = input.value.replace(/\D/g, ''); // Remove non-numeric characters

//             if (value.length >= 3) value = value.slice(0, 2) + '-' + value.slice(2);
//             if (value.length >= 6) value = value.slice(0, 5) + '-' + value.slice(5);
          
//             input.value = value;
          
//         });
//     });
// }
{/* <script>
@if (session()->has('success'))
{
   
  
        
        showToast('success', '{{ session()->get('success') }}')
   
}
@endif
@if (session()->has('error'))
{
   


        showToast('warning', '{{ session()->get('error') }}')

}


@endif
</script> */}
// ---------------------------Date Of Birth Function Start -------------------------------------

// ---------------------------Pincode Function Start -------------------------------------

// ---------------------------Pincode Function Start -------------------------------------


