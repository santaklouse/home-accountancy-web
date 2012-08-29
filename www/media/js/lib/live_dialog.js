(function() {
  var root;

  root = typeof exports !== "undefined" && exports !== null ? exports : this;

  $(function() {
    $.fn.live_dialog = function(options) {
      var create_wrapper, defaults;
      defaults = {
        title: '',
        width: '50%',
        modal: true,
        buttons: [],
        on_open: null,
        close: null,
        closeOnEscape: true
      };
      options = $.extend(defaults, options);
      create_wrapper = function() {
        return $("<div class=\"popup_dialog\"></div>").dialog({
          modal: options.modal,
          width: options.width,
          title: options.title,
          buttons: options.buttons,
          close: function() {
            $(this).destroy_ckeditor();
            if (options.close) options.close();
            $(this).dialog('destroy');
            return $(this).remove();
          },
          autoOpen: false,
          minHeight: 70
        });
      };
      $(this).live('click', function(e) {
        var dialog_buttons, dialog_wrapper;
        dialog_wrapper = create_wrapper();
        dialog_buttons = dialog_wrapper.parent().find('.ui-dialog-buttonset button');
        dialog_buttons.hide();
        dialog_wrapper.dialog('option', 'position', 'center');
        dialog_wrapper.dialog('open');
        dialog_wrapper.loading_icon('large', true);
        dialog_wrapper.on('keydown', function(event) {
          var input;
          if ($(event.target).is('textarea')) return;
          if (event.keyCode === $.ui.keyCode.ENTER) {
            input = $(':input[value=""]:visible:first', dialog_wrapper);
            if (input.length) {
              if (input.is('select')) {
                return input.prev('.sb').find('.display').trigger('mouseup');
              }
              return input.focus();
            }
            return dialog_wrapper.parent().find('button:first').click();
          }
        });
        $.get($(this).attr('href'), function(data) {
          dialog_wrapper.html(data);
          dialog_wrapper.dialog('option', 'position', 'center');
          $(':input:visible:first', dialog_wrapper).focus();
          dialog_buttons.toggle(500);
          $('form', dialog_wrapper).submit(function(e) {
            return false;
          });
          dialog_wrapper.custom_select();
          if (options.on_open) return options.on_open(dialog_wrapper);
        });
        return e.preventDefault();
      });
      return this;
    };
    $.fn.live_submit = function(options) {
      var defaults, params, self, the_form;
      defaults = {
        success: null,
        failure: null
      };
      options = $.extend(defaults, options);
      the_form = $(this).find('form:first');
      self = $(this);
      params = {
        success: function(response_data) {
          self.destroy_ckeditor();
          if (!response_data.length) {
            if (options.success) options.success(self);
            return;
          }
          self.html(response_data);
          self.custom_select();
          if (options.failure) return options.failure(self);
        }
      };
      self.destroy_ckeditor();
      the_form.ajaxSubmit(params);
      return this;
    };
    $.fn.live_confirm = function(options) {
      var action_url, defaults, dialog_wrapper, link;
      defaults = {
        title: 'remove_record',
        message: 'are_you_sure_you_want_to_remove_this_record_?',
        width: 300,
        modal: true,
        confirm: null,
        cancel: null,
        failure: null,
        data: {},
        extraData: null,
        reqType: 'DELETE',
        dataRequired: false,
        clearData: false
      };
      options = $.extend(defaults, options);
      action_url = '';
      link = null;
      dialog_wrapper = $("<div class=\"popup_dialog\"></div>").dialog({
        modal: options.modal,
        width: options.width,
        title: options.title,
        buttons: [
          {
            title: 'yes',
            text: 'yes',
            click: function() {
              var self;
              self = $(this);
              return $.ajax({
                url: action_url,
                data: options.data,
                type: options.reqType,
                success: function(data) {
                  if (!data.length) {
                    self.dialog('close');
                    if (options.confirm) options.confirm(dialog_wrapper, link);
                    return;
                  }
                  if (options.failure) options.failure(self);
                  if (data) {
                    return self.prepend("<div class=\"form-alert\">" + data + "</div>");
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  if (options.failure) return options.failure(self);
                }
              });
            }
          }, {
            title: 'no',
            text: 'no',
            click: function() {
              if (options.cancel) options.cancel(dialog_wrapper);
              return $(this).dialog('close');
            }
          }
        ],
        autoOpen: false
      });
      $(this).live('click', function(e) {
        e.preventDefault();
        link = $(this);
        if (options.clearData) options.data = {};
        if (options.extraData) {
          options.data = $.extend(options.data, options.extraData());
        }
        if (options.dataRequired === true && jQuery.isEmptyObject(options.data)) {
          return;
        }
        action_url = $(this).attr('href');
        dialog_wrapper.html(options.message);
        return dialog_wrapper.dialog('open');
      });
      return this;
    };
    return $.fn.custom_select = function() {
      return $('select', this).each(function() {
        $(this).before('<div class="clear"></div>');
        $(this).sb({});
        return $(this).prev('.selectbox').width('100%');
      });
    };
  });

}).call(this);
