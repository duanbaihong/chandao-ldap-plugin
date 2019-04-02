<?php
/**
 * The model file of ldap module of ZenTaoPMS.
 *
 * @license     dbh ()
 * @author      dbh888
 * @package     ldap
 * @link        http://www.zentao.net
 */

$lang->ldap->common           = "LDAP";
$lang->ldap->setting          = "設置";
$lang->ldap->proto            = 'LDAP協議:';
$lang->ldap->open             = "開啟LDAP認證";
$lang->ldap->host             = 'LDAP服務器:';
$lang->ldap->port             = '端口:';
$lang->ldap->version          = '協議版本:';
$lang->ldap->bindDN           = 'BindDN:';
$lang->ldap->password         = 'BindDN密碼:';
$lang->ldap->baseDN           = 'BaseDN:';
$lang->ldap->userSearchOU     = '用戶搜索OU:';
$lang->ldap->userFilter       = '搜索用戶過濾條件:';
$lang->ldap->groupSearchOU    = '組搜索OU:';
$lang->ldap->groupFilter      = '組搜索過濾條件:';
$lang->ldap->groupField       = '組字段:';
$lang->ldap->syncGroups       = '是否自動同步組:';
$lang->ldap->username         = '用戶名';
$lang->ldap->userpass         = '密碼';
$lang->ldap->syncGroupBtn     = '手動同步所有組';
$lang->ldap->save             = '保存設置';
$lang->ldap->savesuccess      = 'LDAP設置保存成功！';
$lang->ldap->test             = '連接測試';
$lang->ldap->usertest         = '用戶連接測試';
$lang->ldap->connect          = '連接';
$lang->ldap->userFieldMap     = '用戶字段映射:';
$lang->ldap->groupFieldMap    = '組字段映射:';
$lang->ldap->chkUserFieldErr  = '用戶映射字段格式錯誤，非json格式.';
$lang->ldap->chkUserFieldErr1 = '用戶映射缺少%s字段.';
$lang->ldap->chkGrpFieldErr   = '組映射字段格式錯誤，非json格式.';
$lang->ldap->chkGrpFieldErr1  = '組映射字段缺少name屬性字段.';
$lang->ldap->notpost          = '非法請求，不是POST請求';

$lang->ldap->syncLabels       = '同步組是是用來確定用戶權限，如果是首次同步，需要管理員設置此組的權限分配。';
$lang->ldap->findGroupsMsg    = '找到%s個組信息，同步了個%s組信息,因本地存儲相同組名，跳過%s個組信息同步.';

$lang->ldap->methodOrder[5]   = 'index';
$lang->ldap->methodOrder[10]  = 'setting';