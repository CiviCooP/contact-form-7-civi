<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
?>
<h3><?php echo esc_html( __( 'CiviCRM Settings', 'cf7_civi' ) ); ?></h3>

<div class="contact-form-editor-box-civicrm">

  <p><label for="enable-civicrm"><input type="checkbox" id="enable-civicrm" name="enable-civicrm" class="toggle-form-table" value="1"<?php echo ( ! empty( $civicrm['enable'] ) ) ? ' checked="checked"' : ''; ?> /> <?php echo esc_html( __( 'Enable CIVICRM processing', 'cf7_civi' ) ); ?></label></p>

<fieldset>

  <legend><?php echo esc_html( __( "Fill in a CiviCRM API entity and action. E.g. Entity: Contact, action: create. Use parameters to add additional api parameters e.g. contact_type=Individual&source=wordpress", 'cf7_civi' ) ); ?></legend>

  <table class="form-table">
    <tbody>

    <tr>
      <th scope="row">
        <label for="entity"><?php echo esc_html( __( 'Entity', 'cf7_civi' ) ); ?></label>
      </th>
      <td>
        <input type="text" id="civicrm-entity" name="civicrm-entity" class="large-text code" size="70" value="<?php echo esc_attr( $civicrm['entity'] ); ?>" />
      </td>
    </tr>
    <tr>
      <th scope="row">
        <label for="action"><?php echo esc_html( __( 'Action', 'cf7_civi' ) ); ?></label>
      </th>
      <td>
        <input type="text" id="civicrm-action" name="civicrm-action" class="large-text code" size="70" value="<?php echo esc_attr( $civicrm['action'] ); ?>" />
      </td>
    </tr>
    <tr>
      <th scope="row">
        <label for="action"><?php echo esc_html( __( 'Additional parameters', 'cf7_civi' ) ); ?></label>
      </th>
      <td>
        <input type="text" id="civicrm-parameters" name="civicrm-parameters" class="large-text code" size="70" value="<?php echo esc_attr( $civicrm['parameters'] ); ?>" />
      </td>
    </tr>
    </tbody>
  </table>

</fieldset>

</div>