package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Address;
import com.dvsa.mot.selenium.datasource.Contact;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.framework.api.helper.RequestorAttachment;

import javax.json.Json;
import javax.json.JsonArrayBuilder;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;
import java.util.Collection;

public class VtsCreationApi extends BaseApi {

    public VtsCreationApi() {
        super(testSupportUrl(), null);
    }

    /**
     * creates a VTS authorised for the specified vehicle classes
     *
     * @return VTS id
     */
    public int createVts(int aeId, Collection<Integer> classes, Login schm, String diff) {
        return createVtsSite(aeId, null, classes, schm, diff).getId();
    }

    /**
     * creates a VTS authorised for the specified groups of vehicle classes
     *
     * @return VTS id
     */
    public int createVts(int aeId, TestGroup testGroup, Login schm, String diff) {
        return createVtsSite(aeId, testGroup, null, schm, diff).getId();
    }

    /**
     * creates a VTS site authorised for the specified groups of vehicle classes
     *
     * @return vts data
     */
    public Site createVtsSite(int aeId, TestGroup testGroup, Login schm, String diff) {
        return createVtsSite(aeId, testGroup, null, schm, diff);
    }

    public int createVts(VtsData vtsData, Login schm) {
        JsonObjectBuilder vtsCreationData = Json.createObjectBuilder();

        JsonArrayBuilder classesArrayBuilder = Json.createArrayBuilder();
        if (null != vtsData.classes) {
            for (Integer vehicleClass : vtsData.classes) {
                classesArrayBuilder.add(vehicleClass);
            }
            vtsCreationData.add("classes", classesArrayBuilder);
        }
        vtsCreationData.add("siteName", vtsData.name);
        vtsCreationData.add("emailAddress", vtsData.email);
        vtsCreationData.add("addressLine1", vtsData.addressLine1);

        RequestorAttachment.attach(schm, vtsCreationData);

        vtsCreationData.add("aeId", String.valueOf(vtsData.aeId));

        JsonObject response = post("testsupport/vts", vtsCreationData.build());

        JsonObject responseData = response.getJsonObject("data");
        return responseData.getInt("id");
    }

    private Site createVtsSite(int aeId, TestGroup testGroup, Collection<Integer> classes,
            Login schm, String diff) {

        JsonObjectBuilder vtsCreationData = Json.createObjectBuilder();

        if (null != diff) {
            vtsCreationData.add("diff", diff);
        }

        if (null != testGroup) {
            vtsCreationData.add("testGroup", String.valueOf(testGroup.group));
        }

        JsonArrayBuilder classesArrayBuilder = Json.createArrayBuilder();

        if (null != classes) {
            for (Integer vehicleClass : classes) {
                classesArrayBuilder.add(vehicleClass);
            }
            vtsCreationData.add("classes", classesArrayBuilder);
        }
        RequestorAttachment.attach(schm, vtsCreationData);

        vtsCreationData.add("aeId", String.valueOf(aeId));

        JsonObject response = post("testsupport/vts", vtsCreationData.build());

        JsonObject responseData = response.getJsonObject("data");

        Address address = new Address(null, null, null, responseData.getString("town"), null, responseData.getString("postcode"));
        Contact contact = new Contact(address, null, null, null);

        return new Site(responseData.getInt("id"), responseData.getString("name"),
                responseData.getString("siteNumber"), contact, null);
    }

    public static class VtsData {
        int aeId;
        Collection<Integer> classes;
        String name;
        String email;
        String addressLine1;

        public VtsData aeId(int id) {
            aeId = id;
            return this;
        }

        public VtsData classes(Collection<Integer> classes) {
            this.classes = classes;
            return this;
        }

        public VtsData name(String name) {
            this.name = name;
            return this;
        }

        public VtsData email(String email) {
            this.email = email;
            return this;
        }

        public VtsData addressLine1(String addressLine1) {
            this.addressLine1 = addressLine1;
            return this;
        }
    }
}
