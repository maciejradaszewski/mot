package uk.gov.dvsa.helper;

import org.testng.SkipException;
import uk.gov.dvsa.domain.service.FeaturesService;

import java.io.IOException;

public class ConfigHelper {

    private static FeaturesService service = new FeaturesService();

    public static void isJasperAsyncEnabled() throws IOException {
        if (!service.getToggleValue("jasper.async")) {
            throw new SkipException("Jasper Async not Enabled");
        }
    }

    public static boolean isOpenamDASEnabled() throws IOException {
        return service.getToggleValue("openam.das.enabled");
    }
}
