<?php

class Employee extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [

    ];

    public static $updaterules = [

    ];

    protected $fillable = [
        'organization_id',
        'given_name',
        'surname',
        'user_level',
        'designation',
        'country_code',
        'contact_no',
        'email',
        'password',
        'project_contact_person',
        'receive_sms_alert',
        'profile_image_gcs_file_url',
        'profile_image_file_url',
        'signature_html',
        'signature_file_url',
        'signature_gcs_file_url',
        'new_lead_alert',
        'lead_assignment_alert',
        'quotation_status_update_alert',
        'token',
        'status',
        'selected_the_layout_once',
        'use_old_layout',
        'took_the_guide',
        'login_attempts',
        'last_login_attempt_time',
    ];

    public static $user_levels = array(
        'marketing' => 'Marketing',
        'sales' => 'Sales',
        'management' => 'Management',
        'super_user' => 'Super User',
    );


    public static $country_codes = array(
        '+1' => 'United States of America +1',
        '+93' => 'Afghanistan +93',
        '+355' => 'Albania +355',
        '+213' => 'Algeria +213',
        '+376' => 'Andorra +376',
        '+244' => 'Angola +244',
        '+264' => 'Anguilla +264',
        '+268' => 'Antigua +268',
        '+54' => 'Argentina +54',
        '+374' => 'Armenia +374',
        '+297' => 'Aruba +297',
        '+247' => 'Ascension Island +247',
        '+61' => 'Australia +61',
        '+672' => 'Australian External Territories +672',
        '+43' => 'Austria +43',
        '+994' => 'Azerbaijan +994',
        '+242' => 'Bahamas +242',
        '+973' => 'Bahrain +973',
        '+880' => 'Bangladesh +880',
        '+246' => 'Barbados +246',
        '+375' => 'Belarus +375',
        '+32' => 'Belgium +32',
        '+501' => 'Belize +501',
        '+229' => 'Benin +229',
        '+441' => 'Bermuda +441',
        '+975' => 'Bhutan +975',
        '+591' => 'Bolivia +591',
        '+387' => 'Bosnia and Herzegovina +387',
        '+267' => 'Botswana +267',
        '+55' => 'Brazil +55',
        '+284' => 'British Virgin Islands +284',
        '+673' => 'Brunei +673',
        '+359' => 'Bulgaria +359',
        '+226' => 'Burkina Faso +226',
        '+257' => 'Burundi +257',
        '+855' => 'Cambodia +855',
        '+237' => 'Cameroon +237',
        '+238' => 'Cape Verde +238',
        '+345' => 'Cayman Islands +345',
        '+236' => 'Central African Republic +236',
        '+235' => 'Chad +235',
        '+56' => 'Chile +56',
        '+86' => 'China +86',
        '+57' => 'Colombia +57',
        '+269' => 'Comoros +269',
        '+242' => 'Congo +242',
        '+682' => 'Cook Islands +682',
        '+506' => 'Costa Rica +506',
        '+385' => 'Croatia +385',
        '+53' => 'Cuba +53',
        '+357' => 'Cyprus +357',
        '+420' => 'Czech Republic +420',
        '+45' => 'Denmark +45',
        '+246' => 'Diego Garcia +246',
        '+253' => 'Djibouti +253',
        '+767' => 'Dominica +767',
        '+809, 829, 849' => 'Dominican Republic +809, 829, 849',
        '+670' => 'East Timor +670',
        '+593' => 'Ecuador +593',
        '+20' => 'Egypt +20',
        '+503' => 'El Salvador +503',
        '+240' => 'Equatorial Guinea +240',
        '+291' => 'Eritrea +291',
        '+372' => 'Estonia +372',
        '+251' => 'Ethiopia +251',
        '+500' => 'Falkland Islands +500',
        '+298' => 'Faroe Islands +298',
        '+679' => 'Fiji +679',
        '+358' => 'Finland +358',
        '+33' => 'France +33',
        '+596' => 'French Antilles +596',
        '+594' => 'French Guiana +594',
        '+689' => 'French Polynesia +689',
        '+241' => 'Gabon +241',
        '+220' => 'Gambia +220',
        '+995' => 'Georgia +995',
        '+49' => 'Germany +49',
        '+233' => 'Ghana +233',
        '+350' => 'Gibraltar +350',
        '+30' => 'Greece +30',
        '+299' => 'Greenland +299',
        '+473' => 'Grenada +473',
        '+590' => 'Guadeloupe +590',
        '+502' => 'Guatemala +502',
        '+224' => 'Guinea +224',
        '+245' => 'Guinea-Bissau +245',
        '+592' => 'Guyana +592',
        '+509' => 'Haiti +509',
        '+504' => 'Honduras +504',
        '+852' => 'Hong Kong +852',
        '+36' => 'Hungary +36',
        '+354' => 'Iceland +354',
        '+91' => 'India +91',
        '+62' => 'Indonesia +62',
        '+98' => 'Iran +98',
        '+964' => 'Iraq +964',
        '+353' => 'Ireland +353',
        '+972' => 'Israel +972',
        '+39' => 'Italy +39',
        '+225' => 'Ivory Coast +225',
        '+876' => 'Jamaica +876',
        '+81' => 'Japan +81',
        '+962' => 'Jordan +962',
        '+7' => 'Kazakhstan +7',
        '+254' => 'Kenya +254',
        '+686' => 'Kiribati +686',
        '+383' => 'Kosovo +383',
        '+965' => 'Kuwait +965',
        '+996' => 'Kyrgyzstan +996',
        '+856' => 'Laos +856',
        '+371' => 'Latvia +371',
        '+961' => 'Lebanon +961',
        '+266' => 'Lesotho +266',
        '+231' => 'Liberia +231',
        '+218' => 'Libya +218',
        '+423' => 'Liechtenstein +423',
        '+370' => 'Lithuania +370',
        '+352' => 'Luxembourg +352',
        '+853' => 'Macau +853',
        '+389' => 'Macedonia +389',
        '+261' => 'Madagascar +261',
        '+265' => 'Malawi +265',
        '+60' => 'Malaysia +60',
        '+960' => 'Maldives +960',
        '+223' => 'Mali +223',
        '+356' => 'Malta +356',
        '+692' => 'Marshall Islands +692',
        '+222' => 'Mauritania +222',
        '+230' => 'Mauritius +230',
        '+262' => 'Mayotte +262',
        '+52' => 'Mexico +52',
        '+691' => 'Micronesia +691',
        '+373' => 'Moldova +373',
        '+377' => 'Monaco +377',
        '+976' => 'Mongolia +976',
        '+382' => 'Montenegro +382',
        '+664' => 'Montserrat +664',
        '+212' => 'Morocco +212',
        '+258' => 'Mozambique +258',
        '+95' => 'Myanmar +95',
        '+264' => 'Namibia +264',
        '+674' => 'Nauru +674',
        '+977' => 'Nepal +977',
        '+31' => 'Netherlands +31',
        '+599' => 'Netherlands Antilles +599',
        '+687' => 'New Caledonia +687',
        '+64' => 'New Zealand +64',
        '+505' => 'Nicaragua +505',
        '+227' => 'Niger +227',
        '+234' => 'Nigeria +234',
        '+683' => 'Niue +683',
        '+850' => 'North Korea +850',
        '+47' => 'Norway +47',
        '+968' => 'Oman +968',
        '+92' => 'Pakistan +92',
        '+680' => 'Palau +680',
        '+970' => 'Palestinin Authority +970',
        '+507' => 'Panama +507',
        '+675' => 'Papua New Guinea +675',
        '+595' => 'Paraguay +595',
        '+51' => 'Peru +51',
        '+63' => 'Philippines +63',
        '+48' => 'Poland +48',
        '+351' => 'Portugal +351',
        '+974' => 'Qatar +974',
        '+262' => 'Reunion Island +262',
        '+40' => 'Romania +40',
        '+7' => 'Russia +7',
        '+250' => 'Rwanda +250',
        '+290' => 'Saint Helena +290',
        '+869' => 'Saint Kitts and Nevis +869',
        '+758' => 'Saint Lucia +758',
        '+721' => 'St. Maarten +721',
        '+508' => 'Saint Pierre and Miquelon +508',
        '+784' => 'Saint Vincent and the Grenadines +784',
        '+378' => 'San Marino +378',
        '+239' => 'Sao Tome and Principe +239',
        '+966' => 'Saudi Arabia +966',
        '+221' => 'Senegal +221',
        '+381' => 'Serbia +381',
        '+248' => 'Seychelles +248',
        '+232' => 'Sierra Leone +232',
        '+65' => 'Singapore +65',
        '+421' => 'Slovakia +421',
        '+386' => 'Slovenia +386',
        '+677' => 'Solomon Islands +677',
        '+252' => 'Somalia +252',
        '+27' => 'South Africa +27',
        '+82' => 'South Korea +82',
        '+2011' => 'South Sudan +2011',
        '+34' => 'Spain +34',
        '+94' => 'Sri Lanka +94',
        '+249' => 'Sudan +249',
        '+597' => 'Suriname +597',
        '+268' => 'Swaziland +268',
        '+46' => 'Sweden +46',
        '+41' => 'Switzerland +41',
        '+963' => 'Syria +963',
        '+886' => 'Taiwan +886',
        '+992' => 'Tajikistan +992',
        '+255' => 'Tanzania +255',
        '+66' => 'Thailand +66',
        '+228' => 'Togo +228',
        '+690' => 'Tokelau +690',
        '+676' => 'Tonga +676',
        '+868' => 'Trinidad and Tobago +868',
        '+216' => 'Tunisia +216',
        '+90' => 'Turkey +90',
        '+993' => 'Turkmenistan +993',
        '+649' => 'Turks and Caicos +649',
        '+688' => 'Tuvalu +688',
        '+256' => 'Uganda +256',
        '+380' => 'Ukraine +380',
        '+971' => 'United Arab Emirates +971',
        '+44' => 'United Kingdom +44',
        '+598' => 'Uruguay +598',
        '+998' => 'Uzbekistan +998',
        '+678' => 'Vanuatu +678',
        '+58' => 'Venezuela +58',
        '+84' => 'Vietnam +84',
        '+681' => 'Wallis and Futuna +681',
        '+685' => 'Western Samoa +685',
        '+967' => 'Yemen +967',
        '+243' => 'Democratic Republic of the Congo +243',
        '+260' => 'Zambia +260',
        '+263' => 'Zimbabwe +263');

    public static $only_allow_for_test_users = array(
        'suren.dias@webnatics.biz',
        'kasun.kulathunge@webnatics.biz'
    );

    public function organization()
    {
        return $this->belongsTo('Organization', 'organization_id');
    }

    public function accounts()
    {
        return $this->hasMany('Customer');
    }

    public function meetings()
    {
        return $this->hasMany('Meeting');
    }

    public function calls()
    {
        return $this->hasMany('Call');
    }

    public function emails()
    {
        return $this->hasMany('Email');
    }

    public function quotations()
    {
        return $this->hasMany('Quotation');
    }

    public function auditTrails()
    {
        return $this->hasMany('AuditTrail');
    }

    public function employeeScreenPermissions()
    {
        return $this->hasMany('EmployeeScreenPermission');
    }

    public function employeeActionPermissions()
    {
        return $this->hasMany('EmployeeActionPermission');
    }

    public function event360MessengerThreadMessages()
    {
        return $this->hasMany('Event360MessengerThreadMessage');
    }

    public function event360VendorProfileChanges()
    {
        return $this->hasMany('Event360VendorProfileChanges');
    }

    /*
     * this is to check if user is a employee and if his login is available
     *
     */
    public static function checkIfEmployeeLogin($email)
    {
        $employeeLogins = DB::table('employees')
            ->where('email', '=', $email)
            ->get();
        if (count($employeeLogins) > 0) {
            //dd($customerLogins);
            if ($employeeLogins[0]->status == 1) {
                return json_encode($employeeLogins[0]);
            } else {
                return false;
            }

        } else {
            return false;
        }
    }


    // custom get data functions

    public function getEmployeeFullName()
    {
        return $this->given_name . ' ' . $this->surname;
    }

    public static function getEmployeeByEmail($email)
    {
        $employee = Employee::where('email', $email)->first();
        return $employee;
    }

    public function getPhoneNumber()
    {
        return $this->country_code . $this->contact_no;
    }

    /*
     * Password Reset functions
     */

    public static function sendPasswordresetEmail($email) {
        date_default_timezone_set("Asia/Singapore");
        $employee = Employee::where('email',$email)->first();

        $token = uniqid('', true);

        $date_time_now = new DateTime(date('Y-m-d h:i:sa'));

        $data_employee_password_reset_token = array(
            'token' => $token,
            'user_id' => $employee->id,
            'status' => 1,
            'created_timestamp' => $date_time_now
        );

        EmployeePasswordResetToken::create($data_employee_password_reset_token);

        $url = route('employees.show.password.reset.form', array('_token' => $token ));
        $email_sender = new EmailsController();

        $subject = "Web360 Password Reset - ".date('Y-m-d h:i:sa');
        $mailBody = View::make('emails.password_reset', compact('employee','url'))->render();

        //$email_sender->sendEmail($subject,$mailBody,$email);
        EmailsController::sendGridEmailSender($subject,$mailBody,$email);
    }

    public static function checkValidToken($_token){
        date_default_timezone_set("Asia/Singapore");
        $employee_password_reset_token = EmployeePasswordResetToken::where('token', $_token)->first();
        $status = $employee_password_reset_token->status;
        $created_timestamp = $employee_password_reset_token->created_timestamp;
        $created_timestamp = new DateTime($created_timestamp);
        $date_time_now = new DateTime(date('Y-m-d h:i:sa'));
        $interval = $created_timestamp->diff($date_time_now);
        $interval = $interval->format('%h');
        $token_check_status  = false;


        if($status == 1 ){
            if($interval < 1){
                $token_check_status = true;
            }else{
                $token_data['status'] = 0;
                $employee_password_reset_token->update($token_data);
                $token_check_status = false;
            }
        }

        return $token_check_status;
    }

    /*
     * Permission functions
     */

    // mobile api create token
    public static function createLoginToken($email) {
        return uniqid('web360_mobile_login_'.$email);
    }

    public static function postLoginEmployeeFunctions($employee)
    {
        if (isset($employee)) {
            // check if disabled
            if ($employee->status == 1) {
                Session::set('user-email', $employee->email);
                Session::set('user-name', $employee->given_name . ' ' . $employee->surname);
                Session::set('user-level', $employee->user_level);
                Session::set('user-id', $employee->id);
                Session::set('user-organization-id', $employee->organization_id);
                Session::set('user-profile-image', $employee->profile_image_file_url);
                Session::set('user-selected-the-layout-once', $employee->selected_the_layout_once);
                Session::set('user-use-old-layout', $employee->use_old_layout);
                Session::set('user-took-the-guide', $employee->took_the_guide);
                Session::set('user-profile-image', $employee->profile_image_file_url);
                Session::set('user-profile-image', $employee->profile_image_file_url);
                Session::set('webtics-project-ids', Organization::getProjectIds($employee->organization_id));
                Session::set('demo-mode', $employee->demo_mode);


                // get organization configurations
                $organization_configurations = OrganizationConfigurationMapping::where('organization_id', $employee->organization_id)->lists('organization_configuration_id');
                Session::set('organization-configurations', $organization_configurations);


                $audit_action = array(
                    'action' => 'login',
                    'model-id' => $employee->id,
                    'data' => $employee
                );

                AuditTrail::addAuditEntry("SystemLogin", json_encode($audit_action));

                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    // this function is to check if a particular employee has access to particular project data
    public static function projectChecker($project_name) {
        if($project_name == 'event360') {
            if(in_array(Config::get('project_vars.event360_project_id'), Session::get('webtics-project-ids'))) {
                return true;
            }
        }
    }


    public static function checkAuthorizedAccess($allowed_user_levels = array(), $allowed_users = array()) {


        if(Session::get('user-level') == 'super_user') {
            return true;
        }

        if (in_array(Session::get('user-level'), $allowed_user_levels) || in_array(Session::get('user-email'), $allowed_users)) {
            return true;
        } else {
            false;
        }
    }


    public static function checkAuthorizedAccessMyActivities($allowed_user_id = array()) {
        if((Session::get('user-level') == 'super_user') || (Session::get('user-level') == 'management')) {
            return true;
        }
        if (in_array(Session::get('user-id'), $allowed_user_id)) {
            return true;
        } else {
            false;
        }
    }

    public static function getEmailSignatureHTML(){

        $user_id = Session::get('user-id');

        $user = Employee::findOrFail($user_id);

        $signature_html = $user->signature_html;
        $signature_file_url = $user->signature_file_url;

        $email_signature = $signature_html.'<p><img src="'.$signature_file_url.'"  /></p>';

        syslog(LOG_INFO,$email_signature);

        return $email_signature;
    }

    public function getEmployeeNotificationStatus($type_of_alert, $type_of_notification)
    {
        $notification_status = Notification::where('employee_id', $this->id)
            ->where('type_of_alert', $type_of_alert)
            ->where('organization_id', Session::get('user-organization-id'))
            ->pluck($type_of_notification);

        return $notification_status;
    }


}