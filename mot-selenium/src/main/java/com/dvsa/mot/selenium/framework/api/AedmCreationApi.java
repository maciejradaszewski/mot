package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Address;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.framework.api.helper.RequestorAttachment;

import javax.json.Json;
import javax.json.JsonArrayBuilder;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;
import java.util.Collection;
import java.util.Collections;

public class AedmCreationApi extends BaseApi {

    public AedmCreationApi() {
        super(testSupportUrl(), null);
    }

    public JsonObject createAedm(Collection<Integer> aeIds, Login schm, String diff,
            boolean accountClaimRequired) {

        JsonObjectBuilder aedmCreationData = Json.createObjectBuilder();

        JsonArrayBuilder aeIdsArrayBuilder = Json.createArrayBuilder();

        for (Integer aeId : aeIds) {
            aeIdsArrayBuilder.add(aeId);
        }
        aedmCreationData.add("aeIds", aeIdsArrayBuilder);

        aedmCreationData.add("accountClaimRequired", accountClaimRequired);
        RequestorAttachment.attach(schm, aedmCreationData);

        if (null != diff) {
            aedmCreationData.add("diff", diff);
        }

        JsonObject response = post("testsupport/aedm", aedmCreationData.build());

        return response.getJsonObject("data");
    }

    public Login createAedm(int aedmId, Login schm, boolean claimAccountReq) {
        Person person =
                createAedmAsPerson(Collections.singletonList(aedmId), schm, claimAccountReq);
        return new Login(person.login.username, person.login.password);
    }


    public Person createAedmAsPerson(Collection<Integer> aeIds, Login schm,
            boolean accountClaimRequired) {
        JsonObjectBuilder aedmCreationData = Json.createObjectBuilder();

        JsonArrayBuilder aeIdsArrayBuilder = Json.createArrayBuilder();

        for (Integer aeId : aeIds) {
            aeIdsArrayBuilder.add(aeId);
        }
        aedmCreationData.add("aeIds", aeIdsArrayBuilder);

        aedmCreationData.add("accountClaimRequired", accountClaimRequired);
        RequestorAttachment.attach(schm, aedmCreationData);

        JsonObject response = post("testsupport/aedm", aedmCreationData.build());

        JsonObject responseData = response.getJsonObject("data");

        return new Person(Integer.toString(responseData.getInt("personId")), null,
                responseData.getString("firstName"), "", responseData.getString("surname"), 0, 0,
                null, null, "test@email.com", null, null, Address.ADDRESS_ADDRESS1, null,
                new Login(responseData.getString("username"), responseData.getString("password")),
                null, null);
    }

    public Person createAedmAsPerson(int aeId) {
        return createAedmAsPerson(Collections.singletonList(aeId), Login.LOGIN_AREA_OFFICE2, false);
    }
}
