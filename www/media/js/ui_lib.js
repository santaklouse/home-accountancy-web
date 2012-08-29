
/*
  script ....
  based on twitter bootstrap progress bars styles
*/

(function() {
  var defaultOptions, root;

  root = typeof exports !== "undefined" && exports !== null ? exports : this;

  $(function() {
    var defaultOptions;
    defaultOptions = {
      clearContent: true,
      active: true,
      striped: true,
      type: null,
      min: 0,
      max: 100,
      step: 10,
      timeout: 1000
    };
    return $.fn.pseudoAjaxLoadingProgress = function(options) {
      var currentVal, interval, progressBar, progressContainer;
      if (options == null) options = {};
      options = $.extend(true, defaultOptions, options);
      if (options.clearContent) $(this).html('');
      progressContainer = $('<div></div>').addClass('progress');
      progressBar = $('<div></div>').addClass('bar').css('width', "" + options.min + "%");
      if (options.active) progressContainer.addClass('active');
      if (options.striped) progressContainer.addClass('progress-striped');
      progressContainer.append(progressBar);
      $(this).append(progressContainer);
      currentVal = options.min;
      return interval = setInterval(function() {
        if (!progressBar.length) clearInterval(interval);
        currentVal = currentVal + options.step;
        if (currentVal > options.max) currentVal = options.min;
        return progressBar.css('width', "" + currentVal + "%");
      }, options.timeout);
    };
  });

  defaultOptions = {
    text: 'Oops some error occured',
    closable: true,
    append: false,
    type: 'alert-error'
  };

  $(function() {
    return $.fn.inlineAlert = function(options) {
      var alert, close;
      if (options == null) options = {};
      options = $.extend(defaultOptions, options);
      alert = $('<div></div>').addClass('alert');
      alert.addClass(options.type);
      if (options.closable) {
        close = $('<a></a>').addClass('close');
        close.attr('data-dismiss', 'alert');
        close.text('x');
        alert.append(close);
      }
      alert.append(options.text);
      if (options.append) {
        return $(this).append(alert);
      } else {
        return $(this).html(alert);
      }
    };
  });

}).call(this);
