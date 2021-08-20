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
        displayStatus(data,$(".ldap_setting"),$(obj))
    });
}
function displayStatus(data,dispObj,ctlObj){
    try{
        msg=$.parseJSON(data)
    }catch(err){
        msg={code: "99999",results: data+"."+err}
    }
    alert_msgObj[popoIndex]=$(html).css({zIndex: popoIndex});
    dispObj.prepend(alert_msgObj[popoIndex].append((typeof msg.results === 'object')?Object.values(msg.results).join(','):msg.results));
    if(msg.code == "00000") {
        classvar='alert-info in'
    } else {
        classvar='alert-danger in';
    }
    setTimeout("alert_msgObj["+popoIndex+"].addClass(`${classvar}`);setTimeout('alert_msgObj["+popoIndex+"].alert(\"close\")',4000)",100)
    ctlObj.removeAttr('disabled');
    popoIndex+=1
}
function syncGroups(obj) {
    $(obj).attr('disabled',true);
    $.get(createLink('ldap', 'sync'), function(ret){
        displayStatus(ret,$(".ldap_setting"),$(obj))
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
        displayStatus(ret,$("#showModuleModal .modal-header"),_this)
    })
})
$("#ldapProto").change(function() {
    var val=$(this).val()
    var port=389
    if(val=='ldap') port=389
    if(val=='ldaps') port=636
    $("#ldapPort").val(port)
})
var html="<div class='alert fade' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button></div>";
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