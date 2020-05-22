<?php

namespace rs\sitemap\Core;

class generator extends \OxidEsales\Eshop\Core\Base
{
    public function getActShopId()
    {
        return $this->getConfig()->getShopId();
    }


    public function loadSettings($shopid)
    {
        $oConfig = $this->getConfig();
        $settings=[];
        $settings['alanguages']=explode("|",$oConfig->getConfigParam('rs-sitemap_main_language'));
        $settings['shop']=$oConfig->getConfigParam('rs-sitemap_shop_enable');
        $settings['shopfrequenz']=$oConfig->getConfigParam('rs-sitemap_shop_frequence');
        $settings['shopprio']=$oConfig->getConfigParam('rs-sitemap_shop_prio');

        $settings['category']=$oConfig->getConfigParam('rs-sitemap_category_enable');
        $settings['categoryfrequenz']=$oConfig->getConfigParam('rs-sitemap_category_frequence');
        $settings['categoryprio']=$oConfig->getConfigParam('rs-sitemap_category_prio');
        $settings['categorypics']=$oConfig->getConfigParam('rs-sitemap_category_pic');

        $settings['manufacturer']=$oConfig->getConfigParam('rs-sitemap_manufacturer_enable');
        $settings['manufacturerfrequenz']=$oConfig->getConfigParam('rs-sitemap_manufacturer_frequence');
        $settings['manufacturerprio']=$oConfig->getConfigParam('rs-sitemap_manufacturer_prio');
        $settings['manufacturerpics']=$oConfig->getConfigParam('rs-sitemap_manufacturer_pic');

        $settings['article']=$oConfig->getConfigParam('rs-sitemap_article_enable');
        $settings['articlefrequenz']=$oConfig->getConfigParam('rs-sitemap_article_frequence');
        $settings['articleprio']=$oConfig->getConfigParam('rs-sitemap_article_prio');
        $settings['articlepics']=$oConfig->getConfigParam('rs-sitemap_article_pic');
        $settings['articlevariants']=$oConfig->getConfigParam('rs-sitemap_article_variants');
        $settings['articlestock']=$oConfig->getConfigParam('rs-sitemap_article_stock');

        $settings['content']=$oConfig->getConfigParam('rs-sitemap_content_enable');
        $settings['contentfrequenz']=$oConfig->getConfigParam('rs-sitemap_content_frequence');
        $settings['contentprio']=$oConfig->getConfigParam('rs-sitemap_content_prio');
        return $settings;
    }


    public function getSetting($shopid, $value)
    {
        $settings=$this->loadSettings($shopid);
        return $settings[$value];
    }



    private function toXml($text)
    {
        $text = str_replace("&","&amp;",$text);
        $text = str_replace("'","&apos;",$text);
        $text = str_replace("<","&lt;",$text);
        $text = str_replace(">","&gt;",$text);
        $text = str_replace('"',"&quot;",$text);
        return trim($text);
    }
    public function generateHeader()
    {
        $returnvalue="";
        $returnvalue.=("<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n");
        $returnvalue.=("<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:image=\"http://www.google.com/schemas/sitemap-image/1.1\">\r\n");
        return $returnvalue;
    }
    public function generateFooter()
    {
        $returnvalue="";
        $returnvalue.=('</urlset>');
        return $returnvalue;
    }
    public function correctLink($link)
    {
        if(strrpos($link,"?")!==false)
        {
            $tmp = explode("?", $link);
            $link = $tmp[0];
        }
        return $link;
    }

    public function generateShop($iLang)
    {

        $sShopID = $this->getActShopId();

        $loc=$this->correctLink($this->getConfig()->getShopUrl( $iLang, false ));
        $lastmod = date("Y-m-d");
        $changefreq=$this->getSetting($sShopID, 'shopfrequenz');
        $priority=$this->getSetting($sShopID, 'shopprio');

        $returnvalue="";
        $returnvalue.="<url>\r\n";
        $returnvalue.="<loc>".$this->toXml($loc)."</loc>\r\n";
        $returnvalue.="<lastmod>".$lastmod."</lastmod>\r\n";
        $returnvalue.="<changefreq>".$changefreq."</changefreq>\r\n";
        $returnvalue.="<priority>".$priority."</priority>\r\n";
        $returnvalue.="</url>\r\n";

        return $returnvalue;

    }

