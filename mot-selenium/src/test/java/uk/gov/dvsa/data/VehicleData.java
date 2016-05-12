package uk.gov.dvsa.data;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.service.VehicleService;

import java.io.IOException;

public class VehicleData extends VehicleService {

    public VehicleData() {}

    @Deprecated
    public Vehicle getNewVehicle(User tester) throws IOException {
        return createVehicle(tester);
    }

    public Vehicle getNewVehicle() throws IOException {
        return createVehicle();
    }

    @Deprecated
    public Vehicle getNewDvlaVehicle(User tester) throws IOException {
        return createDvlaVehicle(tester);
    }

    public Vehicle getNewDvlaVehicle() throws IOException {
        return createDvlaVehicle();
    }

    @Deprecated
    public Vehicle getNewVehicle(User tester, Integer vehicleWeight) throws IOException {
        return createVehicle(vehicleWeight, tester);
    }

    public Vehicle getNewVehicle(Integer vehicleWeight) throws IOException {
        return createVehicle(vehicleWeight);
    }
}
