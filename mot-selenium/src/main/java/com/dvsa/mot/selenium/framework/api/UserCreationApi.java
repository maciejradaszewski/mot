package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Login;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;

public class UserCreationApi extends BaseApi {

    public UserCreationApi() {
        super(testSupportUrl(), null);
    }

    public Login createUser(String diff) {

        JsonObjectBuilder userData = Json.createObjectBuilder();

        if (null != diff) {
            userData.add("diff", diff);
        }

        JsonObject response = post("testsupport/user", userData.build());

        JsonObject responseData = response.getJsonObject("data");
        return new Login(responseData.getString("username"), responseData.getString("password"));
    }
}
