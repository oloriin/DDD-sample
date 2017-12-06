<?php

namespace ApplicationLayerApiBundle\Security;

use FOS\UserBundle\Security\UserProvider;

class ApiKeyUserProvider extends UserProvider
{
    public function getUsernameForApiToken($apiToken)
    {
        // Look up the username based on the token in the database, via
        // an API call, or do something entirely different

        $user = $this->userManager->findUserBy(['apiToken' => $apiToken]);
        if (is_null($user)) {
             return null;
        }
        return $user->getUsername();
    }
}
