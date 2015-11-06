package uk.gov.dvsa.ui.feature.journey;

import org.testng.annotations.BeforeClass;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.helper.RandomDataGenerator;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.dvsa.RolesAndAssociationsPage;
import uk.gov.dvsa.ui.pages.dvsa.UserSearchPage;
import uk.gov.dvsa.ui.pages.dvsa.UserSearchProfilePage;
import uk.gov.dvsa.ui.pages.dvsa.UserSearchResultsPage;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;

import java.io.IOException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;
import static org.hamcrest.core.StringContains.containsString;

public class DVSAManagingUserRolesTests extends BaseTest {

    private User vehicleExaminer;
    private User areaOffice1User;
    private User csco;
    private User tester;
    private AeDetails aeDetails;
    private Site site;

    @BeforeClass(alwaysRun = true)
    private void setup() throws IOException {
        aeDetails = aeData.createAeWithDefaultValues();
        site = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        areaOffice1User = userData.createAreaOfficeOne("AreaOfficer");
        vehicleExaminer = userData.createVehicleExaminer("ft-Enf-", false);
        csco = userData.createCustomerServiceOfficer(false);
        tester = userData.createTester(site.getId());
    }

    @Test(groups = {"BVT", "Regression"})
    public void areaOfficeUserAddsRoleToVeUser() throws IOException{

        //Given that I am on Manage roles page as a Area office 1 user
        pageNavigator.goToManageRolesPageViaUserSearch(areaOffice1User, vehicleExaminer);

        //When I add role of AO2
        motUI.manageRoles.addRole("AO2");

        //Then Ao2 role is added to user
        assertThat(motUI.manageRoles.confirmRemoveRoleAction(), containsString("added"));
    }

    @Test(groups = {"BVT", "Regression"})
    public void areaOfficeUserRemovesRoleFromVeUser() throws IOException{

        //Given that I am on Manage roles page as a Area office 1 user
        pageNavigator.goToManageRolesPageViaUserSearch(areaOffice1User, vehicleExaminer);

        //When I remove role
        motUI.manageRoles.removeRole("VE");

        //Then VE role is removed from user
        assertThat(motUI.manageRoles.confirmRemoveRoleAction(), containsString("removed"));
    }

    @Test(groups = {"BVT", "Regression", "VM-12318"},
            description = "Test that validates the authorised DVSA user can search for user by email with " +
                    "expanded additional search criteria section")
    public void areaOfficeUserCanSearchForUserByEmailExpandedSection() throws IOException{

        //Given that I am on Search user page as a Area office 1 user
        pageNavigator.goToUserSearchPage(areaOffice1User);

        //When I search for user by email with expanded criteria section
        motUI.searchUser.searchForUserByUserEmail(vehicleExaminer.getEmailAddress(), true, UserSearchResultsPage.class);

        //Then I should see the user details
        assertThat(motUI.searchUser.isUserSearchResultAccurate(vehicleExaminer), is(true));
    }

    @Test(groups = {"BVT", "Regression", "VM-12318"},
            description = "Test that validates the authorised DVSA user can't search for user by email with " +
                    "collapsed additional search criteria section")
    public void areaOfficeUserCantSearchForUserByEmailCollapsedSection() throws IOException{

        //Given that I am on Search user page as a Area office 1 user
        pageNavigator.goToUserSearchPage(areaOffice1User);

        //When I search for user by email with collapsed criteria section
        motUI.searchUser.searchForUserByUserEmail(vehicleExaminer.getEmailAddress(), false, UserSearchPage.class);

        //Then I should see an Error message
        assertThat(motUI.searchUser.isErrorMessageDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "VM-12168"},
            description = "Test that validates the authorised DVSA user cant search for user by invalid email")
    public void areaOfficeUserCantSearchForUserByInvalidEmail() throws IOException{

        //Given that I am on Search user page as a Area office 1 user
        pageNavigator.goToUserSearchPage(areaOffice1User);

        //When I search for user by invalid email
        motUI.searchUser.searchForUserByUserEmail(RandomDataGenerator.generateEmail(20, System.nanoTime()), true, UserSearchPage.class);

        //Then I should see a Validation message
        assertThat(motUI.searchUser.isNoResultsMessageDisplayed(), is(true));
    }

    @Test(groups = {"VM-7646", "Regression"}, dataProvider = "dvsaUserCanSearchForAUserByTown")
    public void dvsaUserCanSearchOnTown(User user) throws IOException {

        //Given that I am on Search user page as a authorised DVSA user
        pageNavigator.goToUserSearchPage(user);

        //When I search for user by town
        motUI.searchUser.searchForUserByTown("Bristol");

        //Then I should see the user details
        assertThat(motUI.searchUser.isSearchResultAccurateWhenSearchingByTown("Bristol"), is(true));
    }

    @Test(groups = {"VM-4741", "Regression", "W-Sprint5"},
            description = "Verify that authorised dvsa user can search for user with valid date of birth")
    public void dvsaUserCanSearchForUserByDateOfBirth() throws IOException {

        //Given that I am on Search user page as a authorised DVSA user
        pageNavigator.goToUserSearchPage(areaOffice1User);

        //When I search for user by valid date of birth
        motUI.searchUser.searchForUserByDateOfBirth("24-11-1961", true);

        //Then I should see the user details
        assertThat(motUI.searchUser.isSearchResultAccurateWhenSearchingByDOB("24-11-1961"), is(true));
    }

