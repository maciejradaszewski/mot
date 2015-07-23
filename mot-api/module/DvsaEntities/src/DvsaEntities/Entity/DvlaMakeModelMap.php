<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * DvlaMakeModelMap Entity. Maps make and model codes from DVLA to their DVSA counterpart.
 *
 * We don't use a Doctrine JoinColumn annotation between this table and dvla_make and dvla_model tables because it is
 * not possible to use join columns pointing to non-primary keys. Doctrine will think these are the primary keys and
 * create lazy-loading proxies with the data, which can lead to unexpected results. Doctrine can for performance
 * reasons not validate the correctness of this settings at runtime but only through the Validate Schema command.
 *
 * @ORM\Table(name="dvla_model_model_detail_code_map")
 * @ORM\Entity(readOnly=true)
 * @ORM\Cache(usage="READ_ONLY", region="staticdata")
 */
class DvlaMakeModelMap extends Entity
{
    use CommonIdentityTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="dvla_make_code", type="string", length=5, nullable=false)
     */
    private $dvlaMakeCode;

    /**
     * @var string
     *
     * @ORM\Column(name="dvla_model_code", type="string", length=5, nullable=false)
     */
    private $dvlaModelCode;

    /**
     * @var Make
     *
     * @ORM\OneToOne(targetEntity="Make")
     * @ORM\JoinColumn(name="make_id", referencedColumnName="id")
     */
    private $make;

    /**
     * @var Model|null
     *
     * @ORM\OneToOne(targetEntity="Model")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id", nullable=true)
     */
    private $model = null;

    /**
     * @var Make
     *
     * @ORM\OneToOne(targetEntity="ModelDetail")
     * @ORM\JoinColumn(name="model_detail_id", referencedColumnName="id", nullable=true)
     */
    private $modelDetail = null;

    /**
     * @param string $dvlaMakeCode
     *
     * @return $this
     */
    public function setDvlaMakeCode($dvlaMakeCode)
    {
        $this->dvlaMakeCode = $dvlaMakeCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getDvlaMakeCode()
    {
        return $this->dvlaMakeCode;
    }

    /**
     * @param string $dvlaModelCode
     *
     * @return $this
     */
    public function setDvlaModelCode($dvlaModelCode)
    {
        $this->dvlaModelCode = $dvlaModelCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getDvlaModelCode()
    {
        return $this->dvlaModelCode;
    }

    /**
     * @param Make $make
     *
     * @return $this
     */
    public function setMake(Make $make)
    {
        $this->make = $make;

        return $this;
    }

    /**
     * @return Make
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * @param Model $model
     *
     * @return $this
     */
    public function setModel(Model $model = null)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return Model|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param ModelDetail $modelDetail
     *
     * @return $this
     */
    public function setModelDetail(ModelDetail $modelDetail = null)
    {
        $this->modelDetail = $modelDetail;

        return $this;
    }

    /**
     * @return ModelDetail|null
     */
    public function getModelDetail()
    {
        return $this->modelDetail;
    }
}
