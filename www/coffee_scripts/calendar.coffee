
root = exports ? this

$ ->
  $("div#calendar-container").pseudo_dialog {
    title: 'calendar'
  }

  current_date = null
  table_cell = null

  root.load_calendar = (url = "#{url_base}calendar")->
    $("div#calendar-container").loading_icon()
    $("div#calendar-container").load url, {}, (data)->
      $('td[title!=""], span[title!=""]', $(this)).tipTip()
      #after first load

  $('a#change_month').live 'click', (e)->
    e.preventDefault()
    load_calendar $(this).attr('href')

  dialog_wrapper = null

  reload_day_events = ()->
    $('ul', table_cell).remove()
    table_cell.append $('<span></span>').addClass('loading').loading_icon 'small'
    $.get "#{url_base}calendar_events/events_for_date",
      {
        date: current_date || table_cell.data('date'),
      },
      (data)->
        $('span.loading', table_cell).remove()
        table_cell.append data

  $('table.calendar td').live 'click', (e)->
    table_cell = $(this)
    current_date = $(this).data('date')
    return if $(e.target).is('a') || $(e.target).is('img')
    return if ! $('ul.event-list', table_cell).length

    dialog_wrapper = $("<div class=\"detailed_events_dialog\"></div>").dialog {
      modal: true,
      width: 500,
      title: 'detailed_events_for_day',
    }

    dialog_wrapper.loading_icon 'large', true
    dialog_wrapper.load "#{url_base}calendar_events/events_for_date",{
      date: current_date,
      detailed: true,
    },
    ()->
      $('ul li', dialog_wrapper).each ()->
        return if $(this).hasClass 'todo'
        actions = $('span.actions', $(this))
        text = $('<div></div>').addClass('text').html $('a:first', $(this)).html()
        $(this).html ''
        $(this).append actions
        $(this).append text

  $("a.remove_event").live_confirm {
    confirm: (dialog_wrapper, link)->
      dialog = link.closest('.ui-dialog:visible')
      current_event = link.closest 'li'
      current_event.remove()

      if dialog.length
        reload_day_events()
  }

  $("a.remove_spacial_day").live_confirm {
    confirm: (dialog_wrapper, link)->
      current_event = link.closest 'li'
      dialog = link.closest '.ui-dialog:visible'
      day_events = link.closest 'ul.event-list'

      current_event.remove()

      special_day = $('li.special_day:first', day_events)

      if special_day.length
        type_id = special_day.data('type-id')

      table_cell.attr('id', 'special-day-' + type_id )

      if dialog.length
        reload_day_events()
  }

  $('a.live_event_for_date').live_dialog {
    title: 'add_or_edit_day_events',
    width: '40%',
    modal: true,
    buttons: [
      {
        title: 'save',
        text: 'save',
        click: ->
          $(this).live_submit {
            success: (dialog_widget)->
              select = $('select[name="Calendar[type_id]"]', dialog_widget)
              type_id = select.val()
              if ! table_cell.attr('id') and type_id > 0
                table_cell.attr 'id', 'special-day-' + type_id
              reload_day_events()
              dialog_widget.dialog 'close'
            failure: (dialog)->

          }
      }
    ],
  }