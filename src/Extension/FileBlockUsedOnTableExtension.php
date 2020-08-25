<?php

namespace SilverStripe\ElementalFileBlock\Extension;

use SilverStripe\ElementalFileBlock\Block\FileBlock;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;

class FileBlockUsedOnTableExtension extends DataExtension
{
    /**
     * Hides FileBlocks's from the "Used On" tab when viewing files
     *
     * @return void
     * @var ArrayList $dataObjects
     * @var DataObject $record
     * @see UsedOnTable::updateUsage
     */
    public function updateUsage(ArrayList &$dataObjects, DataObject &$record)
    {
        $dataObjects = $dataObjects->exclude('ClassName', FileBlock::class);
    }
}
