(function() {

  $(function() {
    var check_date_format, date_alert;
    date_alert = function() {
      $.get("" + url_base + "/alerts/date_format", {}, function(html) {
        return $("body > .container").prepend(html);
      });
      return console.log('alert');
    };
    check_date_format = function(date) {
      var expr, reg_expr, _i, _len;
      reg_expr = [/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/, /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/];
      for (_i = 0, _len = reg_expr.length; _i < _len; _i++) {
        expr = reg_expr[_i];
        if (expr.test(date)) return true;
      }
      date_alert();
      return false;
    };
    $("#go-today").on('click', function() {
      return load_calendar();
    });
    $("#go-today").trigger("click");
    return $("#go-to-date").on("click", function() {
      var date, url;
      date = $('input', $(this).parent()).val();
      date = date.trim();
      if (!check_date_format(date)) return;
      $(".dateformat-alert").remove();
      url = "" + url_base + "calendar/?date=" + date;
      return load_calendar(url);
    });
  });

}).call(this);