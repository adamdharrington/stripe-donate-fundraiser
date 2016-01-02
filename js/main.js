jQuery(function ($) {

  // Instantiate StripeDonate
  var stripe = new StripeDonate();

  var getAmounts = function(){
    var amounts = null;
    if (__stripe_donate_opts && __stripe_donate_opts.hasOwnProperty('donationAmounts')){
      amounts = [];
      for (var amount in __stripe_donate_opts.donationAmounts){
        amounts.push(__stripe_donate_opts.donationAmounts[amount]);
      }
    }
    return amounts
    },
    safeCheck = function(optionName){
      if (__stripe_donate_opts && __stripe_donate_opts.hasOwnProperty(optionName))
        return __stripe_donate_opts[optionName];
      return null;
    };

  var opts = {
    values_single: getAmounts(),
    redirectUrl: "http://example.com",
    defaultPayment: "monthly",
    includeMonthly: safeCheck('generic')
  };
  stripe.setup(opts);



  Stripe.setPublishableKey(safeCheck('publishable') || "");



  // Page Styles
  (function(){
    var $w = $(window), $h = $('#header');
    $(window).on('scroll',function(){
      var val  = Math.ceil($w.scrollTop() / 1.25);
      $h.css('background-position-y', val / 1.25 +'px');
    });
  })();


  /*
   * ============================================================================
   *                                                         Media Checks
   */

  (function () {
    window._environment = {
      //mobile or desktop compatible event name, to be used with '.on' function
      TOUCH_DOWN_EVENT_NAME: 'mousedown touchstart',
      TOUCH_UP_EVENT_NAME: 'mouseup touchend',
      TOUCH_MOVE_EVENT_NAME: 'mousemove touchmove',
      TOUCH_DOUBLE_TAB_EVENT_NAME: 'dblclick dbltap',
      getSize: function () {
        // Shouyld be Same as stylesheet breakpoints
        var sizes = {"xxs": "10", "xs": "400", "sm": "768", "md": "992", "lg": "1200"},
          width = $(window).width(), closest;
        for (var size in sizes) {
          if (closest == undefined) closest = size;
          else {
            if (width > sizes[size]) closest = size;
            else break;
          }
        }
        return closest;
      },
      isAndroid: function () {
        return navigator.userAgent.match(/Android/i);
      },
      isNiche: function () {
        return navigator.userAgent.match(/Nokia|SonyEricsson|Fennec/i);
      },
      isBlackBerry: function () {
        return navigator.userAgent.match(/BlackBerry/i);
      },
      isIOS: function () {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
      },
      isOpera: function () {
        return navigator.userAgent.match(/Opera Mini|Opera Mobi/i);
      },
      isWindows: function () {
        return navigator.userAgent.match(/IEMobile/i);
      },
      isMobile: function () {
        return (
        _environment.isAndroid()
        || _environment.isNiche()
        || _environment.isBlackBerry()
        || _environment.isIOS()
        || _environment.isOpera()
        || _environment.isWindows()
        );
      }
    };

    // Only if NOT mobile
    if (!window._environment.isMobile()) {
      var setFloat = function (update) {
        update = update || null;

        var screensize = window._environment.getSize(),
          $target = $('#donate-ask'),
          floatForm = function (h, p) {
            var opts = {
              offsetY: h,
              startOffset: p,
              duration: 0
            };
            update ? $target.stickyfloat('update', opts) : $target.stickyfloat(opts);
          };
        switch (screensize) {
          case "xxs":
          case "xs":
          case "sm":
            $target.stickyfloat('destroy');
            $target.animate({'top':0},100);
            break;
          case "md":
            floatForm( -130, 100);
            break;
          case "lg":
            floatForm( -180, 140);
            break;
          default:
            break;
        }
      };
      $('#donate-ask').stickyfloat();

      setTimeout(function () {
        setFloat("update");
      }, 100);

      $(window).on("resize", function () {
        setFloat("update");
      });
    }
    $('.floating-form').removeClass('hidden');
  })();
});

