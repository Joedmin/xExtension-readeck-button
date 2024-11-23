<?php

class FreshExtension_readeckButton_Controller extends Minz_ActionController
{
  /** @var ReadeckButton\View */
  protected $view;

  public function jsVarsAction(): void
  {
    $extension = Minz_ExtensionManager::findExtension('Readeck Button');
    $this->view->readeck_button_vars = json_encode(array(
      'instance_url' => FreshRSS_Context::userConf()->attributeString('readeck_instance_url'),
      'keyboard_shortcut' => FreshRSS_Context::userConf()->hasParam("readeck_shortcut")
        ? FreshRSS_Context::userConf()->attributeString('readeck_shortcut')
        : '',
      'icons' => array(
        'added_to_readeck' => $extension->getFileUrl('added_to_readeck.svg', 'svg'),
      ),
      'i18n' => array(
        'added_article_to_readeck' => _t('ext.readeckButton.notifications.added_article_to_readeck', '%s'),
        'failed_to_add_article_to_readeck' => _t('ext.readeckButton.notifications.failed_to_add_article_to_readeck', '%s'),
        'ajax_request_failed' => _t('ext.readeckButton.notifications.ajax_request_failed'),
        'article_not_found' => _t('ext.readeckButton.notifications.article_not_found'),
      )
    ));

    $this->view->_layout(null);
    $this->view->_path('readeckButton/vars.js');

    header('Content-Type: application/javascript; charset=utf-8');
  }

  public function requestAccessAction(): void
  {
    $instance_url = Minz_Request::paramString('instance_url');
    $api_token = Minz_Request::paramString('api_token');

    // Handle leading slash
    if (substr($instance_url, -1) == '/')
    {
      $instance_url = substr($instance_url, 0, -1);
    }

    FreshRSS_Context::userConf()->_attribute('readeck_instance_url', $instance_url);
    FreshRSS_Context::userConf()->_attribute('readeck_api_token', $api_token);
    FreshRSS_Context::userConf()->save();

    $result = $this->curlGetRequest('/profile');
    if ($result['status'] == 200) {
      FreshRSS_Context::userConf()->_attribute('readeck_username', $result['response']->user->username);
      FreshRSS_Context::userConf()->save();

      $url_redirect = array('c' => 'extension', 'a' => 'configure', 'params' => array('e' => 'Readeck Button'));
      Minz_Request::good(_t('ext.readeckButton.notifications.authorized_success'), $url_redirect);
      return;
    }

    $url_redirect = array('c' => 'extension', 'a' => 'configure', 'params' => array('e' => 'Readeck Button'));
    Minz_Request::bad(_t('ext.readeckButton.notifications.request_access_failed', $result['status']), $url_redirect);
  }

  public function revokeAccessAction(): void
  {
    // TODO: remove in the next version
    $this->migrateConfigvariables();

    FreshRSS_Context::userConf()->_attribute('readeck_instance_url');
    FreshRSS_Context::userConf()->_attribute('readeck_api_token');
    FreshRSS_Context::userConf()->_attribute('readeck_username');
    FreshRSS_Context::userConf()->save();

    $url_redirect = array('c' => 'extension', 'a' => 'configure', 'params' => array('e' => 'Readeck Button'));
    Minz_Request::forward($url_redirect);
  }

  // TODO: remove this method after the next version - all users should've already been migrated, hopefuly
  private function migrateConfigvariables(): void
  {
    $migrated = FreshRSS_Context::userConf()->hasParam('readeck_migrated');
    if ($migrated) {
      // Already migrated.
      return;
    }

    $username = FreshRSS_Context::userConf()->attributeString('username');
    $keyboard_shortcut = FreshRSS_Context::userConf()->attributeString('keyboard_shortcut');
    $instance_url = FreshRSS_Context::userConf()->attributeString('instance_api_url');
    $api_token = FreshRSS_Context::userConf()->attributeString('api_token');

    if (!isset($username) && !isset($instance_url) && !isset($api_token)) {
      // Already migrated.
      FreshRSS_Context::userConf()->_attribute('readeck_migrated', true);
      FreshRSS_Context::userConf()->save();
      return;
    }

    // Migrate to base url
    if (substr($instance_url, -1) == '/')
    {
      $instance_url = substr($instance_url, 0, -1);
    }
    if (substr($instance_url, -4) == '/api')
    {
      $instance_url = substr($instance_url, 0, -4);
    }

    // Save them with preix
    FreshRSS_Context::userConf()->_attribute('readeck_username', $username);
    FreshRSS_Context::userConf()->_attribute('readeck_instance_url', $instance_url);
    FreshRSS_Context::userConf()->_attribute('readeck_api_token', $api_token);

    // Remove old variables
    FreshRSS_Context::userConf()->_attribute('username');
    FreshRSS_Context::userConf()->_attribute('instance_api_url');
    FreshRSS_Context::userConf()->_attribute('api_token');

    if (isset($keyboard_shortcut)) {
      FreshRSS_Context::userConf()->_attribute('readeck_shortcut', $keyboard_shortcut);
      FreshRSS_Context::userConf()->_attribute('keyboard_shortcut');
    }
    else {
      // Does not matter, user might have not even set it
    }

    // Migration complete
    FreshRSS_Context::userConf()->_attribute('readeck_migrated', true);
    FreshRSS_Context::userConf()->save();
  }

