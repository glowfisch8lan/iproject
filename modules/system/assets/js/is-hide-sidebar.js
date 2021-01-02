$(document).ready(function(){


    var status =  localStorage.getItem('isHideSidebar');

    if( status == '1'){
        $('#sidebar').addClass('active');
    }

    // $('a').on('click', function() {
    //
    //     if($(this).hasClass("active")){
    //         localStorage.setItem('isHideSidebar',1);
    //     }
    //     else{
    //         localStorage.setItem('isHideSidebar', 0);
    //     }
    //  });

    $('#sidebarCollapse').on('click', function() {

        if($("#sidebar").hasClass("active")){
            localStorage.setItem('isHideSidebar',1);
        }
        else{
            localStorage.setItem('isHideSidebar', 0);
        }
    });
});