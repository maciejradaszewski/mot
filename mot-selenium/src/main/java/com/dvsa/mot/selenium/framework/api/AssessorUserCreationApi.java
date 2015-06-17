package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Login;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;

public class AssessorUserCreationApi extends BaseApi {

    public AssessorUserCreationApi() {
        super(testSupportUrl(), null);
    }

    public Login createAssessorUser(String diff) {

        JsonObjectBuilder assessorUserData = Json.createObjectBuilder();

        if (null != diff) {
            assessorUserData.add("diff", diff);
        }

        JsonObject response = post("testsupport/assessor", assessorUserData.build());

        JsonObject responseData = response.getJsonObject("data");
        return new Login(responseData.getString("username"), responseData.getString("password"));
    }

    public Login createAssessorUser() {
        return createAssessorUser(null);
    }
}
