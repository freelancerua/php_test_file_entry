<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use TestProject\Entry;

class EntryTest extends TestCase
{
	/**
	 * Test __toString magic
	 */
    public function testCanBeUsedAsString(): void
    {
    	$path = 'path';
    	$name = 'test_name'; 

    	$this->assertEquals(
            $path . DIRECTORY_SEPARATOR . $name,
            new Entry('path', $name, time(), false)
        );
    }

    /**
     * Test Entry:getMostUsedName() function ( __invoke magic )
     * @see TestProject\Entry
     */
    public function testMostUsedName()
    {
    	// Catalogs
    	$catalog = new Entry('path', 'name', time(), false);
		$catalog_level_1 = new Entry('path', 'name2', time(), false);
		$catalog_level_1_1 = new Entry('path', 'name3', time(), false);

		// Files
		$file_1_catalog_level_1 = new Entry('path', 'name2', time());
		$file_2_catalog_level_1 = new Entry('path', 'name1', time());
		$file_1_catalog_level_1_1 = new Entry('path', 'name1', time());
		$file_1_catalog = new Entry('path', 'name1', time());

		// Add as child elements
		$catalog_level_1_1->addChildEntry($file_1_catalog_level_1_1);
		$catalog_level_1->addChildEntry($file_1_catalog_level_1);
		$catalog_level_1->addChildEntry($file_2_catalog_level_1);
		$catalog_level_1->addChildEntry($catalog_level_1_1);
		$catalog->addChildEntry($catalog_level_1);
		$catalog->addChildEntry($file_1_catalog);

		// Make test
		$this->assertEquals(
			'name1', $catalog()
		);
    }
}
