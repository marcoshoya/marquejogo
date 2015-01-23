<?php

namespace Marcoshoya\MarquejogoBundle\Service;

use Marcoshoya\MarquejogoBundle\Entity\Provider;
use Marcoshoya\MarquejogoBundle\Service\AutocompleteService;

/**
 * ProviderService
 *
 * @author Marcos Lazarin <marcoshoya at gmail dot com>
 */
class ProviderService extends BaseService
{

    /**
     * @var Marcoshoya\MarquejogoBundle\Service\AutocompleteService
     */
    public $autocomplete;

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
    public function update(Provider $provider)
    {
        $autocomplete = $this->getAutocomplete();

        try {

            // persist subject object
            $this->getEm()->persist($provider);
            $this->getEm()->flush();

            // notify observers
            $provider->attach($autocomplete);
            $provider->notify();
        } catch (\Exception $e) {
            $this->getLogger()->error("ProviderService error: " . $e->getMessage());
        }
    }

}
