<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

/*
Plugin Name: Contact Form 7 CiviCRM integration
Plugin URI: https://www.civicoop.org
Description: Submit contact form 7 to an external CiviCRM
Version: 1.0.0
Author: Jaap Jansma
Author URI: https://www.civicoop.org
License: AGPLv3
Text Domain: cf7_civi
*/

define( 'CF7_CIVI__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CF7_CIVI__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( CF7_CIVI__PLUGIN_DIR . 'cf7_civi_settings.php' );
require_once( CF7_CIVI__PLUGIN_DIR . 'class.api.php' );

if ( is_admin() ) {
  require_once( CF7_CIVI__PLUGIN_DIR . 'cf7_civi_admin.php' );
  add_action( 'init', array( 'cf7_civi_admin', 'init' ) );
}

add_filter( 'wpcf7_contact_form_properties', 'contact_form_properties');
add_action('wpcf7_before_send_mail', 'cf7_civi_before_send_mail');

function cf7_civi_before_send_mail($contact_form) {
  $properties = $contact_form->get_properties();
  if (empty($properties['civicrm']['enable'])) {
    return;
  }

  $api = new civicrm_api3 (array (
      'server' => cf7_civi_settings::getHost(),
      'api_key'=> cf7_civi_settings::getApiKey(),
      'key'=> cf7_civi_settings::getSiteKey()
  ));
  $action = $properties['civicrm']['action'];
  $entity = $properties['civicrm']['entity'];

  $submission = WPCF7_Submission::get_instance();
  $data = $submission->get_posted_data();

  $parameters = explode("&", $properties['civicrm']['parameters']);
  foreach($parameters as $param) {
    list($key, $val) = explode("=", $param);
    if (!empty($key)) {
      $data[$key] = $val;
    }
  }

  $result = $api->call($entity, $action, $data);
}

function contact_form_properties($properties) {

  if (!isset($properties['civicrm'])) {
    $properties['civicrm'] = array(
      'enable' => false,
      'entity' => 'Contact',
      'action' => 'create',
      'parameters' => 'contact_type=Individual&source=Wordpress'
    );
  }
  return $properties;
}