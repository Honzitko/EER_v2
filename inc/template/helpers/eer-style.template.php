<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Style {

	public static function eer_print_styles_callback() {
		//if ((intval(ESR()->settings->eer_get_option('disable_styles', -1)) !== 1) && !is_admin()) {
			$styles = EER()->settings->eer_get_registered_settings()['style'];
			?>
			<style>
				<?php
					foreach ($styles as $group_key => $group_values) {
						foreach ($group_values as $key => $value) {
							if (isset($value['selector'])) {
								$eer_settings = EER()->settings->eer_get_option($value['id']);

								if (!isset($value['optional']) || (isset($value['optional']) && isset($eer_settings['enable_style']) && ($eer_settings['enable_style'] == 'on'))) {
									$property = is_array($value['property']) ? $value['property'] : [$value['property']];
									if (in_array('background', $property)) {
										$css_value = $eer_settings;
										$default_css_values = $value['std'];
										$rule = $value['selector'] . '{';

										if (isset($css_value['background_color']) && ($css_value['background_color'] !== '')) {
											$rule .= 'background-color:' . self::hex2rgba(isset($css_value['background_color']) ? $css_value['background_color'] : $default_css_values['background_color'], isset($css_value['background_opacity']) ? (float) $css_value['background_opacity'] : (float) $default_css_values['background_opacity']) . ';';
										}
										if (isset($css_value['border_color']) && ($css_value['border_color'] !== '')) {
											$rule .= 'border: ' . (isset($css_value['border_width']) ? $css_value['border_width'] : $default_css_values['border_width']) . 'px solid ' . (isset($css_value['border_color']) ? $css_value['border_color'] : $default_css_values['border_color']) . ';';
										}

										$rule .= '}';

										echo $rule;
									}
									if (in_array('font', $property)) {
										$css_value = $eer_settings;
										$default_css_values = $value['std'];

										$rule = $value['selector'] . '{';

										if (isset($default_css_values['color']) || isset($css_value['color'])) {
											$rule .= 'color:' . (isset($css_value['color']) ? $css_value['color'] : $default_css_values['color']) . ';';
										}

										if (isset($default_css_values['size']) || isset($css_value['size'])) {
											$rule .= 'font-size: ' . (isset($css_value['size']) ? $css_value['size'] : $default_css_values['size']) . 'px;';
											$rule .= 'line-height: ' . (isset($css_value['size']) ? $css_value['size'] : $default_css_values['size']) . 'px;';
										}

										$rule .= '}';

										echo $rule;
									}
									if (in_array('fill', $property)) {
										$css_value = $eer_settings;
										$default_css_values = $value['std'];

										$rule = $value['selector'] . '{';

										if (isset($default_css_values['color']) || isset($css_value['color'])) {
											$rule .= 'fill:' . (isset($css_value['color']) ? $css_value['color'] : $default_css_values['color']) . ';';
										}

										$rule .= '}';

										echo $rule;
									}
									if (in_array('color', $property)) {
										$css_value = $eer_settings;
										$default_css_values = $value['std'];

										$rule = $value['selector'] . '{';

										if (isset($default_css_values['color']) || isset($css_value['color'])) {
											$rule .= 'color:' . (isset($css_value['color']) ? $css_value['color'] : $default_css_values['color']) . ';';
										}

										$rule .= '}';

										echo $rule;
									}
									if (in_array('background-color', $property)) {
										$css_value = $eer_settings;
										$default_css_values = $value['std'];

										$rule = $value['selector'] . '{';

										if (isset($default_css_values['color']) || isset($css_value['color'])) {
											$rule .= 'background-color:' . (isset($css_value['color']) ? $css_value['color'] : $default_css_values['color']) . ';';
										}

										$rule .= '}';

										echo $rule;
									}
								}
							}
						}
					}
				?>
			</style>
			<?php
		//}
	}

	private static function hex2rgba($color, $opacity = -1) {
		$default = 'rgb(0,0,0)';

		//Return default if no color provided
		if (empty($color)) {
			return $default;
		}

		//Sanitize $color if "#" is provided
		if ($color[0] == '#') {
			$color = substr($color, 1);
		}

		//Check if color has 6 or 3 characters and get values
		if (strlen($color) == 6) {
			$hex = [$color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]];
		} elseif (strlen($color) == 3) {
			$hex = [$color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]];
		} else {
			return $default;
		}

		//Convert hexadec to rgb
		$rgb = array_map('hexdec', $hex);

		//Check if opacity is set(rgba or rgb)
		if ($opacity !== -1) {
			if (abs($opacity) > 1) {
				$opacity = 1.0;
			}
			$output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode(",", $rgb) . ')';
		}

		//Return rgb(a) color string
		return $output;
	}
}

add_action('eer_print_styles', ['EER_Template_Style', 'eer_print_styles_callback']);