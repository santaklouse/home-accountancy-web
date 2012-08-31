
$ ->
  $("a.inline_edit").live 'click', (e)->
    e.preventDefault();
    parent = $(this).parent();
    $(this).remove();
    fields = {
      'identifier': 'identifier',
      'language-id': 'language_id',
    }
    html = "<form class=\"submit_translation\" action=\"#{url_base}translations/update\" method=\"POST\">";
    for key,value of fields
      html += "<input name=\"#{value}\" type=\"hidden\" value=\"#{parent.data key}\"/>"
    html += "
    <textarea class=\"hidden\" name=\"old-translation\">#{parent.html()}</textarea>
    <textarea name=\"translation\">#{parent.html()}</textarea>
    <input type=\"submit\" value=\"#{__('save')}\"/>
    <span class=\"label label-inverse cancel\"><i class=\"icon-minus-sign\"></i> #{__('cancel')} </span>
    </form>"
    parent.html html
    $('textarea[name="translation"]', parent).resizable()

  $('form.submit_translation').live 'submit', (e)->
    self = $(this)
    area = self.closest "div.editable-area"
    self.ajaxSubmit( {
      success: ->
        hide_edit_form self
        area.append "<span class=\"ok\">ok</span>"
        area.find('span.ok').fadeOut(2000)
    })
    return false

  hide_edit_form = (object)->
    area = object.closest("div.editable-area")
    self = $ 'form.submit_translation', area
    text = $('textarea[name="old-translation"]', area).val()
    if ! text.trim()
      text = $('textarea[name="translation"]', area).val()
    self.remove()
    area.append "#{text}<a href=\"#\" class=\"inline_edit\">
      <span class=\"label label-warning\">
        <i class=\"icon-pencil\"></i>
        Edit
      </span>
    </a>"

  $("span.cancel").live "click", ()->
    hide_edit_form $(this)

  $('a.inline_remove').live_confirm {
    confirm: (something, link)->
      link.parents('tr').remove()
  }