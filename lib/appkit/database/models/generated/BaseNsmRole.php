<?php

/**
 * BaseNsmRole
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $role_id
 * @property string $role_name
 * @property string $role_description
 * @property integer $role_disabled
 * @property timestamp $role_created
 * @property timestamp $role_modified
 * @property Doctrine_Collection $NsmPrincipal
 * @property Doctrine_Collection $NsmUserRole
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6401 2009-09-24 16:12:04Z guilhermeblanco $
 */
abstract class BaseNsmRole extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('nsm_role');
        $this->hasColumn('role_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'unsigned' => 0,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('role_name', 'string', 40, array(
             'type' => 'string',
             'length' => 40,
             'fixed' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('role_description', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('role_disabled', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             'unsigned' => 0,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('role_created', 'timestamp', null, array(
             'type' => 'timestamp',
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('role_modified', 'timestamp', null, array(
             'type' => 'timestamp',
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasOne('NsmPrincipal', array(
             'local' => 'role_id',
             'foreign' => 'principal_role_id'));

        $this->hasMany('NsmUserRole', array(
             'local' => 'role_id',
             'foreign' => 'usro_role_id'));
    }
}