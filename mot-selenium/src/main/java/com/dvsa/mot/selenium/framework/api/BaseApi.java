package com.dvsa.mot.selenium.framework.api;

import com.dvsa.mot.selenium.framework.Configurator;
import org.glassfish.jersey.client.ClientConfig;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;
import javax.ws.rs.client.ClientBuilder;
import javax.ws.rs.client.Entity;
import javax.ws.rs.client.Invocation;
import javax.ws.rs.client.WebTarget;
import javax.ws.rs.core.MediaType;
import javax.ws.rs.core.MultivaluedHashMap;
import javax.ws.rs.core.MultivaluedMap;
import javax.ws.rs.core.Response;
import java.util.Collections;
import java.util.HashMap;
import java.util.Map;

public class BaseApi extends Configurator {

    protected String accessToken;
    protected static String oneTimePassword = "123456";
    protected static String apiUsername = "tester1";
    protected static String apiPassword = "Password1";
    private WebTarget apiEndpoint;

    public BaseApi() {
        this(apiUrl(), fakeAmLogin(apiUsername, apiPassword));
    }

    public BaseApi(String url) {
        this(url, fakeAmLogin(apiUsername, apiPassword));
    }

    public BaseApi(String url, String accessToken) {
        this.accessToken = accessToken;
        changeApiEndpoint(url);
    }

    protected void changeApiEndpoint(String url) {
        apiEndpoint = ClientBuilder.newClient(new ClientConfig()).target(url);
    }

    public JsonObject post(String resource, Map<String, String> form) {
        form.put("oneTimePassword", oneTimePassword);
        JsonObject json = buildJsonObjectFromMap(form);
        return post(resource, json);
    }

    public JsonObject post(String resource, JsonObject json) {
        MultivaluedMap<String, Object> headers = new MultivaluedHashMap<>();

        if (accessToken != null) {
            headers.put("Authorization",
                    Collections.singletonList((Object) ("Bearer " + accessToken)));
        }

        return invokeRestCall(
                apiEndpoint.path(resource).request(MediaType.APPLICATION_JSON).headers(headers)
                        .buildPost(Entity.json(json))).readEntity(JsonObject.class);


    }

    public JsonObject get(String resource, MultivaluedMap<String, Object> headers) {
        return invokeRestCall(apiEndpoint.path(resource).request().headers(headers).buildGet())
                .readEntity(JsonObject.class);
    }

    public void delete(String resource, MultivaluedMap<String, Object> headers) {
        invokeRestCall(apiEndpoint.path(resource).request().headers(headers).buildDelete());
    }

    protected static String fakeAmLogin(String username, String password) {
        Map<String, String> loginForm = new HashMap<>();
        loginForm.put("username", username);
        loginForm.put("password", password);

        /*
         *  Urgh, creating object in static method defined in the class!
         *  This ugly code will be replaced by the OpenAM login code in due course
         */
        JsonObject response = new BaseApi(apiUrl(), null).post("session", loginForm);

        return response.getJsonObject("data").getString("accessToken");
    }

    private Response invokeRestCall(Invocation invocation) {
        Response response = invocation.invoke();

        if (!response.getStatusInfo().getFamily().equals(Response.Status.Family.SUCCESSFUL)) {
            throw new RuntimeException(
                    "Rest invocation returned response code " + response.getStatus() +
                            "\n" + response.readEntity(String.class));
        }

        if (!response.getMediaType().isCompatible(MediaType.APPLICATION_JSON_TYPE)) {
            throw new RuntimeException(
                    "Rest invocation returned unexpected media type " + response.getMediaType() +
                            "\n" + response.readEntity(String.class));
        }

        return response;
    }

    private JsonObject buildJsonObjectFromMap(Map<String, String> hashmap) {
        JsonObjectBuilder builder = Json.createObjectBuilder();

        for (Map.Entry<String, String> entry : hashmap.entrySet()) {
            if (entry.getValue() == null) {
                builder.addNull(entry.getKey());
            } else {
                builder.add(entry.getKey(), entry.getValue());
            }
        }

        return builder.build();
    }
}


