<?php
// Written by Mick Niepoth
// www.mickniepoth.com
//
// Published under MIT license


// error_reporting(E_ALL);
// ini_set('display_errors', '1');

$stripe_live_id = 'sk_live_XXX';
$numberofbatches = 20; // I recommend 20, to prevent time-outs

if($_SERVER['REQUEST_METHOD'] !== "POST") {
  echo "<style>* { font-family:Arial; text-align:center; padding:10px; }</style>What is the newest Transfer ID in Xero?<form method='post' action=''><input type='text' name='transfer_id' placeholder='Newest Transfer ID' /><input type='submit' /><br><br><br><small>Hint: You can find the Transfer ID under 'Reference' in the newest Xero 'bank statement line'.</small>";
  exit;
}

require_once('./lib/Stripe.php');

Stripe::setApiKey($stripe_live_id);

function convertcodes($in, $type){
  $out = "";
  $long = array('Afghanistan' , 'Åland Islands' , 'Albania' , 'Algeria' , 'American Samoa' , 'Andorra' , 'Angola' , 'Anguilla' , 'Antarctica' , 'Antigua and Barbuda' , 'Argentina' , 'Armenia' , 'Aruba' , 'Australia' , 'Austria' , 'Azerbaijan' , 'Bahamas' , 'Bahrain' , 'Bangladesh' , 'Barbados' , 'Belarus' , 'Belgium' , 'Belize' , 'Benin' , 'Bermuda' , 'Bhutan' , 'Bolivia - Plurinational State of' , 'Bonaire - Sint Eustatius and Saba' , 'Bosnia and Herzegovina' , 'Botswana' , 'Bouvet Island' , 'Brazil' , 'British Indian Ocean Territory' , 'Brunei Darussalam' , 'Bulgaria' , 'Burkina Faso' , 'Burundi' , 'Cambodia' , 'Cameroon' , 'Canada' , 'Cape Verde' , 'Cayman Islands' , 'Central African Republic' , 'Chad' , 'Chile' , 'China' , 'Christmas Island' , 'Cocos (Keeling) Islands' , 'Colombia' , 'Comoros' , 'Congo' , 'Congo - the Democratic Republic of the' , 'Cook Islands' , 'Costa Rica' , 'Côte d\'Ivoire' , 'Croatia' , 'Cuba' , 'Curaçao' , 'Cyprus' , 'Czech Republic' , 'Denmark' , 'Djibouti' , 'Dominica' , 'Dominican Republic' , 'Ecuador' , 'Egypt' , 'El Salvador' , 'Equatorial Guinea' , 'Eritrea' , 'Estonia' , 'Ethiopia' , 'Falkland Islands (Malvinas)' , 'Faroe Islands' , 'Fiji' , 'Finland' , 'France' , 'French Guiana' , 'French Polynesia' , 'French Southern Territories' , 'Gabon' , 'Gambia' , 'Georgia' , 'Germany' , 'Ghana' , 'Gibraltar' , 'Greece' , 'Greenland' , 'Grenada' , 'Guadeloupe' , 'Guam' , 'Guatemala' , 'Guernsey' , 'Guinea' , 'Guinea-Bissau' , 'Guyana' , 'Haiti' , 'Heard Island and McDonald Islands' , 'Holy See (Vatican City State)' , 'Honduras' , 'Hong Kong' , 'Hungary' , 'Iceland' , 'India' , 'Indonesia' , 'Iran - Islamic Republic of' , 'Iraq' , 'Ireland' , 'Isle of Man' , 'Israel' , 'Italy' , 'Jamaica' , 'Japan' , 'Jersey' , 'Jordan' , 'Kazakhstan' , 'Kenya' , 'Kiribati' , 'Korea - Democratic People\'s Republic of' , 'Korea - Republic of' , 'Kuwait' , 'Kyrgyzstan' , 'Lao People\'s Democratic Republic' , 'Latvia' , 'Lebanon' , 'Lesotho' , 'Liberia' , 'Libya' , 'Liechtenstein' , 'Lithuania' , 'Luxembourg' , 'Macao' , 'Macedonia - the former Yugoslav Republic of' , 'Madagascar' , 'Malawi' , 'Malaysia' , 'Maldives' , 'Mali' , 'Malta' , 'Marshall Islands' , 'Martinique' , 'Mauritania' , 'Mauritius' , 'Mayotte' , 'Mexico' , 'Micronesia - Federated States of' , 'Moldova - Republic of' , 'Monaco' , 'Mongolia' , 'Montenegro' , 'Montserrat' , 'Morocco' , 'Mozambique' , 'Myanmar' , 'Namibia' , 'Nauru' , 'Nepal' , 'Netherlands' , 'New Caledonia' , 'New Zealand' , 'Nicaragua' , 'Niger' , 'Nigeria' , 'Niue' , 'Norfolk Island' , 'Northern Mariana Islands' , 'Norway' , 'Oman' , 'Pakistan' , 'Palau' , 'Palestinian Territory - Occupied' , 'Panama' , 'Papua New Guinea' , 'Paraguay' , 'Peru' , 'Philippines' , 'Pitcairn' , 'Poland' , 'Portugal' , 'Puerto Rico' , 'Qatar' , 'Réunion' , 'Romania' , 'Russian Federation' , 'Rwanda' , 'Saint Barthélemy' , 'Saint Helena - Ascension and Tristan da Cunha' , 'Saint Kitts and Nevis' , 'Saint Lucia' , 'Saint Martin (French part)' , 'Saint Pierre and Miquelon' , 'Saint Vincent and the Grenadines' , 'Samoa' , 'San Marino' , 'Sao Tome and Principe' , 'Saudi Arabia' , 'Senegal' , 'Serbia' , 'Seychelles' , 'Sierra Leone' , 'Singapore' , 'Sint Maarten (Dutch part)' , 'Slovakia' , 'Slovenia' , 'Solomon Islands' , 'Somalia' , 'South Africa' , 'South Georgia and the South Sandwich Islands' , 'South Sudan' , 'Spain' , 'Sri Lanka' , 'Sudan' , 'Suriname' , 'Svalbard and Jan Mayen' , 'Swaziland' , 'Sweden' , 'Switzerland' , 'Syrian Arab Republic' , 'Taiwan - Province of China' , 'Tajikistan' , 'Tanzania - United Republic of' , 'Thailand' , 'Timor-Leste' , 'Togo' , 'Tokelau' , 'Tonga' , 'Trinidad and Tobago' , 'Tunisia' , 'Turkey' , 'Turkmenistan' , 'Turks and Caicos Islands' , 'Tuvalu' , 'Uganda' , 'Ukraine' , 'United Arab Emirates' , 'United Kingdom' , 'United States' , 'United States Minor Outlying Islands' , 'Uruguay' , 'Uzbekistan' , 'Vanuatu' , 'Venezuela - Bolivarian Republic of' , 'Viet Nam' , 'Virgin Islands - British' , 'Virgin Islands - U.S.' , 'Wallis and Futuna' , 'Western Sahara' , 'Yemen' , 'Zambia' , 'Zimbabwe');
  $short = array('AF','AX','AL','DZ','AS','AD','AO','AI','AQ','AG','AR','AM','AW','AU','AT','AZ','BS','BH','BD','BB','BY','BE','BZ','BJ','BM','BT','BO','BQ','BA','BW','BV','BR','IO','BN','BG','BF','BI','KH','CM','CA','CV','KY','CF','TD','CL','CN','CX','CC','CO','KM','CG','CD','CK','CR','CI','HR','CU','CW','CY','CZ','DK','DJ','DM','DO','EC','EG','SV','GQ','ER','EE','ET','FK','FO','FJ','FI','FR','GF','PF','TF','GA','GM','GE','DE','GH','GI','GR','GL','GD','GP','GU','GT','GG','GN','GW','GY','HT','HM','VA','HN','HK','HU','IS','IN','ID','IR','IQ','IE','IM','IL','IT','JM','JP','JE','JO','KZ','KE','KI','KP','KR','KW','KG','LA','LV','LB','LS','LR','LY','LI','LT','LU','MO','MK','MG','MW','MY','MV','ML','MT','MH','MQ','MR','MU','YT','MX','FM','MD','MC','MN','ME','MS','MA','MZ','MM','NA','NR','NP','NL','NC','NZ','NI','NE','NG','NU','NF','MP','NO','OM','PK','PW','PS','PA','PG','PY','PE','PH','PN','PL','PT','PR','QA','RE','RO','RU','RW','BL','SH','KN','LC','MF','PM','VC','WS','SM','ST','SA','SN','RS','SC','SL','SG','SX','SK','SI','SB','SO','ZA','GS','SS','ES','LK','SD','SR','SJ','SZ','SE','CH','SY','TW','TJ','TZ','TH','TL','TG','TK','TO','TT','TN','TR','TM','TC','TV','UG','UA','AE','GB','US','UM','UY','UZ','VU','VE','VN','VG','VI','WF','EH','YE','ZM','ZW');
  //$in = strtolower(trim($in));
  switch($type){
  case 'long':$out = str_replace($short, $long, $in);break;
  case 'short':$out = str_replace($long, $short, $in);break;
  }
  return $out;
}


