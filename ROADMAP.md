# Roadmap

Engineering roadmap for the UCSC Travel Functionality plugin, as of 2026-10-26 (v0.1.0 released).

This is a technical roadmap, not a product plan. The items come from testing the plugin in WordPress Playground and from comparing it with the `ucsc-giving-functionality` plugin. The order shows dependency and risk, not committed dates. The site owners decide what the plugin does next.

---

## Current state

The plugin renames the built-in Category taxonomy to "Expedition" for display. The repository is at [github.com/ucsc/ucsc-travel-functionality](https://github.com/ucsc/ucsc-travel-functionality).

Landed work, newest first:

| Commit or PR | Change |
| --- | --- |
| [#10](https://github.com/ucsc/ucsc-travel-functionality/pull/10) | The `CLAUDE.md` intro line is in plain English, so the writing hook no longer flags it. |
| [#9](https://github.com/ucsc/ucsc-travel-functionality/pull/9) | `tests/tools/capture-core-labels.php` recaptures the core label fixture with one `wp eval-file`. |
| [#8](https://github.com/ucsc/ucsc-travel-functionality/pull/8) | The `add_new_item` label says "Add Expedition", to match core. The open label questions are closed. |
| [#7](https://github.com/ucsc/ucsc-travel-functionality/pull/7) | Local test sites replace the planned Playground blueprint. Git and the zip check exclude local environment files. |
| [#4](https://github.com/ucsc/ucsc-travel-functionality/pull/4) | `release.yml`: calls the shared `ucsc/actions` release workflow on version tags |
| [v0.1.0](https://github.com/ucsc/ucsc-travel-functionality/releases/tag/v0.1.0) | First GitHub Release, with `ucsc-travel-functionality.zip`. The tag is on `1e3095c`, so that `release.yml` ran. |
| `aa3c40e` | First release commit: version 0.1.0 and `CHANGELOG.md` |
| [#3](https://github.com/ucsc/ucsc-travel-functionality/pull/3) | `ci.yml`: PHPCS and PHPUnit on PHP 8.1 and 8.4, and a zip content check |
| `7a67992`, `3733f08` | `README.md` and the GPL v3 `LICENSE` |
| [#1](https://github.com/ucsc/ucsc-travel-functionality/pull/1) | This roadmap updated for the GitHub remote |
| `535e043` | `CLAUDE.md` and this roadmap |
| `a479ec4` | Composer, npm, PHPCS and PHPUnit tooling, the version updater, and a 13-test suite |
| `1a4a6a9` | Plugin bootstrap, the Category label filter, and the archive title prefix filter |

The label code came from a doc: [Renaming Category in WordPress](https://claude.ai/artifact/3Si5iuGSdTRNnGES9zqSNS)

A Playground test on WordPress 7.1.3 found six labels that the doc did not set. The plugin sets them too. On 2026-10-26 the doc got the six labels and a note that this repository is the source of truth.

---

## 1. Theme strings

Text that a theme hardcodes in its templates or block patterns, such as `__( 'Categories', 'theme' )`, does not use the taxonomy labels. Neither filter changes it. The fix depends on which theme the Travel site uses, and that is not recorded yet. If the site uses `ucsc-2022`, check its templates and patterns for "Categor" before launch.

## 2. Maintenance

After each WordPress release that adds a taxonomy label, run `tests/tools/capture-core-labels.php` to capture the core label fixture again. `CLAUDE.md` gives the command.

---

## Non-goals

- Tests inside a real WordPress test suite. The unit suite stubs the few WordPress functions the plugin calls, and each developer checks real WordPress behavior by hand in a local site. The plugin has no ACF or theme dependency. If the plugin grows, this choice can change.
- A shared local environment in the repository, such as a Playground `blueprint.json` or a `.wp-env.json`. Each developer sets up a local WordPress site and maps this folder into it. The primary developer uses wp-env, with a `.wp-env.json` in a separate site folder.
- A `gettext` filter (a hook that replaces translated strings) for the core text on the Expeditions admin screen. The help tab, the hint under the Parent field, and the converter link still say "Categories", because core writes them into `edit-tags.php`. Only editors and administrators see that screen. The filter must match core's exact English. If core rewords a string, the filter stops working without an error.
- Setting the URL base in plugin code. To change `/category/`, an administrator sets Settings, Permalinks, Category base on each site. A `register_taxonomy_args` filter needs a rewrite flush and overrides that setting without any sign on the Permalinks screen. On a live site, old `/category/` links need redirects with either method.
- Registering a new taxonomy to replace Category. The rename is for display only, so existing posts, terms, and links keep working.
- A class-based or namespaced rewrite, or a build step. The plugin is three small PHP files.
