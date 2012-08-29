
root = exports ? this
$ ->

  root.auto_slide_control = $('.slide-control button')
  root.auto_slide_control.click ()->
    if $(this).hasClass 'btn-success'
      $(this).removeClass 'btn-success'
      $(this).addClass 'btn-danger'
      return $(this).text 'auto_slide_off'
    $(this).removeClass 'btn-danger'
    $(this).addClass 'btn-success'
    $(this).text 'auto_slide_on'

