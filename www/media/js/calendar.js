(function() {
  var root;

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
        return $('td[title!=""], span[title!=""]', $(this)).tipTip();
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
