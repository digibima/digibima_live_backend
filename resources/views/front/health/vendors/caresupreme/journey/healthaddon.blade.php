@php
// dd($addon);
@endphp
<style>
    .error {
        border: 1px solid red !important;
    }

    #pwpmrowprice+span .remove-button {
        display: none;
    }
</style>
<script>
    var globalSelectedAddOns = [];
    let rowCounter = 0;


    function updateSelectedAddOnsList() {
        let rowCounter = 0;
        const list = document.querySelector("#selectAddonList");
        list.innerHTML = ""; // Clear the list

        const addonMappings = {
            ic: "Instant Cover",
            ahc: "Annual Health Check-up",
            cs: "Claim Shield",
            opd: "OPD Care",
            befit: "Befit Benefit",
            ncb: "Cumulative Bonus Super",
            pwpm: "PED Wait Period Modification",
            wb : 'Wellness Benefit'
        };

        const addonpriceMappings = {
            ic: "{{ ceil($addOn_Value['field_IC'] + $addOn_Value['field_IC'] * 0.18) }}",
            ahc: "{{ ceil($addOn_Value['field_AHC'] + $addOn_Value['field_AHC'] * 0.18) }}",
            cs: "{{ ceil($addOn_Value['field_CS'] + $addOn_Value['field_CS'] * 0.18) }}",
            opd: "{{ ceil($addOn_Value['field_OPD'] + $addOn_Value['field_OPD'] * 0.18) }}",
            befit: "{{ ceil($addOn_Value['field_Befit'] + $addOn_Value['field_Befit'] * 0.18) }}",
            ncb: "{{ ceil($addOn_Value['field_NCB'] + $addOn_Value['field_NCB'] * 0.18) }}",
            pwpm: "{{ ceil($addOn_Value['field_PWPM'] + $addOn_Value['field_PWPM'] * 0.18) }}",
            wb: "{{ ceil($addOn_Value['field_WB'] + $addOn_Value['field_WB'] * 0.18) }}",
           
        };

        // Ensure selectedAddOns is an array
        const selectedAddOnsArray = Object.values(selectedAddOns);
        // console.log('Processing Addon:', selectedAddOnsArray);

        selectedAddOnsArray.forEach((addon) => {
            // console.log('Processing Addon:', addon);

            // Skip processing for '1' and '2' as they need special handling
            if (addon === '1' || addon === '2') {
                // Place the price into the element with id 'pwpmrowprice'
                let price = addon || "N/A";
                const pwpmRowPriceElement = document.querySelector('#pwpmrowprice');
                if (pwpmRowPriceElement) {
                    pwpmRowPriceElement.innerHTML = `<small class="fw-bold" style="display: inline-block;">${price} Year</small>`;
                }
                return;
            }

            // For other addons, proceed as usual
            let fullName = addonMappings[addon] || addon;
            let price = addonpriceMappings[addon] || "N/A";

            const tr = document.createElement("tr");
            tr.setAttribute("data-addon", addon);
            tr.id = `addon-row-${rowCounter}`;
            rowCounter++;

            // Add the full name to the row, with a conditional ID if "PED Wait Period Modification" exists
            const tdName = document.createElement("td");
            let smallTagContent = `<small class="fw-bold">${fullName}</small>`;

            if (fullName === "PED Wait Period Modification") {
                smallTagContent = `<small class="fw-bold" id="pwpmrow">${fullName}</small>`;
            }

            tdName.innerHTML = smallTagContent;
            tr.appendChild(tdName);

            // Add the price and remove button
            const tdRemove = document.createElement("td");
            tdRemove.classList.add("text-end");

            let priceContent = `<small class="fw-bold" style="display: inline-block;">₹${price}</small>`;

            if (price === "0") {
                priceContent = `<small class="fw-bold" id="pwpmrowprice" style="display: inline-block;">₹${price}</small>`;
            }

            tdRemove.innerHTML = `
        ${priceContent}
        `;

            tr.appendChild(tdRemove);

            // Append the row to the list
            list.appendChild(tr);

            // Attach the event listener to the remove button for this specific row
            document.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('remove-button')) {
                    handleRemoveButtonClick(event);
                }
            });
        });

    }






    // proposal.php //
    document.addEventListener("DOMContentLoaded", () => {

        let applyClicked = false;
        let isAddOnsModified = false;

        const addAddonsRoute = window.addAddonsRoute;
        //console.log("route:",addAddonsRoute);
        const csrfToken = window.csrfToken;

        let selectedAddOns = window.selectedAddOns || [];

        console.log("select:",selectedAddOns);
        if (typeof selectedAddOns === 'object' && !Array.isArray(selectedAddOns)) {
            selectedAddOns = Array.from(Object.values(selectedAddOns));
        }
        if (!selectedAddOns.includes('pwpm')) {
            selectedAddOns = selectedAddOns.filter(item => item !== '1' && item !== '2');
            console.log('Array', selectedAddOns)
        }

        if (!Array.isArray(selectedAddOns)) {
            return;
        }


        function updateSelectedAddons(checkbox) {
            const addon = checkbox.getAttribute('data-addon');
            if (!selectedAddOns.includes('pwpm')) {
                selectedAddOns = selectedAddOns.filter(item => item !== '1' && item !== '2');
                console.log('Array', selectedAddOns)
            }
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

            globalSelectedAddOns = [...selectedAddOns];
        }


        document.querySelectorAll('.addon-checkbox').forEach(function(checkbox) {
            const addon = checkbox.getAttribute('data-addon');
            if (selectedAddOns.includes(addon)) {
                checkbox.checked = true;
            }

            checkbox.addEventListener('change', function() {
                updateSelectedAddons(checkbox);
                applyClicked = false;
            });
        });
        const pedAddonValueElement = document.getElementById('pedaddonvalue');
        pedAddonValueElement.addEventListener('change', function() {
            isAddOnsModified = true;
        });

        // Ensure that all elements with the class 'applybtn' are properly handled
        document.querySelectorAll('#applybtn').forEach(function(button) {
            button.addEventListener('click', function(event) {
                if (!selectedAddOns.includes('pwpm')) {
                    selectedAddOns = selectedAddOns.filter(item => item !== '1' && item !==
                        '2');
                    console.log('Array', selectedAddOns);
                }
                // Check if 'pwpm' checkbox is checked
                if ($('#pwpm-cb').prop('checked')) {
                    const pedAddonValue = document.getElementById('pedaddonvalue');
                    const selectedValue = pedAddonValue.value;

                    // If no value is selected, show error
                    if (!selectedValue) {
                        event.preventDefault();
                        errorBox.style.display = "flex";
                        errorTitleElement.innerText =
                            'Please select a value for PED Wait Period Modification.';
                        setTimeout(() => {
                            errorBox.style.display = 'none';
                        }, 3000);
                        pedAddonValue.classList.add('error');
                        return;
                    }

                    // Remove any existing 'pwpm' without value from selectedAddOns
                    // selectedAddOns = selectedAddOns.filter(item => !item.startsWith('pwpm='));

                    // Add the selected 'pwpm' value
                    if (selectedValue === '1' || selectedValue === '2') {
                        selectedAddOns = selectedAddOns.filter(value => value !== '1' &&
                            value !== '2');
                        selectedAddOns.push(`${selectedValue}`);
                        const pwpmRowPriceElement = document.querySelector('#pwpmrowprice');
                        if (pwpmRowPriceElement) {
                            pwpmRowPriceElement.innerHTML = `${selectedValue}`;
                        }
                    }

                }

                applyClicked = true;
                isAddOnsModified = false;
                handleAddButtonClick();
            });
        });






        // "Proceed To Proposal" button click handler
        document.getElementById('gotoproposal').addEventListener('click', function(event) {
            if (isAddOnsModified && !applyClicked) {
                event.preventDefault();
                errorBox.style.display = "flex";
                errorTitleElement.innerText =
                    'Please click Apply to save your AddOns changes before proceeding to the proposal.';
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                return;
            } else {
                document.getElementById('gotoproposalpage').click();
            }
        });


        function handleAddButtonClick() {
            updateAddOnsOnServer();
        }




        function handleRemoveButtonClick(event) {
            let target = event.target;

            // Traverse up the DOM to find the closest element with the class 'remove'
            while (target && !target.classList.contains("remove")) {
                target = target.parentElement;
            }

            if (target) {
                const row = target.closest("tr");
                if (!row) return;

                // Map full names to abbreviations
                const fullNameToAbbreviation = {
                    "Instant Cover": "ic",
                    "Annual Health Check-up": "ahc",
                    "Claim Shield": "cs",
                    "OPD Care": "opd",
                    "Befit Benefit ": "befit",
                    "Cumulative Bonus Super": "ncb",
                    "PED Wait Period Modification": "pwpm"
                };

                // Extract the full name from the row and convert it to abbreviation
                const fullName = row.querySelector("td small").textContent.trim();
                const addonType = fullNameToAbbreviation[fullName];

                if (!addonType) {
                    // console.log("Addon Type not found in mapping:", fullName);
                    return;
                }

                // console.log("Removing Addon Type:", addonType);

                // Find and remove the addonType from selectedAddOns
                const index = selectedAddOns.indexOf(addonType);
                if (index > -1) {
                    selectedAddOns.splice(index, 1);
                    updateSelectedAddOnsList();

                    // Enable the corresponding add button
                    const addButton = addButtonMap.get(addonType);
                    if (addButton) {
                        addButton.disabled = false;
                        addButton.style.opacity = "1";
                        addButtonMap.delete(addonType);
                    }

                    // Update the server with the new list of selected add-ons
                    updateAddOnsOnServer();
                } else {
                    // console.log("Addon Type not found in selectedAddOns:", addonType);
                }
            } else {
                // console.log("Remove button not found");
            }
        }


        function updateAddOnsOnServer() {
            fetch(addAddonsRoute, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        addon: selectedAddOns,
                    }),
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((data) => {
                    window.location.reload();
                })
                .catch((error) => {
                    console.error("Error during AJAX request:", error);
                });
        }

        updateSelectedAddOnsList();
    });

    // console.log('selectAddons', selectedAddOns);
    // console.log('databaseAddons', dbaddonsArray);
</script>