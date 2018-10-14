<?php /** @noinspection SpellCheckingInspection */

namespace lyttelton_gaol;

use lyttelton_gaol\fields;
use lyttelton_gaol\fields\bio;

require 'gaol_fields.php'; // still need to require even when 'use' specified???

class gaol_metaboxes
{
	private $screen = array(
		'convict',
	);

	private $conviction_meta_fields;
	private $bio_meta_fields;

	public function __construct()
	{
		$this->conviction_meta_fields = array(
			array(
				// fields generated in code:
				// offence
				// sentence
				// date_tried
				// discharged
				// gazette_source
				// gazette_publication_year
				// gazette_volume
				// gazette_page
				'id'   => 'convictions',
				'type' => 'array',
			),
		);

        $this->bio_meta_fields = array(
	        array(
		        'id' => bio::NAME['id'],
		        'type' => bio::NAME['type'],
		        'label' =>  bio::NAME['desc'],
	        ),
	        array(
		        'id' => bio::SURNAME['id'],
		        'type' => bio::SURNAME['type'],
		        'label' =>  bio::SURNAME['desc'],
	        ),
	        array(
		        'id' => bio::CHRISTIAN_NAME['id'],
		        'type' => bio::CHRISTIAN_NAME['type'],
		        'label' =>  bio::CHRISTIAN_NAME['desc'],
	        ),
	        array(
		        'id' => bio::MIDDLE_NAME['id'],
		        'type' => bio::MIDDLE_NAME['type'],
		        'label' =>  bio::MIDDLE_NAME['desc'],
	        ),
	        array(
		        'id' => bio::ALIAS['id'],
		        'type' => bio::ALIAS['type'],
		        'label' =>  bio::ALIAS['desc'],
	        ),
	        array(
		        'id' => bio::BORN['id'],
		        'type' => bio::BORN['type'],
		        'label' =>  bio::BORN['desc'],
		        'js_options' => array(
			        'defaultDate' => '"1/1/1800"',
			        'minDate' => '"1/1/1600"',
		        ),
		        'inline' => true,
		        'timestamp' => true,
	        ),
	        array(
		        'id' => bio::COUNTRY_OF_BIRTH['id'],
		        'type' => bio::COUNTRY_OF_BIRTH['type'],
		        'label' =>  bio::COUNTRY_OF_BIRTH['desc'],
	        ),
	        array(
		        'id' => bio::NATIVE_OF['id'],
		        'type' => bio::NATIVE_OF['type'],
		        'label' =>  bio::NATIVE_OF['desc'],
	        ),
	        array(
		        'id' => bio::TRADE['id'],
		        'type' => bio::TRADE['type'],
		        'label' =>  bio::TRADE['desc'],
	        ),
	        array(
		        'id' => bio::COMPLEXION['id'],
		        'type' => bio::COMPLEXION['type'],
		        'label' =>  bio::COMPLEXION['desc'],
	        ),
	        array(
		        'id' => bio::HEIGHT['id'],
		        'type' => bio::HEIGHT['type'],
		        'label' =>  bio::HEIGHT['desc'],
	        ),
	        array(
		        'id' => bio::HAIR['id'],
		        'type' => bio::HAIR['type'],
		        'label' =>  bio::HAIR['desc'],
	        ),
	        array(
		        'id' => bio::EYES['id'],
		        'type' => bio::EYES['type'],
		        'label' =>  bio::EYES['desc'],
	        ),
	        array(
		        'id' => bio::NOSE['id'],
		        'type' => bio::NOSE['type'],
		        'label' =>  bio::NOSE['desc'],
	        ),
	        array(
		        'id' => bio::CHIN['id'],
		        'type' => bio::CHIN['type'],
		        'label' =>  bio::CHIN['desc'],
	        ),
	        array(
		        'id' => bio::MOUTH['id'],
		        'type' => bio::MOUTH['type'],
		        'label' =>  bio::MOUTH['desc'],
	        ),
	        array(
		        'id' => bio::PHOTOGRAPHED['id'],
		        'type' => bio::PHOTOGRAPHED['type'],
		        'label' =>  bio::PHOTOGRAPHED['desc'],
	        ),
	        array(
		        'id' => bio::PREVIOUS_CONVICTIONS['id'],
		        'type' => bio::PREVIOUS_CONVICTIONS['type'],
		        'label' =>  bio::PREVIOUS_CONVICTIONS['desc'],
	        ),
	        array(
		        'id' => bio::REMARKS['id'],
		        'type' => bio::REMARKS['type'],
		        'label' =>  bio::REMARKS['desc'],
	        ),
        );

		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
		add_action('save_post', array($this, 'save_fields'));
	}

