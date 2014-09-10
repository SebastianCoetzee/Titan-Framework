<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TitanFrameworkOptionRepeatableText extends TitanFrameworkOption {

	public $defaultSecondarySettings = array(
		'placeholder' => '', // show this when blank
		'is_password' => false,
		'sanitize_callbacks' => array(),
		'maxlength' => '',
		'unit' => ''
	);
        
        private function openFormTable(){
            ?>
            <table class="form-table tf-form-table">
                <tbody>
            <?php
        }
        
	/*
	 * Display for options and meta
	 */
	public function display() {
		$this->echoOptionHeader();
                
                $ID = $this->getID() . '[0]';
                $placeholder = $this->settings['placeholder'];
                $maxlength = $this->settings['maxlength'];
                $value = $this->getValue();
                
                $this->openFormTable();
                ?>
                
                    
        <tr valign="top" style="padding-top: 0px;">
            <th scope="row" style="padding-top: inherit;">
                <label for="<?php echo $ID;?>">Line 1</label>
            </th>
            <td style="padding-top: inherit;">
            <?php echo "<input class=\"regular-text\" name=\"$ID\" placeholder=\"$placeholder\" maxlength=\"$maxlength\" id=\"\" type=\"text\" value=\"\"\>";?>
            </td>
        </tr>
        <?php 
            $ID = $this->getID() . '[1]';
            $placeholder = $this->settings['placeholder'];
            $maxlength = $this->settings['maxlength'];
            $value = esc_attr( $this->getValue() );
        ?>
        <tr valign="top" style="padding-top: 0px;">
            <th scope="row" style="padding-top: inherit;">
                <label for="<?php echo $ID;?>">Line 2</label>
            </th>
            <td style="padding-top: inherit;">
            <?php echo "<input class=\"regular-text\" name=\"$ID\" placeholder=\"$placeholder\" maxlength=\"$maxlength\" id=\"\" type=\"text\" value=\"\"\>";?>
            </td>
        </tr>
        <tr valign="top" style="padding-top: 0px;">
            <td style="padding-top: inherit;padding-left:0px;"><a class="button">Add Line</a></td>
        </tr>
    </tbody>
</table><?php
		$this->echoOptionFooter();
	}

	public function cleanValueForSaving( $value ){
                foreach ($value as $index => $val){
                    $value[$index] = sanitize_text_field($val);
                }
		if( !empty( $this->settings['sanitize_callbacks'] ) ){
			foreach( $this->settings['sanitize_callbacks'] as $callback ){
				$value = call_user_func_array( $callback, array( $value, $this ) );
			}
		}

		return $value;
	}

	/*
	 * Display for theme customizer
	 */
	public function registerCustomizerControl( $wp_customize, $section, $priority = 1 ) {
		$wp_customize->add_control( new TitanFrameworkCustomizeControl( $wp_customize, $this->getID(), array(
			'label' => $this->settings['name'],
			'section' => $section->settings['id'],
			'settings' => $this->getID(),
			'description' => $this->settings['desc'],
			'priority' => $priority,
		) ) );
	}
}