<?php
require_once(LIB_PATH."/smarty/Smarty.class.php");

class Smarty_RCMS extends Smarty {
    private $module_id;
    private $real_template_paths = array(); // debug用
    private $lang;

    function Smarty_RCMS() {

        $this->Smarty();

        parent::__construct();
        // 言語ディレクトリのパスを組み立てる
        $this->lang = RCMS_Locale::getLocale()->getSiteLanguage();

        //セキュリティ高の設定
        $this->secure_dir = array(TEMPLATE_PATH."/",SITE_TEMPLATE_PATH."/",ROOT_DIR."/templates_c/",PAGE_DEFAULT_DIR);
        //$this->secure_dir = array(TEMPLATE_PATH."/",SITE_TEMPLATE_PATH."/",SERVER_TEMPLATE_PATH."/",ROOT_DIR."/templates_c/",PAGE_DEFAULT_DIR);

        $this->security = true;
        $this->security_settings['IF_FUNCS'] = array('is_null','NULL','null','false','FALSE','count','file_exists', 'is_array','in_array');
        $this->security_settings['MODIFIER_FUNCS'] = array('escape','nl2br','join','array_key_exists','count','split','is_array','in_array','mt_rand','strip_tags','urlencode', 'min', 'max', 'substr','strstr','mb_strlen','mb_substr','strlen','array_keys','json_encode','html_convert', 'range','strtolower','strtolower','strtoupper','key', 'round','number_format','md5','forContentsConvert','mb_convert_encoding','implode','unset');
        $this->security_settings['ALLOW_CONSTANTS'] = true;
        if(RCMS_DEV_MODE == 1){
            $this->security_settings['MODIFIER_FUNCS'][] = "print_r";
        }

        // 各ディレクトリ
        $this->plugins_dir  =  array('plugins', ROOT_DIR . '/lib/smarty/plugins', 'plugins/rcms');
        $this->template_dir = TEMPLATE_PATH;
        $this->compile_dir  = ROOT_DIR."/templates_c/";

        $this->register_outputfilter(array('Smarty_RCMS', 'include_head'));
        //$this->register_prefilter(array('Smarty_RCMS', 'pre01'));
        //$this->register_postfilter(array('Smarty_RCMS', 'post01'));

        $this->register_resource("stringFetch", array(
            array($this, "string_get_template"),
            array($this, "string_get_timestamp"),
            array($this, "string_get_secure"),
            array($this, "string_get_trusted")
            )
        );

        $this->assign("rauth", new RCMSAuth_smary());
    }

    function display($resource_name, $cache_id = null, $compile_id = null) {
        $this->fetch($resource_name, $cache_id, $compile_id, true);
    }

    function fetch($resource_name, $cache_id = null, $compile_id = null, $display = false,$templateedit_id = null) {

        $resource_name = $this->findResourcePath($resource_name,$templateedit_id);
        return parent::fetch($resource_name, $cache_id, $compile_id, $display);
    } // fetch()

    // 表ページで処理中のmobuleID(survey_id, inquiry_idなど)
    public function setModuleId($id) {
        $this->module_id = $id;
    }
    public function findResourcePath($resource_name,$templateedit_id = null) {
        return !is_user_v2() ? $this->_findResourcePath_v1($resource_name) : $this->_findResourcePath_v2($resource_name,$templateedit_id);
    }

