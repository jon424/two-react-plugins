<?php /* Template Name: EmptyReactPageTemplate */ ?>
<?php global $post;
/**
 * Load react app files im page should contain a react app.
 * (C) Ben Broide https://medium.com/swlh/wordpress-create-react-app-integration-30b41657b79e
 * 
 * @return bool|void
 * @since 1.0.0
 */

function repr_write_react_app_into_template() {
  // Only load react app scripts on pages that contain our apps
  global $post;
  $repr_apps = get_option('repr_apps') ?? [];
  $pageslugs = $repr_apps ? array_map(fn ($el) => $el['pageslugs'], $repr_apps) : [];

  $valid_pages = array_merge(...$pageslugs);
  $document_root = $_SERVER['DOCUMENT_ROOT'] ?? '';
  if (is_page() && in_array($post->post_name, $valid_pages)) {

    // Setting path variables.
    $current_app = array_values(array_filter($repr_apps, fn ($el) => in_array($post->post_name, $el['pageslugs'])))[0];
    $appname = $current_app['appname'];
    $plugin_app_dir_url = escapeshellcmd(REPR_APPS_URL . "/{$appname}/");

    // Use fallback if $_SERVER['DOCUMENT_ROOT'] is not set
    $relative_apppath = escapeshellcmd("/wp-content/reactpress/apps/{$appname}/");
    if (strpos($plugin_app_dir_url, $document_root) === 0) {
      // Add check to ensure that the document root and plugin app dir live on the same disk
      $relative_apppath = explode($document_root, $plugin_app_dir_url)[1];
    }

    $react_app_build = $plugin_app_dir_url . 'build/';
    $manifest_url = $react_app_build . 'asset-manifest.json';

    // Request manifest file.
    set_error_handler(
      // Needed to surpress pontetial errors in file_get_contents and make try/catch
      // usable for php errors - which are much older than exceptions.
      function ($severity, $message, $file, $line) {
        throw new \ErrorException($message, $severity, $severity, $file, $line);
      }
    );
    $request = false;
    try {
      $request = file_get_contents($manifest_url);
    } catch (\Exception $e) {
      repr_log($e->getMessage());
    }
    // remove error handler again.
    restore_error_handler();

    // If the remote request fails, return.
    if (!$request)
      return false;

    // Convert json to php array.
    $files_data = json_decode(strval($request));
    if ($files_data === null)
      return;


    if (!property_exists($files_data, 'entrypoints'))
      return false;

    // Get assets links.
    $assets_files = $files_data->entrypoints;

    // We use array_values to reindex the array (because PHP)
    $js_files = array_values(array_filter(
      $assets_files,
      fn ($file_string) => pathinfo($file_string, PATHINFO_EXTENSION) === 'js'
    ));
    $css_files = array_filter(
      $assets_files,
      fn ($file_string) => pathinfo($file_string, PATHINFO_EXTENSION) === 'css'
    );

    // Load css files.
    foreach ($css_files as $index => $css_file) {
      echo '<link href="' . $plugin_app_dir_url . 'build/' . $css_file . '" rel="stylesheet"></link>';
    }

    // Load js files.
    foreach ($js_files as $index => $js_file) {
      echo '<script defer="defer" src="' . $plugin_app_dir_url . 'build/' . $js_file . '"></script>';
    }

    // Variables for app use
    echo '<script> 
            var reactPress = ' . wp_json_encode(
      [
        'api' => [
          'nonce' => wp_create_nonce('wp_rest'),
          'rest_url' => esc_url_raw(rest_url()),

        ],
        'user' => wp_get_current_user(),
        'usermeta' => get_user_meta(
          get_current_user_id()
        ),
      ]
    ) . '</script>';
  }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="icon" href="/favicon.ico" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="theme-color" content="#000000" />
  <meta name="description" content="Web site created using create-react-app" />
  <link rel="apple-touch-icon" href="<?php echo wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full')[0] ?? '/favicon.ico'; ?>" />
  <link rel="manifest" href="/<?php echo $post->post_name; ?>/manifest.json" />
  <title><?php echo $post->post_title; ?></title>

  <?php repr_write_react_app_into_template() ?>
</head>

<body>
  <noscript>You need to enable JavaScript to run this app.</noscript>

  <?php the_content(); ?>
  <!--
      This HTML file is a template.
      If you open it directly in the browser, you will see an empty page.

      You can add webfonts, meta tags, or analytics to this file.
      The build step will place the bundled scripts into the <body> tag.

      To begin the development, run `npm start` or `yarn start`.
      To create a production bundle, use `npm run build` or `yarn build`.
    -->
</body>

</html>