<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$GLOBALS['mm_alias']['count'] = 0;

/***
 * Hooks
 */
$GLOBALS['TL_HOOKS']['getContentElement'][] = array('MMChangeLanguage', 'findMMLists');
$GLOBALS['TL_HOOKS']['translateUrlParameters'][] = array('MMChangeLanguage', 'translateAlias');

