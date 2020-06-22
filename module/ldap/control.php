<?php
/**
 * The model file of ldap module of ZenTaoPMS.
 *
 * @license     dbh ()
 * @author      dbh888
 * @package     ldap
 * @link        http://www.zentao.net
 */
class ldap extends control
{
    public $referer;

    /**
     * Construct 
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->locate(inlink('setting'));
    }

    public function setting() 
    {
        if (!empty($_POST)) {
            $groupmap=addslashes($this->post->ldapGroupFieldMap);
            $usermap=addslashes($this->post->ldapUserFieldMap);
            $this->config->ldap->ldapOpen      = $this->post->ldapOpen;
            $this->config->ldap->proto         = $this->post->ldapProto;
            $this->config->ldap->host          = $this->post->ldapHost;
            $this->config->ldap->port          = $this->post->ldapPort;
            $this->config->ldap->version       = $this->post->ldapVersion;
            $this->config->ldap->bindDN        = $this->post->ldapBindDN;
            $this->config->ldap->bindPWD       = $this->post->ldapPassword;
            $this->config->ldap->baseDN        = $this->post->ldapBaseDN;
            $this->config->ldap->userSearchOU  = $this->post->ldapUserOU;
            $this->config->ldap->userFilter    = $this->post->ldapUserFilter;
            $this->config->ldap->groupSearchOU = $this->post->ldapGroupOU;
            $this->config->ldap->groupFilter   = $this->post->ldapGroupFilter;
            $this->config->ldap->syncGroups    = $this->post->ldapSyncGroups;
            $this->config->ldap->groupFieldMap = $groupmap;
            $this->config->ldap->userFieldMap  = $usermap;

            // 此处我们把配置写入配置文件
            $ldapConfig = "<?php \n"
                          ."\$config->ldap = new stdclass();\n"
                          ."\$config->ldap->ldapOpen = '{$this->post->ldapOpen}';\n"
                          ."\$config->ldap->proto = '{$this->post->ldapProto}';\n"
                          ."\$config->ldap->host = '{$this->post->ldapHost}';\n"
                          ."\$config->ldap->port = '{$this->post->ldapPort}';\n"
                          ."\$config->ldap->version = '{$this->post->ldapVersion}';\n"
                          ."\$config->ldap->bindDN = '{$this->post->ldapBindDN}';\n"
                          ."\$config->ldap->bindPWD = '{$this->post->ldapPassword}';\n"
                          ."\$config->ldap->baseDN = '{$this->post->ldapBaseDN}';\n"
                          ."\$config->ldap->userSearchOU = '{$this->post->ldapUserOU}';\n"
                          ."\$config->ldap->userFilter = '{$this->post->ldapUserFilter}';\n"
                          ."\$config->ldap->groupSearchOU = '{$this->post->ldapGroupOU}';\n"
                          ."\$config->ldap->groupFilter = '{$this->post->ldapGroupFilter}';\n"
                          ."\$config->ldap->syncGroups = '{$this->post->ldapSyncGroups}';\n"
                          ."\$config->ldap->groupFieldMap = '{$groupmap}';\n"
                          ."\$config->ldap->userFieldMap = '{$usermap}';\n";

            $file = fopen("config.php", "w") or die("Unable to open file!");
            fwrite($file, $ldapConfig); 
            fclose($file); 
            $this->view->SaveSuccess=$this->lang->ldap->savesuccess;
        }
        $this->view->title      = $this->lang->ldap->common . $this->lang->colon . $this->lang->ldap->setting;
        $this->view->position[] = html::a(inlink('index'), $this->lang->ldap->common);
        $this->view->position[] = $this->lang->ldap->setting;
        $this->display();
    }

    public function test()
    {
      if (!empty($_POST)) {
        $postargs=$this->post;
        $test_status=$this->ldap->testconn("{$postargs->proto}://{$postargs->host}:{$postargs->port}",$postargs->port, $postargs->dn, $postargs->pwd,$postargs->version);
        if($test_status == "Success"){
          $this->send(array("code"=>"00000","results"=>$test_status)); 
        }else{
          $this->send(array("code"=>"99999","results"=>$test_status)); 
        }
      }else{
        $this->send(array("code"=>"99999","results"=>$this->lang->ldap->notpost)); 
      }
    }
    public function usertest()
    {
      if (!empty($_POST)) {
        $auth_user=$this->ldap->identify($this->post->ldapUserName,$this->post->ldapUserPass);
        $this->send($auth_user);
      }else{
        $this->send(array("code"=>"99999","results"=>$this->lang->ldap->notpost)); 
      }
    }
    public function sync()
    { 
      $groups = $this->ldap->syncGroups2db($this->config->ldap);
      $this->send($groups); 
    }
}
