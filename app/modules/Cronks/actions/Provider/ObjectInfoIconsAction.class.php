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


class Cronks_Provider_ObjectInfoIconsAction extends CronksBaseAction implements IAppKitDispatchableAction {
    public function getDefaultViewName() {
        return 'Success';
    }
    
    public function executeRead(AgaviRequestDataHolder $rd) {
        return $this->executeWrite($rd);
    }
    
    public function executeWrite(AgaviRequestDataHolder $rd) {
        
        $model = $this->getContext()->getModel('Provider.ObjectInfoIcons', 'Cronks', array (
            'type' => $rd->getParameter('type'),
            'oids' => $rd->getParameter('oids'),
            'connection' => $rd->getParameter('connection','icinga')
        ));
        
        $this->setAttribute('info_data', $model->getData());
        
        return $this->getDefaultViewName();
    }
    
    public function isSecure() {
        return true;
    }

    public function getCredentials() {
        return array('icinga.user');
    }

    public function handleError(AgaviRequestDataHolder $rd) {
        return $this->getDefaultViewName();
    }
}
