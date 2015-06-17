package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Login;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;

public class AreaOffice2UserCreationApi extends BaseApi {

    public AreaOffice2UserCreationApi() {

        super(testSupportUrl(), null);
    }

    public Login createAreaOffice2User(String diff) {

        JsonObjectBuilder areaOffice2Data = Json.createObjectBuilder();

        if (null != diff) {
            areaOffice2Data.add("diff", diff);
        }

        JsonObject response = post("testsupport/areaoffice2", areaOffice2Data.build());

        JsonObject responseData = response.getJsonObject("data");
        return new Login(responseData.getString("username"), responseData.getString("password"));
    }

    public Login createAreaOffice2User() {
        return createAreaOffice2User(null);
    }
}
