package uk.gov.dvsa.ui.feature.journey;

import org.testng.annotations.BeforeClass;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.HomePage;
import uk.gov.dvsa.ui.pages.ProfilePage;
import uk.gov.dvsa.ui.pages.dvsamanageroles.ManageRolesPage;
import uk.gov.dvsa.ui.pages.dvsamanageroles.UserSearchPage;
import uk.gov.dvsa.ui.pages.dvsamanageroles.UserSearchProfilePage;
import uk.gov.dvsa.ui.pages.dvsamanageroles.UserSearchResultsPage;


import java.io.IOException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.StringContains.containsString;

public class DVSAManagingUserRoles extends BaseTest {

    private User vehicleExaminer;
    private User areaOffice1User;

    @BeforeClass(alwaysRun = true)
    private void setup() throws IOException {
        areaOffice1User = userData.createAreaOfficeOne("AreaOfficer");
        vehicleExaminer = userData.createVehicleExaminer("ft-Enf-", false);
    }

    @Test(groups = {"BVT", "Regression"})
    public void SchemeManagerAddsRoleToADVSAUser() throws IOException{

       //Given I am logged in as a scheme area office 1 user on the homepage
        HomePage homePage = pageNavigator.gotoHomePage(areaOffice1User);

        //and i add select user search
        UserSearchPage userSearchPage = homePage.clickUserSearchLinkExpectingUserSearchPage();

        //insert vehicle examiner into the username search field
        UserSearchResultsPage userSearchResultsPage = userSearchPage.enterUsernameIntoSearchFieldAndClickSearch(vehicleExaminer.getUsername());

        //select from results
        UserSearchProfilePage profilePage = userSearchResultsPage.clickUserName(0);

        //from ve profile click manage roles
        ManageRolesPage manageRolesPage = profilePage.clickManageRolesLinkExpectingManageRolesPage();

        //confirm add role
        manageRolesPage.addRoleOfAo2();
        manageRolesPage.confirmAddRoleOfAo2();

        //verify role added
        assertThat(manageRolesPage.checkRoleNotification(), containsString("added"));
    }

    @Test(groups = {"BVT", "Regression"})
    public void SchemeManagerRemovesRoleToADVSAUser() throws IOException{

        //Given I am logged in as a scheme area office 1 user on the homepage
        HomePage homePage = pageNavigator.gotoHomePage(areaOffice1User);

        //and i add select user search
        UserSearchPage userSearchPage = homePage.clickUserSearchLinkExpectingUserSearchPage();

        //insert vehicle examiner into the username search field
        UserSearchResultsPage userSearchResultsPage = userSearchPage.enterUsernameIntoSearchFieldAndClickSearch(vehicleExaminer.getUsername());

        //select from results
        UserSearchProfilePage profilePage = userSearchResultsPage.clickUserName(0);

        //from ve profile click manage roles
        ManageRolesPage manageRolesPage = profilePage.clickManageRolesLinkExpectingManageRolesPage();

        //confirm remove role
        manageRolesPage.removeRoleOfVehicleExaminer();
        manageRolesPage.confirmRemoveRoleOfVehicleExaminer();

        //verify role added
        assertThat(manageRolesPage.checkRoleNotification(), containsString("removed"));
    }
}
