<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This changes the user 
 */
class Migration_Convert_user_profiles_table extends CI_Migration {

    public function up()
    {
    	$this->load->language('users/user');
    	$this->load->library('settings/Settings');

    	// Load up the streams driver and convert the profiles table
    	// into a stream.
    	$this->load->driver('Streams');

    	$this->streams->utilities->convert_table_to_stream('profiles', 'users', null, 'User Profiles', 'Profiles for users module', 'display_name', array('display_name'));

    	// Go ahead and convert our standard user fields:
    	$columns = array(
    		'display_name' => array(
    			'field_name' => 'profile_display_name',
    			'field_type' => 'text',
    			'extra'		 => array('max_length' => 50),
    			'assign'	 => array('required' => true)
    		),
			'first_name' => array(
    			'field_name' => 'user_first_name',
    			'field_type' => 'text',
    			'extra'		 => array('max_length' => 50),
    			'assign'	 => array('required' => true)
    		),
			'last_name' => array(
    			'field_name' => 'user_last_name',
    			'field_type' => 'text',
    			'extra'		 => array('max_length' => 50)
    		),
    		'company' => array(
    			'field_name' => 'profile_company',
    			'field_slug' => 'company',
    			'field_type' => 'text',
    			'extra'		 => array('max_length' => 100)
    		),
    		'language' => array(
    			'field_name' => 'user_lang',
    			'field_slug' => 'pyro_lang',
    			'extra'		 => array('filter_theme' => 'yes')
    		),
 			'bio' => array(
    			'field_name' => 'profile_bio',
    			'field_type' => 'textarea'
    		),
 			'dob' => array(
    			'field_name' => 'profile_dob',
    			'field_type' => 'datetime',
    			'extra'		 => array('use_time' => 'no', 'storage' => 'unix')
    		),
    		'gender' => array(
    			'field_name' => 'profile_gender',
    			'field_type' => 'choice',
    			'extra'		 => array('choice_type' => 'radio', 'choice_data' => " : Not Telling\nm : Male\nf : Female")
    		),
     		'phone' => array(
    			'field_name' => 'profile_phone',
    			'field_type' => 'text',
    			'extra'		 => array('max_length' => 20)
    		),
     		'mobile' => array(
    			'field_name' => 'profile_mobile',
    			'field_type' => 'text',
    			'extra'		 => array('max_length' => 20)
    		),
      		'address_line1' => array(
    			'field_name' => 'profile_address_line1',
    			'field_type' => 'text'
    		),
      		'address_line2' => array(
    			'field_name' => 'profile_address_line2',
    			'field_type' => 'text'
    		),
    		'address_line3' => array(
    			'field_name' => 'profile_address_line3',
    			'field_type' => 'text'
    		),
    		'postcode' => array(
    			'field_name' => 'profile_address_postcode',
    			'field_type' => 'text',
    			'extra'		 => array('max_length' => 20)
    		),
     		'website' => array(
    			'field_name' => 'profile_website',
    			'field_type' => 'text'
    		),
            'updated_on' => array(
                'field_name' => 'profile_updated_on',
                'field_type' => 'current_time'
            )
    	);

		// Special case: Do we require the last name?
		if (Settings::get('require_lastname'))
		{
			$fields['last_name']['assign'] = array('required' => true);
		}

		// Here we go...
		// Run through each column and add the field
		// metadata to it.
    	foreach($columns as $field_slug => $column)
    	{
    		// We only want fields that actually exist in the
    		// DB. The user could have deleted some of them.
    		if ($this->db->field_exists($field_slug, 'profiles'))
    		{
	    		$extra = array();
	    		$assign = array();

	    		if (isset($column['extra']))
	    		{
	    			$extra = $column['extra'];
	    		}

	    		if (isset($column['assign']))
	    		{
	    			$assign = $column['assign'];
	    		}

	    		$this->streams->utilities->convert_column_to_field('profiles', 'users', $column['field_name'], $field_slug, $column['field_type'], $extra, $assign);

	    		unset($extra);
	    		unset($assign);
    		}
    	}
    }

    public function down()
    {
		
    }
}