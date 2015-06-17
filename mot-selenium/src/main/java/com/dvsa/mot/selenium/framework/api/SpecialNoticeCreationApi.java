package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Login;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;
import java.util.HashMap;
import java.util.Map;

public class SpecialNoticeCreationApi extends BaseApi {
    public SpecialNoticeCreationApi() {
        super(apiUrl(), fakeAmLogin(Login.LOGIN_SCHEME_USER.username,
                Login.LOGIN_SCHEME_USER.password));
    }

    public void broadcast(final String username, final int specialNoticeContentId,
            boolean isAcknowledged) {
        changeApiEndpoint(testSupportUrl());

        Map<String, String> params = new HashMap<>();
        params.put("username", username);
        params.put("specialNoticeContentId", Integer.toString(specialNoticeContentId));
        params.put("isAcknowledged", Boolean.toString(isAcknowledged));

        post("testsupport/special-notice/broadcast", params);
    }

    public int create(final String publishDate, boolean isPublished, final String title) {
        changeApiEndpoint(apiUrl());

        Map<String, String> params = new HashMap<>();
        params.put("noticeTitle", title);
        params.put("internalPublishDate", publishDate);
        params.put("externalPublishDate", publishDate);
        params.put("acknowledgementPeriod", "5");
        params.put("noticeText", "notice text");

        JsonObjectBuilder builder = Json.createObjectBuilder();
        for (Map.Entry<String, String> entry : params.entrySet()) {
            builder.add(entry.getKey(), entry.getValue());
        }
        builder.add("targetRoles", Json.createArrayBuilder().add("TESTER-CLASS-1"));

        JsonObject response = post("/special-notice-content", builder.build());
        int id = response.getJsonObject("data").getInt("id");


        if (isPublished) {
            post("/special-notice-content/" + id + "/publish", new HashMap<String, String>());
        }

        return id;
    }
}
