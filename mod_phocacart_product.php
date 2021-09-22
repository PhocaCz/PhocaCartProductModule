<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;// no direct access

if (!JComponentHelper::isEnabled('com_phocacart', true)) {
	$app = JFactory::getApplication();
	$app->enqueueMessage(JText::_('Phoca Cart Error'), JText::_('Phoca Cart is not installed on your system'), 'error');
	return;
}

JLoader::registerPrefix('Phocacart', JPATH_ADMINISTRATOR . '/components/com_phocacart/libraries/phocacart');


$p									= array();
$p['load_component_media']			= $params->get( 'load_component_media', 1 );

$p['item_ordering']					= $params->get( 'item_ordering', 1 );
$p['item_limit']					= $params->get( 'item_limit', 1 );
$p['hide_price']					= $params->get( 'hide_price', 0 );
$p['display_view_product_button']	= $params->get( 'display_view_product_button', 1 );
$p['catid_multiple']				= $params->get( 'catid_multiple', array() );
$p['featured_only']					= $params->get( 'featured_only', 0 );
//$p['stock_checking']				= $params->get( 'stock_checking', 0 );// in module display all products

$p['display_product_description']	= $params->get( 'display_product_description', 0 );
$p['module_description']			= $params->get( 'module_description', '' );



$lang = JFactory::getLanguage();
//$lang->load('com_phocacart.sys');
$lang->load('com_phocacart');


$s = PhocacartRenderStyle::getStyles();
if ($p['load_component_media'] == 1) {
	$media = PhocacartRenderMedia::getInstance('main');
	$media->loadBase();
	$media->loadBootstrap();
	$media->loadSpec();
}




$rights							= new PhocacartAccessRights();
$p['can_display_price']	= $rights->canDisplayPrice();
if ($p['hide_price'] == 1) {
    $p['can_display_price'] = false;// override the component rights
}

$moduleclass_sfx 					= htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

$pCom								= PhocacartUtils::getComponentParameters();
$pc['display_star_rating']			= $pCom->get( 'display_star_rating', 0 );

$t['switch_image_category_items']	= $pCom->get( 'switch_image_category_items', 0 );
$t['image_width_cat']				= $pCom->get( 'image_width_cat', '' );
$t['image_height_cat']				= $pCom->get( 'image_height_cat', '' );
$t['lazy_load_category_items']		= $pCom->get( 'lazy_load_category_items', 0 );
$t['medium_image_width']			= $pCom->get( 'medium_image_width', 300 );
$t['medium_image_height']			= $pCom->get( 'medium_image_height', 200 );
$t['display_webp_images']			= $pCom->get( 'display_webp_images', 0 );


// TO DO - the following function can check publish, stock, price - this can be added to the parameters
$products			= PhocacartProduct::getProducts(0, $p['item_limit'], $p['item_ordering'], 0, true, false, false, 0, $p['catid_multiple'], $p['featured_only'], array(0,1), '', '', true);
$t['pathitem'] 		= PhocacartPath::getPath('productimage');


require(JModuleHelper::getLayoutPath('mod_phocacart_product', $params->get('layout', 'default')));
?>
