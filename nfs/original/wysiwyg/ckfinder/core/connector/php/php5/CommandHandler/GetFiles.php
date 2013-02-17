<?php
/*
 * CKFinder
 * ========
 * http://ckfinder.com
 * Copyright (C) 2007-2012, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 */
if (!defined('IN_CKFINDER')) exit;

/**
 * @package CKFinder
 * @subpackage CommandHandlers
 * @copyright CKSource - Frederico Knabben
 */

/**
 * Include base XML command handler
 */
require_once CKFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/XmlCommandHandlerBase.php";

/**
 * Handle GetFiles command
 *
 * @package CKFinder
 * @subpackage CommandHandlers
 * @copyright CKSource - Frederico Knabben
 */
class CKFinder_Connector_CommandHandler_GetFiles extends CKFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    /**
     * Command name
     *
     * @access private
     * @var string
     */
    private $command = "GetFiles";

    /**
     * handle request and build XML
     * @access protected
     *
     */
    protected function buildXml()
    {

        global $baseUrl;
        $resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();
        if($resourceTypeInfo->getUrl()==$baseUrl."photo/"){
            return $this->buildXml_fromRCMSPhoto();
        }elseif($resourceTypeInfo->getUrl()==$baseUrl."flickr/"){
            return $this->buildXml_fromRCMSFlicker();
        }else{
            return $this->buildXml_fromFiles();
        }
    }

    /**
     * handle request and build XML
     * @access protected
     *
     */
    protected function buildXml_fromRCMSFlicker()
    {
require_once LIB_PATH.'/zend/Zend/Service/Flickr.php';

         global $cn;
$flickr = new Zend_Service_Flickr('ec6ee02925b2ba7940a36b6a83a16935');
/* 04f10d9fcde33efe */
$results = $flickr->userSearch("Gaku@STUDIO-Freesia",array("per_page"=>40));

foreach ($results as $result) {
    $files[] = array(
                     "filenm" => $result->Medium->uri,
                     "thumb" => $result->Thumbnail->uri,
                     "filemtime" => $result->dateupload,
                     );
}
        // Create the "Files" node.
        $oFilesNode = new Ckfinder_Connector_Utils_XmlNode("Files");
        $this->_connectorNode->addChild($oFilesNode);

        if (sizeof($files)>0) {

            $i=0;
            foreach ($files as $file) {
                $filemtime = $file["filemtime"];

                //otherwise file doesn't exist or we can't get it's filename properly
                if ($filemtime !== false) {

                    $oFileNode[$i] = new Ckfinder_Connector_Utils_XmlNode("File");
                    $oFilesNode->addChild($oFileNode[$i]);
                    $oFileNode[$i]->addAttribute("name", $file["filenm"]);
                    $oFileNode[$i]->addAttribute("date", @date("YmdHi", $filemtime));
                    $oFileNode[$i]->addAttribute("thumb", $file["thumb"]);
                    $oFileNode[$i]->addAttribute("size", 0);
                    $i++;
                }
            }
        }
    }


    /**
     * handle request and build XML
     * @access protected
     * RCMS‘Î‰ž
     */
    protected function buildXml_fromRCMSPhoto()
    {
         global $cn;

         $arrPath = explode("/",str_replace(ROOT_DIR,"",$this->_currentFolder->getServerPath()));

         if($arrPath[4]){
		         $arrWhere = array();

		         $arrWhere["dflg"] = array("type"=>"int","value"=>"0");
		         $arrWhere["season"] = array("type"=>"int","value"=>$arrPath[4]);

		         $photo_category_list = Photo::getCategoryTreeList($cn);

		         foreach($photo_category_list as $val2){
		             if($val2["category_nm"] == $arrPath[5] && $arrPath[6]){
		                 foreach($photo_category_list as $val3){
		                     if($val2["photo_category_id"] == $val3["parent_id"] && $val3["category_nm"] == $arrPath[6]){
		                         $arrWhere["category_id"] = array("type"=>"int","value"=>$val3["photo_category_id"]);
		                     }
		                 }
		             }elseif($val2["category_nm"] == $arrPath[5] && !$arrPath[6]){
		                 $arrWhere["category_id"] = array("type"=>"int","value"=>$val2["photo_category_id"]);
		             }
		         }
		         if($_SESSION["user_img_flg"] == 1 && !$_SESSION["super_flg"]) { $arrWhere["member_id"]   = array("type"=>"int","value"=>$_SESSION['member_id']);}



		         $strSQL = "select * from t_photo_header ".
		               makeWhere($arrWhere)." order by ymd desc" ;
		         $result = selectQuery($cn, $strSQL);

		         while ($row = getRow($result)) {
		             $files[] = $row;
		         }

        }

        // Create the "Files" node.
        $oFilesNode = new Ckfinder_Connector_Utils_XmlNode("Files");
        $this->_connectorNode->addChild($oFilesNode);

        $_sServerDir = PHOTO_SAVE_DIR."/";
        if (sizeof($files)>0) {

            natcasesort($files);
            $i=0;
            foreach ($files as $file) {
                $filename = $file["photo_id"].".jpg";
                $filemtime = @filemtime($_sServerDir . $filename);

                //otherwise file doesn't exist or we can't get it's filename properly
                if ($filemtime !== false) {

                    $oFileNode[$i] = new Ckfinder_Connector_Utils_XmlNode("File");
                    $oFilesNode->addChild($oFileNode[$i]);
                    $oFileNode[$i]->addAttribute("name", $filename);
                    $oFileNode[$i]->addAttribute("date", @date("YmdHi", $filemtime));
                    $oFileNode[$i]->addAttribute("thumb", $filename);
                    $size = filesize(PHOTO_SAVE_DIR."/".$filename);
                    if ($size && $size<1024) {
                        $size = 1;
                    }
                    else {
                        $size = (int)round($size / 1024);
                    }

                    $oFileNode[$i]->addAttribute("size", $size);
                    $i++;
                }
            }
        }
    }

    /**
     * handle request and build XML
     * @access protected
     *
     */
    protected function buildXml_fromFiles()
    {
        $_config =& CKFinder_Connector_Core_Factory::getInstance("Core_Config");
        if (!$this->_currentFolder->checkAcl(CKFINDER_CONNECTOR_ACL_FILE_VIEW)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        // Map the virtual path to the local server path.
        $_sServerDir = $this->_currentFolder->getServerPath();

        // Create the "Files" node.
        $oFilesNode = new Ckfinder_Connector_Utils_XmlNode("Files");
        $this->_connectorNode->addChild($oFilesNode);

        if (!is_dir($_sServerDir)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_FOLDER_NOT_FOUND);
        }

        $files = array();
        $thumbFiles = array();

        if ($dh = @opendir($_sServerDir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != ".." && !is_dir($_sServerDir . $file)) {
                    $files[] = $file;
                }
            }
            closedir($dh);
        } else {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        }

        $resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();

        if (sizeof($files)>0) {
            $_thumbnailsConfig = $_config->getThumbnailsConfig();
            $_thumbServerPath = '';
            $_showThumbs = (!empty($_GET['showThumbs']) && $_GET['showThumbs'] == 1);
            if ($_thumbnailsConfig->getIsEnabled() && ($_thumbnailsConfig->getDirectAccess() || $_showThumbs)) {
                $_thumbServerPath = $this->_currentFolder->getThumbsServerPath();
            }

            natcasesort($files);
            $i=0;
            foreach ($files as $file) {
                $filemtime = @filemtime($_sServerDir . $file);

                //otherwise file doesn't exist or we can't get it's filename properly
                if ($filemtime !== false) {
                    $filename = CKFinder_Connector_Utils_Misc::mbBasename($file);
                    if (!$resourceTypeInfo->checkExtension($filename, false)) {
                        continue;
                    }
                    if ($resourceTypeInfo->checkIsHiddenFile($filename)) {
                        continue;
                    }
                    $oFileNode[$i] = new Ckfinder_Connector_Utils_XmlNode("File");
                    $oFilesNode->addChild($oFileNode[$i]);
                    $oFileNode[$i]->addAttribute("name", CKFinder_Connector_Utils_FileSystem::convertToConnectorEncoding(CKFinder_Connector_Utils_Misc::mbBasename($file)));
                    $oFileNode[$i]->addAttribute("date", @date("YmdHi", $filemtime));
                    if (!empty($_thumbServerPath) && preg_match(CKFINDER_REGEX_IMAGES_EXT, $filename)) {
                        if (file_exists($_thumbServerPath . $filename)) {
                            $oFileNode[$i]->addAttribute("thumb", $filename);
                        }
                        elseif ($_showThumbs) {
                            $oFileNode[$i]->addAttribute("thumb", "?" . $filename);
                        }
                    }
                    $size = filesize($_sServerDir . $file);
                    if ($size && $size<1024) {
                        $size = 1;
                    }
                    else {
                        $size = (int)round($size / 1024);
                    }
                    $oFileNode[$i]->addAttribute("size", $size);
                    $i++;
                }
            }
        }
    }
}
