<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Top Plans</title>
    @include('front.partial.csslink')
    <style>
        select option {
            cursor: pointer !important;
        }

        :root {
            --primary-color: #1C5FA8;
            --error-bg: #EF665B;
            --error-icon-color: #fff;
            --scrollbar-thumb: #888;
            --scrollbar-thumb-hover: #555;
        }

        .btn_one {
            align-items: center;
            cursor: pointer;
            border: 1px solid #1C5FA8;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            color: #000;
            letter-spacing: 1px;
            transition: all 0.3s ease-in-out;
            justify-content: space-between;
        }

        .btn_one select[name="children"] {
            width: 160px !important;
        }

        .btn_one label {
            cursor: pointer;
            margin-left: 8px;
            font-size: 14px;
        }

        select {
            background-color: #F0FAFC;
            height: 50px;
            max-height: 50px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .disabled {
            pointer-events: none;
            opacity: 0.6;
        }

        .col-lg-6 .Agebox {
            width: 110px !important;
            margin-left: 10px;
        }

        .Agebox {
            height: 50px;
            max-height: 50px;
            padding: 5px;
        }

        .Agebox option {
            height: 50px;
            max-height: 50px;
            overflow-y: scroll;
        }

        select[multiple],
        select[size] {
            height: 50px;
            max-height: 50px;
        }

        select::-webkit-scrollbar {
            width: 8px;
        }

        select::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        select::-webkit-scrollbar-thumb {
            background: #888;
        }

        select::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        select option {
            height: 5px;
        }




        .MainErrorBox {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            position: fixed;
            top: 10px;
            right: 10px;
            width: auto;
            padding: 12px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: start;
            background: #EF665B;
            border-radius: 8px;
            box-shadow: 0px 0px 5px -3px #111;
            z-index: 1000;
        }

        .error__icon {
            width: 20px;
            height: 20px;
            transform: translateY(-2px);
            margin-right: 8px;
            color: #fff;
        }


        .error__title {
            font-weight: 500;
            font-size: 14px;
            color: #fff;
        }

        .error__close {
            width: 20px;
            height: 20px;
            cursor: pointer;
            margin-left: auto;
            color: #fff;
        }

        .fa-circle-exclamation {
            color: #fff;
        }

        @media (min-width: 992px) and (max-width: 1199.98px) {
            #slide3 {
                padding: 2rem;
            }

            #slide3 h5 {
                padding: 0px 140px;
            }

            .slide2-img {
                width: 450px;
                max-width: 450px;
                height: auto;
            }

        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            main {
                padding: 2rem 2rem;
            }

            #slide3 h5 {
                padding: 0px 90px !important;
                font-size: 26px !important;
            }

            #slide3 {
                padding: 2rem;
            }

            .slide2-img {
                margin-bottom: 20px;
            }
        }

        @media (min-width: 576px) and (max-width: 767.98px) {
            main {
                padding: 2rem 2rem;
            }

            #slide3 h5 {
                padding: 0px 0px !important;
                font-size: 25px !important;
            }

            #slide3 {
                padding: 1.5rem;
            }

            .slide2-img {
                text-align: center;
                margin-bottom: 20px;
            }
        }

        @media (min-width: 0px) and (max-width: 575.98px) {
            #slide3 h5 {
                padding: 0px 0px !important;
                font-size: 22px !important;
            }

            #slide3 {
                padding: 1rem;
            }

            .continue {
                width: 150px;
            }

            .slide2-img {
                width: 350px;
                max-width: 350px;
                height: auto;
            }

            main {
                padding: 1rem;
            }
        }

        @media (max-width: 375px) {
            .continue {
                width: 110px;
            }

            #slide2 {
                padding: 1rem;
            }

            #slide3 h5 {
                padding: 0px 0px !important;
                font-size: 22px !important;
            }

            #slide3 {
                padding: 1rem;
            }

            .d-flex {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn_one {
                margin-left: 3px;
            }

            .slide2-img {
                width: 250px;
                max-width: 250px;
                height: auto;
            }

            .d-flex {
                flex-direction: row;
                align-items: normal;
            }
        }
    </style>
</head>

