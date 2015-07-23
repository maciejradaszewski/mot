package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.Utilities;
import com.dvsa.mot.selenium.framework.api.vehicle.IVehicleDataRandomizer;
import org.joda.time.format.DateTimeFormat;

import javax.json.JsonObject;
import java.util.HashMap;
import java.util.Map;

public class VehicleApi extends BaseApi {

    public Vehicle vehicle;
    private String resource = "testsupport/vehicle/create";

    public VehicleApi() {
        super(testSupportUrl());
    }

    public Vehicle createVehicle(Vehicle vehicle, IVehicleDataRandomizer randomizer) {

        Map<String, String> vehicleData = new HashMap<>();

        String carReg = vehicle.carReg;
        String fullVIN = vehicle.fullVIN;

        if (null != randomizer) {
            carReg = carReg != null ? randomizer.nextReg(carReg.length()) : null;
            fullVIN = fullVIN != null ? randomizer.nextVin(fullVIN.length()) : null;
        }

        vehicleData.put("registrationNumber", carReg);
        vehicleData.put("vin", fullVIN);
        vehicleData.put("make", vehicle.make.getVehicleID());
        vehicleData.put("makeOther", "");
        vehicleData.put("model", vehicle.model.getModelId());
        vehicleData.put("modelOther", "");
        //vehicleData.put("modelType", vehicle.modelType);
        //vehicleData.put("modelType", "1");
        vehicleData.put("colour", vehicle.primaryColour.getColourId());
        vehicleData.put("secondaryColour", vehicle.secondaryColour.getColourId());
        vehicleData.put("dateOfFirstUse",
                vehicle.dateOfFirstUse.toString(DateTimeFormat.forPattern("YYYY-MM-dd")));
        vehicleData.put("dateOfManufacture",vehicle.manufactureDate.toString(DateTimeFormat.forPattern("YYYY-MM-dd")));
        vehicleData.put("firstRegistrationDate",vehicle.dateOfFirstRegistration.toString(DateTimeFormat.forPattern("YYYY-MM-dd")));
        vehicleData.put("newAtFirstReg", Integer.toString(vehicle.isNewAtFirstRegistration));
        vehicleData.put("fuelType", vehicle.fuelType.getFuelId());
        vehicleData.put("testClass", vehicle.vehicleClass.getId());
        vehicleData.put("countryOfRegistration",
                vehicle.countryOfRegistration.getcountryOfRegistrationCode());
        vehicleData.put("cylinderCapacity", Integer.toString(vehicle.cylinderCapacity));
        vehicleData.put("transmissionType", vehicle.transType.getTransmissionCode());
        vehicleData.put("bodyType", vehicle.bodyType.getCode());

        JsonObject response = post(resource, vehicleData);

        Vehicle newVehicle = new Vehicle(vehicle);
        newVehicle.carID = String.valueOf(response.getInt("data"));
        newVehicle.carReg = carReg;
        newVehicle.fullVIN = fullVIN;
        newVehicle.isNewAtFirstRegistration = vehicle.isNewAtFirstRegistration;
        newVehicle.manufactureDate = vehicle.manufactureDate;
        newVehicle.dateOfFirstRegistration = vehicle.dateOfFirstRegistration;

        Utilities.Logger.LogInfo(
                "Old vehicle " + vehicle.carReg + " -> " + "New vehicle " + newVehicle.carReg);

        return newVehicle;
    }
}
