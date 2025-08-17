<?php

namespace GGL\customizer;

use WP_Customize_Control;

function custom_controls() : void {
	class WP_CheckboxList_Customize_Control extends WP_Customize_Control {
		public $type = 'multi-select';

		public function render_content() {
			$input_id         = '_customize-input-' . $this->id;
			$description_id   = '_customize-description-' . $this->id;
			$describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';

			if ( empty( $this->choices ) ) {
				return;
			}
			?>

			<?php if ( ! empty( $this->label ) ) : ?>
                <label for="<?= esc_attr( $input_id ) ?>" class="customize-control-title">
					<?= esc_html( $this->label ) ?>
                </label>
			<?php endif; ?>

			<?php if ( ! empty( $this->description ) ) : ?>
                <span id="<?= esc_attr( $description_id ); ?>" class="customize-control-description description">
                <?= ( $this->description ) ?>
            </span>
			<?php endif; ?>

            <select multiple id="<?= esc_attr( $input_id ) ?>" <?= $describedby_attr ?> <?php $this->link() ?> >
				<?php foreach ( $this->choices as $value => $label ) : ?>
                    <option value="<?= esc_attr( $value ) ?>" <?= in_array( $value, $this->value() ) ? selected( 1, 1, false ) : '' ?> >
						<?= esc_html( $label ) ?>
                    </option>
				<?php endforeach; ?>
            </select>

			<?php
		}
	}
	class MultiInputControl extends WP_Customize_Control{
		public $type = 'multi_input';
		public function enqueue(){
			wp_enqueue_script( 'custom_controls', get_template_directory_uri().'/inc/customizer/controls/js/multi-input.js', array( 'jquery' ),'', true );
			wp_enqueue_style( 'custom_controls_css', get_template_directory_uri().'/inc/customizer/controls/css/custom-controls.css');
		}
		public function render_content(){
			?>
            <label class="customize_multi_input">
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <p><?php echo wp_kses_post($this->description); ?></p>
                <input type="hidden" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr($this->value()); ?>" class="customize_multi_value_field" data-customize-setting-link="<?php echo esc_attr($this->id); ?>"/>
                <div class="customize_multi_fields">
                    <div class="set">
                        <input type="text" value="" class="customize_multi_single_field"/>
                        <a href="#" class="customize_multi_remove_field">X</a>
                    </div>
                </div>
                <a href="#" class="button button-primary customize_multi_add_field"><?php esc_attr_e('Add More', 'mytheme') ?></a>
            </label>
			<?php
		}
	}
}