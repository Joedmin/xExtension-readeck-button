<?php

class ReadeckButtonExtension extends Minz_Extension
{
  #[\Override]
  public function init()
  {
    $this->registerTranslates();

    Minz_View::appendScript($this->getFileUrl('script.js'), false, false, false);
    Minz_View::appendStyle($this->getFileUrl('style.css'));
    Minz_View::appendScript(strval(_url('readeckButton', 'jsVars')), false, true, false);

    $this->registerController('readeckButton');
    $this->registerViews();
  }

  #[\Override]
  public function handleConfigureAction()
  {
    $this->registerTranslates();
    if (!Minz_Request::isPost()) {
      return;
    }

    $keyboard_shortcut = Minz_Request::paramString('readeck_shortcut');
    FreshRSS_Context::userConf()->_attribute('readeck_shortcut', $keyboard_shortcut);
    FreshRSS_Context::userConf()->save();

    $button_location = Minz_Request::paramString('readeck_button_location');
    $url_redirect = array('c' => 'extension', 'a' => 'configure', 'params' => array('e' => 'Readeck Button'));

    switch ($button_location) {
      case "header_bottom":
      case "header":
      case "bottom":
      case "hidden":
        FreshRSS_Context::userConf()->_attribute('readeck_button_location', $button_location);
        FreshRSS_Context::userConf()->save();

        Minz_Request::good(_t('ext.readeckButton.notifications.changes_saved_sucessfully'), $url_redirect);
        return;
      default:
        Minz_Request::bad(_t('ext.readeckButton.notifications.changes_failed', $button_location), $url_redirect);
    }
  }

  /**
   * @method bool isConfigured()
   */
  public function isConfigured(): bool
  {
    return FreshRSS_Context::userConf()->attributeString('readeck_api_token') != '';
  }

  /**
   * @method bool shouldBeShown()
   */
  public function shouldBeShown(string $entryName): bool
  {
    $headerLocation = FreshRSS_Context::userConf()->attributeString('readeck_button_location');

    // TO BE REMOVED:
    // Update missing entry after update
    if ($headerLocation == "") {
      FreshRSS_Context::userConf()->_attribute('readeck_button_location', "header_bottom");
      FreshRSS_Context::userConf()->save();
      return true;
    }

    if ($headerLocation == "hidden") {
      return false;
    } else if ($headerLocation == "header_bottom") {
      return true;
    }
    return $entryName == $headerLocation;
  }
}
