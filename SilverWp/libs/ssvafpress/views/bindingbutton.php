<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_head', $head_info); ?>
	<div class="vp-field" style="text-align: right;">
		<div class="field">
			<div class="input">
				<button class="zx-preview-button vp-button button button-primary" value="<?php echo $default; ?>" name="<?php echo $name; ?>"><i class="fa fa-refresh"></i>&nbsp;&nbsp;<?php _e('Load Preview', 'ZX_TEXTDOMAIN') ?></button>
			</div>
			<div class="vp-js-bind-loader vp-field-loader vp-hide"><img src="<?php VP_Util_Res::img_out('ajax-loader.gif', ''); ?>"></div>
		</div>
	</div>
	<?php if(!$is_compact) echo VP_View::instance()->load('control/template_control_foot'); ?>