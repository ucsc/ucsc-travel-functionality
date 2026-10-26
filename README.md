# UCSC Travel Functionality

A WordPress plugin for the UC Santa Cruz Travel website. It renames the built-in Category taxonomy to "Expedition" wherever WordPress shows it.

## Requirements

- WordPress 6.5 or later
- PHP 8.1 or later

The plugin needs no other plugin and no specific theme.

## What the plugin changes

The plugin changes how the Category taxonomy is labeled. It does not change how categories are stored.

- The admin, the block editor, and the Site Editor show "Expedition" and "Expeditions" in place of "Category" and "Categories". This includes the Posts menu, the Expeditions screen, the post sidebar, and the filter on the Posts list.
- Category archive headings show "Expedition: Antarctica" in place of "Category: Antarctica". This applies to most themes and to the Query Title block.

These things stay the same:

- The taxonomy name stays `category`. Existing posts, terms, and code that use `category` keep working.
- Category archive URLs stay at `/category/<slug>/`. To change that base, go to Settings, Permalinks, Category base.
- Some help text on the Expeditions admin screen still says "Categories". WordPress writes that text directly into the screen.
- Text that a theme hardcodes in its templates or patterns does not change.

## Installation

1. Download `ucsc-travel-functionality.zip` from the [releases page](https://github.com/ucsc/ucsc-travel-functionality/releases).
2. In WordPress, go to Plugins, Add New Plugin, Upload Plugin, and upload the zip.
3. Activate the plugin.

## Development

You need PHP 8.1 or later, Composer, and Node.js with npm.

```bash
composer install      # PHP tools: PHPCS and PHPUnit
npm install           # Release and zip tools

composer lint         # Check the PHP against the WordPress Coding Standards
composer test         # Run the PHPUnit tests
npm run zip           # Build ucsc-travel-functionality.zip
```

The PHPUnit tests replace the WordPress functions the plugin calls with simple stand-ins. They need no WordPress install and no database.

To test the plugin in real WordPress, use your own local WordPress site. The repository does not include a local environment file. Map this folder to `wp-content/plugins/ucsc-travel-functionality` in the site, then activate the plugin on the Plugins screen.

For example, with [wp-env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/), put a `.wp-env.json` in a separate site folder and list the path to this folder:

```json
{
  "plugins": [ "../ucsc-travel-functionality" ]
}
```

For a temporary site with no setup, run [WordPress Playground](https://wordpress.github.io/wordpress-playground/) from the plugin folder:

```bash
npx @wp-playground/cli@latest server --login \
  --mount="$PWD:/wordpress/wp-content/plugins/ucsc-travel-functionality"
```

## Releases

Commit messages follow [Conventional Commits](https://www.conventionalcommits.org/). The release tool reads them to choose the next version number.

```bash
npm run dryrun        # Preview the next version and changelog
npm run release       # Update the version, write CHANGELOG.md, commit, and tag
```

`npm run release` updates the version in `package.json`, `package-lock.json`, and the plugin header in `plugin.php`.

## Questions and issues

To report a problem or ask a question, [open an issue](https://github.com/ucsc/ucsc-travel-functionality/issues).

## License

GPL v3 or later. See [LICENSE](LICENSE).
