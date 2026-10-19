# Roadmap

Engineering roadmap for the UCSC Travel Functionality plugin, as of 2026-10-19 (v0.1.0, not yet released).

This is a technical roadmap, not a product plan. The items come from testing the plugin in WordPress Playground and from comparing it with the `ucsc-giving-functionality` plugin. The order shows dependency and risk, not committed dates. The site owners decide what the plugin does next.

---

## Current state

The plugin renames the built-in Category taxonomy to "Expedition" for display. The repository is at [github.com/ucsc/ucsc-travel-functionality](https://github.com/ucsc/ucsc-travel-functionality).

Landed work, newest first:

| Commit or PR | Change |
| --- | --- |
| `ci/pr-checks` branch | `ci.yml`: PHPCS and PHPUnit on PHP 8.1 and 8.4, and a zip content check |
| `7a67992`, `3733f08` | `README.md` and the GPL v3 `LICENSE` |
| [#1](https://github.com/ucsc/ucsc-travel-functionality/pull/1) | This roadmap updated for the GitHub remote |
| `535e043` | `CLAUDE.md` and this roadmap |
| `a479ec4` | Composer, npm, PHPCS and PHPUnit tooling, the version updater, and a 13-test suite |
| `1a4a6a9` | Plugin bootstrap, the Category label filter, and the archive title prefix filter |

The label code came from a doc: [Renaming Category in WordPress](https://claude.ai/artifact/3Si5iuGSdTRNnGES9zqSNS)

A Playground test on WordPress 7.1.3 found six labels that the doc did not set. The plugin sets them too. The doc still has the shorter list.

---

## 1. Decide the open label questions

These need a decision from the site owners before any code changes.

- Core text on the Expeditions admin screen. The help tab, the hint under the Parent field, and the categories-to-tags converter link still say "Categories". Core writes these strings into `edit-tags.php` for the `category` taxonomy, so no label filter reaches them. A `gettext` filter (a hook that can replace translated strings) can change them. It must run only on that screen and only for the `default` text domain, because an unscoped `gettext` filter runs on every translated string.
- The "Add" wording. Core now uses "Add Category". The plugin uses "Add New Expedition", from the doc. To match core, change `add_new_item` to "Add Expedition".
- The URL base. Category archives are still at `/category/<slug>/`. To change the base, set it under Settings, Permalinks, Category base, or set `$args['rewrite']['slug']` in a `register_taxonomy_args` filter. A code change needs a rewrite flush, and existing links need redirects.

## 2. Theme strings

Text that a theme hardcodes in its templates or block patterns, such as `__( 'Categories', 'theme' )`, does not use the taxonomy labels. Neither filter changes it. The fix depends on which theme the Travel site uses, and that is not recorded yet. If the site uses `ucsc-2022`, check its templates and patterns for "Categor" before launch.

## 3. Repository setup

The GitHub repository exists, and its URL matches the plugin header and `package.json`. Each item below copies something the giving plugin already has. `ci.yml` is done.

1. Add `.github/workflows/release.yml`, which calls the shared `ucsc/actions` release workflow on `v*.*.*` tags. Set `zip-name` to `ucsc-travel-functionality.zip`, because this package name has no `-plugin` suffix.
2. Make the first release with `npm run release -- --first-release`, so that the version stays 0.1.0.

## 4. Commit a Playground blueprint

Testing in real WordPress uses WordPress Playground. The blueprint used so far lives outside the repository. It activates the plugin, logs in, and creates a test post in an "Antarctica" category so that a category archive has content. A committed blueprint, for example `blueprint.json`, makes that test the same for each developer. The zip check in `ci.yml` already forbids `blueprint.json`. If the blueprint has a different name, update that list.

## 5. Maintenance

- Capture the core label fixture again after each WordPress release that adds a taxonomy label. `CLAUDE.md` gives the method. The plain `get_taxonomy()` read returns filtered values and gives a false pass.
- Update the doc's label list to match the plugin, or add a note to the doc that the plugin is now the source of truth.

---

## Non-goals

- Tests inside a real WordPress test suite. The unit suite stubs the few WordPress functions the plugin calls, and Playground covers real WordPress behavior by hand. The plugin has no ACF or theme dependency. If the plugin grows, this choice can change.
- Registering a new taxonomy to replace Category. The rename is for display only, so existing posts, terms, and links keep working.
- A class-based or namespaced rewrite, or a build step. The plugin is two small PHP files.
