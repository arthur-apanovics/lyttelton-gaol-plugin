<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace lyttelton_gaol;

use League\Csv\Reader;

require __DIR__ . '/csv/autoload.php';

class gaol_importer{
	public function __construct()
	{
		add_action('admin_head', array($this, 'do_the_import'));
	}

	public function do_the_import(){
		//load the CSV document from a stream
		$stream = fopen(__DIR__ . '/convicts.csv', 'r');
		$csv = Reader::createFromStream($stream);
		$csv->setDelimiter(',');
		$csv->setHeaderOffset(0);

//build a statement (filter)
		$stmt = (new \League\Csv\Statement())
			->offset(0)
			->limit(-1);

//query your records from the document
		$all_convicts = $stmt->process($csv);
		$grouped_convicts = [];

		foreach ($all_convicts as $convict) {
			// trim whitespace
			foreach ($convict as $key => $value){$convict[$key] = trim($value);}

			$name_key = $convict['Surname'] . ' '
				. $convict['Given Name(s)']
				. ' (' . $convict['Birth year'] . ')';

			if (!array_key_exists($name_key, $grouped_convicts)){
				$grouped_convicts[$name_key]['bio_name']                 = $convict['Given Name(s)'];
				$grouped_convicts[$name_key]['bio_surname']              = $convict['Surname'];
				$grouped_convicts[$name_key]['bio_christian_name']       = $convict['Christian Name'];
				$grouped_convicts[$name_key]['bio_middle_name']          = $convict['Middle Name'];
				$grouped_convicts[$name_key]['bio_alias']                = $convict['Alias'];
				$grouped_convicts[$name_key]['bio_born']                 = $convict['Birth year'];
				$grouped_convicts[$name_key]['bio_country_of_birth']     = $convict['Country of birth'];
				$grouped_convicts[$name_key]['bio_native_of']            = $convict['Native of'];
				$grouped_convicts[$name_key]['bio_trade']                = $convict['Occupation'];
				$grouped_convicts[$name_key]['bio_complexion']           = $convict['Complexion'];
				$grouped_convicts[$name_key]['bio_height']               = $convict['Height'];
				$grouped_convicts[$name_key]['bio_hair']                 = $convict['Hair'];
				$grouped_convicts[$name_key]['bio_eyes']                 = $convict['Eyes'];
				$grouped_convicts[$name_key]['bio_nose']                 = $convict['Nose'];
				$grouped_convicts[$name_key]['bio_chin']                 = $convict['Chin'];
				$grouped_convicts[$name_key]['bio_mouth']                = $convict['Mouth'];
				$grouped_convicts[$name_key]['bio_photographed']         = $convict['Photographed'];
				$grouped_convicts[$name_key]['bio_previous_convictions'] = $convict['Previous convictions'];
				$grouped_convicts[$name_key]['bio_remarks']              = $convict['Other information'];
			}

			$grouped_convicts[$name_key]['convictions'][] = [
				'offence'                  => $convict['Offence'],
				'sentence'                 => $convict['Sentences'],
				'date_tried'               => $convict['Date tried'],
				'discharged'               => $convict['Date discharged'],
				'gazette_source'           => $convict['Source'],
				'gazette_publication_year' => $convict['Publication year'],
				'gazette_volume'           => $convict['Volume'],
				'gazette_page'             => $convict['page'],
			];
		}

		echo 'convicts grouped - '.count($grouped_convicts).' records...<br>';

		$created = 0;
		foreach ($grouped_convicts as $key => $value){
			if (post_exists($key)){
				continue;
			}

			$post_arr = array(
				'post_title'   => $key,
//				'post_content' => '',
				'post_status'  => 'publish',
				'post_author'  => 0,
				'post_type'    => 'convict',
				'meta_input'   => $value,
			);

			$post_id = wp_insert_post($post_arr, true);
			$created++;
			echo "created post $post_id - $key<br>";
		}
		echo "done. imported $created/" . count($grouped_convicts) . ' convicts' ;
	}
}
