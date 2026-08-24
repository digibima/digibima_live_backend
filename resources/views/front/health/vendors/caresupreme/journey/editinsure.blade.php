<div class="px-2 bg-white row" style="height: 520px; overflow-y: scroll;">
    @csrf
    @php
        $gender = $gender ?? 'male';
    @endphp

    @foreach (['self', $gender == 'male' ? 'wife' : 'husband'] as $member)
        <div class="col-lg-12 mb-2">
            <div class="d-flex">
                <div class="btn_one ml-2 parent-div">
                    <input type="checkbox" id="{{ $member }}box"
                        @if (!empty($aInsureData) && $aInsureData->contains('name', $member)) checked @endif value="{{ $member }}"
                        onchange="toggleButtons()">
                    <label for="{{ $member }}box">{{ ucfirst($member) }}</label>
                </div>

                <select name="{{ $member }}" id="{{ $member }}" class="btn_one Agebox">
                    <option value="">Age</option>
                    @for ($i = 18; $i <= 99; $i++)
                        <option value="{{ $i }}"
                            @if (
                                !empty($aInsureData) &&
                                    $aInsureData->contains('name', $member) &&
                                    $aInsureData->where('name', $member)->first()->age == $i) selected @endif>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>
    @endforeach

    <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
        <div class="btn_one parent-div" style="width:100%;">
            <input type="checkbox" id="child" name="children" value="children"
                @if (!empty($child)) @checked(count($child) > 0) @endif
                onchange="toggleButtons()">
            <label for="child" class="mr-2" style="margin-left:8px;">Children</label>
            <div style="display: inline;float: right;">
                <span class="minus-box" id="minusButton"><i id="minusButtonIcon"
                        class="fas fa-minus disabled" onclick="removeChildren()" disabled></i></span>
                <label id="count">0</label><span class="plus-box" id="plusButton"
                    style="margin-left: 8px;"><i id="plusButtonIcon" class="fas fa-plus disabled"
                        onclick="addChildren()" disabled></i></span>
            </div>
        </div>

    </div>
    <div class="col-lg-12 col-md-12 col-sm-12" id="mainchildContainer" style="display:none">
        <div class="child-container row" id="childContainer"></div>
    </div>
    @foreach (['father', 'mother', 'grandfather', 'grandmother', 'father-in-law', 'mother-in-law'] as $member)
        <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
            <div class="d-flex">
                <div class="btn_one ml-2 parent-div">
                    <input type="checkbox" id="{{ str_replace('-', '', $member) }}box"
                        value="{{ str_replace('-', '', $member) }}" onchange="toggleButtons()"
                        @if (!empty($aInsureData) && $aInsureData->contains('name', str_replace('-', '', $member))) checked @endif>
                    <label
                        for="{{ str_replace('-', '', $member) }}box">{{ ucfirst($member) }}</label>
                </div>
                <select name="{{ str_replace('-', '', $member) }}"
                    id="{{ str_replace('-', '', $member) }}" class="btn_one Agebox">
                    <option value="">Age</option>
                    @for ($i = 18; $i <= 99; $i++)
                        <option value="{{ $i }}"
                            @if (
                                !empty($aInsureData) &&
                                    $aInsureData->contains('name', str_replace('-', '', $member)) &&
                                    $aInsureData->where('name', str_replace('-', '', $member))->first()->age == $i) selected @endif>
                            {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
    @endforeach

</div>
<script>
       document.addEventListener("DOMContentLoaded", function() {
            ['self', 'wife', 'father', 'mother', 'grandfather', 'grandmother', 'fatherinlaw', 'motherinlaw'].forEach
                (member => {
                    // generateAgeOptions(document.getElementById(member));
                    const selectElement = document.getElementById(member);
                });

            const parentDivs = document.querySelectorAll('.parent-div');
            parentDivs.forEach(div => {
                div.addEventListener('click', (event) => {
                    if (event.target === div) {
                        const checkbox = div.querySelector('input[type="checkbox"]');
                        checkbox.checked = !checkbox.checked;
                        toggleButtons();
                    }
                });
            });

            toggleButtons();
        });

        let maxCount = 4;
        let currentCount = 0;
        @if (!empty($child))
            @foreach ($child as $rec)
                @php
                    $childData = [];
                @endphp
                @foreach ($rec as $item)

                    @php
                        $childData[] = $item;
                    @endphp
                @endforeach
                ChildrenEdit(@json($childData));
            @endforeach
        @endif
        function toggleButtons() {
            const childCheckbox = document.getElementById('child');
            const plusButton = document.querySelector('#plusButtonIcon');
            const minusButton = document.querySelector('#minusButtonIcon');
            if (childCheckbox && plusButton && minusButton) {
                if (childCheckbox.checked) {
                    plusButton.classList.remove('disabled');
                    minusButton.classList.remove('disabled');
                    plusButton.removeAttribute('disabled');
                    minusButton.removeAttribute('disabled');
                } else {
                    plusButton.classList.add('disabled');
                    minusButton.classList.add('disabled');
                    plusButton.setAttribute('disabled', 'true');
                    minusButton.setAttribute('disabled', 'true');
                    removeAllChildren();
                }
            }
            // Enable or disable selects based on checkboxes
            ['self', 'wife', 'father', 'mother', 'grandfather', 'grandmother', 'fatherinlaw', 'motherinlaw'].forEach(
                member => {
                    const checkbox = document.getElementById(`${member}box`);
                    const select = document.getElementById(member);
                    if (checkbox && select) {
                        select.disabled = !checkbox.checked;
                        if (!checkbox.checked) {
                            select.value = ''; // Clear dropdown when disabling
                        }
                    }
                });
        }

        function addChildren() {
            if (currentCount < maxCount) {
                currentCount++;
                document.getElementById('mainchildContainer').style.display = currentCount > 0 ? 'block' : 'none';

                const childContainer = document.getElementById('childContainer');
                if (!childContainer) {
                    return;
                }

                const childDiv = document.createElement('div');
                childDiv.className = 'childDiv col-lg-12 col-md-12 col-sm-12 mb-2';
                childDiv.style.display = 'flex';

                const selectChild = document.createElement('select');
                selectChild.name = 'children[]';
                selectChild.className = 'btn_one child-select';
                selectChild.id = `child_${currentCount}`;
                selectChild.style.width = '130px!important';
                selectChild.setAttribute('aria-label', 'Child');
                selectChild.innerHTML = `
            <option value="">Select Child</option>
            <option value="Son">Son</option>
            <option value="Daughter">Daughter</option>
        `;

                const selectAge = document.createElement('select');
                selectAge.className = 'btn_one age-select Agebox';
                selectAge.id = `child_${currentCount}Age`;
                selectAge.name = 'childrenAge[]';
                selectAge.innerHTML = '<option value="">Age</option>';
                // for (let months = 4; months <= 24; months++) {
                //     selectAge.innerHTML += `<option value="${months}">${months} months</option>`;
                // }

                // Add options for ages from 2 years (24 months) to 24 years
                for (let years = 1; years <= 24; years++) {
                    selectAge.innerHTML += `<option value="${years}">${years}</option>`;
                }

                childDiv.appendChild(selectChild);
                childDiv.appendChild(selectAge);
                childContainer.appendChild(childDiv);

                updateCount();
                toggleButtons();
            } else {
                errorBox.style.display = "flex";
                errorTitleElement.innerText = 'Maximum Four Children Add';
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
            }
        }


        function ChildrenEdit(childData) {
            if (currentCount < maxCount) {
                currentCount++;
                document.getElementById('mainchildContainer').style.display = currentCount > 0 ? 'block' : 'none';

                const childContainer = document.getElementById('childContainer');
                const childDiv = document.createElement('div');
                childDiv.className = 'childDiv col-lg-12 col-md-12 col-sm-12 mb-2';
                childDiv.style.display = 'flex';

                const selectChild = document.createElement('select');
                selectChild.name = 'children[]';
                selectChild.className = 'btn_one child-select';
                selectChild.id = `child_${currentCount}`;
                selectChild.style.width = '130px!important';
                selectChild.setAttribute('aria-label', 'Child');
                selectChild.innerHTML = `
            <option value="">Select Child</option>
            <option value="Son" ${childData[0] === 'Son' ? 'selected' : ''}>Son</option>
            <option value="Daughter" ${childData[0] === 'Daughter' ? 'selected' : ''}>Daughter</option>
        `;

                const selectAge = document.createElement('select');
                selectAge.className = 'btn_one age-select Agebox';
                selectAge.id = `child_${currentCount}Age`;
                selectAge.name = 'childrenAge[]';
                selectAge.innerHTML = '<option value="">Age</option>';

                // Adding options for ages from 4 months to 24 months
                // for (let months = 4; months <= 24; months++) {
                //     selectAge.innerHTML +=
                //         `<option value="${months}" ${childData[1] == months ? 'selected' : ''}>${months} months</option>`;
                // }

                // Adding options for ages from 2 years (24 months) to 24 years
                for (let years = 1; years <= 24; years++) {
                    selectAge.innerHTML +=
                        `<option value="${years}" ${childData[1] == years ? 'selected' : ''}>${years}</option>`;
                }

                childDiv.appendChild(selectChild);
                childDiv.appendChild(selectAge);
                childContainer.appendChild(childDiv);

                updateCount();
                toggleButtons();
            } else {
                // Handle max children count error
                errorBox.style.display = "flex";
                errorTitleElement.innerText = 'Maximum Four Children Add';
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
            }
        }


        function removeChildren() {
            if (currentCount > 0) {
                const childContainer = document.getElementById('childContainer');
                childContainer.removeChild(childContainer.lastChild);
                currentCount--;
                updateCount();
                toggleButtons();
            }
            document.getElementById('mainchildContainer').style.display = currentCount === 0 ? 'none' : 'block';
        }

        function removeAllChildren() {
            const childContainer = document.getElementById('childContainer');
            while (childContainer.firstChild) {
                childContainer.removeChild(childContainer.firstChild);
            }
            currentCount = 0;
            updateCount();
            document.getElementById('mainchildContainer').style.display = 'none';
        }

        function updateCount() {
            document.getElementById('count').textContent = currentCount;
        }
        // add children end
</script>