    public function _findResourcePath_v2($resource_name,$templateedit_id) {
        global $conf_template_nm_list_by_column;

        $match_flg = 0;
        if (preg_match('|^(.*)/(.+)$|', $resource_name, $rs)) {
            $dir_name  = $rs[1];
            $file_name = $rs[2];
            $match_flg = 1;
        }

        //親子関係があるテンプレートは、親テンプレート内から子テンプレートをincludeするのでここでは対象にしない
        if($templateedit_id && !isset($conf_template_nm_list_by_column[$dir_name])){
            $path = SITE_TEMPLATE_PATH."/v2/".$templateedit_id.".html";
            if(is_file($path)){
                list($langpath, $langpath_en) = $this->getLangResourcePath($path); //言語毎の設定があれば、それを表示する

                if($langpath && is_file($langpath)){
                    $path = $langpath;
                }elseif($langpath_en && is_file($langpath_en)){
                    $path = $langpath_en;
                }
                $this->real_template_paths[] = array($langpath, $langpath_en);
                return $path;
            }
        }


        //$page_id = $this->get_template_vars('page_page_id');

        // モバイル用を探索する
        // モバイルはi18n未対応
        if(is_mobile()){
            if(is_SmartPhone()){
                $arrDeviceDir = array("smartphone","mobile");
            }else{
                $arrDeviceDir[] = "mobile";
            }
        }
        if(is_Social()){
            //今のところソーシャルの場合デフォルトはPC版にする
            $arrDeviceDir = array("social");
        }
        if(is_MobileApp()){
            //今のところソーシャルの場合デフォルトはPC版にする
            $arrDeviceDir = array("mobileapp");
        }

        //デバイス毎のものを探索する場合
        if ($arrDeviceDir) {
            if ($match_flg) {
                // 探索する順番を設定
                $paths = array();

                $paths[] = array(SITE_TEMPLATE_PATH .   "/$dir_name", $file_name);
             //   $paths[] = array(SERVER_TEMPLATE_PATH . "/$dir_name", $file_name);
                $paths[] = array(TEMPLATE_PATH .        "/$dir_name", $file_name);

                /*  次にデバイス毎に探索する  */
                foreach ($paths as $pair) {
                    list($dir_name, $file_name) = $pair;
                    if ($arrDeviceDir) {
                        $paths_device = array();
                        foreach($arrDeviceDir as $device_dir){
                            $paths_device[] = $dir_name . "/".$device_dir."/" . $file_name;
                        }
                        foreach ($paths_device as $path) {
                            if (is_file($path)) {
                                // 見つかった!
                                list($langpath, $langpath_en) = $this->getLangResourcePath($path); //言語毎の設定があれば、それを表示する
                                if($langpath && is_file($langpath)){
                                    $path = $langpath;
                                }elseif($langpath_en && is_file($langpath_en)){
                                    $path = $langpath_en;
                                }

                                $this->real_template_paths[] = array($resource_name, $path);
                                return $path;
                            }
                        }
                    }
                }
            }
        }

        /* ここからはPCの場合（デフォルトデバイス）を考慮する */
        list($langpath, $langpath_en) = $this->getLangResourcePath($resource_name);

        // 探索する順番を設定
        $paths = array();


        if($langpath){$paths[] = SITE_TEMPLATE_PATH .   "/$langpath";}
        if($langpath_en){$paths[] = SITE_TEMPLATE_PATH .   "/$langpath_en";}
        $paths[] = SITE_TEMPLATE_PATH .   "/$resource_name";
    //   if($langpath){$paths[] = SERVER_TEMPLATE_PATH . "/$langpath";}
    //   if($langpath_en){$paths[] = SERVER_TEMPLATE_PATH . "/$langpath_en";}
    //   $paths[] = SERVER_TEMPLATE_PATH . "/$resource_name";
        if($langpath){$paths[] = TEMPLATE_PATH .        "/$langpath";}
        if($langpath_en){$paths[] = TEMPLATE_PATH .        "/$langpath_en";}

        foreach ($paths as $path) {

            if (is_file($path)) {
                // 見つかった!
                $this->real_template_paths[] = array($resource_name, $path);
                return $path;
            }
        }
        //管理画面v2対応
        if(is_admin_v2()){
            if (preg_match('|^(.*)/([^/]+?)$|', $resource_name, $rs)){
                if(is_file(SITE_TEMPLATE_PATH."/".$rs[1]."/v2/".$rs[2])) {
                    $path = SITE_TEMPLATE_PATH."/".$rs[1]."/v2/".$rs[2];
                    $this->real_template_paths[] = array($resource_name, $path);
                    return $path;
           //    }elseif(is_file(SERVER_TEMPLATE_PATH."/".$rs[1]."/v2/".$rs[2])) {
           //         $path = SERVER_TEMPLATE_PATH."/".$rs[1]."/v2/".$rs[2];
           //         $this->real_template_paths[] = array($resource_name, $path);
           //          return $path;
                }elseif(is_file(TEMPLATE_PATH."/".$rs[1]."/v2/".$rs[2])) {
                    $path = TEMPLATE_PATH."/".$rs[1]."/v2/".$rs[2];
                    $this->real_template_paths[] = array($resource_name, $path);
                    return $path;
                }
            }
        }

        // 元のまま返す
        $this->real_template_paths[] = array($resource_name, $resource_name);

        return $resource_name;

    }
    public function _findResourcePath_v1($resource_name) {
        $page_id = $this->get_template_vars('page_page_id');

        // モバイル用を探索する
        // モバイルはi18n未対応
        if(is_mobile()){
            if(is_SmartPhone()){
                $arrDeviceDir = array("smartphone","mobile");
            }else{
                $arrDeviceDir[] = "mobile";
            }
        }
        if(is_Social()){
            //今のところソーシャルの場合デフォルトはPC版にする
            $arrDeviceDir = array("social");
        }
        if(is_MobileApp()){
            //今のところソーシャルの場合デフォルトはPC版にする
            $arrDeviceDir = array("mobileapp");
        }

        //デバイス毎のものを探索する場合
        if ($arrDeviceDir) {
            if (preg_match('|^(.*)/(.+)$|', $resource_name, $rs)) {
                $dir_name = $rs[1];
                $file_name = $rs[2];

                // 探索する順番を設定
                $paths = array();

                /*  まずはコンテンツ毎に探索する  */
                // ページ毎
                if ($page_id) {
                    $paths[] = array(SITE_TEMPLATE_PATH . "/$page_id/$dir_name", $file_name);
                }
                // コンテンツ毎
                if ($this->module_id) {
                    $paths[] = array(SITE_TEMPLATE_PATH . "/$dir_name", str_replace(".html", "+{$this->module_id}.html", $file_name));
                }
                $paths[] = array(SITE_TEMPLATE_PATH .   "/$dir_name", $file_name);
             //   $paths[] = array(SERVER_TEMPLATE_PATH . "/$dir_name", $file_name);
                $paths[] = array(TEMPLATE_PATH .        "/$dir_name", $file_name);

                /*  次にデバイス毎に探索する  */
                foreach ($paths as $pair) {
                    list($dir_name, $file_name) = $pair;
                    if ($arrDeviceDir) {
                        $paths_device = array();
                        foreach($arrDeviceDir as $device_dir){
                            $paths_device[] = $dir_name . "/".$device_dir."/" . $file_name;
                        }
                        foreach ($paths_device as $path) {
                            if (is_file($path)) {
                                // 見つかった!
                                list($langpath, $langpath_en) = $this->getLangResourcePath($path); //言語毎の設定があれば、それを表示する
                                if($langpath && is_file($langpath)){
                                    $path = $langpath;
                                }elseif($langpath_en && is_file($langpath_en)){
                                    $path = $langpath_en;
                                }

                                $this->real_template_paths[] = array($resource_name, $path);
                                return $path;
                            }
                        }
                    }
                }
            }
        }

        /* ここからはPCの場合（デフォルトデバイス）を考慮する */
        list($langpath, $langpath_en) = $this->getLangResourcePath($resource_name);

        // 探索する順番を設定
        $paths = array();

        // ページ毎
        if ($page_id) {
            if($langpath){$paths[] = SITE_TEMPLATE_PATH . "/$page_id/$langpath";}
            if($langpath_en){$paths[] = SITE_TEMPLATE_PATH . "/$page_id/$langpath_en";}
            $paths[] = SITE_TEMPLATE_PATH . "/$page_id/$resource_name";
        }
        // コンテンツ毎
        if ($this->module_id) {
            if($langpath){$paths[] = SITE_TEMPLATE_PATH . "/" . str_replace(".html", "+{$this->module_id}.html", $langpath);}
            if($langpath_en){$paths[] = SITE_TEMPLATE_PATH . "/" . str_replace(".html", "+{$this->module_id}.html", $langpath_en);}
            $paths[] = SITE_TEMPLATE_PATH . "/" . str_replace(".html", "+{$this->module_id}.html", $resource_name);
        }

        if($langpath){$paths[] = SITE_TEMPLATE_PATH .   "/$langpath";}
        if($langpath_en){$paths[] = SITE_TEMPLATE_PATH .   "/$langpath_en";}
        $paths[] = SITE_TEMPLATE_PATH .   "/$resource_name";
    //   if($langpath){$paths[] = SERVER_TEMPLATE_PATH . "/$langpath";}
    //   if($langpath_en){$paths[] = SERVER_TEMPLATE_PATH . "/$langpath_en";}
    //   $paths[] = SERVER_TEMPLATE_PATH . "/$resource_name";
        if($langpath){$paths[] = TEMPLATE_PATH .        "/$langpath";}
        if($langpath_en){$paths[] = TEMPLATE_PATH .        "/$langpath_en";}

        foreach ($paths as $path) {

            if (is_file($path)) {
                // 見つかった!
                $this->real_template_paths[] = array($resource_name, $path);
                return $path;
            }
        }
        
        //管理画面v2対応
        if(is_admin_v2()){
            if (preg_match('|^(.*)/([^/]+?)$|', $resource_name, $rs)){
                if(is_file(SITE_TEMPLATE_PATH."/".$rs[1]."/v2/".$rs[2])) {
                    $path = SITE_TEMPLATE_PATH."/".$rs[1]."/v2/".$rs[2];
                    $this->real_template_paths[] = array($resource_name, $path);
                    return $path;
               }elseif(is_file(SERVER_TEMPLATE_PATH."/".$rs[1]."/v2/".$rs[2])) {
                    $path = SERVER_TEMPLATE_PATH."/".$rs[1]."/v2/".$rs[2];
                    $this->real_template_paths[] = array($resource_name, $path);
                     return $path;
                }elseif(is_file(TEMPLATE_PATH."/".$rs[1]."/v2/".$rs[2])) {
                    $path = TEMPLATE_PATH."/".$rs[1]."/v2/".$rs[2];
                    $this->real_template_paths[] = array($resource_name, $path);
                    return $path;
                }
            }
        }

        // 元のまま返す
        $this->real_template_paths[] = array($resource_name, $resource_name);
        return $resource_name;
    }

