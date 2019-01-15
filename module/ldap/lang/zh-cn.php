<?php
/**
 * The model file of ldap module of ZenTaoPMS.
 *
 * @license     dbh ()
 * @author      dbh888
 * @package     ldap
 * @link        http://www.zentao.net
 */

$lang->ldap->common         = "LDAP";
$lang->ldap->setting        = "设置";
$lang->ldap->proto          = 'LDAP协议:';
$lang->ldap->host           = 'LDAP服务器:';
$lang->ldap->port           = '端口:';
$lang->ldap->version        = '协议版本:';
$lang->ldap->bindDN         = 'BindDN:';
$lang->ldap->password       = 'BindDN密码:';
$lang->ldap->baseDN         = 'BaseDN:';
$lang->ldap->userSearchOU   = '用户搜索OU:';
$lang->ldap->userFilter     = '搜索用户过滤条件:';
$lang->ldap->groupSearchOU  = '组搜索OU:';
$lang->ldap->groupFilter    = '组搜索过滤条件:';
$lang->ldap->groupField     = '组字段:';
$lang->ldap->syncGroups     = '是否自动同步组:';
$lang->ldap->username       = '用户名';
$lang->ldap->userpass       = '密码';
$lang->ldap->syncGroupBtn   = '手动同步所有组';
$lang->ldap->save           = '保存设置';
$lang->ldap->savesuccess    = 'LDAP设置保存成功！';
$lang->ldap->test           = '连接测试';
$lang->ldap->usertest       = '用户连接测试';
$lang->ldap->connect        = '连接';
$lang->ldap->userFieldMap   = '用户字段映射:';
$lang->ldap->groupFieldMap  = '组字段映射:';
$lang->ldap->chkUserFieldErr= '用户映射字段格式错误，非json格式.';
$lang->ldap->chkUserFieldErr1= '用户映射缺少%s字段.';
$lang->ldap->chkGrpFieldErr = '组映射字段格式错误，非json格式.';
$lang->ldap->chkGrpFieldErr1= '组映射字段缺少name属性字段.';
$lang->ldap->notpost        = '非法请求，不是POST请求';

$lang->ldap->syncLabels     = '同步组是是用来确定用户权限，如果是首次同步，需要管理员设置此组的权限分配。';
$lang->ldap->findGroupsMsg  = '找到%s个组信息，同步了个%s组信息,因本地存储相同组名，跳过%s个组信息同步.';

$lang->ldap->methodOrder[5] = 'index';
$lang->ldap->methodOrder[10] = 'setting';