<body>
    @include('front.partial.header')
    <div class="MainErrorBox" style="float: right;display:none;"><span class="error__icon"><i
                class="fa-solid fa-circle-exclamation"></i></span>
        <p class="error__title mb-0 " style="margin-right:10px;">Self cannot be combined with Father or Mother.</p><span
            class="error__close "><i class="fa-solid fa-xmark"></i></span>
    </div>
    <main>
        <section id="slide3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h5>Select members you want to insure</h5>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="slide2-img">
                        <img src="{{ config('constant.BASE_URL') }}front/images/DIGIBIMA-1.png" alt="Slide Image">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <form action="{{ route('illnesses') }}" method="post">
                        @csrf
                        <div class="row">
                            @foreach (['self', $gender == 'male' ? 'wife' : 'husband'] as $member)
                            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                <div class="d-flex">
                                    <div class="btn_one ml-2 parent-div">
                                        <input type="checkbox" id="{{ $member }}box"
                                            @if (!empty($aInsureData) && $aInsureData->contains('name', $member)) checked @endif
                                        value="{{ $member }}" onchange="toggleButtons()">
                                        <label for="{{ $member }}box">{{ ucfirst($member) }}</label>
                                    </div>
                                    <select name="{{ $member }}" id="{{ $member }}"
                                        class="btn_one Agebox">
                                        <option value="">Age</option>
                                        @for ($i = 18; $i <= 99; $i++)
                                            <option value="{{ $i }}"
                                            @if (
                                            !empty($aInsureData) &&
                                            $aInsureData->contains('name', $member) &&
                                            $aInsureData->where('name', $member)->first()->age == $i) selected @endif>
                                            {{ $i }}</option>
                                            @endfor
                                    </select>
                                </div>
                            </div>
                            @endforeach
                            <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                <div class="btn_one parent-div" style="width:100%;">
                                    <input type="checkbox" id="child" name="children" value="children"
                                        @if (!empty($child)) @checked(count($child)> 0) @endif
                                    onchange="toggleButtons()">
                                    <label for="child" class="mr-2" style="margin-left:8px;">Children</label>
                                    <div style="display: inline;float: right;">
                                        <span class="minus-box" id="minusButton"><i class="fas fa-minus disabled"
                                                onclick="removeChildren()" disabled></i></span>
                                        <label id="count">0</label><span class="plus-box" id="plusButton"
                                            style="margin-left: 8px;"><i class="fas fa-plus disabled"
                                                onclick="addChildren()" disabled></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 mb-2"></div>
                            <div class="col-lg-12 col-md-12 col-sm-12" id="mainchildContainer" style="display:none">
                                <div class="child-container row" id="childContainer"></div>
                            </div>
                            @foreach (['father', 'mother', 'grandfather', 'grandmother', 'father-in-law', 'mother-in-law'] as $member)
                            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
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
                        <div class="d-none">
                            <input type="submit" id="continue">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 mt-2 text-center">
                <a href="{{ route('health.root') }}"><button class="continue">Back</button></a>
                <button id="continue-button" class="continue mt-2" onclick="validateAndSubmit()">Continue</button>
            </div>
        </section>
    </main>
    @include('front.partial.chatwidget')
    @include('front.partial.footer')
    @include('front.partial.jslink')
    <script>
        const errorBox = document.querySelector('.MainErrorBox');
        const errorTitleElement = errorBox?.querySelector('.error__title');

   
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
        @if(!empty($child))
        @foreach($child as $rec)
        @php
        $childData = [];
        @endphp
        @foreach($rec as $item)

        @php
        $childData[] = $item;
        @endphp
        @endforeach
        ChildrenEdit(@json($childData));
        @endforeach
        @endif

        function toggleButtons() {
            const childCheckbox = document.getElementById('child');
            const plusButton = document.querySelector('.plus-box i');
            const minusButton = document.querySelector('.minus-box i');


            // Enable or disable plus/minus buttons based on child checkbox
            if (childCheckbox && plusButton && minusButton) {
                if (childCheckbox.checked) {
                    plusButton.classList.remove('disabled');
                    minusButton.classList.remove('disabled');
                } else {
                    plusButton.classList.add('disabled');
                    minusButton.classList.add('disabled');
                    removeAllChildren();
                }
            }


            // Enable or disable selects based on checkboxes
            ['self', 'wife','husband', 'father', 'mother', 'grandfather', 'grandmother', 'fatherinlaw', 'motherinlaw'].forEach(
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
                const childDiv = document.createElement('div');
                childDiv.className = 'childDiv col-lg-6 col-md-6 col-sm-12 mb-2';
                // childDiv.id = `child_${currentCount}`;
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
                // Initialize the dropdown with a default option
                // Initialize the dropdown with a default option
                selectAge.innerHTML = '<option value="">Age</option>';

                // Add options for ages from 4 months (91 days) to 24 months (2 years)
                // for (let months = 4; months <= 24; months++) {
                //     selectAge.innerHTML += `<option value="${months}">${months} months</option>`;
                // }

                // Add options for ages from 2 years (24 months) to 24 years
                for (let years = 1; years <= 24; years++) {
                    selectAge.innerHTML += `<option value="${years}">${years} </option>`;
                }


                childDiv.appendChild(selectChild);
                childDiv.appendChild(selectAge);
                childContainer.appendChild(childDiv);

                updateCount();
                toggleButtons();
            } else {
                errorBox.style.display = "flex";
                errorTitleElement.innerText = '';
                errorTitleElement.innerText = `Maximum Four Children Add`;
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                // alert('Maximum Four Children Add');
            }
        }

        function ChildrenEdit(childData) {
            if (currentCount < maxCount) {
                currentCount++;
                document.getElementById('mainchildContainer').style.display = currentCount > 0 ? 'block' : 'none';

                const childContainer = document.getElementById('childContainer');
                const childDiv = document.createElement('div');
                childDiv.className = 'childDiv col-lg-6 col-md-6 col-sm-12 mb-2';
                // childDiv.id = `child_${currentCount}`;
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
                errorBox.style.display = "flex";
                errorTitleElement.innerText = '';
                errorTitleElement.innerText = `Maximum Four Children Add`;
                setTimeout(() => {
                    errorBox.style.display = 'none';
                }, 3000);
                // alert('Maximum Four Children Add');
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

        function validateAndSubmit() {
            let isValid = true;
            let adultCount = 0;
            let childCount = 0;
            let selfSelected = false;
            let selfAge = null;
            let spouseSelected = false;
            let spouseAge = null;
            let fatherSelected = false;
            let motherSelected = false;
            let fatherInLawSelected = false;
            let motherInLawSelected = false;
            let childDetails = [];

            const members = [
                { checkboxId: 'selfbox', selectId: 'self', relationship: 'Self' },
                { checkboxId: 'wifebox', selectId: 'wife', relationship: 'Wife' },
                { checkboxId: 'husbandbox', selectId: 'husband', relationship: 'Husband' },
                { checkboxId: 'fatherbox', selectId: 'father', relationship: 'Father' },
                { checkboxId: 'motherbox', selectId: 'mother', relationship: 'Mother' },
                { checkboxId: 'grandfatherbox', selectId: 'grandfather', relationship: 'Grandfather' },
                { checkboxId: 'grandmotherbox', selectId: 'grandmother', relationship: 'Grandmother' },
                { checkboxId: 'fatherinlawbox', selectId: 'fatherinlaw', relationship: 'Father-in-law' },
                { checkboxId: 'motherinlawbox', selectId: 'motherinlaw', relationship: 'Mother-in-law' }
            ];

           const anySelected = members.some(member => {
                const checkbox = document.getElementById(member.checkboxId);
                return checkbox ? checkbox.checked : false;  // Only check `.checked` if the element exists
            });

            if (!anySelected) {
                // Show error and stop the validation
                errorBox.style.display = "flex";
                errorTitleElement.innerText = "Please select at least one family member.";
                setTimeout(() => { errorBox.style.display = 'none'; }, 3000);
                return false;
            }

            members.forEach(member => {
                const checkbox = document.getElementById(member.checkboxId);
                const select = document.getElementById(member.selectId);

                if (checkbox && select) {
                    if (checkbox.checked && select.value === '') {
                        errorBox.style.display = "flex";
                        errorTitleElement.innerText = `Please select an age for ${member.relationship}.`;
                        setTimeout(() => { errorBox.style.display = 'none'; }, 3000);
                        isValid = false;
                    }
                    if (checkbox.checked) {
                        const age = parseInt(select.value, 10);
                        if (age >= 18) adultCount++;

                        switch (member.relationship) {
                            case 'Self':
                                selfSelected = true;
                                selfAge = age;
                                break;
                            case 'Wife':
                            case 'Husband':
                                spouseSelected = true;
                                spouseAge = age;
                                break;
                            case 'Father':
                                fatherSelected = true;
                                fatherAge = age;
                                break;
                            case 'Mother':
                                motherSelected = true;
                                motherAge = age;
                                break;
                            case 'Father-in-law':
                                fatherInLawSelected = true;
                                fatherInLawAge = age;
                                break;
                            case 'Mother-in-law':
                                motherInLawSelected = true;
                                motherInLawAge = age;
                                break;
                        }
                    }
                }
            });

            const childSelects = document.querySelectorAll('.childDiv .age-select');
            childSelects.forEach(childSelect => {
                const selectedAge = parseInt(childSelect.value, 10);
                if (!isNaN(selectedAge)) {
                    childDetails.push({ age: selectedAge });
                }
            });

            // Dynamically select spouse's age
            let spouseAge1 = document.getElementById('wife') ? parseInt(document.getElementById('wife').value) : 
                            document.getElementById('husband') ? parseInt(document.getElementById('husband').value) : null;

            let fatherAge1 = parseInt(document.getElementById('father').value);
            let motherAge1 = parseInt(document.getElementById('mother').value);
            let fatherinlawAge1 = parseInt(document.getElementById('fatherinlaw').value);
            let motherinlawAge1 = parseInt(document.getElementById('motherinlaw').value);
            let grandfatherAge1 = parseInt(document.getElementById('grandfather').value);
            let grandmotherAge1 = parseInt(document.getElementById('grandmother').value);

            // Check age gaps
            if (fatherAge1 - selfAge < 18 || (spouseSelected && fatherAge1 - spouseAge1 < 18)) {
                showError('The gap between Self/Spouse and Father should be at least 18 years.');
                return false;
            }
            if (motherAge1 - selfAge < 18 || (spouseSelected && motherAge1 - spouseAge1 < 18)) {
                showError('The gap between Self/Spouse and Mother should be at least 18 years.');
                return false;
            }
            if (fatherinlawAge1 - selfAge < 18 || (spouseSelected && fatherinlawAge1 - spouseAge1 < 18)) {
                showError('The gap between Self/Spouse and Father-In-Law should be at least 18 years.');
                return false;
            }
            if (motherinlawAge1 - selfAge < 18 || (spouseSelected && motherinlawAge1 - spouseAge1 < 18)) {
                showError('The gap between Self/Spouse and Mother-In-Law should be at least 18 years.');
                return false;
            }
            if (grandfatherAge1 - selfAge < 36 || (spouseSelected && grandfatherAge1 - spouseAge1 < 18)) {
                showError('The gap between Self/Spouse and Grandfather should be at least 36 years.');
                return false;
            }
            if (grandmotherAge1 - selfAge < 36 || (spouseSelected && grandmotherAge1 - spouseAge1 < 18)) {
                showError('The gap between Self/Spouse and Grandmother should be at least 36 years.');
                return false;
            }
            if (grandfatherAge1 - fatherAge1 < 18) {
                showError('The gap between Father and Grandfather should be at least 18 years.');
                return false;
            }
            if (grandmotherAge1 - fatherAge1 < 18) {
                showError('The gap between Father and Grandmother should be at least 18 years.');
                return false;
            }
            if (grandfatherAge1 - motherAge1 < 18) {
                showError('The gap between Mother and Grandfather should be at least 18 years.');
                return false;
            }
            if (grandmotherAge1 - motherAge1 < 18) {
                showError('The gap between Mother and Grandmother should be at least 18 years.');
                return false;
            }

            if (isValid) {
                document.getElementById('continue').click();
            }
        }

        function showError(message) {
            errorBox.style.display = "flex";
            errorTitleElement.innerText = message;
            setTimeout(() => { errorBox.style.display = 'none'; }, 3000);
        }

    </script>
</body>

</html>