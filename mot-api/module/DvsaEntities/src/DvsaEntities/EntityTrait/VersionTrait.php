<?php
/**
 * Version trait
 *
 * @author Jakub Igla <jakub.igla@valtech.co.uk>
 */
namespace DvsaEntities\EntityTrait;

/**
 * Version trait
 */
trait VersionTrait
{
    /**
     * @var integer
     *
     * @ORM\Column(name="version", type="integer", nullable=false)
     * @version
     */
    private $version = '1';

    /**
     * Set version
     *
     * @param integer $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }
}
