<?php
Class Page_Two
{

  private $opts;
  private $chip_in;

  public function __construct(Stripe_Donate_Fundraiser $master)
  {
    $this->country_opts = self::country_options(array("IE", "GB", "DE", "US", "CA"));
    $this->county_opts = self::counties_options();
  }

  function make()
  {
    return <<<HTML
  <form id="your-info" autocomplete="on">
    <div class="row">
      <div class="form-group col-sm-5">
        <label for="first-name">First name</label>
        <input type="text" class="form-control" required id="first-name" data-stripe="first-name" placeholder="First name">
      </div>
      <div class="form-group col-sm-7">
        <label for="last-name">Last name</label>
        <input type="text" class="form-control" required id="last-name" data-stripe="last-name" placeholder="Last name">
      </div>
    </div>
    <div class="form-group">
      <label for="email">Email address</label>
      <input type="email" class="form-control" required id="email" data-stripe="email" placeholder="Email">
    </div>
    <div class="form-group">
      <label for="country">Country</label>
      <select class="form-control" id="country" data-stripe="country">
        $this->country_opts
      </select>
    </div>
    <div class="form-group">
      <label for="county">County</label>
      <select class="form-control" id="county" data-stripe="county">
        $this->county_opts
      </select>
    </div>
    <div class="form-group">
      <button type="button" class="btn btn-default btn-sm btn-prev"><span class="icon-prev"></span> Back</button>
      <button type="button" class="btn btn-success btn-lg btn-next pull-right">Next <span class="icon-next"></span></button>
    </div>
  </form>
HTML;
  }

  private function country_options($start_with = [])
  {
    $countries = array(
      'AF' => 'Afghanistan',
      'AX' => 'Aland Islands',
      'AL' => 'Albania',
      'DZ' => 'Algeria',
      'AS' => 'American Samoa',
      'AD' => 'Andorra',
      'AO' => 'Angola',
      'AI' => 'Anguilla',
      'AQ' => 'Antarctica',
      'AG' => 'Antigua and Barbuda',
      'AR' => 'Argentina',
      'AM' => 'Armenia',
      'AW' => 'Aruba',
      'AU' => 'Australia',
      'AT' => 'Austria',
      'AZ' => 'Azerbaijan',
      'BS' => 'Bahamas',
      'BH' => 'Bahrain',
      'BD' => 'Bangladesh',
      'BB' => 'Barbados',
      'BY' => 'Belarus',
      'BE' => 'Belgium',
      'BZ' => 'Belize',
      'BJ' => 'Benin',
      'BM' => 'Bermuda',
      'BT' => 'Bhutan',
      'BO' => 'Bolivia',
      'BQ' => 'Bonaire, Sint Eustatius and Saba',
      'BA' => 'Bosnia and Herzegovina',
      'BW' => 'Botswana',
      'BV' => 'Bouvet Island',
      'BR' => 'Brazil',
      'IO' => 'British Indian Ocean Territory',
      'BN' => 'Brunei Darussalam',
      'BG' => 'Bulgaria',
      'BF' => 'Burkina Faso',
      'BI' => 'Burundi',
      'KH' => 'Cambodia',
      'CM' => 'Cameroon',
      'CA' => 'Canada',
      'CV' => 'Cape Verde',
      'KY' => 'Cayman Islands',
      'CF' => 'Central African Republic',
      'TD' => 'Chad',
      'CL' => 'Chile',
      'CN' => 'China',
      'CX' => 'Christmas Island',
      'CC' => 'Cocos (Keeling) Islands',
      'CO' => 'Colombia',
      'KM' => 'Comoros',
      'CG' => 'Congo',
      'CD' => 'Congo, The Democratic Republic of the',
      'CK' => 'Cook Islands',
      'CR' => 'Costa Rica',
      'CI' => 'Côte d\'Ivoire',
      'HR' => 'Croatia',
      'CU' => 'Cuba',
      'CW' => 'Curaçao',
      'CY' => 'Cyprus',
      'CZ' => 'Czech Republic',
      'DK' => 'Denmark',
      'DJ' => 'Djibouti',
      'DM' => 'Dominica',
      'DO' => 'Dominican Republic',
      'EC' => 'Ecuador',
      'EG' => 'Egypt',
      'SV' => 'El Salvador',
      'GB-E' => 'England',
      'GQ' => 'Equatorial Guinea',
      'ER' => 'Eritrea',
      'EE' => 'Estonia',
      'ET' => 'Ethiopia',
      'FK' => 'Falkland Islands (Malvinas)',
      'FO' => 'Faroe Islands',
      'FJ' => 'Fiji',
      'FI' => 'Finland',
      'FR' => 'France',
      'GF' => 'French Guiana',
      'PF' => 'French Polynesia',
      'TF' => 'French Southern Territories',
      'GA' => 'Gabon',
      'GM' => 'Gambia',
      'GE' => 'Georgia',
      'DE' => 'Germany',
      'GH' => 'Ghana',
      'GI' => 'Gibraltar',
      'GR' => 'Greece',
      'GL' => 'Greenland',
      'GD' => 'Grenada',
      'GP' => 'Guadeloupe',
      'GU' => 'Guam',
      'GT' => 'Guatemala',
      'GG' => 'Guernsey',
      'GN' => 'Guinea',
      'GW' => 'Guinea-Bissau',
      'GY' => 'Guyana',
      'HT' => 'Haiti',
      'HM' => 'Heard Island and Mcdonald Islands',
      'VA' => 'Holy See (Vatican City State)',
      'HN' => 'Honduras',
      'HK' => 'Hong Kong',
      'HU' => 'Hungary',
      'IS' => 'Iceland',
      'IN' => 'India',
      'ID' => 'Indonesia',
      'IR' => 'Iran',
      'IQ' => 'Iraq',
      'IE' => 'Ireland',
      'IM' => 'Isle Of Man',
      'IL' => 'Israel',
      'IT' => 'Italy',
      'JM' => 'Jamaica',
      'JP' => 'Japan',
      'JE' => 'Jersey',
      'JO' => 'Jordan',
      'KZ' => 'Kazakhstan',
      'KE' => 'Kenya',
      'KI' => 'Kiribati',
      'KP' => 'Democratic People\'s Republic of Korea',
      'KR' => 'Republic of Korea',
      'ZZ' => 'Kosovo',
      'KW' => 'Kuwait',
      'KG' => 'Kyrgyzstan',
      'LA' => 'Lao People\'s Democratic Republic',
      'LV' => 'Latvia',
      'LB' => 'Lebanon',
      'LS' => 'Lesotho',
      'LR' => 'Liberia',
      'LY' => 'Libya',
      'LI' => 'Liechtenstein',
      'LT' => 'Lithuania',
      'LU' => 'Luxembourg',
      'MO' => 'Macao',
      'MK' => 'Macedonia',
      'MG' => 'Madagascar',
      'MW' => 'Malawi',
      'MY' => 'Malaysia',
      'MV' => 'Maldives',
      'ML' => 'Mali',
      'MT' => 'Malta',
      'MH' => 'Marshall Islands',
      'MQ' => 'Martinique',
      'MR' => 'Mauritania',
      'MU' => 'Mauritius',
      'YT' => 'Mayotte',
      'MX' => 'Mexico',
      'FM' => 'Micronesia, Federated States of',
      'MD' => 'Moldova',
      'MC' => 'Monaco',
      'MN' => 'Mongolia',
      'ME' => 'Montenegro',
      'MS' => 'Montserrat',
      'MA' => 'Morocco',
      'MZ' => 'Mozambique',
      'MM' => 'Myanmar (Burma)',
      'NA' => 'Namibia',
      'NR' => 'Nauru',
      'NP' => 'Nepal',
      'NL' => 'Netherlands',
      'NC' => 'New Caledonia',
      'NZ' => 'New Zealand',
      'NI' => 'Nicaragua',
      'NE' => 'Niger',
      'NG' => 'Nigeria',
      'NU' => 'Niue',
      'NF' => 'Norfolk Island',
      'GB-NI' => 'Northern Ireland',
      'MP' => 'Northern Mariana Islands',
      'NO' => 'Norway',
      'OM' => 'Oman',
      'PK' => 'Pakistan',
      'PW' => 'Palau',
      'PS' => 'Palestine',
      'PA' => 'Panama',
      'PG' => 'Papua New Guinea',
      'PY' => 'Paraguay',
      'PE' => 'Peru',
      'PH' => 'Philippines',
      'PN' => 'Pitcairn',
      'PL' => 'Poland',
      'PT' => 'Portugal',
      'PR' => 'Puerto Rico',
      'QA' => 'Qatar',
      'RE' => 'Réunion',
      'RO' => 'Romania',
      'RU' => 'Russian Federation',
      'RW' => 'Rwanda',
      'BL' => 'Saint Barthélemy',
      'SH' => 'Saint Helena, Ascension and Tristan Da Cunha',
      'KN' => 'Saint Kitts and Nevis',
      'LC' => 'Saint Lucia',
      'MF' => 'Saint Martin (French Part)',
      'PM' => 'Saint Pierre and Miquelon',
      'VC' => 'Saint Vincent and the Grenadines',
      'WS' => 'Samoa',
      'SM' => 'San Marino',
      'ST' => 'Sao Tome and Principe',
      'SA' => 'Saudi Arabia',
      'GB-S' => 'Scotland',
      'SN' => 'Senegal',
      'RS' => 'Serbia',
      'SC' => 'Seychelles',
      'SL' => 'Sierra Leone',
      'SG' => 'Singapore',
      'SX' => 'Sint Maarten (Dutch Part)',
      'SK' => 'Slovakia',
      'SI' => 'Slovenia',
      'SB' => 'Solomon Islands',
      'SO' => 'Somalia',
      'ZA' => 'South Africa',
      'GS' => 'South Georgia and the South Sandwich Islands',
      'SS' => 'South Sudan',
      'ES' => 'Spain',
      'LK' => 'Sri Lanka',
      'SD' => 'Sudan',
      'SR' => 'Suriname',
      'SJ' => 'Svalbard and Jan Mayen',
      'SZ' => 'Swaziland',
      'SE' => 'Sweden',
      'CH' => 'Switzerland',
      'SY' => 'Syria',
      'TW' => 'Taiwan',
      'TJ' => 'Tajikistan',
      'TZ' => 'Tanzania',
      'TH' => 'Thailand',
      'TL' => 'Timor-Leste',
      'TG' => 'Togo',
      'TK' => 'Tokelau',
      'TO' => 'Tonga',
      'TT' => 'Trinidad and Tobago',
      'TN' => 'Tunisia',
      'TR' => 'Turkey',
      'TM' => 'Turkmenistan',
      'TC' => 'Turks And Caicos Islands',
      'TV' => 'Tuvalu',
      'UG' => 'Uganda',
      'UA' => 'Ukraine',
      'AE' => 'United Arab Emirates',
      'GB' => 'United Kingdom',
      'US' => 'United States',
      'UM' => 'United States Minor Outlying Islands',
      'UY' => 'Uruguay',
      'UZ' => 'Uzbekistan',
      'VU' => 'Vanuatu',
      'VE' => 'Venezuela',
      'VN' => 'Vietnam',
      'VG' => 'Virgin Islands, British',
      'VI' => 'Virgin Islands, U.S.',
      'GB-W' => 'Wales',
      'WF' => 'Wallis and Futuna',
      'EH' => 'Western Sahara',
      'YE' => 'Yemen',
      'ZM' => 'Zambia',
      'ZW' => 'Zimbabwe'
    );

    $html = '';
    $d = $start_with[0];
    foreach ($start_with as $key){
      $sel = $d == $key ? "selected=\"selected\"" : "";
      $html .= sprintf('<option value="%s" %s>%s</option>',
        $key,
        $sel,
        $countries[$key]
      );
    }
    $html .= '<option value="">-------</option>';
    foreach ($countries as $key => $val){
      $html .= sprintf('<option value="%s">%s</option>',
        $key,
        $val
      );
    }
    return $html;
  }
  private function counties_options(){
    $counties = array(
      '' => 'Select County',
      'Antrim' => 'Antrim',
      'Armagh'=> 'Armagh',
      'Carlow' => 'Carlow',
      'Cavan' => 'Cavan',
      'Clare' => 'Clare',
      'Cork' => 'Cork',
      'Derry' => 'Derry',
      'Donegal' => 'Donegal',
      'Down' => 'Down',
      'Dublin' => 'Dublin',
      'Fermanagh' =>'Fermanagh',
      'Galway' => 'Galway',
      'Kerry' => 'Kerry',
      'Kildare' => 'Kildare',
      'Kilkenny' => 'Kilkenny',
      'Laois' => 'Laois',
      'Leitrim' => 'Leitrim',
      'Limerick' => 'Limerick',
      'Longford' => 'Longford',
      'Louth' => 'Louth',
      'Mayo' => 'Mayo',
      'Meath' => 'Meath',
      'Monaghan' => 'Monaghan',
      'Offaly' => 'Offaly',
      'Roscommon' => 'Roscommon',
      'Sligo' => 'Sligo',
      'Tipperary' => 'Tipperary',
      'Tyrone' => 'Tyrone',
      'Waterford' => 'Waterford',
      'Westmeath' => 'Westmeath',
      'Wexford' => 'Wexford',
      'Wicklow' => 'Wicklow',
      'Outside Ireland' => 'Outside Ireland'
    );
    $html = "";
    foreach ($counties as $key => $val){
      $html .= sprintf('<option value="%s">%s</option>',
        $key,
        $val
      );
    }
    return $html;
  }

}