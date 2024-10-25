<?php

return array(
  'readeckButton' => array(
    'configure' => array(
      'api_token' => 'Token API',
      'api_token_description' => '<ul class="listedNumbers">
        <li>Navigujte do své Readeck instance na \'<c><vaše_instance>/profile/tokens</c>\'</li>
        <li>Vytvořte nový token API minimálně s oprávněním \'<c>Záložky : Pouze k zápisu</c>\'</li>
        <li>Zadejte URL své Readeck instance a token API a klikněte na \'Připojit se k Readecku\'</li>
      </ul>
      <span>Podrobnosti naleznete na <a href="https://github.com/Joedmin/freshrss-readeck-button" target="_blank">GitHubu</a>!',
      'connect_to_readeck' => 'Připojit se k Readecku',
      'username' => 'Uživatelské jméno',
      'instance_url' => 'URL adresa instance Readeck',
      'keyboard_shortcut' => ' Klávesová zkratka',
      'extension_disabled' => 'Před připojením ke službě Readeck je nutné rozšíření povolit!',
      'connected_to_readeck' => 'Jste připojeni k Readecku skrze účet <b>%s</b> zapomocí API tokenu <b>%s</b> na adrese <b>%s</b>.',
      'revoke_access' => 'Odpojit se od Readecku!'
    ),
    'notifications' => array(
      'added_article_to_readeck' => 'Úspěšně přidán <i>\'%s\'</i> do Readecku!',
      'failed_to_add_article_to_readeck' => 'Přidání článku na Readeck se nezdařilo! Kód chyby Readeck API: %s',
      'ajax_request_failed' => 'Požadavek Ajax selhal!',
      'authorized_success' => 'Autorizace proběhla úspěšně!',
      'authorized_aborted' => 'Autorizace přerušena!',
      'authorized_failed' => 'Autorizace selhala! Chyba Readeck API: %s',
      'request_access_failed' => 'Žádost o přístup se nezdařila! Kód chyby Readeck API: %s',
      'article_not_found' => 'Nelze najít článek!',
    )
  ),
);
