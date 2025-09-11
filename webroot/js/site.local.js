// JavaScript Document
var csrfToken = $('meta[name="csrfToken"]').attr('content');
$(document).ajaxSend(function (event, jqxhr, settings){jqxhr.setRequestHeader('X-CSRF-Token', csrfToken)});