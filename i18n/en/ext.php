<?php

return array(
  'readeckButton' => array(
    'configure' => array(
      'api_token' => 'API token',
      'api_token_description' => '<ul class="rb_listedNumbers">
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
      'connected_to_readeck' => 'You are connected as <b>%s</b> to Readeck at <b>%s</b>.',
      'revoke_access' => 'Disconnect from Readeck!',
      'save_changes' => 'Save',
      'button_location' => 'Readeck button possition. The keyboard shortcut works even when the \'Hidden\' option is selected.',
      'button_location_header_bottom' => 'Top and bottom line',
      'button_location_header' => 'Top line',
      'button_location_bottom' => 'Bottom line',
      'button_location_hidden' => 'Hidden',
      'behavior' => "Behavior",
      'behavior_smart' => "Smart",
      'behavior_link' => "Link",
      'behavior_content' => "Content",
      'behavior_description' => '<ul>
      <li><b>Smart</b> (default) - toggles between old and new behavior based on if feed authentication is set up</li>
      <li><b>Link</b> (old behavior) - sends only article link and lets Readeck fetch the content</li>
      <li><b>Content</b> (new behavior) - directly sends the content from the feed to Readeck. Useful when articles are behind a paywall but complete in the feed.</li></ul>'
    ),
    'notifications' => array(
      'added_article_to_readeck' => 'Successfully added <i>\'%s\'</i> to Readeck!',
      'failed_to_add_article_to_readeck' => 'Adding article to Readeck failed! Readeck API error code: %s',
      'ajax_request_failed' => 'Ajax request failed!',
      'authorized_success' => 'Authorization successful!',
      'authorized_aborted' => 'Authorization aborted!',
      'authorized_failed' => 'Authorization failed! Readeck API error code: %s',
      'relog_required' => 'Relog to Readeck is required! Please log out and log back in in the extension settings.',
      'request_access_failed' => 'Access request failed! Readeck API error code: %s',
      'article_not_found' => 'Can\'t find article!',
      'authorization_revoked' => 'Authorization successfully revoked!',
      'changes_saved_sucessfully' => "Changes saved successfully!",
      'changes_failed' => "Could not save changes! Value '%s' is not supported!",
    )
  ),
);
