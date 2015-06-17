package com.dvsa.mot.selenium.priv.testdata.tradeuser.provider;


import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.api.VehicleApi;


public class VehicleProvider {

    private VehicleApi api = new VehicleApi();

    public Vehicle createVehicle(Vehicle vehicle) {
        return api.createVehicle(vehicle, null);
    }
}
