var $ = jQuery;
$(document).ready(function() {
  initCloseBlock();
  initFixedHeader();
  initHeaderSearch();
  initOpenBasket();
  initMobileNav();
  initSpinner();
  initSlickSlider();
  initSlickSlider2();
  initSlickSlider3();
  initSlickSlider4();
  initSlickSliderProduct();
  initCustomSelect();
  initOpenBlock();
  initAccordion();
  initFixedBlock();
  initScrollBtn();
  initAnchorMenu();
  initPlayVideo();
  initCustomTextarea();
  initTabs();
  initRatingStars();
  initLightBox();
  initFancyBox();
  initVerticalScroll();
});

function initCloseBlock(){
  $('.close-block').on('click', function(){
    $('.top-bar').css('display','none');
    $('body').toggleClass('no-top-bar');
    return false;
  })
}
function initFixedHeader() {
  window.onscroll = function() {myFunction()};
  var sticky = header.offsetTop;
  function myFunction() {
    if (window.pageYOffset > sticky) {
      $('#header').addClass("sticky");
    } else {
      $('#header').removeClass("sticky");
    }
  }
}
function initHeaderSearch() {
  $('.form-search-btn').on('click', function(){
    $(this).closest('.form-search').toggleClass('open');
    return false;
  });
  $(document).click(function(event) {
    if ($(event.target).closest(".form-search .search").length) return;
    $(".form-search").removeClass('open');
    event.stopPropagation();
  });
}
function initOpenBasket(){
  $('body').on('click', '.btn-basket', function(){
    $('body').toggleClass('open-basket').trigger('mini_cart_open');
    return false;
  });
  $(document).click(function(event) {
    if ($(event.target).closest(".open-basket .basket-services-box").length) return;
    $('body').removeClass('open-basket').trigger('mini_cart_close');
    event.stopPropagation();
  });
};
function initMobileNav(){
  $('.mob-btn').on('click', function(){
    $(this).closest('#nav').toggleClass('active');
    $('body').toggleClass('open-nav');
    return false;
  });
  $(document).click(function(event) {
    if ($(event.target).closest("#nav .nav-open-drop").length) return;
    $("#nav").removeClass('active');
    $('body').removeClass('open-nav');
    event.stopPropagation();
  });
};
function initSpinner() {
  if ($('.spinner').length) {
    $('.spinner').spinner({
      min: 1,
    })
  }
}
function initSlickSlider() {
  $('.slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    dots: true,
    fade: true,
    autoplay: true,
    autoplaySpeed: 4000
  });
}
function initSlickSlider2() {
  $('.goods-list-slider').slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    arrows: true,
    dots: false,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1
        }
      }
    ]
  });
}
function initSlickSlider3() {
  $('.reviews-slider').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: true,
    dots: false,
    adaptiveHeight: true,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          centerMode: true,
          centerPadding: '100px',
          arrows: false
        }
      }
    ]
  });
}
function initSlickSlider4() {
  $('.social-slider').slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    arrows: false,
    dots: true,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1
        }
      }
    ]
  });
}
function initSlickSliderProduct() {
  $('.slider-for').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    adaptiveHeight: true,
    asNavFor: '.slider-nav'
  });
  $('.slider-nav').slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    asNavFor: '.slider-for',
    arrows: false,
    dots: false,
    // centerMode: true,
    focusOnSelect: true,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1
        }
      }
    ]
  });
}
function initCustomSelect(){
  $('.custom-select select').select2();
  $(".js-select2").select2({
      closeOnSelect : false,
      placeholder : "Групи товарів",
      allowHtml: true,
      allowClear: true
    });
}
function initOpenBlock(){
  $('body').on('click', '.expanded-opener', function() {
    $(this).closest('.filter-options-item').toggleClass('open-drop');
    $(this).closest('.hide-text').toggleClass('open');
    $(this).siblings('.expanded').slideToggle();
    return false;
  });
  $('.nav-menu .arrow-down').on('click', function() {
    $(this).closest('.menu-item-has-children').toggleClass('open');
    $(this).siblings('.nav-menu .sub-menu').slideToggle();
    return false;
  });
  $('body').on('click', '.custom-select.multiple .expanded-opener',function() {
    $(this).closest('.custom-select.multiple').toggleClass('open');
    $(this).siblings('.custom-select.multiple .filter-options-item').slideToggle();
    return false;
  });
}
function initAccordion(){
  $('.accordion .opener').on('click', function() {
    if($(this).closest('li').hasClass('active')){
      $(this).closest('li').removeClass('active');
      $(this).closest('li').find('.accordion-slide').slideUp();
    } else {
      $(this).closest('.accordion').find('.accordion-slide').slideUp();
      $(this).closest('.accordion').find('li').removeClass('active');
      $(this).closest('li').addClass('active');
      $(this).closest('li').find('.accordion-slide').slideDown();
    }
    return false;
  });
  $('.accordion-mobile-opener').on('click', function() {
    $(this).closest('.accordion-mobile > li').toggleClass('open');
    $(this).siblings('.accordion-mobile-slide').slideToggle();
    return false;
  });
}
function initFixedBlock() {
  $(".fixed-block .fixed-sidebar").stick_in_parent();
}
function initScrollBtn(){
  $('.page-up').click(function(){
    $('body,html').animate({scrollTop:$('.user-form-block').offset().top},1000);
    return false;
  });
  $('.product-info-section .rating-stars .rating-text').click(function(){
    $('.product-info-description .tabset').toggleClass('open-change');
    $('.product-info-description .tabset.open-change .tab-control li').removeClass('active');
    $('.product-info-description .tabset.open-change .tab-body .tab').removeClass('active');
    $('.product-info-description .tabset.open-change .tab-control li:nth-child(3)').toggleClass('active');
    $('.product-info-description .tabset.open-change .tab-body .tab:nth-child(3)').toggleClass('active');
    $('body,html').animate({scrollTop:$('.product-info-description').offset().top - 125},1000);
    return false;
  });
}
function initAnchorMenu(){
  $(".page-navigation").on("click","a", function (event) {
    event.preventDefault();
    $(".page-navigation li").removeClass('active');
    $(this).parent().addClass('active');
    var id  = $(this).attr('href'),
     top = $(id).offset().top - 120;
    $('body,html').animate({scrollTop: top}, 1000);
  });
}
function initPlayVideo() {
  $('.playpause').click(function () {
    $(this).parent().find('video').attr('controls', '')[0].play();
    $(this).parents('.video').toggleClass('play');
    $(this).hide();
  });
}
function initCustomTextarea(){
  document.querySelectorAll('textarea').forEach(el => {
      el.style.height = el.setAttribute('style', 'height: ' + el.scrollHeight + 'px');
      el.classList.add('auto');
      el.addEventListener('input', e => {
          el.style.height = 'auto';
          el.style.height = (el.scrollHeight) + 'px';
      });
  });
}
function initTabs(){
  $('body').on('click', '.tabset .tab-control li:not(.active)', function(e) {
    $(this)
      .addClass('active').siblings().removeClass('active')
      .closest('.tabset').find('.tab').removeClass('active').eq($(this).index()).addClass('active');
    $('#customer_details').addClass('active').find('.wc-ukr-shipping-np-fields').find('.wc-ukrposhta-up-fields').find('.wc-pickup-fields');
  });
  if(window.matchMedia("screen and (max-width: 767px)").matches==true) {
    $('.product-info-description .tabset').addClass('accordion-mobile');
    $('.product-info-description .accordion-mobile-opener').on('click', function() {
      $(this).closest('.product-info-description .tab').toggleClass('open');
      return false;
    });
  }
}
function initRatingStars() {
  ratingForm = document.getElementById("ratingForm");
  if(ratingForm)
    document.getElementById("ratingForm").onsubmit = function (e) {
        e.preventDefault();
        document.querySelector('input[name="rating"]:checked').value;
        document.querySelector(".panel")
    }, document.querySelectorAll('input[name="rating"]').forEach((e => {
    e.addEventListener("change", (() => {
        !0 === e.checked && (submitBtn.disabled = !1)
    }))
  }))
}
function initLightBox(){
  lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true
  })
}
function initFancyBox(){
  $('.fancybox').fancybox();
}
function initVerticalScroll() {
  $(".mCustomScrollbar").mCustomScrollbar({
    axis:"y",
    advanced:{autoExpandHorizontalScroll:false}
  });
}
function initDownloadFile(){
  var copyText = document.getElementById("download-file");
  copyText.select();
  copyText.setSelectionRange(0, 99999); // For mobile devices
  navigator.clipboard.writeText(copyText.value);
}
if (!document.fullscreenElement) {
  document.documentElement.requestFullscreen().catch((e)=>{});
}
