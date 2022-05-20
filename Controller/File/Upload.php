<?php

namespace GhoSter\CustomerAvatar\Controller\File;

/**
 * Class Upload for upload file action
 */
class Upload extends AbstractUpload
{
    public function getFileId(): string
    {
        return $this->_request->getParam('param_name', 'image');
    }
}
