@php
    // dd($data);
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
        list.innerHTML = ""; // Clear list

        const addonMappings = @json($addonlist); // Full names
        const addonpriceMappings = @json($addonvalue); // Prices

        const selectedAddOnsArray = Object.values(selectedAddOns);

        selectedAddOnsArray.forEach((addon) => {
            let fullName = addonMappings[addon] ?? addon;
            let price = addonpriceMappings[addon] ??
                addonpriceMappings['ped1'] ??
                addonpriceMappings['ped2'] ??
                "N/A";

            // Special handling for ped1 and ped2
            if (addon === 'ped1' || addon === 'ped2') {
                fullName = addon === 'ped1' ? 'PED Wait Period Modification1' : 'PED Wait Period Modification2';
            }

            const tr = document.createElement("tr");
            tr.setAttribute("data-addon", addon);
            tr.id = `addon-row-${rowCounter++}`;

            const tdName = document.createElement("td");
            tdName.innerHTML =
                `<small class="fw-bold" ${addon === 'ped1' || addon === 'ped2' ? 'id="pwpmrow"' : ''}>${fullName}</small>`;
            tr.appendChild(tdName);

            const tdValue = document.createElement("td");
            tdValue.classList.add("text-end");

            if (addon === 'ped1') {
                tdValue.innerHTML = `<small class="fw-bold" id="pwpmrowprice">1 Year</small>`;
            } else if (addon === 'ped2') {
                tdValue.innerHTML = `<small class="fw-bold" id="pwpmrowprice">2 Years</small>`;
            } else {
                tdValue.innerHTML = `<small class="fw-bold">₹${price}</small>`;
            }

            tr.appendChild(tdValue);
            list.appendChild(tr);
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        let applyClicked = false;
        let isAddOnsModified = false;

        const addAddonsRoute = window.addAddonsRoute;
        const csrfToken = window.csrfToken;

        let selectedAddOns = window.selectedAddOns || [];

        if (typeof selectedAddOns === 'object' && !Array.isArray(selectedAddOns)) {
            selectedAddOns = Array.from(Object.values(selectedAddOns));
        }
        console.log("Selected AddOns on page load:", selectedAddOns);
        // Remove legacy or incorrect values
        selectedAddOns = selectedAddOns.filter(item => item !== '1' && item !== '2' && item !== 'ped');

        if (!Array.isArray(selectedAddOns)) return;

        function updateSelectedAddons(checkbox) {
            const addon = checkbox.getAttribute('data-addon');

            // Prevent "ped" (group key) from being added
            if (addon === 'ped') return;

            // Clean up any legacy/invalid entries
            selectedAddOns = selectedAddOns.filter(item => item !== '1' && item !== '2' && item !== 'ped');

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

        document.querySelectorAll('#applybtn').forEach(function(button) {
            button.addEventListener('click', function(event) {
                // Always remove ped1 and ped2 before applying new value
                selectedAddOns = selectedAddOns.filter(item => item !== 'ped1' && item !==
                    'ped2');

                if (!Array.isArray(selectedAddOns)) return;

                if ($('#pwpm-cb').prop('checked')) {
                    const pedAddonValue = document.getElementById('pedaddonvalue');
                    const selectedValue = pedAddonValue.value;

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

                    if (selectedValue === 'ped1' || selectedValue === 'ped2') {
                        selectedAddOns.push(selectedValue);

                        const pwpmRowPriceElement = document.querySelector('#pwpmrowprice');
                        if (pwpmRowPriceElement) {
                            pwpmRowPriceElement.innerHTML = selectedValue === 'ped1' ?
                                '1 Year' : '2 Years';
                        }
                    }
                }

                applyClicked = true;
                isAddOnsModified = false;
                handleAddButtonClick();
            });
        });

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

        function updateAddOnsOnServer() {
            fetch(addAddonsRoute, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        addon: selectedAddOns
                    }),
                })
                .then((response) => {
                    if (!response.ok) throw new Error("Network response was not ok");
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
</script>

{{-- // const addonpriceMappings = {
        //     // ic: "{{ ceil($addOn_Value['field_IC'] + $addOn_Value['field_IC'] * 0.18) }}",
        //     // ahc: "{{ ceil($addOn_Value['field_AHC'] + $addOn_Value['field_AHC'] * 0.18) }}",
        //     // cs: "{{ ceil($addOn_Value['field_CS'] + $addOn_Value['field_CS'] * 0.18) }}",
        //     // opd: "{{ ceil($addOn_Value['field_OPD'] + $addOn_Value['field_OPD'] * 0.18) }}",
        //     // befit: "{{ ceil($addOn_Value['field_BFB'] + $addOn_Value['field_BFB'] * 0.18) }}",
        //     // ncb: "{{ ceil($addOn_Value['field_NCB'] + $addOn_Value['field_NCB'] * 0.18) }}",
        //     // pwpm: "{{ ceil($addOn_Value['field_PWPM'] + $addOn_Value['field_PWPM'] * 0.18) }}"
        //     // ic: "{{ ceil($addOn_Value['field_IC']) }}",
        //     // ahc: "{{ ceil($addOn_Value['field_AHC']) }}",
        //     // cs: "{{ ceil($addOn_Value['field_CS']) }}",
        //     // opd: "{{ ceil($addOn_Value['field_OPD']) }}",
        //     // befit: "{{ ceil($addOn_Value['field_BFB']) }}",
        //     // pwpm: "{{ ceil($addOn_Value['field_PWPM']) }}"
        // }; --}}
