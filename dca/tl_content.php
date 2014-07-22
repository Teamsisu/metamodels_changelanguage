<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



$GLOBALS['TL_DCA']['tl_content']['palettes']['metamodel_content'] = str_replace('metamodel_meta_description;', 'metamodel_meta_description,metamodel_alias_lookup;', $GLOBALS['TL_DCA']['tl_content']['palettes']['metamodel_content']);


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['metamodel_alias_lookup'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['metamodel_alias_lookup'],
    'inputType' => 'checkbox',
    'eval'      => array('tl_class' => 'w50 m12'),
    'sql'       => "char(1) NOT NULL default '0'"
);
