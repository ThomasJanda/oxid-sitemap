<?php
namespace rs\sitemap\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Request;

class rs_sitemap_generator extends \OxidEsales\Eshop\Application\Controller\Admin\AdminController
{

    /*
    $request = oxNew(Request::class);
    $this->_aViewData['formedit_project']=$request->getRequestEscapedParameter("project");
    $this->_aViewData['formedit_index1']=$request->getRequestEscapedParameter("index1value");
    $this->_aViewData['formedit_index2']=$request->getRequestEscapedParameter("index2value");
    $this->_aViewData['formedit_language']=$this->_iEditLang;
    $this->_aViewData['formedit_navi']=$request->getRequestEscapedParameter("navi");
    */

    protected $_template="rs_sitemap_generator.tpl";

    protected $_status="";
    protected $_reload=false;
    protected $_typ="";
    protected $_offset=0;

    public function render()
    {
        parent::render();

        $sShopID = $this->getConfig()->getShopId();

        $request = oxNew(Request::class);
        $action=$request->getRequestEscapedParameter("action");
        if($action=="generate")
        {
            $this->generate();
        }
        $this->_aViewData["reload"]=$this->_reload;
        $this->_aViewData["status"]=$this->_status;
        $this->_aViewData["typ"]=$this->_typ;
        $this->_aViewData["offset"]=$this->_offset;

        return $this->_template;
    }

    private function _getUrl()
    {
        $this->_getPath();
        $sUrl = $this->getConfig()->getShopUrl()."export/";
        return $sUrl;
    }
    private function _getPath()
    {
        $sDir = $this->getConfig()->getConfigParam('sShopDir')."export";
        @mkdir($sDir);
        return $sDir."/";
    }

    private function _getFilename()
    {
        $sShopID = $this->getConfig()->getShopId();
        $filename=$this->_getPath().$sShopID.".xml";
        return $filename;
    }

    public function getfileurl()
    {
        if(file_exists($this->_getFilename()))
        {
            $sShopID = $this->getConfig()->getShopId();
            $filename=$this->_getUrl().$sShopID.".xml";
            return $filename;
        }
    }

