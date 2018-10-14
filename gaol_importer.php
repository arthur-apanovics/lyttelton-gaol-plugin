<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace lyttelton_gaol;

use http\Exception\BadQueryStringException;
use League\Csv\Reader, lyttelton_gaol\fields\bio, lyttelton_gaol\fields\conviction, lyttelton_gaol\fields\gazette;

require 'csv/autoload.php';

class gaol_importer{
	private $filename;

	public function __construct(string $filename)
	{
		if (!isset($filename))
			throw new BadQueryStringException("Convict Import Error: filename missing!");
		$this->filename = $filename;

		add_action('admin_head', array($this, 'do_the_import'));
	}

	public function do_the_import(){
		//load the CSV document from a stream
		$stream = fopen(__DIR__ . $this->filename, 'r');
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
				$grouped_convicts[$name_key][bio::NAME['id']]                 = $convict['Given Name(s)'];
				$grouped_convicts[$name_key][bio::SURNAME['id']]              = $convict['Surname'];
				$grouped_convicts[$name_key][bio::CHRISTIAN_NAME['id']]       = $convict['Christian Name'];
				$grouped_convicts[$name_key][bio::MIDDLE_NAME['id']]          = $convict['Middle Name'];
				$grouped_convicts[$name_key][bio::ALIAS['id']]                = $convict['Alias'];
				$grouped_convicts[$name_key][bio::BORN['id']]                 = $convict['Birth year'];
				$grouped_convicts[$name_key][bio::COUNTRY_OF_BIRTH['id']]     = $convict['Country of birth'];
				$grouped_convicts[$name_key][bio::NATIVE_OF['id']]            = $convict['Native of'];
				$grouped_convicts[$name_key][bio::TRADE['id']]                = $convict['Occupation'];
				$grouped_convicts[$name_key][bio::COMPLEXION['id']]           = $convict['Complexion'];
				$grouped_convicts[$name_key][bio::HEIGHT['id']]               = $convict['Height'];
				$grouped_convicts[$name_key][bio::HAIR['id']]                 = $convict['Hair'];
				$grouped_convicts[$name_key][bio::EYES['id']]                 = $convict['Eyes'];
				$grouped_convicts[$name_key][bio::NOSE['id']]                 = $convict['Nose'];
				$grouped_convicts[$name_key][bio::CHIN['id']]                 = $convict['Chin'];
				$grouped_convicts[$name_key][bio::MOUTH['id']]                = $convict['Mouth'];
				$grouped_convicts[$name_key][bio::PHOTOGRAPHED['id']]         = $convict['Photographed'];
				$grouped_convicts[$name_key][bio::PREVIOUS_CONVICTIONS['id']] = $convict['Previous convictions'];
				$grouped_convicts[$name_key][bio::REMARKS['id']]              = $convict['Other information'];
			}

			$grouped_convicts[$name_key]['convictions'][] = [
				// CONVICTION
				conviction::OFFENCE['id']       => $convict['Offence'],
				conviction::SENTENCE['id']      => $convict['Sentences'],
				conviction::DATE_TRIED['id']    => $convict['Date tried'],
				conviction::DISCHARGED['id']    => $convict['Date discharged'],
				// GAZETTE
				gazette::SOURCE['id']           => $convict['Source'],
				gazette::PUBLICATION_YEAR['id'] => $convict['Publication year'],
				gazette::VOLUME['id']           => $convict['Volume'],
				gazette::PAGE['id']             => $convict['page'],
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
