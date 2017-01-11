<?php

namespace Vehicle\UpdateVehicleProperty\Action;

use Core\TwoStepForm\EditStepAction;
use Dvsa\Mot\ApiClient\Service\VehicleService;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Obfuscate\ParamObfuscator;
use Vehicle\UpdateVehicleProperty\Process\UpdateCountryOfRegistrationProcess;

class UpdateCountryAction extends AbstractUpdateVehicleAction implements AutoWireableInterface
{
    public function __construct(
        EditStepAction $editStepAction,
        UpdateCountryOfRegistrationProcess $countryProcess,
        VehicleService $vehicleService,
        ParamObfuscator $paramObfuscator
    )
    {
        parent::__construct($editStepAction, $countryProcess, $vehicleService, $paramObfuscator);
    }
}