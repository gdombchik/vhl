<?php
require_once("Ctct/autoload.php");
define("APIKEY", "ecdct88jb7rfhpgdcennt9sg");
define("ACCESS_TOKEN", "04ad9f9a-2d05-477f-bd31-f1af138c7e7a");

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;
use Ctct\Components\Contacts\Address;
use Ctct\Components\Contacts\CustomField;

$cc = new ConstantContact(APIKEY);
$ContactListID = "15";
//-- Use this to get a contact lists so can determine the list ID for your contact list...In this case, looking for Electronic News, which is ID 15
// attempt to fetch lists in the account, catching any exceptions and printing the errors to screen
/*
try {
    $lists = $cc->getLists(ACCESS_TOKEN);
} catch (CtctException $ex) {
    foreach ($ex->getErrors() as $error) {
        print_r($error);
    }
}

echo '<pre>'; print_r($lists); echo '</pre>';
*/

//$response = $cc->getContactByEmail(ACCESS_TOKEN, "thewebleys@bigpond.com");
//echo '<pre>'; print_r($response); echo '</pre>';
//exit;

// check if the form was submitted
//echo '<pre>'; print_r($_POST); print_r('</pre>');

if (isset($_POST['iSubmit']) && strlen($_POST['iSubmit']) > 1)
{
  if (!empty($_POST['iFirstName']) && !empty($_POST['iLastName']) && !empty($_POST['iEmail']))
  {
    //$action = "VHL.org E-News Signup: Getting Contact By Email Address";
    try {
        $address['line1'] = $_POST['iAddress1'];
        $address['line2'] = $_POST['iAddress2'];
        $address['city'] = $_POST['iCity'];
        $address['address_type'] = "UNKNOWN";
        $address['state_code'] = $_POST['iState'];
        $address['country_code'] = $_POST['iCountry'];
        $address['postal_code'] = $_POST['iPostalCode'];
         
        // check to see if a contact with the email addess already exists in the account
        $response = $cc->getContactByEmail(ACCESS_TOKEN, $_POST['iEmail']);

        // create a new contact if one does not exist
        if (empty($response->results)) {
            $action = "VHL.org E-News Signup: Creating Contact";

            $contact = new Contact();
            $contact->source = "VHL.org E-News Signup";
            $contact->addList($ContactListID);
            $contact->status = "ACTIVE";
            $contact->first_name = $_POST['iFirstName'];
            $contact->last_name = $_POST['iLastName'];
            $contact->addEmail($_POST['iEmail']);

            if (!empty($_POST['iType']))
            {            
              $custom['name'] = 'CustomField10';
              $custom['value'] = 'I am a ' . $_POST['iType'];
              $customField = CustomField::create($custom);
              $contact->addCustomField($customField);
            }
                        
            $custom['name'] = "CustomField7";
            $custom['value'] = $_POST['iComments'];
            $customField = CustomField::create($custom);
            $contact->addCustomField($customField);

            $DOB['name'] = "CustomField8";
            $DOB['value'] = 'DOB: ' . $_POST['iDOBMonth'] . '/' . $_POST['iDOBDay'] . '/' . $_POST['iDOBYear'];
            $customField = CustomField::create($DOB);
            $contact->addCustomField($customField);
                        
            if (!empty($_POST['iPhone1']))
            {
              $contact->home_phone = $_POST['iPhone1'];
            }
            
            if (!empty($_POST['iPhone2']))
            {
              $contact->work_phone = $_POST['iPhone2'];
            }
                        
            if (!empty($_POST['iPhone3']))
            {
              $contact->cell_phone = $_POST['iPhone3'];
            }
                        
            $contact->addresses[] = Address::create($address);

            /*
             * The third parameter of addContact defaults to false, but if this were set to true it would tell Constant
             * Contact that this action is being performed by the contact themselves, and gives the ability to
             * opt contacts back in and trigger Welcome/Change-of-interest emails.
             *
             * See: http://developer.constantcontact.com/docs/contacts-api/contacts-index.html#opt_in
             */
            $returnContact = $cc->addContact(ACCESS_TOKEN, $contact, false);

            // update the existing contact if address already existed
        } else {
            $action = "VHL.org E-News Signup: Updating Contact";

            $contact = $response->results[0];
            $contact->source = "VHL.org E-News Signup";
            $contact->addList($ContactListID);
            $contact->status = "ACTIVE";
            $contact->first_name = $_POST['iFirstName'];
            $contact->last_name = $_POST['iLastName'];
            
            if (!empty($_POST['iPhone1']))
            {
              $contact->home_phone = $_POST['iPhone1'];
            }
            
            if (!empty($_POST['iPhone2']))
            {
              $contact->work_phone = $_POST['iPhone2'];
            }
                        
            if (!empty($_POST['iPhone3']))
            {
              $contact->cell_phone = $_POST['iPhone3'];
            }

            if (!empty($_POST['iType']))
            {            
              $custom['name'] = 'CustomField10';
              $custom['value'] = 'I am a ' . $_POST['iType'];
              $customField = CustomField::create($custom);
              $contact->addCustomField($customField);
            }
                        
            $custom['name'] = "CustomField7";
            $custom['value'] = $_POST['iComments'];
            $customField = CustomField::create($custom);
            $contact->addCustomField($customField);

            $DOB['name'] = "CustomField8";
            $DOB['value'] = 'DOB: ' . $_POST['iDOBMonth'] . '/' . $_POST['iDOBDay'] . '/' . $_POST['iDOBYear'];
            $customField = CustomField::create($DOB);
            $contact->addCustomField($customField);
             
            if (!empty($_POST['iEmail']))
            {                 
              $contact->email_addresses[0] = $_POST['iEmail'];
            }
            
            $contact->addresses[] = Address::create($address);
            
            echo "<pre>"; print_r($contact); echo '</pre>';
            /*
             * The third parameter of updateContact defaults to false, but if this were set to true it would tell
             * Constant Contact that this action is being performed by the contact themselves, and gives the ability to
             * opt contacts back in and trigger Welcome/Change-of-interest emails.
             *
             * See: http://developer.constantcontact.com/docs/contacts-api/contacts-index.html#opt_in
             */
            $returnContact = $cc->updateContact(ACCESS_TOKEN, $contact, false);
        }
        unset($_POST);
        // catch any exceptions thrown during the process and print the errors to screen
    } catch (CtctException $ex) {
      //-- Will email this to director/support when there's an error trying to create/update the record
      $to = "KyInthavong@gmail.com";
      $message = "Received this error during " . $action . ": " . $ex->getMessage() . "\r\n\r\n"
               . "Signup Information:\r\n"
               . "First Name: " . $_POST['iFirstName'] . "\r\n"
               . "Last Name: " . $_POST['iLastName'] . "\r\n"
               . "Email: " . $_POST['iEmail'] . "\r\n"
               . "Country: " . $_POST['iCountry'] . "\r\n"
               . "State: " . $_POST['iState'] . "\r\n"
               . "City: " . $_POST['iCity'] . "\r\n"
               . "Postal Code: " . $_POST['iPostalCode'] . "\r\n"
               . "Home Ph: " . $_POST['iPhone1'] . "\r\n"
               . "Work Ph: " . $_POST['iPhone2'] . "\r\n"
               . "Cell Ph: " . $_POST['iPhone3'] . "\r\n"
               . "DOB: " . $_POST['iDOBMonth'] . "/" . $_POST['iDOBDay'] . "/" . $_POST['iDOBYear'] . "\r\n"
               . "I am: " . $_POST['iType'] . "\r\n"               
               . "Comments: " . $_POST['iComments'] . "\r\n";
               
               
      wp_mail($to, "E-News Sign Up Error", $message);
      
        echo '<span class="label label-important">Error! ' . $action . '</span>';
        echo '<div class="container alert-error"><pre class="failure-pre">';
        print_r($ex->getErrors());
        //echo 'Thank you for signing up for the E-Newsletter.  Your information has been sent.';
        echo '</pre></div>';
    }
    $action = 'Thank you for signing up for the E-Newsletter.  Your information has been sent.';
  }
  else
  {
    $action = "Missing some required fields";
  }
}    
?>

