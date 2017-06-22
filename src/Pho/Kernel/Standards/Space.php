<?php

namespace Pho\Kernel\Standards;

use Pho\Kernel\Kernel;

final class Space extends \Pho\Framework\Space implements \Serializable {

    use \Pho\Kernel\Bridge\GraphHydratorTrait;
    use \Pho\Kernel\Traits\Node\VolatileTrait;

    private $kernel;

    public function __construct(Kernel $kernel) {
        $this->kernel = $kernel;
        $this->persist();
    }

    public function label(): string
    {
        return "Space";
    }

/*

    public function persist(bool $skip = false): void
    {
        if($skip) return;
        $this->kernel->database()->set(
            sprintf("node:%s", $this->id()), serialize($this)
        );
    }

public function serialize(): string
    {
        return serialize($this->toArray());
    }

  public function unserialize($data): void
  {
    $this->kernel = $GLOBALS["kernel"];
    $data = unserialize($data);
    $this->members = [];
    foreach($data["members"] as $member)
        $this->members[] = $this->kernel["utils"]->node($member);
  }

  public function onAdd(\Pho\Lib\Graph\NodeInterface $node): void
  {
        $this->kernel->logger()->info(
          "Node added with ID: %s and label: %s", $node->id(), $node->label()
        );
  }

  */

}