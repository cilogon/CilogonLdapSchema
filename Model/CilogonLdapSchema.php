<?php
/**
 * COmanage Registry Cilogon Ldap Schema Model
 *
 * Copyright (C) 2016 Spherical Cow Group
 * 
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software distributed under
 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 *
 * @copyright     Copyright (C) 2016 Spherical Cow Group
 * @link          http://www.internet2.edu/comanage COmanage Project
 * @package       registry-plugin
 * @since         COmanage Registry v2.0.0
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @version       $Id$
 */

class CilogonLdapSchema extends AppModel {

  // LDAP schema CILogonPerson
  public $attributes = array(
    'CILogonPerson' => array(
      'objectclass' => array(
        'required' => true
      ),
      'attributes' => array(
        'CILogonPersonMediaWikiUsername' => array(
          'required' => false,
          'multiple' => false,
          'extendedtype' => 'identifier_types',
          'defaulttype' => 'mediawikiusername'
        )
      )
    )
  );

  // Required by COmanage Plugins
  public $cmPluginType = "ldapschema";
  
  // Document foreign keys
  public $cmPluginHasMany = array();

  /**
  * Assemble attributes to write. Required for LDAP schema plugin.
  * 
  * @since COmanage Registry 2.0.0
  * @param array $configuredAttributes Array of configured attributes
  * @param array $provisioningData Array of provisioning data
  * @return array Array of attribute names and values to write
  */
  public function assemblePluginAttributes($configuredAttributes, $provisioningData) {
    $attrs = array();

    foreach($configuredAttributes as $attr => $cfg) {
      switch($attr) {
        case 'CILogonPersonMediaWikiUsername':
          $attrs[$attr] = array();
          foreach($provisioningData['Identifier'] as $m) {
            if(isset($m['type'])
               && $m['type'] == $cfg['type']
               && $m['status'] == StatusEnum::Active) {
              $attrs[$attr] = $m['identifier'];
              break;
            }
          }
          break;
        // else we don't know what this attribute is
      }
    }
     
    return $attrs;
  }

  /**
   * Expose menu items.
   * 
   * @ since COmanage Registry v0.9.2
   * @ return Array with menu location type as key and array of labels, controllers, actions as values.
   */
  public function cmPluginMenus() {
    return array();
  }
}
