package com.dvsa.mot.selenium.framework.api.vehicle;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.api.BaseApi;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;

public class Vm10519userCreationApi extends BaseApi {

    public Vm10519userCreationApi() {

        super(testSupportUrl(), null);
    }

    public Login createVm10519user(String diff) {

        JsonObjectBuilder vm10519userData = Json.createObjectBuilder();

        if (null != diff) {
            vm10519userData.add("diff", diff);
        }

        JsonObject response = post("testsupport/vm10519user", vm10519userData.build());

        JsonObject responseData = response.getJsonObject("data");
        return new Login(responseData.getString("username"), responseData.getString("password"));
    }

    public Login createVm10519user() {
        return createVm10519user(null);
    }
}
