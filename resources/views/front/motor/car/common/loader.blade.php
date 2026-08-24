<style>
    #loader {

        /* Ensure loader is shown */
        justify-content: center;
        align-items: center;

        overflow: hidden;
        position: fixed;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        top: 0;
        left: 0;
        z-index: 9999;
        /* Ensure loader is on top */
    }



    .loader {
        position: relative;
        width: 300px;
        height: 100px;
    }

    /* Car Container (Includes Car, Wheels, and Smoke) */
    .car-container {
        position: absolute;
        left: -80px;
        bottom: 1px;
        animation: drive 2s ease-in-out infinite, bounce 0.4s ease-in-out infinite alternate;
    }

    .car-container img {
        position: relative;
        width: 135px;
        height: 50px;
    }

    .car {
        font-size: 60px;
        color: #1C5FA8;
        position: relative;
    }

    /* Moving Car Animation */
    @keyframes drive {
        0% {
            left: -80px;
        }

        50% {
            transform: rotate(3deg);
        }

        100% {
            left: 280px;
        }
    }

    /* Bouncing Effect */
    @keyframes bounce {
        0% {
            transform: translateY(0);
        }

        100% {
            transform: translateY(-5px);
        }
    }

    /* Road */
    .road {
        width: 320px;
        height: 6px;
        background: #444;
        position: absolute;
        bottom: 0px;
        border-radius: 3px;
        overflow: hidden;
    }

    /* Moving Road Dashes */
    .road::before {
        content: "";
        position: absolute;
        width: 100%;
        height: 3px;
        background: repeating-linear-gradient(90deg, white, white 20px, transparent 20px, transparent 40px);
        top: 1px;
        left: 0;
        animation: roadMove 0.8s linear infinite;
    }

    @keyframes roadMove {
        from {
            transform: translateX(0);
        }

        to {
            transform: translateX(-40px);
        }
    }

    /* Exhaust Smoke */
    .smoke-container {
        position: absolute;
        left: -20px;
        bottom: 15px;
    }

    .smoke {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 12px;
        height: 12px;
        background-color: rgba(000, 000, 000, 0.9);
        border-radius: 50%;
        animation: smoke 1.5s linear infinite;
    }

    .smoke:nth-child(2) {
        animation-delay: 0.3s;
        left: -10px;
    }

    .smoke:nth-child(3) {
        animation-delay: 0.6s;
        left: -20px;
    }

    @keyframes smoke {
        0% {
            opacity: 0.7;
            transform: translateY(0px) scale(1);
        }

        100% {
            opacity: 0;
            transform: translateY(-20px) scale(1.5);
        }
    }

    /* Wheels (Attached to the Car) */
    .wheels {
        position: absolute;
        bottom: -1px;
        left: 17px;
        display: flex;
        gap: 55px;
    }

    .wheel {
        width: 25px;
        height: 25px;
        background: black;
        border-radius: 50%;
        border: 3px solid white;
        animation: rotate 0.5s linear infinite;
    }

    /* Rotating Wheels Animation */
    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* Car Shadow */
    .shadow {
        position: absolute;
        bottom: 5px;
        left: 50px;
        width: 80px;
        height: 10px;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 50%;
        filter: blur(5px);
        animation: shadowScale 3s ease-in-out infinite;
    }

    @keyframes shadowScale {
        0% {
            transform: scaleX(1);
        }

        50% {
            transform: scaleX(1.2);
        }

        100% {
            transform: scaleX(1);
        }
    }
</style>
<div id="loader">
    <div class="loader">
        <div class="car-container">
            <div class="smoke-container">
                <div class="smoke"></div>
                <div class="smoke"></div>
                <div class="smoke"></div>
            </div>
            <img src="{{ config('constant.BASE_URL') }}front/images/loadercar.png"
                alt="Loading...">
            <div class="wheels">
                <div class="wheel"></div>
                <div class="wheel"></div>
            </div>
        </div>
        <div class="road"></div>
        <div class="shadow"></div>
    </div>
</div>