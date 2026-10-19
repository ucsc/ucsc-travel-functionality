# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Roadmap

Read @ROADMAP.md first. It records the open work and the decisions behind the current shape of the plugin.

## Commands

```bash
composer install      # PHP dev dependencies: PHPCS, WordPress Coding Standards, PHPUnit
npm install           # Node dev dependencies: @wordpress/scripts, commit-and-tag-version

composer lint         # PHPCS across the repository
composer lint-fix     # PHPCBF auto-fix
composer test         # PHPUnit, the whole suite

./vendor/bin/phpunit --filter CategoryLabelsTest                    # one test class
./vendor/bin/phpunit --filter test_no_label_still_says_category     # one test method

npm run zip           # Build ucsc-travel-functionality.zip (wp-scripts plugin-zip)
npm run dryrun        # Preview the version bump and changelog
npm run release       # Bump the version, update CHANGELOG.md, commit and tag
```

`.github/workflows/ci.yml` runs `composer lint` and `composer test` on PHP 8.1 and 8.4 for each pull request and each push to `main`. Run both yourself before you commit, because the local PHP is not 8.1.

## Repository shape

This is a standalone plugin repository, not a site tree. The bootstrap is `plugin.php` at the repository root. The repository has no `wp-content/` folder, so tools that look for `wp-content/plugins/*` find no plugin here. The `detect_plugins.mjs` skill script also finds no plugin. Its header regex does not match a line that starts with ` * `.

The code is procedural. `plugin.php` defines `UCSCTRAVEL_PLUGIN_DIR` and `UCSCTRAVEL_PLUGIN_BASE` and includes the files in `lib/functions/`. Each included file registers its hooks at file scope with `add_filter()`. The repository has no classes, namespaces, autoloader, or build step. Composer and npm are for development only, and `vendor/` and `node_modules/` never ship.

The plugin has no runtime dependency. It does not require ACF or a specific theme.

## What the plugin does

`lib/functions/taxonomies.php` renames the built-in Category taxonomy to "Expedition" for display. The taxonomy name stays `category`, and the `/category/` URL base does not change. Two filters do the work, and both are needed:

- `ucsctravel_filter_category_labels()` on `taxonomy_labels_category` replaces the label object. This reaches the admin, the block editor, and any theme that reads `get_taxonomy( 'category' )->labels`.
- `ucsctravel_filter_category_archive_title_prefix()` on `get_the_archive_title_prefix` replaces the "Category:" archive heading. Core's `get_the_archive_title()` hardcodes that prefix and ignores the labels, so the label filter cannot reach it.

The label filter must set every label that core ships with "category" in its text. Core adds labels over time. The filter originally came from a doc that missed six of them, for example `filter_by_item` (the Posts list dropdown) and `template_name` (the Site Editor). `CategoryLabelsTest::test_no_label_still_says_category` exists to catch the next missing label.

Some "Categories" text on the Expeditions admin screen comes from core, not from labels: the help tab, the hint under the Parent field, and the converter link. Core writes those strings into `edit-tags.php` for the `category` taxonomy, so no label filter can change them.

## Tests

The PHPUnit suite stubs WordPress and does not run inside it. `tests/bootstrap.php` loads three files in this order: the state class, `tests/stubs.php`, then `plugin.php`. The stubs must load first, because `plugin.php` calls `plugin_dir_path()` at file scope.

- `add_filter()` in the stubs records each call in `UCSCTravel_Test_State::$filters`. Tests assert hook registration against that list. The plugin loads once per run, so `reset()` does not clear `$filters`.
- Values that tests change, such as the return value of `is_category()`, live on `UCSCTravel_Test_State`. Call `reset()` in `setUp()`.
- If a new plugin function calls a WordPress function, add a stub for that function to `tests/stubs.php`.

`tests/fixtures/core-category-labels.php` holds core's default Category labels from WordPress 7.1.3. When a WordPress release adds a label, capture the labels again. Do not read `get_taxonomy( 'category' )->labels` to do this: WordPress builds the labels at registration and keeps them on the taxonomy object, so you get the filtered values even after `remove_filter()`. Instead, in a running WordPress, remove the plugin's filter, build `new WP_Taxonomy( 'category', 'post', array( 'hierarchical' => true ) )`, and export its `labels`.

To check behavior in real WordPress, start WordPress Playground with this folder mounted. The site data is temporary.

```bash
npx @wp-playground/cli@latest server --login \
  --mount="$PWD:/wordpress/wp-content/plugins/ucsc-travel-functionality"
```

Then activate the plugin on the Plugins screen at http://127.0.0.1:9400/wp-admin/plugins.php. Code changes appear on reload, because the folder is mounted live.

## Conventions

- Prefix every global with `ucsctravel_` or `UCSCTRAVEL_`. The text domain is `ucsctravel`. PHPCS enforces both.
- PHPCS uses `WordPress-Extra` and `WordPress-Docs`. It scans only `.php` files.
- Use tabs in PHP, as `.editorconfig` sets. JSON and YAML use 2 spaces, and `composer.json` uses 4.
- Write named functions with a docblock, and register each one directly above its definition. Do not use anonymous functions, because other code cannot remove them with `remove_filter()`.
- Wrap every user-facing string in `__()` or `_x()` with the `ucsctravel` text domain.

## Releases

`commit-and-tag-version` reads Conventional Commit messages to choose the version bump: `fix:` gives a patch, `feat:` gives a minor version, and `chore:`, `build:`, `docs:` and `test:` give no bump. The commit prefix therefore changes the release.

`npm run release` updates the version in `package.json`, in `package-lock.json`, and in the `Version:` header of `plugin.php`. The header update goes through `wp-plugin-version-updater.js`. If the header and `package.json` disagree, `PluginBootstrapTest` fails. For the first release, run `npm run release -- --first-release`, so that the version stays 0.1.0.

The zip ships the paths in the `files` array of `package.json`, plus `package.json` itself, which npm always includes. `CHANGELOG.md` is in that list, but the first `npm run release` creates it. Until then, the zip build skips it without an error.

The `package` job in `ci.yml` builds the zip and makes sure that it holds the required files and no development files. If you add a path to `files` or a development file at the repository root, update the lists in that job.
