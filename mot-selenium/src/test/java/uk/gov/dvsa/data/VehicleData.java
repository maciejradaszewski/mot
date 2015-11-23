package uk.gov.dvsa.data;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.service.VehicleService;

import java.io.IOException;

public class VehicleData extends VehicleService {

    public VehicleData() {}

    public Vehicle getNewVehicle(User tester) throws IOException {
        return createVehicle(tester);
    }
    public Vehicle getNewVehicle(User tester, Integer vehicleWeight) throws IOException {
        return createVehicle(vehicleWeight, tester);
    }
}
