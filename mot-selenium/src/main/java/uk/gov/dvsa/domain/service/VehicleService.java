package uk.gov.dvsa.domain.service;

import com.jayway.restassured.response.Response;
import org.apache.commons.lang3.RandomStringUtils;
import org.joda.time.DateTime;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.*;
import uk.gov.dvsa.framework.config.webdriver.WebDriverConfigurator;

import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

public class VehicleService extends Service {
    private static final String CREATE_DVSA_VEHICLE_PATH = "/testsupport/vehicle/create";
    private static final String CREATE_DVLA_VEHICLE_PATH = "/testsupport/dvla-vehicle/create";
    private static final String oneTimePassword = "123456";
    private AuthService authService = new AuthService();

    protected VehicleService() {
        super(WebDriverConfigurator.testSupportUrl());
    }

    @Deprecated
    protected Vehicle createVehicle(User user) throws IOException {
        Map<String, String> vehicleDataMap = prepareDvsaPayload(VehicleClass.four, null);
        return createVehicle(vehicleDataMap, user, CREATE_DVSA_VEHICLE_PATH);
    }

    protected Vehicle createVehicle() throws IOException {
        Map<String, String> vehicleDataMap = prepareDvsaPayload(VehicleClass.four, null);
        return createVehicle(vehicleDataMap, null, CREATE_DVSA_VEHICLE_PATH);
    }

    @Deprecated
    protected Vehicle createVehicle(Integer vehicleWeight, User user) throws IOException {
        Map<String, String> vehicleDataMap = prepareDvsaPayload(VehicleClass.four, vehicleWeight);
        return createVehicle(vehicleDataMap, user, CREATE_DVSA_VEHICLE_PATH);
    }

    protected Vehicle createVehicle(Integer vehicleWeight) throws IOException {
        Map<String, String> vehicleDataMap = prepareDvsaPayload(VehicleClass.four, vehicleWeight);
        return createVehicle(vehicleDataMap, null, CREATE_DVSA_VEHICLE_PATH);
    }

    @Deprecated
    protected Vehicle createDvlaVehicle(User user) throws IOException {
        Map<String, String> vehicleDataMap = prepareDvlaPayload();
        return createVehicle(vehicleDataMap, user, CREATE_DVLA_VEHICLE_PATH);
    }

    protected Vehicle createDvlaVehicle() throws IOException {
        Map<String, String> vehicleDataMap = prepareDvlaPayload();
        return createVehicle(vehicleDataMap, null, CREATE_DVLA_VEHICLE_PATH);
    }

    protected Vehicle createVehicle(Map<String, String> vehicleDataMap, User user, String path) throws IOException {

        String vehicleRequest = jsonHandler.convertToString(vehicleDataMap);

        Response response = postRequest(user, vehicleRequest, path);

        return new Vehicle(vehicleDataMap, ServiceResponse.createResponse(response, String.class));
    }

    private Map<String, String> prepareDvsaPayload(VehicleClass vehicleClass, Integer vehicleWeight) {
        Map<String, String> vehicleDataMap = new HashMap<>();
        VehicleDetails vehicleDetails = VehicleDetails.MercedesBenz_300D;

        vehicleDataMap.put("registrationNumber", generateCarRegistration());
        vehicleDataMap.put("vin", getRandomVin());
        vehicleDataMap.put("make", vehicleDetails.getId());
        vehicleDataMap.put("makeOther", "");
        vehicleDataMap.put("makeName", vehicleDetails.getMake());
        vehicleDataMap.put("model", vehicleDetails.getModelId());
        vehicleDataMap.put("modelName", vehicleDetails.getMakeId());
        vehicleDataMap.put("modelOther", "");
        vehicleDataMap.put("colour", Colour.Black.getId());
        vehicleDataMap.put("secondaryColour", Colour.Yellow.getId());
        vehicleDataMap.put("dateOfFirstUse", getDateMinusYears(5));
        vehicleDataMap.put("dateOfManufacture", getDateMinusYears(5));
        vehicleDataMap.put("firstRegistrationDate",getDateMinusYears(5));
        vehicleDataMap.put("newAtFirstReg", Integer.toString(0));
        vehicleDataMap.put("fuelType", FuelTypes.Petrol.getId());
        vehicleDataMap.put("testClass", vehicleClass.getId());
        vehicleDataMap.put("countryOfRegistration",
                CountryOfRegistration.Great_Britain.getRegistrationCode());
        vehicleDataMap.put("cylinderCapacity", Integer.toString(1700));
        vehicleDataMap.put("transmissionType", TransmissionType.Manual.getCode());
        vehicleDataMap.put("bodyType", BodyType.Hatchback.getCode());
        vehicleDataMap.put("oneTimePassword", oneTimePassword);
        vehicleDataMap.put("returnOriginalId", String.valueOf(true));
        vehicleDataMap.put("weight", null == vehicleWeight ? null : Integer.toString(vehicleWeight));

        return vehicleDataMap;
    }

    private Map<String, String> prepareDvlaPayload() {
        Map<String, String> vehicleDataMap = new HashMap<>();

        vehicleDataMap.put("registration", generateCarRegistration());
        vehicleDataMap.put("vin", getRandomVin());

        return vehicleDataMap;
    }

    private Response postRequest(User user, String vehicleRequest, String path) throws IOException {
        return motClient.createVehicle(vehicleRequest, path, user != null ? authService.createSessionTokenForUser(user) : "");
    }

    private String generateCarRegistration() {
      return RandomStringUtils.randomAlphanumeric(7).toUpperCase();
    }

    private String getRandomVin(){
        return new DefaultVehicleDataRandomizer().nextVin();
    }

    private String getDateMinusYears(int years){
        return DateTime.now().minusYears(years).toString("YYYY-MM-dd");
    }
}
