(function() {
  var root;

  root = typeof exports !== "undefined" && exports !== null ? exports : this;

  $(function() {
    root.auto_slide_control = $('.slide-control button');
    return root.auto_slide_control.click(function() {
      if ($(this).hasClass('btn-success')) {
        $(this).removeClass('btn-success');
        $(this).addClass('btn-danger');
        return $(this).text('auto_slide_off');
      }
      $(this).removeClass('btn-danger');
      $(this).addClass('btn-success');
      return $(this).text('auto_slide_on');
    });
  });

}).call(this);
