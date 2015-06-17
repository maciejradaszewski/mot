package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.api.helper.RequestorAttachment;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;

/**
 * @Deprecated
 * Use AeService instead
 */
@Deprecated
public class AeCreationApi extends BaseApi {

    public AeCreationApi() {
        super(testSupportUrl(), null);
    }

    public int createAe(String diff, Login schm) {
        return createAe(diff, schm, null);
    }

    public int createAe(AeData data, Login schm) {
        JsonObjectBuilder aeCreationData = Json.createObjectBuilder();

        aeCreationData.add("slots", data.slots);
        if (data.name != null) {
            aeCreationData.add("organisationName", data.name);
        }
        if (data.email != null) {
            aeCreationData.add("emailAddress", data.email);
        }
        RequestorAttachment.attach(schm, aeCreationData);

        JsonObject response = post("testsupport/ae", aeCreationData.build());

        JsonObject responseData = response.getJsonObject("data");
        return responseData.getInt("id");
    }

    public int createAe(String diff, Login schm, Integer slots) {
        JsonObjectBuilder aeCreationData = Json.createObjectBuilder();

        if (null != diff) {
            aeCreationData.add("diff", diff);
        }

        if (null != slots) {
            aeCreationData.add("slots", slots.toString());
        }

        RequestorAttachment.attach(schm, aeCreationData);

        JsonObject response = post("testsupport/ae", aeCreationData.build());

        JsonObject responseData = response.getJsonObject("data");
        return responseData.getInt("id");
    }


    public static class AeData {
        String name;
        int slots;
        String email;

        public AeData name(String name) {
            this.name = name;
            return this;
        }

        public AeData slots(int slots) {
            this.slots = slots;
            return this;
        }

        public AeData email(String email) {
            this.email = email;
            return this;
        }
    }

}
