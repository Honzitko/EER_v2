var eer_remove_function = [];
var eer_submit_function = [];

jQuery(function ($) {
  $(document).ready(function () {
    $.ajaxSetup({
      data: { nonce: eer_ajax_object.nonce }
    });

    var max_height = 0;
    $(".eer-tickets .eer-ticket-body-wraper").each(function (key, ticket) {
      var ticket_outer_height = $(ticket).outerHeight();
      if (max_height < ticket_outer_height) {
        max_height = ticket_outer_height;
      }
    });
    if (max_height > 0) {
      $(".eer-tickets .eer-ticket-body-wraper").css("height", max_height);
    }

    /** global: ajaxurl */
    $("body").on("click", ".eer-ticket:not(.eer-ticket-remove):not(.eer-sold) .eer-ticket-add", function (e) {
      e.preventDefault();
      var sale_wrapper = $(this).closest(".eer-tickets-sale-wrapper");
      var ticket = $(this).closest(".eer-ticket");

      if ($("#eer-ticket-" + ticket.data("id")).length === 0) {
        var prep_row = sale_wrapper.find(".eer-ticket-default-form-row").html();
        var tickets_placeholder = sale_wrapper.find(".eer-form-tickets");

        if (tickets_placeholder.hasClass("eer-error")) {
          tickets_placeholder.removeClass("eer-error").empty();
        }

        //Replace data in prep row
        prep_row = prep_row.replace(/%eer-ticket-title%/g, ticket.data("title"));
        prep_row = prep_row.replace(/%eer-price%/g, ticket.data("price"));
        prep_row = prep_row.replace(/%eer-price-currency%/g, eer_price_template(ticket.data("price"), sale_wrapper));
        prep_row = prep_row.replace(/%eer-max%/g, ticket.data("max"));
        prep_row = prep_row.replace(/%eer-ticket-id%/g, ticket.data("id"));
        prep_row = prep_row.replace(/%eer-separately%/g, ticket.data("sold_separately"));
        tickets_placeholder.append(prep_row);

        var ticket_row = sale_wrapper.find(".eer-form-tickets #eer-ticket-" + ticket.data("id"));
        if (ticket.data("max") === 1) {
          ticket_row.find(".eer-number-of-tickets").hide();
        }
        if (ticket.data("solo") === 1) {
          ticket_row.find(".eer-partner").remove();
        } else {
          ticket_row.find(".eer-number-of-tickets").hide();

          //Check and remove dancing as options
          if ((ticket.data("leader-enabled") === "") || (ticket.data("leader-enabled") === 0)) {
            ticket_row.find(".eer-dancing-as-input.eer-leader").prop("disabled", true).prop("checked", false).parent().hide();
          }
          if ((ticket.data("follower-enabled") === "") || (ticket.data("follower-enabled") === 0)) {
            ticket_row.find(".eer-dancing-as-input.eer-follower").prop("disabled", true).prop("checked", false).parent().hide();
          }
        }

        //Load levels
        if (ticket.hasClass("eer-has-levels")) {
          $.each(ticket.data("levels"), function (key, level) {
            ticket_row.find("select[name=level_id]")
            .append("<option value=\"" + key + "\" data-leaders=\"" + level.leaders + "\" data-followers=\"" + level.followers + "\" data-tickets=\"" + level.tickets + "\">" + level.name + "</option>");
          });
        } else {
          ticket_row.find(".eer-info-row.eer-level-row").remove();
        }

        /*if ((typeof ticket.data("related-tickets") !== "undefined") && (ticket.data("related-tickets").length > 0)) {
          var related_tickets = ticket.data("related-tickets");
          $.each($(".eer-ticket:not([data-id=" + ticket.data("id") + "])"), function (key, rticket) {
            if ($.inArray($(rticket).data("id").toString(), related_tickets) === -1) {
              $(rticket).addClass("eer-ticket-remove");
            }
          });
        } else {*/
          if (ticket.data("sold_separately") === 1) {
            sale_wrapper.find(".eer-tickets .eer-ticket").addClass("eer-ticket-remove");
          } else {
            sale_wrapper.find(".eer-tickets .eer-ticket[data-sold_separately=1]").addClass("eer-ticket-remove");
          }
        //}

        if ((typeof ticket.data("disable_partner_check") !== "undefined") && (ticket.data("disable_partner_check") === 1)) {
          ticket_row.find(".eer-info-row.eer-dancing-with input[value=0]").prop("checked", true);
          ticket_row.find(".eer-info-row.eer-dancing-with").hide();
        }

        ticket.addClass("eer-ticket-remove");

        eer_tickets_final_price_recount(sale_wrapper);
      }
    }).on("click", ".eer-ticket-remove", function () {
      var sale_wrapper = $(this).closest(".eer-tickets-sale-wrapper");
      var ttb = $(this).closest(".eer-ticket-to-buy");
      var ticket = sale_wrapper.find(".eer-ticket[data-id=" + ttb.data("id") + "]").removeClass("eer-ticket-remove");
      ttb.remove();

      eer_tickets_final_price_recount(sale_wrapper);

      if ($(".eer-form-tickets .eer-ticket-to-buy", $(sale_wrapper)).size() === 0) {
        $(".eer-tickets .eer-ticket").removeClass("eer-ticket-remove");
      }

      $.each(eer_remove_function, function (key, func) {
        if (window[func] !== undefined) {
          window[func](ttb.data("id"));
        }
      });
    }).on("change", ".eer-number-of-tickets input[type=number]", function () {
      if (parseInt($(this).val()) > parseInt($(this).attr("max"))) {
        $(this).val(parseInt($(this).attr("max")));
      }
      eer_tickets_price_recount_row($(this).closest(".eer-ticket-to-buy"), $(this).closest(".eer-tickets-sale-wrapper"));
    }).on("change", "input.choose_partner", function () {
      if ($(this).is(":checked")) {
        var partner_input_box = $(this).closest(".eer-ticket-student-info").find(".eer-info-row.dancing-with-email");
        if ($(this).val() === "0") {
          partner_input_box.hide();
          partner_input_box.find(".eer-info-row-input.dancing-with").prop("required", false);
        } else {
          partner_input_box.show();
          partner_input_box.find(".eer-info-row-input.dancing-with").prop("required", true);
        }

        var partner_name_input_box = $(this).closest(".eer-ticket-student-info").find(".eer-info-row.dancing-with-name");
        if ($(this).val() === "0") {
          partner_name_input_box.hide();
          partner_name_input_box.find(".eer-info-row-input.dancing-with").prop("required", false);
        } else {
          partner_name_input_box.show();
          if (partner_name_input_box.data("required") === 1) {
            partner_name_input_box.find(".eer-info-row-input.dancing-with-name").prop("required", true);
          }
        }
      }
    }).on("change", ".eer-info-row-input.eer-levels", function () {
      var option = $(this).find("option:selected");
      var parent = $(this).closest(".eer-ticket-student-info");
      parent.find(".eer-dancing-as-input").prop("disabled", false).parent().show();
      if (!option.hasClass(".eer-default")) {
        if (option.data("leaders") === 0) {
          var option_leader = parent.find(".eer-dancing-as-input.eer-leader");
          option_leader.prop("disabled", true).prop("checked", false).parent().hide();
        }
        if (option.data("followers") === 0) {
          var option_folower = parent.find(".eer-dancing-as-input.eer-follower");
          option_folower.prop("disabled", true).prop("checked", false).parent().hide();
        }
      }
    }).on("change", ".eer-update-price", function () {
      eer_tickets_final_price_recount($(this).closest(".eer-tickets-sale-wrapper"));
    }).on("submit", "#eer-ticket-shop-form", function (e) {
      e.preventDefault();
      var form = $(this);
      var sale_wrapper = $(this).closest(".eer-tickets-sale-wrapper");
      var spinner = eer_run_spinner(sale_wrapper);
      form.find(".eer-error:not(.eer-form-tickets)").remove(); //Remove old errors
      var order_data = {};

      order_data["tickets"] = {};
      if (form.find(".eer-form-tickets .eer-ticket-to-buy").size() > 0) {
        form.find(".eer-form-tickets .eer-ticket-to-buy").each(function (key, row) {
          var ticket_id = $(row).data("id");
          order_data["tickets"][ticket_id] = {};
          order_data["tickets"][ticket_id]["ticket_id"] = ticket_id;
          order_data["tickets"][ticket_id]["number_of_tickets"] = $(row).find("input[name=number_of_tickets]").val();

          if ($(row).find(".eer-dancing-as-input").length !== 0) {
            order_data["tickets"][ticket_id]["dancing_as"] = $(row).find(".eer-dancing-as-input:checked").val();
          }

          if ($(row).find("[name=level_id]").length !== 0) {
            order_data["tickets"][ticket_id]["level_id"] = $(row).find("[name=level_id]").val();
          }

          if ($(row).find(".eer-choose-partner-input").length !== 0) {
            order_data["tickets"][ticket_id]["choose_partner"] = $(row).find(".eer-choose-partner-input:checked").val();
            order_data["tickets"][ticket_id]["dancing_with"] = $(row).find("[name=dancing_with]").val();

            if ($(row).find(".eer-info-row.dancing-with-name").length !== 0) {
              order_data["tickets"][ticket_id]["dancing_with_name"] = $(row).find("[name=dancing_with_name]").val();
            }
          }
        });

        //load user data
        order_data["user_info"] = {};
        form.find(".eer-user-form input, .eer-user-form select, .eer-user-form textarea").each(function (key, input) {
          var value = $(input).val();
          if ($(input).attr("type") === "checkbox") {
            if (!$(input).hasClass("eer-object-input")) {
              value = $(input).prop("checked");
            } else {
              if ($(input).prop("checked")) {
                if (order_data["user_info"][$(input).attr("name")] === undefined) {
                  var obj = {};
                  obj[value] = value;
                  order_data["user_info"][$(input).attr("name")] = obj;
                } else {
                  order_data["user_info"][$(input).attr("name")][value] = value;
                }
              }

              return;
            }
          }
          order_data["user_info"][$(input).attr("name")] = value;

        });
        //send ajax
        order_data["event_id"] = form.find("input[name=event_id]").val();
        var order_data_json = JSON.stringify(order_data);
        var data = {
          "action": "eer_process_order",
          "order_data": order_data_json
        };

        /** global: eer_ajax_object */
        $.post(eer_ajax_object.ajaxurl, data, function (response) {
        }).done(function (response) {
          eer_stop_spinner(spinner, sale_wrapper);
          if (response.hasOwnProperty("thank_you_text")) {
            sale_wrapper.find(".eer-ticket-shop-form").remove();
            sale_wrapper.find(".eer-tickets").empty().append(response.thank_you_text);
            $("html, body").animate({
              scrollTop: sale_wrapper.find(".eer-thank-you").offset().top - 50
            }, 2000);
          } else if (response.hasOwnProperty("errors")) {
            $.each(response.errors.errors, function (key, message) {
              var keys = key.split(".");
              if (keys[0] === "user_info") {
                sale_wrapper.find("[name=" + keys[1] + "]").after("<div class=\"eer-error\">" + message[0] + "</div>");
              } else if (keys[0] === "tickets") {
                if (keys[1] === "all") {

                } else {
                  sale_wrapper.find(".eer-ticket-to-buy[data-id=" + keys[1] + "]").append("<div class=\"eer-error\">" + message[0] + "</div>");
                }
                if (keys[2] === "full") {
                  //$(".eer-ticket[data-id=" + keys[1] + "]").addClass('eer-sold');
                }
              }
            });
            $("html, body").animate({
              scrollTop: sale_wrapper.find(".eer-form-tickets").offset().top - 50
            }, 2000);
          }
        });
      } else {
        form.find(".eer-form-tickets").text(form.data("no-tickets")).addClass("eer-error");
        $("html, body").animate({
          scrollTop: sale_wrapper.find(".eer-form-tickets").offset().top - 50
        }, 2000);
        eer_stop_spinner(spinner, sale_wrapper);
      }
    });

    function eer_tickets_price_recount_row(row, sale_wrapper) {
      var number_of_tickets = $(row).find(".eer-number-of-tickets input[type=number]").val();
      var price_row = $(row).find(".eer-price");
      var price = price_row.data("price");

      $(row).find(".eer-price").text(eer_price_template((isNaN(price) ? 0 : price) * (isNaN(number_of_tickets) ? 1 : number_of_tickets), sale_wrapper));
      eer_tickets_final_price_recount(sale_wrapper);
    }

    function eer_tickets_final_price_recount(sale_wrapper) {
      var final_sum = 0;

      sale_wrapper.find(".eer-ticket-shop-form .eer-ticket-to-buy").each(function () {
        var number_of_tickets = $(this).find(".eer-number-of-tickets input[type=number]").val();

        var price_row = $(this).find(".eer-price");
        var price = price_row.data("price");
        final_sum += (isNaN(price) ? 0 : price) * (isNaN(number_of_tickets) ? 1 : number_of_tickets);
      });

      sale_wrapper.find(".eer-update-price").each(function () {
        var price = $(this).find("option:selected").data("price");
        final_sum += isNaN(price) ? 0 : price;
      });

      var finalPrice = sale_wrapper.find(".eer-final-price .eer-final-price-value");
      sale_wrapper.data("final-price", final_sum);
      finalPrice.text(eer_price_template(final_sum, sale_wrapper));
    }

    function eer_price_template(price, sale_wrapper) {
      var price_template = sale_wrapper.find(".eer-form-tickets").data("price-template");
      return price_template.replace("[price]", price);
    }

    function eer_run_spinner(wrapper) {
      var opts = {
        lines: 12, // The number of lines to draw
        length: 30, // The length of each line
        width: 17, // The line thickness
        radius: 45, // The radius of the inner circle
        scale: 1, // Scales overall size of the spinner
        corners: 1, // Corner roundness (0..1)
        color: "#ffffff", // CSS color or array of colors
        fadeColor: "transparent", // CSS color or array of colors
        speed: 0.7, // Rounds per second
        rotate: 0, // The rotation offset
        animation: "spinner-line-fade-quick", // The CSS animation name for the lines
        direction: 1, // 1: clockwise, -1: counterclockwise
        zIndex: 2e9, // The z-index (defaults to 2000000000)
        className: "spinner", // The CSS class to assign to the spinner
        top: "80%", // Top position relative to parent
        left: "50%", // Left position relative to parent
        shadow: "0 0 1px transparent", // Box-shadow for the lines
        position: "absolute" // Element positioning
      };

      var spinner = new Spinner(opts).spin();
      $(".eer-spinner-bg", wrapper).show();
      $(wrapper).append(spinner.el);
      return spinner;
    }

    function eer_stop_spinner(spinner, wrapper) {
      spinner.stop();
      $(".eer-spinner-bg", wrapper).hide();
    }
  });
});
