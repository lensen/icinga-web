<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Addnsmsession extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('nsm_session', array(
             'session_entry_id' => 
             array(
              'type' => 'integer',
              'length' => 4,
              'fixed' => false,
              'unsigned' => false,
              'primary' => true,
              'autoincrement' => true,
             ),
             'session_id' => 
             array(
              'type' => 'string',
              'length' => 255,
              'fixed' => false,
              'unsigned' => false,
              'primary' => false,
              'notnull' => true,
              'autoincrement' => false,
             ),
             'session_name' => 
             array(
              'type' => 'string',
              'length' => 255,
              'fixed' => false,
              'unsigned' => false,
              'primary' => false,
              'notnull' => true,
              'autoincrement' => false,
             ),
             'session_data' => 
             array(
              'type' => 'blob',
              'fixed' => false,
              'unsigned' => false,
              'primary' => false,
              'notnull' => true,
              'autoincrement' => false,
              'length' => NULL,
             ),
             'session_checksum' => 
             array(
              'type' => 'string',
              'length' => 255,
              'fixed' => false,
              'unsigned' => false,
              'primary' => false,
              'notnull' => true,
              'autoincrement' => false,
             ),
             'session_created' => 
             array(
              'notnull' => true,
              'type' => 'timestamp',
              'length' => 25,
             ),
             'session_modified' => 
             array(
              'notnull' => true,
              'type' => 'timestamp',
              'length' => 25,
             ),
             ), array(
             'indexes' => 
             array(
             ),
             'primary' => 
             array(
              0 => 'session_entry_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropTable('nsm_session');
    }
}