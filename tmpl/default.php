<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

$layoutV	= new JLayoutFile('button_product_view', $basePath = JPATH_ROOT .'/components/com_phocacart/layouts');
$layoutP	= new JLayoutFile('product_price', $basePath = JPATH_ROOT .'/components/com_phocacart/layouts');

echo '<div class="ph-product-module-box">';
if (!empty($products)) {
	foreach ($products as $k => $v) {
		
		echo '<div class="thumbnail ph-thumbnail">';

		$image = PhocaCartImage::getThumbnailName($t['pathitem'], $v->image, 'medium');
		$link = JRoute::_(PhocaCartRoute::getItemRoute($v->id, $v->catid, $v->alias, $v->categoryalias));
		echo '<a href="'.$link.'">';
		if (isset($image->rel) && $image->rel != '') {
			echo '<img src="'.JURI::base(true).'/'.$image->rel.'" alt="" class="img-responsive ph-image"';
			echo ' />';
		}
		echo '</a>';
		

		// CAPTION, DESCRIPTION
		echo '<div class="caption">';
		echo '<h3>'.$v->title.'</h3>';
		
		// Description box will be displayed even no description is set - to set height and have all columns same height
		echo '<div class="ph-item-desc">';
		if ($v->description != '') {
			echo $v->description;
		}
		echo '</div>';// end desc
		
		// :L: PRICE
		$price 				= new PhocaCartPrice;
		$d					= array();
		$d['priceitems']	= $price->getPriceItems($v->price, $v->taxrate, $v->taxcalculationtype, $v->taxtitle, $v->unit_amount, $v->unit_unit);
		$d['priceitemsorig']= array();
		if ($v->price_original != '' && $v->price_original > 0) {
			$d['priceitemsorig'] = $price->getPriceItems($v->price_original, $v->taxrate, $v->taxcalculationtype);
		}
		$d['class']			= 'ph-category-price-box';
		echo $layoutP->render($d);
		
		// VIEW PRODUCT BUTTON
		echo '<div class="ph-category-add-to-cart-box">';
		
		// :L: LINK TO PRODUCT VIEW
		$d					= array();
		$d['link']			= $link;
		echo $layoutV->render($d);
	
		
		echo '</div>';// end add to cart box
		echo '<div class="clearfix"></div>';
		
		
		echo '</div>';// end caption
		
		echo '</div>';// end thumbnail
	}
}
echo '</div>';
?>


