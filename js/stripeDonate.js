var StripeDonate = (function () {
  var $ = jQuery,
    setup = function (opts) {
    var _opts = opts || {},
      config = {
        values_monthly: [1, 3, 5, 8, 10, 20],
        values_single: [5, 10, 20, 50, 75, 100],
        defaultPayment: "single",
        includeMonthly: true,
        redirectUrl: location.pathname
      };
    $.extend(config, _opts);

    var $widget = $("#stripe-donate"), // use this scope for everything
      ifSuccess = function () {
        var $success = $('#success-modal'); //TODO: SOC
        if ($success.length > 0) {
          $success.modal();
          return true;
        }
        else return false;
      },
      makeForm = function () {
        var custMeta = {},
          validators = [],
          vdata = [{
            rules:{},
            messages:{}
          },{
            rules:{
              "first-name": "required",
              "email": {
                required: true,
                email: true
              }
            },
            messages:{
              "first-name": "You must provide your name",
              "last-name": "You must provide your name",
              "email": {
                required: "You must enter your email address",
                email: "Your email address must be valid"
              }
            }
          },{
            rules:{},
            messages:{}
          }],
          manageSubmit = function () {
            $widget.find("form").each(function (i) { //TODO: SOC
              var $singleForm = $(this);
              validators[i] = $singleForm.validate(vdata[i]);
              /*$singleForm.find('input,select').on('change', function () {
                validators[i].element(this);
              });*/
              $singleForm.find('button').submit(function (e) {
                e.preventDefault();
              });
            });
          },
          applyStripeValidate = function () {
            /*
             * ============ Stripe.payment validation and formatting
             * === thanks to Stripe
             */
            $widget.find('.cc-number').payment('formatCardNumber');
            $widget.find('.cc-exp').payment('formatCardExpiry');
            $widget.find('.cc-cvc').payment('formatCardCVC');
            $widget.find('[data-numeric]').payment('restrictNumeric');
            $.fn.toggleInputError = function (erred) {
              this.parent('.form-group').toggleClass('has-error', erred);
              return this;
            };
          },
          managePages = function () {
            var $nexts = $widget.find('.btn-next'), //TODO: SOC, specificity
              $previous = $widget.find('.btn-prev'), //TODO: SOC, specificity
              page = 1,
              pageOne = function () {
                // Handle Different amounts for donations
                //
                var buttons = $widget.find("#donation-amounts button"),
                  $amount = $widget.find("#donation-amount"),
                  chooseValue = function (val) {
                    if (+val) {
                      custMeta['donation amount'] = val;
                      var reg = custMeta["recurring"],
                        textVal="";
                      reg = reg == "monthly" ? " Monthly" : "";
                      textVal = "€" + val / 100 + reg;
                      $widget.find('span[data-role=amount]')
                        .text(textVal);
                    }
                  },
                  updateVal = function (val) {
                    $amount.val(val);
                    chooseValue(val);
                  };

                buttons.on('click', function (e) {
                  $widget.find('.active-amount').removeClass('active-amount');
                  $(this).toggleClass('active-amount');
                  updateVal(this.value);
                });

                $widget.find("#donation-amounts #other-amount").on('change', function (e) {
                  $widget.find('.active-amount').removeClass('active-amount');
                  updateVal(100 * (parseInt(this.value) || 1 ));
                });

                // Handle Monthly Donations
                var radiobuttons = $widget.find(".recurring button"),
                  changeRecurring = function (button) {
                    $widget.find(".recurring input[type=radio]#recurring_" + button).prop('checked', true);
                    custMeta["recurring"] = button;
                  },
                  changeValues = function (recurring) {
                    var vals;
                    if (recurring == "monthly") {
                      vals = config.values_monthly;
                      // Hide "OTHER"
                      $widget.find('.other-amount').hide();
                    }
                    else {
                      vals = config.values_single;
                      // show "OTHER"
                      $widget.find('.other-amount').show();
                    }

                    buttons.each(function (i) {
                      $(this)
                        .val(vals[i] * 100)
                        .find('span.amount').text(vals[i]);
                    });

                    // Select Mid range figure
                    $(buttons[2]).trigger("click");

                  };

                radiobuttons.on('click', function (e) {
                  radiobuttons.removeClass('active-button');
                  $(this).addClass('active-button');
                  changeRecurring(this.value);
                  changeValues(this.value);
                });

                if (!config.includeMonthly) {
                  // TODO: Disable monthly donations
                } else if (config.defaultPayment === "monthly" || config.defaultPayment === "single") {
                  // set initial payment
                  $widget
                    .find('.recurring button[value=' + config.defaultPayment + ']')
                    .trigger('click');
                }

                $widget.find('#donate-amount button.btn-next').on('click', function () {
                    $(this).trigger('next');
                });
              },

              pageTwo = function () {
                /*
                 * === Page 2 - #your-info ===
                 */

                $widget.find('#your-info button.btn-next').on('click', function () {
                  var ok = true;
                  $widget.find('input[data-stripe], select[data-stripe]').each(function () {
                    var name = this.dataset['stripe'],
                      valu = this.value;
                    if (name && valu) {
                      custMeta[name] = valu;
                    }
                    else ok = false;
                  });
                  if (ok && validators[1].errorList.length == 0) {
                    $(this).trigger('next');
                  }
                  else $widget.find('#your-info').validate();
                });

                $widget.find('#your-info button.btn-prev').on('click', function () {
                  $(this).trigger('prev');
                });
              },


              pageThree = function () {
                /*
                 * === Page 3 #donate-form ===
                 */

                $widget.find('#donate-form button.btn-next').on('click', function () {
                  var val = $widget.find('input[data-stripe=amount]');
                  if (val.val()) {
                    $(this).trigger('getToken');
                  }
                });

                $widget.find('#donate-form button.btn-prev').on('click', function () {
                  $(this).trigger('prev');
                });

              },
              updateProgress = function () {
                // Control the page indicator
                $widget.find('.steps-list li').each(function (i) {
                  //TODO: SOC, specificity
                  if (i !== page - 1)
                    $(this).removeClass("active"); //TODO: SOC, specificity
                  else
                    $(this).addClass("active"); //TODO: SOC, specificity
                })
              },
              navigationHandlers = function () {
                /*
                 * === Navigation General ===
                 */
                var $pageWrap = $widget.find('.pages-wrap'),
                  move = function (l) {
                    $pageWrap.animate({'left': l}, 300);
                    updateProgress();
                  };

                $nexts.on("next", function () {
                  var p = $(this).parents('.page').attr('data-page');
                  if (p < 3) {
                    var left = ( -100 * p ) + "%";
                    page += 1;
                    move(left);
                  }
                });

                $previous.on("prev", function () {
                  var p = $(this).parents('.page').attr('data-page');
                  if (p > 1) {
                    var left = ( -100 * ( p - 2 )) + "%";
                    page -= 1;
                    move(left);
                  }
                });

              };
            // Call page handlers
            pageOne();
            pageTwo();
            pageThree();
            navigationHandlers();
          };

        manageSubmit();
        managePages();
        applyStripeValidate();

        /*
         doToken submits an ajax request for a token from Stripe and then submits the page
         -- This could be a good place TODO: Control next actions (send to new page or refresh)
         --
         */

        var doToken = function (e) {

          e.preventDefault();

          // disable the submit button to prevent repeated clicks
          this.disabled = "disabled";


          var validate = (function () {
            var cardType = $.payment.cardType($widget.find('.cc-number').val());

            $widget.find('.cc-number')
              .toggleInputError(!$.payment.validateCardNumber($widget.find('.cc-number').val()));
            $widget.find('.cc-exp')
              .toggleInputError(!$.payment.validateCardExpiry($widget.find('.cc-exp').payment('cardExpiryVal')));
            $widget.find('.cc-cvc')
              .toggleInputError(!$.payment.validateCardCVC($widget.find('.cc-cvc').val(), cardType));
            $widget.find('.cc-brand')
              .text(cardType);

            $widget.find('.validation').removeClass('text-danger text-success');
            $widget.find('.validation').addClass($('.has-error').length ? 'text-danger' : 'text-success');

            return $widget.find('#donate-form .has-error').length == 0;
          })();

          if (validate) {

            var exp = $widget.find('input.cc-exp').payment('cardExpiryVal');
            // createToken returns immediately - the supplied callback submits the form if there are no errors
            Stripe.createToken({
              number: $widget.find('.cc-number').val(),
              cvc: $widget.find('.cc-cvc').val(),
              exp_month: exp.month,
              exp_year: exp.year,
              name: custMeta["first-name"] + " " + custMeta["last-name"],
              address_country: custMeta["country"],
              address_line1: custMeta["email"],
              metadata: {"email": custMeta["email"]}
            }, stripeResponseHandler);


            return false; // submit from callback
          }

          else this.disabled = false; // Fields not valid, not sent to Stripe, re-enable button
          // TODO: a message here would be nice

          function stripeResponseHandler(status, response) {
            if (response.error) {
              // re-enable the submit button
              $widget.find('.submit-button').removeAttr("disabled");
              // show the errors on the form
              // TODO: ensure .payment-errors exists
              $widget.find(".payment-errors").html("<p>"+ response.error.message + "</p>");
            }
            else {
              var $form = $widget.find("#donate-form");
              // token contains id, last4, and card type
              var token = response['id'];
              // insert the token into the form so it gets submitted to the server
              $form.append("<input type='hidden' name='stripeToken' value='" + token + "' />");

              // insert all custMeta info so it arrives in _$POST
              for (var key in custMeta) {
                $form.append("<input type='hidden' name='" + key + "' value='" + custMeta[key] + "' />");
              }
              // and submit
              $form.get(0).submit();
            }
          }
        };


        // TODO: Which Submit? This should be a setting
        $widget.find("#donate-form .submit-button").on("getToken", doToken);

      };

    // Execute Here.
    if (!ifSuccess())makeForm();
  };

  return {
    setup: function (opts) {
      if (opts) return setup(opts);
      else return setup();
    }
  }

});