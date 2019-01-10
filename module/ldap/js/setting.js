function onClickTest() {
    var par = 'host=' + $('#ldapHost').val() + '&dn=';
    par += $('#ldapBindDN').val().replace(/\=/g,"%3D");
    par += '&pwd=' + $('#ldapPassword').val().replace(/\=/g,"%3D");
    par += '&proto=' + $('#ldapProto').val().replace(/\=/g,"%3D");
    par += '&port=' + $('#ldapPort').val().replace(/\=/g,"%3D");

    $.get(createLink('ldap', 'test', par), function(data) {
        $('#testRlt').html(data);
    });
}

function syncGroups() {
    $.get(createLink('ldap', 'sync'), function(ret){
        alert("同步了"+ret+"位用户信息");
    });
}
$("#ldapProto").change(function() {
    var val=$(this).val()
    var port=389
    if(val=='ldap') port=389
    if(val=='ldaps') port=636
    $("#ldapPort").val(port)
})