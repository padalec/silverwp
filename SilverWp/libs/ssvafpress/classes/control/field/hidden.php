<?php

class VP_Control_Field_Hidden extends VP_Control_Field
{

    protected $_placeholder;

    public function __construct()
    {
            parent::__construct();
    }

    public static function withArray($arr = array(), $class_name = null)
    {
        if (is_null($class_name)) {
            $instance = new self();
        } else {
            $instance = new $class_name;
        }
        $instance->_basic_make($arr);
        return $instance;
    }

    protected function _setup_data()
    {
        parent::_setup_data();
    }

    public function render($is_compact = true)
    {
        // Setup Data
        $this->_setup_data();
        $this->add_data('is_compact', $is_compact);
        return VP_View::instance()->load('control/hidden', $this->get_data());
    }
}
/**
 * EOF
 */
