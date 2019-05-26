<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;

$layoutV	= new JLayoutFile('button_product_view', null, array('component' => 'com_phocacart'));
$layoutP	= new JLayoutFile('product_price', null, array('component' => 'com_phocacart'));
$layoutR	= new JLayoutFile('product_rating', null, array('component' => 'com_phocacart'));

echo '<div class="ph-product-module-box'.$moduleclass_sfx .'">';

if ($p['module_description'] != '') {
	echo '<div class="ph-mod-desc">'.$p['module_description'].'</div>';
}
if (!empty($products)) {
	foreach ($products as $k => $v) {

		echo '<div class="ph-item-box">';

		echo '<div class="thumbnail ph-thumbnail">';

		$image = PhocacartImage::getThumbnailName($t['pathitem'], $v->image, 'medium');
		$link = JRoute::_(PhocacartRoute::getItemRoute($v->id, $v->catid, $v->alias, $v->catalias));
		echo '<a href="'.$link.'">';
		if (isset($image->rel) && $image->rel != '') {
			echo '<img src="'.JURI::base(true).'/'.$image->rel.'" alt="" class="img-responsive ph-image"';
			echo ' />';
		}
		echo '</a>';


		// CAPTION, DESCRIPTION
		echo '<div class="caption">';
		echo '<h3>'.$v->title.'</h3>';


		// REVIEW - STAR RATING
		if ((int)$pc['display_star_rating'] > 0) {
			$d							= array();
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
		if ($this->t['can_display_price']) {
			$price 				= new PhocacartPrice;
			$d					= array();
			$d['priceitems']	= $price->getPriceItems($v->price, $v->taxid, $v->taxrate, $v->taxcalculationtype, $v->taxtitle, $v->unit_amount, $v->unit_unit, 1, 1, $v->group_price);
			$d['priceitemsorig']= array();
			if ($v->price_original != '' && $v->price_original > 0) {
				$d['priceitemsorig'] = $price->getPriceItems($v->price_original, $v->taxid, $v->taxrate, $v->taxcalculationtype);
			}
			$d['class']			= 'ph-category-price-box';// we need the same class as category or items view
			$d['product_id']	= (int)$v->id;
			$d['typeview']		= 'Module';

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

		// VIEW PRODUCT BUTTON
		echo '<div class="ph-category-add-to-cart-box">';

		// :L: LINK TO PRODUCT VIEW
		if ((int)$p['display_view_product_button'] > 0) {
			$d									= array();
			$d['link']							= $link;
			$d['display_view_product_button'] 	= $p['display_view_product_button'];
			echo $layoutV->render($d);
		}


		echo '</div>';// end add to cart box
		echo '<div class="clearfix"></div>';


		echo '</div>';// end caption

		echo '</div>';// end thumbnail
		echo '</div>';// end ph-item-box
	}
}
echo '</div>';
?>


