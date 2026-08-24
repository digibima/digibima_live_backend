(function (global) {
    
          const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox.querySelector('.error__title');
                // Function to show a message when KYC is verified
        function showVerified(message) {
            const successBox = document.querySelector('.MainverifiyedBox');
            const successTitleElement = successBox.querySelector('.verifiyed__title');
            successBox.style.display = "flex";
            successTitleElement.innerText = message;
            setTimeout(() => {
                successBox.style.display = 'none';
            }, 3000);
        }

        function displayError(message, inputerror, id) {
      

            if (errorBox && errorTitleElement) {
                errorTitleElement.innerText = `${message} ${inputerror}`;
                errorBox.style.display = "flex";
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                const inputElement = document.getElementById(id);

                if (inputElement) {
                    inputElement.classList.add('highlight-error');
                    setTimeout(() => {
                        inputElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        inputElement.focus();
                    }, 100);
                }
            }
        }
    function updateFileName(inputId) {
        const fileInput = document.getElementById(inputId);

        if (fileInput.files && fileInput.files.length > 0) {
            let fileName = fileInput.files[0].name;
            const maxLength = 10;
            if (fileName.length > maxLength) {
                fileName = fileName.substring(0, maxLength) + '...';
            }
            const label = fileInput.previousElementSibling;
            if (label && label.classList.contains('fileNameDisplay')) {
                label.textContent = `Selected: ${fileName}`;
            }
            console.log(`File selected in ${inputId}: ${fileName}`);
        }
    }

    function uploadDocument(event,undermsg,api) {
        event.preventDefault();
      
        

        let isValid = true;
        let errorMessage = '';

        const fathernameValue = $('#otherCardfathername').val();
        const identityValue = $('#identitytypeproof').val();
        const addressValue = $('#addresstypeproof').val();
        const uploadphoto = $('#upload-photo')[0];

        // const under = 'individual' || '';
        let under = undermsg;
        let apiroute = api;
        // console.log(under,apiroute);
        // return false;

        if (!fathernameValue && under === 'individual') {
            $('#otherCardfathername').addClass('error');
            errorTitleElement.innerText = `Please Enter Father's Name.`;
            errorBox.style.display = 'flex';
            setTimeout(() => { errorBox.style.display = 'none'; }, 3000);
            return false;
        }

        if (!identityValue) {
            errorTitleElement.innerText = `Please select an Identity Proof Type.`;
            errorBox.style.display = 'flex';
            setTimeout(() => { errorBox.style.display = 'none'; }, 3000);
            return false;
        }

        if (!addressValue) {
            errorTitleElement.innerText = `Please select an Address Proof Type.`;
            errorBox.style.display = 'flex';
            setTimeout(() => { errorBox.style.display = 'none'; }, 3000);
            return false;
        }

        if (!uploadphoto.files.length) {
            isValid = false;
            errorMessage = 'Please upload Media before proceeding.';
        }

        function validateField(selector, msg) {
            if (!$(selector).val() || ($(selector)[0].files && !$(selector)[0].files.length)) {
                isValid = false;
                errorMessage = msg;
            }
        }

        // === Address Proofs ===
        if (addressValue === 'aadhar') {
            validateField('#addresstypeaadharnumber', 'Please enter the last 4 digits of your Aadhaar number for address.');
            validateField('#addressidentity_aadhar', 'Please upload Address Aadhaar (Front) Proof.');
        } else if (addressValue === 'pan') {
            validateField('#addresspancard', 'Please upload Address PAN Proof.');
        } else if (addressValue === 'passport') {
            validateField('#addresstypepassportnumber', 'Please enter the Passport number for address.');
            validateField('#addresspassportcard', 'Please upload Address Passport Proof.');
        } else if (addressValue === 'drivinglicense') {
            validateField('#addressdlnumber', 'Please enter the Driving License number for address.');
            validateField('#addressdlfront', 'Please upload Address Driving License (Front) Proof.');
        } else if (addressValue === 'voterid') {
            validateField('#addresstypevoteridumber', 'Please enter the Voter ID number for address.');
            validateField('#addressvoterfront', 'Please upload Address Voter ID (Front) Proof.');
        } else if (addressValue === 'form60') {
            validateField('#addressform60', 'Please upload Address Form 60 Proof.');
        } else if (addressValue === 'gst') {
            validateField('#addresstypegstnumber', 'Please enter the GST number for address.');
            validateField('#addressfront', 'Please upload Address GST Proof.');
        } else if (addressValue === 'other') {
            validateField('#addresstypeothernumber', 'Please enter the Other number for address.');
            validateField('#addressother', 'Please upload Other ID Proof.');
        }

        // === Identity Proofs ===
        if (identityValue === 'aadhar') {
            validateField('#identityaadharnumber', 'Please enter the last 4 digits of your Aadhaar number for identity.');
            validateField('#identity_aadhar', 'Please upload Identity Aadhaar (Front) Proof.');
        } else if (identityValue === 'pan') {
            validateField('#identitypannumber', 'Please enter your PAN number for identity.');
            validateField('#identity_pancard', 'Please upload Identity PAN Proof.');
        } else if (identityValue === 'passport') {
            validateField('#identitypassportnumber', 'Please enter your Passport number for identity.');
            validateField('#identitypassportcard', 'Please upload Identity Passport Proof.');
        } else if (identityValue === 'drivinglicense') {
            validateField('#identitydlnumber', 'Please enter your Driving License number for identity.');
            validateField('#drivingfront', 'Please upload Identity Driving License (Front) Proof.');
        } else if (identityValue === 'voterid') {
            validateField('#identitypevoteridumber', 'Please enter your Voter ID number for identity.');
            validateField('#voterfront', 'Please upload Identity Voter ID (Front) Proof.');
        } else if (identityValue === 'form60') {
            validateField('#form60', 'Please upload Identity Form 60 Proof.');
        } else if (identityValue === 'gst') {
            validateField('#identitygstnumber', 'Please enter the GST number for identity.');
            validateField('#identity_gst', 'Please upload Identity GST Proof.');
        } else if (identityValue === 'other') {
            validateField('#identityothernumber', 'Please enter your Other number for identity.');
            validateField('#identity_other', 'Please upload Identity Other Proof.');
        }

        if (!isValid) {
            errorTitleElement.innerText = errorMessage;
            errorBox.style.display = 'flex';
            setTimeout(() => {
                errorBox.style.display = 'none';
            }, 3000);
            return false;
        }

        const fileInputs = document.querySelectorAll('.fileInput');
        const formData = new FormData();

        fileInputs.forEach(input => {
            if (input.files.length > 0) {
                formData.append(input.name, input.files[0]);
            }
        });

        const inputFields = document.querySelectorAll('#othercardDetails input[type="text"], #othercardDetails input[type="hidden"], #othercardDetails select');
        inputFields.forEach(input => {
            const key = input.name || input.id;
            const value = input.value.trim();
            if (key && value !== "") {
                formData.append(key, value);
            }
        });

        uploadDocumentApi(formData,apiroute);
    }

    async function uploadDocumentApi(formData ,apiroute) {
        console.log(apiroute);
        let aUploadResponse = "";
        try {
            aUploadResponse = await UploadDocumentCallAPI(apiroute, formData, "");
        } catch (error) {
            console.error("API error:", error);
            return;
        }

        const status = aUploadResponse.kyc;
        if (status == 1) {
            const successBox = document.querySelector('.MainverifiyedBox');
            const successTitleElement = successBox.querySelector('.verifiyed__title');
            successBox.style.display = "flex";
            successTitleElement.innerText = `Others verification successful! You can now continue.`;
            setTimeout(() => {
                successBox.style.display = 'none';
            }, 3000);
            $('#uploadbtn').text('Uploaded');
            $('#uploadbtn').prop('disabled', true);
        } else {
            const errorBox = document.querySelector('.MainErrorBox');
            const errorTitleElement = errorBox.querySelector('.error__title');
            errorBox.style.display = "flex";
            errorTitleElement.innerText = `${aUploadResponse.message || "Verification failed."}`;
        }
    }

    // expose globally
    global.updateFileName = updateFileName;
    global.uploadDocument = uploadDocument;
     global.showVerified = showVerified;
    global.displayError = displayError;
    console.log("upload-documents.js loaded");
})(window);

