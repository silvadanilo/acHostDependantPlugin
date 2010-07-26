<?php

class acHostDependatGuardSecurityUser extends sfGuardSecurityUser
{
    public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
    {
        parent::initialize($dispatcher, $storage, $options);
        if($this->isAuthenticated())
        {
            if($this->getAttribute("project_username",null,"sfGuardSecurityUser") !== sfConfig::get("sf_project_username"))
                $this->signOut();
        }
    }

    public function signIn($user, $remember = false, $con = null)
    {
        $this->setAttribute("project_username", sfConfig::get("sf_project_username"), 'sfGuardSecurityUser');
        $this->setAttribute("last_login", $user->last_login, 'sfGuardSecurityUser');
        $return = parent::signIn($user, $remember, $con);

        return $return;
    }
}