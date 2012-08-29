
$ ->
  $.fn.loading_icon = (style = 'large', css = false)->
    if css
      return $(this).html $('<div></div>')
      .attr('id','loading_icon')
      .addClass('loading_icon_' + style)

    return $(this).html $('<img>')
      .attr('src', "#{url_base}media/images/icons/loading-#{style}.gif")
      .attr('id','loading_icon')
      .addClass('loading_icon')
