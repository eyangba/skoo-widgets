<?php
/**
 * Skoo_Widget Class.
 */
class Skoo_Widget extends WP_Widget {
	
	function build_options($options, $instance) {

		/*global $allowedtags;
		$optionsframework_settings = get_option( 'optionsframework' );

		// Gets the unique option id
		if ( isset( $optionsframework_settings['id'] ) ) {
			$option_name = $optionsframework_settings['id'];
		}
		else {
			$option_name = 'options_framework_theme';
		};

		$settings = get_option($option_name);
		$options =& _optionsframework_options(); */

		$counter = 0;
		$menu = '';

		foreach ( $options as $value ) {

			$val = '';
			$select_value = '';
			$checked = '';
			$output = '';
			
			$cla = 'skoo_widgets_item';
			if ( isset( $value['class'] ) ) {
				$cla = ' ' . $value['class'];
			}
			
			if ( isset( $value['hidden_by'] ) ) {
				if ( $instance[ $value['hidden_by'] ] == $value['hidden_condition'] ) {
					$cla .= ' hidden';
				}
			}
				
			$output .= '<p id="' . $this->get_field_id( $value['id'] ) . '_wrap" class="' . $cla . '">' . "\n";
			$output .= '<label>' . esc_html( $value['name'] ) . '</label>' . "\n";

			// Set default value to $val
			if ( isset( $value['std'] ) ) {
				$val = $value['std'];
			}

			// If the option is already saved, ovveride $val
			//if ( ( $value['type'] != 'heading' ) && ( $value['type'] != 'info') ) {
			if ( isset( $instance[ $value['id'] ] ) ) {
				$val = $instance[ $value['id'] ];
				// Striping slashes of non-array options
				if ( !is_array($val) ) {
					$val = stripslashes( $val );
				}
			}
			//}

			// If there is a description save it for labels
			$explain_value = '';
			if ( isset( $value['desc'] ) ) {
				$explain_value = $value['desc'];
			}

			switch ( $value['type'] ) {

			// Basic text input
			case 'text':
				$output .= '<input id="' . $this->get_field_id( $value['id'] ) . '" class="widefat" name="' . $this->get_field_name( $value['id'] ) . '" type="text" value="' . esc_attr( $val ) . '" />';
				break;

			// Textarea
			case 'textarea':
				$rows = '8';

				if ( isset( $value['settings']['rows'] ) ) {
					$custom_rows = $value['settings']['rows'];
					if ( is_numeric( $custom_rows ) ) {
						$rows = $custom_rows;
					}
				}

				$val = stripslashes( $val );
				$output .= '<textarea id="' . $this->get_field_id( $value['id'] ) . '" class="of-input" name="' . $this->get_field_name( $value['id'] ) . '" rows="' . $rows . '">' . esc_textarea( $val ) . '</textarea>';
				break;

			// Select Box
			case 'select':
				$output .= '<select class="widefat" name="' . $this->get_field_name( $value['id'] ) . '" id="' . $this->get_field_id( $value['id'] ) . '">';

				foreach ( $value[ 'options' ] as $key => $option ) {
					$selected = '';
					if ( $val != '' ) {
						if ( $val == $key) { $selected = ' selected="selected"';}
					}
					$output .= '<option'. $selected .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
				}
				$output .= '</select>';
				
				break;

				// Checkbox
			case "checkbox":
					$output .= '<input id="' . $this->get_field_id( $value['id'] ) . '" class="checkbox of-input" type="checkbox" name="' . $this->get_field_name( $value['id'] ) . '" '. checked( $val, 1, false) .' />';
					$output .= '<label class="explain" for="' . esc_attr( $value['id'] ) . '">' . wp_kses( $explain_value, $allowedtags) . '</label>';
					break;

				// Multicheck
				case "multicheck":
					foreach ($value['options'] as $key => $option) {
						$checked = '';
						$label = $option;
						$option = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($key));

						$id = $this->get_field_id( $value['id'] . '-'. $key );
						$name = $this->get_field_name( $value['id'] . '-'. $key );

						if ( isset($val[$option]) ) {
							$checked = checked($val[$option], 1, false);
						}

						$output .= '<input id="' . esc_attr( $id ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $name ) . '" ' . $checked . ' /><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
					}
					break;

				// Radio Box
				case "radio":
					$name = $this->get_field_name( $value['id'] );
					foreach ($value['options'] as $key => $option) {
						$id = $this->get_field_id( $value['id'] . '-'. $key );
						$output .= '<input class="of-input of-radio" type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="'. esc_attr( $key ) . '" '. checked( $val, $key, false) .' /><label for="' . esc_attr( $id ) . '">' . esc_html( $option ) . '</label>';
					}
					break;


			}

			/*if ( ( $value['type'] != "heading" ) && ( $value['type'] != "info" ) ) {
				$output .= '</div>';
				if ( ( $value['type'] != "checkbox" ) && ( $value['type'] != "editor" ) ) {
					$output .= '<div class="explain">' . wp_kses( $explain_value, $allowedtags) . '</div>'."\n";
				}
				$output .= '</div></div>'."\n";
			}*/
			
			$output .= '</p>'."\n";
			echo $output;
		}
		//echo '</p>';
	}
	
	
}


?>
