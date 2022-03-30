<?php
/*
Plugin Name: Renape Share Links
Plugin URI: https://github.com/oktayefendi/renape-share-links
Description: With this plugin, you can collect your social media links or other links that you have shortened on a single page.
Version: 0.1
Author: Oktay Afandi
Author URI: https://github.com/oktayefendi
License: GPLv2 or later
Text Domain: renape
*/

/* Filter the single_template with our custom function*/


add_filter('single_template', 'renape_template_1');

function renape_template_1($single) {

    global $post;

  

    /* Checks for single template by post type */
    if ( $post->post_type == 'link' ) {
        if ( file_exists( WP_PLUGIN_DIR . '/renape-share-links/single-link.php' ) ) {
            return WP_PLUGIN_DIR . '/renape-share-links/single-link.php';
        }
    }

    return $single;

}

/* 

METABOX REPEATER

*/

// Add Meta Box to post

add_action('admin_init', 'single_rapater_meta_boxes', 2);

function single_rapater_meta_boxes() {
	add_meta_box( 'single-repeter-data', 'Links', 'single_repeatable_meta_box_callback', 'link', 'normal', 'default');
}

function single_repeatable_meta_box_callback($post) {

	$single_repeter_group = get_post_meta($post->ID, 'single_repeter_group', true);
	$banner_img = get_post_meta($post->ID,'post_banner_img',true);
	wp_nonce_field( 'repeterBox', 'formType' );
	?>
	<script type="text/javascript">
		jQuery(document).ready(function( $ ){
			$( '#add-row' ).on('click', function() {
				var row = $( '.empty-row.custom-repeter-text' ).clone(true);
				row.removeClass( 'empty-row custom-repeter-text' ).css('display','table-row');
				row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
				return false;
			});

			$( '.remove-row' ).on('click', function() {
				$(this).parents('tr').remove();
				return false;
			});
		});

	</script>

	<table id="repeatable-fieldset-one" width="100%">
		<tbody>
			<?php
			if ( $single_repeter_group ) :
				foreach ( $single_repeter_group as $field ) {
					?>
					<tr>
						<td><input type="text"  style="width:98%;" name="title[]" value="<?php if($field['title'] != '') echo esc_attr( $field['title'] ); ?>" placeholder="Title" /></td>
						<td><input type="text"  style="width:98%;" name="tdesc[]" value="<?php if ($field['tdesc'] != '') echo esc_attr( $field['tdesc'] ); ?>" placeholder="URL" /></td>
						<td><a class="button remove-row" href="#1"><?php _e( 'Remove', 'renape' ); ?></a></td>
					</tr>
					<?php
				}
			else :
				?>
				<tr>
					<td><input type="text"   style="width:98%;" name="title[]" placeholder="Title"/></td>
					<td><input type="text"  style="width:98%;" name="tdesc[]" value="" placeholder="URL" /></td>
					<td><a class="button  cmb-remove-row-button button-disabled" href="#"><?php _e( 'Remove', 'renape' ); ?></a></td>
				</tr>
			<?php endif; ?>
			<tr class="empty-row custom-repeter-text" style="display: none">
				<td><input type="text" style="width:98%;" name="title[]" placeholder="Title"/></td>
				<td><input type="text" style="width:98%;" name="tdesc[]" value="" placeholder="URL"/></td>
				<td><a class="button remove-row" href="#"><?php _e( 'Remove', 'renape' ); ?></a></td>
			</tr>
			
		</tbody>
	</table>
	<p><a id="add-row" class="button" href="#"><?php _e( 'Add Another', 'renape' ); ?></a></p>
	<?php
}

// Save Meta Box values.
add_action('save_post', 'single_repeatable_meta_box_save');

function single_repeatable_meta_box_save($post_id) {

	if (!isset($_POST['formType']) && !wp_verify_nonce($_POST['formType'], 'repeterBox'))
		return;

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	if (!current_user_can('edit_post', $post_id))
		return;

	$old = get_post_meta($post_id, 'single_repeter_group', true);

	$new = array();
	$titles = $_POST['title'];
	$tdescs = $_POST['tdesc'];
	$count = count( $titles );
	for ( $i = 0; $i < $count; $i++ ) {
		if ( $titles[$i] != '' ) {
			$new[$i]['title'] = stripslashes( strip_tags( $titles[$i] ) );
			$new[$i]['tdesc'] = stripslashes( $tdescs[$i] );
		}
	}

	if ( !empty( $new ) && $new != $old ){
		update_post_meta( $post_id, 'single_repeter_group', $new );
	} elseif ( empty($new) && $old ) {
		delete_post_meta( $post_id, 'single_repeter_group', $old );
	}
	$repeter_status= $_REQUEST['repeter_status'];
	update_post_meta( $post_id, 'repeter_status', $repeter_status );
}



