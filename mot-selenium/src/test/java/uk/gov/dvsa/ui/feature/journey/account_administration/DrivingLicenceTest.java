package uk.gov.dvsa.ui.feature.journey.account_administration;

import org.testng.annotations.BeforeClass;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.helper.ConfigHelper;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.HomePage;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.ProfilePage;
import uk.gov.dvsa.ui.pages.profile.PersonProfilePage;
import uk.gov.dvsa.ui.pages.changedriverlicence.ChangeDrivingLicencePage;
import uk.gov.dvsa.ui.pages.changedriverlicence.RemoveDriverLicencePage;
import uk.gov.dvsa.ui.pages.changedriverlicence.ReviewDrivingLicencePage;
import uk.gov.dvsa.ui.pages.dvsa.UserSearchPage;
import uk.gov.dvsa.ui.pages.dvsa.UserSearchResultsPage;
import uk.gov.dvsa.ui.pages.exception.PageInstanceNotFoundException;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.apache.commons.lang3.StringUtils.equalsIgnoreCase;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;
import static org.hamcrest.core.StringContains.containsString;

public class DrivingLicenceTest extends BaseTest {

    private User areaOffice1User;
    private User areaOffice2User;
    private User vehicleExaminerUser;
    private User cscoUser;
    private User tester;
    private User schemeManager;
    private Site testSite;
    private AeDetails aeDetails;

    private static final String DRIVING_LICENCE_CHANGE_SUCCESS = "Driving licence has been changed successfully.";
    private static final String DRIVING_LICENCE_WRONG_NI_FORMAT = "Driving licence - must be a valid Northern Ireland driving licence";
    private static final String DRIVING_LICENCE_WRONG_GB_FORMAT = "Driving licence - must be a valid Great Britain driving licence";
    private static final String DRIVING_LICENCE_EMPTY = "you must enter a driving licence number";
    private static final String ISSUING_COUNTRY_INVALID = "you must choose an issuing country";

    // Driving licence region text
    private static final String DRIVING_LICENCE_REGION_TEXT_GB = "GB";
    private static final String DRIVING_LICENCE_REGION_TEXT_NI = "NI";
    private static final String DRIVING_LICENCE_REGION_TEXT_NU = "Non-United Kingdom";
    private static final String DRIVING_LICENCE_REGION_TEXT_NU_NEW_PROFILE = "NU";

    @BeforeClass(alwaysRun = true)
    private void setup() throws IOException {
        aeDetails = aeData.createAeWithDefaultValues();
        testSite = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        areaOffice1User = userData.createAreaOfficeOne("AreaOfficerOne");
        areaOffice2User = userData.createAreaOfficeTwo("AreaOfficerTwo");
        vehicleExaminerUser = userData.createVehicleExaminer("VehicleExaminer", false);
        cscoUser = userData.createCustomerServiceOfficer(false);
        tester = userData.createTester(testSite.getId());
        schemeManager = userData.createSchemeUser(false);
        }

    @Test(groups = {"BVT", "Regression"},
            description = "Test that validates the authorised DVSA user can see driver licence section with or without add/edit link in it",
            dataProvider = "dvsaUserExpectsDrivingLicenceSection")
    public void driverLicenceInfoIsVisibleOnSearchedUserProfilePage (User loggedInUser, boolean assertion) throws IOException, URISyntaxException {
        ProfilePage profilePage = searchForUserAndViewProfile(loggedInUser, tester);

        // Then the driving licence number element is displayed
        assertThat(profilePage.drivingLicenceIsDisplayed(), is(true));

        // Then Tester's driving licence element contains the driving licence number
        assertThat(profilePage.getDrivingLicenceForPerson(), containsString(tester.getDrivingLicenceNumber()));

        // Then the add/edit link is present or not, depending on role
        assertThat(profilePage.addEditDrivingLicenceLinkExists(), is(assertion));
    }

    @Test(groups = {"BVT", "Regression"},
            description = "Test that validates the logged user can't see driver licence section on it's own profile page",
            dataProvider = "userCantSeeDrivingLicenceSection")
    public void driverLicenceInfoNotVisibleOnUserOwnProfilePage (User loggedInUser) throws IOException, URISyntaxException {
        // Given that I'm on a logged user profile page
        PersonProfilePage personProfilePage = pageNavigator.navigateToPage(loggedInUser, PersonProfilePage.PATH, PersonProfilePage.class);

        // Then the driving licence number element is not displayed
        assertThat(personProfilePage.drivingLicenceIsDisplayed(), is(false));

        // Then the add/edit link is not present
        assertThat(personProfilePage.addEditDrivingLicenceLinkExists(), is(false));
    }

