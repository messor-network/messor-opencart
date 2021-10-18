$(document).ready(function(){
    $(".settings__content").hide();
    $(".last__toggle-row").addClass("collapse");
});

$(".settings__btn").on('click', function (e) {
    $('.settings__content').slideToggle( "slow" );
    $(".settings").toggleClass('open');
});
$("#last__title").on('click', function (e) {
    $('.last__table').slideToggle( "slow" );
    $(".last").toggleClass('open');
});

$(".more").click(function() {
    var contentId =this.dataset.href;
    $(contentId).toggleClass("collapse");
    $(this).parent().parent().toggleClass('more-delete');
});