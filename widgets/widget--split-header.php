<?php
// namespace Elementor;

class Elementor_EA_Split_Header_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'split-header';
    }

    public function get_title()
    {
        return __('Split Header', 'elementor-extra-additions');
    }

    // public function get_icon()
    // {
    //     return 'fa fa-code';
    // }

    public function get_categories()
    {
        return ['general'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Text', 'elementor-extra-additions'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'content',
            [
                'label' => __('Text to display', 'elementor-extra-additions'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __('Your Content', 'elementor-extra-additions'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => __('Typography', 'elementor-extra-additions'),
                'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-ea__split-header',
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'colour_section',
            [
                'label' => __('Colour', 'elementor-extra-additions'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'colour-left', [
                'label' => __('Left colour', 'elementor-extra-additions'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-ea__split-header:after' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-ea__split-header span.word-seperator' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'colour-right', [
                'label' => __('Right colour', 'elementor-extra-additions'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-ea__split-header' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'seperation_type',
            [
                'label' => __('Seperator Type', 'elementor-extra-additions'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Word', 'your-plugin'),
                'label_off' => __('Value', 'your-plugin'),
                'return_value' => 'word',
            ]
        );

        $this->add_control(
            'position_value', [
                'label' => __('Position', 'elementor-extra-additions'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 50,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-ea__split-header:after' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'seperation_type',
                            'operator' => '!in',
                            'value' => [
                                'word',
                            ],
                        ],
                    ],
                ],

            ]
        );
        $this->add_control(
            'position_word', [
                'label' => __('Word amount', 'elementor-extra-additions'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 200,
                'default' => 1,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'seperation_type',
                            'operator' => 'in',
                            'value' => [
                                'word',
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $content = $settings['content'];
        $seperation_type = 'value';
        if ($settings['seperation_type'] == 'word') {
            $seperation_type = 'word';
            $content = explode(' ', $content);
            array_splice($content, intval($settings['position_word']), 0, ['</span>']);
            array_unshift($content, '<span class="word-seperator">');
            $content = implode(' ', $content);
        }

        echo '<h1 class="elementor-ea__split-header ' . $seperation_type . '" data-heading="' . $settings['content'] . '">';
        echo $content;
        echo '</h1>';
    }
}
