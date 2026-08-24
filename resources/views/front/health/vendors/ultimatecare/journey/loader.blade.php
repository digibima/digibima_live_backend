<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
    
    

 #loder{
  width: 100%;
  height: 50vh;
  background-color: #fff;
  display: flex;
  justify-content: center;
  align-items: center;
  font-family: 'Poppins', sans-serif;
}

.loader-container {
  position: relative;
  width: 18rem;
  height: 18rem;
  background-color: #ffffff;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.dot {
  position: absolute;
  width: 20px;
  height: 20px;
  border-radius: 50%;
}

.dot-1 {
  background-color: #3db5e9;
  z-index: 4;
  animation: moveDot1 2s infinite ease;
}

.dot-2 {
  background-color: #1de1c0;
  z-index: 3;
  animation: moveDot2 2s infinite ease;
}

.dot-3 {
  background-color: #005cff;
  z-index: 2;
  animation: moveDot3 2s infinite ease;
}

.dot-4 {
  background-color: #03007f;
  z-index: 1;
  animation: moveDot4 2s infinite ease;
}

@keyframes moveDot1 {
  from {
    transform: rotate(0deg);
    /* transform-origin: top left */
    transform-origin: -20px -20px;
    transform-style: preserve-3D;
  }

  to {
    transform: rotate(-360deg);
    /* transform-origin: top left */
    transform-origin: -20px -20px;
    transform-style: preserve-3D;
  }
}

@keyframes moveDot2 {
  from {
    transform: rotate(0deg);
    /* transform-origin: top right */
    transform-origin: 40px -20px;
    transform-style: preserve-3D;
  }

  to {
    transform: rotate(-360deg);
    /* transform-origin: top right */
    transform-origin: 40px -20px;
    transform-style: preserve-3D;
  }
}

@keyframes moveDot3 {
  from {
    transform: rotate(0deg);
    /* transform-origin: bottom left */
    transform-origin: -20px 40px;
    transform-style: preserve-3D;
  }

  to {
    transform: rotate(-360deg);
    /* transform-origin: bottom left */
    transform-origin: -20px 40px;
    transform-style: preserve-3D;
  }
}

@keyframes moveDot4 {
  from {
    transform: rotate(0deg);
    /* transform-origin: bottom right */
    transform-origin: 40px 40px;
    transform-style: preserve-3D;
  }

  to {
    transform: rotate(-360deg);
    /* transform-origin: bottom right */
    transform-origin: 40px 40px;
    transform-style: preserve-3D;
  }
}




    </style>
    
</head>
<body>
  @include('front.partial.header')
    <div id="loder">
        <div class="loader-container">
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
            <div class="dot dot-3"></div>
            <div class="dot dot-4"></div>
          </div>
        
    </div>
  
</body>
</html>