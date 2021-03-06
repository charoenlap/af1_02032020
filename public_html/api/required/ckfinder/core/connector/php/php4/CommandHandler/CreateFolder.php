                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
        }

        $_config =& CKFinder_Connector_Core_Factory::getInstance("Core_Config");
        if (!$this->_currentFolder->checkAcl(CKFINDER_CONNECTOR_ACL_FOLDER_CREATE)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }

        $_resourceTypeConfig = $this->_currentFolder->getResourceTypeConfig();
        $sNewFolderName = isset($_GET["NewFolderName"]) ? $_GET["NewFolderName"] : "";
        $sNewFolderName = CKFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($sNewFolderName);
        if ($_config->forceAscii()) {
            $sNewFolderName = CKFinder_Connector_Utils_FileSystem::convertToAscii($sNewFolderName);
        }

        if (!CKFinder_Connector_Utils_FileSystem::checkFileName($sNewFolderName) || $_resourceTypeConfig->checkIsHiddenFolder($sNewFolderName)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }

        $sServerDir = CKFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getServerPath(), $sNewFolderName);
        if (!is_writeable($this->_currentFolder->getServerPath())) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        }

        $bCreated = false;

        if (file_exists($sServerDir)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_ALREADY_EXIST);
        }

        if ($perms = $_config->getChmodFolders()) {
            $oldUmask = umask(0);
            $bCreated = @mkdir($sServerDir, $perms);
            umask($oldUmask);
        }
        else {
            $bCreated = @mkdir($sServerDir);
        }

        if (!$bCreated) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_ACCESS_DENIED);
        } else {
            $oNewFolderNode = new Ckfinder_Connector_Utils_XmlNode("NewFolder");
            $this->_connectorNode->addChild($oNewFolderNode);
            $oNewFolderNode->addAttribute("name", CKFinder_Connector_Utils_FileSystem::convertToConnectorEncoding($sNewFolderName));
        }
    }
}
