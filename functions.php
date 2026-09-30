<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'BTV_ELEMENTOR_VERSION', '1.0.0' );
define( 'EHP_THEME_SLUG', 'btv-base' );

define( 'BTV_THEME_PATH', get_template_directory() );
define( 'BTV_THEME_URL', get_template_directory_uri() );
define( 'BTV_THEME_ASSETS_PATH', BTV_THEME_PATH . '/assets/' );
define( 'BTV_THEME_ASSETS_URL', BTV_THEME_URL . '/assets/' );
define( 'BTV_THEME_SCRIPTS_PATH', BTV_THEME_ASSETS_PATH . 'js/' );
define( 'BTV_THEME_SCRIPTS_URL', BTV_THEME_ASSETS_URL . 'js/' );
define( 'BTV_THEME_STYLE_PATH', BTV_THEME_ASSETS_PATH . 'css/' );
define( 'BTV_THEME_STYLE_URL', BTV_THEME_ASSETS_URL . 'css/' );
define( 'BTV_THEME_IMAGES_PATH', BTV_THEME_ASSETS_PATH . 'images/' );
define( 'BTV_THEME_IMAGES_URL', BTV_THEME_ASSETS_URL . 'images/' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'btv_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function btv_elementor_setup() {
		if ( is_admin() ) {
			btv_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'btv_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'btv-base' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'btv-base' ) ] );
		}

		if ( apply_filters( 'btv_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'btv_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
					'navigation-widgets',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);
			add_theme_support( 'align-wide' );
			add_theme_support( 'responsive-embeds' );

			/*
			 * Editor Styles
			 */
			add_theme_support( 'editor-styles' );
			add_editor_style( 'assets/css/editor-styles.css' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'btv_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'btv_elementor_setup' );

function btv_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'btv_theme_version';
	// The theme version saved in the database.
	$btv_theme_db_version = get_option( $theme_version_option_name );

	// If the 'btv_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $btv_theme_db_version || version_compare( $btv_theme_db_version, BTV_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, BTV_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'btv_elementor_display_header_footer' ) ) {
	/**
	 * Check whether to display header footer.
	 *
	 * @return bool
	 */
	function btv_elementor_display_header_footer() {
		$btv_elementor_header_footer = true;

		return apply_filters( 'btv_elementor_header_footer', $btv_elementor_header_footer );
	}
}

if ( ! function_exists( 'btv_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function btv_elementor_scripts_styles() {
		if ( apply_filters( 'btv_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'btv-base',
				BTV_THEME_STYLE_URL . 'reset.css',
				[],
				BTV_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'btv_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'btv-base-theme-style',
				BTV_THEME_STYLE_URL . 'theme.css',
				[],
				BTV_ELEMENTOR_VERSION
			);
		}

		if ( btv_elementor_display_header_footer() ) {
			wp_enqueue_style(
				'btv-base-header-footer',
				BTV_THEME_STYLE_URL . 'header-footer.css',
				[],
				BTV_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'btv_elementor_scripts_styles' );

if ( ! function_exists( 'btv_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function btv_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'btv_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'btv_elementor_register_elementor_locations' );

if ( ! function_exists( 'btv_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function btv_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'btv_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'btv_elementor_content_width', 0 );

if ( ! function_exists( 'btv_elementor_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function btv_elementor_add_description_meta_tag() {
		if ( ! apply_filters( 'btv_elementor_description_meta_tag', true ) ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post = get_queried_object();
		if ( empty( $post->post_excerpt ) ) {
			return;
		}

		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'btv_elementor_add_description_meta_tag' );

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Header & footer styling option, inside Elementor
require get_template_directory() . '/includes/elementor-functions.php';

if ( ! function_exists( 'btv_elementor_customizer' ) ) {
	// Customizer controls
	function btv_elementor_customizer() {
		if ( ! is_customize_preview() ) {
			return;
		}

		if ( ! btv_elementor_display_header_footer() ) {
			return;
		}

		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'btv_elementor_customizer' );

if ( ! function_exists( 'btv_elementor_check_hide_title' ) ) {
	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function btv_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'btv_elementor_page_title', 'btv_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'btv_elementor_body_open' ) ) {
	function btv_elementor_body_open() {
		wp_body_open();
	}
}

require BTV_THEME_PATH . '/theme.php';

BTVTheme\Theme::instance();
