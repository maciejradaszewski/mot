package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Login;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;

public class VehicleExaminerUserCreationApi extends BaseApi {

    public VehicleExaminerUserCreationApi() {
        super(testSupportUrl(), null);
    }

    public Login createVehicleExaminerUser(String diff) {

        JsonObjectBuilder vehicleExaminerData = Json.createObjectBuilder();

        if (null != diff) {
            vehicleExaminerData.add("diff", diff);
        }

        JsonObject response = post("testsupport/vehicleexaminer", vehicleExaminerData.build());

        JsonObject responseData = response.getJsonObject("data");
        return new Login(responseData.getString("username"), responseData.getString("password"));
    }

    public Login createVehicleExaminerUser() {
        return createVehicleExaminerUser(null);
    }
}
