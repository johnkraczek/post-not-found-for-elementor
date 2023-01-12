<?php

/**
 * Post Not Found Content By FunnelPress
 * 
 * @since 0.1.0
 *
 * @license MIT
 * 
 */

namespace ElementorPostNotFound\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Post_Not_Found_Content
 */
class PNF_Manager
{
    private static $section_templates = null;
    private static $page_templates = null;
    private static $widget_templates = null;

    public function register_controls($element, $args)
    {
		$element->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

        $element->add_control(
            'enable_post_not_found', [
                'label' => __('Show Not Found Content', 'post-not-found-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', 'post-not-found-for-elementor'),
                'label_off' => __('No', 'post-not-found-for-elementor'),
                'return_value' => 'yes',
            ]
        );

        $element->add_control(
            'type_of_content',
            [
                'label' => __('Type Of Content', 'post-not-found-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => [
                    'saved_section' => __('Saved Section', 'post-not-found-for-elementor'),
                    'simple_text' => __('Simpe Text', 'post-not-found-for-elementor'),
                ],
                'default' => 'saved_section',
                'condition' => [
                    'enable_post_not_found' => 'yes',
                ],
            ]
        );

        // Display Saved Section
        $element->add_control(
            'post_action_display_saved_section',
            [
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label' => __('Saved Section:', 'post-not-found-for-elementor'),
                'options' => $this::get_saved_data('section'),
                'default' => 'Select',
                'multiple' => false,
                'label_block' => true,
                'condition' => [
                    'enable_post_not_found' => 'yes',
                    'type_of_content' => 'saved_section',
                ],
            ]
        );
        $element->add_control(
            'post_action_display_simple_text',
            [
				'label' => __( 'Text to display', 'post-not-found-for-elementor' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'rows' => 10,
				'default' => __( 'No Posts Found', 'post-not-found-for-elementor' ),
                'placeholder' => __( 'Type your description here', 'post-not-found-for-elementor' ),
                'condition' => [
                    'enable_post_not_found' => 'yes',
                    'type_of_content' => 'simple_text',
                ],
            ]
        );
        $element->add_control(
			'post_action_simple_text_alignment',
			[
				'label' => __( 'Alignment', 'post-not-found-for-elementor' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'post-not-found-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'post-not-found-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'post-not-found-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
                'toggle' => true,
                'condition' => [
                    'enable_post_not_found' => 'yes',
                    'type_of_content' => 'simple_text',
                ],
			]
		);
    }

    private static function get_saved_data($type = 'page')
    {

        $template_type = $type . '_templates';

        $templates_list = array();

        if ((null === self::$page_templates && 'page' === $type) || (null === self::$section_templates && 'section' === $type) || (null === self::$widget_templates && 'widget' === $type)) {

            $posts = get_posts(
                array(
                    'post_type' => 'elementor_library',
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'posts_per_page' => '-1',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'elementor_library_type',
                            'field' => 'slug',
                            'terms' => $type,
                        ),
                    ),
                )
            );

            foreach ($posts as $post) {

                $templates_list[] = array(
                    'id' => $post->ID,
                    'name' => $post->post_title,
                );
            }

            self::${$template_type}[-1] = __('Select', 'post-not-found-for-elementor');

            if (count($templates_list)) {
                foreach ($templates_list as $saved_row) {
                    $content_id = $saved_row['id'];
                    $content_id = apply_filters('wpml_object_id', $content_id);
                    self::${$template_type}[$content_id] = $saved_row['name'];
                }
            } else {
                self::${$template_type}['no_template'] = __('It seems that, you have not saved any of those templates yet.', 'post-not-found-for-elementor');
            }
        }
        return self::${$template_type};
    }

    public function render_not_found($query, $widget)
    {
        $total = $query->found_posts;

        if ($total == 0) {

            $settings = $widget->get_settings();

            if (!empty($settings['enable_post_not_found']) && $settings['enable_post_not_found'] == 'yes') {
                if (!empty($settings['type_of_content'])) {
                    
                    if ($settings['type_of_content'] == 'saved_section') {
                        $section = $settings['post_action_display_saved_section'];
                        $content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display(apply_filters('wpml_object_id', $section, 'page'));
                        echo $content;
                    }
                    if ($settings['type_of_content'] == 'simple_text') {
                        $text = $settings['post_action_display_simple_text'];
                        $align = $settings['post_action_simple_text_alignment'];
                        echo '<div style="text-align: '.$align.'">
                        <p>'.$text.'</p>
                        </div>';
                    }
                }
            }
        }
    }

    public function __construct(){
        if (!defined('ELEMENTOR_PATH')) {
            return;
        }
        add_action('elementor/element/posts/section_query/before_section_end', [$this, 'register_controls'], 10, 2);
        add_action('elementor/query/query_results', [$this, 'render_not_found'], 10, 2);
    }
}