    /*
     言語毎のファイルパスを取得する
     副言語のパス、英語版のパスを返す
    */
    protected function getLangResourcePath($path){

        //多言語利用しない
        if(!USE_MULTILANG){return array("","");}
        
        if (preg_match('|^(.*)/(.+)$|', $path, $rs)) {
            $dir_name = $rs[1];
            $file_name = $rs[2];
            $langpath = "{$dir_name}/langs/{$this->lang}/{$file_name}";
            if(I18N_DEFAULT_LANGUAGES != $this->lang && in_array("en",explode(",",I18N_OTHER_LANGUAGES))){$langpath_en = "{$dir_name}/langs/en/{$file_name}";}
        }
        else {
            $langpath = "langs/{$this->lang}/{$resource_name}";
            if(I18N_DEFAULT_LANGUAGES != $this->lang && in_array("en",explode(",",I18N_OTHER_LANGUAGES))){$langpath_en = "langs/en/{$resource_name}";}
        }
        return array($langpath,$langpath_en);
    }

    // 渡された文字列をテンプレートとして使用する
    public function stringFetch($string, $cache_id = null, $compile_id = null, $display = false) {
        $this->string = $string;
        return $this->fetch('stringFetch:mystring', $cache_id, $compile_id, $display);
    }

    function pre01($buff, &$smarty) {
      return mb_convert_encoding($buff,"EUC-JP","SJIS");
    }

