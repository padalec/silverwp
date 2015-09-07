<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_head', $head_info); ?>
<input type="text" name="<?php echo $name ?>" <?php echo $this->flat_attributes( $attributes ); ?> value="<?php echo esc_attr($value); ?>" />

<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_foot'); ?>