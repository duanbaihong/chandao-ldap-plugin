<?php
/**
 * The model file of ldap module of ZenTaoPMS.
 *
 * @license     dbh ()
 * @author      dbh888
 * @package     ldap
 * @link        http://www.zentao.net
 */
include '../../common/view/header.html.php';
?>
<?php echo "<script>var message='".($SaveSuccess??'')."';</script>
" ?>
<div class='container'>
<div class="ldap_setting">
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'>
        <?php echo html::icon($lang->icons['mail']);?></span> <strong><?php echo $lang->ldap->common;?></strong>
      <small class='text-muted'>
        <?php echo $lang->ldap->setting;?>
        <?php echo html::icon('cog');?></small>
    </div>
  </div>
  <?php $has_ldap=function_exists('ldap_connect'); if(!$has_ldap) { ?>
    <div class="alert alert-danger" role="alert">
      <?php echo $lang->ldap->notmodule; ?>
    </div>
  <?php } ?>
  <!-- form-condensed -->
  <form class='pdt-20' method='post' action='<?php echo inlink('setting');?>
    '>
    <table class='table table-form'>
      <tr>
        <th class="w-p14">
          <?php echo $lang->ldap->open; ?>:</th>
        <th class="w-p50" style="text-align: left;padding: 15px 5px;">
          <div class="checkbox-primary inline-block">
            <input type="checkbox" name="ldapOpen" value="true"  <?php if($config->
            ldap->ldapOpen) echo "checked='checked'"?> <?php if(!$has_ldap) echo 'disabled'; ?>  id="ldapOpen">
            <label for="ldapOpen">
              <?php echo $lang->ldap->no; ?></label>
          </div>
        </th>
      </tr>
      <tr>
        <th >
          <?php echo $lang->ldap->host; ?></th>
        <td class='w-p50 required'>
          <div class='input-group'>
            <span class='input-group-addon ldap_select_proto'>
              <?php echo html::select('ldapProto', array('ldap'=>
              'ldap','ldaps'=>'ldaps'),$config->
                ldap->proto, "class='form-control' required placeholder='ldap/ldaps'");?>
            </span>
            <?php echo html::input('ldapHost', $config->
            ldap->host, "class='form-control' required placeholder='ip/domain'");?>
            <span class='input-group-addon'>
              <?php echo $lang->ldap->port;?></span>
            <?php echo html::input('ldapPort', $config->
            ldap->port, "class='form-control ldap_port' required placeholder='389/636'");?>
          </div>
        </td>
      </tr>
      <tr>
        <th>
          <?php echo $lang->ldap->version; ?></th>
        <td class='w-p50 required'>
          <?php echo html::select('ldapVersion',  array('2'=>
          '2','3'=>'3'),$config->
            ldap->version, "class='form-control chosen' required placeholder='2,3'");?>
        </td>
      </tr>
      <tr>
        <th>
          <?php echo $lang->ldap->bindDN; ?></th>
        <td class='w-p50 required'>
          <?php echo html::input('ldapBindDN', $config->
          ldap->bindDN, "class='form-control' required placeholder='cn=admin,dc=test,dc=com'");?>
        </td>
      </tr>
      <tr>
        <th>
          <?php echo $lang->ldap->password; ?></th>
        <td class='w-p50 required'>
          <?php echo html::password('ldapPassword', $config->
          ldap->bindPWD, "class='form-control' required autocomplete=off");?>
        </td>
      </tr>
      <tr>
        <td></td>
        <td class="text-right">
          <label id='testRlt'></label>
          <?php echo html::commonButton($lang->
          ldap->test, "onclick='javascript:onClickTest(this)' class='btn btn-danger'".($has_ldap?'':' disabled')); ?>
        </td>
      </tr>
      <tr>
        <th>
          <?php echo $lang->ldap->baseDN; ?></th>
        <td class='w-p50 required'>
          <?php echo html::input('ldapBaseDN', $config->
          ldap->baseDN, "class='form-control' required placeholder='dc=test,dc=com'");?>
        </td>
      </tr>
      <tr>
        <th>
          <?php echo $lang->ldap->userSearchOU; ?></th>
        <td class='w-p50 required'>
          <?php echo html::input('ldapUserOU', $config->
          ldap->userSearchOU, "class='form-control' required=required placeholder='ou=users'");?>
        </td>
      </tr>
      <tr>
        <th>
          <?php echo $lang->ldap->userFilter; ?></th>
        <td class='w-p50'>
          <?php echo html::input('ldapUserFilter', $config->
          ldap->userFilter, "class='form-control' placeholder='(&(uid=%s))'");?>
        </td>
      </tr>
      <tr>
        <th>
          <?php echo $lang->ldap->userFieldMap; ?></th>
        <td class='w-p50 required'>
          <?php echo html::input('ldapUserFieldMap', stripslashes($config->
          ldap->userFieldMap), "class='form-control' required=required placeholder='{\"account\": \"uid\",\"email\": \"mail\",\"realname\":\"sn\",\"mobile\":\"mobile\"}'");?>
        </td>
      </tr>
      <tr>
        <th>
          <?php echo $lang->ldap->groupSearchOU; ?></th>
        <td class='w-p50 required'>
          <?php echo html::input('ldapGroupOU', $config->
          ldap->groupSearchOU, "class='form-control' required=required placeholder='ou=groups'");?>
        </td>
      </tr>
      <tr>
        <th>
          <?php echo $lang->ldap->groupFilter; ?></th>
        <td class='w-p50'>
          <?php echo html::input('ldapGroupFilter', $config->
          ldap->groupFilter, "class='form-control' placeholder='(|(objectclass=groupOfNames)(objectclass=groupOfUniqueNames)(objectclass=posixGroup))'");?>
        </td>
      </tr>
      <tr>
        <th>
          <?php echo $lang->ldap->groupFieldMap; ?></th>
        <td class='w-p50 required'>
          <?php echo html::input('ldapGroupFieldMap', stripslashes($config->
          ldap->groupFieldMap), "class='form-control' required=required placeholder='{\"name\":\"cn\",\"desc\":\"description\",\"role\":\"cn\"}'");?>
        </td>
      </tr>
      <tr>
        <th>
          <?php echo $lang->ldap->syncGroups; ?></th>
        <td class='w-p50'>
          <div class="checkbox-primary inline-block">
            <input type="checkbox" name="ldapSyncGroups" value="true"  <?php if($config->
            ldap->syncGroups) echo "checked='checked'"?>  id="ldapSyncGroups">
            <label for="closedProduct">
              <?php echo $lang->ldap->syncLabels; ?></label>
          </div>
        </td>
      </tr>
      <tr>
        <td class="text-center" colspan="2" style="padding: 15px;">
          <?php 
            echo html::submitButton($lang->ldap->save);
            if($config->ldap->ldapOpen){
              echo html::commonButton($lang->ldap->usertest, "href='#showModuleModal' data-toggle='modal'",'btn btn-warning');
              echo html::commonButton($lang->ldap->syncGroupBtn,"onclick='javascript:syncGroups(this)' data-loading-text='Loading...'",'btn btn-success'); 
            }
            ?>
        </td>
      </tr>
    </table>
  </form>
