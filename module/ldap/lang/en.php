<?php
/**
 * The model file of ldap module of ZenTaoPMS.
 *
 * @license dbh ()
 * @author dbh888
 * @package ldap
 * @link http://www.zentao.net
 */

$lang->ldap->notmodule        = "The extension module for PHP LDAP is not installed";
$lang->ldap->common           = "LDAP";
$lang->ldap->setting          = "Settings";
$lang->ldap->proto            = 'LDAP protocol:';
$lang->ldap->open             = "Enable LDAP";
$lang->ldap->host             = 'LDAP Server:';
$lang->ldap->port             = 'port:';
$lang->ldap->version          = 'Protocol version:';
$lang->ldap->bindDN           = 'BindDN:';
$lang->ldap->password         = 'BindDN password:';
$lang->ldap->baseDN           = 'BaseDN:';
$lang->ldap->userSearchOU     = 'User search OU:';
$lang->ldap->userFilter       = 'Search User Filter Condition:';
$lang->ldap->groupSearchOU    = 'Group Search OU:';
$lang->ldap->groupFilter      = 'Group search filter: ';
$lang->ldap->groupField       = 'Group field:';
$lang->ldap->syncGroups       = 'Do you want to automatically synchronize groups:';
$lang->ldap->username         = 'username';
$lang->ldap->userpass         = 'password';
$lang->ldap->syncGroupBtn     = 'Manually synchronize all groups';
$lang->ldap->save             = 'Save Settings';
$lang->ldap->savesuccess      = 'LDAP settings saved successfully! ';
$lang->ldap->test             = 'Connection Test';
$lang->ldap->usertest         = 'User Connection Test';
$lang->ldap->connect          = 'Connect';
$lang->ldap->userFieldMap     = 'User Field Mapping:';
$lang->ldap->groupFieldMap    = 'Group Field Mapping:';
$lang->ldap->chkUserFieldErr  = 'User mapping field format error, non-json format.';
$lang->ldap->chkUserFieldErr1 = 'User mapping is missing %s field.';
$lang->ldap->chkGrpFieldErr   = 'Group mapping field format error, non-json format.';
$lang->ldap->chkGrpFieldErr1  = 'The group mapping field is missing the name attribute field.';
$lang->ldap->notpost          = 'Illegal request, not POST request';

$lang->ldap->syncLabels       = 'The sync group is used to determine user permissions. If it is the first sync, the administrator needs to set the permissions assignment for this group. ';
$lang->ldap->findGroupsMsg    = 'Found %s group information, synchronized %s group information, because the same group name is stored locally, skip %s group information synchronization.';
$lang->ldap->testLDAPConnect  = 'Test LDAP user connection';
$lang->ldap->notfoundGroup    = 'No user group related information was found';

$Lang->ldap->cacert 		  ='root certificate CA: ';
$Lang->ldap->clientkey 		  = 'private key file key:';
$Lang->ldap->clientcert 	  ='certificate file PEM: ';
$Lang->ldap->placecacert 	  = 'please select the root certificate file（ ca.pem )...';
$Lang->ldap->placeclientkey   = 'please select the private key file（ client.key )...';
$Lang->ldap->placeclientcert  = 'please select a certificate file（ client.pem )...';

$lang->ldap->methodOrder[5]   = 'index';
$lang->ldap->methodOrder[10]  = 'setting';