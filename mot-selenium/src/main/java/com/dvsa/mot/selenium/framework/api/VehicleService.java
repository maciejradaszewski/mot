package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.DvlaVehicle;
import com.dvsa.mot.selenium.framework.Configurator;
import com.dvsa.mot.selenium.framework.Utilities;
import com.dvsa.mot.selenium.framework.api.helper.UrlHelper;

import javax.json.JsonObject;
import java.util.HashMap;
import java.util.Map;

public class VehicleService extends Configurator {
    private MotClient motClient = new MotClient(UrlHelper.buildWebTargetUrl(testSupportUrl()));
    public DvlaVehicle dvlaVehicle;

    public String createDvlaVehicle(DvlaVehicle dvlaVehicle) {

        Map<String, String> dvlaVehicleData = new HashMap<>();

        dvlaVehicleData.put("model_code", dvlaVehicle.getModelCode());
        dvlaVehicleData.put("make_code", dvlaVehicle.getMakeCode());

        JsonObject response = motClient.post("testsupport/dvla-vehicle/create", dvlaVehicleData);

        Utilities.Logger.LogInfo(
                "DVLA vehicle created");

        return String.valueOf(response.getString("data"));
    }
}
