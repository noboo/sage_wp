// page top
jQuery(function () {
  var pageTop = jQuery(".page-top");
  pageTop.hide();
  jQuery(window).scroll(function () {
    if (jQuery(this).scrollTop() > 400) {
      pageTop.fadeIn();
    } else {
      pageTop.fadeOut();
    }
  });
  pageTop.click(function () {
    jQuery("body,html").animate(
      {
        scrollTop: 0,
      },
      900
    );
    return false;
  });

  // start fade
  jQuery(window).on("load", function () {
    jQuery(".loader-bg").hide();
  });

  // bottomenu
  var menuHeight = jQuery("#bottomenu").height();
  var navPos = 10;
  jQuery(window).scroll(function () {
    var Pos = jQuery(this).scrollTop();
    if (Pos > navPos) {
      if (jQuery(window).scrollTop() >= 100) {
        jQuery("#bottomenu").css("bottom", "-" + menuHeight + "px");
      }
    } else {
      jQuery("#bottomenu").css("bottom", "0px");
    }
    navPos = Pos;
  });
  // --navbar fade--
  jQuery(function() {
    var $win = jQuery(window),
        $main = jQuery('.wrap'),
        $nav = jQuery('.navbar'),
        navHeight = $nav.outerHeight(),
        navPos = $nav.offset().top,
        fixedClass = 'fixed-top';
  
    $win.on('load scroll', function() {
      var value = jQuery(this).scrollTop();
      if ( value > navPos ) {
        $nav.addClass(fixedClass);
        $main.css('margin-top', navHeight);
      } else {
        $nav.removeClass(fixedClass);
        $main.css('margin-top', '0');
      }
    });
  });

// --navbar fade-- 下スクロール非表示　上スクロール表示
  jQuery(function() {
    var $win = jQuery(window),
        $header = jQuery('.navbar'),
        headerHeight = $header.outerHeight(),
        startPos = 0;
  
    $win.on('load scroll', function() {
      var value = jQuery(this).scrollTop();
      if ( value > startPos && value > headerHeight ) {
        $header.css('top', '-' + headerHeight + 'px');
      } else {
        $header.css('top', '0');
      }
      startPos = value;
    });
  });

  // current_page_item
  jQuery(
    ".current_page_item a, .current-menu-item a, .current-cat a, .current-menu-parent a"
  ).addClass("text-dark bg-light");
});

jQuery(document).ready(function () {
  console.log("ready");
  var EffectH = 100;
  jQuery(window).on("scroll load", function () {
    var scTop = jQuery(this).scrollTop();
    var scBottom = scTop + jQuery(this).height();
    var effectPos = scBottom - EffectH;
    jQuery(".breffects").each(function () {
      var thisPos = jQuery(this).offset().top;
      if (thisPos < effectPos) {
        // .js-scrollという要素が可視範囲に入ったら
        jQuery
          .when(
            // .js-scrollにshowというclassを付与
            jQuery(this).addClass("")
          )
          .done(function () {
            //  その後、0.5秒遅らせて.js-scrollにdoneというclassを付与
            jQuery(this)
              .delay(0)
              .queue(function () {
                jQuery(this).addClass("blockn");
              });
          });
      }
    });
  });
});
