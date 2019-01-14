function onClickTest(obj) {
    $(obj).attr('disabled',true);
    var par={host: $('#ldapHost').val(),
             dn: $('#ldapBindDN').val(),
             pwd: $('#ldapPassword').val(),
             proto: $('#ldapProto').val(),
             port: $('#ldapPort').val(),
             version: $('#ldapVersion').val()
        }
    $.post(createLink('ldap', 'test'),par, function(data) {
        alert_msgObj2=$(html)
        $(".ldap_setting").prepend(alert_msgObj2.append(data));
        setTimeout("alert_msgObj2.addClass('in');setTimeout('alert_msgObj2.alert(\"close\")',4000)",100)
        $(obj).removeAttr('disabled');
    });
}

function syncGroups(obj) {
    $(obj).attr('disabled',true);
    $.get(createLink('ldap', 'sync'), function(ret){
        msg=$.parseJSON(ret)
        alert_msgObj1=$(html)
        $(".ldap_setting").prepend(alert_msgObj1.append(msg.results));
        setTimeout("alert_msgObj1.addClass('in');setTimeout('alert_msgObj1.alert(\"close\")',4000)",100)
        $(obj).removeAttr('disabled');
    });
}
$(".ldap_testuser").click(function() {
    sub_form=$(".ldap_usertest_form");
    checkbool=false
    sub_form.find("input").each(function(e) {
        if($(this).val()=="") {
            $(this).focus().parent().addClass('has-error');
            checkbool=false
            return false;
        }else{
            $(this).parent().removeClass('has-error');
            checkbool=true
        }
    })
    if(!checkbool) return false;
    args=sub_form.serializeArray();
    _this=$(this)
    _this.attr('disabled',true)
    $.post(createLink('ldap', 'usertest'),args,function(msg) {
        testuser_msg=$(html)
        $("#showModuleModal .modal-header").prepend(testuser_msg.append(msg));
        setTimeout("testuser_msg.addClass('in');setTimeout('testuser_msg.alert(\"close\")',4000)",100)
        _this.removeAttr('disabled')// alert(msg);
    })
})
$("#ldapProto").change(function() {
    var val=$(this).val()
    var port=389
    if(val=='ldap') port=389
    if(val=='ldaps') port=636
    $("#ldapPort").val(port)
})
var html="<div class='alert alert-info fade' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button></div>";
$(document).ready(function () {
    alert_msgObj=$(html);
    if (message){
        $(".ldap_setting").prepend(alert_msgObj.append(message));
        setTimeout("alert_msgObj.addClass('in');setTimeout('alert_msgObj.alert(\"close\")',4000)",100)
    }
})