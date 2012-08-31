
root = exports ? this

$ ->

  root.__ = (str, first_letter_capitalize = true)->
    str = I18n.t str
    if first_letter_capitalize
      str = I18n.c str
    str