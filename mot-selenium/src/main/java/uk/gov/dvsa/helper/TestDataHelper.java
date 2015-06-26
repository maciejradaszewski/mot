package uk.gov.dvsa.helper;

import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.Vehicle;
import uk.gov.dvsa.domain.service.ServiceLocator;

import java.io.IOException;

public class TestDataHelper {

    public static Vehicle getNewVehicle() throws IOException {
        AeDetails aeDetails = ServiceLocator.getAeService().createAe("default");
        Site site = ServiceLocator.getSiteService().createSite(aeDetails.getId(), "default-Site");
        User tester = ServiceLocator.getUserService().createUserAsTester(site.getId());

        return ServiceLocator.getVehicleService().createVehicle(tester);
    }

    public static User createTester(int siteId) throws IOException {
        return ServiceLocator.getUserService().createUserAsTester(siteId);
    }

    public static User createTester(int siteId, boolean claimAccount) throws IOException {
        return ServiceLocator.getUserService().createUserAsTester(siteId, claimAccount);
    }

    public static User createCsco(boolean claimAccount) throws IOException {
        return ServiceLocator.getUserService().createUserAsCsco(claimAccount);
    }

    public static User createAedm(boolean claimAccount) throws IOException {
        AeDetails aeDetails = ServiceLocator.getAeService().createAe("default");
        return ServiceLocator.getUserService().createUserAsAedm(aeDetails.getId(), "def_ae", claimAccount);
    }

    public static User createAedm(int aeId, String namePrefix, boolean claimAccount) throws IOException {
        return ServiceLocator.getUserService().createUserAsAedm(aeId, namePrefix, claimAccount);
    }

    public static Site createSite(int aeId, String name) throws IOException {
        return ServiceLocator.getSiteService().createSite(aeId, name);
    }

    public static AeDetails createAe() throws IOException {
        return ServiceLocator.getAeService().createAe("default", 7);
    }
}
