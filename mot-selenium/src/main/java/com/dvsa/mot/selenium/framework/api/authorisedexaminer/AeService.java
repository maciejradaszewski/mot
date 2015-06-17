package com.dvsa.mot.selenium.framework.api.authorisedexaminer;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.Configurator;
import com.dvsa.mot.selenium.framework.api.AuthService;
import com.dvsa.mot.selenium.framework.api.MotClient;
import com.dvsa.mot.selenium.framework.api.helper.UrlHelper;
import com.dvsa.mot.selenium.framework.api.helper.RequestorAttachment;

import javax.json.*;

public class AeService extends Configurator {

    private AuthService authService = new AuthService();
    private MotClient motClient = new MotClient(UrlHelper.buildWebTargetUrl(testSupportUrl()),
            authService.createSessionTokenForUser(Login.LOGIN_AREA_OFFICE1));

    public AeDetails createAe(String namePrefix){
        return createAe(namePrefix, Login.LOGIN_AREA_OFFICE1);
    }

    public AeDetails createAe(String namePrefix, Login userLogin) {
        return createAe(namePrefix, userLogin, null);
    }

    public AeDetails createAe(String namePrefix, Login userLogin, Integer slots) {
        JsonObjectBuilder aeCreationData = Json.createObjectBuilder();

        if (null != namePrefix) {
            aeCreationData.add("diff", namePrefix);
        }

        if (null != slots) {
            aeCreationData.add("slots", slots.toString());
        }

        RequestorAttachment.attach(userLogin, aeCreationData);

        JsonObject response = motClient.post("testsupport/ae", aeCreationData.build());

        JsonObject responseData = response.getJsonObject("data");

        AeDetails aeDetails = new AeDetails();

        aeDetails.setId(responseData.getInt("id"));
        aeDetails.setAeRef(responseData.getString("aeRef"));
        aeDetails.setAeName(responseData.getString("aeName"));

        return aeDetails;
    }
}
