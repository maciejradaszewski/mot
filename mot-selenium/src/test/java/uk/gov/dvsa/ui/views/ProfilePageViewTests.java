package uk.gov.dvsa.ui.views;

import org.testng.annotations.BeforeClass;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.helper.RandomDataGenerator;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.profile.NewProfilePage;
import uk.gov.dvsa.ui.pages.profile.ProfilePage;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class ProfilePageViewTests extends BaseTest {

    private User tester;
    private User schemeManager;
    private User areaOffice1User;
    private User areaOffice2User;
    private User vehicleExaminer;
    private User csco;
    private User aedm;
    private String randomName = RandomDataGenerator.generateRandomString(5, System.nanoTime());

    @BeforeClass(alwaysRun = true)
    private void setup() throws IOException {
        AeDetails aeDetails = aeData.createAeWithDefaultValues();
        Site site = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        tester = userData.createTester(site.getId());
        schemeManager = userData.createSchemeUser(false);
        areaOffice1User = userData.createAreaOfficeOne(randomName);
        areaOffice2User = userData.createAreaOfficeTwo(randomName);
        vehicleExaminer = userData.createVehicleExaminer(randomName, false);
        csco = userData.createCustomerServiceOfficer(false);
        aedm = userData.createAedm(aeDetails.getId(), randomName, false);
    }

    @Test(groups = {"Regression"}, description = "VM-10334")
    public void testerQualificationStatusDisplayedOnProfilePage() throws IOException, URISyntaxException {

        //Given I'm on the Your Profile Details page
        ProfilePage profilePage = pageNavigator.goToPage(tester,  ProfilePage.PATH, ProfilePage.class);

        //Then I should be able to see the qualification status
        assertThat(profilePage.isTesterQualificationStatusDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "VM-12321"},
            description = "Verifies that trade user can check own roles via roles and associations link " +
                    "on it's own profile page")
    public void tradeUserCanViewHisOwnRolesAndAssociations() throws IOException, URISyntaxException {

        //Given I'm on the Your Profile Details page
        ProfilePage profilePage = pageNavigator.goToPage(tester, ProfilePage.PATH, ProfilePage.class);

        //When I click on Roles and Associations link
        profilePage.clickRolesAndAssociationsLink();

        //Then roles should be displayed
        assertThat(motUI.manageRoles.isRolesTableContainsValidTesterData(), is(true));
    }

    @Test(groups = {"Regression", "BL-448"},
            description = "Verifies that authorised dvsa user can see change driving licence link and date of birth " +
                    "information on trade user profile",
            dataProvider = "dvsaUserForPersonalDetails")
    public void dvsaUserCanSeeUserPersonalDetails(User user, boolean isChangeLinkDisplayed) throws IOException, URISyntaxException {

        //Given I'm on the Trade user New Profile Details page as authorised DVSA user
        NewProfilePage newProfilePage = navigateToNewProfilePage(user, tester, false);

        //Then Driving licence information should be displayed
        assertThat(newProfilePage.isDrivingLicenceAndDOBInformationIsDisplayed(), is(true));

        //And Change driving licence link should be displayed if appropriate
        assertThat(newProfilePage.isChangeDrivingLicenceLinkIsDisplayed(), is(isChangeLinkDisplayed));
    }

    @Test(groups = {"Regression", "BL-448"},
            description = "Verifies that authorised dvsa user can change trade user email on trade user profile",
            dataProvider = "dvsaUserForContactDetails")
    public void dvsaUserCanSeeUserContactDetails(User user) throws IOException, URISyntaxException {

        //Given I'm on the Trade user New Profile Details page as authorised DVSA user
        NewProfilePage newProfilePage = navigateToNewProfilePage(user, tester, false);

        //Then Change email link should be displayed
        assertThat(newProfilePage.isChangeEmailLinkIsDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "BL-448"},
            description = "Verifies that authorised user can see dvsa roles on dvsa user profile",
            dataProvider = "dvsaUserForContactDetails")
    public void dvsaUserCanSeeDvsaUserRoles(User user) throws IOException, URISyntaxException {

        //Given I'm on the Trade user New Profile Details page as authorised DVSA user
        NewProfilePage newProfilePage = navigateToNewProfilePage(user, areaOffice2User, false);

        //Then Dvsa roles section should be displayed
        assertThat(newProfilePage.isDvsaRolesSectionIsDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "BL-448"},
            description = "Verifies that authorised user can see qualification section on user profile",
            dataProvider = "anyUserForQualification")
    public void anyUserCanSeeQualificationsSection(User user) throws IOException, URISyntaxException {

        //Given I'm on the Trade user New Profile Details page as authorised DVSA user
        NewProfilePage newProfilePage = navigateToNewProfilePage(user, tester, false);

        //Then Qualification section should be displayed
        assertThat(newProfilePage.isQualificationStatusSectionIsDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "BL-448"},
            description = "Verifies that user can see account security section on own user profile",
            dataProvider = "anyUserForAccountSecurity")
    public void anyUserCanSeeAccountSecuritySectionOnOwnProfile(User user) throws IOException, URISyntaxException {

        //Given I'm on the New Profile Details page as logged user
        NewProfilePage newProfilePage = navigateToNewProfilePage(user, user, true);

        //Then Account security section should be displayed
        assertThat(newProfilePage.isAccountSecuritySectionDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "BL-448"},
            description = "Verifies that csco user can see account management section on other user profile",
            dataProvider = "anyUserForAccountManagement")
    public void cscoUserCanSeeAccountManagementSectionOnAnyProfile(User user) throws IOException, URISyntaxException {

        //Given I'm on the New Profile Details page as logged user
        NewProfilePage newProfilePage = navigateToNewProfilePage(csco, user, false);

        //Then Account management section should be displayed
        assertThat(newProfilePage.isAccountManagementSectionDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "BL-448"},
            description = "Verifies that authorised dvsa user can see change qualification links " +
                    "on trade user profile",
            dataProvider = "dvsaUserForChangeQualifications")
    public void dvsaUserCanSeeChangeQualificationLinksOnTradeProfile(User user) throws IOException, URISyntaxException {

        //Given I'm on the New Profile Details page as logged user
        NewProfilePage newProfilePage = navigateToNewProfilePage(user, tester, false);

        //Then Change qualification links should be displayed
        assertThat(newProfilePage.isChangeQualificationLinksDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "BL-448"},
            description = "Verifies that authorised dvsa user can see manage roles link " +
                    "on other dvsa user profile",
            dataProvider = "dvsaUserForManageRoles")
    public void dvsaUserCanSeeManageRolesOnUserProfile(User user) throws IOException, URISyntaxException {

        //Given I'm on the New Profile Details page as logged user
        NewProfilePage newProfilePage = navigateToNewProfilePage(user, tester, false);

        //Then Manage roles link should be displayed
        assertThat(newProfilePage.isChangeQualificationLinksDisplayed(), is(true));
    }

    @DataProvider(name = "dvsaUserForPersonalDetails")
    private Object[][] dvsaUserForPersonalDetails() throws IOException {
        return new Object[][]{
                {schemeManager, true},
                {areaOffice1User, true},
                {vehicleExaminer, true},
                {csco, false}};
    }

    @DataProvider(name = "dvsaUserForContactDetails")
    private Object[][] dvsaUserForContactDetails() throws IOException {
        return new Object[][]{
                {schemeManager},
                {areaOffice1User},
                {vehicleExaminer},
                {csco}};
    }

    @DataProvider(name = "dvsaUserForChangeQualifications")
    private Object[][] dvsaUserForChangeQualifications() throws IOException {
        return new Object[][]{
                {schemeManager},
                {areaOffice1User},
                {vehicleExaminer}};
    }

    @DataProvider(name = "dvsaUserForManageRoles")
    private Object[][] dvsaUserForManageRoles() throws IOException {
        return new Object[][]{
                {schemeManager},
                {areaOffice1User},
                {vehicleExaminer}};
    }

    @DataProvider(name = "anyUserForQualification")
    private Object[][] anyUserForQualification() throws IOException {
        return new Object[][]{
                {schemeManager},
                {areaOffice1User},
                {vehicleExaminer},
                {aedm},
                {csco}};
    }

    @DataProvider(name = "anyUserForAccountSecurity")
    private Object[][] anyUserForAccountSecurity() throws IOException {
        return new Object[][]{
                {schemeManager},
                {areaOffice1User},
                {vehicleExaminer},
                {csco},
                {aedm},
                {tester}};
    }

    @DataProvider(name = "anyUserForAccountManagement")
    private Object[][] anyUserForAccountManagement() throws IOException {
        return new Object[][]{
                {schemeManager},
                {areaOffice1User},
                {vehicleExaminer},
                {aedm},
                {tester}};
    }

    private NewProfilePage navigateToNewProfilePage(User authorisedUser, User profileOwner, boolean isOwnProfile)
            throws IOException, URISyntaxException {
        String ownProfile = profileOwner.getId() + "?context=your-profile";
        if (isOwnProfile) {
            return pageNavigator.goToPage(authorisedUser, String.format(NewProfilePage.PATH, ownProfile), NewProfilePage.class);
        }
        return pageNavigator.goToPage(authorisedUser, String.format(NewProfilePage.PATH, profileOwner.getId()), NewProfilePage.class);
    }
}
