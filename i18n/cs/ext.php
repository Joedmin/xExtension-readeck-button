<?php

return array(
  'readeckButton' => array(
    'configure' => array(
      'api_token' => 'Token API',
      'api_token_description' => '<ul class="rb_listedNumbers">
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
      'connected_to_readeck' => 'Jste připojeni jako <b>%s</b> k Readecku na adrese <b>%s</b>.',
      'revoke_access' => 'Odpojit se od Readecku!',
      'save_changes' => 'Uložit',
      'button_location' => 'Pozice tlačítka. Klávesová skratka funguje i v případě možnosti \'Skryté\'.',
      'button_location_header_bottom' => 'Horní i spodní řádek',
      'button_location_header' => 'Horní řádek',
      'button_location_bottom' => 'Spodní řádek',
      'button_location_hidden' => 'Skryté',
      'behavior' => 'Chování',
      'behavior_smart' => 'Inteligentní',
      'behavior_link' => 'Odkaz',
      'behavior_content' => 'Obsah',
      'behavior_description' => '<li><b>Inteligentní</b> (výchozí) - přepíná mezi starým a novým chováním na základě toho, zda je nastavena autentizace kanálu</li>
      <li><b>Odkaz</b> (staré chování) - odešle pouze odkaz na článek a nechá Readeck načíst obsah</li>
      <li><b>Obsah</b> (nové chování) - přímo odešle obsah ze zdroje do Readeck. Užitečné, když jsou články za paywallem, ale ve zdroji jsou kompletní.</li>'
    ),
    'notifications' => array(
      'added_article_to_readeck' => 'Úspěšně přidán <i>\'%s\'</i> do Readecku!',
      'failed_to_add_article_to_readeck' => 'Přidání článku na Readeck se nezdařilo! Kód chyby Readeck API: %s',
      'ajax_request_failed' => 'Požadavek Ajax selhal!',
      'authorized_success' => 'Autorizace proběhla úspěšně!',
      'authorized_aborted' => 'Autorizace přerušena!',
      'authorized_failed' => 'Autorizace selhala! Chyba Readeck API: %s',
      'relog_required' => 'Je nutné provést opětovné přihlášení na Readeck! Odhlaste se a znovu přihlaste v nastavení rozšíření.',
      'request_access_failed' => 'Žádost o přístup se nezdařila! Kód chyby Readeck API: %s',
      'article_not_found' => 'Nelze najít článek!',
      'authorization_revoked' => 'Autorizace úspěšně zrušena!',
      'changes_saved_sucessfully' => "Změny úspěšně uloženy!",
      'changes_failed' => "Nepovedlo se uložit změny! Hodnota '%s' není podporována!",
    )
  ),
);