(function (global, $) {
    function initStepForm(options = {}) {
        let currentStep = 1;
        let validOk;
        const totalSteps = $('.step').length;

        const validateFormStepOne = options.validateFormStepOne || function () { return true; };
        const saveFormStepOne = options.saveFormStepOne || function () { return Promise.resolve({ success: true }); };
        const displayError = options.displayError || function () { };

        updateProgressBar();
        updateNavigationButtons();

       $(".next-step").click(function (event) {
    event.preventDefault();

    if (validateAllFields()) {
        if (currentStep === 1) {
            validOk = validateFormStepOne(); // your custom validation
        }

        if (validOk) {
            if (currentStep === 1) {
                $("#loader").show();
                saveFormStepOne()
                    .then(function (response) {
                        $("#loader").hide();
                        if (response.success) {
                            transitionToStep(currentStep + 1, 'next');
                        } else {
                            alert("Something went wrong. Please check your details.");
                        }
                    })
                    .catch(function (error) {
                        $("#loader").hide();
                        console.error("AJAX Error:", error);
                        displayError(error, null);
                    });
                return;
            }

            if (currentStep < totalSteps) {
                transitionToStep(currentStep + 1, 'next');
            }
        } else {
            console.log('Validation failed for step ' + currentStep);
        }
    } else {
        console.log('Validation blocked all fields');
    }
});


        $(".prev-step").click(function () {
            if (currentStep > 1) {
                transitionToStep(currentStep - 1, 'prev');
            }
        });

        $(".submit-step").click(function (event) {
            event.preventDefault();
            alert("Form submitted!");
        });

        function updateProgressBar() {
            var progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
            $(".progress-bar").css("width", progressPercentage + "%");
        }

        function validateAllFields() {
            return (currentStep === 1 && validateFormStepOne());
        }

        function transitionToStep(step, direction) {
            $(".step-" + currentStep)
                .removeClass("active")
                .addClass("animate__animated animate__fadeOut" + (direction === 'next' ? 'Left' : 'Right'));

            currentStep = step;

            setTimeout(function () {
                $(".step").removeClass("animate__animated animate__fadeOutLeft animate__fadeOutRight");
                $(".step-" + currentStep).addClass("active animate__animated animate__fadeIn" + (direction === 'next' ? 'Right' : 'Left'));
                updateProgressBar();
                updateNavigationButtons();
            }, 500);
        }

        function updateNavigationButtons() {
            $(".prev-step").toggle(currentStep > 1);
            $(".next-step").toggle(currentStep < totalSteps);
            $(".submit-step").toggle(currentStep === totalSteps);

            if (currentStep === 1) {
                $('#backPage').show();
                $('#backSlide').hide();
            } else {
                $('#backPage').hide();
                $('#backSlide').show();
            }
        }
    }
  function saveFormStepOne(apiUrl, formId, csrfToken = '') {
    return new Promise((resolve, reject) => {
        const formElement = document.getElementById(formId);
        if (!formElement) {
            reject(`Form with ID "${formId}" not found.`);
            return;
        }

        const formData = new FormData(formElement);
        formData.append('_token', csrfToken);

        $.ajax({
            url: apiUrl,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                const status = response.data.status;
                const verifyDetails = response.data.verify_details;
                const policyDetails = response.data.proposal;
                const paymentDetails = response.data.paymentSummery;

                document.querySelector('.gotopaynow').style.display = 'none';

                if (status === false) {
                    const errorDescription = response.data.apiresponse?.ERROR_DESC || response.data.error;
                    reject(errorDescription);
                } else if (status === true) {
                    document.querySelector('.gotopaynow').style.display = 'block';
                    setData(verifyDetails, policyDetails, paymentDetails);
                    resolve({ success: true });
                } else {
                    reject("Missing verify_details in response");
                }
            },
            error: function () {
                reject("AJAX request failed");
            }
        });

        function setData(verifyDetails, policyDetails, paymentDetails) {
            $('#headtotal').text(policyDetails.premium);
            $('#vehicledetailInfo').html(`
                <div class="rowDiv"><span class="labelspan">Vehicle Make & Model</span><span class="value">${verifyDetails.make} (${verifyDetails.model})</span></div>
                <div class="rowDiv"><span class="labelspan">Registration Date</span><span class="value">${verifyDetails.regdate}</span></div>
                <div class="rowDiv"><span class="labelspan">Chassis Number</span><span class="value">${verifyDetails.chassisno}</span></div>
                <div class="rowDiv"><span class="labelspan">Capacity</span><span class="value">${verifyDetails.capacity}</span></div>
                <div class="rowDiv"><span class="labelspan">Engine Number</span><span class="value">${verifyDetails.engineno}</span></div>
                <div class="rowDiv"><span class="labelspan">Idv</span><span class="value">${verifyDetails.idv}</span></div>
            `);

            $('#policydetailsDiv').html(`
                <div class="rowDiv"><span class="labelspan">Plan Name</span><span class="value">${policyDetails.planname}</span></div>
                <div class="rowDiv"><span class="labelspan">Plan Type</span><span class="value">${policyDetails.plantype}</span></div>
                <div class="rowDiv"><span class="labelspan">Policy Type</span><span class="value">${policyDetails.policytpe}</span></div>
                <div class="rowDiv"><span class="labelspan">Premium</span><span class="value">${policyDetails.premium}</span></div>
                <div class="rowDiv"><span class="labelspan">Product Code</span><span class="value">${policyDetails.productcode}</span></div>
                <div class="rowDiv"><span class="labelspan">Proposal Type</span><span class="value">${policyDetails.proposaltype}</span></div>
            `);

            let paymentDetailsHTML = '';
            for (const [key, value] of Object.entries(paymentDetails)) {
                if (key.toLowerCase() !== 'total') {
                    paymentDetailsHTML += `
                        <div class="rowDiv"><span class="labelspan">${key}</span><span class="value">${value}</span></div>
                    `;
                }
            }

            paymentDetailsHTML += `
                <div class="rowDiv total"><span>Total</span><span>${paymentDetails.Total}</span></div>
            `;

            $('#paymentdetailsDiv').html(paymentDetailsHTML);

            $('#make').text(verifyDetails.make);
            $('#modelvin').text(verifyDetails.model);
            $('#cubiccapacity').text(verifyDetails.capacity);
            $('#regdob').text(verifyDetails.regdate);
            $('#insudeclared').text(verifyDetails.idv);
            $('#chassisnumber').text(verifyDetails.chassisno);
            $('#engnumber').text(verifyDetails.engineno);
             resolve({ success: true });
        }
    });
}

    global.initStepForm = initStepForm;
    global.saveFormStepOne = saveFormStepOne;
})(window, jQuery);

