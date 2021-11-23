<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class MP_Product_Reviews_Block {

	/**
	 * MP_Product_Reviews_Block constructor.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Runs hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function hooks() {
		add_action( 'acf/init', [ $this, 'register' ] );
	}

	/**
	 * Registers the ad block.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function register() {
		if ( ! function_exists( 'acf_register_block_type' ) ) {
			return;
		}

		acf_register_block_type(
			[
				'name'            => 'mp-product-reviews',
				'title'           => __( 'MP Product Reviews', 'moneypit-product-reviews' ),
				'description'     => __( 'A custom block to display product reviews.', 'moneypit-product-reviews' ),
				'render_callback' => [ $this, 'do_product_reviews' ],
				'category'        => 'widget',
				'keywords'        => [ 'moneypit', 'reviews' ],
				'icon'            => 'yes-alt',
				'mode'            => 'edit',
				'enqueue_assets'  => function() {
					$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
					$version = MP_PRODUCT_REVIEWS_VERSION;
					$file    = MP_PRODUCT_REVIEWS_PLUGIN_DIR . "assets/css/product-reviews{$suffix}.css";

					if ( file_exists( $file ) ) {
						$version .= '.' . date( 'njYHi', filemtime( $file ) );
					}

					wp_enqueue_style( 'moneypit-product-reviews', MP_PRODUCT_REVIEWS_PLUGIN_URL . "assets/css/product-reviews{$suffix}.css", [], $version );
				},
				'supports'        => [
					'align' => [],
					'mode'  => true,
					'jsx'   => false,
				],
			]
		);
	}

	/**
	 * Callback function to render the block.
	 *
	 * @since 0.1.0
	 *
	 * @param array  $block      The block settings and attributes.
	 * @param string $content    The block inner HTML (empty).
	 * @param bool   $is_preview True during AJAX preview.
	 * @param int    $post_id    The post ID this block is saved to.
	 *
	 * @return void
	 */
	function do_product_reviews( $block, $content = '', $is_preview = false, $post_id = 0 ) {
		$products = get_field( 'products' );

		if ( ! $products ) {
			return;
		}

		$count = 1;

		printf( '<div class="mp-product-reviews%s">', '' );

			foreach ( $products as $product ) {
				$product = $this->get_product_data( $product );

				printf( '<div class="mp-product%s">', '' );

					if ( $product['image'] ) {
						echo '<div class="mp-product-image">';
							echo wp_get_attachment_image( $product['image'], 'thumbnail' );
						echo '</div>';
					}

					if ( $product['title'] || $product['link'] || $product['pros'] ) {
						echo '<div class="mp-product-content">';

							if ( $product['title'] ) {
								printf( '<h2 class="mp-product-title">%s</h2>', $product['title'] );
							}

							if ( $product['link'] ) {
								printf( '<p class="mp-product-reviews-link"><a href="%s">%s</a></p>', $product['link'], $product['link_text'] );
							}

							if ( $product['pros'] ) {
								echo $this->get_list( $product['pros'], 'mp-pros', 3 );
							}

						echo '</div>';
					}

					if ( $product['price'] ) {
						echo '<p class="mp-product-price is-style-heading">';
							printf( '$%s', $product['price'] );
						echo '</p>';
					}

					echo '<p class="mp-product-links">';
						if ( $product['link'] ) {
							printf( '<a class="mp-product-button button" href="%s" target="__blank" rel="noopener noreferrer">%s</a>', $product['link'], $product['button_text'] );
						}
					echo '</p>';

					printf( '<p class="mp-product-review-link"><a href="#mp-review-%s">%s</a></p>', $count, __( 'Read Our Review', 'moneypit-product-reviews' ) );

				echo '</div>';

				$count++;
			}

		echo '</div>';

		printf( '<h2 class="has-xxl-margin-bottom">%s</h2>', __( 'Our Unbiased Reviews', 'moneypit-product-reviews' ) );

		$count = 1;

		printf( '<div class="mp-reviews%s">', '' );

			foreach ( $products as $product ) {
				$product = $this->get_product_data( $product );

				printf( '<div id="mp-review-%s" class="mp-review%s">', $count, '' );

					if ( $product['title'] ) {
						printf( '<h3 class="mp-review-title">%s. %s</h3>', $count, $product['title'] );
					}

					printf( '<div class="mp-review-header%s">', '' );

						printf( '<div class="mp-review-header-left%s">', '' );

							if ( $product['image'] ) {
								echo '<div class="mp-review-image">';
									echo wp_get_attachment_image( $product['image'], 'medium' );
								echo '</div>';
							}

						echo '</div>';
						printf( '<div class="mp-review-header-right%s">', '' );

							if ( $product['price'] ) {
								echo '<p class="mp-review-price is-style-heading">';
									printf( '$%s', $product['price'] );
								echo '</p>';
							}

							if ( $product['link'] ) {
								printf( '<p><a class="mp-reviews-link" href="%s">%s</a></p>', $product['link'], $product['link_text'] );
								printf( '<p><a class="mp-review-button button" href="%s" target="__blank" rel="noopener noreferrer">%s</a></p>', $product['link'], $product['button_text'] );
							}

						echo '</div>';

					echo '</div>';

					printf( '<div class="mp-lists%s">', '' );

						if ( $product['pros'] ) {
							echo '<div class="mp-list-wrap">';
								printf( '<p class="is-style-heading">%s:</p>', __( 'Pros', 'moneypit-product-reviews' ) );
								echo $this->get_list( $product['pros'], 'mp-pros' );
							echo '</div>';
						}

						if ( $product['cons'] ) {
							echo '<div class="mp-list-wrap">';
								printf( '<p class="is-style-heading">%s:</p>', __( 'Cons', 'moneypit-product-reviews' ) );
								echo $this->get_list( $product['cons'], 'mp-cons' );
							echo '</div>';
						}

					echo '</div>';

					if ( $product['review'] ) {
						echo $product['review'];
					}

					if ( $product['link'] ) {
						$title = $product['title'] ? ' ' .  $product['title'] : '';
						$more  = sprintf( '%s %s %s', __( 'Find more', 'moneypit-product-reviews' ), $title, __( 'info here', 'moneypit-product-reviews' ) );

						printf( '<p><a class="mp-review-more button button-link button-large" href="%s">%s</a></p>', $product['link'], $more );
					}

				echo '</div>';

				$count++;
			}

		echo '</div>';
	}

	/**
	 * Gets sanitized product data with defaults/fallbacks.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	function get_product_data( $product ) {
		// Defaults.
		$data = wp_parse_args( $product,
			[
				'title'       => '',
				'image'       => '',
				'link'        => '',
				'link_text'   => '',
				'button_text' => '',
				'price'       => '',
				'pros'        => [],
				'cons'        => [],
				'review'      => '',
			]
		);

		// Fallbacks.
		$data['link_text']   = $data['link_text'] ?: __( 'Amazon Customer Reviews', 'moneypit-product-reviews' );
		$data['button_text'] = $data['button_text'] ?: __( 'Shop Now', 'moneypit-product-reviews' );


		// Sanitize.
		$data['title']       = esc_html( trim( $data['title'] ) );
		$data['image']       = absint( $data['image'] );
		$data['link']        = esc_url( $data['link'] );
		$data['link_text']   = esc_html( trim( $data['link_text'] ) );
		$data['button_text'] = esc_html( trim( $data['button_text'] ) );
		$data['price']       = esc_html( trim( $data['price'] ) );
		$data['pros']        = esc_html( trim( $data['pros'] ) );
		$data['cons']        = esc_html( trim( $data['cons'] ) );
		$data['review']      = wp_kses_post( trim( $data['review'] ) );

		return $data;
	}

	/**
	 * Gets list html from new lines.
	 *
	 * @since 0.1.0
	 *
	 * @param string $content The content from textarea field.
	 * @param string $class   The html class for the list.
	 * @param int    $limit   How many items to limit the list to.
	 *
	 * @return string
	 */
	function get_list( $content, $class, $limit = 0 ) {
		$html  = '';
		$array = explode( "\r\n", $content );
		$array = array_values( array_filter( $array ) );
		$array = $limit ? array_slice( $array , 0, absint( $limit ) ) : $array;

		if ( $array ) {
			$html .= sprintf( '<ul class="mp-list %s">', sanitize_html_class( $class ) );
				foreach ( $array as $item ) {
					$html .= sprintf( '<li class="mp-list-item">%s</li>', $item );
				}
			$html .= '</ul>';
		}

		return $html;
	}
}