</div>
</div>

<div class="modal fade" id="showModuleModal" tabindex="-1" role="dialog">
<div class="modal-dialog w-600px">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal"> <i class="icon icon-close"></i>
      </button>
      <h4 class="modal-title"> <i class="icon-cog"></i>
        <?php echo $lang->ldap->testLDAPConnect ?>
      </h4>
    </div>
    <div class="modal-body">
      <form method="post" class="ldap_usertest_form" target="hiddenwin" action="#">
        <div class="container">
          <table class="table table-form">
            <tr>
              <th>
                <?php echo $lang->ldap->username; ?>:</th>
              <td class='w-p50 required'>
                <?php echo html::input('ldapUserName', $config->
                ldap->username, "class='form-control' required placeholder='".$lang->ldap->username."'");?>
              </td>
              <td class='w-p10'></td>
            </tr>
            <tr>
              <th>
                <?php echo $lang->ldap->userpass; ?>:</th>
              <td class='w-p50 required'>
                <?php echo html::password('ldapUserPass', $config->
                ldap->userpass, "class='form-control' autocomplete=off required placeholder='".$lang->ldap->userpass."'");?>
              </td>
              <td class='w-p10'></td>
            </tr>
          </table>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <?php echo html::submitButton($lang->
      ldap->connect,'','btn btn-wide btn-primary ldap_testuser'); ?>
    </div>
  </div>
</div>
</div>
<?php include '../../common/view/footer.html.php';?>