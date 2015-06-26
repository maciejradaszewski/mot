package uk.gov.dvsa.helper;

import java.io.IOException;

public interface Hydrator {

    <T> T hydrateObject(String response, Class<T> object) throws IOException;
}
