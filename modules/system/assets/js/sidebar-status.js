window.onload = function () {
    document.body.classList.add('loaded_hiding');
    window.setTimeout(function () {
        document.body.classList.add('loaded');
        document.body.classList.remove('loaded_hiding');
    }, 500);
}
$('ul.collapse').on('hide.bs.collapse', function () {
    sessionStorage.setItem('#'+$(this).attr('id'), 0);
});

$('ul.collapse').on('show.bs.collapse', function () {
    sessionStorage.setItem('#'+$(this).attr('id'), 1);
});

$(document).ready(function(){

    $(this).find('a.menu-link-dropdown').each(function(){

        var id = $(this).attr('href');
        var result = sessionStorage.getItem(id);
        if(Number(result) === 1){
            $(id).collapse();
        };
    });
});