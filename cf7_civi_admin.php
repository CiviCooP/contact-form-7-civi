<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

class cf7_civi_admin {
  const NONCE = 'cf7_civi_admin';


  protected static $initiated = false;

  public static function init() {
    if (!self::$initiated) {
      self::$initiated = true;
      add_action( 'admin_menu', array( 'cf7_civi_admin', 'admin_menu' ) );
      add_action( 'admin_enqueue_scripts', array('cf7_civi_admin', 'admin_enqueue_scripts') );
      add_action( 'wpcf7_save_contact_form', array('cf7_civi_admin', 'save_contact_form'));

      add_filter( 'wpcf7_editor_panels', array('cf7_civi_admin', 'panels'));
    }
  }

  public static function admin_menu() {
    add_options_page( __('CiviCRM Settings', 'cf7_civi'), __('CiviCRM Settings', 'cf7_civi'), 'manage_options', 'cf7_civi_admin', array( 'cf7_civi_admin', 'display_page' ) );
  }

  public static function get_page_url( $page = 'config' ) {

    $args = array( 'page' => 'cf7_civi_admin' );
    $url = add_query_arg( $args, admin_url( 'options-general.php' ) );

    return $url;
  }

  public static function display_page() {
    $host = cf7_civi_settings::getHost();
    $site_key = cf7_civi_settings::getSiteKey();
    $api_key = cf7_civi_settings::getApiKey();

    if (isset($_POST['host'])) {
      cf7_civi_settings::setHost($_POST['host']);
    }
    if (isset($_POST['site_key'])) {
      cf7_civi_settings::setSiteKey($_POST['site_key']);
    }
    if (isset($_POST['api_key'])) {
      cf7_civi_settings::setApiKey($_POST['api_key']);
    }
    cf7_civi_admin::view( 'settings', compact( 'host', 'site_key', 'api_key') );
  }

  public static function view( $name, array $args = array() ) {
    $args = apply_filters( 'cf7_civi_view_arguments', $args, $name );

    foreach ( $args AS $key => $val ) {
      $$key = $val;
    }

    load_plugin_textdomain( 'cf7_civi' );

    $file = CF7_CIVI__PLUGIN_DIR . 'views/'. $name . '.php';

    include( $file );
  }

  /**
   * Add a Civi setting panel to the contact form admin section.
   *
   * @param array $panels
   * @return array
   */
  public static function panels($panels) {
    $panels['cf7_civi'] = array(
      'title' => __( 'CiviCRM', 'cf7_civi' ),
      'callback' => array('cf7_civi_admin', 'civicrm_panel'),
    ) ;
    return $panels;
  }

  public static function civicrm_panel($post) {
    $civicrm = $post->prop('civicrm' );
    cf7_civi_admin::view('civicrm_panel', array('post' => $post, 'civicrm' => $civicrm));
  }

  public static function save_contact_form($contact_form) {
    $properties = $contact_form->get_properties();
    $civicrm = $properties['civicrm'];

    $civicrm['enable'] = true;

    if ( isset( $_POST['civicrm-entity'] ) ) {
      $civicrm['entity'] = trim( $_POST['civicrm-entity'] );
    }
    if ( isset( $_POST['civicrm-action'] ) ) {
      $civicrm['action'] = trim( $_POST['civicrm-action'] );
    }
    if ( isset( $_POST['civicrm-parameters'] ) ) {
      $civicrm['parameters'] = trim( $_POST['civicrm-parameters'] );
    }

    $properties['civicrm'] = $civicrm;
    $contact_form->set_properties($properties);
  }

  public static function admin_enqueue_scripts($hook_suffix) {
    if ( false === strpos( $hook_suffix, 'wpcf7' ) ) {
      return;
    }

    wp_enqueue_script( 'cf7_civi-admin',
      CF7_CIVI__PLUGIN_URL. 'js/admin.js',
      array( 'jquery', 'jquery-ui-tabs' )
    );
  }

}