	public function add_meta_boxes()
	{
		foreach ($this->screen as $single_screen) {
			//convictions
			add_meta_box(
				'convict_convictions',
				'Convictions',
				array($this, 'convict_convictions_callback'),
				$single_screen,
				'advanced',
				'default'
			);
			//bio
			add_meta_box(
				'convict_bio',
				'Convict Bio',
				array($this, 'convict_details_callback'),
				$single_screen,
				'advanced',
				'default'
			);
		}
	}

	function convict_convictions_callback() {
		global $post;
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'dynamicMeta_noncename' );
		?>

		<div id="meta_inner">

		<?php
		//get the saved meta as an array
		$convictions = get_post_meta($post->ID,'convictions',false);

		$count             = 0;
		$conviction_fields = new fields\conviction();
		$gazette_fields    = new fields\gazette();

		if ( count( $convictions ) > 0 ) {
			foreach( $convictions[0] as $conviction ) {
                ?>
                <div class="conviction-entry">
                    <!--CONVICTION DETAILS-->
		                <?php
                            $out = $this->conviction_table_start('Conviction details');
                            foreach ($conviction_fields->getConstants() as $field) {
                                $out .= $this->convictions_row($count, $field['id'], $field['desc'], $conviction[$field['id']], $field['type']);
                            }
                            $out .= $this->conviction_table_end();
                            echo $out;
		                ?>
                    <!--GAZETTE DETAILS-->
                        <?php
                            $out = $this->conviction_table_start('Gazette entry details');
                            foreach ($gazette_fields->getConstants() as $field) {
                                    $out .= $this->convictions_row($count, $field['id'], $field['desc'], $conviction[$field['id']], $field['type']);
                            }
                        $out .= $this->conviction_remove_entry_button();
                        $out .= $this->conviction_table_end();
                        echo $out;
                        ?>
                    <hr>
                </div>
                <?php
			    $count++;
			}
		}
		?>

		<span id="conviction-area"></span>
		<button id="addConviction" class="button">Add Conviction</button>

		<!-- DYNAMIC CONVICTION -->
		<script>
            var $ =jQuery.noConflict();

            $(document).ready(function() {
                var count = <?php echo $count; ?>;
                $("#addConviction").click(function() {
                    count++;

                    var delEntryRow = $('<tr>')
                        .append($('<th>'))
                        .append($('<td>')
                            .html($('<button>')
                                .addClass('removeConviction button button-link-delete')
                                .css('float', 'right')
                                .attr('type', 'button')
                                .html('Remove conviction')
                            )
                        );

                    var get_table = function (fields) {
                        var table = $('<table>').addClass('form-table');

                        $.each(fields, function (i, field) {
                            var row = $('<tr>')
                                .append($('<th>')
                                    .html($('<label>')
                                        .html(field.desc)
                                        .attr('for', 'convictions[' + count + '][' + field.id + ']')
                                    )
                                )
                                .append($('<td>')
                                    .html($('<input>')
                                        .css('width', '100%')
                                        .attr({
                                            id: field.id,
                                            name: 'convictions[' + count + '][' + field.id + ']',
                                            type: field.type,
                                            value: ''
                                        })
                                    )
                                );

                            table.append(row);
                        });
                        return table;
                    };

                    var convFields = <?php echo json_encode($conviction_fields->getConstants()) ?>;
                    var gazetteFields = <?php echo json_encode($gazette_fields->getConstants()) ?>;

                    var container = $('<div class="conviction-entry">');
                    var convTable = get_table(convFields);
                    var gazetteTable = get_table(gazetteFields);
                    // add 'delete entry' button
                    gazetteTable.append(delEntryRow);

                    $(container)
                        .append($('<h4>').html('Conviction details'))
                        .append(convTable)
                        .append($('<h4>').html('Gazette entry details'))
                        .append(gazetteTable);

                    $('#conviction-area').append(container);

                    return false;
                });

                $('.removeConviction').live('click', function() {
                    $(this).closest('.conviction-entry').remove();
                });
            });
		</script>
		</div>

