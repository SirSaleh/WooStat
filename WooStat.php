<?php

/**
 * Plugin Name: WooStat
 * Plugin sirsaleh.com
 * Description: This Plugin Made to give a simple statistics about orders of Woocommerce.
 * Author: SirSaleh
 * Author URI: sirsaleh.com
 * Version: 1.0.1
 *
 * 
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   my_plugin_menu
 * @author    SirSaleh
 * @category  Admin
 * @copyright Copyleft Sirsaleh.com
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once('orders_of_product.php');
require_once('total_amount_of.php');


function my_plugin_menu() {
	add_submenu_page( 'woocommerce', 'WooStat', 'WooStat', 'manage_options', 'Woostat', 'my_plugin_options' );
	//add_dashboard_page( 'My Plugin Options', 'Orders Statistics', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );
}
add_action( 'admin_menu', 'my_plugin_menu' );



function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'Access denied!' ) );
	}
	if ( class_exists( 'WooCommerce' ) ) {
	
		echo '<div style="color:#f00">';
		echo '<hr> ';
		echo '<p>Statistics of Compeleted orders of in-stock products</p>';
		echo '<hline style="color:#f00">';
		echo '<hr>';
		echo '</div>';
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1
			);
		$loop = new WP_Query( $args );
		//var_dump($loop);
		if ( $loop->have_posts() ) {
			echo ("<table><tr><td style='border: 1px solid black;' >Product Name</td><td style='border: 1px solid black;' >Product Id</td><td style='border: 1px solid black;' >Count of the orders</td><td style='border: 1px solid black;' >Total Counts of orders</td></tr>");
			while ( $loop->have_posts() ) : $loop->the_post();
				if ($loop->post->_stock_status == 'instock'){
					echo ("<tr>");
					$sum = 0;
					$total_sum=0;
					echo ("<th style='border-left: 1px solid black;' >".$loop->post->post_title." </th><th style='border-left: 1px solid black;' >".$loop->post->ID."</th>");
					$ord = get_orders_of($loop->post->ID);
					//var_dump($ord);
					if ($ord){
						if ($ord->have_posts()){
							while($ord->have_posts()){
								$ord->the_post();
								if ($ord->post->post_status == "wc-completed"){
									$total_sum = $total_sum + get_total_count($ord->post->ID,$loop->post->post_title);
									$sum++;
								}
							}
							echo ("<th style='border-left: 1px solid black;' >".$sum."</th><th style='border-left: 1px solid black;' >".$total_sum."</th>");
						}else{
							echo ("<th>0</th><th>0</th>");
						}
					//wc_get_template_part( 'content', 'product' );
					}else{
							echo ("<th>0</th><th>0</th>");
					}
					echo ("</tr>");
				}
			endwhile;
		} else {
			echo ( 'No products found' );
		}
		wp_reset_postdata();
		}
}
