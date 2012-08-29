
$ ->

  pseudo_dialog_button = (attributes)->
    $("<a href=\"#\" title=\"#{attributes.title}\"><span class=\"ui-icon ui-icon-#{attributes.icon}\">
    #{attributes.title}</span></a>")
      .addClass("ui-dialog-titlebar-#{attributes.id} ui-corner-all")
      .attr('role', 'button')
      .hover(
        ()->
          $(this).toggleClass 'ui-state-hover'
        ,
        ()->
          $(this).toggleClass 'ui-state-hover'
      )
      .focus(
        ()->
          $(this).toggleClass 'ui-state-focus'
      )
      .blur(
        ()->
          $(this).toggleClass 'ui-state-focus'
      )
      .click(
        (event)->
          event.preventDefault()
          $(this).trigger 'blur'
          $(this).removeClass 'ui-state-focus'
          attributes.click this
      )

  $.fn.pseudo_dialog = (options)->

    defaults = {
      title: '&#160;',
      resizable: null,
      icon: null,
      cookie_id: null,
      indetifier: null,
      actions: {
        closable: false,
        close: ()->
            return
        ,
        minimizable: true,
        minimized: ()->
          return
        ,
        maximized: ()->
          return
        ,
        configurable: false,
        configuration: (element, button)->

          menu = $('ul.configure', element)
          return if ! menu.length or ! dialog_content.is ':visible'
          return menu.hide() if menu.is ':visible'

          position = button.parent().position()
          top = position.top + button.width() + 3
          left = button.position().left
          menu_window_size = left + menu.outerWidth()

          diff = (menu_window_size + left) - window.outerWidth
          diff = left - Math.abs(diff)
          if diff > menu.outerWidth()
            left = left - menu.outerWidth() + button.width()
            menu_window_size = left + menu.outerWidth()

          #unfortunately hack (but $(window).width() couldn't use)
          window_width = $('body .wrapper #content').width()

          if window_width < menu_window_size
            left = left - menu.width() + button.width()

          menu.css 'top', top
          menu.css 'left', left
          menu.toggle 500
          $('a', menu).click ()->
            menu.hide 500
          $('li', menu).hover(()->
            $(this).addClass('hover')
          ,
          ()->
            $(this).removeClass('hover')
          )
      },
    }

    buttons_width = 0
    options = $.extend true, defaults, options
    actions = options.actions

    $(this).wrap $('<div></div>')
    dialog_content = $(this).parent()
    dialog_content.wrap $('<div></div>')
    pseudo_dialog = dialog_content.parent()

    pseudo_dialog.addClass 'ui-pseudo-dialog ui-widget
    ui-widget-content ui-corner-all'

    dialog_content.addClass('ui-dialog-content ui-widget-content')

    title_bar = $("<div></div>").addClass('ui-dialog-titlebar ui-widget-header
    ui-corner-all ui-helper-clearfix').prependTo pseudo_dialog

    title_text = options.title

    dialog_title = $('<span></span>')
      .addClass('ui-dialog-title')
      .html(title_text)
      .css('max-width', $(this).width() - buttons_width)
      .prependTo(title_bar)
      .attr('title', title_text)

    tabs = pseudo_dialog.closest '.ui-tabs'

    fix_graph = ()->
      # hack to fix chart width. when chart was created or recreated in hidden dialog content
      chart_container = $('div.highcharts-container', dialog_content)
      return if ! chart_container.length
      if chart_container.width() < pseudo_dialog.width()
        # hack after updating highcharts to 2.1
        dialog_content.width pseudo_dialog.width()
        chart_id = chart_container.parent().attr('id')
        chart = charts[chart_id]
        return if ! chart
        chart.setSize(
          dialog_content.width(),
          dialog_content.height(),
          false
        );

    fix_jqgrid = ()->
      jqgrid_table = $('.ui-jqgrid',pseudo_dialog)
      if jqgrid_table.length
        container = pseudo_dialog.parent()
        width = dialog_content.width()
        $('table.ui-jqgrid-btable',jqgrid_table).jqGrid 'setGridWidth', width

    if tabs.length
      tabs.bind 'tabsshow', ()->
        fix_jqgrid()
        fix_graph()

    fix_jqgrid()

    if options.cookie_id
      cookie_id = $.cookie options.cookie_id
      pseudo_dialog.hide() if cookie_id

    if options.icon
      title_bar.prepend "<img src=\"#{options.icon}\" width=\"12\" height=\"12\"/>"

    #show close button
    if options.actions.closable
      title_bar.append(pseudo_dialog_button {
          title: 'close',
          id: 'close',
          icon: 'closethick',
          click: ()->
            tiptip = $('div#tiptip_holder')
            if tiptip.is(':visible')
              #just in case make sure if it's realy tooltip for this button
              if $('#tiptip_content',tiptip).text() is this.title
                tiptip.hide()
            actions.close() if actions.close
            if options.cookie_id
              $.cookie options.cookie_id, options.cookie_id ,{ expires: 456 }
            pseudo_dialog.remove()
        }
      )

    element = $(this)
    #show minimize button
    if options.actions.minimizable
      buttons_width += 30
      title_bar.append(pseudo_dialog_button {
          title: 'minimize',
          id: 'minimize',
          icon: 'minimize',
          click: (button)->
            $('span:first',button)
              .toggleClass('ui-icon-minimize')
              .toggleClass 'ui-icon-miximize'

            console.log 'min'
            dialog_content.animate {
            opacity: 'toggle',
            height: 'toogle',
            }, 500
            pseudo_dialog.css 'height', ''
            pseudo_dialog.find('.ui-resizable-handle').toggle()

            visible = dialog_content.is ':visible'
            if visible and actions.maximized
              actions.maximized element
              fix_graph()

            actions.minimized element if visible and actions.minimized
        }
      )

    #show configure button
    if actions.configurable
      buttons_width += 30
      configure_button = pseudo_dialog_button {
          title: 'configure',
          id: 'configure',
          icon: 'configure',
          click: ()->
            actions.configuration element, configure_button if actions.configuration
        }
      title_bar.append configure_button

    if options.indetifier
      buttons_width += 30
      help_button = pseudo_dialog_button {
          title: 'loading',
          id: 'help',
          icon: 'help',
          click: ()->
            title = help_button.data 'title'
            if !  title.langth
              title = options.title
            dialog_wrapper = $("<div class=\"help_popup_dialog\"></div>").dialog {
              modal: true,
              width: "60%",
              title: title,
              buttons: [
                {
                  title: 'close',
                  text: 'close',
                  click: ()->
                    $( this ).dialog  'close'
                }
              ],
              minHeight: 70,
            }
            content = help_button.data 'content'
            if ! content.length
              content = $('p').text 'sorry_but_there_is_no_further_information'
            dialog_wrapper.html content
        }
      title_bar.append help_button
      $.get("#{url_base}site_help/get",{identifier:options.indetifier}, (data)->
        brief = data.brief || ''
        title = data.title || ''
        content = data.content || ''
        help_button.attr 'title', brief
        help_button.data 'content', content
        help_button.data 'title', title
      )

    resizable_options = {
      minWidth: 260,
      minHeight: 160,
      zIndex: 99599,
      ghost: true,
      resize: (event, ui)->
        width = ui.size.width - buttons_width - 20
        dialog_title.css 'max-width', width
        pseudo_resize = $(this).resizable 'option', 'pseudo_resize'
        chart_container = $('div.highcharts-container', dialog_content)
        if chart_container.length
          # hack after updating highcharts to 2.1
          $(window).resize()

        configure_menu = $('ul.configure', dialog_content)
        if configure_menu.length and configure_menu.is ':visible'
          configure_menu.hide()

        pseudo_resize event, ui if typeof pseudo_resize is 'function'
    }

    if options.resizable
      buttons_width += 30
      resizable_options = $.extend true, resizable_options, options.resizable
      pseudo_dialog.resizable resizable_options

