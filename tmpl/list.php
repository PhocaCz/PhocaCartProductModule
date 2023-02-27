<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

$layoutV	= new JLayoutFile('button_product_view', null, array('component' => 'com_phocacart'));
$layoutP	= new JLayoutFile('product_price', null, array('component' => 'com_phocacart'));
$layoutR	= new JLayoutFile('product_rating', null, array('component' => 'com_phocacart'));
$layoutI	= new JLayoutFile('product_image', null, array('component' => 'com_phocacart'));

$app = Factory::getApplication();
$wa = $app->getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('mod_phocacart_product', 'media/mod_phocacart_product/css/style.css', array('version' => 'auto'));

echo '<div class="ph-product-module-box '.$moduleclass_sfx .'">';

if ($p['module_description'] != '') {
	echo '<div class="ph-mod-desc">'.$p['module_description'].'</div>';
}
if (!empty($products)) {
	foreach ($products as $k => $v) {

		echo '<div class="ph-item-box ph-product-module-item-box">';

		echo '<div class="ph-product-module-item-box-image">';

		echo '<div class="'.$s['c']['thumbnail'].' ph-thumbnail">';

		//$image = PhocacartImage::getThumbnailName($t['pathitem'], $v->image, 'medium');
		if (!isset($v->additional_image)) { $v->additional_image = '';}
		$lt = 'list';
		$attributesOptions = array();


		$image = PhocacartImage::getImageDisplay($v->image, $v->additional_image, $t['pathitem'], $t['switch_image_category_items'], $t['image_width_cat'], $t['image_height_cat'], 'small', $lt, $attributesOptions);
		$link = JRoute::_(PhocacartRoute::getItemRoute($v->id, $v->catid, $v->alias, $v->catalias));
		/*echo '<a href="'.$link.'">';
		if (isset($image->rel) && $image->rel != '') {
			echo '<img src="'.JURI::base(true).'/'.$image->rel.'" alt="" class="'.$s['c']['img-responsive'].' ph-image"';
			echo ' />';
		}
		echo '</a>';*/

		$dI	= array();
		if (isset($image['image']->rel) && $image['image']->rel != '') {
			$dI['t']				= $t;
			$dI['s']				= $s;
			$dI['product_id']		= (int)$v->id;
			$dI['layouttype']		= $lt;
			$dI['title']			= $v->title;
			$dI['image']			= $image;
			$dI['typeview']			= 'Module' . (int)$module->id . 'P';;
		}
		// :L: IMAGE
		echo '<a href="'.$link.'">';
		if (!empty($dI)) { echo $layoutI->render($dI);}
		echo '</a>';

		echo '</div>';// end thumbnail

		echo '</div>';// end ph-product-module-item-box-image
		echo '<div class="ph-product-module-item-box-content">';


		// CAPTION, DESCRIPTION
		echo '<div class="'.$s['c']['caption'].' ">';
		echo '<h4>';
		echo '<a href="'.$link.'">';
		echo $v->title;
		echo '</a>';
		echo '</h4>';


		// REVIEW - STAR RATING
		if ((int)$pc['display_star_rating'] > 0) {
			$d							= array();
			$d['s']                     = $s;
			$d['rating']				= isset($v->rating) && (int)$v->rating > 0 ? (int)$v->rating : 0;
			$d['size']					= 16;
			$d['display_star_rating']	= (int)$pc['display_star_rating'];
			echo $layoutR->render($d);
		}

		// Description box will be displayed even no description is set - to set height and have all columns same height
		echo '<div class="ph-item-desc">';
		if ($v->description != '' && (int)$p['display_product_description'] > 0) {
			echo $v->description;
		}
		echo '</div>';// end desc

		// :L: PRICE
		if ($p['can_display_price']) {
			$price 				= new PhocacartPrice;
			$d					= array();
			$d['s']             = $s;
			$d['type']			= $v->type;// PRODUCTTYPE
			$d['priceitems']	= $price->getPriceItems($v->price, $v->taxid, $v->taxrate, $v->taxcalculationtype, $v->taxtitle, $v->unit_amount, $v->unit_unit, 1, 1, $v->group_price, $v->taxhide);
			$d['priceitemsorig']= array();
			if ($v->price_original != '' && $v->price_original > 0) {
				$d['priceitemsorig'] = $price->getPriceItems($v->price_original, $v->taxid, $v->taxrate, $v->taxcalculationtype, '', 0, '', 0, 1, null, $v->taxhide);
			}
			$d['class']			= 'ph-category-price-box';// we need the same class as category or items view
			$d['product_id']	= (int)$v->id;
			$d['typeview']		= 'Module' . (int)$module->id . 'P';

			// Display discount price
			// Move standard prices to new variable (product price -> product discount)
			$d['priceitemsdiscount']		= $d['priceitems'];
			$d['discount'] 					= PhocacartDiscountProduct::getProductDiscountPrice($v->id, $d['priceitemsdiscount']);

			// Display cart discount (global discount) in product views - under specific conditions only
			// Move product discount prices to new variable (product price -> product discount -> product discount cart)
			$d['priceitemsdiscountcart']	= $d['priceitemsdiscount'];
			$d['discountcart']				= PhocacartDiscountCart::getCartDiscountPriceForProduct($v->id, $v->catid, $d['priceitemsdiscountcart']);

			$d['zero_price']		= 1;// Apply zero price if possible
			echo $layoutP->render($d);
		}


		echo '</div>';// end caption



		echo '</div>';// end ph-product-module-item-box-content


		echo '<div class="ph-product-module-item-box-action">';

		// VIEW PRODUCT BUTTON
		echo '<div class="ph-category-add-to-cart-box">';

		// :L: LINK TO PRODUCT VIEW
		if ((int)$p['display_view_product_button'] > 0) {
			$d									= array();
			$d['s']                             = $s;
			$d['link']							= $link;
			$d['display_view_product_button'] 	= $p['display_view_product_button'];
			echo $layoutV->render($d);
		}
		echo '</div>';// end add to cart box
		//echo '<div class="ph-cb"></div>';

		echo '</div>';// end ph-product-module-item-box-action

		echo '</div>';// end ph-item-box
	}
}
echo '</div>';
?>


