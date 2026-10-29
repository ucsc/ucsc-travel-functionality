<?php
/**
 * Tests for the WordPress Playground blueprint.
 *
 * @package ucsc-travel-functionality
 */

use PHPUnit\Framework\TestCase;

/**
 * Covers blueprint.json at the repository root.
 */
class BlueprintTest extends TestCase {

	/**
	 * The decoded blueprint.
	 *
	 * @var array
	 */
	private $blueprint;

	/**
	 * Reads and decodes blueprint.json.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading a file from this repository, not making a request.
		$json = (string) file_get_contents( dirname( __DIR__, 2 ) . '/blueprint.json' );

		$this->blueprint = json_decode( $json, true, 512, JSON_THROW_ON_ERROR );
	}

	/**
	 * Returns the step that installs this plugin.
	 *
	 * @return array
	 */
	private function install_step() {
		foreach ( $this->blueprint['steps'] as $step ) {
			if ( 'installPlugin' === $step['step'] ) {
				return $step;
			}
		}

		$this->fail( 'blueprint.json has no installPlugin step.' );
	}

	/**
	 * The blueprint names the Playground schema, so editors can validate it.
	 *
	 * @return void
	 */
	public function test_names_the_playground_schema() {
		$this->assertSame( 'https://playground.wordpress.net/blueprint-schema.json', $this->blueprint['$schema'] );
	}

	/**
	 * The plugin installs into the folder that the CLI mount uses.
	 *
	 * If the names differ, a mounted working copy and the GitHub copy load
	 * side by side as two plugins.
	 *
	 * @return void
	 */
	public function test_installs_into_the_mount_folder() {
		$step = $this->install_step();

		$this->assertSame( 'ucsc-travel-functionality', $step['options']['targetFolderName'] );
		$this->assertTrue( $step['options']['activate'] );
	}

	/**
	 * The install skips a plugin that is already there.
	 *
	 * With the CLI, the folder is a mount of the working copy. Any other
	 * value writes the GitHub copy into the developer's working copy.
	 *
	 * @return void
	 */
	public function test_skips_an_installed_plugin() {
		$this->assertSame( 'skip', $this->install_step()['ifAlreadyInstalled'] );
	}
}
