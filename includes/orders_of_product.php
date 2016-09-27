<?php
function get_orders_of($product_id,$order_status=array('wc-Completed')){
	global $wpdb;
	$produto_id = $product_id; // Product ID
	$consulta = "SELECT order_id FROM {$wpdb->prefix}woocommerce_order_itemmeta woim
	LEFT JOIN {$wpdb->prefix}woocommerce_order_items oi
	ON woim.order_item_id = oi.order_item_id
	WHERE meta_key = '_product_id' AND meta_value = ".$produto_id."
	GROUP BY order_id;";
	/*$consulta = "SELECT order_id FROM {$wpdb->prefix}woocommerce_order_itemmeta woim
	LEFT JOIN {$wpdb->prefix}woocommerce_order_items oi
	ON woim.order_item_id = oi.order_item_id
	WHERE meta_key = '_product_id' AND meta_value = 62
	GROUP BY order_id;";*/
	$wpdb->show_errors();
	$order_ids = $wpdb->get_col ($wpdb->prepare( $consulta, $produto_id ) );
	//echo ($consulta);
	//echo ("pid ->".$product_id.". ids-> ".var_dump($order_ids)."<br>".$consulta."<br>");
	if( $order_ids ) {
		$args = array(
			'post_type' =>'shop_order',
			'post__in' => $order_ids,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'order' => 'DESC',
			//'meta_key' => '_customer_user',
			//'meta_value' => get_current_user_id(),
			'tax_query' => array(
				array( 'taxonomy' => 'shop_order_status',
					'field' => 'slug',
					'terms' => $order_status
				)
			)
		);
		$orders = new WP_Query( $args );
		return ($orders);
	}
	return(array());
}
?>