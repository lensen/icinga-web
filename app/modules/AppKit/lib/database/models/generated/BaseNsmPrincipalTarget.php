<?php
/**
 * BaseNsmPrincipalTarget
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $pt_id
 * @property integer $pt_principal_id
 * @property integer $pt_target_id
 * @property NsmTarget $NsmTarget
 * @property NsmPrincipal $NsmPrincipal
 * @property Doctrine_Collection $NsmTargetValue
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseNsmPrincipalTarget extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('nsm_principal_target');
        $this->hasColumn('pt_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('pt_principal_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('pt_target_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('NsmTarget', array(
             'local' => 'pt_target_id',
             'foreign' => 'target_id'));

        $this->hasOne('NsmPrincipal', array(
             'local' => 'pt_principal_id',
             'foreign' => 'principal_id'));

        $this->hasMany('NsmTargetValue', array(
             'local' => 'pt_id',
             'foreign' => 'tv_pt_id'));
    }
}