  public function addAction(): void
  {
    $this->view->_layout(null);

    $entry_id = Minz_Request::paramString('id');
    $entry_dao = FreshRSS_Factory::createEntryDao();
    $entry = $entry_dao->searchById($entry_id);

    if ($entry === null) {
      echo json_encode(array('status' => 404));
      return;
    }

    $post_data = array(
      'url' => $entry->link(),
    );

    // TODO: remove in the next 2 versions
    $this->migrateConfigvariables();

    $result = $this->curlPostRequest('/bookmarks', $post_data);
    $result['response'] = array('title' => $entry->title());

    echo json_encode($result);
  }

  /**
   * @return array<string>
   */
  private function getRequestHeaders(): array
  {
    $api_token = FreshRSS_Context::userConf()->attributeString('readeck_api_token');
    return array(
      'Content-Type: application/json; charset=UTF-8',
      'X-Accept: application/json',
      "Authorization: Bearer " . $api_token,
    );
  }

  /**
   * @return \CurlHandle
   */
  private function getCurlBase(string $url): \CurlHandle
  {
    $headers = $this->getRequestHeaders();
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    return $curl;
  }

  /**
   * @return array<string,mixed>
   */
  private function curlGetRequest(string $endpoint): array
  {
    $instance_url = FreshRSS_Context::userConf()->attributeString('readeck_instance_url');
    $curl = $this->getCurlBase($instance_url . "/api" . $endpoint);

    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');

    $response = curl_exec($curl);
    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $response_header = substr($response, 0, $header_size);
    $response_body = substr($response, $header_size);
    $response_headers = $this->httpHeaderToArray($response_header);

    return array(
      'response' => json_decode($response_body),
      'status' => curl_getinfo($curl, CURLINFO_HTTP_CODE),
      'errorCode' => isset($response_headers['x-error-code']) ? intval($response_headers['x-error-code']) : curl_getinfo($curl, CURLINFO_HTTP_CODE)
    );
  }

  /**
   * @param array<string,mixed> $post_data
   * @return array<string,mixed>
   */
  private function curlPostRequest(string $endpoint, array $post_data): array
  {
    $instance_url = FreshRSS_Context::userConf()->attributeString('readeck_instance_url');
    $curl = $this->getCurlBase($instance_url . "/api" . $endpoint);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));

    $response = curl_exec($curl);

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $response_header = substr($response, 0, $header_size);
    $response_body = substr($response, $header_size);
    $response_headers = $this->httpHeaderToArray($response_header);

    return array(
      'response' => json_decode($response_body),
      'status' => curl_getinfo($curl, CURLINFO_HTTP_CODE),
      'errorCode' => isset($response_headers['x-error-code'])
        ? intval($response_headers['x-error-code'])
        : curl_getinfo($curl, CURLINFO_HTTP_CODE)
    );
  }

   /**
   * @return array<string,string>
   */
  private function httpHeaderToArray(string $header): array
  {
    $headers = array();
    $headers_parts = explode("\r\n", $header);

    foreach ($headers_parts as $header_part) {
      // skip empty header parts
      if (strlen($header_part) <= 0) {
        continue;
      }

      // Filter the beginning of the header which is the basic HTTP status code
      if (strpos($header_part, ':')) {
        $header_name = substr($header_part, 0, strpos($header_part, ':'));
        $header_value = substr($header_part, strpos($header_part, ':') + 1);
        $headers[$header_name] = trim($header_value);
      }
    }

    return $headers;
  }
}
