
$ ->

  date_alert = ()->
    $.get "#{url_base}/alerts/date_format", {}, (html)->
      $("body > .container").prepend html
    console.log 'alert'

  check_date_format = (date)->
    reg_expr = [
      /^[0-9]{2}-[0-9]{2}-[0-9]{4}$/,
      /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/
    ]
    for expr in reg_expr
      if expr.test date
        return true
    date_alert()
    return false

  $("#go-today").on 'click', ()->
    load_calendar()

  $("#go-today").trigger "click"

  $("#go-to-date").on "click", ()->
    date = $('input' ,$(this).parent()).val()
    date = date.trim()
    return if ! check_date_format date
    $(".dateformat-alert").remove()
    url = "#{url_base}calendar/?date=#{date}"
    load_calendar url