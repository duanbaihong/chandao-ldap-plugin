<?php
/**
 * The detect view file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <wwccss@cnezsoft.com>
* @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
include '../../common/view/header.html.php';
?>
<div class='container'>
  <div class="ldap_setting">
    <div id='titlebar'>
      <div class='heading'>
        <span class='prefix'>
          <?php echo html::icon($lang->icons['mail']);?></span> <strong><?php echo $lang->ldap->common;?></strong>
        <small class='text-muted'>
          <?php echo $lang->
          ldap->setting;?>
          <?php echo html::icon('cog');?></small>
      </div>
    </div>
    <!-- form-condensed -->
    <form class='pdt-20' method='post' action='<?php echo inlink('save');?>
      '>
      <table class='table table-form'>
        <tr>
          <th>
            <?php echo $lang->ldap->host; ?></th>
          <td class='w-p50 required'>
            <div class='input-group'>
              <span class='input-group-addon ldap_select_proto'>
                <?php echo html::select('ldapProto', array('ldap'=>'ldap','ldaps'=>'ldaps'),$config->
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

            <?php echo html::input('ldapVersion', $config->
            ldap->version, "class='form-control' required placeholder='1,2,3'");?>
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
            <?php echo html::password('ldapPassword', $config->ldap->bindPWD, "class='form-control' required autocomplete=off");?></td>
        </tr>
        <tr>
          <td></td>
          <td class="text-right">
            <label id='testRlt'></label>
            <?php echo html::commonButton($lang->ldap->test, "onclick='javascript:onClickTest()' class='btn btn-danger'"); ?>
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
            <?php echo html::input('ldapUserFieldMap', stripslashes($config->ldap->userFieldMap), "class='form-control' required=required placeholder=\"{'account': 'uid','email': 'mail','realname':'sn','mobile':'phone'}\"");?></td>
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
            ldap->groupFieldMap), "class='form-control' required=required placeholder=\"{'name':'cn','desc':'description'}\"");?>
          </td>
        </tr>
        <tr>
          <th>
            <?php echo $lang->ldap->syncGroups; ?></th>
          <td class='w-p50'>
            <div class="checkbox-primary inline-block">
              <input type="checkbox" name="ldapSyncGroups" value="ldapSyncGroups"  <?php if($config->
              ldap->syncGroups) echo "checked='checked'"?>  id="ldapSyncGroups">
              <label for="closedProduct"><?php echo $lang->ldap->syncLabels; ?></label>
            </div>
          </td>
        </tr>
        <tr>
          <td class="text-center" colspan="2">
            <?php 
            echo html::submitButton($lang->ldap->save);
            echo html::commonButton($lang->ldap->usertest, "href='#showModuleModal' data-toggle='modal'",'btn btn-warning');
            echo html::commonButton($lang->ldap->syncGroupBtn,"onclick='javascript:syncGroups()'",'btn btn-success');
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
        <h4 class="modal-title"><i class="icon-cog"></i>
          测试LDAP用户连接
        </h4>
      </div>
      <div class="modal-body">
        <form method="post" target="hiddenwin" action="/index.php?m=ldap&f=ldaptest">
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
                  <?php echo html::input('ldapUserPass', $config->
                  ldap->userpass, "class='form-control' required placeholder='".$lang->ldap->userpass."'");?>
                </td>
                <td class='w-p10'></td>
              </tr>
            </table>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <?php echo html::submitButton($lang->ldap->connect,''); ?>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>