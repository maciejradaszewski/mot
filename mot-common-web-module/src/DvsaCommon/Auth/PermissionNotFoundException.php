<?php

namespace DvsaCommon\Auth;

class PermissionNotFoundException extends \Exception
{
    private $permissionLevel;

    private $notExisitngPermission;

    public function __construct($permission, $permissionLevel)
    {
        $this->notExisitngPermission = $permission;
        $this->permissionLevel = $permissionLevel;

        $this->message = $this->buildMessage();
    }

    private function buildMessage()
    {
        $message = sprintf(
            $this->getTemplate(),
            $this->notExisitngPermission,
            PermissionLevel::getMethodSuffix($this->permissionLevel),
            PermissionLevel::getFileName($this->permissionLevel)
        );

        return $message;
    }

    public function getPermissionLevel()
    {
        return $this->permissionLevel;
    }

    private function getTemplate()
    {
        $template = "The asserted permission '%s' does not exist.\n";
        $template .= "You used it inside method: 'is/assertGranted%s'.\n";
        $template .= "You either forgot to put the permission in 'all()' method in %s ";
        $template .= "or you wanted to use a different 'is/assertGranted' method.";

        return $template;
    }
}
