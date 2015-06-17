package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Login;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;

public class SchemeManagementUserCreationApi extends BaseApi {

    public SchemeManagementUserCreationApi() {
        super(testSupportUrl(), null);
    }

    public Login createSchemeManagementUser(String diff) {

        JsonObjectBuilder schmData = Json.createObjectBuilder();

        if (null != diff) {
            schmData.add("diff", diff);
        }

        JsonObject response = post("testsupport/schm", schmData.build());

        JsonObject responseData = response.getJsonObject("data");
        return new Login(responseData.getString("username"), responseData.getString("password"));
    }
}
