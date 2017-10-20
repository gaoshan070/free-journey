<?php if ( !defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
if ( !class_exists( 'Fastshop_ThemeOption' ) ) {
	class Fastshop_ThemeOption
	{
		public function __construct()
		{
			$this->get_sidebars();
			$this->get_footer_options();
			$this->get_header_options();
			$this->get_footer_preview();
			$this->get_product_options();
			$this->get_social_option();
			$this->init_settings();
			add_action( 'admin_bar_menu', array( $this, 'fastshop_custom_menu' ), 1000 );
		}

		public function get_header_options()
		{
			$layoutDir      = get_template_directory() . '/templates/headers/';
			$header_options = array();

			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo[ 'extension' ] == 'php' && $fileInfo[ 'basename' ] != 'index.php' ) {
								$file_data                    = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name                    = str_replace( 'header-', '', $fileInfo[ 'filename' ] );
								$header_options[ $file_name ] = array(
									'title'   => $file_data[ 'Name' ],
									'preview' => get_template_directory_uri() . '/templates/headers/header-' . $file_name . '.jpg',
								);
							}
						}
					}
				}
			}
			$this->header_options = $header_options;
		}

		public function get_product_options()
		{
			$layoutDir       = get_template_directory() . '/woocommerce/product-styles/';
			$product_options = array();

			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					$option = '';
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo[ 'extension' ] == 'php' && $fileInfo[ 'basename' ] != 'index.php' ) {
								$file_data                     = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name                     = str_replace( 'content-product-style-', '', $fileInfo[ 'filename' ] );
								$product_options[ $file_name ] = array(
									'title'   => $file_data[ 'Name' ],
									'preview' => get_template_directory_uri() . '/woocommerce/product-styles/content-product-style-' . $file_name . '.jpg',
								);
							}
						}
					}
				}
			}
			$this->product_options = $product_options;
		}

		public function get_sidebars()
		{
			$sidebars = array();
			global $wp_registered_sidebars;
			foreach ( $wp_registered_sidebars as $sidebar ) {
				$sidebars[ $sidebar[ 'id' ] ] = $sidebar[ 'name' ];
			}
			$this->sidebars = $sidebars;
		}

		public function fastshop_custom_menu()
		{
			global $wp_admin_bar;
			if ( !is_super_admin() || !is_admin_bar_showing() ) return;
			// Add Parent Menu
			$argsParent = array(
				'id'    => 'theme_option',
				'title' => esc_html__( 'Theme Options', 'fastshop' ),
				'href'  => admin_url( 'admin.php?page=fastshop' ),
			);
			$wp_admin_bar->add_menu( $argsParent );
		}

		public function get_social_option()
		{
			$socials     = array();
			$all_socials = cs_get_option( 'user_all_social' );
			$i           = 1;
			if ( $all_socials ) {
				foreach ( $all_socials as $social ) {
					$socials[ $i++ ] = $social[ 'title_social' ];
				}
			}
			$this->social_options = $socials;
		}

		public function get_footer_options()
		{
			$footer_options[ 'default' ] = array(
				'title'   => esc_html__( 'Default', 'fastshop' ),
				'preview' => '',
			);
			$layoutDir                   = get_template_directory() . '/templates/footers/';
			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					$option = '';
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo[ 'extension' ] == 'php' && $fileInfo[ 'basename' ] != 'index.php' ) {
								$file_data                    = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name                    = str_replace( 'footer-', '', $fileInfo[ 'filename' ] );
								$footer_options[ $file_name ] = array(
									'title'   => $file_data[ 'Name' ],
									'preview' => get_template_directory_uri() . '/templates/footers/footer-' . $file_name . '.jpg',
								);
							}
						}
					}
				}
			}
			$this->footer_options = $footer_options;
		}

		public function get_footer_preview()
		{
			$footer_preview = array();
			$template_style = '';
			$args           = array(
				'post_type'      => 'footer',
				'posts_per_page' => -1,
				'orderby'        => 'ASC',
			);
			$loop           = get_posts( $args );
			foreach ( $loop as $value ) {
				setup_postdata( $value );
				$data_meta = get_post_meta( $value->ID, '_custom_footer_options', true );
				if ( !empty( $data_meta ) ) {
					$template_style = $data_meta[ 'fastshop_footer_style' ];
				}
				$footer_preview[ $value->ID ] = array(
					'title'   => $value->post_title,
					'preview' => get_template_directory_uri() . '/templates/footers/footer-' . $template_style . '.jpg',
				);
			}
			$this->footer_preview = $footer_preview;
		}

		public function init_settings()
		{
			// ===============================================================================================
			// -----------------------------------------------------------------------------------------------
			// FRAMEWORK SETTINGS
			// -----------------------------------------------------------------------------------------------
			// ===============================================================================================
			$settings = array(
				'menu_title'      => esc_html__( 'Theme Options', 'fastshop' ),
				'menu_type'       => 'submenu', // menu, submenu, options, theme, etc.
				'menu_slug'       => 'fastshop',
				'ajax_save'       => false,
				'menu_parent'     => 'fastshop_menu',
				'show_reset_all'  => true,
				'menu_position'   => 2,
				'framework_title' => '<a href="http://fastshop.kutethemes.net/" target="_blank">Fastshop</a> <small>by <a href="https://themeforest.net/user/kutethemes/portfolio" target="_blank">KuteThemes</a></small>',
			);

			// ===============================================================================================
			// -----------------------------------------------------------------------------------------------
			// FRAMEWORK OPTIONS
			// -----------------------------------------------------------------------------------------------
			// ===============================================================================================
			$options = array();

			// ----------------------------------------
			// a option section for options overview  -
			// ----------------------------------------
			$options[] = array(
				'name'     => 'general',
				'title'    => esc_html__( 'General', 'fastshop' ),
				'icon'     => 'fa fa-wordpress',
				'sections' => array(
					array(
						'name'   => 'main_settings',
						'title'  => esc_html__( 'Main Settings', 'fastshop' ),
						'fields' => array(
							array(
								'id'    => 'fastshop_logo',
								'type'  => 'image',
								'title' => esc_html__( 'Logo', 'fastshop' ),
							),
							array(
								'id'      => 'fastshop_main_color',
								'type'    => 'color_picker',
								'title'   => esc_html__( 'Main Color', 'fastshop' ),
								'default' => '#0188CC',
								'rgba'    => true,
							),
							array(
								'id'      => 'background_page',
								'type'    => 'background',
								'title'   => esc_html__( 'Background Page', 'fastshop' ),
								'default' => array(
									'image'      => '',
									'repeat'     => 'repeat-x',
									'position'   => 'center center',
									'attachment' => 'fixed',
									'size'       => 'cover',
									'color'      => '',
								),
							),
							array(
								'id'    => 'gmap_api_key',
								'type'  => 'text',
								'title' => esc_html__( 'Google Map API Key', 'fastshop' ),
								'desc'  => wp_kses( sprintf( __( 'Enter your Google Map API key. <a href="%s" target="_blank">How to get?</a>', 'fastshop' ), 'https://developers.google.com/maps/documentation/javascript/get-api-key' ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
							),
							array(
								'id'      => 'enable_theme_options',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Meta Box Options', 'fastshop' ),
								'default' => false,
								'desc'    => esc_html__( 'Enable for using Themes setting each single page.', 'fastshop' ),
							),
							array(
								'id'    => 'fastshop_theme_lazy_load',
								'type'  => 'switcher',
								'title' => esc_html__( 'Use image Lazy Load', 'fastshop' ),
							),
						),
					),

					array(
						'name'   => 'popup_settings',
						'title'  => esc_html__( 'Newsletter Settings', 'fastshop' ),
						'fields' => array(
							array(
								'id'      => 'fastshop_enable_popup',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Popup Newsletter', 'fastshop' ),
								'default' => false,
							),
							array(
								'id'         => 'fastshop_poppup_background',
								'type'       => 'image',
								'title'      => esc_html__( 'Popup Background', 'fastshop' ),
								'dependency' => array( 'fastshop_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'fastshop_popup_title',
								'type'       => 'text',
								'title'      => esc_html__( 'Title', 'fastshop' ),
								'default'    => 'SAVE 25%',
								'dependency' => array( 'fastshop_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'fastshop_popup_subtitle',
								'type'       => 'text',
								'title'      => esc_html__( 'Sub Title', 'fastshop' ),
								'default'    => 'SIGN UP FOR EMAILS',
								'dependency' => array( 'fastshop_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'fastshop_popup_description',
								'type'       => 'wysiwyg',
								'title'      => esc_html__( 'Description', 'fastshop' ),
								'settings'   => array(
									'textarea_rows' => 5,
									'tinymce'       => true,
									'media_buttons' => true,
								),
								'dependency' => array( 'fastshop_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'fastshop_popup_input_placeholder',
								'type'       => 'text',
								'title'      => esc_html__( 'Input placeholder text', 'fastshop' ),
								'default'    => 'Enter your email adress',
								'dependency' => array( 'fastshop_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'fastshop_popup_button_text',
								'type'       => 'text',
								'title'      => esc_html__( 'Input placeholder text', 'fastshop' ),
								'default'    => 'Sign-Up',
								'dependency' => array( 'fastshop_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'fastshop_popup_delay_time',
								'type'       => 'number',
								'title'      => esc_html__( 'Delay time', 'fastshop' ),
								'default'    => '0',
								'dependency' => array( 'fastshop_enable_popup', '==', '1' ),
							),
							array(
								'id'         => 'fastshop_enable_popup_mobile',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Enable Poppup on Mobile', 'fastshop' ),
								'default'    => false,
								'dependency' => array( 'fastshop_enable_popup', '==', '1' ),
							),
						),
					),
					array(
						'name'   => 'widget_settings',
						'title'  => esc_html__( 'Widget Settings', 'fastshop' ),
						'fields' => array(
							array(
								'id'              => 'multi_widget',
								'type'            => 'group',
								'title'           => esc_html__( 'Multi Widget', 'fastshop' ),
								'button_title'    => esc_html__( 'Add Widget', 'fastshop' ),
								'accordion_title' => esc_html__( 'Add New Field', 'fastshop' ),
								'fields'          => array(
									array(
										'id'    => 'add_widget',
										'type'  => 'text',
										'title' => esc_html__( 'Name Widget', 'fastshop' ),
									),
								),
							),
						),
					),
					array(
						'name'   => 'theme_js_css',
						'title'  => esc_html__( 'Customs JS/CSS', 'fastshop' ),
						'fields' => array(
							array(
								'id'         => 'fastshop_custom_css',
								'title'      => esc_html__( 'Custom CSS', 'fastshop' ),
								'type'       => 'ace_editor',
								'attributes' => array(
									'data-theme' => 'twilight',  // the theme for ACE Editor
									'data-mode'  => 'css',     // the language for ACE Editor
								),
							),
							array(
								'id'         => 'fastshop_custom_js',
								'title'      => esc_html__( 'Custom JS', 'fastshop' ),
								'type'       => 'ace_editor',
								'attributes' => array(
									'data-theme' => 'twilight',  // the theme for ACE Editor
									'data-mode'  => 'javascript',     // the language for ACE Editor
								),
							),
						),
					),
				),
			);

			$options[] = array(
				'name'     => 'header',
				'title'    => esc_html__( 'Header Settings', 'fastshop' ),
				'icon'     => 'fa fa-folder-open-o',
				'sections' => array(
					array(
						'name'   => 'main_header',
						'title'  => esc_html__( 'Header Settings', 'fastshop' ),
						'fields' => array(
							array(
								'id'    => 'fastshop_enable_sticky_menu',
								'type'  => 'switcher',
								'title' => esc_html__( 'Main Menu Sticky', 'fastshop' ),
							),
							array(
								'id'         => 'fastshop_used_header',
								'type'       => 'select_preview',
								'title'      => esc_html__( 'Header Layout', 'fastshop' ),
								'desc'       => esc_html__( 'Select a header layout', 'fastshop' ),
								'options'    => $this->header_options,
								'default'    => 'style-01',
								'attributes' => array(
									'data-depend-id' => 'fastshop_used_header',
								),
							),
							array(
								'id'              => 'group_menu_header',
								'type'            => 'group',
								'title'           => esc_html__( 'Group Field', 'fastshop' ),
								'button_title'    => esc_html__( 'Add New', 'fastshop' ),
								'accordion_title' => esc_html__( 'Add New Item', 'fastshop' ),
								'dependency'      => array(
									'fastshop_used_header', 'any', 'style-02,style-03,style-05,style-06,style-08,
									style-09,style-10,style-14,style-16,style-17,style-19,style-23',
								),
								'fields'          => array(
									array(
										'id'    => 'title_item',
										'type'  => 'text',
										'title' => 'Title',
									),
									array(
										'id'      => 'link_item',
										'type'    => 'text',
										'title'   => esc_html__( 'Link Item', 'fastshop' ),
										'default' => '#',
									),
									array(
										'id'      => 'icon_item',
										'type'    => 'icon',
										'title'   => esc_html__( 'Icon Item', 'fastshop' ),
										'default' => 'fa fa-phone',
									),
								),
							),
							array(
								'id'         => 'icon_header',
								'type'       => 'image',
								'title'      => esc_html__( 'Icon Contact', 'fastshop' ),
								'default'    => '',
								'dependency' => array( 'fastshop_used_header', 'any', 'style-01,style-18,style-22' ),
							),
							array(
								'id'         => 'header_support',
								'type'       => 'text',
								'title'      => esc_html__( 'Phone Support', 'fastshop' ),
								'default'    => '',
								'dependency' => array( 'fastshop_used_header', 'any', 'style-01,style-18,style-22' ),
							),
							array(
								'id'         => 'header_email',
								'type'       => 'text',
								'title'      => esc_html__( 'Email Support', 'fastshop' ),
								'default'    => '',
								'dependency' => array( 'fastshop_used_header', 'any', 'style-01,style-18,style-22' ),
							),
							array(
								'id'         => 'get_all_social',
								'type'       => 'select',
								'title'      => esc_html__( 'Select Social', 'fastshop' ),
								'options'    => $this->social_options,
								'attributes' => array(
									'multiple' => 'multiple',
									'style'    => 'width: 50%;line-height: 25px;',
								),
								'class'      => 'chosen',
							),
						),
					),
					array(
						'name'   => 'vertical_menu',
						'title'  => esc_html__( 'Vertical Menu Settings', 'fastshop' ),
						'fields' => array(
							array(
								'id'         => 'enable_vertical_menu',
								'type'       => 'select',
								'options'    => array(
									'yes' => 'Yes',
									'no'  => 'No',
								),
								'attributes' => array(
									'data-depend-id' => 'enable_vertical_menu',
								),
								'title'      => esc_html__( 'Vertical Menu', 'fastshop' ),
								'default'    => 'yes',
								'dependency' => array(
									'fastshop_used_header', 'any', 'style-02,style-08,style-09,style-16,style-19,style-20,style-22',
								),
							),
							array(
								'title'      => esc_html__( 'Vertical Menu Title', 'fastshop' ),
								'id'         => 'vertical_menu_title',
								'type'       => 'text',
								'default'    => esc_html__( 'Shop By Category', 'fastshop' ),
								'dependency' => array(
									'fastshop_used_header|enable_vertical_menu', 'any|==', 'style-02,style-08,style-09,style-16,style-19,style-20,style-22|yes',
								),
							),
							array(
								'title'      => esc_html__( 'Vertical Menu Button show all text', 'fastshop' ),
								'id'         => 'vertical_menu_button_all_text',
								'type'       => 'text',
								'default'    => esc_html__( 'All Categories', 'fastshop' ),
								'dependency' => array(
									'fastshop_used_header|enable_vertical_menu', 'any|==', 'style-02,style-08,style-09,style-16,style-19,style-20,style-22|yes',
								),
							),

							array(
								'title'      => esc_html__( 'Vertical Menu Button close text', 'fastshop' ),
								'id'         => 'vertical_menu_button_close_text',
								'type'       => 'text',
								'default'    => esc_html__( 'Close', 'fastshop' ),
								'dependency' => array(
									'fastshop_used_header|enable_vertical_menu', 'any|==', 'style-02,style-08,style-09,style-16,style-19,style-20,style-22|yes',
								),
							),
							array(
								'title'      => esc_html__( 'Collapse', 'fastshop' ),
								'id'         => 'click_open_vertical_menu',
								'type'       => 'select',
								'options'    => array(
									'yes' => 'Yes',
									'no'  => 'No',
								),
								'desc'       => esc_html__( 'Vertical menu will expand on click', 'fastshop' ),
								'dependency' => array(
									'fastshop_used_header|enable_vertical_menu', 'any|==', 'style-02,style-08,style-09,style-16,style-19,style-20,style-22|yes',
								),
							),
							array(
								'title'      => esc_html__( 'The number of visible vertical menu items', 'fastshop' ),
								'desc'       => esc_html__( 'The number of visible vertical menu items', 'fastshop' ),
								'id'         => 'vertical_item_visible',
								'default'    => 10,
								'type'       => 'number',
								'dependency' => array(
									'fastshop_used_header|enable_vertical_menu', 'any|==', 'style-02,style-08,style-09,style-16,style-19,style-20,style-22|yes',
								),
							),
						),
					),
				),
			);

			$options[] = array(
				'name'   => 'footer',
				'title'  => esc_html__( 'Footer Settings', 'fastshop' ),
				'icon'   => 'fa fa-folder-open-o',
				'fields' => array(
					array(
						'id'      => 'fastshop_footer_options',
						'type'    => 'select_preview',
						'title'   => esc_html__( 'Select Footer Builder', 'fastshop' ),
						'options' => $this->footer_preview,
						'default' => 'default',
					),
				),
			);

			$options[] = array(
				'name'     => 'blog',
				'title'    => esc_html__( 'Blog Settings', 'fastshop' ),
				'icon'     => 'fa fa-rss',
				'sections' => array(
					array(
						'name'   => 'blog_page',
						'title'  => esc_html__( 'Blog Page', 'fastshop' ),
						'fields' => array(
							array(
								'id'      => 'sidebar_blog_layout',
								'type'    => 'image_select',
								'title'   => esc_html__( 'Single Post Sidebar Position', 'fastshop' ),
								'desc'    => esc_html__( 'Select sidebar position on Blog.', 'fastshop' ),
								'options' => array(
									'left'  => FASTSHOP_TOOLKIT_URL . '/includes/core/assets/images/left-sidebar.png',
									'right' => FASTSHOP_TOOLKIT_URL . '/includes/core/assets/images/right-sidebar.png',
									'full'  => FASTSHOP_TOOLKIT_URL . '/includes/core/assets/images/default-sidebar.png',
								),
								'default' => 'left',
							),
							array(
								'id'         => 'blog_sidebar',
								'type'       => 'select',
								'title'      => esc_html__( 'Blog Sidebar', 'fastshop' ),
								'options'    => $this->sidebars,
								'default'    => '',
								'dependency' => array( 'sidebar_blog_layout_full', '==', false ),
							),
							array(
								'id'      => 'blog_full_content',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Show Full Content', 'fastshop' ),
								'default' => false,
							),
							array(
								'id'      => 'using_placeholder',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Using Placeholder', 'fastshop' ),
								'default' => true,
							),
						),
					),
					array(
						'name'   => 'single_post',
						'title'  => esc_html__( 'Single Post', 'fastshop' ),
						'fields' => array(
							array(
								'id'      => 'sidebar_single_post_position',
								'type'    => 'image_select',
								'title'   => esc_html__( 'Single Post Sidebar Position', 'fastshop' ),
								'desc'    => esc_html__( 'Select sidebar position on Single Post.', 'fastshop' ),
								'options' => array(
									'left'  => FASTSHOP_TOOLKIT_URL . '/includes/core/assets/images/left-sidebar.png',
									'right' => FASTSHOP_TOOLKIT_URL . '/includes/core/assets/images/right-sidebar.png',
									'full'  => FASTSHOP_TOOLKIT_URL . '/includes/core/assets/images/default-sidebar.png',
								),
								'default' => 'left',
							),
							array(
								'id'         => 'single_post_sidebar',
								'type'       => 'select',
								'title'      => esc_html__( 'Single Post Sidebar', 'fastshop' ),
								'options'    => $this->sidebars,
								'default'    => '',
								'dependency' => array( 'sidebar_single_post_position_full', '==', false ),
							),
						),
					),
				),
			);
			if ( class_exists( 'WooCommerce' ) ) {
				$options[] = array(
					'name'     => 'wooCommerce',
					'title'    => esc_html__( 'WooCommerce', 'fastshop' ),
					'icon'     => 'fa fa-shopping-bag',
					'sections' => array(
						array(
							'name'   => 'shop_product',
							'title'  => esc_html__( 'Shop Page', 'fastshop' ),
							'fields' => array(
								array(
									'type'    => 'subheading',
									'content' => esc_html__( 'Shop Settings', 'fastshop' ),
								),
								array(
									'id'      => 'product_newness',
									'type'    => 'number',
									'title'   => esc_html__( 'Products Newness', 'fastshop' ),
									'default' => '10',
								),
								array(
									'id'      => 'sidebar_shop_page_position',
									'type'    => 'image_select',
									'title'   => esc_html__( 'Shop Page Sidebar Position', 'fastshop' ),
									'desc'    => esc_html__( 'Select sidebar position on Shop Page.', 'fastshop' ),
									'options' => array(
										'left'  => FASTSHOP_TOOLKIT_URL . '/includes/core/assets/images/left-sidebar.png',
										'right' => FASTSHOP_TOOLKIT_URL . '/includes/core/assets/images/right-sidebar.png',
										'full'  => FASTSHOP_TOOLKIT_URL . '/includes/core/assets/images/default-sidebar.png',
									),
									'default' => 'left',
								),
								array(
									'id'         => 'shop_page_sidebar',
									'type'       => 'select',
									'title'      => esc_html__( 'Shop Sidebar', 'fastshop' ),
									'options'    => $this->sidebars,
									'dependency' => array( 'sidebar_shop_page_position_full', '==', false ),
								),
								array(
									'id'      => 'shop_page_layout',
									'type'    => 'image_select',
									'title'   => esc_html__( 'Shop Default Layout', 'fastshop' ),
									'desc'    => esc_html__( 'Select default layout for shop, product category archive.', 'fastshop' ),
									'options' => array(
										'grid' => get_template_directory_uri() . '/assets/images/grid-display.png',
										'list' => get_template_directory_uri() . '/assets/images/list-display.png',
									),
									'default' => 'grid',
								),
								array(
									'id'      => 'product_per_page',
									'type'    => 'number',
									'title'   => esc_html__( 'Products perpage', 'fastshop' ),
									'desc'    => esc_html__( 'Number of products on shop page.', 'fastshop' ),
									'default' => '10',
								),
								array(
									'id'         => 'fastshop_shop_product_style',
									'type'       => 'select_preview',
									'title'      => esc_html__( 'Product Shop Layout', 'fastshop' ),
									'desc'       => esc_html__( 'Select a Product layout in shop page', 'fastshop' ),
									'options'    => $this->product_options,
									'default'    => '1',
									'attributes' => array(
										'data-depend-id' => 'fastshop_shop_product_style',
									),
								),
								array(
									'id'         => 'hide_product_select',
									'type'       => 'select',
									'title'      => esc_html__( 'Select items to Hide', 'fastshop' ),
									'desc'       => esc_html__( 'Select items you want to hide in product.', 'fastshop' ),
									'options'    => array(
										'hide-cat'    => esc_html__( 'Hide Categories', 'fastshop' ),
										'hide-rating' => esc_html__( 'Hide Rating', 'fastshop' ),
										'hide-border' => esc_html__( 'Hide Border', 'fastshop' ),
									),
									'default'    => '',
									'attributes' => array(
										'multiple' => 'multiple',
										'style'    => 'width: 50%',
									),
									'class'      => 'chosen',
									'dependency' => array( 'fastshop_shop_product_style', '==', '1' ),
								),
								array(
									'type'    => 'subheading',
									'content' => esc_html__( 'Image Settings', 'fastshop' ),
								),
								array(
									'id'      => 'enable_shop_banner',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Shop Banner', 'fastshop' ),
									'default' => false,
								),
								array(
									'id'         => 'woo_shop_banner',
									'type'       => 'gallery',
									'title'      => esc_html__( 'Shop Banner', 'fastshop' ),
									'add_title'  => esc_html__( 'Add Banner', 'fastshop' ),
									'dependency' => array( 'enable_shop_banner', '==', true ),
								),
								array(
									'id'         => 'woo_shop_url',
									'type'       => 'text',
									'default'    => '#',
									'title'      => esc_html__( 'Link Banner', 'fastshop' ),
									'dependency' => array( 'enable_shop_banner', '==', true ),
								),
								array(
									'type'    => 'subheading',
									'content' => esc_html__( 'Carousel Settings', 'fastshop' ),
								),
								array(
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1500px )', 'fastshop' ),
									'id'      => 'fastshop_woo_bg_items',
									'type'    => 'select',
									'default' => '4',
									'options' => array(
										'12' => esc_html__( '1 item', 'fastshop' ),
										'6'  => esc_html__( '2 items', 'fastshop' ),
										'4'  => esc_html__( '3 items', 'fastshop' ),
										'3'  => esc_html__( '4 items', 'fastshop' ),
										'15' => esc_html__( '5 items', 'fastshop' ),
										'2'  => esc_html__( '6 items', 'fastshop' ),
									),

								),
								array(
									'title'   => esc_html__( 'Items per row on Desktop( For grid mode )', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'fastshop' ),
									'id'      => 'fastshop_woo_lg_items',
									'type'    => 'select',
									'default' => '4',
									'options' => array(
										'12' => esc_html__( '1 item', 'fastshop' ),
										'6'  => esc_html__( '2 items', 'fastshop' ),
										'4'  => esc_html__( '3 items', 'fastshop' ),
										'3'  => esc_html__( '4 items', 'fastshop' ),
										'15' => esc_html__( '5 items', 'fastshop' ),
										'2'  => esc_html__( '6 items', 'fastshop' ),
									),

								),
								array(
									'title'   => esc_html__( 'Items per row on landscape tablet( For grid mode )', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'fastshop' ),
									'id'      => 'fastshop_woo_md_items',
									'type'    => 'select',
									'default' => '4',
									'options' => array(
										'12' => esc_html__( '1 item', 'fastshop' ),
										'6'  => esc_html__( '2 items', 'fastshop' ),
										'4'  => esc_html__( '3 items', 'fastshop' ),
										'3'  => esc_html__( '4 items', 'fastshop' ),
										'15' => esc_html__( '5 items', 'fastshop' ),
										'2'  => esc_html__( '6 items', 'fastshop' ),
									),

								),
								array(
									'title'   => esc_html__( 'Items per row on portrait tablet( For grid mode )', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'fastshop' ),
									'id'      => 'fastshop_woo_sm_items',
									'type'    => 'select',
									'default' => '4',
									'options' => array(
										'12' => esc_html__( '1 item', 'fastshop' ),
										'6'  => esc_html__( '2 items', 'fastshop' ),
										'4'  => esc_html__( '3 items', 'fastshop' ),
										'3'  => esc_html__( '4 items', 'fastshop' ),
										'15' => esc_html__( '5 items', 'fastshop' ),
										'2'  => esc_html__( '6 items', 'fastshop' ),
									),

								),
								array(
									'title'   => esc_html__( 'Items per row on Mobile( For grid mode )', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'fastshop' ),
									'id'      => 'fastshop_woo_xs_items',
									'type'    => 'select',
									'default' => '6',
									'options' => array(
										'12' => esc_html__( '1 item', 'fastshop' ),
										'6'  => esc_html__( '2 items', 'fastshop' ),
										'4'  => esc_html__( '3 items', 'fastshop' ),
										'3'  => esc_html__( '4 items', 'fastshop' ),
										'15' => esc_html__( '5 items', 'fastshop' ),
										'2'  => esc_html__( '6 items', 'fastshop' ),
									),

								),
								array(
									'title'   => esc_html__( 'Items per row on Mobile( For grid mode )', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device < 480px)', 'fastshop' ),
									'id'      => 'fastshop_woo_ts_items',
									'type'    => 'select',
									'default' => '12',
									'options' => array(
										'12' => esc_html__( '1 item', 'fastshop' ),
										'6'  => esc_html__( '2 items', 'fastshop' ),
										'4'  => esc_html__( '3 items', 'fastshop' ),
										'3'  => esc_html__( '4 items', 'fastshop' ),
										'15' => esc_html__( '5 items', 'fastshop' ),
										'2'  => esc_html__( '6 items', 'fastshop' ),
									),
								),
							),
						),
						array(
							'name'   => 'single_product',
							'title'  => esc_html__( 'Single product', 'fastshop' ),
							'fields' => array(
								array(
									'id'      => 'sidebar_product_position',
									'type'    => 'image_select',
									'title'   => esc_html__( 'Single Product Sidebar Position', 'fastshop' ),
									'desc'    => esc_html__( 'Select sidebar position on single product page.', 'fastshop' ),
									'options' => array(
										'left'  => FASTSHOP_TOOLKIT_URL . '/includes/core/assets/images/left-sidebar.png',
										'right' => FASTSHOP_TOOLKIT_URL . '/includes/core/assets/images/right-sidebar.png',
										'full'  => FASTSHOP_TOOLKIT_URL . '/includes/core/assets/images/default-sidebar.png',
									),
									'default' => 'left',
								),
								array(
									'id'      => 'style_single_product',
									'type'    => 'select',
									'title'   => esc_html__( 'Single Product Style', 'fastshop' ),
									'options' => array(
										'style-standard-horizon'  => esc_html__( 'Detail Standard Horizon', 'fastshop' ),
										'style-standard-vertical' => esc_html__( 'Detail Standard Vertical', 'fastshop' ),
										'style-with-sticky'       => esc_html__( 'Detail With Sticky', 'fastshop' ),
										'style-with-thumbnail'    => esc_html__( 'Detail With Thumbnail', 'fastshop' ),
									),
								),
								array(
									'id'         => 'single_product_sidebar',
									'type'       => 'select',
									'title'      => esc_html__( 'Single Product Sidebar', 'fastshop' ),
									'options'    => $this->sidebars,
									'default'    => '',
									'dependency' => array( 'sidebar_product_position', '==', 'full' ),
								),
								array(
									'id'    => 'enable_share_product',
									'type'  => 'switcher',
									'title' => esc_html__( 'Enable Product Share', 'fastshop' ),
								),
							),
						),
						array(
							'name'   => 'cross_sell',
							'title'  => esc_html__( 'Cross sell', 'fastshop' ),
							'fields' => array(
								array(
									'title'   => esc_html__( 'Cross sell title', 'fastshop' ),
									'id'      => 'fastshop_cross_sells_products_title',
									'type'    => 'text',
									'default' => esc_html__( 'You may be interested in...', 'fastshop' ),
									'desc'    => esc_html__( 'Cross sell title', 'fastshop' ),
								),

								array(
									'title'   => esc_html__( 'Cross sell items per row on Desktop', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1500px )', 'fastshop' ),
									'id'      => 'fastshop_woo_crosssell_ls_items',
									'type'    => 'select',
									'default' => '3',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Cross sell items per row on Desktop', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'fastshop' ),
									'id'      => 'fastshop_woo_crosssell_lg_items',
									'type'    => 'select',
									'default' => '3',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Cross sell items per row on landscape tablet', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'fastshop' ),
									'id'      => 'fastshop_woo_crosssell_md_items',
									'type'    => 'select',
									'default' => '3',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Cross sell items per row on portrait tablet', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'fastshop' ),
									'id'      => 'fastshop_woo_crosssell_sm_items',
									'type'    => 'select',
									'default' => '2',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Cross sell items per row on Mobile', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'fastshop' ),
									'id'      => 'fastshop_woo_crosssell_xs_items',
									'type'    => 'select',
									'default' => '1',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Cross sell items per row on Mobile', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device < 480px)', 'fastshop' ),
									'id'      => 'fastshop_woo_crosssell_ts_items',
									'type'    => 'select',
									'default' => '1',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
							),
						),
						array(
							'name'   => 'related_product',
							'title'  => esc_html__( 'Related Products', 'fastshop' ),
							'fields' => array(
								array(
									'title'   => esc_html__( 'Related products title', 'fastshop' ),
									'id'      => 'fastshop_related_products_title',
									'type'    => 'text',
									'default' => esc_html__( 'Related Products', 'fastshop' ),
									'desc'    => esc_html__( 'Related products title', 'fastshop' ),
								),

								array(
									'title'   => esc_html__( 'Related products items per row on Desktop', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1500px )', 'fastshop' ),
									'id'      => 'fastshop_woo_related_ls_items',
									'type'    => 'select',
									'default' => '3',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Related products items per row on Desktop', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'fastshop' ),
									'id'      => 'fastshop_woo_related_lg_items',
									'type'    => 'select',
									'default' => '3',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Related products items per row on landscape tablet', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'fastshop' ),
									'id'      => 'fastshop_woo_related_md_items',
									'type'    => 'select',
									'default' => '3',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Related product items per row on portrait tablet', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'fastshop' ),
									'id'      => 'fastshop_woo_related_sm_items',
									'type'    => 'select',
									'default' => '2',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Related products items per row on Mobile', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'fastshop' ),
									'id'      => 'fastshop_woo_related_xs_items',
									'type'    => 'select',
									'default' => '1',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Related products items per row on Mobile', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device < 480px)', 'fastshop' ),
									'id'      => 'fastshop_woo_related_ts_items',
									'type'    => 'select',
									'default' => '1',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
							),
						),
						array(
							'name'   => 'upsells_product',
							'title'  => esc_html__( 'Up sells Products', 'fastshop' ),
							'fields' => array(
								array(
									'title'   => esc_html__( 'Up sells title', 'fastshop' ),
									'id'      => 'fastshop_upsell_products_title',
									'type'    => 'text',
									'default' => esc_html__( 'You may also like&hellip;', 'fastshop' ),
									'desc'    => esc_html__( 'Up sells products title', 'fastshop' ),
								),

								array(
									'title'   => esc_html__( 'Up sells items per row on Desktop', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1500px )', 'fastshop' ),
									'id'      => 'fastshop_woo_upsell_ls_items',
									'type'    => 'select',
									'default' => '3',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Up sells items per row on Desktop', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'fastshop' ),
									'id'      => 'fastshop_woo_upsell_lg_items',
									'type'    => 'select',
									'default' => '3',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Up sells items per row on landscape tablet', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'fastshop' ),
									'id'      => 'fastshop_woo_upsell_md_items',
									'type'    => 'select',
									'default' => '3',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Up sells items per row on portrait tablet', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'fastshop' ),
									'id'      => 'fastshop_woo_upsell_sm_items',
									'type'    => 'select',
									'default' => '2',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Up sells items per row on Mobile', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'fastshop' ),
									'id'      => 'fastshop_woo_upsell_xs_items',
									'type'    => 'select',
									'default' => '2',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
								array(
									'title'   => esc_html__( 'Up sells items per row on Mobile', 'fastshop' ),
									'desc'    => esc_html__( '(Screen resolution of device < 480px)', 'fastshop' ),
									'id'      => 'fastshop_woo_upsell_ts_items',
									'type'    => 'select',
									'default' => '1',
									'options' => array(
										'1' => esc_html__( '1 item', 'fastshop' ),
										'2' => esc_html__( '2 items', 'fastshop' ),
										'3' => esc_html__( '3 items', 'fastshop' ),
										'4' => esc_html__( '4 items', 'fastshop' ),
										'5' => esc_html__( '5 items', 'fastshop' ),
										'6' => esc_html__( '6 items', 'fastshop' ),
									),
								),
							),
						),
					),
				);
			}

			$options[] = array(
				'name'   => 'social_settings',
				'title'  => esc_html__( 'Social Settings', 'fastshop' ),
				'icon'   => 'fa fa-users',
				'fields' => array(
					array(
						'type'    => 'subheading',
						'content' => esc_html__( 'Social User', 'fastshop' ),
					),
					array(
						'id'              => 'user_all_social',
						'type'            => 'group',
						'title'           => esc_html__( 'Social', 'fastshop' ),
						'button_title'    => esc_html__( 'Add New Social', 'fastshop' ),
						'accordion_title' => esc_html__( 'Social Settings', 'fastshop' ),
						'fields'          => array(
							array(
								'id'      => 'title_social',
								'type'    => 'text',
								'title'   => esc_html__( 'Title Social', 'fastshop' ),
								'default' => 'Facebook',
							),
							array(
								'id'      => 'link_social',
								'type'    => 'text',
								'title'   => esc_html__( 'Link Social', 'fastshop' ),
								'default' => 'https://facebook.com',
							),
							array(
								'id'      => 'icon_social',
								'type'    => 'icon',
								'title'   => esc_html__( 'Icon Social', 'fastshop' ),
								'default' => 'fa fa-facebook',
							),
						),
					),
				),
			);

			$options[] = array(
				'name'   => 'typography',
				'title'  => esc_html__( 'Typography Options', 'fastshop' ),
				'icon'   => 'fa fa-font',
				'fields' => array(
					array(
						'id'      => 'typography_font_family',
						'type'    => 'typography',
						'title'   => esc_html__( 'Font Family', 'fastshop' ),
						'default' => array(
							'family'  => 'Open Sans',
							'variant' => '400',
						),
					),
					array(
						'id'      => 'typography_font_size',
						'type'    => 'number',
						'title'   => esc_html__( 'Font Size', 'fastshop' ),
						'default' => 14,
					),
					array(
						'id'      => 'typography_line_height',
						'type'    => 'number',
						'title'   => esc_html__( 'Line Height', 'fastshop' ),
						'default' => 24,
					),
				),
			);

			$options[] = array(
				'name'   => 'backup_option',
				'title'  => esc_html__( 'Backup Options', 'fastshop' ),
				'icon'   => 'fa fa-bold',
				'fields' => array(
					array(
						'type'  => 'backup',
						'title' => esc_html__( 'Backup Field', 'fastshop' ),
					),
				),
			);


			CSFramework::instance( $settings, $options );
		}
	}

	new Fastshop_ThemeOption();
}
