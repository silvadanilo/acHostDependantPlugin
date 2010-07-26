<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfDoctrineGuardPlugin configuration.
 * 
 * @package    sfDoctrineGuardPlugin
 * @subpackage config
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfDoctrineGuardPluginConfiguration.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class acHostDependantPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
      $this->dispatcher->connect('command.filter_options', array($this, 'listenToCommandFilterOptions'));
      $this->dispatcher->connect('command.pre_command', array($this, 'listenToCommandPreCommand'));

      if(isset($_SERVER["HTTP_HOST"]))
      {
          $hostname_config_dir = 'users/'.$_SERVER["HTTP_HOST"].'/config/';
          if(!sfConfig::has('sf_ac_host'))
              sfConfig::set('sf_ac_host', $_SERVER["HTTP_HOST"]);
      }
      elseif(sfConfig::has('sf_ac_host'))
      {
          $hostname_config_dir = 'users/'.sfConfig::get('sf_ac_host').'/config/';
      }

      if(!empty($hostname_config_dir))
      {
          if(is_dir(sfConfig::get("sf_root_dir").'/'.$hostname_config_dir))
          {
              if(method_exists($this->configuration,"getConfigCache"))
              {
                $configCache = $this->configuration->getConfigCache();
                include($configCache->checkConfig($hostname_config_dir . 'settings.yml'));
                include($configCache->checkConfig($hostname_config_dir . 'app.yml'));
              }
          }
      }

      if(!sfConfig::get("sf_project_enable",true) === true)
          die();
  }

  public function listenToCommandFilterOptions(sfEvent $event, $result)
  {
      $task = $event->getSubject();
      $command_manager = $event['command_manager'];
      $option_set = $command_manager->getOptionSet();
      if(!$option_set->hasOption('ac_host'))
        $option_set->addOption(new sfCommandOption('ac_host', null, sfCommandOption::PARAMETER_OPTIONAL, 'Il nome host', true));
      $command_manager->setOptionSet($option_set);

      return $result;
  }

  public function listenToCommandPreCommand(sfEvent $event)
  {
      if(array_key_exists("ac_host",$event['options']))
        sfConfig::set('sf_ac_host', $event['options']['ac_host']);
  }
}