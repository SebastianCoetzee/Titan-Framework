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
        
        /**
	 * Constructor
	 */
	function __construct( $settings, $owner ) {
		parent::__construct( $settings, $owner );

		add_action( 'admin_head', array( __CLASS__, 'createScripts' ) );
	}
        
        private function openFormTable(){
                ?>
                <table class="form-table tf-form-table">
                    <tbody>
                <?php
        }
        
        private function closeFormTable(){
                ?>
                    </tbody>
                </table>
                <?php
        }
        
        /**
         * Creates the actions that allow the text fields to be repeated and removed.
         */
	public static function createScripts() {
		?>
		<script>
		jQuery(document).ready(function($) {
			$('.tf-repeatable-text tbody tr td .dashicons-no').click(function() {
				$(this).parent().parent().remove();
                                $('.tf-repeatable-text tbody tr').each( 
                                        function( index ){
                                                var $this = $(this);
                                                var meta_key = $this.attr('tf-repeatable-text-id');
                                                $this.find('label').attr('for', meta_key + '[' + index + ']').text('Field ' + (index + 1));
                                                $this.find('input').attr('name', meta_key + '[' + index + ']').attr('id', meta_key + '[' + index + ']');
                                        }
                                );
			});
                        
                        $('.tf-repeatable-text-button').click(
                                function(){
                                        
                                }
                        );
		});
		</script>
		<?php
	}
        
	/*
	 * Display for options and meta
	 */
	public function display() {
                
                $ID = $this->getID();
                $maxlength = $this->settings['maxlength'];
                $value = $this->getValue();
                $unit = $this->settings['unit'];
                
		$this->echoOptionHeader();
                $this->openFormTable();
                
                foreach ($value as $index => $val):
                
                        $meta_key = $this->getID() . "[$index]";
                
                        ?>
                        <tr valign="top" tf-repeatable-text-id="<?php echo $ID; ?>">
                                <th scope="row">
                                        <label for="<?php echo $meta_key;?>">Field <?php echo $index + 1;?></label>
                                </th>
                                <td>
                                        <?php
                                        printf("<input class=\"regular-text\" name=\"%s\" placeholder=\"%s\" maxlength=\"%s\" id=\"%s\" type=\"%s\" value=\"%s\"\> %s",
                                        $meta_key,
                                        $this->settings['placeholder'],
                                        $this->settings['maxlength'],
                                        $meta_key,
                                        $this->settings['is_password'] ? 'password' : 'text',
                                        esc_attr( $val ), 
                                        $this->settings['unit'] 
                                        );
                                        ?>
                                        <div class="dashicons dashicons-no"></div>
                                </td>
                        </tr>
                <?php
                
                endforeach;
                
                ?>
                
                <tr valign="top">
                    <td><a class="button tf-repeatable-text-button">Add Text Field</a></td>
                </tr>
                
                <?php
            
                $this->closeFormTable();
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