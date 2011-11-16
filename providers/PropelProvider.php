<?php

require_once 'Xtoph/Tool/Project/Provider/Abstract.php';
require_once 'Zend/Tool/Project/Provider/Exception.php';

class PropelProvider
    extends Xtoph_Tool_Project_Provider_Abstract
{

   public static function createPropelResource(Zend_Tool_Project_Profile $profile)
   {
      $projectDirectory = $profile->search(array('projectDirectory'));
      $resource = $projectDirectory->createResource('PropelDirectory');
      $profile->storeToFile();
      //TODO create folder src/propel
      return $resource;
   }

   public static function _getPropelDirectoryResource(Zend_Tool_Project_Profile $profile)
   {
      $profileSearchParams = array('propelDirectory');
      return $profile->search($profileSearchParams);
   }

   protected function _getDefaultDatabaseSchema($database)
   {
      $schema = <<<EOT
<database name="{$database}" defaultIdMethod="native">
</database>
EOT;
      return $schema;
   }

   public function Enable()
   {
      $this->_loadProfile(self::NO_PROFILE_THROW_EXCEPTION);

      if (!$propelDirectoryResource = self::_getPropelDirectoryResource($this->_loadedProfile)) {
         try {
            $this->_registry->getResponse()->appendContent('Try create propel resource in project');
            $propelDirectoryResource = self::createPropelResource($this->_loadedProfile);
         } catch (Exception $e) {
            $this->_registry->getResponse()->appendContent('Create propel resource in project failed');
            throw $e;
         }
      }

      exit();
      if ($propelDirectoryResource->isEnabled()) {
         throw new Zend_Tool_Project_Provider_Exception('This project already has propel enabled.');
      } else {
         if ($this->_registry->getRequest()->isPretend()) {
            $this->_registry->getResponse()->appendContent('Would enable propel directory at ' . $propelDirectoryResource->getContext()->getPath());
         } else {
            $this->_registry->getResponse()->appendContent('Enabling propel directory at ' . $propelDirectoryResource->getContext()->getPath());
            $propelDirectoryResource->setEnabled(true);
            $propelDirectoryResource->create();
            $this->_storeProfile();
         }
      }
      $this->_registry->getResponse()->appendContent(
          'Note: Create folder src/propel', array('color' => array('yellow'))
      );
      //TODO create empty src/propel/schema.xml
      //TODO create empty src/propel/build.properties
      //TODO create empty src/propel/runtime-conf.xml
   }

   public function Database()
   {
      Zend_Debug::dump($this->_registry->getStorage());
   }

   public function Table()
   {
      /** @todo Implementation */
   }

   public function Column()
   {
      /** @todo Implementation */
   }

   public function Validator()
   {
      /** @todo Implementation */
   }

   public function ForeignKey()
   {
      /** @todo Implementation */
   }

   public function EditProperty()
   {
      /** @todo Implementation */
   }

   public function ShowProperty()
   {
      /** @todo Implementation */
   }

   public function ShowSchema()
   {
      
   }

   public function ShowTable()
   {
      
   }

}

/*
 * 
 *   <table name="match" phpName="Match">
    <column name="pk_match" phpName="PkMatch" type="INTEGER" primaryKey="true" autoIncrement="true" required="true"/>
    <column name="date" phpName="Date" type="DATE" required="false"/>
    <column name="set_1" phpName="Set1" type="VARCHAR" size="4" required="false"/>
    <column name="set_2" phpName="Set2" type="VARCHAR" size="4" required="false"/>
    <column name="set_3" phpName="Set3" type="VARCHAR" size="4" required="false"/>
    <column name="player_fk_player1" phpName="PlayerFkPlayer1" type="INTEGER" size="11" required="true"/>
    <column name="player_fk_player2" phpName="PlayerFkPlayer2" type="INTEGER" size="11" required="true"/>
    <validator column="pk_match">
      <rule name="required" message="The field pk_match is required."/>
    </validator>
    <!--    <validator column="date">
      <rule name="type" value="string" message="The column date must be an string value."/>
    </validator>-->
    <validator column="set_1">
      <rule name="minLength" value="4" message="The field set_1 must be 4 characters long."/>
      <rule name="maxLength" value="4" message="The field set_1 must be not longer than 4 characters."/>
      <rule name="class" class="propel.validator.SetValidator" message="Sets should be valid (see Badminton rules)."/>
    </validator>
    <validator column="set_2">
      <rule name="minLength" value="4" message="The field set_2 must be 4 characters long."/>
      <rule name="maxLength" value="4" message="The field set_2 must be not longer than 4 characters."/>
      <rule name="class" class="propel.validator.SetValidator" message="Sets should be valid (see Badminton rules)."/>
    </validator>
    <validator column="set_3">
      <rule name="minLength" value="4" message="The field set_3 must be 4 characters long."/>
      <rule name="maxLength" value="4" message="The field set_3 must be not longer than 4 characters."/>
      <rule name="class" class="propel.validator.SetValidator" message="Sets should be valid (see Badminton rules)."/>
    </validator>
    <validator column="player_fk_player1">
      <rule name="required" message="The field player_fk_player1 is required."/>
    </validator>
    <validator column="player_fk_player2">
      <rule name="required" message="The field player_fk_player2 is required."/>
    </validator>
    <foreign-key foreignTable="player" name="fk_match_player2" skipSql="true" onDelete="RESTRICT" onUpdate="RESTRICT">
      <reference local="player_fk_player1" foreign="pk_player"/>
    </foreign-key>
    <foreign-key foreignTable="player" name="fk_match_player1" skipSql="true" onDelete="RESTRICT" onUpdate="RESTRICT">
      <reference local="player_fk_player2" foreign="pk_player"/>
    </foreign-key>
  </table>

 */