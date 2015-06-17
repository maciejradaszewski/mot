package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.Configurator;
import com.dvsa.mot.selenium.framework.api.helper.UrlHelper;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;
import java.util.HashMap;
import java.util.Map;

public class AuthService extends Configurator{
    private MotClient motClient = new MotClient(UrlHelper.buildWebTargetUrl(apiUrl()));

    public String createSessionTokenForUser(Login login) {
        Map<String, String> loginForm = new HashMap<>();
        loginForm.put("username", login.username);
        loginForm.put("password", login.password);

        JsonObject response = motClient.post("session", loginForm);
        return response.getJsonObject("data").getString("accessToken");
    }

    public String getResetPasswordToken(int userId){
        JsonObjectBuilder buildRequest = Json.createObjectBuilder();
        buildRequest.add("userId", userId);

        JsonObject response = motClient.resetPassword("reset-password", buildRequest.build());
        return response.getJsonObject("data").getString("token");
    }

    public void forceUserLockout(Login login, int numberOfAttempts) {
        Map<String, String> loginForm = new HashMap<>();
        loginForm.put("username", login.username);
        loginForm.put("password", "WRONG");
        for (int i=0; i < numberOfAttempts; i++) {
            motClient.post("session", loginForm);
        }
    }
}

