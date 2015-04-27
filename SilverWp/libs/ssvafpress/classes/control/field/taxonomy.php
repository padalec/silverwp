<?php

class VP_Control_Field_Taxonomy extends VP_Control_FieldMulti implements VP_MultiSelectable
{

    public function __construct()
    {
        parent::__construct();
        $this->_value = array();
        $this->addScripts();
    }

    # arr is the field options array
    # You can add extra data to field array as you would form the setting
    public static function withArray($arr = array(), $class_name = null)
    {
        if (is_null($class_name)) {
            $instance = new self();
        } else {
            $instance = new $class_name;
        }

        # Add Taxonomy fields
        $terms = get_terms($arr['taxonomy'], $arr['params']);
        $arr['items'] = call_user_func('VP_Control_Field_Taxonomy::terms_to_vp_items', $terms);

        # Turns the array into variables
        $instance->_basic_make($arr);
        return $instance;
    }

    public function render($is_compact = false)
    {
        $this->_setup_data();
        $this->add_data('is_compact', $is_compact);
        $view = VP_View::instance()->load('control/taxonomy', $this->get_data());
        return $view;
    }

    # Gets the terms and format to VP item
    public static function terms_to_vp_items($terms) {
        $items = array();
        foreach ($terms as $term ) {
            $item = array();
            $item['value'] = $term->term_id;
            $item['label'] = $term->name;
            $items[] = $item;
        }
        return $items;
    }
    /**
     * add js script for display font select
     * 
     * @access public
     * @return void
     */
    private function addScripts()
    {
        wp_enqueue_style('fontico', SILVERWP_THEME_URL . '/lib/SilverWp/libs/vafpress/public/css/vendor/select2.css');
        wp_enqueue_script('fontico', SILVERWP_THEME_URL . '/lib/SilverWp/libs/vafpress/public/js/vendor/select2.min.js', array(), false, true);
    }
}
/**
 * EOF
 */
