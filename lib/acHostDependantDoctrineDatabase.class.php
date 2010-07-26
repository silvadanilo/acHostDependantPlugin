<?php

class acHostDependantDoctrineDatabase extends sfDoctrineDatabase
{
  public function initialize($parameters = array())
  {
      $project_db_name = sfConfig::get("sf_project_db_name");
      $parameters['dsn'] = $parameters['dsn'] . $project_db_name;
      $parameters['username'] = sfConfig::get("sf_project_db_username");
      $parameters['password'] = sfConfig::get("sf_project_db_password");

      return parent::initialize($parameters);
  }
}