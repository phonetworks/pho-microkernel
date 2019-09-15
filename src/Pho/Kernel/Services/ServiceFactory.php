<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Services;

use Zend\Config\Config;
use Pho\Kernel\Kernel;
use Pho\Kernel\Services\ServiceInterface;
use Pho\Kernel\Services\Exceptions\AdapterNonExistentException;

/**
 * An factory class to build services with given kernel settings.
 *
 * @author Emre Sokullu
 */
class ServiceFactory {

  private $kernel;

  const SOUGHT_SERVICE_INTERFACE = "Pho\\Kernel\\Services\\ServiceInterface";

  public function __construct(Kernel $kernel) {
    $this->kernel = $kernel;
  }

  /**
   * This function creates an instance of the valid service
   * using the indicated adapter and returns the final object.
   *
   * @param string  $category Service category
   * @param string $type  Service type
   * @param mixed $options Service options
   *
   * @return ServiceInterface
   *
   * @throws AdapterNonExistentException
   */
  public function create(string $category, string $type, string $uri): ServiceInterface
  {
    if(!self::serviceExists($category, $type)) {
      throw new AdapterNonExistentException();
    }

    $service = $this->convertTypeToServiceClassName($category, $type);
    return new $service($this->kernel, $uri);
  }


  /**
   * This function converts a type name's such that it matches the
   * standard service class name format. Please note this function
   * doesn't necessarily check if this class name conforms the
   * requirements to be a valid kernel service.
   *
   * Warning: in cases such as the one with $type=redis
   * convertTypeToServiceClassName returns "redis" if the native Redis
   * extension \Redis is installed and then serviceExists fails because 
   * \Redis does not implement the sought-after adapters.
   * To mitigate this problem, we've added a bypass custom argument which will
   * apply a second search in such circumastances, bypassing the custom class case
   * and trying its luck with a class name based on $this->kernel->config()->namespaces->services
   *
   * @see serviceExists
   *
   * @param string  $category Service category
   * @param string  $type Service type
   * @param bool    $bypass_custom whether the function should take into consideration custom class case
   *
   * @return string|null
   */
  private function convertTypeToServiceClassName(string $category, string $type, bool $bypass_custom = false): ?string
  {
    if( !$bypass_custom && class_exists($type)) { // custom service
      return $type;
    }
    else {
      $type = $this->kernel->config()->namespaces->services . ucfirst(strtolower($category))."\\Adapters\\".ucfirst(strtolower($type));
      if(class_exists($type)) { // standard service
        return $type;
      }
    }
    return null;
  }

  /**
   * Checks if the given type matches any valid service that implements
   * appropriate service interfaces.
   *
   * Warning: in cases such as the one with $type=redis
   * convertTypeToServiceClassName returns "redis" if the native Redis
   * extension \Redis is installed and then serviceExists fails because 
   * \Redis does not implement the sought-after adapters.
   * To mitigate this problem, we've added a bypass custom argument which will
   * apply a second search in such circumastances, bypassing the custom class case
   * and trying its luck with a class name based on $this->kernel->config()->namespaces->services
   *
   * @param string  $category Service category
   * @param string  $type Service type
   * @param $bypass_custom whether the function should take into consideration custom class case
   *
   * @return bool
   */
  private function serviceExists(string $category, string $type, bool $bypass_custom = false): bool
  {
    $service_class = $this->convertTypeToServiceClassName($category, $type, $bypass_custom);
    if(is_null($service_class))
      return false;

    $interfaces = class_implements($service_class);
    if($interfaces===false || !is_array($interfaces)) {
      if(!$bypass_custom)
          return $this->serviceExists($category, $type, true);
      return false;
    }


    $category = ucfirst(strtolower($category));
    $sought_adapter_interface = $this->kernel->config()->namespaces->services . $category. "\\" . $category . "Interface";
    return
      (in_array(self::SOUGHT_SERVICE_INTERFACE, $interfaces) && in_array($sought_adapter_interface, $interfaces));
  }

}