    private function generate()
    {
        $sShopID = $this->getConfig()->getShopId();

        /**
         * @var \rs\sitemap\Core\generator $emcgoogletoolssitemap
         */
        $emcgoogletoolssitemap=oxNew(\rs\sitemap\Core\generator::class);
        $settings=$emcgoogletoolssitemap->loadSettings($sShopID);


        $request = oxNew(Request::class);
        $typ=$request->getRequestEscapedParameter("typ");
        $offset=$request->getRequestEscapedParameter("offset");

        $filename=$this->_getFilename();
        $filetext="";
        $reload=true;
        $itemspertick=20;
        $status="";

        if($typ=="")
        {
            $status="Generiere XML";
            @unlink($filename);
            $filetext=$emcgoogletoolssitemap->generateHeader();
            $typ="shop";
        }
        elseif($typ=="shop")
        {
            $status="Generiere Shop";
            if($settings['shop'])
            {
                foreach($settings['alanguages'] as $LangId)
                {
                    $filetext.=$emcgoogletoolssitemap->generateShop($LangId);
                }
            }
            $typ="category";
        }
        elseif($typ=="category")
        {
            $status="Generiere Kategorie (Start ab ".$offset.")";
            if($settings['category'])
            {
                $oCat=oxnew('oxcategory');
                $sView=$oCat->getViewName();

                $sSql="select oxid from $sView where ".$oCat->getSqlActiveSnippet()." limit ".$offset.",".$itemspertick;
                $resultSet = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->select($sSql);
                //Fetch the results row by row
                if ($resultSet != false && $resultSet->count() > 0) {
                    while (!$resultSet->EOF) {
                        $sCatId = $resultSet->fields[0];
                        foreach($settings['alanguages'] as $LangId)
                        {
                            $filetext.=$emcgoogletoolssitemap->generateCategory($LangId, $sCatId);
                        }
                        $resultSet->fetchRow();
                    }
                }
            }
            if($filetext=="")
            {
                $offset=0;
                $typ="article";
            }
            else
            {
                $offset+=$itemspertick;
            }

        }
        elseif($typ=="article")
        {
            $status="Generiere Artikel (Start ab ".$offset.")";
            if($settings['article'])
            {
                $oArt=oxNew('oxArticle');

                $sView=$oArt->getViewName();
                $where=$oArt->getActiveCheckQuery( );
                if($settings['articlestock'])
                    $where.=$oArt->getStockCheckQuery( );
                if($settings['articlevariants'])
                    $where.="and $sView.oxparentid='' ";
                $where=" (".$where.") ";
                $sSql="select oxid from $sView where ".$where." limit ".$offset.",".$itemspertick;

                $resultSet = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->select($sSql);
                //Fetch the results row by row
                if ($resultSet != false && $resultSet->count() > 0) {
                    while (!$resultSet->EOF) {
                        $sId = $resultSet->fields[0];
                        foreach($settings['alanguages'] as $LangId)
                        {
                            $filetext.=$emcgoogletoolssitemap->generateArticle($LangId, $sId);
                        }
                        $resultSet->fetchRow();
                    }
                }
            }
            if($filetext=="")
            {
                $offset=0;
                $typ="manufacturer";
            }
            else
            {
                $offset+=$itemspertick;
            }

        }
        elseif($typ=="manufacturer")
        {
            $status="Generiere Hersteller (Start ab ".$offset.")";
            if($settings['manufacturer'])
            {
                $oManu=oxNew('oxManufacturer');
                $sView=$oManu->getViewName();
                $sSql="select oxid from $sView where ".$oManu->getSqlActiveSnippet( )." limit ".$offset.",".$itemspertick;
                /*$sqlstring=str_replace($sView,'oxmanufacturers',$sqlstring);*/

                $resultSet = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->select($sSql);
                //Fetch the results row by row
                if ($resultSet != false && $resultSet->count() > 0) {
                    while (!$resultSet->EOF) {
                        $sId = $resultSet->fields[0];
                        foreach($settings['alanguages'] as $LangId)
                        {
                            $filetext.=$emcgoogletoolssitemap->generateManufacturer($LangId, $sId);
                        }
                        $resultSet->fetchRow();
                    }
                }

            }
            if($filetext=="")
            {
                $offset=0;
                $typ="content";
            }
            else
            {
                $offset+=$itemspertick;
            }

        }
        elseif($typ=="content")
        {
            $status="Generiere CMS-Seiten (Start ab ".$offset.")";
            if($settings['content'])
            {
                $oCont=oxnew('oxcontent');
                $sView=$oCont->getViewName();
                $sSql="select oxid from $sView where ".$oCont->getSqlActiveSnippet( )." and $sView.oxtype>0 limit ".$offset.",".$itemspertick;

                $resultSet = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->select($sSql);
                //Fetch the results row by row
                if ($resultSet != false && $resultSet->count() > 0) {
                    while (!$resultSet->EOF) {
                        $sId = $resultSet->fields[0];
                        foreach($settings['alanguages'] as $LangId)
                        {
                            $filetext.=$emcgoogletoolssitemap->generateContent($LangId, $sId);
                        }
                        $resultSet->fetchRow();
                    }
                }
            }
            if($filetext=="")
            {
                $offset=0;
                $typ="end";
            }
            else
            {
                $offset+=$itemspertick;
            }

        }
        elseif($typ=="end")
        {
            $status="XML fertiggestellt";
            $filetext=$emcgoogletoolssitemap->generateFooter();
            $reload=false;
            $typ="";
        }


        if($filetext!="")
        {
            $fp=fopen($filename,'a');
            if($fp)
            {
                fwrite($fp,$filetext);
            }
            else
            {
                die('Sitemap kann nicht angelegt werden');
            }
            @fclose($fp);
        }

        $this->_status=$status;
        $this->_offset=$offset;
        $this->_typ=$typ;
        $this->_reload=$reload;

    }
}
