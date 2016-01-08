package uk.gov.dvsa.data;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.service.UserService;

import java.io.IOException;

public class UserData extends UserService{

    public UserData() {}

    public User createTester(int siteId) throws IOException {
        return createUserAsTester(siteId);
    }

    public User createTester(int siteId, boolean claimAccount) throws IOException {
        return createUserAsTester(siteId, claimAccount);
    }

    public User createCustomerServiceOfficer(boolean claimAccount) throws IOException {
        return createUserAsCsco(claimAccount);
    }

    public User createAedm(boolean claimAccount) throws IOException {
        int aeId = new AeData().createAeWithDefaultValues().getId();
        return createUserAsAedm(aeId, "def_ae", claimAccount);
    }

    public User createAedm(int aeId, String namePrefix, boolean claimAccount) throws IOException {
        return createUserAsAedm(aeId, namePrefix, claimAccount);
    }

    public User createVehicleExaminer(String namePrefix, boolean accountClaimRequired) throws IOException {
        return createUserAsVE(namePrefix, accountClaimRequired);
    }

    public User createSchemeUser(boolean accountClaimRequired) throws IOException {
        return createUserAsSchemeUser(accountClaimRequired);
    }

    public User createAreaOfficeOne(String namePrefix) throws IOException {
        return createUserAsAreaOfficeOneUser(namePrefix);
    }

    public User createAreaOfficeTwo(String namePrefix) throws IOException {
        return createUserAsAreaOfficeTwo(namePrefix);
    }

    public User createSiteManager(int siteId, boolean accountClaimRequired) throws IOException {
        return createUserAsSiteManager(siteId, accountClaimRequired);
    }

    public User createSiteAdmin(int siteId, boolean accountClaimRequired) throws IOException {
        return createUserAsSiteAdmin(siteId, accountClaimRequired);
    }

    public User createDvlaOfficer(String diff) throws IOException {
        return createDvlaOfficerUser(diff);
    }
}
