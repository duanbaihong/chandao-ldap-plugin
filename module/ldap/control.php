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

    /**
     * setting of ldap.
     *
     * @param  boolean  $ldapOpen
     * @param  string   $ldapProto
     * @param  string   $ldapHost
     * @param  string   $ldapPort
     * @param  string   $ldapBindDN
     * @param  string   $ldapPassword
     * @param  string   $ldapBaseDN
     * @param  string   $ldapUserOU
     * @param  string   $ldapUserFilter
     * @param  string   $ldapGroupOU
     * @param  string   $ldapGroupFilter
     * @param  boolean  $ldapSyncGroups
     * @param  int      $ldapVersion
     * @access public
     * @return public
    */
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
                          ."try {\n"
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
                          ."\$config->ldap->userFieldMap = '{$usermap}';\n"
                          ."\$config->ldap->caCert = '{$this->post->ldapCACert}';\n"
                          ."\$config->ldap->clientKey = '{$this->post->ldapClientKey}';\n"
                          ."\$config->ldap->clientCert = '{$this->post->ldapClientCert}';\n"
                          ."} catch (Exception \$e) { \n"
                          ."\techo '加载LDAP配置出错！'; \n"
                          ."}";

            $file = fopen("config.php", "w") or die("Unable to open file!");
            fwrite($file, $ldapConfig); 
            fclose($file); 
            $newConfig=new stdclass();
            $newConfig->configid='0';
            $newConfig->ldapopen=$this->post->ldapOpen=='true'?'true':'false';
            $newConfig->proto=$this->post->ldapProto;
            $newConfig->host=$this->post->ldapHost;
            $newConfig->port=$this->post->ldapPort;
            $newConfig->version=$this->post->ldapVersion;
            $newConfig->binddn=$this->post->ldapBindDN;
            $newConfig->bindpass=base64_encode($this->post->ldapPassword);
            $newConfig->basedn=$this->post->ldapBaseDN;
            $newConfig->userou=$this->post->ldapUserOU;
            $newConfig->userfilter=$this->post->ldapUserFilter;
            $newConfig->groupou=$this->post->ldapGroupOU;
            $newConfig->groupfilter=$this->post->ldapGroupFilter;
            $newConfig->syncgroups=$this->post->ldapSyncGroups=='true'?'true':'false';
            $newConfig->usermap=$usermap;
            $newConfig->groupmap=$groupmap;
            $newConfig->tls=$this->post->ldapProto=='ldaps'?'true':'false';
            $newConfig->cacert=$this->post->ldapCACert;
            $newConfig->clientkey=$this->post->ldapClientKey;
            $newConfig->clientcert=$this->post->ldapClientCert;
            $this->dao->replace($this->config->db->prefix.'ldap')->data($newConfig)->exec();
            $this->view->SaveSuccess=$this->lang->ldap->savesuccess;
        }
        $this->view->title      = $this->lang->ldap->common . $this->lang->colon . $this->lang->ldap->setting;
        $this->view->position[] = html::a(inlink('index'), $this->lang->ldap->common);
        $this->view->position[] = $this->lang->ldap->setting;
        $this->display();
    }

    /**
     * test of ldap.
     *
     * @param  string   $host
     * @param  string   $port
     * @param  string   $dn
     * @param  string   $pwd
     * @param  string   $version
     * @access public
     * @return public
    */
    public function test()
    {
      if (!empty($_POST)) {
        $postargs=$this->post;
        $test_status=$this->ldap->testconn($postargs->proto,
                                           $postargs->host,
                                           $postargs->port, 
                                           $postargs->dn, 
                                           $postargs->pwd,
                                           $postargs->version);
        if($test_status == "Success"){
          $this->send(array("code"=>"00000","results"=>$test_status)); 
        }else{
          $this->send(array("code"=>"99999","results"=>$test_status)); 
        }
      }else{
        $this->send(array("code"=>"99999","results"=>$this->lang->ldap->notpost)); 
      }
    }
    /**
     * test of ldap.
     *
     * @param  string   $ldapUserName
     * @param  string   $ldapUserPass
     * @access public
     * @return public
    */
    public function usertest()
    {
      if (!empty($_POST)) {
        $auth_user=$this->ldap->identify($this->post->ldapUserName,$this->post->ldapUserPass);
        $this->send($auth_user);
      }else{
        $this->send(array("code"=>"99999","results"=>$this->lang->ldap->notpost)); 
      }
    }
    /**
     * test of ldap.
     *
     * @access public
     * @return public
    */
    public function sync()
    { 
      $groups = $this->ldap->syncGroups2db($this->config->ldap);
      $this->send($groups); 
    }
}
