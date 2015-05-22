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
	return JError::raiseError(JText::_('Phoca Cart Error'), JText::_('Phoca Cart is not installed on your system'));
}
if (! class_exists('PhocaCartLoader')) {
    require_once( JPATH_ADMINISTRATOR.'/components/com_phocacart/libraries/loader.php');
}

phocacartimport('phocacart.utils.settings');
phocacartimport('phocacart.filter.filter');
phocacartimport('phocacart.path.route');
phocacartimport('phocacart.product.product');
phocacartimport('phocacart.image.image');

$p['item_ordering']	= $params->get( 'item_ordering', 1 );
$p['item_limit']	= $params->get( 'item_limit', 1 );
$products			= PhocaCartProduct::getProducts($p['item_limit'], $p['item_ordering']);
$t['pathitem'] 		= PhocaCartpath::getPath('productimage');


require(JModuleHelper::getLayoutPath('mod_phocacart_product'));
?>