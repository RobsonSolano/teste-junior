$(document).ready(function() {
    console.log("document is ready");
    $('[data-toggle="offcanvas"], #navToggle').on('click', function() {
        $('.offcanvas-collapse').toggleClass('open')
    })
});
window.onload = function() {
    console.log("window is loaded");
};