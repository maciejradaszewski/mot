package uk.gov.dvsa.domain.service;

import com.jayway.restassured.response.Response;
import org.apache.http.HttpStatus;
import uk.gov.dvsa.helper.JsonHandler;

import java.io.IOException;

public class ServiceResponse {
    private static JsonHandler jsonHandler = new JsonHandler();

    protected static  <T> T createResponse(final Response response, final Class<T> clazz) throws IOException {
        handleNon200Response(response);
        return createResponse(response, null, clazz);
    }

    protected static  <T> T createResponse(final Response response, final String path, final Class<T> clazz) throws IOException {
        handleNon200Response(response);

        return jsonHandler.hydrateObject(
                jsonHandler.convertToString(response.body().path(path == null ? "data" : "data." + path)), clazz);
    }

    private static void handleNon200Response(final Response response) {
        if (response.statusCode() != HttpStatus.SC_OK) {
            throw new IllegalStateException(
                    response.body().path("errors.exception.message").toString());
        }
    }
}