    @Test(groups = {"VM-4741", "Regression", "W-Sprint5"},
            description = "Verify error message is displayed when search user with invalid format date")
    public void dvsaUserCantSearchForUserByInvalidFormatDateOfBirth() throws IOException {

        //Given that I am on Search user page as a authorised DVSA user
        pageNavigator.goToUserSearchPage(areaOffice1User);

        //When I search for user by invalid format date of birth
        motUI.searchUser.searchForUserByDateOfBirth("1-1-1920", false);

        //Then I should see an Error message
        assertThat(motUI.searchUser.isErrorMessageDisplayed(), is(true));
    }

    @Test(groups = {"VM-4741", "Regression", "W-Sprint5"},
            description = "Verify proper message was displayed when user search page return too many results")
    public void dvsaUserSearchTooManyResults() throws IOException {
        //Given that I am on Search user page as a Area office 1 user
        pageNavigator.goToUserSearchPage(areaOffice1User);

        //When I search for user by email with expanded criteria section
        motUI.searchUser.searchForUserByUserEmail("dummy@email.com", true, UserSearchPage.class);

        //Then I should see Too many results message
        assertThat(motUI.searchUser.isTooManyResultsMessageDisplayed("dummy@email.com"), is(true));
    }

    @Test(groups = {"VM-4698", "VM-4842", "VM-7724", "V-Sprint10", "Regression", "W-Sprint4"},
            description = "Verify that authorised dvsa user can search for user by valid username")
    public void dvsaSearchUserByUsername() throws IOException {
        //Given that I am on Search user page as a Area office 1 user
        pageNavigator.goToUserSearchPage(areaOffice1User);

        //When I search for user by username
        motUI.searchUser.searchForUserByUsername(vehicleExaminer.getUsername(), UserSearchResultsPage.class);

        //Then I should see the user details
        assertThat(motUI.searchUser.isUserSearchResultAccurate(vehicleExaminer), is(true));
    }

    @Test(groups = {"Regression"}, description = "Verify that authorised dvsa user can search for user and " +
            "get back to user search page with Back to user search link")
    public void dvsaSearchUserByNameAndGetBackToUserSearchPage() throws IOException {
        //Given that I am on Search user page as a Area office 1 user
        pageNavigator.goToUserSearchPage(areaOffice1User);

        //When I click Back to user search link on User search results page
        motUI.searchUser.searchForUserByUserFirstName(vehicleExaminer.getFirstName(), UserSearchResultsPage.class).clickBackToUserSearch();

        //Then I should see the Search button
        assertThat(motUI.searchUser.isSearchButtonDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "VM-12321"},
            description = "Verifies that authorised user can check user roles via roles and associations link " +
                    "on user profile page")
    public void dvsaUserCanViewTradesUserRolesAndAssociationsFromUserSearch() throws IOException {

        //Given that I am on User search profile page as an authorised DVSA user
        UserSearchProfilePage userSearchProfilePage = pageNavigator.goToUserSearchedProfilePageViaUserSearch(areaOffice1User, tester);

        //When I click on Roles and Associations link
        userSearchProfilePage.clickRolesAndAssociationsLink();

        //Then roles should be displayed
        assertThat(motUI.manageRoles.isRolesTableContainsValidTesterData(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "VM-12321"},
            description = "Verifies that authorised DVSA user can check roles via roles and associations link " +
                    "of trade user")
    public void dvsaUserCanViewTradeUsersRolesAndAssociations() throws IOException {

        //Given I'm on the Site search page as an authorised DVSA user
        pageNavigator.goToSiteSearchPage(areaOffice1User);

        //When I click on an assigned user from vts details page
        motUI.searchSite.searchForSiteBySiteId(site.getSiteNumber(), VehicleTestingStationPage.class).chooseAssignedToVtsUser(tester.getId());

        //Then Roles and Associations link should be displayed
        assertThat(motUI.manageRoles.isRolesAndAssociationsLinkDisplayedOnProfileOfPage(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "VM-12321"},
            description = "Verifies that authorised user can navigate back from roles and associations page " +
                    "to user user profile page via link")
    public void dvsaUserNavigatesBackFromRolesAndAssociationsPageViaLink() throws IOException {

        //Given that I am on User roles and associations page as an authorised DVSA user
        RolesAndAssociationsPage rolesAndAssociationsPage = pageNavigator.goToUserSearchedProfilePageViaUserSearch(areaOffice1User, tester)
                .clickRolesAndAssociationsLink();

        //When I click on Return to user profile link
        UserSearchProfilePage userSearchProfilePage = rolesAndAssociationsPage.clickReturnToUserProfile();

        //Then User profile should be displayed
        assertThat(userSearchProfilePage.selfVerify(), is(true));
    }

    @DataProvider(name = "dvsaUserCanSearchForAUser")
    public Object[][] dvsaUserCanSearchForAUser() {
        return new Object[][]{{areaOffice1User}, {vehicleExaminer},
                {csco}};
    }

    @DataProvider(name = "dvsaUserCanSearchForAUserByTown")
    public Object[][] dvsaUserCanSearchForAUserByTown() {
        return new Object[][]{{areaOffice1User}, {vehicleExaminer}};
    }
}
