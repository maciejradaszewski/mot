package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.RandomDataGenerator;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;

public class SchemeUserCreationApi extends BaseApi {

    public SchemeUserCreationApi() {
        super(testSupportUrl(), null);
    }

    public Login createSchemeUser() {

        JsonObjectBuilder schemeUserData = Json.createObjectBuilder();
        JsonObject response = post("testsupport/schemeuser", schemeUserData.build());
        JsonObject responseData = response.getJsonObject("data");
        return new Login(responseData.getString("username"), responseData.getString("password"));
    }
}
