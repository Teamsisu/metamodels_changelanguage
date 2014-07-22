<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use MetaModels\Attribute\TranslatedAlias\TranslatedAlias;

/**
 * Description of MMChangeLanguage
 *
 * @author tremlma
 */
class MMChangeLanguage extends \Controller
{

    public function __construct()
    {
        $this->Database = \Database::getInstance();
        parent::__construct();
    }


    public function translateAlias($arrParams, $strLanguage, $arrRootPage)
    {

        if (!$GLOBALS['METAMODELS']['aliasLookup']) {
            return $arrParams;
        }

        foreach ($GLOBALS['METAMODELS']['aliasLookup'] AS $objElement) {

            $objMM = MetaModels\Factory::byId($objElement->metamodel);
            $objMMAttrFilter = $objMM->getEmptyFilter();
            $arrFilterParams = $this->parseFilter($objElement->metamodel_filtering, $objMM);
            
            foreach ($arrFilterParams AS $param => $objMMAttribute) {

                if ($param == 'auto_item') {
                    $strAlias = \Input::get('auto_item');
                } else {
                    $strAlias = \Input::get($param);
                }

                $objFilterRule = new \MetaModels\Filter\Rules\SearchAttribute($objMMAttribute, $strAlias);
                $objMMAttrFilter->addFilterRule($objFilterRule);
                $objMMItem = $objMM->findByFilter($objMMAttrFilter)->getItem();

                if ($objMMAttribute instanceof TranslatedAlias) {
                    $result = $this->Database->prepare('SELECT * FROM tl_metamodel_translatedtext WHERE att_id = ? AND item_id = ? and langcode = ?')
                                    ->execute($objMMAttribute->get('id'), $objMMItem->get('id'), $strLanguage)->fetchAssoc();
                    $strAlias = $result['value'];
                }
                
                // 
                if ($param == 'auto_item') {
                    $arrParams['url']['items'] = $strAlias;
                }else{
                    $arrParams['url'][$param] = $strAlias;
                }
                
                
            }
        }

        return $arrParams;
    }

    public function findMMLists($objElement, $strBuffer)
    {

        switch ($objElement->type) {

            case 'metamodel_content':

                if ($objElement->metamodel_alias_lookup) {
                    $GLOBALS['METAMODELS']['aliasLookup'][] = $objElement;
                }

                break;

            case 'module':

                $objModule = \Contao\ModuleModel::findByPk($objElement->module);

                if ($objModule->type == 'metamodel_list' && $objModule->metamodel_alias_lookup) {
                    $GLOBALS['METAMODELS']['aliasLookup'][] = $objModule;
                }

                break;
        }


        return $strBuffer;
    }

    private function parseFilter($filterID, $objMM)
    {
        
        // get all filters from filterset
        $resultFilters = $this->Database->prepare('SELECT * FROM tl_metamodel_filtersetting WHERE fid = ? ORDER BY sorting DESC')->execute($filterID);
        $arrParams = array();
        
        while($resultFilters->next())
        {
            $objMMAttribute = $objMM->getAttributeById($resultFilters->attr_id);
            if(!$resultFilters->urlparam){
                $arrParams[$objMMAttribute->get('colname')] = $objMMAttribute;
            }else{
                $arrParams[$resultFilters->urlparam] = $objMMAttribute;
            }
        }
        
        return $arrParams;
        
    }

}
