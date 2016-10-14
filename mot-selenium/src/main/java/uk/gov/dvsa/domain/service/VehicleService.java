package uk.gov.dvsa.domain.service;

import com.jayway.restassured.response.Response;

import uk.gov.dvsa.domain.api.client.MotClient;
import uk.gov.dvsa.domain.api.response.Vehicle;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.*;
import uk.gov.dvsa.framework.config.webdriver.WebDriverConfigurator;

import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

public class VehicleService extends Service {
    private static final String CREATE_DVSA_VEHICLE_PATH = "/vehicles/";
    private static final String CREATE_DVLA_VEHICLE_PATH = "/testsupport/dvla-vehicle/create";

    private AuthService authService = new AuthService();

    protected VehicleService() {
        super(WebDriverConfigurator.vehicleServiceUrl());
    }

    protected DvlaVehicle createDvlaVehicle(
            User user,
            String registration,
            String vin,
            String make_code,
            String model_code
    ) throws IOException {

        Map<String, String> dvlaVehicleDataMap = new HashMap<>();
        dvlaVehicleDataMap.put("registration", registration);
        dvlaVehicleDataMap.put("vin", vin);
        dvlaVehicleDataMap.put("make_code", make_code);
        dvlaVehicleDataMap.put("model_code", model_code);
        dvlaVehicleDataMap.put("returnVehicleDetail", "true");

        String vehicleRequest = jsonHandler.convertToString(dvlaVehicleDataMap);

        MotClient motClient = new MotClient(WebDriverConfigurator.testSupportUrl());
        Response response = motClient.createVehicle(
                vehicleRequest, CREATE_DVLA_VEHICLE_PATH, authService.createSessionTokenForUser(user));

        return ServiceResponse.hydrateResponse(response, "data", DvlaVehicle.class);
    }

    protected Vehicle createVehicle(
            User user,
            String oneTimePasswordPin,
            String colourId,
            String countryOfRegistrationId,
            String cylinderCapacity,
            String firstUsedDate,
            String fuelTypeId,
            String makeId,
            String modelId,
            String registration,
            String secondaryColourId,
            String vin,
            String vehicleClassId,
            String transmissionTypeId
    ) throws IOException {
        Map<String, Map<String, String>> vehicleDataMap = prepareDvsaVehiclePayloadMap(
                oneTimePasswordPin,
                colourId,
                countryOfRegistrationId,
                cylinderCapacity,
                firstUsedDate,
                fuelTypeId,
                makeId,
                modelId,
                registration,
                secondaryColourId,
                vin,
                vehicleClassId,
                transmissionTypeId
        );

        String vehicleRequest = jsonHandler.convertToString(vehicleDataMap);

        Response response = motClient.createVehicle(
                vehicleRequest, CREATE_DVSA_VEHICLE_PATH, authService.createSessionTokenForUser(user));

        return ServiceResponse.hydrateResponse(response, "", Vehicle.class);
    }

    private Map<String, Map<String, String>> prepareDvsaVehiclePayloadMap(
            String oneTimePasswordPin,
            String colourId,
            String countryOfRegistrationId,
            String cylinderCapacity,
            String firstUsedDate,
            String fuelTypeId,
            String makeId,
            String modelId,
            String registration,
            String secondaryColourId,
            String vin,
            String vehicleClassId,
            String transmissionTypeId
    ) throws IOException {

        Map<String, String> vehicleDataMap = new HashMap<>();
        vehicleDataMap.put("colourId", colourId);
        vehicleDataMap.put("countryOfRegistrationId", countryOfRegistrationId);
        vehicleDataMap.put("cylinderCapacity", cylinderCapacity);
        vehicleDataMap.put("firstUsedDate", firstUsedDate);
        vehicleDataMap.put("fuelTypeId", fuelTypeId);
        vehicleDataMap.put("makeId", makeId);
        vehicleDataMap.put("modelId", modelId);
        vehicleDataMap.put("registration", registration);
        vehicleDataMap.put("secondaryColourId", secondaryColourId);
        vehicleDataMap.put("vin", vin);
        vehicleDataMap.put("vehicleClassId", vehicleClassId);
        vehicleDataMap.put("transmissionTypeId", transmissionTypeId);

        Map<String, String> oneTimePasswordPinMap = new HashMap<>();
        oneTimePasswordPinMap.put("pin", oneTimePasswordPin);

        Map<String, Map<String, String>> payloadMap = new HashMap<>();
        payloadMap.put("vehicle", vehicleDataMap);
        payloadMap.put("oneTimePassword", oneTimePasswordPinMap);

        return payloadMap;
    }
}