    function post01($buff, &$smarty) {
      return mb_convert_encoding($buff,"SJIS","EUC-JP");
    }

    public static function include_head($tpl_output, &$smarty) {
        $blocks = $smarty->get_template_vars('_headblock_');
        $files = $smarty->get_template_vars('_headinclude_');
        $buf = "";

        if ($blocks) {
            $buf = join("\n", $blocks);
        }

        if ($files) {
            foreach ($files as $file) {
                $tmple_file = $smarty->findResourcePath($file);
                if(!is_file($tmple_file)){
                    $tmple_file = TEMPLATE_PATH."/".$tmple_file;
                }
                $doc = file_get_contents($tmple_file);
                $buf .= "<!--" . $file . "-->\n";
                $buf .= $doc . "\n";
            }
        }

        if ($buf) {
            $tpl_output = str_replace("</head>", $buf . "\n</head>", $tpl_output);
        }
        return $tpl_output;
    }

    public function getRealTemplatePaths() {
        return $this->real_template_paths;
    }

    // 以下の4Method(string_get_...)は、渡された文字列をテンプレートとして使用するために使用する(construct時にregister済）
    public function string_get_template($tpl_name, &$tpl_source, &$smarty_obj)
    {
        $tpl_source = $this->string;
        return true;
    }
    public function string_get_timestamp($tpl_name, &$tpl_timestamp, &$smarty_obj)
    {
        $tpl_timestamp = time();
        return true;
    }
    public function string_get_secure($tpl_name, &$smarty_obj)
    { // 全てのテンプレートがセキュアであると仮定します
        return true;
    }
    public function string_get_trusted($tpl_name, &$smarty_obj)
    { // テンプレートから使用しません
    }

}

