<?php
namespace DvsaEntitiesTest\Entity;

use DvsaEntities\Entity\Make;
use DvsaEntities\Entity\Model;
use DvsaEntities\Entity\Vehicle;
use PHPUnit_Framework_TestCase;

class VehicleMakeAndModelTest extends PHPUnit_Framework_TestCase
{
    /** @var Vehicle */
    private $vehicle;

    /** @var Model */
    private $model;

    /** @var Make */
    private $make;

    private $freeTextName;

    public function setUp()
    {
        $this->vehicle = new Vehicle();

        $this->make = new Make();
        $this->make->setName("NameFromMake");

        $this->model = new Model();
        $this->model->setName("NameFromModel");
        $this->model->setMake($this->make);

        $this->freeTextName = "Any text";
    }

    public function testVehicleModelNameIsTakenFromModelWhenVehicleHasModel()
    {
        // GIVEN I have a vehicle with both model set and free text name
        $this->vehicle->setModel($this->model);
        $this->vehicle->setFreeTextModelName($this->freeTextName);

        // THEN the model name is taken from the model
        $this->assertEquals($this->model->getName(), $this->vehicle->getModelName());
    }

    public function testVehicleModelNameIsTakenFromFreeTextWhenVehicleHasNoModel()
    {
        // GIVEN I have a vehicle with free text name, but no model
        $this->vehicle->setFreeTextModelName($this->freeTextName);

        //THEN the name of the model is the free text name
        $this->assertEquals($this->freeTextName, $this->vehicle->getModelName());
    }

    public function testVehicleMakeNameIsTakenFromMakeOfModelWhenVehicleHasModel()
    {
        // GIVEN I have a vehicle with model set and free text name for make
        $this->vehicle->setModel($this->model);
        $this->vehicle->setMake($this->make);
        $this->vehicle->setFreeTextMakeName($this->freeTextName);

        // THEN the model name is taken from the model
        $this->assertEquals($this->make->getName(), $this->vehicle->getMakeName());
    }

    public function testVehicleMakeNameIsTakenFromFreeTextWhenVehicleHasNoModel()
    {
        // GIVEN I have a vehicle with free text name, but no model
        $this->vehicle->setFreeTextMakeName($this->freeTextName);

        //THEN the name of the make is the free text name
        $this->assertEquals($this->freeTextName, $this->vehicle->getMakeName());
    }
}