<style type="text/css">
  .required { color: red; font-weight: bold; }
</style>

<h1>WELCOME TO THE VHL ALLIANCE!</h1>
<div>To receive our electronic newsletter, fill in the following fields and click the submit button at the bottom. If you live in the US, please include your state so that we can send you regional information.&nbsp;</div>
<div>&nbsp;</div>
<div><strong><em><span style="font-family: arial,helvetica,sans-serif; font-size: 12pt;">Thank you!</span></em></strong></div>
<div style="font-weight: bold; color:red;"><?php echo $action; ?></div>
<form method="post" id="frmSignUp" name="frmSignUp">
  <table style="padding:0; margin:0; border-spacing: 0; border-collapse: collapse;">
    <tr><td colspan="2" style="">
    <span style="color:red">* required</span>
    </td>
  </tr>
    <tr><td><span class="required">*</span>First Name:</td>
      <td style="text-align:left"><input type="text" id="iFirstName" name="iFirstName" maxlength="50" style="width:150px" value="<?php if (!empty($_POST['iFirstName'])) echo $_POST['iFirstName'];?>"><label id="lblFirstName"></label></td>
    </tr>
    <tr style="margin-top:0"><td><span class="required">*</span>Last Name:</td><td><input type="text" id="iLastName" name="iLastName" maxlength="50" style="width:150px" value="<?php if (!empty($_POST['iLastName'])) echo $_POST['iLastName'];?>"><label id="lblLastName"></label></td>
    </tr>
    <tr><td><span class="required">*</span>Email:</td><td><input type="text" id="iEmail" name="iEmail" maxlength="150" style="width:250px"  value="<?php if (!empty($_POST['iEmail'])) echo $_POST['iEmail'];?>"><label id="lblEmail"></label></td>
    </tr>
    <tr><td>Country:</td>
    <td>
      <select id="iCountry" name="iCountry">
        <option value=''>Select Country</option>
        <option value='AF'>AFGHANISTAN</option>
        <option value='AX'>ÅLAND ISLANDS</option>
        <option value='AL'>ALBANIA</option>
        <option value='DZ'>ALGERIA</option>
        <option value='AS'>AMERICAN SAMOA</option>
        <option value='AD'>ANDORRA</option>
        <option value='AO'>ANGOLA</option>
        <option value='AI'>ANGUILLA</option>
        <option value='AQ'>ANTARCTICA</option>
        <option value='AG'>ANTIGUA AND BARBUDA</option>
        <option value='AR'>ARGENTINA</option>
        <option value='AM'>ARMENIA</option>
        <option value='AW'>ARUBA</option>
        <option value='AU'>AUSTRALIA</option>
        <option value='AT'>AUSTRIA</option>
        <option value='AZ'>AZERBAIJAN</option>
        <option value='BS'>BAHAMAS</option>
        <option value='BH'>BAHRAIN</option>
        <option value='BD'>BANGLADESH</option>
        <option value='BB'>BARBADOS</option>
        <option value='BY'>BELARUS</option>
        <option value='BE'>BELGIUM</option>
        <option value='BZ'>BELIZE</option>
        <option value='BJ'>BENIN</option>
        <option value='BM'>BERMUDA</option>
        <option value='BT'>BHUTAN</option>
        <option value='BO'>BOLIVIA, PLURINATIONAL STATE OF</option>
        <option value='BQ'>BONAIRE, SINT EUSTATIUS AND SABA</option>
        <option value='BA'>BOSNIA AND HERZEGOVINA</option>
        <option value='BW'>BOTSWANA</option>
        <option value='BV'>BOUVET ISLAND</option>
        <option value='BR'>BRAZIL</option>
        <option value='IO'>BRITISH INDIAN OCEAN TERRITORY</option>
        <option value='BN'>BRUNEI DARUSSALAM</option>
        <option value='BG'>BULGARIA</option>
        <option value='BF'>BURKINA FASO</option>
        <option value='BI'>BURUNDI</option>
        <option value='KH'>CAMBODIA</option>
        <option value='CM'>CAMEROON</option>
        <option value='CA'>CANADA</option>
        <option value='CV'>CAPE VERDE</option>
        <option value='KY'>CAYMAN ISLANDS</option>
        <option value='CF'>CENTRAL AFRICAN REPUBLIC</option>
        <option value='TD'>CHAD</option>
        <option value='CL'>CHILE</option>
        <option value='CN'>CHINA</option>
        <option value='CX'>CHRISTMAS ISLAND</option>
        <option value='CC'>COCOS (KEELING) ISLANDS</option>
        <option value='CO'>COLOMBIA</option>
        <option value='KM'>COMOROS</option>
        <option value='CG'>CONGO</option>
        <option value='CD'>CONGO, THE DEMOCRATIC REPUBLIC OF THE</option>
        <option value='CK'>COOK ISLANDS</option>
        <option value='CR'>COSTA RICA</option>
        <option value='CI'>CÔTE D'IVOIRE</option>
        <option value='HR'>CROATIA</option>
        <option value='CU'>CUBA</option>
        <option value='CW'>CURAÇAO</option>
        <option value='CY'>CYPRUS</option>
        <option value='CZ'>CZECH REPUBLIC</option>
        <option value='DK'>DENMARK</option>
        <option value='DJ'>DJIBOUTI</option>
        <option value='DM'>DOMINICA</option>
        <option value='DO'>DOMINICAN REPUBLIC</option>
        <option value='EC'>ECUADOR</option>
        <option value='EG'>EGYPT</option>
        <option value='SV'>EL SALVADOR</option>
        <option value='GQ'>EQUATORIAL GUINEA</option>
        <option value='ER'>ERITREA</option>
        <option value='EE'>ESTONIA</option>
        <option value='ET'>ETHIOPIA</option>
        <option value='FK'>FALKLAND ISLANDS (MALVINAS)</option>
        <option value='FO'>FAROE ISLANDS</option>
        <option value='FJ'>FIJI</option>
        <option value='FI'>FINLAND</option>
        <option value='FR'>FRANCE</option>
        <option value='GF'>FRENCH GUIANA</option>
        <option value='PF'>FRENCH POLYNESIA</option>
        <option value='TF'>FRENCH SOUTHERN TERRITORIES</option>
        <option value='GA'>GABON</option>
        <option value='GM'>GAMBIA</option>
        <option value='GE'>GEORGIA</option>
        <option value='DE'>GERMANY</option>
        <option value='GH'>GHANA</option>
        <option value='GI'>GIBRALTAR</option>
        <option value='GR'>GREECE</option>
        <option value='GL'>GREENLAND</option>
        <option value='GD'>GRENADA</option>
        <option value='GP'>GUADELOUPE</option>
        <option value='GU'>GUAM</option>
        <option value='GT'>GUATEMALA</option>
        <option value='GG'>GUERNSEY</option>
        <option value='GN'>GUINEA</option>
        <option value='GW'>GUINEA-BISSAU</option>
        <option value='GY'>GUYANA</option>
        <option value='HT'>HAITI</option>
        <option value='HM'>HEARD ISLAND AND MCDONALD ISLANDS</option>
        <option value='VA'>HOLY SEE (VATICAN CITY STATE)</option>
        <option value='HN'>HONDURAS</option>
        <option value='HK'>HONG KONG</option>
        <option value='HU'>HUNGARY</option>
        <option value='IS'>ICELAND</option>
        <option value='IN'>INDIA</option>
        <option value='ID'>INDONESIA</option>
        <option value='IR'>IRAN, ISLAMIC REPUBLIC OF</option>
        <option value='IQ'>IRAQ</option>
        <option value='IE'>IRELAND</option>
        <option value='IM'>ISLE OF MAN</option>
        <option value='IL'>ISRAEL</option>
        <option value='IT'>ITALY</option>
        <option value='JM'>JAMAICA</option>
        <option value='JP'>JAPAN</option>
        <option value='JE'>JERSEY</option>
        <option value='JO'>JORDAN</option>
        <option value='KZ'>KAZAKHSTAN</option>
        <option value='KE'>KENYA</option>
        <option value='KI'>KIRIBATI</option>
        <option value='KP'>KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF</option>
        <option value='KR'>KOREA, REPUBLIC OF</option>
        <option value='KW'>KUWAIT</option>
        <option value='KG'>KYRGYZSTAN</option>
        <option value='LA'>LAO PEOPLE'S DEMOCRATIC REPUBLIC</option>
        <option value='LV'>LATVIA</option>
        <option value='LB'>LEBANON</option>
        <option value='LS'>LESOTHO</option>
        <option value='LR'>LIBERIA</option>
        <option value='LY'>LIBYA</option>
        <option value='LI'>LIECHTENSTEIN</option>
        <option value='LT'>LITHUANIA</option>
        <option value='LU'>LUXEMBOURG</option>
        <option value='MO'>MACAO</option>
        <option value='MK'>MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF</option>
        <option value='MG'>MADAGASCAR</option>
        <option value='MW'>MALAWI</option>
        <option value='MY'>MALAYSIA</option>
        <option value='MV'>MALDIVES</option>
        <option value='ML'>MALI</option>
        <option value='MT'>MALTA</option>
        <option value='MH'>MARSHALL ISLANDS</option>
        <option value='MQ'>MARTINIQUE</option>
        <option value='MR'>MAURITANIA</option>
        <option value='MU'>MAURITIUS</option>
        <option value='YT'>MAYOTTE</option>
        <option value='MX'>MEXICO</option>
        <option value='FM'>MICRONESIA, FEDERATED STATES OF</option>
        <option value='MD'>MOLDOVA, REPUBLIC OF</option>
        <option value='MC'>MONACO</option>
        <option value='MN'>MONGOLIA</option>
        <option value='ME'>MONTENEGRO</option>
        <option value='MS'>MONTSERRAT</option>
        <option value='MA'>MOROCCO</option>
        <option value='MZ'>MOZAMBIQUE</option>
        <option value='MM'>MYANMAR</option>
        <option value='NA'>NAMIBIA</option>
        <option value='NR'>NAURU</option>
        <option value='NP'>NEPAL</option>
        <option value='NL'>NETHERLANDS</option>
        <option value='NC'>NEW CALEDONIA</option>
        <option value='NZ'>NEW ZEALAND</option>
        <option value='NI'>NICARAGUA</option>
        <option value='NE'>NIGER</option>
        <option value='NG'>NIGERIA</option>
        <option value='NU'>NIUE</option>
        <option value='NF'>NORFOLK ISLAND</option>
        <option value='MP'>NORTHERN MARIANA ISLANDS</option>
        <option value='NO'>NORWAY</option>
        <option value='OM'>OMAN</option>
        <option value='PK'>PAKISTAN</option>
        <option value='PW'>PALAU</option>
        <option value='PS'>PALESTINE, STATE OF</option>
        <option value='PA'>PANAMA</option>
        <option value='PG'>PAPUA NEW GUINEA</option>
        <option value='PY'>PARAGUAY</option>
        <option value='PE'>PERU</option>
        <option value='PH'>PHILIPPINES</option>
        <option value='PN'>PITCAIRN</option>
        <option value='PL'>POLAND</option>
        <option value='PT'>PORTUGAL</option>
        <option value='PR'>PUERTO RICO</option>
        <option value='QA'>QATAR</option>
        <option value='RE'>RÉUNION</option>
        <option value='RO'>ROMANIA</option>
        <option value='RU'>RUSSIAN FEDERATION</option>
        <option value='RW'>RWANDA</option>
        <option value='BL'>SAINT BARTHÉLEMY</option>
        <option value='SH'>SAINT HELENA, ASCENSION AND TRISTAN DA CUNHA</option>
        <option value='KN'>SAINT KITTS AND NEVIS</option>
        <option value='LC'>SAINT LUCIA</option>
        <option value='MF'>SAINT MARTIN (FRENCH PART)</option>
        <option value='PM'>SAINT PIERRE AND MIQUELON</option>
        <option value='VC'>SAINT VINCENT AND THE GRENADINES</option>
        <option value='WS'>SAMOA</option>
        <option value='SM'>SAN MARINO</option>
        <option value='ST'>SAO TOME AND PRINCIPE</option>
        <option value='SA'>SAUDI ARABIA</option>
        <option value='SN'>SENEGAL</option>
        <option value='RS'>SERBIA</option>
        <option value='SC'>SEYCHELLES</option>
        <option value='SL'>SIERRA LEONE</option>
        <option value='SG'>SINGAPORE</option>
        <option value='SX'>SINT MAARTEN (DUTCH PART)</option>
        <option value='SK'>SLOVAKIA</option>
        <option value='SI'>SLOVENIA</option>
        <option value='SB'>SOLOMON ISLANDS</option>
        <option value='SO'>SOMALIA</option>
        <option value='ZA'>SOUTH AFRICA</option>
        <option value='GS'>SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS</option>
        <option value='SS'>SOUTH SUDAN</option>
        <option value='ES'>SPAIN</option>
        <option value='LK'>SRI LANKA</option>
        <option value='SD'>SUDAN</option>
        <option value='SR'>SURINAME</option>
        <option value='SJ'>SVALBARD AND JAN MAYEN</option>
        <option value='SZ'>SWAZILAND</option>
        <option value='SE'>SWEDEN</option>
        <option value='CH'>SWITZERLAND</option>
        <option value='SY'>SYRIAN ARAB REPUBLIC</option>
        <option value='TW'>TAIWAN, PROVINCE OF CHINA</option>
        <option value='TJ'>TAJIKISTAN</option>
        <option value='TZ'>TANZANIA, UNITED REPUBLIC OF</option>
        <option value='TH'>THAILAND</option>
        <option value='TL'>TIMOR-LESTE</option>
        <option value='TG'>TOGO</option>
        <option value='TK'>TOKELAU</option>
        <option value='TO'>TONGA</option>
        <option value='TT'>TRINIDAD AND TOBAGO</option>
        <option value='TN'>TUNISIA</option>
        <option value='TR'>TURKEY</option>
        <option value='TM'>TURKMENISTAN</option>
        <option value='TC'>TURKS AND CAICOS ISLANDS</option>
        <option value='TV'>TUVALU</option>
        <option value='UG'>UGANDA</option>
        <option value='UA'>UKRAINE</option>
        <option value='AE'>UNITED ARAB EMIRATES</option>
        <option value='GB'>UNITED KINGDOM</option>
        <option value='US'>UNITED STATES</option>
        <option value='UM'>UNITED STATES MINOR OUTLYING ISLANDS</option>
        <option value='UY'>URUGUAY</option>
        <option value='UZ'>UZBEKISTAN</option>
        <option value='VU'>VANUATU</option>
        <option value='VE'>VENEZUELA, BOLIVARIAN REPUBLIC OF</option>
        <option value='VN'>VIET NAM</option>
        <option value='VG'>VIRGIN ISLANDS, BRITISH</option>
        <option value='VI'>VIRGIN ISLANDS, U.S.</option>
        <option value='WF'>WALLIS AND FUTUNA</option>
        <option value='EH'>WESTERN SAHARA</option>
        <option value='YE'>YEMEN</option>
        <option value='ZM'>ZAMBIA</option>
        <option value='ZW'>ZIMBABWE</option>
      </select>
    </td>
    </tr>
    <tr><td>State:</td><td>
      <select name="iState" id="iState">
        <option value=""></option>
        <option value="AK">AK</option>
        <option value="AL">AL</option>
        <option value="AR">AR</option>
        <option value="AS">AS</option>
        <option value="AZ">AZ</option>
        <option value="CA">CA</option>
        <option value="CO">CO</option>
        <option value="CT">CT</option>
        <option value="DC">DC</option>
        <option value="DE">DE</option>
        <option value="FL">FL</option>
        <option value="GA">GA</option>
        <option value="GU">GU</option>
        <option value="HI">HI</option>
        <option value="IA">IA</option>
        <option value="ID">ID</option>
        <option value="IL">IL</option>
        <option value="IN">IN</option>
        <option value="KS">KS</option>
        <option value="KY">KY</option>
        <option value="LA">LA</option>
        <option value="MA">MA</option>
        <option value="ME">ME</option>
        <option value="MD">MD</option>
        <option value="MI">MI</option>
        <option value="MN">MN</option>
        <option value="MO">MO</option>
        <option value="MS">MS</option>
        <option value="MT">MT</option>
        <option value="NC">NC</option>
        <option value="ND">ND</option>
        <option value="NE">NE</option>
        <option value="NH">NH</option>
        <option value="NJ">NJ</option>
        <option value="NM">NM</option>
        <option value="NV">NV</option>
        <option value="NY">NY</option>
        <option value="OH">OH</option>
        <option value="OK">OK</option>
        <option value="OR">OR</option>
        <option value="PA">PA</option>
        <option value="PR">PR</option>
        <option value="RI">RI</option>
        <option value="SC">SC</option>
        <option value="SD">SD</option>
        <option value="TN">TN</option>
        <option value="TX">TX</option>
        <option value="UT">UT</option>
        <option value="VA">VA</option>
        <option value="VT">VT</option>
        <option value="WA">WA</option>
        <option value="WI">WI</option>
        <option value="WV">WV</option>
        <option value="WY">WY</option>
      </select>
    </td>
    </tr>
    <tr><td>Address Line 1:</td><td><input id="iAddress1" name="iAddress1" maxlength="250" style="width:250px"  value="<?php if (!empty($_POST['iAddress1'])) echo $_POST['iAddress1'];?>"/></td>
    </tr>
    <tr><td>Address Line 2:</td><td><input id="iAddress2" name="iAddress2" maxlength="250" style="width:250px" value="<?php if (!empty($_POST['iAddress2'])) echo $_POST['iAddress2'];?>" /></td>
    </tr>
    <tr><td>City:</td><td><input id="iCity" name="iCity" maxlength="100" value="<?php if (!empty($_POST['iCity'])) echo $_POST['iCity'];?>" /></td>
    </tr>
    <tr><td>Postal Code:</td><td><input id="iPostalCode" name="iPostalCode" maxlength="20" value="<?php if (!empty($_POST['iPostalCode'])) echo $_POST['iPostalCode'];?>" /></td>
    </tr>
    <tr><td>Home Phone:</td><td><input id="iPhone1" name="iPhone1" maxlength="20" value="<?php if (!empty($_POST['iPhone1'])) echo $_POST['iPhone1'];?>" /></td>
    </tr>
    <tr><td>Work Phone:</td><td><input id="iPhone2" name="iPhone2" maxlength="20" value="<?php if (!empty($_POST['iPhone2'])) echo $_POST['iPhone2'];?>" /></td>
    </tr>
    <tr><td>Cell Phone:</td><td><input id="iPhone3" name="iPhone3" maxlength="20" value="<?php if (!empty($_POST['iPhone3'])) echo $_POST['iPhone3'];?>" /></td>
    </tr>
    <tr><td>Date of Birth:</td><td>
      <table style="padding: 0; border: 0; width: 75%; margin:0">
        <tr><td>
      <select id="iDOBMonth" name="iDOBMonth" style="width:100px">
        <option value="0">Month</option>
