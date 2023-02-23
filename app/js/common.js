$(function() {
  initCloseBlock();
  initFixedHeader();
  initHeaderSearch();
  initOpenBasket();
  initOpenSubMenu();
  initMobileNav();
  initlangSwitcher();
  initSpinner();
  initSlickSlider();
  initSlickSlider2();
  initSlickSlider3();
  initSlickSlider4();
  initCustomSelect();
  initOpenBlock();
  initAccordion();
  initFixedBlock();
  initScrollBtn();
  initAnchorMenu();
  initPlayVideo();
  initCustomTextarea();
  initTabs();
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
  $('.btn-basket').on('click', function(){
    $(this).closest('.basket-box').toggleClass('active');
    $('body').toggleClass('open-basket');
    return false;
  });
  $(document).click(function(event) {
    if ($(event.target).closest(".basket-box .basket-services-box").length) return;
    $(".basket-box").removeClass('active');
    $('body').removeClass('open-basket');
    event.stopPropagation();
  });
};
function initOpenSubMenu(){
  if(window.matchMedia("screen and (max-width: 1024px)").matches==true) {
    $(".nav-menu > li > a").attr("data-count", "0");
    $(".nav-menu > li > a").on("click", function(){ 
    var clickCount = $(this).attr("data-count");
    clickCount ++;
      if (clickCount == 1) {
        $(this).attr("data-count", clickCount);
        return false
      } else {
        return true;
      }
    });
  }
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
function initlangSwitcher(){
  $('.lang-opener').on('click', function(event) {
    event.preventDefault();
    $(this).closest('.lang').toggleClass('drop-active');
    $(this).parent().find('.lang-drop').slideToggle(200);
  });
  $(document).click(function(event) {
    if ($(event.target).closest('.lang').length) return;
    $('.lang').removeClass('drop-active');
    $('.lang-drop').slideUp(200);
    event.stopPropagation();
  });
  $('.lang-drop a').click(function(){
    $('.lang-opener').html($(this).html());
    $('.lang').removeClass('drop-active');
    $('.lang-drop').slideUp(200);
    return false;
  });
}
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
  $('.expanded-opener').on('click', function() {
    $(this).closest('.filter-options-item').toggleClass('open-drop');
    $(this).closest('.hide-text').toggleClass('open');
    $(this).siblings('.expanded').slideToggle();
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
    if($(this).closest('li').hasClass('open')){
      $(this).closest('li').removeClass('open');
      $(this).closest('li').find('.accordion-mobile-slide').slideUp();
    } else {
      $(this).closest('.accordion-mobile').find('.accordion-mobile-slide').slideUp();
      $(this).closest('.accordion-mobile').find('li').removeClass('open');
      $(this).closest('li').addClass('open');
      $(this).closest('li').find('.accordion-mobile-slide').slideDown();
    }
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
}
function initAnchorMenu(){
  $(".page-navigation").on("click","a", function (event) {
    event.preventDefault();
    var id  = $(this).attr('href'),
     top = $(id).offset().top - 120;
    $('body,html').animate({scrollTop: top}, 1000);
  });
}
function initPlayVideo() {
  $('.playpause').click(function () {
    $('video').attr('controls', '');
    $('video')[0].play();
    $('.video').toggleClass('play');
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
  $('.tabset .tab-control').on('click', 'li:not(.active)', function() {
    $(this)
      .addClass('active').siblings().removeClass('active')
      .closest('.tabset').find('.tab').removeClass('active').eq($(this).index()).addClass('active');
      return false;
  });
}