    public function generateCategory($LangId, $sCatId)
    {
        $oCat=oxNew('oxCategory');
        $oCat->loadInLang( $LangId, $sCatId );
        if($oCat)
        {
            $sShopID = $this->getActShopId();

            /**
             * @var \OxidEsales\Eshop\Application\Model\SeoEncoderCategory $seoEncoderCategory
             */
            $seoEncoderCategory = oxNew(\OxidEsales\Eshop\Application\Model\SeoEncoderCategory::class);
            $link = $seoEncoderCategory->getCategoryPageUrl($oCat,$LangId);
            $loc=$this->correctLink($link);
            $date = date("Y-m-d");
            $changefreq=$this->getSetting($sShopID, 'categoryfrequenz');
            $priority=$this->getSetting($sShopID, 'categoryprio');

            $returnvalue="";
            $returnvalue.="<url>\r\n";
            $returnvalue.="<loc>".$this->toXml($loc)."</loc>\r\n";
            $returnvalue.="<lastmod>".$date."</lastmod>\r\n";
            $returnvalue.="<changefreq>".$changefreq."</changefreq>\r\n";
            $returnvalue.="<priority>".$priority."</priority>\r\n";

            if($this->getSetting($sShopID, 'categorypics')=="1")
            {
                $pic=$oCat->getThumbUrl();
                $title=$oCat->getTitle();
                if($pic!="")
                {
                    $returnvalue.="<image:image>\r\n";
                    $returnvalue.="<image:loc>".$this->toXml($pic)."</image:loc>\r\n";
                    $returnvalue.="<image:title>".$this->toXml($title)."</image:title>\r\n";
                    $returnvalue.="</image:image>\r\n";
                }
                $pic=$oCat->getIconUrl();
                if($pic!="")
                {
                    $returnvalue.="<image:image>\r\n";
                    $returnvalue.="<image:loc>".$this->toXml($pic)."</image:loc>\r\n";
                    $returnvalue.="<image:title>".$this->toXml($title)."</image:title>\r\n";
                    $returnvalue.="</image:image>\r\n";
                }
            }

            $returnvalue.="</url>\r\n";

            return $returnvalue;
        }
        return "";
    }

    public function generateArticle($LangId, $sArtId)
    {

        $oArt=oxNew('oxArticle');
        $oArt->loadInLang( $LangId, $sArtId );
        if($oArt)
        {
            $sShopID = $this->getActShopId();

            /**
             * @var \OxidEsales\Eshop\Application\Model\SeoEncoderArticle $seoEncoder
             */
            $seoEncoder = oxNew(\OxidEsales\Eshop\Application\Model\SeoEncoderArticle::class);
            $link=$seoEncoder->getArticleMainUrl($oArt,$LangId);
            $loc=$this->correctLink($link);

            $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            $sqlstring="select oxtimestamp from oxarticles where oxid=".$oDb->quote($oArt->getId());
            $date = $oDb->getOne($sqlstring);
            $date = substr($date,0,10);
            $changefreq=$this->getSetting($sShopID, 'articlefrequenz');
            $priority=$this->getSetting($sShopID, 'articleprio');

            $returnvalue="";
            $returnvalue.="<url>\r\n";


            $returnvalue.="<loc>".$this->toXml($loc)."</loc>\r\n";
            $returnvalue.="<lastmod>".$date."</lastmod>\r\n";
            $returnvalue.="<changefreq>".$changefreq."</changefreq>\r\n";
            $returnvalue.="<priority>".$priority."</priority>\r\n";

            if($this->getSetting($sShopID, 'articlepics')=="1")
            {
                for($x=1;$x<6;$x++)
                {
                    $pic=$oArt->getZoomPictureUrl( $x );
                    $title=trim($oArt->oxarticles__oxtitle->value." ".$oArt->oxarticles__oxvarselect->value);
                    if($pic!="")
                    {
                        $returnvalue.="<image:image>\r\n";
                        $returnvalue.="<image:loc>".$this->toXml($pic)."</image:loc>\r\n";
                        $returnvalue.="<image:title>".$this->toXml($title)."</image:title>\r\n";
                        $returnvalue.="</image:image>\r\n";
                    }
                }
            }

            $returnvalue.="</url>\r\n";

            return $returnvalue;
        }
        return "";
    }

