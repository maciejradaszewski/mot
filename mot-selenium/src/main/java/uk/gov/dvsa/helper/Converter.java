package uk.gov.dvsa.helper;

import java.io.IOException;

public interface Converter {

    String convertToString(Object object) throws IOException;
}
