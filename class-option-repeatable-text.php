<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TitanFrameworkOptionRepeatableText extends TitanFrameworkOption {

	public $defaultSecondarySettings = array(
		'placeholder' => '', // show this when blank
		'is_password' => false,
		'sanitize_callbacks' => array(),
		'maxlength' => '',
		'unit' => '',
                'field_label' => 'Field'
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
                //Define the function variables in the global space so that they can be used again later.
                var tf_repeatable_text_init, tf_repeatable_text_renumber;
                        
		jQuery(document).ready(function($) {
			
                        //Binds the actions to the click event of the remove icons.
                        tf_repeatable_text_init = function(){
                                $('.tf-repeatable-text tbody tr td .dashicons-no').click(function() {
                                        $(this).parent().parent().remove();
                                        tf_repeatable_text_renumber();
                                });
                        };
                        
                        //Renumber all the labels and 'name' attributes of the inputs of the repeatable text fields 
                        tf_repeatable_text_renumber = function(){
                                $('.tf-repeatable-text tbody tr').each( 
                                        function( index ){
                                                var $this = $(this);
                                                var id = $this.attr('tf-repeatable-text-id');
                                                $this.find('label').attr('for', id + '[' + index + ']').text('Field ' + (index + 1));
                                                $this.find('input').attr('name', id + '[' + index + ']').attr('id', id + '[' + index + ']');
                                        }
                                );
                        };
                        
                        
                        //Binds the actions to the click events when the DOM is ready.
                        tf_repeatable_text_init();
                        
                        //Adds a new field when the button is clicked.
                        $('.tf-repeatable-text-button').click(
                                function(){
                                        var $this = $(this);
                                        var id = $this.attr('tf-repeatable-text-id');
                                        var maxlength = $this.attr('tf-repeatable-text-maxlength');
                                        var type = $this.attr('tf-repeatable-text-type');
                                        var placeholder = $this.attr('tf-repeatable-text-placeholder');
                                        var unit = $this.attr('tf-repeatable-text-unit');
                                        
                                        var new_element;
                                        new_element +=  "<tr valign=\"top\" tf-repeatable-text-id=\"" + id + "\">";
                                        new_element +=          "<th scope=\"row\">";
                                        new_element +=                  "<label></label>";
                                        new_element +=          "</th>";
                                        new_element +=          "<td>";
                                        new_element +=                  "<input class=\"regular-text\" placeholder=\"" + placeholder + "\" maxlength=\"" + maxlength + "\" id=\"" + id + "\" type=\"" + type + "\" \> " + unit;
                                        new_element +=                  "<div class=\"dashicons dashicons-no\"  style=\"font-size: 30px; cursor: pointer; margin-left: 20px;\"></div>";
                                        new_element +=          "</td>";
                                        new_element +=  "</tr>";
                                        
                                        $this.parent().parent().before( new_element );
                                        tf_repeatable_text_init();
                                        tf_repeatable_text_renumber();
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
                $field_name = $this->settings['field_label'];
                
		$this->echoOptionHeader();
                $this->openFormTable();
                
                if (empty($value)){
                        $value = array(
                            ''
                        );
                }
                        
                foreach ($value as $index => $val):

                        $meta_key = $this->getID() . "[$index]";

                        ?>
                        <tr valign="top" tf-repeatable-text-id="<?php echo $ID; ?>">
                                <th scope="row">
                                        <label for="<?php echo $meta_key;?>"><?php echo $field_name . ' ' . ($index + 1);?></label>
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
                                        <div class="dashicons dashicons-no" style="font-size: 30px; cursor: pointer; margin-left: 20px;"></div>
                                </td>
                        </tr>
                        <?php

                endforeach;
                        
                ?>
                
                <tr valign="top">
                        <td>
                                <a 
                                        class="button tf-repeatable-text-button"  
                                        tf-repeatable-text-id="<?php echo $ID; ?>" 
                                        tf-repeatable-text-index="<?php echo $index; ?>" 
                                        tf-repeatable-text-placeholder="<?php echo $this->settings['placeholder']; ?>" 
                                        tf-repeatable-text-type="<?php echo $this->settings['is_password'] ? 'password' : 'text' ?>" 
                                        tf-repeatable-text-maxlength="<?php echo $this->settings['maxlength']; ?>" 
                                        tf-repeatable-text-unit="<?php echo $this->settings['unit']; ?>"
                                >
                                        Add Text Field
                                </a>
                        </td>
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