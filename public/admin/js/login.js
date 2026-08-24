
const inputs = document.querySelectorAll(".otp-input input");
const timerDisplay = document.getElementById("timer");
const resendButton = document.getElementById("resend"); 
let timeLeft = 5; // 5 seconds 
let timerId;

function startTimer() {
  timerId = setInterval(() => {
    if (timeLeft <= 0) {
      clearInterval(timerId);
      timerDisplay.style.display = 'none';

      resendButton.style.display = 'block';
      inputs.forEach((input) => (input.disabled = true));  
    } else {
      const seconds = timeLeft % 60;
      timerDisplay.textContent = `Resend in: ${seconds
        .toString()
        .padStart(2, "0")}`;
      timeLeft--;
    }
  }, 1000);
}

function resendOTP() {
  alert("New OTP sent!");
  timeLeft = 180;
  inputs.forEach((input) => {
    input.value = "";
    input.disabled = false;
  });
  resendButton.disabled = true;
  inputs[0].focus();
  clearInterval(timerId);
  startTimer();
}


function verifyOTP() {
  const otp = Array.from(inputs)
    .map((input) => input.value)
    .join("");
  if (otp.length === 6) {
    if (timeLeft > 0) {
      alert(`Verifying OTP: ${otp}`);
    } else {
      alert("OTP has expired. Please request a new one.");
    }
  } else {
    alert("Please enter a 6-digit OTP");
  }
}

// Get OTP function
function getOTP() {
  const mobileNumber = document.getElementById("mobileNumber").value;
  if (mobileNumber.length === 10) {
    alert("OTP sent to " + mobileNumber);
    document.querySelector(".mobilesection").style.display = "none";
    document.querySelector(".otpsection").style.display = "block";
    startTimer(); 
  } else {
    alert("Please enter a valid 10-digit mobile number");
  }
}
// Move focus to next input after entering a digit
function moveFocus(event, nextInputIndex) {
  const currentInput = event.target;
  const currentValue = currentInput.value;

  if (!/^[0-9]$/.test(currentValue)) {
    currentInput.value = '';
    return;
  }

  const nextInput = document.querySelectorAll('.otp-input input')[nextInputIndex];
  if (nextInput && currentValue) {
    nextInput.focus();
  }
  if (event.target.value.length > 1) {
    event.target.value = event.target.value.slice(0, 1); 
  }
  function moveBackspace(event, prevInputIndex) {
    if (event.key === "Backspace") {
      if (event.target.value === "") {
        if (prevInputIndex >= 0) {
          document.querySelectorAll(".otp-input input")[prevInputIndex].focus();
        }
      }
    }
  }
  if (event.target.value.length === 1 && nextInputIndex < 6) {
    document.querySelectorAll(".otp-input input")[nextInputIndex].focus();
  }
}



inputs.forEach((input, index) => {
  input.addEventListener('input', (e) => {
    if (e.target.value.length > 1) {
      e.target.value = e.target.value.slice(0, 1);
    }
    if (e.target.value.length === 1) {
      if (index < inputs.length - 1) {
        inputs[index + 1].focus();
      }
    }
  });

  input.addEventListener('keydown', (e) => {
    if (e.key === 'Backspace' && !e.target.value) {
      if (index > 0) {
        inputs[index - 1].focus();
      }
    }
    if (e.key === 'e') {
      e.preventDefault();
    }
  });
});

function validatePhoneNumber(event) {
  const input = event.target;
  input.value = input.value.replace(/[^0-9]/g, '');
}
function openModal() {
var myModal = new bootstrap.Modal(document.getElementById('loginModal'), {
backdrop: 'static',  
keyboard: false   
});
myModal.show();
}


