<?php /** @noinspection SpellCheckingInspection */

namespace lyttelton_gaol;
class gaol_metaboxes
{
	private $screen = array(
		'convict',
	);

	private $conviction_meta_fields = array(
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
			'type' => 'array'
		)
	);
	private $bio_meta_fields = array(
		array(
			'id' => 'bio_name',
			'type' => 'text',
			'label' =>  'Name',
			'desc' =>  'First name',
		),
		array(
			'id' => 'bio_surname',
			'type' => 'text',
			'label' =>  'Surname',
			'desc' =>  'Last name',
		),
		array(
			'id' => 'bio_christian_name',
			'type' => 'text',
			'label' =>  'Christian Name',
		),
		array(
			'id' => 'bio_middle_name',
			'type' => 'text',
			'label' =>  'Middle Name',
		),
		array(
			'id' => 'bio_alias',
			'type' => 'text',
			'label' =>  'Alias',
		),
		array(
			'id' => 'bio_born',
			'type' => 'date',
			'label' =>  'Born',
			'js_options' => array(
				'defaultDate' => '"1/1/1800"',
				'minDate' => '"1/1/1600"',
			),
			'inline' => true,
			'timestamp' => true,
		),
		array(
			'id' => 'bio_country_of_birth',
			'type' => 'text',
			'label' =>  'Country of Birth',
		),
		array(
			'id' => 'bio_native_of',
			'type' => 'text',
			'label' =>  'Native of',
		),
		array(
			'id' => 'bio_trade',
			'type' => 'text',
			'label' =>  'Trade',
		),
		array(
			'id' => 'bio_complexion',
			'type' => 'text',
			'label' =>  'Complexion',
		),
		array(
			'id' => 'bio_height',
			'type' => 'text',
			'label' =>  'Height',
		),
		array(
			'id' => 'bio_hair',
			'type' => 'text',
			'label' =>  'Hair',
		),
		array(
			'id' => 'bio_eyes',
			'type' => 'text',
			'label' =>  'Eyes',
		),
		array(
			'id' => 'bio_nose',
			'type' => 'text',
			'label' =>  'Nose',
		),
		array(
			'id' => 'bio_chin',
			'type' => 'text',
			'label' =>  'Chin',
		),
		array(
			'id' => 'bio_mouth',
			'type' => 'text',
			'label' =>  'Mouth',
		),
		array(
			'id' => 'bio_photographed',
			'type' => 'text',
			'label' =>  'Photographed',
		),
		array(
			'id' => 'bio_previous_convictions',
			'type' => 'text',
			'label' =>  'Previous Convictions',
		),
		array(
			'id' => 'bio_remarks',
			'type' => 'wysiwyg',
			'label' =>  'Remarks',
		),
	);

	public function __construct()
	{
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

		$c = 0;
		if ( count( $convictions ) > 0 ) {
			foreach( $convictions[0] as $conviction ) {
			    ?>
                <div class="conviction-entry">
                    <!--CONVICTION DETAILS-->
                    <h4>Conviction details</h4>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th>
                                <label for="convictions[<?php echo $c ?>][offence]">Offence</label>
                            </th>
                            <td>
                                <input style="width: 100%" id="conviction_offence" name="convictions[<?php echo $c ?>][offence]" type="text" value="<?php echo $conviction['offence'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="convictions[<?php echo $c ?>][sentence]">Sentence</label>
                            </th>
                            <td>
                                <input style="width: 100%" id="conviction_sentence" name="convictions[<?php echo $c ?>][sentence]" type="text" value="<?php echo $conviction['sentence'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="convictions[<?php echo $c ?>][date_tried]">Date Tried</label>
                            </th>
                            <td>
                                <input style="width: 100%" id="conviction_date_tried" name="convictions[<?php echo $c ?>][date_tried]" type="text" value="<?php echo $conviction['date_tried'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="convictions[<?php echo $c ?>][discharged]">Date Discharged</label>
                            </th>
                            <td>
                                <input style="width: 100%" id="conviction_discharged" name="convictions[<?php echo $c ?>][discharged]" type="text" value="<?php echo $conviction['discharged'] ?>">
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <!--GAZETTE DETAILS-->
                    <h4>Gazette entry details</h4>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th>
                                <label for="convictions[<?php echo $c ?>][gazette_source]">Source</label>
                            </th>
                            <td>
                                <input style="width: 100%" id="gazette_source" name="convictions[<?php echo $c ?>][gazette_source]" type="text" value="<?php echo $conviction['gazette_source'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="convictions[<?php echo $c ?>][gazette_publication_year]">Publication Year</label>
                            </th>
                            <td>
                                <input style="width: 100%" id="gazette_publication_year" name="convictions[<?php echo $c ?>][gazette_publication_year]" type="number" value="<?php echo $conviction['gazette_publication_year'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="convictions[<?php echo $c ?>][gazette_volume]">Volume</label>
                            </th>
                            <td>
                                <input style="width: 100%" id="gazette_volume" name="convictions[<?php echo $c ?>][gazette_volume]" type="text" value="<?php echo $conviction['gazette_volume'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="convictions[<?php echo $c ?>][gazette_page]">Page</label>
                            </th>
                            <td>
                                <input style="width: 100%" id="gazette_page" name="convictions[<?php echo $c ?>][gazette_page]" type="number" value="<?php echo $conviction['gazette_page'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td>
                                <button type="button" class="removeConviction button button-link-delete" style="float: right;">Remove conviction</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr>
                </div>
                <?php
			    $c++;
			}
		}
		?>
		<span id="conviction-area"></span>
		<button id="addConviction" class="button" style="flo">Add Conviction</button>

		<!-- LOGIC HERE -->
		<script>
            var $ =jQuery.noConflict();

            $(document).ready(function() {
                var count = <?php echo $c; ?>;
                $("#addConviction").click(function() {
                    count++;

                    var offence_html = '<div class="conviction-entry">' +
                        '<h4>Conviction details</h4>' +
                        '<table class="form-table">'+
                            '<tbody>'+
                                '<tr>'+
                                    '<th>'+
                                        '<label for="convictions['+count+'][offence]">Offence</label>'+
                                    '</th>'+
                                    '<td>'+
                                        '<input style="width: 100%" id="conviction_offence" name="convictions['+count+'][offence]" type="text" value="">'+
                                    '</td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<th>'+
                                        '<label for="convictions['+count+'][sentence]">Sentence</label>'+
                                    '</th>'+
                                    '<td>'+
                                        '<input style="width: 100%" id="conviction_sentence" name="convictions['+count+'][sentence]" type="text" value="">'+
                                    '</td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<th>'+
                                        '<label for="convictions['+count+'][date_tried]">Date Tried</label>'+
                                    '</th>'+
                                    '<td>'+
                                        '<input style="width: 100%" id="conviction_date_tried" name="convictions['+count+'][date_tried]" type="date" value="">'+
                                    '</td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<th>'+
                                        '<label for="convictions['+count+'][discharged]">Date Discharged</label>'+
                                    '</th>'+
                                    '<td>'+
                                        '<input style="width: 100%" id="conviction_discharged" name="convictions['+count+'][discharged]" type="date" value="">'+
                                    '</td>'+
                                '</tr>'+
                            '</tbody>'+
                        '</table>'+
                        '<h4>Gazette entry details</h4>' +
                        '<table class="form-table">' +
                            '<tbody>' +
                                '<tr>' +
                                    '<th>' +
                                        '<label for="convictions['+count+'][gazette_source]">Source</label>' +
                                    '</th>' +
                                    '<td>' +
                                        '<input style="width: 100%" id="gazette_source" name="convictions['+count+'][gazette_source]" type="text" value="">' +
                                    '</td>' +
                                '</tr>' +
                                '<tr>' +
                                    '<th>' +
                                        '<label for="convictions['+count+'][gazette_publication_year]">Publication Year</label>' +
                                    '</th>' +
                                    '<td>' +
                                        '<input style="width: 100%" id="gazette_publication_year" name="convictions['+count+'][gazette_publication_year]" type="number" value="">' +
                                    '</td>' +
                                '</tr>' +
                                '<tr>' +
                                    '<th>' +
                                        '<label for="convictions['+count+'][gazette_volume]">Volume</label>' +
                                    '</th>' +
                                    '<td>' +
                                        '<input style="width: 100%" id="gazette_volume" name="convictions['+count+'][gazette_volume]" type="text" value="">' +
                                    '</td>' +
                                '</tr>' +
                                '<tr>' +
                                    '<th>' +
                                        '<label for="convictions['+count+'][gazette_page]">Page</label>' +
                                    '</th>' +
                                    '<td>' +
                                        '<input style="width: 100%" id="gazette_page" name="convictions['+count+'][gazette_page]" type="number" value="">' +
                                    '</td>' +
                                '</tr>' +
                                '<tr>'+
                                    '<th></th>'+
                                    '<td>'+
                                        '<button type="button" class="removeConviction button button-link-delete" style="float: right;">Remove conviction</button>'+
                                    '</td>'+
                                '</tr>'+
                            '</tbody>' +
                        '</table>' +
                        '<hr>'+
                        '</div>';

                    $('#conviction-area').append(offence_html);

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