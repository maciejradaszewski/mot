package com.dvsa.mot.selenium.framework.api.helper;


import com.dvsa.mot.selenium.datasource.Login;

import javax.json.Json;
import javax.json.JsonObjectBuilder;

public class RequestorAttachment {

    public static void attach(Login login, JsonObjectBuilder parent) {
        parent.add("requestor", Json.createObjectBuilder().add("username", login.username)
                        .add("password", login.password).build());
    }
}
