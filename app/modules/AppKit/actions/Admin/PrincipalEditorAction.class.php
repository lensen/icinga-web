<?php

class AppKit_Admin_PrincipalEditorAction extends AppKitBaseAction {
    /**
     * Returns the default view if the action does not serve the request
     * method used.
     *
     * @return     mixed <ul>
     *                     <li>A string containing the view name associated
     *                     with this action; or</li>
     *                     <li>An array with two indices: the parent module
     *                     of the view to be executed and the view to be
     *                     executed.</li>
     *                   </ul>
     */
    public function getDefaultViewName() {
        return 'Success';
    }

    public function isSecure() {
        return true;
    }

    public function execute() {
        return $this->getDefaultViewName();
    }

    public function getCredentials() {
        return array('appkit.admin');
    }

    public function handleError(AgaviRequestDataHolder $rd) {
        return $this->getDefaultViewName();
    }

    public function executeRead(AgaviRequestDataHolder $rd) {
        return $this->getDefaultViewName();
    }
}

?>