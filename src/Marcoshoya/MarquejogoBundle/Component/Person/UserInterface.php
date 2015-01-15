<?php

namespace Marcoshoya\MarquejogoBundle\Component\Person;

/**
 * User interface
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
interface UserInterface
{
    /**
     * Get username
     *
     * @return string
     */
    public function getUsername();

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword();

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles();
}
