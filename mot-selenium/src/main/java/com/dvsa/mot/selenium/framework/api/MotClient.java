package com.dvsa.mot.selenium.framework.api;

import javax.json.Json;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;
import javax.ws.rs.client.Entity;
import javax.ws.rs.client.Invocation;
import javax.ws.rs.client.WebTarget;
import javax.ws.rs.core.MediaType;
import javax.ws.rs.core.MultivaluedHashMap;
import javax.ws.rs.core.MultivaluedMap;
import javax.ws.rs.core.Response;
import java.util.Collections;
import java.util.Map;

public class MotClient {

    private WebTarget endpointUrl;
    private String accessToken;

    public MotClient(WebTarget endpointUrl) {
        this.endpointUrl = endpointUrl;
    }

    public MotClient(WebTarget endpointUrl, String accessToken) {
        this(endpointUrl);
        this.accessToken = accessToken;
    }

    public JsonObject post(String resource, JsonObject requestBody) {
        MultivaluedMap<String, Object> headers = new MultivaluedHashMap<>();

        if (accessToken != null) {
            headers.put("Authorization",
                    Collections.singletonList((Object) ("Bearer " + accessToken)));
        }

        return invokeRestCall(
                endpointUrl.path(resource).request(MediaType.APPLICATION_JSON).headers(headers)
                        .buildPost(Entity.json(requestBody))).readEntity(JsonObject.class);
    }

    public JsonObject post(String resource, Map<String, String> form) {
        JsonObject json = buildJsonObjectFromMap(form);
        return post(resource, json);
    }

    private JsonObject buildJsonObjectFromMap(Map<String, String> hashmap) {
        JsonObjectBuilder builder = Json.createObjectBuilder();

        for (Map.Entry<String, String> entry : hashmap.entrySet()) {
            builder.add(entry.getKey(), entry.getValue());
        }

        return builder.build();
    }

    public JsonObject resetPassword(String endpoint, JsonObject requestBody) {
        return invokeRestCall(
                endpointUrl.path(endpoint).request(MediaType.APPLICATION_JSON)
                        .buildPost(Entity.json(requestBody))).readEntity(JsonObject.class);
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
}
