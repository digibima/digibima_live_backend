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

  
  
