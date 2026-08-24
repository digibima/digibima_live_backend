@php
use Illuminate\Support\Facades\Route;
@endphp
<style>
    #sidebar {
        background: #fff;
    }

    #topnavbar {
        margin-bottom: 0px;
    }

    #maincontrow {
        padding: 10px 30px 0;
        padding-top: 2%;
        width: -webkit-fill-available;
    }

    @media (min-width: 992px) and (max-width: 1304px) {
        #logoimg {
            /* Your styles here */
            width: 130px;
        }
    }
</style>
<div class="col-md-3 col-lg-2 px-0 position-fixed h-100 shadow-sm sidebar" id="sidebar">
    <a href="https://digibima.com" target="_blank" class="px-2">
        <img src="{{ config('constant.BASE_URL') }}front/images/logo.png" id="logoimg" class="mb-2 mt-1" width="180" alt="Logo" />
    </a>
    <div class="list-group rounded-0">
        <a href="{{ route('userroot') }}"
            class="list-group-item list-group-item-action  border-0 d-flex align-items-center {{ Route::currentRouteName() == 'userroot' ? 'active' : '' }}">
            <table>
                <tr>
                    <td><span class="fa-solid fa-house me-1"></span></td>
                    <td><span class="ml-2">Dashboard</span></td>
                </tr>
            </table>
        </a>

        <a href="{{ route('userpolicy') }}" class="list-group-item list-group-item-action  border-0 d-flex align-items-center {{ Route::currentRouteName() == 'userpolicy' ? 'active' : '' }}">
            <table>
                <tr>
                    <td><span class="fa-solid fa-handshake me-1"></span></td>
                    <td><span class="ml-2">My Policies</span></td>
                </tr>
            </table>
        </a>
        <a href="{{ route('userclaim') }}"
            class="list-group-item list-group-item-action  border-0 d-flex align-items-center {{ Route::currentRouteName() == 'userclaim' ? 'active' : '' }}">
            <table>
                <tr>
                    <td><span class="fa-solid fa-hand-holding-heart me-1"></span></td>
                    <td><span class="ml-2">Claim</span></td>
                </tr>
            </table>
        </a>
        <a href="{{ route('usersetting') }}"
            class="list-group-item list-group-item-action border-0 d-flex align-items-center {{ Route::currentRouteName() == 'usersetting' ? 'active' : '' }}">
            <table>
                <tr>
                    <td><span class="fa-regular fa-user me-1"></span></td>
                    <td><span class="ml-2">My Profile</span></td>
                </tr>
            </table>
        </a>

    </div>
</div>