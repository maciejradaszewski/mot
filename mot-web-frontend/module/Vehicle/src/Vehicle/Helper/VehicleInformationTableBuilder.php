<?php

namespace Vehicle\Helper;

use Application\Service\CatalogService;
use Core\Routing\VehicleRoutes;
use Core\ViewModel\Gds\Table\GdsRow;
use Core\ViewModel\Gds\Table\GdsTable;
use DateTime;
use Dvsa\Mot\ApiClient\Resource\Item\DvsaVehicle;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Dto\Vehicle\VehicleExpiryDto;
use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Enum\ColourCode;
use DvsaCommon\Enum\CountryOfRegistrationCode;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use Zend\View\Helper\Url;

class VehicleInformationTableBuilder implements AutoWireableInterface
{
    const EMPTY_VALUE_TEXT = 'Unknown';

    protected static $unknownCountriesCodes = [
        CountryOfRegistrationCode::NON_EU => "Non Eu",
        CountryOfRegistrationCode::NOT_APPLICABLE => "Not Known",
        CountryOfRegistrationCode::NOT_KNOWN => "Not Applicable",
    ];

    private $catalogService;

    /** @var DvsaVehicle */
    private $vehicle;

    private $vehicleObfuscatedId;

    /** @var \DvsaCommon\Dto\Vehicle\VehicleExpiryDto */
    private $expiryDateInformation;

    private $authorisationService;
    private $urlHelper;

