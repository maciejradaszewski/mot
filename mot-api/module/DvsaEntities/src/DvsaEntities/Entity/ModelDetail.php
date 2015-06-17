<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * ModelDetail
 *
 * @ORM\Table(
 *  name="model_detail",
 *  indexes={
 *      @ORM\Index(name="fk_model_detail_created_by", columns={"created_by"}),
 *      @ORM\Index(name="fk_model_detail_last_updated_by", columns={"last_updated_by"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="DvsaEntities\Repository\ModelDetailRepository")
 */
class ModelDetail extends Entity
{
    use CommonIdentityTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=5, nullable=false)
     */
    private $code;

    /**
     * @var Make
     *
     * @ORM\ManyToOne(targetEntity="Make", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="make_id", referencedColumnName="id"),
     * })
     */
    private $make;

    /**
     * @var Model
     *
     * @ORM\ManyToOne(targetEntity="Model", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="model_id", referencedColumnName="id"),
     * })
     */
    private $model;

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $make
     */
    public function setMake($make)
    {
        $this->make = $make;
    }

    /**
     * @return string
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
