<?php

class FreshExtension_readeckButton_Controller extends Minz_ActionController
{
  /** @var ReadeckButton\View */
  protected $view;

  public function jsVarsAction(): void
  {
    $extension = Minz_ExtensionManager::findExtension('Readeck Button');
    $this->view->readeck_button_vars = json_encode(array(
      'instance_api_url' => FreshRSS_Context::userConf()->attributeString('instance_api_url'),
      'keyboard_shortcut' => FreshRSS_Context::userConf()->attributeString('keyboard_shortcut'),
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

    $this->view->_layout(false);
    $this->view->_path('readeckButton/vars.js');

    header('Content-Type: application/javascript; charset=utf-8');
  }

  public function requestAccessAction(): void
  {
    $api_token = Minz_Request::paramString('api_token');
    $instance_api_url = Minz_Request::paramString('instance_api_url');
    FreshRSS_Context::userConf()->_attribute('instance_api_url', $instance_api_url);
    FreshRSS_Context::userConf()->_attribute('api_token', $api_token);
    FreshRSS_Context::userConf()->save();

    $result = $this->curlGetRequest($instance_api_url . '/profile', $api_token);
    if ($result['status'] == 200) {
      FreshRSS_Context::userConf()->_attribute('username', $result['response']->user->username);
      FreshRSS_Context::userConf()->_attribute('instance_api_url', $instance_api_url);
      FreshRSS_Context::userConf()->_attribute('api_token', $api_token);
      FreshRSS_Context::userConf()->save();

      $url_redirect = array('c' => 'extension', 'a' => 'configure', 'params' => array('e' => 'Readeck Button'));
      Minz_Request::good(_t('ext.readeckButton.notifications.authorized_success'), $url_redirect);
      exit();
    }

    $url_redirect = array('c' => 'extension', 'a' => 'configure', 'params' => array('e' => 'Readeck Button'));
    Minz_Request::bad(_t('ext.readeckButton.notifications.request_access_failed', $result['status']), $url_redirect);
  }

  public function revokeAccessAction(): void
  {
    FreshRSS_Context::userConf()->instance_api_url = '';
    FreshRSS_Context::userConf()->api_token = '';
    FreshRSS_Context::userConf()->username = '';
    FreshRSS_Context::userConf()->save();

    $url_redirect = array('c' => 'extension', 'a' => 'configure', 'params' => array('e' => 'Readeck Button'));
    Minz_Request::forward($url_redirect);
  }

  public function addAction(): void
  {
    $this->view->_layout(false);

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

    $api_token = FreshRSS_Context::userConf()->api_token;
    $instance_api_url = FreshRSS_Context::userConf()->instance_api_url;

    $result = $this->curlPostRequest($instance_api_url . "/bookmarks", $post_data, $api_token);
    $result['response'] = array('title' => $entry->title());

    echo json_encode($result);
  }

  /**
   * @param string $api_token
   * @return array<string>
   */
  private function getRequestHeaders($api_token): array
  {
    return array(
      'Content-Type: application/json; charset=UTF-8',
      'X-Accept: application/json',
      "Authorization: Bearer " . $api_token
    );
  }

  /**
   * @param string $url
   * @param string $api_token
   * @return \CurlHandle
   */
  private function getCurlBase($url, $api_token): \CurlHandle
  {
    $headers = $this->getRequestHeaders($api_token);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    return $curl;
  }

  /**
   * @param string $url
   * @param string $api_token
   * @return array<string,mixed>
   */
  private function curlGetRequest($url, $api_token): array
  {
    $curl = $this->getCurlBase($url, $api_token);
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
   * @param string $url
   * @param array<string,mixed> $post_data
   * @param string $api_token
   * @return array<string,mixed>
   */
  private function curlPostRequest($url, $post_data, $api_token): array
  {
    $curl = $this->getCurlBase($url, $api_token);
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
      'errorCode' => isset($response_headers['x-error-code']) ? intval($response_headers['x-error-code']) : curl_getinfo($curl, CURLINFO_HTTP_CODE)
    );
  }

   /**
   * @param string $header
   * @return array<string,string>
   */
  private function httpHeaderToArray($header): array
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
