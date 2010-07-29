<?php

class acHostDependantCreateUserTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
     $this->addArguments(array(
       new sfCommandArgument('domain_name', sfCommandArgument::REQUIRED),
     ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
      new sfCommandOption('no_configuration', null, sfCommandOption::PARAMETER_NONE, 'Non pone le domande per la configurazione'),
    ));

    $this->namespace        = 'acHostDependant';
    $this->name             = 'createUser';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [acHostDependant:createUser|INFO] task does things.
Call it with:

  [php symfony acHostDependant:createUser|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
      $domain = $arguments['domain_name'];
      $users_dir = sfConfig::get('sf_root_dir') . "/users";
      $domain_dir = $users_dir . '/' . $domain;
      
      if(!is_dir($users_dir))
      {
          sfFilesystem::mkdirs($users_dir);
      }

      if(is_dir($domain_dir))
      {
          echo "La configurazione per il dominio '" . $domain . "' è già presente!\n\n";
          exit;
      }
      else
      {
          $config_dir = $domain_dir . '/config';
          $uploads_dir = $domain_dir . '/uploads';

          sfFilesystem::mkdirs($domain_dir);
          sfFilesystem::mkdirs($config_dir);
          sfFilesystem::mkdirs($uploads_dir.'/files');
      }

      if($options['no_configuration'])
      {
          $project_username = "";
          $project_db_name = "";
          $project_db_username = "";
          $project_db_password = "";
      }
      else
      {
          $project_username = $this->ask('Scrivi il nome del progetto: ');
          $project_db_name = $this->ask('Scrivi il nome del database: ');
          $project_db_username = $this->ask('Scrivi il nome utente per l\'accesso al database: ');
          $project_db_password = $this->ask('Scrivi la password per l\'accesso al database: ');
      }

      $settings_all['project_username'] = $project_username;
      $settings_all['project_db_name'] = $project_db_name;
      $settings_all['project_db_username'] = $project_db_username;
      $settings_all['project_db_password'] = $project_db_password;
      $settings_all['private_upload_dir'] = str_replace(sfConfig::get('sf_root_dir'), '%sf_root_dir%', $uploads_dir);
      $settings_all['upload_files_dir_name'] = "files";

      $settings['all'] = $settings_all;
      $app = array('all'=>array());


      $dumper = new sfYamlDumper();
      $yaml = $dumper->dump($settings,2);
      file_put_contents($config_dir . '/settings.yml', $yaml);

      $yaml = $dumper->dump($app,2);
      file_put_contents($config_dir . '/app.yml', $yaml);
  }
}