$data = Stripe_Transfer::all(array("ending_before" => $_POST['transfer_id'], "limit" => $numberofbatches, "status" => "paid"));
$data = $data->__toArray($recursive=false);

// echo '<h2>Last '.$numberofbatches.' Stripe bank batches:</h2>';

header( 'Content-type: application/force-download' );
header( 'Content-Disposition: attachment; filename="transactions.csv"' );

echo "Transaction Amount, Payee, Description, Reference, Transaction Date\n";

foreach($data[data] as $k) {
  $amount = $k[amount] / 100;
  $transactionData = Stripe_BalanceTransaction::all(array("limit" => 100, "transfer" => $k[id]));
  $transactionData = $transactionData->__toArray($recursive=false);
  foreach($transactionData[data] as $m) {
      if ($m[description] == 'Fee Credit') { # Fee Credits are weird, but tend to happen sometimes
        echo ($m['amount'] / 100).", Stripe Fee credit Transfer ID: $transfer, Stripe Fee credit Transfer ID: $transfer, Transfer ID: $transfer, ".$date."\n"; // Line for Stripe processing fees
        continue;
      } else if (stripos($m[source], 'tr_') !== 0) // Remove Transfer Headers, Only Individual Transactions
      {
        $charge = Stripe_Charge::retrieve($m[source]);
        $charge = $charge->__toArray($recursive=false);
        $payee = $charge[card][name];
        if(empty($payee)) { $payee = $m['source']; }
      } else {
        $transfer = $m[source];
        $date = date('Y-m-d', $m['available_on']);
        echo ($m['amount'] / 100).", Stripe Transfer, Interim account Stripe - Bank transfer, {$transfer}, {$date}\n";
      }
      if (ctype_digit($m['amount'])) // Check if Refund
      {
        $description = "VAT: Unknown - Country: ".convertcodes($charge[card][country], 'long')." - Customer ID: {$m['source']} - Transfer ID: $transfer";
      }
      if (stripos($m[source], 'tr_') !== 0) // Remove Transfer Headers, Only Individual Transactions
      {
        echo ($m['amount'] / 100).", {$payee}, {$m['description']}, {$description}, ".$date."\n";// Line for transaction
        echo ($m['fee'] / 100 *-1).", Stripe Fees, {$m['fee_details'][0]['description']} for {$payee}, Customer ID: {$m['source']} - Transfer ID: $transfer, ".$date."\n"; // Line for Stripe processing fees
      }
  }
}
?>
