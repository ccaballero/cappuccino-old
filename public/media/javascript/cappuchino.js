$(document).ready(function(){
    var request = window.location.pathname.substr(1);
    $('#menubar li a[href="/'+request+'"]').parent().addClass('active');
    
    
});
