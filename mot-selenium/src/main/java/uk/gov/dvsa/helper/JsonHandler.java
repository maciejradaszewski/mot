package uk.gov.dvsa.helper;

import com.fasterxml.jackson.core.JsonParser;
import com.fasterxml.jackson.databind.ObjectMapper;

import java.io.IOException;

public class JsonHandler implements Converter, Hydrator {

    ObjectMapper mapper;

    public JsonHandler() {
        mapper = new ObjectMapper();
        mapper.configure(JsonParser.Feature.ALLOW_UNQUOTED_FIELD_NAMES, true);
    }

    public <T> T hydrateObject(String response, Class<T> objectType) throws IOException{
            return mapper.readValue(response, objectType);
    }

    public String convertToString(Object objectValue) throws IOException {
        return mapper.writeValueAsString(objectValue);
    }
}
