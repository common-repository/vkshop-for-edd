<?php
/*
Plugin Name: VKShop for Easy Digital Downloads
Description: Автоматическая синхронизация магазина на Easy Digital Downloads c разделом Товары ВКонтакте.
Version: 0.9
Plugin URI: https://wordpress.org/plugins/vkshop-for-edd/
Author: Aleksandrx
Author URI: https://profiles.wordpress.org/aleksandrx/
Text Domain: vkshop-for-edd
Domain Path: /languages/
EDD tested up to: 2.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*  Copyright 2016 Aleksej Solovjov

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

*/

function vks_version() {
	return '0.9';
}


function vks_requirements( $status = false ) {
	if ( $status ) {
		if ( ! class_exists( 'WP_Settings_API_Class2_Edd' ) ) {
			include_once( 'inc/wp-settings-api-class-edd.php' );
		}

		if ( ! class_exists( 'WP_Help_Pointer_Edd' ) ) {
			include_once( 'inc/wp-help-pointer-class-edd.php' );
		}

		include 'inc/vkwp-api-edd.php';
		include 'vks-functions.php';
		include 'vks-export.php';

		include 'vks-admin.php';
	} else {
		add_action( 'admin_notices', 'vks_admin_notice_deactivation' );
		add_action( 'admin_init', 'vks_deactivation' );
	}
}

global $wp_version;

if ( version_compare( PHP_VERSION, '5', '>' ) &&
     version_compare( $wp_version, '4.4', '>=' )
) {

	if ( in_array( 'easy-digital-downloads/easy-digital-downloads.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		$vks_requirements = true;
	} else {
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		if ( is_plugin_active_for_network( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
			$vks_requirements = true;
		} else {
			$vks_requirements = false;
		}
	}

} else {
	$vks_requirements = false;
}


vks_requirements( $vks_requirements );


function vks_deactivation() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

function vks_admin_notice_deactivation() {
	$url = site_url( 'wp-admin/plugin-install.php?tab=plugin-information&plugin=easy-digital-downloads&TB_iframe=true&width=772&height=507' );

	printf( '<div class="error"><p>' .
	        __( 'For VKShop & Easy Digital Downloads plugin is required PHP 5, WordPress 4.4 and <a class="thickbox" href="%s">Easy Digital Downloads</a> plugin. Please install and activate the necessary programs.', 'vkshop-for-edd' ) .
	        '</p></div>', $url, $url
	);
}


define( 'VKSEDD_TOKEN_URL', 'https://oauth.vk.com/access_token' );
define( 'VKSEDD_AUTHORIZATION_URL', 'https://oauth.vk.com/authorize' );
define( 'VKSEDD_API_URL', 'https://api.vk.com/method/' );


function vks_init() {
	global $wp_version;


	if ( version_compare( PHP_VERSION, '5', '>' ) &&
	     version_compare( $wp_version, '4.4', '>=' )
	) {

		if ( in_array( 'easy-digital-downloads/easy-digital-downloads.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			$vks_requirements = true;
		} else {
			if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			}

			if ( is_plugin_active_for_network( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
				$vks_requirements = true;
			} else {
				$vks_requirements = false;
			}
		}

	} else {
		$vks_requirements = false;
	}

	if ( $vks_requirements ) {

		load_plugin_textdomain( 'vkshop-for-edd', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

add_action( 'admin_init', 'vks_init' );


function vks_admin_head() {

	if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array(
			'vkshop',
			'vkshop-settings',
			'vkshop-bulk',
			'vkshop-log',
			'vkshop-help',
		) )
	) {
		?>
		<style type="text/css">
			#col-right.vks {
				width: 35%;
			}

			#col-left.vks {
				width: 64%;
			}

			.vks-box {
				padding: 0 20px 0 40px;
			}

			@media only screen and (max-width: 960px) {
				#col-right.vks {
					width: 100%;
				}

				#col-left.vks {
					width: 100%;
				}

				.vks-box {
					padding: 0;
				}
			}

			.vks-boxx {
				background: none repeat scroll 0 0 #FFFFFF;
				border-left: 4px solid #2EA2CC;
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
				margin: 5px 0 15px;
				padding: 1px 12px;
			}

			.vks-boxx h3 {
				line-height: 1.5;
			}

			.vks-boxx p {
				margin: 0.5em 0;
				padding: 2px;
			}
		</style>

		<?php
	}

	if ( in_array( $GLOBALS['pagenow'], array( 'post.php' ) ) ) {
		?>
		<style type="text/css">
			#vks-product-link {
				color: #666;
				line-height: 24px;
				min-height: 25px;
				padding: 0 10px;
			}
		</style>
		<?php
	}
}

add_action( 'admin_head', 'vks_admin_head', 90 );


function vks_plugin_action_links( $links ) {

	$links[] = '<a href="' . admin_url( 'admin.php?page=vkshop-help' ) . '">Быстрый старт</a>';

	return $links;
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'vks_plugin_action_links' );


function vks_admin_help_pointers() {

	$pointers = array(
		array(
			'id'       => 'vks_help_page_pointer',
			'screen'   => array(
				'dashboard',
				'plugins',
				'edit-product',
				'toplevel_page_vkshop',
				'tovary-vk_page_vkshop-settings'
			),
			'target'   => '#toplevel_page_vkshop',
			'title'    => 'Товары ВК: Быстрый старт',
			'content'  => '<a href="' . admin_url( 'admin.php?page=vkshop-help' ) . '">Документация</a>: от настроек до публикации первого товара в группе ВКонтакте.',
			'position' => array(
				'edge'  => 'left', //top, bottom, left, right
				'align' => 'right' //top, bottom, left, right, middle
			)
		)
	);

	new WP_Help_Pointer_Edd( $pointers );
}

add_action( 'admin_enqueue_scripts', 'vks_admin_help_pointers' );