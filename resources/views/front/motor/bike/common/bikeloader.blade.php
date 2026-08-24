<style>
      

        #loader {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: rgba(255, 255, 255, 0.8);
        }

        .loader {
            position: relative;
            width: 200px;
            height: 100px;
        }

        .bike {
            position: absolute;
            width: 120px;
            height: 120px;
            background: url('{{ config('constant.BASE_URL') }}front/images/loaderbike.png') no-repeat center;
            background-size: contain;
            animation: moveBike 1s linear infinite;
        }

        .wheel {
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid black;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .wheel::after {
            content: "";
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #006297;
        }

        .wheel1 {
            left: 4px;
            bottom: 8px;
            animation: rotate 0.5s linear infinite;
        }

        .wheel2 {
            right: 4px;
            bottom: 8px;
            animation: rotate 0.5s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes moveBike {
            0% {
                transform: translateX(-70px);
            }

            100% {
                transform: translateX(70px);
            }
        }
    </style>
    <div id="loader">
    <div class="loader">
        <div class="bike">
            <div class="wheel wheel1"></div>
            <div class="wheel wheel2"></div>
        </div>
    </div>
    </div>