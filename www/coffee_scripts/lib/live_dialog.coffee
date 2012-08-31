
root = exports ? this

$ ->

  $.fn.live_dialog = (options)->

    defaults = {
      title: '',
      width: '50%',
      modal: true,
      buttons: [],
      on_open: null,
      close: null,
      closeOnEscape: true,
    }

    options = $.extend defaults, options

    create_wrapper = ->
      $("<div class=\"popup_dialog\"></div>").dialog {
        modal: options.modal,
        width: options.width,
        title: options.title,
        buttons: options.buttons,
        close: ->
          $(this).destroy_ckeditor()
          options.close() if options.close
          $(this).dialog 'destroy'
          $(this).remove()
        autoOpen: false,
        minHeight: 70,
      }


    $(this).live 'click', (e)->
      dialog_wrapper = create_wrapper()
      dialog_buttons = dialog_wrapper.parent().find('.ui-dialog-buttonset button')
      dialog_buttons.hide();
      dialog_wrapper.dialog 'option', 'position', 'center'
      dialog_wrapper.dialog 'open'
      dialog_wrapper.loading_icon 'large', true

      dialog_wrapper.on 'keydown', (event)->
        return if $(event.target).is 'textarea'
        if event.keyCode is $.ui.keyCode.ENTER
          input = $(':input[value=""]:visible:first', dialog_wrapper)
          if input.length
            if input.is 'select'
              return input.prev('.sb').find('.display').trigger 'mouseup'
            return input.focus()
          dialog_wrapper.parent().find('button:first').click()

      $.get $(this).attr('href'), (data)->
        dialog_wrapper.html data
        dialog_wrapper.dialog 'option', 'position', 'center'
        $(':input:visible:first',dialog_wrapper).focus()
        dialog_buttons.toggle 500
        #prevent form submittion on enter key
        $('form', dialog_wrapper).submit (e)->
          return false
        dialog_wrapper.custom_select()
        options.on_open(dialog_wrapper) if options.on_open
      e.preventDefault()
    this

  $.fn.live_submit = (options)->
    defaults = {
      success: null,
      failure: null,
    }
    options = $.extend defaults, options
    the_form = $(this).find('form:first')
    self = $(this)

    params = {
      success: (response_data)->
        self.destroy_ckeditor()
        if ! response_data.length
          options.success(self) if options.success
          return
        self.html response_data
        self.custom_select()
        if options.failure
          options.failure self
    }
    self.destroy_ckeditor()
    the_form.ajaxSubmit params
    this





  $.fn.live_confirm = (options)->
    defaults = {
      title: __('remove_record_?'),
      message: __('are_you_sure_you_want_to_remove_this_record_?'),
      width: 300,
      modal: true,
      confirm: null,
      cancel: null,
      failure: null,
      data: {},
      extraData: null,
      reqType: 'DELETE',
      dataRequired: false,
      clearData: false,
    }

    options = $.extend defaults, options
    action_url = ''

    link = null

    dialog_wrapper = $("<div class=\"popup_dialog\"></div>").dialog {
      modal: options.modal,
      width: options.width,
      title: options.title,
      buttons: [
        {
          title: __('yes'),
          text: __('yes'),
          click: ()->
            self = $(this)
            $.ajax {
              url: action_url,
              data: options.data,
              type: options.reqType,
              success: (data)->
                if ! data.length
                  self.dialog 'close'
                  options.confirm(dialog_wrapper, link) if options.confirm
                  return
                if options.failure
                  options.failure self
                if data
                  self.prepend "<div class=\"form-alert\">#{data}</div>"
              error : (jqXHR, textStatus, errorThrown)->
                if options.failure
                  options.failure self
            },
        },
        {
          title: __('no'),
          text: __('no'),
          click: ()->
            options.cancel(dialog_wrapper) if options.cancel
            $(this).dialog 'close'
        },
      ],
      autoOpen: false,
    }

    $(this).live 'click', (e)->
      e.preventDefault()
      link = $(this)
      options.data = {} if options.clearData
      if options.extraData
        options.data = $.extend options.data, options.extraData()
      return if options.dataRequired is true and jQuery.isEmptyObject options.data
      action_url = $(this).attr('href')
      dialog_wrapper.html options.message
      dialog_wrapper.dialog 'open'
    this

  $.fn.custom_select = ()->
    $('select', this).each ()->
      $(this).before '<div class="clear"></div>'
      $(this).sb {}
      $(this).prev('.selectbox').width '100%'

