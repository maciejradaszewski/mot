<?php

namespace Organisation\ViewModel\View\Index;

use DvsaClient\Entity\Person;
use DvsaClient\Entity\VehicleTestingStation;
use DvsaCommon\Constants\PersonContactType;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Dto\Organisation\OrganisationPositionDto;
use DvsaCommon\Dto\Person\PersonDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use Organisation\Authorisation\AuthorisedExaminerViewAuthorisation;

/**
 * Class IndexViewModel
 *
 * @package Organisation\ViewModel
 */
class IndexViewModel
{
    private $viewAuthorisation;

    /**
     * @var EmployeeViewModel[]
     */
    private $employees = [];
    private $organisation;
    private $vehicleTestingStations = [];
    private $principals = [];

    private $numberOfEmployees = 0;
    private $numberOfVehicleTestingStation = 0;
    private $numberOfPrincipals = 0;

    /**
     * @var EmployeeViewModel
     */
    private $lastEmployee;

    /**
     * @var Person
     */
    private $lastPrincipal;

    /**
     * @var VehicleTestingStationDto
     */
    private $lastVts;

    /**
     * @param AuthorisedExaminerViewAuthorisation $viewAuthorisation
     * @param OrganisationDto                     $organisation
     * @param VehicleTestingStationDto[]          $vehicleTestingStations
     * @param OrganisationPositionDto[]           $positions
     * @param PersonDto[]                         $principals
     */
    public function __construct(
        AuthorisedExaminerViewAuthorisation $viewAuthorisation,
        OrganisationDto $organisation,
        $vehicleTestingStations,
        $positions,
        $principals
    ) {
        $this->viewAuthorisation = $viewAuthorisation;

        $this->organisation = $organisation;
        $this->vehicleTestingStations = $vehicleTestingStations;
        $this->employees = $this->groupPositionsByPerson($positions);
        $this->principals = $principals;

        $this->numberOfEmployees = count($this->employees);
        $this->numberOfVehicleTestingStation = count($this->vehicleTestingStations);
        $this->numberOfPrincipals = count($this->principals);

        $this->lastEmployee = end($this->employees);
        $this->lastVts = end($vehicleTestingStations);
        $this->lastPrincipal = end($this->principals);
    }

    public function getViewAuthorisation()
    {
        return $this->viewAuthorisation;
    }

    public function getVehicleTestingStations()
    {
        return $this->vehicleTestingStations;
    }

    public function getPrincipals()
    {
        return $this->principals;
    }

    /**
     * @param int $principalIndex
     * @return \DvsaCommon\Dto\Person\PersonContactDto|null
     */
    public function getPrincipalPersonalContact($principalIndex)
    {
        $personalContact = null;

        /** @var \DvsaCommon\Dto\Person\PersonContactDto $contact */
        foreach ($this->principals[$principalIndex]->getContacts() as $contact) {
            if ($contact->getType() === PersonContactType::PERSONAL) {
                $personalContact = $contact;
                break;
            }
        }

        return $personalContact;
    }

    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @return int
     */
    public function getNumberOfEmployees()
    {
        return $this->numberOfEmployees;
    }

    /**
     * @param EmployeeViewModel $employee
     *
     * @return bool
     */
    public function isLastEmployee(EmployeeViewModel $employee)
    {
        return $this->lastEmployee === $employee;
    }

    /**
     * @param PersonDto $person
     *
     * @return bool
     */
    public function isLastPrincipal(PersonDto $person)
    {
        return $this->lastPrincipal === $person;
    }

    /**
     * @return int
     */
    public function getNumberOfVehicleTestingStations()
    {
        return $this->numberOfVehicleTestingStation;
    }

    /**
     * @param VehicleTestingStation $vts
     *
     * @return bool
     */
    public function isLastVts(VehicleTestingStation $vts)
    {
        return $this->lastVts === $vts;
    }

    /**
     * @return \Organisation\ViewModel\View\Index\EmployeeViewModel[]
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    public function shouldViewContactDetailsForVts()
    {
        return $this->getNumberOfVehicleTestingStations() > 0;
    }

    /**
     * @param OrganisationPositionDto[] $positions
     *
     * @return EmployeeViewModel[]
     */
    private function groupPositionsByPerson($positions)
    {
        $employees = [];

        if (!empty($positions)) {
            foreach ($positions as $position) {
                $person = $position->getPerson();

                if (array_key_exists($person->getId(), $employees)) {
                    $employee = $employees[$person->getId()];
                } else {
                    $employee = new EmployeeViewModel($person);
                }

                $employee->addPosition($position);
                $employees[$person->getId()] = $employee;
            }
        }

        return $employees;
    }

    public function hasPrincipals()
    {
        return $this->numberOfPrincipals > 0;
    }

    public function canViewAeSection()
    {
        if ($this->hasPrincipals() && $this->viewAuthorisation->canViewAuthorisedExaminerPrincipals()) {
            return true;
        }

        return false;
    }
}
