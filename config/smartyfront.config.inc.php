<?php
/*
* 2007-2012 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2012 PrestaShop SA
*  @version  Release: $Revision: 6844 $
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
global $smarty;
$smarty->setTemplateDir(_PS_THEME_DIR_.'tpl');

function smartyTranslate($params, &$smarty)
{
	global $_LANG;

	if (!isset($params['js'])) $params['js'] = 0;
	if (!isset($params['pdf'])) $params['pdf'] = false;
	if (!isset($params['mod'])) $params['mod'] = false;
	if (!isset($params['sprintf'])) $params['sprintf'] = null;

	$string = str_replace('\'', '\\\'', $params['s']);
	$filename = ((!isset($smarty->compiler_object) || !is_object($smarty->compiler_object->template)) ? $smarty->template_resource : $smarty->compiler_object->template->getTemplateFilepath());

	$basename = basename($filename, '.tpl');
	$key = $basename.'_'.md5($string);

	if (isset($smarty->source) && (strpos($smarty->source->filepath, DIRECTORY_SEPARATOR.'override'.DIRECTORY_SEPARATOR) !== false))
		$key = 'override_'.$key;

	if ($params['mod'])
		return Translate::getModuleTranslation($params['mod'], $params['s'], $basename, $params['sprintf']);
	else if ($params['pdf'])
		return Translate::getPdfTranslation($params['s']);

	if ($_LANG != null && isset($_LANG[$key]))
		$msg = $_LANG[$key];
	elseif ($_LANG != null && isset($_LANG[Tools::strtolower($key)]))
		$msg = $_LANG[Tools::strtolower($key)];
	else
		$msg = $params['s'];

	if ($msg != $params['s'])
		$msg = $params['js'] ? addslashes($msg) : stripslashes($msg);

	if ($params['sprintf'] !== null)
		$msg = Translate::checkAndReplaceArgs($msg, $params['sprintf']);

	return $params['js'] ? $msg : Tools::htmlentitiesUTF8($msg);
}

