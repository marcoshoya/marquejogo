<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Component\Person\UserInterface;
use Marcoshoya\MarquejogoBundle\Service\BaseService;
use Marcoshoya\MarquejogoBundle\Service\AutocompleteService;

/**
 * PersonService delegates who is called to get user
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class PersonService extends BaseService
{

    /**
     * @var Marcoshoya\MarquejogoBundle\Service\AutocompleteService
     */
    public $autocomplete;

    /**
     * Get user
     *
     * @param UserInterface $user
     *
     * @return Customer|Provider|AdmUser
     */
    public function getUser(UserInterface $user)
    {
        $service = $this->getPersonDelegate()->getBusinessService($user);
        $entity = $service->getUser($user);
        if (null !== $entity) {
            $service->doAuth($entity);

            return $entity;
        }

        return null;
    }

    /**
     * Get autocomplete observer
     *
     * @return AutocompleteService
     *
     * @throws \InvalidArgumentException
     */
    public function getAutocomplete()
    {
        if ($this->autocomplete instanceof AutocompleteService) {
            return $this->autocomplete;
        } else {
            throw new \InvalidArgumentException("Object have to be instance of AutocompleteService");
        }
    }

    /**
     * Update entity
     *
     * @param Provider $provider
     */
    public function update(UserInterface $provider)
    {
        $autocomplete = $this->getAutocomplete();
        $service = $this->getPersonDelegate()->getBusinessService($provider);
        $service->update($provider, $autocomplete);
    }

    /**
     * Get all images
     *
     * @param UserInterface $user
     * @return type
     */
    public function getAllPicture(UserInterface $user)
    {
        return $this->getPersonDelegate()->getBusinessService($user)->getAllPicture();
    }

}
