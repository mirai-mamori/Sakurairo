<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly. ?>

<p>Welcome to the exciting world of Codestar Framework. Built in Object Oriented Programming paradigm with high number of custom fields and tons of options. Allows you to bring custom admin, metabox, taxonomy and customize settings to all of your pages, posts and categories. It's highly modern and advanced framework.</p>

<div class="csf-welcome-cols">

  <div class="csf--col csf--col-first">
    <span class="csf--icon csf--active"><i class="fas fa-check"></i></span>
    <div class="csf--title">Admin Option Framework</div>
    <p class="csf--text">Built in Object Oriented Programming paradigm with high number of custom fields and tons of options. It's highly modern and advanced framework.</p>
  </div>

  <div class="csf--col csf--col-first">
    <span class="csf--icon csf--<?php echo esc_attr( CSF::$premium ? 'active' : 'deactive' ); ?>"><i class="fas fa-<?php echo esc_attr( CSF::$premium ? 'check' : 'times' ); ?>"></i></span>
    <div class="csf--title">Customize Option Framework</div>
    <p class="csf--text">Inherits the default WordPress Customizer with integration of own custom fields. It's more powerful to customize your site on live.</p>
  </div>

  <div class="csf--col csf--col-first csf--last">
    <span class="csf--icon csf--<?php echo esc_attr( CSF::$premium ? 'active' : 'deactive' ); ?>"><i class="fas fa-<?php echo esc_attr( CSF::$premium ? 'check' : 'times' ); ?>"></i></span>
    <div class="csf--title">Metabox Option Framework</div>
    <p class="csf--text">Allows you to bring custom metabox settings to all of your pages and posts. We provide advanced settings with numerious number of fields.</p>
  </div>

  <div class="clear"></div>

  <div class="csf--col csf--col-first">
    <span class="csf--icon csf--<?php echo esc_attr( CSF::$premium ? 'active' : 'deactive' ); ?>"><i class="fas fa-<?php echo esc_attr( CSF::$premium ? 'check' : 'times' ); ?>"></i></span>
    <div class="csf--title">Nav Menu Option Framework</div>
    <p class="csf--text">Allows you to bring custom nav menu item settings to all of your menus. We provide advanced settings with numerious number of fields.</p>
  </div>

  <div class="csf--col csf--col-first">
    <span class="csf--icon csf--<?php echo esc_attr( CSF::$premium ? 'active' : 'deactive' ); ?>"><i class="fas fa-<?php echo esc_attr( CSF::$premium ? 'check' : 'times' ); ?>"></i></span>
    <div class="csf--title">Taxonomy Option Framework</div>
    <p class="csf--text">Allows you to bring custom taxonomy settings to all of your categories, tags or CPT. We provide advanced settings with numerious number of fields.</p>
  </div>

  <div class="csf--col csf--col-first csf--last">
    <span class="csf--icon csf--<?php echo esc_attr( CSF::$premium ? 'active' : 'deactive' ); ?>"><i class="fas fa-<?php echo esc_attr( CSF::$premium ? 'check' : 'times' ); ?>"></i></span>
    <div class="csf--title">Profile Option Framework</div>
    <p class="csf--text">Allows you to bring custom user profile settings to all of users. We provide advanced settings with numerious number of fields.</p>
  </div>

  <div class="clear"></div>

  <div class="csf--col">
    <span class="csf--icon csf--<?php echo esc_attr( CSF::$premium ? 'active' : 'deactive' ); ?>"><i class="fas fa-<?php echo esc_attr( CSF::$premium ? 'check' : 'times' ); ?>"></i></span>
    <div class="csf--title">Widget Option Framework</div>
    <p class="csf--text">Allows you to creating custom widgets. We provide advanced settings wtih numerious number of fields.</p>
  </div>

  <div class="csf--col">
    <span class="csf--icon csf--<?php echo esc_attr( CSF::$premium ? 'active' : 'deactive' ); ?>"><i class="fas fa-<?php echo esc_attr( CSF::$premium ? 'check' : 'times' ); ?>"></i></span>
    <div class="csf--title">Comment Option Framework</div>
    <p class="csf--text">Allows you to bring custom comment metabox settings to all of comments. We provide advanced settings wtih numerious number of fields.</p>
  </div>

  <div class="csf--col csf--last">
    <span class="csf--icon csf--<?php echo esc_attr( CSF::$premium ? 'active' : 'deactive' ); ?>"><i class="fas fa-<?php echo esc_attr( CSF::$premium ? 'check' : 'times' ); ?>"></i></span>
    <div class="csf--title">Shortcode Option Framework</div>
    <p class="csf--text">Comes with pre-built shortcode editor to manage your content. It's easy and flexible to build unlimited layouts with endless possibilites.</p>
  </div>

  <?php if ( ! CSF::$premium ) { ?>
  <div class="clear"></div>
  <div class="csf--col-upgrade">
    <a href="http://codestarframework.com/" class="button button-primary" target="_blank" rel="nofollow"><i class="fas fa-share"></i> Upgrade Premium Version</a>
  </div>
  <?php } ?>

  <div class="clear"></div>
</div>

<hr />

<div class="csf-features-cols csf--col-wrap">
  <div class="csf--col csf--key-features">

  <h4>Key Features</h4>

  <ul>
    <li>WordPress 5.6.x Ready</li>
    <li>Gutenberg Ready</li>
    <li>Multiple instances</li>
    <li>Unlimited frameworks</li>
    <li>Output css styles</li>
    <li>Output typography</li>
    <li>Advanced option fields</li>
    <li>Fields dependencies based on rules</li>
    <li>Sanitize and validate fields</li>
    <li>Ajax saving</li>
    <li>Localization</li>
    <li>Useful hooks for configurations</li>
    <li>Export and import options</li>
    <li>and much more...</li>
  </ul>

  </div>

  <div class="csf--col csf--available-fields">

  <h4>Available Fields</h4>

  <table class="csf--table-fields fixed widefat">
    <tbody>
      <tr>
        <td>text</td>
        <td>accordion</td>
        <td>background</td>
        <td>backup</td>
        <td>icon</td>
      </tr>
      <tr>
        <td>textarea</td>
        <td>repeater</td>
        <td>heading</td>
        <td>date</td>
        <td>code_editor</td>
      </tr>
      <tr>
        <td>checkbox</td>
        <td>group</td>
        <td>image_select</td>
        <td>slider</td>
        <td>content</td>
      </tr>
      <tr>
        <td>select</td>
        <td>gallery</td>
        <td>notice</td>
        <td>fieldset</td>
        <td>typography</td>
      </tr>
      <tr>
        <td>switcher</td>
        <td>sorter</td>
        <td>link_color</td>
        <td>subheading</td>
        <td>upload</td>
      </tr>
      <tr>
        <td>color</td>
        <td>media</td>
        <td>radio</td>
        <td>tabbed</td>
        <td>wp_editor</td>
      </tr>
      <tr>
        <td>spacing</td>
        <td>border</td>
        <td>palette</td>
        <td>spinner</td>
        <td>dimensions</td>
      </tr>
      <tr>
        <td>link_color</td>
        <td>sortable</td>
        <td>button_set</td>
        <td>accordion</td>
        <td>others</td>
      </tr>
    </tbody>
  </table>

  <p>and more on the way...</p>

  </div>

  <div class="clear"></div>
</div>

<?php if ( CSF::$premium ) { ?>
<hr />
<h5>You can force to disable this page with (it would works for only premium users):</h5>
<div class="csf-code-block">
<pre>
add_filter( 'csf_welcome_page', '__return_false' );
</pre>
</div>
<?php } ?>
