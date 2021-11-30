<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class MP_Product_Reviews_Register {

	/**
	 * MP_Product_Reviews_Register constructor.
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
		add_action( 'acf/render_field/key=mppr_products', [ $this, 'admin_css' ] );
	}

	/**
	 * Registers field group.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function register() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group(
			[
				'key'    => 'mp_product_reviews',
				'title'  => __( 'Moneypit Product Reviews', 'moneypit-product-reviews' ),
				'fields' => $this->get_fields(),
				'location' => [
					[
						[
							'param'    => 'block',
							'operator' => '==',
							'value'    => 'acf/mp-product-reviews',
						],
					],
				],
			]
		);
	}

	/**
	 * Gets field group fields.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function get_fields() {
		return [
			[
				'key'          => 'mppr_products',
				'label'        => __( 'Product Reviews', 'moneypit-product-reviews' ),
				'name'         => 'products',
				'type'         => 'repeater',
				'collapsed'    => 'mppr_title',
				'min'          => 1,
				'max'          => 0,
				'layout'       => 'block',
				'button_label' => __( 'Add Product', 'moneypit-product-reviews' ),
				'sub_fields'   => [
					[
						'label' => __( 'Title', 'moneypit-product-reviews' ),
						'key'   => 'mppr_title',
						'name'  => 'title',
						'type'  => 'text',
					],
					[
						'label'         => __( 'Image', 'moneypit-product-reviews' ),
						'key'           => 'mppr_image',
						'name'          => 'image',
						'type'          => 'image',
						'return_format' => 'id',
						'preview_size'  => 'medium',
						'library'       => 'all',
					],
					[
						'label' => __( 'Link', 'moneypit-product-reviews' ),
						'key'   => 'mppr_link',
						'name'  => 'link',
						'type'  => 'url',
					],
					[
						'label'       => __( 'Reviews Text', 'moneypit-product-reviews' ),
						'key'         => 'mppr_link_text',
						'name'        => 'link_text',
						'type'        => 'text',
						'placeholder' => __( 'Amazon Customer Reviews', 'moneypit-product-reviews' ),
					],
					[
						'label'       => __( 'Button Text', 'moneypit-product-reviews' ),
						'key'         => 'mppr_button_text',
						'name'        => 'button_text',
						'type'        => 'text',
						'placeholder' => __( 'Shop Now', 'moneypit-product-reviews' ),
					],
					[
						'label'   => __( 'Price', 'moneypit-product-reviews' ),
						'key'     => 'mppr_price',
						'name'    => 'price',
						'type'    => 'text',
						'prepend' => '$',
					],
					[
						'label'     => __( 'Pros', 'moneypit-product-reviews' ),
						'key'       => 'mppr_pros',
						'name'      => 'pros',
						'type'      => 'textarea',
						'rows'      => 5,
						'new_lines' => 'br',
						'wrapper'   => [
							'width' => '50',
						],
					],
					[
						'label'     => __( 'Cons', 'moneypit-product-reviews' ),
						'key'       => 'mppr_cons',
						'name'      => 'cons',
						'type'      => 'textarea',
						'rows'      => 5,
						'new_lines' => 'br',
						'wrapper'   => [
							'width' => '50',
						],
					],
					[
						'label'        => __( 'Review', 'moneypit-product-reviews' ),
						'key'          => 'mppr_review',
						'name'         => 'review',
						'type'         => 'wysiwyg',
						'tabs'         => 'visual',
						'toolbar'      => 'basic',
						'media_upload' => 0,
						'delay'        => 1,
					],
				],
			],
		];
	}

	/**
	 * Adds custom CSS in the first field.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function admin_css( $field ) {
		echo '<style>
		.acf-field-mppr-products .acf-label label {
			font-size: 1rem !important;
		}
		</style>';
	}
}
