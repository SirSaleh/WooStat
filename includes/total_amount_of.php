<?php
function get_total_count($order_id,$product_name){
	global $wpdb;
	//$produto_id = $product_id; // Product ID
	$consulta = "SELECT order_item_id FROM {$wpdb->prefix}woocommerce_order_items WHERE order_id=".$order_id." AND order_item_name='".$product_name."';";
	$wpdb->show_errors();
	$item_id = $wpdb->get_col ($wpdb->prepare( $consulta, $produto_id ) );
	//echo ($consulta);
	//echo ("pid ->".$product_id.". ids-> ".var_dump($order_ids)."<br>".$consulta."<br>");
	//echo("dump: ".$item_id[0]);
	$consulta2 = "SELECT meta_value FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id=".$item_id[0]." AND meta_key = '_qty';";
	$count_col = $wpdb->get_col ($wpdb->prepare($consulta2,$produto_id));
	return ($count_col[0]);
}
?>