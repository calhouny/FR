<?php



return array(
	array(
		'type'      => 'group',
		'repeating' => true,
		'sortable'  => true,
		'name'      => 'hover_info',
		'priority'  => 'high',
		'title'     => __('Hover Info', 'vp_textdomain'),
		'fields'    => array(
		
		
		
				array(
					'type' => 'notebox',
					'name' => 'nb_1',
					'label' => __('Author Comment', 'vp_textdomain'),
					'description' => __('To get all features working, please buy the pro version here <a target="_blank" href="http://demo.wpeffects.com/amazing-hover-effects/">Amazing Hover Effects Pro</a> for only $11', 'vp_textdomain'),
					'status' => 'error',
					),
	
			array(
					'type' => 'upload',
					'name' => 'image',
					'label' => __('Hover Image', 'vp_textdomain'),
				),
				
				
			array(
				'type'  => 'textbox',
				'name'  => 'title',
				'label' => __('Heading', 'vp_textdomain'),
				'default' => 'Heading Here',
				
			),			
			
			
			array(
				'type'  => 'textarea',
				'name'  => 'description',
				'label' => __('Description', 'vp_textdomain'),
				'default' => 'Description goes here',
			),
			
			
				array(
				'type'  => 'textbox',
				'name'  => 'link',
				'label' => __('Image Link (Pro Only)', 'vp_textdomain'),
				'default' => 'http://google.com',
			),

		),
	),
);

/**
 * EOF
 */