class RCMSAuth_smary{

    public function isSuper() {
        return RCMSUser::getUser()->isSuper();
    }

    public function isAdmin($module_type, $type) {
        return RCMSUser::getUser()->isAdmin($module_type, $type);
    }

    // 権限を持つのか判定
    public function canSelect($target, $lang = null) {
        $auth = RCMSUser::getUser()->getResourceAuth($target, 'select', $lang);
        return $auth->isAble();
    }

    public function canInsert($target, $lang = null) {
        $auth = RCMSUser::getUser()->getResourceAuth($target, 'insert', $lang);
        return $auth->isAble();
    }

    public function canUpdate($target, $lang = null) {
        $auth = RCMSUser::getUser()->getResourceAuth($target, 'update', $lang);
        return $auth->isAble();
    }

    public function canDelete($target, $lang = null) {
        $auth = RCMSUser::getUser()->getResourceAuth($target, 'delete', $lang);
        return $auth->isAble();
    }

    // 承認不要なのか判定
    public function unlimitedInsert($target, $lang = null) {
        $auth = RCMSUser::getUser()->getResourceAuth($target, 'insert', $lang);
        return $auth->isUnlimited();
    }

    public function unlimitedUpdate($target, $lang = null) {
        $auth = RCMSUser::getUser()->getResourceAuth($target, 'update', $lang);
        return $auth->isUnlimited();
    }

    public function unlimitedDelete($target, $lang = null) {
        $auth = RCMSUser::getUser()->getResourceAuth($target, 'delete', $lang);
        return $auth->isUnlimited();
    }

    // 承認が必要なのか判定
    public function limitedInsert($target, $lang = null) {
        return !$this->unlimitedInsert($target, $lang);
    }

    public function limitedUpdate($target, $lang = null) {
        return !$this->unlimitedUpdate($target, $lang);
    }

    public function limitedDelete($target, $lang = null) {
        return !$this->unlimitedDelete($target, $lang);
    }
}
