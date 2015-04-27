<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_head', $head_info); ?>

	<input type="text" name="<?php echo $name ?>" class="binded_message_input vp-input input-large" value="<?php echo esc_attr($value); ?>" />
	<div class="zx_ajax_message alert success">
		fsdafsd
	</div>

	<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_foot'); ?>