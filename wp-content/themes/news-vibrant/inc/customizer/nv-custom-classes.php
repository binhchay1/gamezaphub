<?php
/**
 * Define customizer custom classes
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

if ( class_exists( 'WP_Customize_Control' ) ) 
{
/*--------------------------------------------------------------------------------------------------------------*/
    /**
     * Toggle control (modified checkbox).
     *
     * @since 1.5.0
     */
    class News_Vibrant_Customize_Toggle_Control extends WP_Customize_Control {
        
        /**
         * The control type.
         *
         * @access public
         * @var string
         * @since 1.0.0
         */
        public $type = 'nv-toggle';
        
        /**
         * Refresh the parameters passed to the JavaScript via JSON.
         *
         * @see WP_Customize_Control::to_json()
         * @since 1.0.0
         */
        public function to_json() {
            parent::to_json();

            $this->json['value']   = $this->value();
            $this->json['link']    = $this->get_link();
            $this->json['id']      = $this->id;

        }

        /**
         * Don't render the content via PHP.  This control is handled with a JS template.
         *
         * @access public
         * @return void
         * @since  1.0.0
         */
        public function render_content() {}
        
        /**
         * An Underscore (JS) template for this control's content (but not its container).
         *
         * Class variables for this control class are available in the `data` JS object;
         * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
         *
         * @see WP_Customize_Control::print_template()
         *
         * @access protected
         * @since 1.0.0
         */
        protected function content_template() {
    ?>
            <div class="nv-toggle">
                <div class="toggle--wrapper">
                    <# if ( data.label ) { #>
                        <span class="customize-control-title">{{ data.label }}</span>
                    <# } #>

                    <input id="toggle-{{ data.id }}" type="checkbox" class="toggle--input" value="{{ data.value }}" {{{ data.link }}} <# if ( data.value ) { #> checked="checked" <# } #> />
                    <label for="toggle-{{ data.id }}" class="toggle--label"></label>
                </div><!-- .toggle--wrapper -->

                <# if ( data.description ) { #>
                    <span class="description customize-control-description">{{ data.description }}</span>
                <# } #>
            </div><!-- .nv-toggle -->
    <?php
        }
    }
