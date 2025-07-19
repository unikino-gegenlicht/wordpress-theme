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
}