        <?php
	}

	/**
	 * @param $count int
	 * @param $field_id string
	 * @param $field_description
	 * @param $field_value string
	 * @param $field_type string
	 * @return string table row
	 */
	private function convictions_row($count, $field_id, $field_description, $field_value, $field_type)
    {
	    return '<tr>
            <th>
                <label for="convictions[' . $count . '][' . $field_id . ']">' . $field_description . '</label>
            </th>
            <td>
                <input style="width: 100%" id="' . $field_id . '" name="convictions[' . $count . '][' . $field_id . ']"
                       type="' . $field_type . '" value="' . $field_value . '">
            </td>
        </tr>';
    }

    private function conviction_table_start($title){
        return '<h4>'. $title .'</h4>
                <table class="form-table">
                    <tbody>';
    }

    private function conviction_remove_entry_button(){
	    return '<tr>
                    <th></th>
                    <td>
                        <button type="button" class="removeConviction button button-link-delete" style="float: right;">Remove conviction</button>
                    </td>
                </tr>';
    }

    private function conviction_table_end(){
	    return '</tbody></table>';
    }

	public function convict_details_callback($post)
	{
		wp_nonce_field('lytteltongaol_data', 'lytteltongaol_nonce');
		echo 'Convict biographical details';
		$this->field_generator($post, $this->bio_meta_fields);
	}

	public function field_generator($post, $meta_fields)
	{
		$output = '';
		foreach ($meta_fields as $meta_field) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			$meta_value = get_post_meta($post->ID, $meta_field['id'], true);
			if (empty($meta_value)) {
				$meta_value = isset($meta_field['default']) ? $meta_field['default'] : '';
			}
			switch ($meta_field['type']) {
				case 'wysiwyg':
					ob_start();
					wp_editor($meta_value, $meta_field['id']);
					$input = ob_get_contents();
					ob_end_clean();
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
			$output .= $this->format_table_rows($label, $input);
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}

	public function format_table_rows($label, $input)
	{
		return '<tr><th>' . $label . '</th><td>' . $input . '</td></tr>';
	}

	public function save_fields($post_id)
	{
		$all_fields = array_merge($this->bio_meta_fields, $this->conviction_meta_fields);

		if (!isset($_POST['lytteltongaol_nonce']))
			return $post_id;
		$nonce = $_POST['lytteltongaol_nonce'];
		if (!wp_verify_nonce($nonce, 'lytteltongaol_data'))
			return $post_id;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;

		foreach ($all_fields as $meta_field) {
			if (isset($_POST[$meta_field['id']])) {
				switch ($meta_field['type']) {
					case 'text':
						$_POST[$meta_field['id']] = sanitize_text_field($_POST[$meta_field['id']]);
						break;
				}

				update_post_meta($post_id, $meta_field['id'], $_POST[$meta_field['id']]);
			} else if ($meta_field['type'] === 'checkbox') {
				update_post_meta($post_id, $meta_field['id'], '0');
			}
		}
	}
}