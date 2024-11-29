# FreshRSS Readeck Button
A [FreshRSS](https://freshrss.org/) extension which adds a [Readeck](https://readeck.org/en/) sharing integration.

With this extension you can simply press the Readeck Button next to an article or a custom keyboard shortcut to share it with Readeck. Everything else happens in the background while you can continue reading articles without any further interruptions.

## Download and setup
1. Download the [latest release](https://github.com/Joedmin/xExtension-readeck-button/releases)
2. Extract and upload it to the `./extensions` folder of your FreshRSS installation
3. Go to `<readeck_intance_url>/profile/tokens`
4. Create a new API token with at least the `Bookmarks : Write Only` permission
5. Enter your Readeck instance url in the Readeck Button extension settings
6. Enter your key in the Readeck Button extension settings
7. Press "Connect to Readeck"
8. *Optional Set a custom keyboard shortcut*

## Readeck API Error codes
If you get errors while trying to connect to Readeck, please check the [Readeck OpenAPI specification](https://codeberg.org/readeck/readeck/src/branch/main/docs/api/api.yaml).

## Contributing

### Translations
If you'd like to translate the extension to another language please file a pull request. I'd be happy to merge it!

### Development
For local development pull the repository. The prerequisite is [Docker](https://www.docker.com/) installed.

Go to the repository root folder and run `docker compose up` that will start a local [FreshRSS](https://www.freshrss.org/) instance running `http://localhost:8080/`.

Complete it's installation and navigate to Extensions, where you have to enable `Readeck Button`.

All changes in the PHP files are loaded with each page refresh.

## Credits

This extension is based on [freshrss-pocket-button](https://github.com/christian-putzke/freshrss-pocket-button) and re-branded for Readeck.

Thank you very much [Christian Putzke](https://github.com/christian-putzke) for creating the original extension. I used it every day until migrating from Pocket to Readeck.

Original icon is used from the original [Readeck repository](https://codeberg.org/readeck/readeck) and the outlined version is done using [Online Vector Designing Apps](https://vectordad.com/photo-to-outline/).
