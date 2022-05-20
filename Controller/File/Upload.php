<?php

namespace GhoSter\CustomerAvatar\Controller\File;

/**
 * Class Upload for upload file action
 */
class Upload extends AbstractUpload
{
    /**
     * Get file id
     *
     * @return string
     */
    public function getFileId(): string
    {
        return $this->_request->getParam('param_name', 'image');
    }
}
