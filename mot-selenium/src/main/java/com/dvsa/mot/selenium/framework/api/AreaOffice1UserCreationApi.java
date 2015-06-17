package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Login;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;

public class AreaOffice1UserCreationApi extends BaseApi {

    public AreaOffice1UserCreationApi() {

        super(testSupportUrl(), null);
    }

    public Login createAreaOffice1User(String diff) {

        JsonObjectBuilder areaOffice1Data = Json.createObjectBuilder();

        if (null != diff) {
            areaOffice1Data.add("diff", diff);
        }

        JsonObject response = post("testsupport/areaoffice1", areaOffice1Data.build());

        JsonObject responseData = response.getJsonObject("data");
        return new Login(responseData.getString("username"), responseData.getString("password"));
    }

    public Login createAreaOffice1User() {
        return createAreaOffice1User(null);
    }
}
