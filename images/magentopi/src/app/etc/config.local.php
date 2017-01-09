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
  /**
   * 'The configuration file doesn\'t contain the sensitive data by security reason. The sensitive data can be stored in the next environment variables:
   * CONFIG__DEFAULT__DEV__RESTRICT__ALLOW_IPS for dev/restrict/allow_ips'
   */
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
      'dev' => 
      array (
        'restrict' => 
        array (
        ),
        'debug' => 
        array (
          'template_hints_storefront' => '0',
          'template_hints_admin' => '0',
          'template_hints_blocks' => '0',
        ),
        'template' => 
        array (
          'allow_symlink' => '0',
          'minify_html' => '1',
        ),
        'translate_inline' => 
        array (
          'active' => '0',
          'active_admin' => '0',
        ),
        'js' => 
        array (
          'enable_js_bundling' => '1',
          'merge_files' => '1',
          'minify_files' => '1',
        ),
        'css' => 
        array (
          'merge_css_files' => '1',
          'minify_files' => '0',
        ),
        'static' => 
        array (
          'sign' => '1',
        ),
      ),
    ),
  ),
  'i18n' => 
  array (
  )
);
