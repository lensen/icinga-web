<?php
// {{{ICINGA_LICENSE_CODE}}}
// -----------------------------------------------------------------------------
// This file is part of icinga-web.
// 
// Copyright (c) 2009-2012 Icinga Developer Team.
// All rights reserved.
// 
// icinga-web is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// icinga-web is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with icinga-web.  If not, see <http://www.gnu.org/licenses/>.
// -----------------------------------------------------------------------------
// {{{ICINGA_LICENSE_CODE}}}


class AppKit_Admin_TasksAction extends AppKitBaseAction {
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

    public function executeWrite(AgaviRequestDataHolder $rd) {

        $task = $rd->getParameter('task');

        if ($task) {
            $this->getContext()->getLoggerManager()->log(sprintf('Prepare running admin task: %s', $task), AgaviLogger::INFO);

            switch ($task) {
                case 'purgeCache':
                    $model = $this->getContext()->getModel('Tasks.ClearCache', 'AppKit');
                    $model->clearCache();
                    break;
            }
        }

        return $this->getDefaultViewName();
    }
}

?>