<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 
defined('_JEXEC') or die('Restricted access');// no direct access

if (!JComponentHelper::isEnabled('com_phocacart', true)) {
	$app = JFactory::getApplication();
	$app->enqueueMessage(JText::_('Phoca Cart Error'), JText::_('Phoca Cart is not installed on your system'), 'error');
	return;
}
if (! class_exists('PhocaCartLoader')) {
    require_once( JPATH_ADMINISTRATOR.'/components/com_phocacart/libraries/loader.php');
}

phocacartimport('phocacart.utils.settings');
phocacartimport('phocacart.filter.filter');
phocacartimport('phocacart.path.path');
phocacartimport('phocacart.path.route');
phocacartimport('phocacart.price.price');
phocacartimport('phocacart.currency.currency');
phocacartimport('phocacart.product.product');
phocacartimport('phocacart.image.image');


$lang = JFactory::getLanguage();
//$lang->load('com_phocacart.sys');
$lang->load('com_phocacart');
JHTML::stylesheet('media/com_phocacart/css/main.css' );

$p['item_ordering']	= $params->get( 'item_ordering', 1 );
$p['item_limit']	= $params->get( 'item_limit', 1 );

// TODO - the following function can check publish, stock, price - this can be added to the parameters
$products			= PhocaCartProduct::getProducts($p['item_limit'], $p['item_ordering']);
$t['pathitem'] 		= PhocaCartpath::getPath('productimage');


require(JModuleHelper::getLayoutPath('mod_phocacart_product'));
?>