    @Test(groups = {"BVT", "Regression"},
            description = "Test that validates the authorised DVSA user can't see driver licence section on other DVSA user",
            dataProvider = "expectsDrivingLicenceNotShown")
    public void dvsaUserCantSeeDriverLicenceForOtherDvsaUser(User loggedInUser, User searchedUser) throws IOException, URISyntaxException {
        // Given that I'm on a DVSA user profile as authorised DVSA user
        ProfilePage profilePage = searchForUserAndViewProfile(loggedInUser, searchedUser);

        // Then the driving licence number element is not displayed
        assertThat(profilePage.drivingLicenceIsDisplayed(), is(false));

        // Then the add/edit link is not present
        assertThat(profilePage.addEditDrivingLicenceLinkExists(), is(false));
    }

    @Test(groups = {"BVT", "Regression"},
            description = "Test that DVSA user can edit non-DVSA user driving licence successfully",
            dataProvider = "validLicenceInputTestCases")
    public void dvsaUserCanChangeNonDvsaUserDrivingLicenceWithValidInput(String number, String country, String countryText) throws IOException, URISyntaxException {
        // Given that I'm on a non-DVSA user profile as authorised DVSA user
        ProfilePage profilePage = searchForUserAndViewProfile(areaOffice1User, tester);

        // And I am trying to submit valid licence details
        ReviewDrivingLicencePage reviewDrivingLicencePage =
                fillAndSubmitNewDriverLicenceDetails(profilePage, number, country, ReviewDrivingLicencePage.class);

        // And I confirm these details on the summary screen
        assertThat(reviewDrivingLicencePage.getDrivingLicenceNumber(), is(number.toUpperCase()));
        ProfilePage postChangeProfilePage = reviewDrivingLicencePage.clickChangeDrivingLicenceButton();

        // Then the driving licence number and region are shown
        assertThat(postChangeProfilePage.getDrivingLicenceForPerson(), containsString(number.toUpperCase()));
        assertThat(postChangeProfilePage.getDrivingLicenceRegionForPerson(), containsString(countryText));

        // And a success message is displayed
        assertThat(postChangeProfilePage.getMessageSuccess(), containsString(DRIVING_LICENCE_CHANGE_SUCCESS));
    }

    @Test(groups = {"BVT", "Regression"},
            description = "Test that DVSA user can remove non-DVSA user driving licence")
    public void dvsaUserCanRemoveNonDvsaUserDrivingLicence() throws IOException, URISyntaxException {
        tester = userData.createTester(testSite.getId());

        // Given that I'm on a non-DVSA user profile as authorised DVSA user
        ProfilePage profilePage = searchForUserAndViewProfile(areaOffice1User, tester);

        // And the users driving licence number is displayed
        assertThat(profilePage.getDrivingLicenceForPerson(), containsString(tester.getDrivingLicenceNumber()));

        // When I remove the users driving licence
        ProfilePage postDeleteDrivingLicenceProfilePage = removeDrivingLicence(profilePage);

        // Then no driving licence will be recorded for the user
        assertThat(postDeleteDrivingLicenceProfilePage.getDrivingLicenceForPerson(), is("None recorded"));
    }

    @Test(groups = {"BVT", "Regression"},
            description = "Test that a validation error message is displayed to DVSA user when submitting wrong information",
            dataProvider = "invalidLicenceInputTestCases")
    public void dvsaUserSeesValidationErrorWithInvalidInput(String number, String country, String error) throws IOException, URISyntaxException {
        // Given that I'm on a non-DVSA user profile as authorised DVSA user
        ProfilePage profilePage = searchForUserAndViewProfile(areaOffice1User, tester);

        // And I am trying to submit incorrect driver licence information
        ChangeDrivingLicencePage changeDrivingLicencePage = fillAndSubmitNewDriverLicenceDetails(profilePage, number, country, ChangeDrivingLicencePage.class);

        // Then a validation error is displayed
        assertThat(equalsIgnoreCase(changeDrivingLicencePage.getValidationSummary(), error), is(true));
    }

    @Test(groups = {"BVT", "Regression"},
            description = "Test that a validation error message is displayed to DVSA user when no licence number is entered",
            dataProvider = "issuingCountriesNoLicenceNumberExpectsValidationError")
    public void dvsaUserSeesValidationErrorWithNoLicenceNumberProvided(String country) throws IOException, URISyntaxException {
        // Given that I'm on a non-DVSA user profile as authorised DVSA user
        ProfilePage profilePage = searchForUserAndViewProfile(areaOffice1User, tester);

        // And I am trying to submit driver licence information without driver licence number
        ChangeDrivingLicencePage changeDrivingLicencePage = fillAndSubmitNewDriverLicenceDetails(profilePage, "", country, ChangeDrivingLicencePage.class);

        // Then a validation error is displayed
        assertThat(changeDrivingLicencePage.getValidationSummary(), containsString(DRIVING_LICENCE_EMPTY));
    }

