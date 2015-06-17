package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.api.helper.RequestorAttachment;

import javax.json.Json;
import javax.json.JsonArrayBuilder;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;
import java.util.Collection;
import java.util.Collections;

public class SiteManagerCreationApi extends BaseApi {

    public SiteManagerCreationApi() {
        super(testSupportUrl(), null);
    }

    public Login createSm(Collection<Integer> siteIds, Login person, String diff) {

        JsonObjectBuilder siteManagerCreationData = Json.createObjectBuilder();

        JsonArrayBuilder siteIdsArrayBuilder = Json.createArrayBuilder();

        for (Integer siteId : siteIds) {
            siteIdsArrayBuilder.add(siteId);
        }
        siteManagerCreationData.add("siteIds", siteIdsArrayBuilder);

        RequestorAttachment.attach(person, siteManagerCreationData);

        if (null != diff) {
            siteManagerCreationData.add("diff", diff);
        }

        JsonObject response = post("testsupport/vts/sm", siteManagerCreationData.build());

        JsonObject responseData = response.getJsonObject("data");
        return new Login(responseData.getString("username"), responseData.getString("password"));
    }

    public Login createSm(int vtsId, Login person) {
        return createSm(Collections.singletonList(vtsId), person, null);
    }

    public void createSmForExistingPerson(Collection<Integer> siteIds, Login person, int personId) {
        JsonObjectBuilder siteManagerCreationData = Json.createObjectBuilder();

        JsonArrayBuilder siteIdsArrayBuilder = Json.createArrayBuilder();

        for (Integer siteId : siteIds) {
            siteIdsArrayBuilder.add(siteId);
        }
        siteManagerCreationData.add("siteIds", siteIdsArrayBuilder);

        RequestorAttachment.attach(person, siteManagerCreationData);

        if (null != person) {
            siteManagerCreationData.add("username", person.username);
            siteManagerCreationData.add("password", person.password);
        }

        siteManagerCreationData.add("personId", personId);

        post("testsupport/vts/sm", siteManagerCreationData.build());
    }
}