/*--------------------------------------------------------------------------------------------------------------*/
    /**
     * Customize for multiple checkboxes
     */
    class News_Vibrant_Customize_Multiple_Checkboxes_Control extends WP_Customize_Control {
        /**
         * The type of customize control being rendered.
         *
         * @since  1.0.0
         * @access public
         * @var    string
         */
        public $type = 'checkbox-multiple';
        /**
         * Displays the control content.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function render_content() {

            if ( empty( $this->choices ) ) {
                return;
            }            
    ?>
            <?php if ( !empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif; ?>

            <?php if ( !empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
            <?php endif; ?>

            <?php $multi_values = !is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value(); ?>

            <ul>
                <?php foreach ( $this->choices as $value => $label ) : ?>

                    <li>
                        <label>
                            <input type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $multi_values ) ); ?> /> 
                            <?php echo esc_html( $label ); ?>
                        </label>
                    </li>

                <?php endforeach; ?>
            </ul>

            <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $multi_values ) ); ?>" />
    <?php
        }
    }// end News_Vibrant_Customize_Multiple_Checkboxes_Control

/*-----------------------------------------------------------------------------------------------------------------------*/
    /**
     * Radio image customize control.
     *
     * @since  1.0.0
     * @access public
     */
    class News_Vibrant_Customize_Control_Radio_Image extends WP_Customize_Control {
        /**
         * The type of customize control being rendered.
         *
         * @since  1.0.0
         * @access public
         * @var    string
         */
        public $type = 'radio-image';

        /**
         * Loads the jQuery UI Button script and custom scripts/styles.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function enqueue() {
            wp_enqueue_script( 'jquery-ui-button' );
        }

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();

            // We need to make sure we have the correct image URL.
            foreach ( $this->choices as $value => $args )
                $this->choices[ $value ]['url'] = esc_url( sprintf( $args['url'], get_template_directory_uri(), get_stylesheet_directory_uri() ) );

            $this->json['choices'] = $this->choices;
            $this->json['link']    = $this->get_link();
            $this->json['value']   = $this->value();
            $this->json['id']      = $this->id;
        }


        /**
         * Underscore JS template to handle the control's output.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */

        public function content_template() { ?>
            <# if ( data.label ) { #>
                <span class="customize-control-title">{{ data.label }}</span>
            <# } #>

            <# if ( data.description ) { #>
                <span class="description customize-control-description">{{{ data.description }}}</span>
            <# } #>

            <div class="buttonset">

                <# for ( key in data.choices ) { #>

                    <input type="radio" value="{{ key }}" name="_customize-{{ data.type }}-{{ data.id }}" id="{{ data.id }}-{{ key }}" {{{ data.link }}} <# if ( key === data.value ) { #> checked="checked" <# } #> /> 

                    <label for="{{ data.id }}-{{ key }}">
                        <span class="screen-reader-text">{{ data.choices[ key ]['label'] }}</span>
                        <img src="{{ data.choices[ key ]['url'] }}" title="{{ data.choices[ key ]['label'] }}" alt="{{ data.choices[ key ]['label'] }}" />
                    </label>
                <# } #>

            </div><!-- .buttonset -->
        <?php }
    } // end News_Vibrant_Customize_Control_Radio_Image
/*-----------------------------------------------------------------------------------------------------------------------*/
    /**
     * Customize controls for repeater field
     *
     * @since 1.0.0
     */
    class News_Vibrant_Repeater_Controler extends WP_Customize_Control {
        /**
         * The control type.
         *
         * @access public
         * @var string
         */
        public $type = 'repeater';

        public $news_vibrant_box_label = '';

        public $news_vibrant_box_add_control = '';

        /**
         * The fields that each container row will contain.
         *
         * @access public
         * @var array
         */
        public $fields = array();

        /**
         * Repeater drag and drop controller
         *
         * @since  1.0.0
         */
        public function __construct( $manager, $id, $args = array(), $fields = array() ) {
            $this->fields = $fields;
            $this->news_vibrant_box_label = $args['news_vibrant_box_label'] ;
            $this->news_vibrant_box_add_control = $args['news_vibrant_box_add_control'];
            parent::__construct( $manager, $id, $args );
        }

        public function render_content() {

            $values = json_decode( $this->value() );
            $repeater_id = $this->id;
            $field_count = count( $values );
        ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

            <?php if ( $this->description ) { ?>
                <span class="description customize-control-description">
                    <?php echo wp_kses_post( $this->description ); ?>
                </span>
            <?php } ?>

            <ul class="nv-repeater-field-control-wrap">
                <?php $this->news_vibrant_get_fields(); ?>
            </ul>

            <input type="hidden" <?php esc_attr( $this->link() ); ?> class="nv-repeater-collector" value="<?php echo esc_attr( $this->value() ); ?>" />
            <input type="hidden" name="<?php echo esc_attr( $repeater_id ).'_count'; ?>" class="field-count" value="<?php echo absint( $field_count ); ?>">
            <input type="hidden" name="field_limit" class="field-limit" value="5">
            <button type="button" class="button nv-repeater-add-control-field"><?php echo esc_html( $this->news_vibrant_box_add_control ); ?></button>
    <?php
        }

        private function news_vibrant_get_fields() {
            $fields = $this->fields;
            $values = json_decode( $this->value() );

            if ( is_array( $values ) ) {
            foreach ( $values as $value ) {
        ?>
            <li class="nv-repeater-field-control">
            <h3 class="nv-repeater-field-title"><?php echo esc_html( $this->news_vibrant_box_label ); ?></h3>
            
            <div class="nv-repeater-fields">
            <?php
                foreach ( $fields as $key => $field ) {
                $class = isset( $field['class'] ) ? $field['class'] : '';
            ?>
                <div class="nv-repeater-field nv-repeater-type-<?php echo esc_attr( $field['type'] ).' '. esc_attr( $class ); ?>">

                <?php 
                    $label = isset( $field['label'] ) ? $field['label'] : '';
                    $description = isset( $field['description'] ) ? $field['description'] : '';
                    if ( $field['type'] != 'checkbox' ) { 
                ?>
                        <span class="customize-control-title"><?php echo esc_html( $label ); ?></span>
                        <span class="description customize-control-description"><?php echo esc_html( $description ); ?></span>
                <?php 
                    }

                    $new_value = isset( $value->$key ) ? $value->$key : '';
                    $default = isset( $field['default'] ) ? $field['default'] : '';

                    switch ( $field['type'] ) {
                        case 'text':
                            echo '<input data-default="'.esc_attr( $default ).'" data-name="'.esc_attr( $key ).'" type="text" value="'.esc_attr( $new_value ).'"/>';
                            break;

                        case 'url':
                            echo '<input data-default="'.esc_attr( $default ).'" data-name="'.esc_attr( $key ).'" type="text" value="'.esc_url( $new_value ).'"/>';
                            break;

                        case 'social_icon':
                            echo '<div class="nv-repeater-selected-icon"><i class="'.esc_attr( $new_value ).'"></i><span><i class="fa fa-angle-down"></i></span></div><ul class="nv-repeater-icon-list nv-clearfix">';
                            $news_vibrant_font_awesome_social_icon_array = news_vibrant_font_awesome_social_icon_array();
                            foreach ( $news_vibrant_font_awesome_social_icon_array as $news_vibrant_font_awesome_icon ) {
                                $icon_class = $new_value == $news_vibrant_font_awesome_icon ? 'icon-active' : '';
                                echo '<li class='. esc_attr( $icon_class ) .'><i class="'. esc_attr( $news_vibrant_font_awesome_icon ) .'"></i></li>';
                            }
                            echo '</ul><input data-default="'. esc_attr( $default ) .'" type="hidden" value="'. esc_attr( $new_value ) .'" data-name="'.esc_attr( $key ).'"/>';
                            break;

                        default:
                            break;
                    }
                ?>
                </div>
                <?php
                } ?>

                <div class="nv-clearfix nv-repeater-footer">
                    <div class="alignright">
                    <a class="nv-repeater-field-remove" href="#remove"><?php esc_html_e( 'Delete', 'news-vibrant' ) ?></a> |
                    <a class="nv-repeater-field-close" href="#close"><?php esc_html_e( 'Close', 'news-vibrant' ) ?></a>
                    </div>
                </div>
            </div>
            </li>
            <?php   
            }
            }
        }
    } // end News_Vibrant_Repeater_Controler
/*-----------------------------------------------------------------------------------------------------------------------*/
    
    /**
     * Upsell customizer section.
     *
     * @since  1.0.6
     * @access public
     */
    class News_Vibrant_Customize_Section_Upsell extends WP_Customize_Section {

        /**
         * The type of customize section being rendered.
         *
         * @since  1.0.0
         * @access public
         * @var    string
         */
        public $type = 'upsell';

        /**
         * Custom button text to output.
         *
         * @since  1.0.0
         * @access public
         * @var    string
         */
        public $pro_text = '';

        /**
         * Custom pro button URL.
         *
         * @since  1.0.0
         * @access public
         * @var    string
         */
        public $pro_url = '';

        /**
         * Add custom parameters to pass to the JS via JSON.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function json() {
            $json = parent::json();

            $json['pro_text'] = $this->pro_text;
            $json['pro_url']  = esc_url( $this->pro_url );

            return $json;
        }

        /**
         * Outputs the Underscore.js template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        protected function render_template() { ?>

            <li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">
                <h3 class="accordion-section-title">
                    {{ data.title }}

                    <# if ( data.pro_text && data.pro_url ) { #>
                        <a href="{{ data.pro_url }}" class="button button-secondary alignright" target="_blank">{{ data.pro_text }}</a>
                    <# } #>
                </h3>
            </li>
        <?php }
    }// end News_Vibrant_Customize_Section_Upsell

} //end WP_Customize_Control