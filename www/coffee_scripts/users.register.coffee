
$ ->

  $(".btn-group .btn").on "click", ()->
    language_id = $(this).data "id"
    $(".btn-group .languages-rgroup").each ()->
      $(this).removeAttr "checked"
    $(".btn-group .languages-rgroup[value=\"#{language_id}\"]").attr "checked", 'checked'