// Meta Box Class: DescriptionMetaBox
// Get the field value: $metavalue = get_post_meta( $post_id, $field_id, true );
class DescriptionMetaBox{

	private $screen = array(
		'link'
                        
	);

	private $meta_fields = array(
                array(
                    'label' => 'Description',
                    'id' => 'description',
                    'type' => 'textarea',
                ),
    
                array(
                    'label' => 'Background Color',
                    'id' => 'background_color',
                    'default' => '#76967d',
                    'type' => 'color',
                )

	);

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_fields' ) );
	}

	public function add_meta_boxes() {
		foreach ( $this->screen as $single_screen ) {
			add_meta_box(
				'Description',
				__( 'Description', 'textdomain' ),
				array( $this, 'meta_box_callback' ),
				$single_screen,
				'normal',
				'default'
			);
		}
	}

	public function meta_box_callback( $post ) {
		wp_nonce_field( 'Description_data', 'Description_nonce' );
		$this->field_generator( $post );
	}
	public function field_generator( $post ) {
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			$meta_value = get_post_meta( $post->ID, $meta_field['id'], true );
			if ( empty( $meta_value ) ) {
				if ( isset( $meta_field['default'] ) ) {
					$meta_value = $meta_field['default'];
				}
			}
			switch ( $meta_field['type'] ) {
                                case 'textarea':
                                    $input = sprintf(
                                        '<textarea style="" id="%s" name="%s" rows="5">%s</textarea>',
                                        $meta_field['id'],
                                        $meta_field['id'],
                                        $meta_value
                                    );
                                    break;
            
				default:
                                    $input = sprintf(
                                        '<input %s id="%s" name="%s" type="%s" value="%s">',
                                        $meta_field['type'] !== 'color' ? 'style="width: 100%"' : '',
                                        $meta_field['id'],
                                        $meta_field['id'],
                                        $meta_field['type'],
                                        $meta_value
                                    );
			}
			$output .= $this->format_rows( $label, $input );
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}

	public function format_rows( $label, $input ) {
		return '<tr><th>'.$label.'</th><td>'.$input.'</td></tr>';
	}

	public function save_fields( $post_id ) {
		if ( ! isset( $_POST['Description_nonce'] ) )
			return $post_id;
		$nonce = $_POST['Description_nonce'];
		if ( !wp_verify_nonce( $nonce, 'Description_data' ) )
			return $post_id;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;
		foreach ( $this->meta_fields as $meta_field ) {
			if ( isset( $_POST[ $meta_field['id'] ] ) ) {
				switch ( $meta_field['type'] ) {
					case 'email':
						$_POST[ $meta_field['id'] ] = sanitize_email( $_POST[ $meta_field['id'] ] );
						break;
					case 'text':
						$_POST[ $meta_field['id'] ] = sanitize_text_field( $_POST[ $meta_field['id'] ] );
						break;
				}
				update_post_meta( $post_id, $meta_field['id'], $_POST[ $meta_field['id'] ] );
			} else if ( $meta_field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, $meta_field['id'], '0' );
			}
		}
	}
}

if (class_exists('DescriptionMetabox')) {
	new DescriptionMetabox;
};


function cptui_register_my_cpts_link() {

	/**
	 * Post Type: Link.
	 */

	$labels = [
		"name" => __( "Link", "renape" ),
		"singular_name" => __( "Links", "renape" ),
		"menu_name" => __( "Renape", "renape" ),
	];

	$args = [
		"label" => __( "Link", "renape" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "link", "with_front" => true ],
		"query_var" => true,
		"menu_icon" => "dashicons-admin-links",
		"supports" => [ "title", "thumbnail" ],
		"show_in_graphql" => false,
	];

	register_post_type( "link", $args );
}

add_action( 'init', 'cptui_register_my_cpts_link' );