<option value="1">January</option>
<option value="2">February</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
      </select>
       </td><td> 
      <select id="iDOBDay" name="iDOBDay" style="width:60px">
<option value="0">Day</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>        
      </select>
      </td><td>
      <select id="iDOBYear" name="iDOBYear" style="width:100px">
        <option value="0">Year</option>
        <?php
          $FirstYear = date("Y") - 100;
          $CurrentYear = date("Y");
          for ($index = $FirstYear; $index < $CurrentYear; $index++)
          {
            echo "<option value='$index'>$index</option>";
          }
        ?>
      </select>
       </td>
       </tr>
       </table>
    </td>
    </tr>
    <tr><td>I am:</td><td>
      <input type="radio" id="iType" name="iType" value="Patient"> &nbsp;Patient</input><br />
      <input type="radio" id="iType" name="iType" value="Family Member"> &nbsp;Family Member</input><br />
      <input type="radio" id="iType" name="iType" value="Friend"> &nbsp;Friend</input><br />
      <input type="radio" id="iType" name="iType" value="Physician"> &nbsp;Physician</input><br />
      <input type="radio" id="iType" name="iType" value="Genetic Counselor"> &nbsp;Genetic Counselor</input><br />
      <input type="radio" id="iType" name="iType" value="Researcher"> &nbsp;Researcher</input><br />
    </td>
    </tr>
    <tr><td>Comments:</td><td>
      <textarea id="iComments" name="iComments"><?php if (!empty($_POST['iComments'])) echo $_POST['iComments'];?></textarea>
    </td>    
    </tr>
    <tr><td colspan="2" style="text-align:center">
    <input type="submit" id="iSubmit" name="iSubmit" value="Submit" />
  </tr>
  </table>    
  </div>
</form>

<script type="text/javascript">
  function ValidEmail(emailAddress) {
      var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
      return pattern.test(emailAddress);
  }    
    
  function SubmitForm()
  {
    $('#lblEmail').html("");
    $('#lblFirstName').html("");
    $('#lblLastName').html("");
    
    var FirstName = $("input#iFirstName").val();
    var LastName = $("input#iLastName").val();
    var Email = $("input#iEmail").val();
    var status = true;
    
    if (FirstName == "")
    {
      $('#lblFirstName').html("required.");
      status = false;
    }

    if (LastName == "")
    {
      $('#lblLastName').html("required.");
      status = false;
    }

    if (!ValidEmail(email))
    {
      $('#lblEmail').html("Required/Invalid format, please verify");
      status = false;
    }
  }
  
  $(document).ready(function(){
    $(document).keypress(function(e){
      if (e.which == 13)
      {
      SubmitForm();          
      }
    });

    $('#frmSignUp').click(function(){
      SubmitForm();          
    });
  });
  
</script>
