<?php

namespace GhoSter\CustomerAvatar\Plugin\Model\Metadata\Form;

use GhoSter\CustomerAvatar\Model\ImageProcessor;
use Magento\Customer\Model\Metadata\Form\File;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class FilePlugin fixing problem while saving file parameter
 */
class FilePlugin
{
    /**
     * @var ImageProcessor
     */
    protected $imageProcessor;

    /**
     * @var string
     */
    protected $attributeCode;

    /**
     * FilePlugin constructor.
     * @param ImageProcessor $imageProcessor
     * @param string $attributeCode
     */
    public function __construct(ImageProcessor $imageProcessor, string $attributeCode)
    {
        $this->imageProcessor = $imageProcessor;
        $this->attributeCode = $attributeCode;
    }

    /**
     * @param File $subject
     * @param $result
     * @param RequestInterface $request
     * @return mixed
     * @throws LocalizedException
     */
    public function afterExtractValue(File $subject, $result, RequestInterface $request)
    {
        $attrCode = $subject->getAttribute()->getAttributeCode();
        $value = $request->getParam($attrCode, false);

        if (!is_array($result)
            && $value
            && $attrCode == $this->attributeCode
        ) {
            return $value;
        }

        if (isset($result['name'])
            && empty($result['name'])
            && $value
            && $attrCode == $this->attributeCode
        ) {
            return $value;
        }

        return $result;
    }

    /**
     * @param File $subject
     * @param $result
     * @param $value
     * @return string
     * @throws LocalizedException
     */
    public function afterCompactValue(File $subject, $result, $value): string
    {
        $validFileName = $result;

        if (empty($result)) {
            return $validFileName;
        }

        $attrCode = $subject->getAttribute()->getAttributeCode();

        if ($attrCode == $this->attributeCode
            && $result !== $value
        ) {
            try {
                $validFileName = $this->imageProcessor->moveFileFromTmp($value, true);
            } catch (\Exception $e) {
                return $validFileName;
            }
        }

        return $validFileName;
    }
}
