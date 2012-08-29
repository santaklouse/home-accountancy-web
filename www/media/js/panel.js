(function() {
  var root;

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

  $(function() {
    var pseudo_dialog_button;
    pseudo_dialog_button = function(attributes) {
      return $("<a href=\"#\" title=\"" + attributes.title + "\"><span class=\"ui-icon ui-icon-" + attributes.icon + "\">    " + attributes.title + "</span></a>").addClass("ui-dialog-titlebar-" + attributes.id + " ui-corner-all").attr('role', 'button').hover(function() {
        return $(this).toggleClass('ui-state-hover');
      }, function() {
        return $(this).toggleClass('ui-state-hover');
      }).focus(function() {
        return $(this).toggleClass('ui-state-focus');
      }).blur(function() {
        return $(this).toggleClass('ui-state-focus');
      }).click(function(event) {
        event.preventDefault();
        $(this).trigger('blur');
        $(this).removeClass('ui-state-focus');
        return attributes.click(this);
      });
    };
    return $.fn.pseudo_dialog = function(options) {
      var actions, buttons_width, configure_button, cookie_id, defaults, dialog_content, dialog_title, element, fix_graph, fix_jqgrid, help_button, pseudo_dialog, resizable_options, tabs, title_bar, title_text;
      defaults = {
        title: '&#160;',
        resizable: null,
        icon: null,
        cookie_id: null,
        indetifier: null,
        actions: {
          closable: false,
          close: function() {},
          minimizable: true,
          minimized: function() {},
          maximized: function() {},
          configurable: false,
          configuration: function(element, button) {
            var diff, left, menu, menu_window_size, position, top, window_width;
            menu = $('ul.configure', element);
            if (!menu.length || !dialog_content.is(':visible')) return;
            if (menu.is(':visible')) return menu.hide();
            position = button.parent().position();
            top = position.top + button.width() + 3;
            left = button.position().left;
            menu_window_size = left + menu.outerWidth();
            diff = (menu_window_size + left) - window.outerWidth;
            diff = left - Math.abs(diff);
            if (diff > menu.outerWidth()) {
              left = left - menu.outerWidth() + button.width();
              menu_window_size = left + menu.outerWidth();
            }
            window_width = $('body .wrapper #content').width();
            if (window_width < menu_window_size) {
              left = left - menu.width() + button.width();
            }
            menu.css('top', top);
            menu.css('left', left);
            menu.toggle(500);
            $('a', menu).click(function() {
              return menu.hide(500);
            });
            return $('li', menu).hover(function() {
              return $(this).addClass('hover');
            }, function() {
              return $(this).removeClass('hover');
            });
          }
        }
      };
      buttons_width = 0;
      options = $.extend(true, defaults, options);
      actions = options.actions;
      $(this).wrap($('<div></div>'));
      dialog_content = $(this).parent();
      dialog_content.wrap($('<div></div>'));
      pseudo_dialog = dialog_content.parent();
      pseudo_dialog.addClass('ui-pseudo-dialog ui-widget\
    ui-widget-content ui-corner-all');
      dialog_content.addClass('ui-dialog-content ui-widget-content');
      title_bar = $("<div></div>").addClass('ui-dialog-titlebar ui-widget-header\
    ui-corner-all ui-helper-clearfix').prependTo(pseudo_dialog);
      title_text = options.title;
      dialog_title = $('<span></span>').addClass('ui-dialog-title').html(title_text).css('max-width', $(this).width() - buttons_width).prependTo(title_bar).attr('title', title_text);
      tabs = pseudo_dialog.closest('.ui-tabs');
      fix_graph = function() {
        var chart, chart_container, chart_id;
        chart_container = $('div.highcharts-container', dialog_content);
        if (!chart_container.length) return;
        if (chart_container.width() < pseudo_dialog.width()) {
          dialog_content.width(pseudo_dialog.width());
          chart_id = chart_container.parent().attr('id');
          chart = charts[chart_id];
          if (!chart) return;
          return chart.setSize(dialog_content.width(), dialog_content.height(), false);
        }
      };
      fix_jqgrid = function() {
        var container, jqgrid_table, width;
        jqgrid_table = $('.ui-jqgrid', pseudo_dialog);
        if (jqgrid_table.length) {
          container = pseudo_dialog.parent();
          width = dialog_content.width();
          return $('table.ui-jqgrid-btable', jqgrid_table).jqGrid('setGridWidth', width);
        }
      };
      if (tabs.length) {
        tabs.bind('tabsshow', function() {
          fix_jqgrid();
          return fix_graph();
        });
      }
      fix_jqgrid();
      if (options.cookie_id) {
        cookie_id = $.cookie(options.cookie_id);
        if (cookie_id) pseudo_dialog.hide();
      }
      if (options.icon) {
        title_bar.prepend("<img src=\"" + options.icon + "\" width=\"12\" height=\"12\"/>");
      }
      if (options.actions.closable) {
        title_bar.append(pseudo_dialog_button({
          title: 'close',
          id: 'close',
          icon: 'closethick',
          click: function() {
            var tiptip;
            tiptip = $('div#tiptip_holder');
            if (tiptip.is(':visible')) {
              if ($('#tiptip_content', tiptip).text() === this.title) {
                tiptip.hide();
              }
            }
            if (actions.close) actions.close();
            if (options.cookie_id) {
              $.cookie(options.cookie_id, options.cookie_id, {
                expires: 456
              });
            }
            return pseudo_dialog.remove();
          }
        }));
      }
      element = $(this);
      if (options.actions.minimizable) {
        buttons_width += 30;
        title_bar.append(pseudo_dialog_button({
          title: 'minimize',
          id: 'minimize',
          icon: 'minimize',
          click: function(button) {
            var visible;
            $('span:first', button).toggleClass('ui-icon-minimize').toggleClass('ui-icon-miximize');
            dialog_content.toggle(500);
            pseudo_dialog.css('height', '');
            pseudo_dialog.find('.ui-resizable-handle').toggle();
            visible = dialog_content.is(':visible');
            if (visible && actions.maximized) {
              actions.maximized(element);
              fix_graph();
            }
            if (visible && actions.minimized) return actions.minimized(element);
          }
        }));
      }
      if (actions.configurable) {
        buttons_width += 30;
        configure_button = pseudo_dialog_button({
          title: 'configure',
          id: 'configure',
          icon: 'configure',
          click: function() {
            if (actions.configuration) {
              return actions.configuration(element, configure_button);
            }
          }
        });
        title_bar.append(configure_button);
      }
      if (options.indetifier) {
        buttons_width += 30;
        help_button = pseudo_dialog_button({
          title: 'loading',
          id: 'help',
          icon: 'help',
          click: function() {
            var content, dialog_wrapper, title;
            title = help_button.data('title');
            if (!title.langth) title = options.title;
            dialog_wrapper = $("<div class=\"help_popup_dialog\"></div>").dialog({
              modal: true,
              width: "60%",
              title: title,
              buttons: [
                {
                  title: 'close',
                  text: 'close',
                  click: function() {
                    return $(this).dialog('close');
                  }
                }
              ],
              minHeight: 70
            });
            content = help_button.data('content');
            if (!content.length) {
              content = $('p').text('sorry_but_there_is_no_further_information');
            }
            return dialog_wrapper.html(content);
          }
        });
        title_bar.append(help_button);
        $.get("" + url_base + "site_help/get", {
          identifier: options.indetifier
        }, function(data) {
          var brief, content, title;
          brief = data.brief || '';
          title = data.title || '';
          content = data.content || '';
          help_button.attr('title', brief);
          help_button.data('content', content);
          return help_button.data('title', title);
        });
      }
      resizable_options = {
        minWidth: 260,
        minHeight: 160,
        zIndex: 99599,
        ghost: true,
        resize: function(event, ui) {
          var chart_container, configure_menu, pseudo_resize, width;
          width = ui.size.width - buttons_width - 20;
          dialog_title.css('max-width', width);
          pseudo_resize = $(this).resizable('option', 'pseudo_resize');
          chart_container = $('div.highcharts-container', dialog_content);
          if (chart_container.length) $(window).resize();
          configure_menu = $('ul.configure', dialog_content);
          if (configure_menu.length && configure_menu.is(':visible')) {
            configure_menu.hide();
          }
          if (typeof pseudo_resize === 'function') return pseudo_resize(event, ui);
        }
      };
      if (options.resizable) {
        buttons_width += 30;
        resizable_options = $.extend(true, resizable_options, options.resizable);
        return pseudo_dialog.resizable(resizable_options);
      }
    };
  });

  root = typeof exports !== "undefined" && exports !== null ? exports : this;

  $(function() {
    var current_date, dialog_wrapper, reload_day_events, table_cell;
    $("div#calendar-container").pseudo_dialog({
      title: 'calendar'
    });
    current_date = null;
    table_cell = null;
    root.load_calendar = function(url) {
      if (url == null) url = "" + url_base + "calendar";
      $("div#calendar-container").loading_icon();
      return $("div#calendar-container").load(url, {}, function(data) {
        $('table.calendar td').hover(function() {
          return $(this).toggleClass('hover', function() {
            return $(this).toggleClass('hover');
          });
        });
        return $('table.calendar caption span').hover(function() {
          return $(this).toggleClass('hover', function() {
            return $(this).toggleClass('hover');
          });
        });
      });
    };
    $('a#change_month').live('click', function(e) {
      e.preventDefault();
      return load_calendar($(this).attr('href'));
    });
    dialog_wrapper = null;
    reload_day_events = function() {
      $('ul', table_cell).remove();
      table_cell.append($('<span></span>').addClass('loading').loading_icon('small'));
      return $.get("" + url_base + "calendar_events/events_for_date", {
        date: current_date || table_cell.data('date')
      }, function(data) {
        $('span.loading', table_cell).remove();
        return table_cell.append(data);
      });
    };
    $('table.calendar td').live('click', function(e) {
      table_cell = $(this);
      current_date = $(this).data('date');
      if ($(e.target).is('a') || $(e.target).is('img')) return;
      if (!$('ul.event-list', table_cell).length) return;
      dialog_wrapper = $("<div class=\"detailed_events_dialog\"></div>").dialog({
        modal: true,
        width: 500,
        title: 'detailed_events_for_day'
      });
      dialog_wrapper.loading_icon('large', true);
      return dialog_wrapper.load("" + url_base + "calendar_events/events_for_date", {
        date: current_date,
        detailed: true
      }, function() {
        return $('ul li', dialog_wrapper).each(function() {
          var actions, text;
          if ($(this).hasClass('todo')) return;
          actions = $('span.actions', $(this));
          text = $('<div></div>').addClass('text').html($('a:first', $(this)).html());
          $(this).html('');
          $(this).append(actions);
          return $(this).append(text);
        });
      });
    });
    $("a.remove_event").live_confirm({
      confirm: function(dialog_wrapper, link) {
        var current_event, dialog;
        dialog = link.closest('.ui-dialog:visible');
        current_event = link.closest('li');
        current_event.remove();
        if (dialog.length) return reload_day_events();
      }
    });
    $("a.remove_spacial_day").live_confirm({
      confirm: function(dialog_wrapper, link) {
        var current_event, day_events, dialog, special_day, type_id;
        current_event = link.closest('li');
        dialog = link.closest('.ui-dialog:visible');
        day_events = link.closest('ul.event-list');
        current_event.remove();
        special_day = $('li.special_day:first', day_events);
        if (special_day.length) type_id = special_day.data('type-id');
        table_cell.attr('id', 'special-day-' + type_id);
        if (dialog.length) return reload_day_events();
      }
    });
    return $('a.live_event_for_date').live_dialog({
      title: 'add_or_edit_day_events',
      width: '40%',
      modal: true,
      buttons: [
        {
          title: 'save',
          text: 'save',
          click: function() {
            return $(this).live_submit({
              success: function(dialog_widget) {
                var select, type_id;
                select = $('select[name="Calendar[type_id]"]', dialog_widget);
                type_id = select.val();
                if (!table_cell.attr('id') && type_id > 0) {
                  table_cell.attr('id', 'special-day-' + type_id);
                }
                reload_day_events();
                return dialog_widget.dialog('close');
              },
              failure: function(dialog) {}
            });
          }
        }
      ]
    });
  });

}).call(this);
