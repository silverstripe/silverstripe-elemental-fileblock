<?php

namespace SilverStripe\ElementalFileBlock\Block;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Image_Backend;
use SilverStripe\Core\Manifest\ModuleResourceLoader;
use SilverStripe\Dev\Deprecation;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\FieldType\DBHTMLText;

class FileBlock extends BaseElement
{
    private static $has_one = [
        'File' => File::class,
    ];

    private static $owns = [
        'File',
    ];

    private static $singular_name = 'file block';

    private static $plural_name = 'file blocks';

    private static $icon = 'font-icon-block-file';

    private static $table_name = 'S_EB_FileBlock';

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            /** @var UploadField $uploadField */
            $uploadField = $fields->fieldByName('Root.Main.File');
            $uploadField->setIsMultiUpload(false);
        });

        return parent::getCMSFields();
    }

    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'File');
    }

    public function getSummary()
    {
        if ($this->File() && $this->File()->exists()) {
            return $this->getSummaryThumbnail() . $this->File()->Title;
        }
        return '';
    }

    /**
     * Return file title and thumbnail for summary section of ElementEditor
     *
     * @return array
     */
    protected function provideBlockSchema()
    {
        $blockSchema = parent::provideBlockSchema();
        if ($this->File() && $this->File()->exists() && $this->File()->getIsImage()) {
            $blockSchema['fileURL'] = $this->File()->CMSThumbnail()->getURL();
            $blockSchema['fileTitle'] = $this->File()->getTitle();
        }
        return $blockSchema;
    }

    /**
     * Return a thumbnail of the file, if it's an image. Used in GridField preview summaries.
     *
     * @return DBHTMLText
     * @deprecated 3.1.0 Will be removed without equivalent functionality to replace it
     */
    public function getSummaryThumbnail()
    {
        Deprecation::notice('3.1.0', 'Will be removed without equivalent functionality to replace it.');
        
        $data = [];

        if ($this->File() && $this->File()->exists()) {
            if ($this->File()->getIsImage()) {
                // Stretch to maximum of 36px either way then trim the extra off
                if ($this->File()->getOrientation() === Image_Backend::ORIENTATION_PORTRAIT) {
                    $data['Image'] = $this->File()->ScaleWidth(36)->CropHeight(36);
                } else {
                    $data['Image'] = $this->File()->ScaleHeight(36)->CropWidth(36);
                }
            } else {
                $data = [
                    'Image' => ModuleResourceLoader::resourceURL(
                        'silverstripe/framework:client/images/app_icons/document_92.png'
                    ),
                    'IsPlaceholder' => true
                ];
            }
        }

        return $this->customise($data)->renderWith(__CLASS__ . '/SummaryThumbnail');
    }
}
