package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.Utilities;
import org.joda.time.DateTime;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;

public class V5CCreationApi extends BaseApi {

    public V5CCreationApi() {
        super(testSupportUrl(), null);
    }

    public boolean addV5C(Vehicle vehicle, String v5c, DateTime firstSeen, DateTime lastSeen,
            String mot1LegacyId) {
        JsonObjectBuilder data = Json.createObjectBuilder();
        data.add("vehicleId", vehicle.carID).add("v5cRef", v5c);

        if (firstSeen != null) {
            data.add("firstSeen", Utilities.dateTimeToString(firstSeen));
        } else {
            data.addNull("firstSeen");
        }

        if (lastSeen != null) {
            data.add("lastSeen", Utilities.dateTimeToString(lastSeen));
        } else {
            data.addNull("lastSeen");
        }

        if (mot1LegacyId != null) {
            data.add("mot1LegacyId", mot1LegacyId);
        } else {
            data.addNull("mot1LegacyId");
        }


        JsonObject response = post("testsupport/vehicle/v5c-add", data.build());
        String result = response.getString("data");
        return result != null && result.equals("Success");
    }

}
