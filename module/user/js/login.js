// Prevent login page show in a iframe modal
if(window.self !== window.top) window.top.location.href = window.location.href;

$(document).ready(function()
{
    /* Fix bug for misc-ping */
    $('#hiddenwin').removeAttr('id');

    var $login = $('#login');
    var adjustPanelPos = function()
    {
        var bestTop = Math.max(0, Math.floor($(window).height() - $login.outerHeight())/2);
        $login.css('margin-top', bestTop);
    };
    adjustPanelPos();
    $(window).on('resize', adjustPanelPos);

    $('#account').focus();

    $("#langs li > a").click(function() 
    {
        selectLang($(this).data('value'));
    });
});