    public function generateManufacturer($LangId, $sManuId)
    {

        $oMan=oxNew('oxManufacturer');
        $oMan->loadInLang( $LangId, $sManuId );
        if($oMan)
        {
            $sShopID = $this->getActShopId();

            /**
             * @var \OxidEsales\Eshop\Application\Model\SeoEncoderManufacturer $seoEncoder
             */
            $seoEncoder = oxNew(\OxidEsales\Eshop\Application\Model\SeoEncoderManufacturer::class);
            $link=$seoEncoder->getManufacturerUrl($oMan,$LangId);
            $loc=$this->correctLink($link);

            $date = date("Y-m-d");
            $changefreq=$this->getSetting($sShopID, 'manufacturerfrequenz');
            $priority=$this->getSetting($sShopID, 'manufacturerprio');

            $returnvalue="";
            $returnvalue.="<url>\r\n";
            $returnvalue.="<loc>".$this->toXml($loc)."</loc>\r\n";
            $returnvalue.="<lastmod>".$date."</lastmod>\r\n";
            $returnvalue.="<changefreq>".$changefreq."</changefreq>\r\n";
            $returnvalue.="<priority>".$priority."</priority>\r\n";

            if($this->getSetting($sShopID, 'manufacturerpics')=="1")
            {
                $pic=$oMan->getIconUrl();
                $title=$oMan->getTitle();
                if($pic!="")
                {
                    $returnvalue.="<image:image>\r\n";
                    $returnvalue.="<image:loc>".$this->toXml($pic)."</image:loc>\r\n";
                    $returnvalue.="<image:title>".$this->toXml($title)."</image:title>\r\n";
                    $returnvalue.="</image:image>\r\n";
                }
            }

            $returnvalue.="</url>\r\n";

            return $returnvalue;
        }
        return "";
    }

    public function generateContent($LangId, $sConId)
    {
        $oCon=oxNew('oxContent');
        $oCon->loadInLang( $LangId, $sConId );
        if($oCon)
        {
            $sShopID = $this->getActShopId();

            /**
             * @var \OxidEsales\Eshop\Application\Model\SeoEncoderContent $seoEncoder
             */
            $seoEncoder = oxNew(\OxidEsales\Eshop\Application\Model\SeoEncoderContent::class);
            $link=$seoEncoder->getContentUrl($oCon,$LangId);
            /*$link=$oCon->getLink();*/
            $loc=$this->correctLink($link);
            $date = date("Y-m-d");
            $changefreq=$this->getSetting($sShopID, 'contentfrequenz');
            $priority=$this->getSetting($sShopID, 'contentprio');

            $returnvalue="";
            $returnvalue.="<url>\r\n";
            $returnvalue.="<loc>".$this->toXml($loc)."</loc>\r\n";
            $returnvalue.="<lastmod>".$date."</lastmod>\r\n";
            $returnvalue.="<changefreq>".$changefreq."</changefreq>\r\n";
            $returnvalue.="<priority>".$priority."</priority>\r\n";
            $returnvalue.="</url>\r\n";

            return $returnvalue;
        }
        return "";
    }
}
