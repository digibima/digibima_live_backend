<style>
    /* #userName {
      
        display: flex;
        justify-content: center;
        align-items: center;

    }

    #userName span {
        background: beige;
        padding: 4px 13px;
        margin-bottom: 10px;
        border-radius: 10px;
        font-weight: 400;
        color: #1C5FA8;
        font-family: 'Poppins';
    } */
    .btn-check:focus+.btn,
    .btn:focus {
        outline: 0;
        box-shadow: 0 0 0 !important;
        background: #E5E6E8 !important;
    }



    /* Header */
    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 20px;
        background-color: white;
        border-bottom: 1px solid #e0e0e0;
        flex-wrap: wrap;
        position: relative;
    }

    /* Logo */
    .logo img {
        height: 35px;
    }

    /* User Info */
    .user-container {
        position: relative;
    }

    .user-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 25px;
        cursor: pointer;
        background: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: background 0.2s;
    }

    .user-toggle:hover {
        background: #f0f0f0;
    }

    .user-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #b2bec3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: bold;
        color: white;
    }

    .user-icon img {
        width: 100%;
        height: 100%;
    }

    .user-name {
        font-size: 14px;
        color: #333;
        white-space: nowrap;
    }

    .arrow {
        font-size: 14px;
        color: #555;
        transition: transform 0.3s;
    }

    /* Dropdown Menu */
    .dropdown {
        display: none;
        position: absolute;
        top: 45px;
        right: 0;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 6px;
        overflow: hidden;
        min-width: 150px;
        z-index: 100;
    }

    .dropdown.active {
        display: block;
    }

    .dropdown a {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: black;
        font-size: 14px;
        transition: background 0.2s;
        font-weight: 400;
    }

    .dropdown a:hover {
        background: #f0f0f0;
    }



    /* Responsive Design */
    @media (max-width: 768px) {

        /* Top Bar */
        .top-bar {
            flex-direction: row;
            padding: 10px;
        }

        /* Header */
        .header {
            flex-direction: row;
            padding: 10px;
        }

        .logo img {
            height: 30px;
        }

        /* User Info */
        .user-container {
            margin-top: 0px;


        }

        .user-toggle {
            padding: 6px 8px;
            gap: 5px;
        }

        .user-name {
            font-size: 12px;
        }

        /* Dropdown */
        .dropdown {
            top: 40px;
            right: auto;
            left: 50%;
            transform: translateX(-50%);
            min-width: 90%;
            text-align: center;
        }

        .dropdown a {
            padding: 12px;
        }
    }

    @media (max-width: 480px) {

        /* Smaller User Icon */
        .user-icon {
            width: 28px;
            height: 28px;
        }

        .user-name {
            font-size: 12px;
        }

        /* Dropdown Menu */
        .dropdown {
            min-width: 95%;
        }

        .dropdown a {
            font-size: 13px;
        }
    }

    #username {
        padding: 4px 10px;
        background-color: #34A8D8;
        color: #fff;
        border-radius: 50px;
        margin-left: 10px;
    }
</style>
<!-- <nav id="topnavbar" class="px-4 shadow-sm d-flex align-items-center">
   
    <button class="btn py-0 d-lg-none" id="open-sidebar">
        <span class="bi bi-list text-primary h3"></span>
    </button>


    <div class="d-flex flex-grow-1 justify-content-end align-items-center">
    
        <span id="username">{{Auth::user()->name;}}</span>
        <div class="dropdown d-none d-lg-block user ">
            <button class="btn d-flex align-items-center" id="logout-dropdown" data-bs-toggle="dropdown"
                aria-expanded="false" style="padding: 10px;">
                <span class="fa-regular fa-user"></span>
            </button>

            <div class="dropdown-menu dropdown-menu-end border-0 shadow-sm"
                aria-labelledby="logout-dropdown">
                <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
            </div>
        </div>

        <div class="dropdown d-lg-none ms-2 mt-2 user">
            <button class="btn d-flex align-items-center" id="logout-dropdown-mobile" data-bs-toggle="dropdown"
                aria-expanded="false" style="padding: 10px;">
                <span class="fa-regular fa-user"></span>
            </button>

            <div class="dropdown-menu dropdown-menu-end border-0 shadow-sm"
                aria-labelledby="logout-dropdown-mobile">
                <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
            </div>
        </div>
    </div>
</nav> -->
<div class="header">
    <button class="btn py-0 d-lg-none" id="open-sidebar">
        <span class="bi bi-list text-primary h3"></span>
    </button>
    <div class="logo">

    </div>
    <div class="user-container">
        <div class="user-toggle" onclick="toggleDropdown()">
            <!-- <div class="user-icon"><i class="bi bi-person-circle"></i></div> -->
            <div class="user-icon"><img src="{{ config('constant.BASE_URL') }}front/images/user.png" alt=""></div>
            @auth
            <span class="user-name">Hi, {{ Auth::user()->name ?? Auth::user()->mobile }}!</span>
            @endauth

            <span class="arrow">&#9662;</span>
        </div>
        <div id="dropdownMenu" class="dropdown">
            @auth
            <a class="dropdown-item" href="{{ route('userroot') }}">Profile</a>
            <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
            @endauth
            @guest
            <a class="dropdown-item" href="#" onclick="openModal();">Login</a>
            @endguest
        </div>
    </div>
</div>
<script>
    function toggleDropdown() {
        let dropdown = document.getElementById('dropdownMenu');
        let arrow = document.querySelector('.arrow');

        dropdown.classList.toggle('active');
        arrow.style.transform = dropdown.classList.contains('active') ? "rotate(180deg)" : "rotate(0deg)";
    }

    // Close dropdown when clicking outside
    document.addEventListener("click", function(event) {
        let userToggle = document.querySelector(".user-toggle");
        let dropdown = document.getElementById("dropdownMenu");

        if (!userToggle.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove("active");
            document.querySelector('.arrow').style.transform = "rotate(0deg)";
        }
    });
</script>