<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

class cf7_civi_settings {

  public static function getHost() {
    return get_option('cf7_civi_host');
  }

  public static function setHost($host) {
    update_option( 'cf7_civi_host', $host );
  }

  public static function getSiteKey() {
    return get_option('cf7_civi_site_key');
  }

  public static function setSiteKey($key) {
    update_option( 'cf7_civi_site_key', $key );
  }

  public static function getApiKey() {
    return get_option('cf7_civi_api_key');
  }

  public static function setApiKey($key) {
    update_option( 'cf7_civi_api_key', $key );
  }

}