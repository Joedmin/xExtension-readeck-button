<?php

return array(
  'readeckButton' => array(
    'configure' => array(
      'api_token' => 'API token',
      'api_token_description' => '<ul class="listedNumbers">
        <li>Go to your Readeck instance and navigate to \'<c><your_readeck_instance>/profile/tokens</c>\'</li>
        <li>Create a new API token with at least the \'<c>Bookmarks : Write Only</c>\' permission</li>
        <li>Enter your Readeck instance url and API token and hit \'Connect to Readeck\'</li>
      </ul>
      <span>Details can be found on <a href="https://github.com/Joedmin/freshrss-readeck-button" target="_blank">GitHub</a>!',
      'connect_to_readeck' => 'Connect to Readeck',
      'username' => 'Username',
      'instance_url' => 'Readeck instance url',
      'keyboard_shortcut' => 'Keyboard shortcut',
      'extension_disabled' => 'You need to enable the extension before you can connect to Readeck!',
      'connected_to_readeck' => 'You are connected to Readeck with the account <b>%s</b> using the access token <b>%s</b> at <b>%s</b>.',
      'revoke_access' => 'Disconnect from Readeck!'
    ),
    'notifications' => array(
      'added_article_to_readeck' => 'Successfully added <i>\'%s\'</i> to Readeck!',
      'failed_to_add_article_to_readeck' => 'Adding article to Readeck failed! Readeck API error code: %s',
      'ajax_request_failed' => 'Ajax request failed!',
      'authorized_success' => 'Authorization successful!',
      'authorized_aborted' => 'Authorization aborted!',
      'authorized_failed' => 'Authorization failed! Readeck API error code: %s',
      'request_access_failed' => 'Access request failed! Readeck API error code: %s',
      'article_not_found' => 'Can\'t find article!',
    )
  ),
);
