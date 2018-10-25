<?php

namespace lyttelton_gaol\fields;

use ReflectionClass;

trait field_methods
{
	public function getConstants()
	{
		$reflectionClass = new ReflectionClass($this);
		return $reflectionClass->getConstants();
	}
}

class bio
{
	use field_methods;

	public const NAME                 = ['id' => 'bio_name',                 'desc' => 'First name',           'type' => 'text'];
	public const SURNAME              = ['id' => 'bio_surname',              'desc' => 'Last name',            'type' => 'text'];
	public const CHRISTIAN_NAME       = ['id' => 'bio_christian_name',       'desc' => 'Christian Name',       'type' => 'text'];
	public const MIDDLE_NAME          = ['id' => 'bio_middle_name',          'desc' => 'Middle Name',          'type' => 'text'];
	public const ALIAS                = ['id' => 'bio_alias',                'desc' => 'Alias',                'type' => 'text'];
	public const BORN                 = ['id' => 'bio_born',                 'desc' => 'Born',                 'type' => 'number'];
	public const COUNTRY_OF_BIRTH     = ['id' => 'bio_country_of_birth',     'desc' => 'Country of Birth',     'type' => 'text'];
	public const NATIVE_OF            = ['id' => 'bio_native_of',            'desc' => 'Native of',            'type' => 'text'];
	public const TRADE                = ['id' => 'bio_trade',                'desc' => 'Trade',                'type' => 'text'];
	public const COMPLEXION           = ['id' => 'bio_complexion',           'desc' => 'Complexion',           'type' => 'text'];
	public const HEIGHT               = ['id' => 'bio_height',               'desc' => 'Height',               'type' => 'text'];
	public const HAIR                 = ['id' => 'bio_hair',                 'desc' => 'Hair',                 'type' => 'text'];
	public const EYES                 = ['id' => 'bio_eyes',                 'desc' => 'Eyes',                 'type' => 'text'];
	public const NOSE                 = ['id' => 'bio_nose',                 'desc' => 'Nose',                 'type' => 'text'];
	public const CHIN                 = ['id' => 'bio_chin',                 'desc' => 'Chin',                 'type' => 'text'];
	public const MOUTH                = ['id' => 'bio_mouth',                'desc' => 'Mouth',                'type' => 'text'];
	public const GAOL                 = ['id' => 'bio_gaol',                 'desc' => 'Gaol',                 'type' => 'text'];
	public const PHOTOGRAPHED         = ['id' => 'bio_photographed',         'desc' => 'Photographed',         'type' => 'text'];
	public const PREVIOUS_CONVICTIONS = ['id' => 'bio_previous_convictions', 'desc' => 'Previous Convictions', 'type' => 'text'];
	public const REMARKS              = ['id' => 'bio_remarks',              'desc' => 'Remarks',              'type' => 'wysiwyg'];
}

class conviction
{
	use field_methods;

	public const OFFENCE     = ['id' => 'offence_crime',       'desc' => 'Offence',         'type' => 'text'];
	public const SENTENCE    = ['id' => 'offence_sentence',    'desc' => 'Sentence',        'type' => 'text'];
	public const WHERE_TRIED = ['id' => 'offence_where_tried', 'desc' => 'Where Tried',     'type' => 'text'];
	public const DATE_TRIED  = ['id' => 'offence_date_tried',  'desc' => 'Date Tried',      'type' => 'text']; // keeping type as text for now...
	public const DISCHARGED  = ['id' => 'offence_discharged',  'desc' => 'Date Discharged', 'type' => 'text']; // keeping type as text for now...
}

class gazette
{
	use field_methods;

	public const SOURCE           = ['id' => 'gazette_source',           'desc' => 'Source',           'type' => 'text'];
	public const PUBLICATION_YEAR = ['id' => 'gazette_publication_year', 'desc' => 'Publication Year', 'type' => 'number'];
	public const VOLUME           = ['id' => 'gazette_volume',           'desc' => 'Volume',           'type' => 'text'];
	public const PAGE             = ['id' => 'gazette_page',             'desc' => 'Page',             'type' => 'number'];
}