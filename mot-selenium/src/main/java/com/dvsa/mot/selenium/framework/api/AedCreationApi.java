package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.api.helper.RequestorAttachment;

import javax.json.Json;
import javax.json.JsonArrayBuilder;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;
import java.util.Collection;

public class AedCreationApi extends BaseApi {

    public AedCreationApi() {
        super(testSupportUrl(), null);
    }

    public Login createAed(Collection<Integer> aeIds, Login schm, String diff) {

        JsonObjectBuilder aedCreationData = Json.createObjectBuilder();
        JsonArrayBuilder aeIdsArrayBuilder = Json.createArrayBuilder();

        for (Integer aeId : aeIds) {
            aeIdsArrayBuilder.add(aeId);
        }
        aedCreationData.add("aeIds", aeIdsArrayBuilder);
        RequestorAttachment.attach(schm, aedCreationData);

        if (null != diff) {
            aedCreationData.add("diff", diff);
        }

        JsonObject response = post("testsupport/aed", aedCreationData.build());

        JsonObject responseData = response.getJsonObject("data");
        return new Login(responseData.getString("username"), responseData.getString("password"));
    }


    public void createAedRoleForExistingPerson(Collection<Integer> aeIds, Login schm, Login person,
            int personId) {
        JsonObjectBuilder aedCreationData = Json.createObjectBuilder();

        JsonArrayBuilder aeIdsArrayBuilder = Json.createArrayBuilder();

        for (Integer aeId : aeIds) {
            aeIdsArrayBuilder.add(aeId);
        }
        aedCreationData.add("aeIds", aeIdsArrayBuilder);

        RequestorAttachment.attach(schm, aedCreationData);

        if (null != person) {
            aedCreationData.add("username", person.username);
            aedCreationData.add("password", person.password);
        }

        aedCreationData.add("personId", personId);

        post("testsupport/aed", aedCreationData.build());
    }
}
