package uk.gov.dvsa.domain.api.client;

import com.jayway.restassured.RestAssured;
import com.jayway.restassured.response.Response;

import static com.jayway.restassured.RestAssured.with;

public class MotClient {

    private String endpointUrl;

    public MotClient(String endpointUrl) {
        RestAssured.useRelaxedHTTPSValidation();
        this.endpointUrl = endpointUrl;
    }

    public Response createSession(String request, String path) {
        return postRequestWithoutToken(request, path);
    }

    public Response createAe(String request, String path, String token) {
        return postRequest(request, path, token);
    }

    public Response post(String request, String path, String token) {
        return postRequest(request, path, token);
    }

    public Response createUser(String request, String path, String token) {
        return postRequest(request, path, token);
    }

    public Response createUser(String request, String path) {
        return postRequestWithoutToken(request, path);
    }

    public Response createVehicle(String request, String path, String token) {
        return postRequest(request, path, token);
    }

    public Response createSite(String request, String path) {
        return postRequestWithoutToken(request, path);
    }

    private Response postRequestWithoutToken(String request, String path) {
        return with()
                .header("Content-Type", "application/json")
                .body(request)
                .post(endpointUrl + path);
    }

    private Response postRequest(String request, String path, String token) {
        return with()
                .header("Authorization", "Bearer " + token)
                .header("Content-Type", "application/json")
                .body(request)
                .post(endpointUrl + path);
    }
}
