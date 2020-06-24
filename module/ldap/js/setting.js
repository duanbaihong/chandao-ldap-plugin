function check_return_data(obj,data) {
    var obj= obj || '.ldap_setting'
    try{
        msg=$.parseJSON(data)
    }catch(err){
        msg={code: "99999",results: data+"."+err}
    }
    if(msg.code == "00000") {
        classvar='alert-info in'
    } else {
        classvar='alert-danger in';
    }
    alert_msgObj[popoIndex]=$(html).css({zIndex: popoIndex});
    $(obj).prepend(alert_msgObj[popoIndex].append(msg.results));
    setTimeout("alert_msgObj["+popoIndex+"].addClass(`${classvar}`);setTimeout('alert_msgObj["+popoIndex+"].alert(\"close\")',4000)",100)
    popoIndex+=1
}
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
        check_return_data('.ldap_setting',data)
        $(obj).removeAttr('disabled')// alert(msg);
    });
}

function syncGroups(obj) {
    $(obj).attr('disabled',true);
    $.get(createLink('ldap', 'sync'), function(ret){
        check_return_data('.ldap_setting',ret)
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
    $.post(createLink('ldap', 'usertest'),args,function(ret) {
        check_return_data('#showModuleModal .modal-header',ret)
        _this.removeAttr('disabled')// alert(msg);
    })
})
$("#ldapProto").change(function() {
    var val=$(this).val()
    var port=389
    var tls=$(".has_enable_tls")
    if(val=='ldap') {
        port=389
        tls.removeClass('enable')
    }
    if(val=='ldaps'){
        port=636
        tls.addClass('enable')
    }
    $("#ldapPort").val(port)
    
})
var html="<div class='alert fade' role='alert'><span class='costomer_close close icon-close' data-dismiss='alert' aria-label='Close'></span></div>";
var popoIndex=1000
var alert_msgObj=[];
$(document).ready(function () {
    alert_msgObj[popoIndex]=$(html).css({zIndex: popoIndex});;
    if (message!= ""){
        $(".ldap_setting").prepend(alert_msgObj[popoIndex].append(message));
        setTimeout("alert_msgObj["+popoIndex+"].addClass('alert-info in');setTimeout('alert_msgObj["+popoIndex+"].alert(\"close\")',4000)",100)
        popoIndex+=1
    }
})