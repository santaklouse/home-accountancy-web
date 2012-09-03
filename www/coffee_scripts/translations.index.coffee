
$ ->
  $("a.inline_edit").live 'click', (e)->
    e.preventDefault();
    parent = $(this).parent()
    translation_text = $("span.translation", parent).html()
    $(this).remove();
    fields = {
      'identifier': 'identifier',
      'language-id': 'language_id',
    }
    html = "<form class=\"submit_translation\" action=\"#{url_base}translations/update\" method=\"POST\">";
    for key,value of fields
      html += "<input name=\"#{value}\" type=\"hidden\" value=\"#{parent.data key}\"/>"
    html += "
    <textarea class=\"hidden\" name=\"old-translation\">#{translation_text}</textarea>
    <textarea name=\"translation\">#{translation_text}</textarea>
    <span class=\"label label-success save\"><i class=\"icon-ok\"></i> #{__('save')} </span>
    <span class=\"label label-inverse cancel\"><i class=\"icon-minus-sign\"></i> #{__('cancel')} </span>
    </form>"
    $("span.translation", parent).html html
    $('textarea[name="translation"]', parent).resizable()

  $("span.save").live "click", ()->
    $(this).closest("form").submit()

  $('form.submit_translation').live 'submit', (e)->
    self = $(this)
    area = self.closest "div.editable-area"
    self.ajaxSubmit {
      success: ->
        hide_edit_form self
        area.append "<span class=\"ok\">ok</span>"
        area.find('span.ok').fadeOut 900, ()->
          $(this).remove()
    }
    return false

  hide_edit_form = (object)->
    area = object.closest("div.editable-area")
    self = $ 'form.submit_translation', area
    text = $('textarea[name="old-translation"]', area).val()
    if ! text.trim()
      text = $('textarea[name="translation"]', area).val()
    area.html ""
    area.append "<span class=\"translation\">#{text}</span><a href=\"#\" class=\"inline_edit\">
      <span class=\"label label-warning\">
        <i class=\"icon-pencil\"></i>
        #{__('edit')}
      </span>
    </a>"

  $("span.cancel").live "click", ()->
    hide_edit_form $(this)

  $('a.inline_remove').live_confirm {
    confirm: (something, link)->
      link.parents('tr').remove()
  }

  translations_table = $ 'table#translations-table tbody'

  $(".btn-group.right a").on 'click', (event)->
    event.preventDefault()

  show_all = ()->
    $("tr", translations_table).show()

  SHOW_TRANSLATED = 1
  SHOW_NOT_TRANSLATED = 2
  MOVE_NOT_TRANSLATED = 3

  records_actions = (options)->
    show_all()
    $("tr", translations_table).each ()->
      self = $ this
      tr_obj = this
      $("td", self).each ()->
        length = $("span.translation", $(this)).length
        html = $("span.translation", $(this)).html()
        switch options.action
          when SHOW_TRANSLATED
            if length && ! html
              self.hide()
          when SHOW_NOT_TRANSLATED
            if length && html
              self.hide()
          when MOVE_NOT_TRANSLATED
            if length && ! html
              translations_table.prepend tr_obj.cloneNode true
              self.remove()
              return false

  $('#show-all').on 'click', ()->
    show_all()

  $('#show-translated').on 'click', ()->
    records_actions {
      action: SHOW_TRANSLATED
    }

  $('#show-not-translated').on 'click', ()->
    records_actions {
      action: SHOW_NOT_TRANSLATED
    }

  $("#move-not-translated-up").on 'click', ()->
    records_actions {
      action: MOVE_NOT_TRANSLATED
    }

