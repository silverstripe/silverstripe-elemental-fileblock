<?php

namespace SilverStripe\ElementalFileBlock\Tests\Block;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Folder;
use SilverStripe\Assets\Image;
use SilverStripe\Assets\Dev\TestAssetStore;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\ElementalFileBlock\Block\FileBlock;

class FileBlockTest extends SapphireTest
{
    protected static $fixture_file = 'FileBlockTest.yml';

    protected function setUp(): void
    {
        parent::setUp();
        TestAssetStore::activate('FileBlockTest');

        // Copy test images for each of the fixture references
        /** @var File $image */
        $files = File::get()->exclude('ClassName', Folder::class);
        foreach ($files as $image) {
            $sourcePath = __DIR__ . '/FileBlockTest/' . $image->Name;
            $image->setFromLocalFile($sourcePath, $image->Filename);
            $image->write();
        }
    }

    protected function tearDown(): void
    {
        TestAssetStore::reset();
        parent::tearDown();
    }

    public function testGetSummaryReturnsStringWithoutAssociatedFile()
    {
        $block = new FileBlock;
        $this->assertSame('', $block->getSummary());
    }

    public function testGetSummaryReturnsFileTitleWhenLinkedToFile()
    {
        /** @var FileBlock $block */
        $block = $this->objFromFixture(FileBlock::class, 'with_file');

        $summary = $block->getSummary();
        $this->assertEquals('Some file', $summary);
    }

    public function testImageIsAddedToSchemaData()
    {
        /** @var FileBlock $block */
        $block = $this->objFromFixture(FileBlock::class, 'with_image');

        $schemaData = $block->getBlockSchema();

        $this->assertNotEmpty($schemaData['fileURL'], 'File URL is added to schema');
        $this->assertStringContainsString('ss-logo', $schemaData['fileURL'], 'File URL contains image name');
        $this->assertNotEmpty($schemaData['fileTitle'], 'File title is added to schema');

        /** @var FileBlock $block */
        $block2 = $this->objFromFixture(FileBlock::class, 'with_file');

        $schemaData = $block2->getBlockSchema();

        $this->assertNotEmpty($schemaData['fileURL'], 'File URL is added to schema');
        $this->assertStringContainsString(
            'document_92.png',
            $schemaData['fileURL'],
            'File URL contains placeholder file name'
        );
        $this->assertNotEmpty($schemaData['fileTitle'], 'File title is added to schema');
    }

    public function testUploadFieldShouldNotAllowMultipleFiles()
    {
        /** @var FileBlock $block */
        $block = $this->objFromFixture(FileBlock::class, 'with_image');

        /** @var UploadField $field */
        $field = $block->getCMSFields()->fieldByName('Root.Main.File');

        $this->assertInstanceOf(UploadField::class, $field, 'Field should be an UploadField');
        $this->assertFalse($field->getIsMultiUpload(), 'Field should not allow multiple uploads');
    }
}
