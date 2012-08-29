(function() {

  $(function() {
    return $.fn.loading_icon = function(style, css) {
      if (style == null) style = 'large';
      if (css == null) css = false;
      if (css) {
        return $(this).html($('<div></div>').attr('id', 'loading_icon').addClass('loading_icon_' + style));
      }
      return $(this).html($('<img>').attr('src', "" + url_base + "media/images/icons/loading-" + style + ".gif").attr('id', 'loading_icon').addClass('loading_icon'));
    };
  });

}).call(this);
