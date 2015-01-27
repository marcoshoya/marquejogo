<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;

/**
 * PersonInterface
 * 
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
interface PersonInterface {
    
    /**
     * Get user instance
     * 
     * @return string
     */
    public function getUser(UserInterface $user);
}
