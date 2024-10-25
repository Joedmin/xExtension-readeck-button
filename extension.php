<?php

class ReadeckButtonExtension extends Minz_Extension
{
  #[\Override]
  public function init()
  {
    $this->registerTranslates();

    Minz_View::appendScript($this->getFileUrl('script.js', 'js'), false, false, false);
    Minz_View::appendStyle($this->getFileUrl('style.css', 'css'));
    Minz_View::appendScript(strval(_url('readeckButton', 'jsVars')), false, true, false);

    $this->registerController('readeckButton');
    $this->registerViews();
  }

  #[\Override]
  public function handleConfigureAction()
  {
    $this->registerTranslates();
    if (Minz_Request::isPost()) {
      $keyboard_shortcut = Minz_Request::paramString('keyboard_shortcut');
      FreshRSS_Context::userConf()->_attribute('readeck_shortcut', $keyboard_shortcut);
      FreshRSS_Context::userConf()->save();
    }
  }
}
