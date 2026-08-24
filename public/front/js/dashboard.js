// $(document).ready(()=>{
  
//     $('#open-sidebar').click(()=>{
       
//         // add class active on #sidebar
//         $('#sidebar').addClass('active');
        
//         // show sidebar overlay
//         $('#sidebar-overlay').removeClass('d-none');
      
//      });
    
    
//      $('#sidebar-overlay').click(function(){
       
//         // add class active on #sidebar
//         $('#sidebar').removeClass('active');
        
//         // show sidebar overlay
//         $(this).addClass('d-none');
      
//      });
    
//   });



$(document).ready(function() {
  function checkScreenSize() {
    if ($(window).width() >= 768) {
      $('#open-sidebar').hide();
    } else {

      $('#open-sidebar').show();
    }
  }

  checkScreenSize();

  $(window).resize(function() {
    checkScreenSize();
  });

  $('#open-sidebar').click(function() {
    $('#sidebar').addClass('active');
    $('#sidebar-overlay').removeClass('d-none');
  });

  $('#sidebar-overlay').click(function() {
    $('#sidebar').removeClass('active');
    $(this).addClass('d-none');
  });
});

  
  

  function showAlert(type, message, duration = 5000) {
    if (type === 'error') {
      const errorBox = document.getElementById('MainErrorBox');
      if (!errorBox) return console.error("MainErrorBox not found in DOM");

      errorBox.querySelector('.error__title').textContent = message;
      errorBox.style.display = 'flex';

      setTimeout(() => hideAlert('error'), duration);
    }

    if (type === 'success') {
      const successBox = document.getElementById('MainVerifiedBox');
      if (!successBox) return console.error("MainVerifiedBox not found in DOM");

      successBox.querySelector('.verifiyed__title').textContent = message;
      successBox.style.display = 'flex';

      setTimeout(() => hideAlert('success'), duration);
    }
  }

  function hideAlert(type) {
    if (type === 'error') {
      const box = document.getElementById('MainErrorBox');
      if (box) box.style.display = 'none';
    }
    if (type === 'success') {
      const box = document.getElementById('MainVerifiedBox');
      if (box) box.style.display = 'none';
    }
  }

