<?php
return array (
  'scopes' => 
  array (
    'websites' => 
    array (
      'admin' => 
      array (
        'website_id' => '0',
        'code' => 'admin',
        'name' => 'Admin',
        'sort_order' => '0',
        'default_group_id' => '0',
        'is_default' => '0',
      ),
      'base' => 
      array (
        'website_id' => '1',
        'code' => 'base',
        'name' => 'Main Website',
        'sort_order' => '0',
        'default_group_id' => '1',
        'is_default' => '1',
      ),
    ),
    'groups' => 
    array (
      0 => 
      array (
        'group_id' => '0',
        'website_id' => '0',
        'name' => 'Default',
        'root_category_id' => '0',
        'default_store_id' => '0',
      ),
      1 => 
      array (
        'group_id' => '1',
        'website_id' => '1',
        'name' => 'Main Website Store',
        'root_category_id' => '2',
        'default_store_id' => '1',
      ),
    ),
    'stores' => 
    array (
      'admin' => 
      array (
        'store_id' => '0',
        'code' => 'admin',
        'website_id' => '0',
        'group_id' => '0',
        'name' => 'Admin',
        'sort_order' => '0',
        'is_active' => '1',
      ),
      'default' => 
      array (
        'store_id' => '1',
        'code' => 'default',
        'website_id' => '1',
        'group_id' => '1',
        'name' => 'Default Store View',
        'sort_order' => '0',
        'is_active' => '1',
      ),
    ),
  ),
  'system' => 
  array (
    'default' => 
    array (
      'web' => 
      array (
        'unsecure' => 
        array (
          'base_url' => 'http://pi.choukalos.org/',
        ),
      ),
      'general' => 
      array (
        'region' => 
        array (
          'display_all' => '1',
          'state_required' => 'AT,BR,CA,CH,EE,ES,FI,LT,LV,RO,US',
        ),
      ),
      'catalog' => 
      array (
        'category' => 
        array (
          'root_id' => '2',
        ),
      ),
    ),
  ),
  'i18n' => 
  array (
  )
);
