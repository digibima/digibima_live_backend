<style>
    .info-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 10px;
    }

    .info-card h2 {
        margin-bottom: 10px;
        color: #333;
        font-size: 18px;
        font-weight: bold;
    }

    .card_info {
        margin: 10px 0;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        /* border-bottom: 1px solid #10508F; */
    }

    .card_info span {
        font-size: 14px;
        font-weight: 500;
        color: #10508F;
    }

    .edit-icon {
        cursor: pointer;
        color: #007bff;
        font-size: 16px;
    }

    .highlight {
        color: #000 !important;
        display: inline-block;
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    hr {
        color: 10508F !important;
        border: 1px solid #10508F !important;
        background: #000 !important;
    }



    /* card style 2 type start ------------------------------------ */

    .car-card {
        max-width: 400px;
        margin: auto;
        border-radius: 16px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.125);
        background: #fff;
        padding: 24px;
    }

    .car-card h5 {
        font-weight: 600;
        margin-bottom: 20px;
    }

    .car-info {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }

    .car-info:last-child {
        border-bottom: none;
    }


    .labelspan {
        font-weight: 500;
        color: #666;
        font-size: 15px;
    }

    .value {
        font-weight: 600;
        color: #222;
        font-size: 15px;
    }

    .status-expired {
        color: #dc2626;
        font-weight: 700;
    }

    .btn-pay {
        width: 100%;
        margin-top: 20px;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 0.375rem;
        /* background-color: #0d6efd; */
           background: linear-gradient(135deg, #4e54c8, #8f94fb) !important;
        color: #fff;
        border: none;
        padding: 0.65rem;
    }

    /* card style 2 type end ------------------------------------ */
</style>
@php
    // dd($vehicledetails);
    $expiry = $vehicledetails['policy_expiry'] ?? '';
    $isExpired = strtolower($expiry) === 'expired';
@endphp
{{-- <div class="info-card">
    <h2>Private Car</h2>
    <div class="card_info">
        <span>RTO City:</span>
        <span class="highlight">{{ $vehicledetails['city'] }}</span>
    </div>
    <!-- <hr> -->
    <div class="card_info">
        <span>Manufacturer:</span>
        <span class="highlight">{{ $vehicledetails['brand'] }} </span>
    </div>
    <!-- <hr> -->
    <div class="card_info">
        <span>Model:</span>
        <span class="highlight">{{ $vehicledetails['model'] }} </span>
    </div>
    <!-- <hr> -->
    <div class="card_info">
        <span>Variant:</span>
        <span class="highlight">{{ $vehicledetails['variant'] }}</span>
    </div>
    <!-- <hr> -->
    <div class="card_info" style="{{ empty($vehicledetails['regyear']) ? 'display: none;' : '' }}">
        <span>Registration Year:</span>
        <span class="highlight">{{ $vehicledetails['regyear'] }} </span>
    </div>
    <!-- <hr> -->

    <div class="card_info" style="{{ empty($vehicledetails['policy_expiry']) ? 'display: none;' : '' }}">
        <span>Policy Expiry:</span>
        <span class="highlight">{{ $vehicledetails['policy_expiry'] }} </span>
    </div>
    <!-- <hr> -->

    <div class="col-md-12 col-lg-12 mt-4 gotopaynow" style="display: none;">
        <a href="{{ route('shriram.payment') }}" ><button class="getstarted mb-2 w-100">Pay Now</button></a>
    </div>
</div> --}}

<div class="car-card">
    <h5 style="text-align: left">Private Car</h5>

    <div class="car-info">
        <span class="label">RTO City:</span>
        <span class="value">{{ $vehicledetails['city'] ?? "" }}</span>
    </div>

    <div class="car-info">
        <span class="label">Manufacturer:</span>
        <span class="value">{{ $vehicledetails['brand'] ?? "" }}</span>
    </div>

    <div class="car-info">
        <span class="label">Model:</span>
        <span class="value">{{ $vehicledetails['model']  ?? ""}}</span>
    </div>

    <div class="car-info">
        <span class="label">Variant:</span>
        <span class="value">{{ $vehicledetails['variant'] ?? "" }}</span>
    </div>

    <div class="car-info" style="{{ empty($vehicledetails['regyear']) ? 'display: none;' : '' }}">
        <span class="label">Registration Year:</span>
        <span class="value">{{ $vehicledetails['regyear'] ?? "" }}</span>
    </div>

   <div class="car-info" style="{{ empty($expiry) ? 'display: none;' : '' }}">
        <span class="label">Policy Expiry:</span>
        <span class="value {{ $isExpired ? 'status-expired' : '' }}">{{ $expiry ?? "" }}</span>
    </div>
    <div class="col-md-12 col-lg-12 gotopaynow" style="display: none;">
        {{-- <button class="btn1 btn-primary1 btn-pay">Pay Now</button> --}}
        <a href="{{ route('shriram.payment') }}"><button class="btn-pay">Pay Now</button></a>
    </div>

</div>
<script></script>