    public function __construct(
        CatalogService $catalogService,
        MotAuthorisationServiceInterface $authorisationService,
        Url $urlHelper
    )
    {
        $this->catalogService = $catalogService;
        $this->authorisationService = $authorisationService;
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param DvsaVehicle $vehicle
     * @return VehicleInformationTableBuilder
     */
    public function setVehicle($vehicle)
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function setVehicleObfuscatedId($vehicleObfuscatedId)
    {
        $this->vehicleObfuscatedId = $vehicleObfuscatedId;

        return $this;
    }

    /**
     * @param VehicleExpiryDto $expiryDateInformation
     * @return VehicleInformationTableBuilder
     */
    public function setExpiryDateInformation($expiryDateInformation)
    {
        $this->expiryDateInformation = $expiryDateInformation;

        return $this;
    }

    /**
     * @return GdsTable
     */
    public function getVehicleSpecificationGdsTable()
    {
        $table = new GdsTable();

        $this->addRowToTableWithChangeLink(
            $table,
            'Make and model',
            $this->getMakeAndModel(),
            VehicleRoutes::of($this->urlHelper)->changeMake($this->vehicleObfuscatedId)
        );

        $this->addRowToTableWithChangeLink(
            $table,
            'Engine',
            $this->getEngineInfo(),
            VehicleRoutes::of($this->urlHelper)->vehicleEditEngine($this->vehicleObfuscatedId)
        );

        $this->addRowToTableWithChangeLink(
            $table,
            'Colour',
            $this->getVehicleColourNames(),
            VehicleRoutes::of($this->urlHelper)->changeColour($this->vehicleObfuscatedId)
        );

        $this->addRowToTable($table, 'Brake test weight', $this->getVehicleBrakeWeight());

        $this->addRowToTableWithChangeLink(
            $table,
            'MOT test class',
            $this->vehicle->getVehicleClass()->getName(),
            VehicleRoutes::of($this->urlHelper)->changeClass($this->vehicleObfuscatedId)
        );

        $this->addRowToTable($table, 'MOT expiry date', $this->getExpiryDate());

        return $table;
    }

    private function addChangeLink(GdsRow $row, $url)
    {
        if ($this->canUserEditVehicle()) {
            $row->addActionLink('Change', $url);
        }
    }

    private function addRowToTableWithChangeLink(GdsTable $table, $label, $value, $changeUrl)
    {
        $row = $this->addRowToTable($table, $label, $value);
        $this->addChangeLink($row, $changeUrl);

        return $row;
    }

    /**
     * @return GdsTable
     */
    public function getVehicleRegistrationGdsTable()
    {
        $changeCountryLink = $this->urlHelper->__invoke('vehicle/detail/change/country-of-registration',
            ['id' => $this->vehicleObfuscatedId]
        );

        $table = new GdsTable();
        $this->addRowToTable($table, 'Registration mark', $this->vehicle->getRegistration());
        $this->addRowToTable($table, 'VIN', $this->vehicle->getVin());
        $this->addRowToTable($table, 'Country of registration', $this->getCountryCodeById($this->vehicle->getCountryOfRegistrationId()))
            ->addActionLink('Change', $changeCountryLink);
        $this->addRowToTable($table, 'Declared new', $this->vehicle->getIsNewAtFirstReg() ? "Yes" : "No");
        $this->addRowToTable($table, 'Manufacture date', $this->dateFormat($this->vehicle->getManufactureDate()));
        $this->addRowToTable($table, 'First registered', $this->dateFormat($this->vehicle->getFirstRegistrationDate()));
        $table = $this->getFirstUsedDateRow($table);
        $this->addRowToTable($table, 'Details created', $this->dateFormat($this->vehicle->getAmendedOn()));

        return $table;
    }

    private function getFirstUsedDateRow(GdsTable $table)
    {
        $firstDateUsedRow = $this->addRowToTable($table, 'First used', $this->dateFormat($this->vehicle->getFirstUsedDate()));
        if ($this->canUserEditVehicleExtendedProperties()) {
            $firstDateUsedRow->addActionLink(
                'Change',
                VehicleRoutes::of($this->urlHelper)->changeFirstUsedDate($this->vehicleObfuscatedId)
            );
        }
        return $table;
    }

    /**
     * @param GdsTable $table
     * @param string $label
     * @param string $value
     * @return GdsRow
     */
    private function addRowToTable(GdsTable $table, $label, $value)
    {
        if (is_null($value)) {
            $value = self::EMPTY_VALUE_TEXT;
        }

        $htmlId = str_replace(' ', '-', strtolower($label));
        $gdsRow = $table->newRow($htmlId);
        $gdsRow->setLabel($label)->setValue($value);

        return $gdsRow;
    }

    /**
     * @param int $countryId
     * @return string|null
     */
    private function getCountryCodeById($countryId)
    {
        $countries = $this->catalogService->getCountriesOfRegistration();

        if (array_key_exists($countryId, $countries)) {
            return $countries[$countryId];
        }

        return null;
    }

    /**
     * @param DateTime|string $date
     * @return null|string
     */
    private function dateFormat($date)
    {
        if (!empty($date)) {
            $dateObj = $date instanceof DateTime ? $date : new DateTime($date);

            return DateTimeDisplayFormat::date($dateObj);
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    private function getVehicleColourNames()
    {
        $colourNotStated = in_array($this->vehicle->getColourSecondary()->getCode(),[
            ColourCode::NOT_STATED, null
        ]);

        if ($colourNotStated) {
            return $this->vehicle->getColour()->getName();
        } else {
            return $this->vehicle->getColour()->getName() . ' and ' . $this->vehicle->getColourSecondary()->getName();
        }
    }

    /**
     * @return null|string
     */
    private function getExpiryDate()
    {
        if ($this->expiryDateInformation->getPreviousCertificateExists()) {
            return $this->dateFormat($this->expiryDateInformation->getExpiryDate());
        } else {
            return null;
        }
    }


    /**
     * @return string
     */
    private function getMakeAndModel()
    {
        return $this->vehicle->getModel()->getName()
            ? $this->vehicle->getMake()->getName() . ', ' . $this->vehicle->getModel()->getName()
            : $this->vehicle->getMake()->getName();
    }

    /**
     * @return int|null
     */
    private function getVehicleBrakeWeight()
    {
        $vehicleWeight = $this->vehicle->getWeight();

        if (!is_null($vehicleWeight)) {
            $vehicleWeight = number_format($vehicleWeight) . ' Kg';
        }

        return $vehicleWeight;
    }

    /**
     * @return string
     */
    private function getEngineInfo()
    {
        $fuelType = $this->vehicle->getFuelType()->getName();
        $cylinderCapacity = $this->vehicle->getCylinderCapacity();

        return !is_null($cylinderCapacity)
            ? $fuelType . ', ' . number_format($cylinderCapacity) . ' cc'
            : $fuelType;
    }

    private function canUserEditVehicle()
    {
        return $this->authorisationService->isGranted(PermissionInSystem::VEHICLE_UPDATE);
    }

    private function canUserEditVehicleExtendedProperties()
    {
        return $this->authorisationService->isGranted(PermissionInSystem::VEHICLE_CHANGE_PROPERTY_EXPANDED);
    }
}