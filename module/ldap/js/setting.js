function onClickTest() {
    var par={host: $('#ldapHost').val(),
             dn: $('#ldapBindDN').val(),
             pwd: $('#ldapPassword').val(),
             proto: $('#ldapProto').val(),
             port: $('#ldapPort').val(),
             version: $('#ldapVersion').val()
        }
    $.post(createLink('ldap', 'test'),par, function(data) {
        $('#testRlt').html(data);
    });
}

function syncGroups() {
    $.get(createLink('ldap', 'sync'), function(ret){
        msg=$.parseJSON(ret)
        alert(msg.results);
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
    $.post(createLink('ldap', 'usertest'),args,function(msg) {
        // body...
        alert(msg);
    })
})
$("#ldapProto").change(function() {
    var val=$(this).val()
    var port=389
    if(val=='ldap') port=389
    if(val=='ldaps') port=636
    $("#ldapPort").val(port)
})