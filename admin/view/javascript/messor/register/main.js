$(document).ready(function(){
    $(".settings__content").hide();
    $(".about__form").hide();
    $("#modal-installation").hide();
    $(".settings__form").hide();
    $(".modal-installation__content").hide();
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

$(".account__checkbox").click(function() {
    $(this).find(".account__checkbox-tip").toggleClass("hide");
});

let animation_ongoing=0;

$(".about__top").on('click', function (e) {
    if(!animation_ongoing) {
        animation_ongoing=1;
        $(".about__form").slideToggle( "slow" ).promise().done(function(){
          animation_ongoing=0;
        });
        $(".about").toggleClass('open', 350);
        $(".about__chevron").toggleClass('rotated');
    }
});

$(".settings__top").on('click', function (e) {
    if(!animation_ongoing) {
        animation_ongoing=1;
        $(".settings__form").slideToggle( "slow" ).promise().done(function(){
          animation_ongoing=0;
        });
        $(".settings").toggleClass('open', 350);
        $(".settings__chevron").toggleClass('rotated');
    }
});

$(".modal-installation__head").on('click', function (e) {
    if(!animation_ongoing) {
        animation_ongoing=1;
        $(".modal-installation__content").slideToggle( "slow" ).promise().done(function(){
          animation_ongoing=0;
        });
        $(".modal-installation__body").toggleClass('open');
        $(".modal-installation").toggleClass('rotated');
        $(".modal-installation__devider").toggleClass('hide');
        $(".modal-installation__chevron").toggleClass('rotated');
    }
});
/*
$("#input-country").on('click', function (e) {
        $("#select-country").toggleClass('hide');
        $("#select__chevrone-country").toggleClass('rotated');
});*/

$(".modal-installation__close-button").on('click', function (e) {
    $('#modal-installation').css( "display", "none" );
});

$(".install__button").on('click', function (e) {
    $('#modal-installation').css( "display", "block" );
});

$(document).mouseup(function (e){
	if (!$(".modal-installation__top").is(e.target) && $(".modal-installation__top").has(e.target).length === 0 
		&& !$(".modal-installation__body").is(e.target) && $(".modal-installation__body").has(e.target).length === 0) {
		$('#modal-installation').hide();
	}
});

$('#input-country').on('click', function() {
    if ( $(this).hasClass('is-active') ) {
        $(this).removeClass('is-active');
        $('#select-country').slideUp();
    } else {
        $(this).addClass('is-active');
        $('#select-country').slideDown();
    }
});

$(document).click(function (e) {
    if ( !$('#input-country').is(e.target) && !$('#select-country').is(e.target) && $('#select-country').has(e.target).length === 0) {
        $('#select-country').slideUp();
        $('#input-country').removeClass('is-active');
    };
});

$(".about__option-country").click(function (e) {
        $('#select-country').slideUp();
        $('#input-country').removeClass('is-active');
        document.getElementById('input-country').value=$(this).text().trim();
});


$('#input-language').on('click', function() {
    if ( $(this).hasClass('is-active') ) {
        $(this).removeClass('is-active');
        $('#select-language').slideUp();
    } else {
        $(this).addClass('is-active');
        $('#select-language').slideDown();
    }
});

$(document).click(function (e) {
    if ( !$('#input-language').is(e.target) && !$('#select-language').is(e.target) && $('#select-language').has(e.target).length === 0) {
        $('#select-language').slideUp();
        $('#input-language').removeClass('is-active');
    };
});

$(".about__option-language").click(function (e) {
        $('#select-language').slideUp();
        $('#input-language').removeClass('is-active');
        document.getElementById('input-language').value=$(this).text().trim();
});

$('#input-server').on('click', function() {
    if ( $(this).hasClass('is-active') ) {
        $(this).removeClass('is-active');
        $('#select-server').slideUp();
    } else {
        $(this).addClass('is-active');
        $('#select-server').slideDown();
    }
});

$(document).click(function (e) {
    if ( !$('#input-server').is(e.target) && !$('#select-server').is(e.target) && $('#select-server').has(e.target).length === 0) {
        $('#select-server').slideUp();
        $('#input-server').removeClass('is-active');
    };
});

$(".settings__option-server").click(function (e) {
        $('#select-server').slideUp();
        $('#input-server').removeClass('is-active');
        document.getElementById('input-server').value=$(this).text().trim();
});

$('#input-encryption').on('click', function() {
    if ( $(this).hasClass('is-active') ) {
        $(this).removeClass('is-active');
        $('#select-encryption').slideUp();
    } else {
        $(this).addClass('is-active');
        $('#select-encryption').slideDown();
    }
});

$(document).click(function (e) {
    if ( !$('#input-encryption').is(e.target) && !$('#select-encryption').is(e.target) && $('#select-encryption').has(e.target).length === 0) {
        $('#select-encryption').slideUp();
        $('#input-encryption').removeClass('is-active');
    };
});

$(".settings__option-encryption").click(function (e) {
        $('#select-encryption').slideUp();
        $('#input-encryption').removeClass('is-active');
        document.getElementById('input-encryption').value=$(this).text().trim();
});