    @Test(groups = {"BVT", "Regression"},
            description = "Test that a validation error message is displayed to DVSA user when issuing country is invalid")
    public void dvsaUserSeesValidationErrorWithInvalidIssuingCountryProvided() throws IOException, URISyntaxException {
        // Given that I'm on a non-DVSA user profile as authorised DVSA user
        ProfilePage profilePage = searchForUserAndViewProfile(areaOffice1User, tester);

        // When I click the change driving licence link
        ChangeDrivingLicencePage changeDrivingLicencePage = profilePage.clickChangeDrivingLicenceLink();

        // And I enter driver licence number and supply an invalid issuing country
        changeDrivingLicencePage.enterDriverLicenceNumber("AAAAA701010AA9AA")
                .setInvalidDlIssuingCountry();

        // And I click the submit button
        changeDrivingLicencePage.clickSubmitDrivingLicenceButton(ChangeDrivingLicencePage.class);

        // Then a validation error is displayed
        assertThat(changeDrivingLicencePage.getValidationSummary(), containsString(ISSUING_COUNTRY_INVALID));
    }

    @Test(groups = {"BVT", "Regression"},
            expectedExceptions = PageInstanceNotFoundException.class,
            description = "Test that URL cannot be modified by DVSA user to change DVSA user driving licence")
    public void dvsaUserCannotModifyUrlToChangeOtherDvsaUserDrivingLicence() throws IOException {
        // Given I attempt to modify the ID fragment of the change driving licence page
        pageNavigator.goToChangeDrivingLicencePage(areaOffice1User, areaOffice2User.getId());

        // Then the application will display an error page
    }

    @DataProvider
    private Object[][] dvsaUserExpectsDrivingLicenceSection() {
        return new Object[][] {
                {areaOffice1User, true},
                {vehicleExaminerUser, true},
                {cscoUser, false},
                {schemeManager, ConfigHelper.isNewPersonProfileEnabled()}
        };
    }

    @DataProvider
    private Object[][] userCantSeeDrivingLicenceSection() {
        return new Object[][] {
                {areaOffice1User},
                {vehicleExaminerUser},
                {cscoUser},
                {schemeManager},
                {tester},
        };
    }

    @DataProvider
    private Object[][] expectsDrivingLicenceNotShown() {
        return new Object[][] {
                {areaOffice1User, areaOffice2User},
                {areaOffice1User, cscoUser},
                {cscoUser, areaOffice1User},
                {cscoUser, schemeManager},
                {schemeManager, areaOffice1User},
                {schemeManager, cscoUser},
        };
    }

    @DataProvider
    private Object[][] validLicenceInputTestCases() {
        return new Object[][] {
                {"AAAAA807217BM9PC", "GB", DRIVING_LICENCE_REGION_TEXT_GB},
                {"aaaaa807217bm9pc", "GB", DRIVING_LICENCE_REGION_TEXT_GB},
                {"12345678", "NI", DRIVING_LICENCE_REGION_TEXT_NI},
                {"123-456-789", "NU",
                        ConfigHelper.isNewPersonProfileEnabled()
                                ? DRIVING_LICENCE_REGION_TEXT_NU_NEW_PROFILE : DRIVING_LICENCE_REGION_TEXT_NU},
        };
    }

    @DataProvider
    private Object[][] invalidLicenceInputTestCases() {
        return new Object[][] {
                {"AAAA8807217BM9PC", "GB", DRIVING_LICENCE_WRONG_GB_FORMAT},
                {"123456789", "NI", DRIVING_LICENCE_WRONG_NI_FORMAT},
        };
    }

    @DataProvider
    private Object[][] issuingCountriesNoLicenceNumberExpectsValidationError() {
        return new Object[][] {
                {"GB"},
                {"NI"},
                {"NU"},
        };
    }

    private ProfilePage searchForUserAndViewProfile(User loggedInUser, User searchedUser) throws IOException, URISyntaxException, URISyntaxException {
        HomePage homePage = pageNavigator.navigateToPage(loggedInUser, HomePage.PATH,  HomePage.class);
        UserSearchPage userSearchPage = homePage.clickUserSearchLinkExpectingUserSearchPage();
        UserSearchResultsPage userSearchResultsPage = userSearchPage
                .searchForUserByUsername(searchedUser.getUsername())
                .clickSearchButton(UserSearchResultsPage.class);
        return userSearchResultsPage.chooseUser(0);
    }

    private ProfilePage removeDrivingLicence(ProfilePage profilePage)
    {
        ChangeDrivingLicencePage changeDrivingLicencePage = profilePage.clickChangeDrivingLicenceLink();
        RemoveDriverLicencePage removeDriverLicencePage = changeDrivingLicencePage.clickRemoveDrivingLicenceLink();
        return removeDriverLicencePage.clickRemoveDrivingLicenceButton();
    }

    private <T extends Page> T fillAndSubmitNewDriverLicenceDetails(ProfilePage page, String number, String country, Class<T> clazz) {
        // When I click the change driving licence link
        ChangeDrivingLicencePage changeDrivingLicencePage = page.clickChangeDrivingLicenceLink();

        // And I enter a licence number and country
        changeDrivingLicencePage.enterDriverLicenceNumber(number)
                .selectDlIssuingCountry(country);

        // And I click submit driver licence number
        return changeDrivingLicencePage.clickSubmitDrivingLicenceButton(clazz);
    }
}
