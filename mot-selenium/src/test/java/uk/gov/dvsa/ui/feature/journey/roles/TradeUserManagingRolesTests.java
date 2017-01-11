package uk.gov.dvsa.ui.feature.journey.roles;

import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.ui.DslTest;
import uk.gov.dvsa.ui.pages.RemoveRolePage;
import uk.gov.dvsa.ui.pages.dvsa.RolesAndAssociationsPage;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class TradeUserManagingRolesTests extends DslTest {

    @Test(dataProvider = "dvsaTesterAndSiteManager",
            testName = "NewProfile", groups = {"BVT", "VM-12321"},
            description = "Verifies that trade user cant check roles via roles and associations link " +
                    "of other trade user")
    public void tradeUserCantViewAeAssociationOfOtherTradeUser(User tester, User siteManager) throws IOException, URISyntaxException {

        //Given I'm on the profile page of a user as a Trade user
        motUI.profile.tradeViewUserProfile(siteManager, tester);

        //I expect Roles and Associations link should be displayed
        assertThat(motUI.profile.page().isRolesAndAssociationsLinkDisplayed(), is(false));
    }


    @Test(dataProvider = "dvsaTester",
            groups = {"BVT", "BL-94"},
            description = "Verifies that trade user can navigate back from Remove role page to " +
                    "Roles and Associations page via link")
    public void tradeUserCanNavigateFromDeleteRolePageViaLink(User tester) throws IOException, URISyntaxException {

        //Given I'm logged in as Trade user and I am on Remove role page
        motUI.profile.viewYourProfile(tester);
        RemoveRolePage removeRolePage = motUI.profile.page().clickRolesAndAssociationsLink().removeRole();

        //When I click on Cancel and return to roles and associations link
        removeRolePage.cancelRoleRemoval();

        //Then roles should be displayed
        assertThat(motUI.manageRoles.isRolesTableContainsValidTesterData(), is(true));
    }

    @Test(dataProvider = "dvsaTesterAndVehicle",
            groups = {"BVT", "BL-94"},
            description = "Verifies that trade user can't remove his own role if he has test in progress")
    public void tradeUserCantRemoveOwnTradeRoleWithTestInProgress(User tester, Vehicle testVehicle) throws IOException, URISyntaxException {
        vehicleReinspectionWorkflow().startMotTestAsATester(tester, testVehicle);

        //Given I'm logged in as Trade user with test in progress and I am on Remove role page
        motUI.profile.viewYourProfile(tester);
        RemoveRolePage removeRolePage = motUI.profile.page().clickRolesAndAssociationsLink().removeRole();

        //When I click on Confirm button
        removeRolePage.confirmRemoveRole(RolesAndAssociationsPage.class);

        //Then Error message should be displayed
        assertThat(motUI.manageRoles.isErrorMessageDisplayedOnRolesAndAssociationsPage(), is(true));
    }

    @Test(dataProvider = "dvsaTester",
            groups = {"BVT", "BL-94"},
            description = "Verifies that trade user can remove his own role")
    public void tradeUserCanRemoveOwnTradeRole(User tester) throws IOException, URISyntaxException {

        //Given I am logged in as Trade user and I am on Remove role page
        motUI.profile.viewYourProfile(tester);
        RemoveRolePage removeRolePage = motUI.profile.page().clickRolesAndAssociationsLink().removeRole();

        //When I click on Confirm button
        removeRolePage.confirmRemoveRole(RolesAndAssociationsPage.class);

        //Then role should be removed
        assertThat(motUI.manageRoles.isSuccessMessageDisplayedOnRolesAndAssociationsPage(), is(true));
    }

    @Test(dataProvider = "dvsaTesterAndSiteAndAreaOfficer",
            groups = {"BVT", "BL-94"},
            description = "Verifies that when trade user removes his own role it's not assigned to vts")
    public void tradeUserRoleIsNotAssignedToVtsAfterDeletion(User tester, Site site, User areaOffice1User) throws IOException, URISyntaxException {
        tradeUserCanRemoveOwnTradeRole(tester);

        //Given I am logged in as an authorised DVSA user and I am on the Site search page
        motUI.site.vtsSearchPage(areaOffice1User);

        //When I search for expected vts
        motUI.searchSite.searchForSiteBySiteId(site.getSiteNumber(), VehicleTestingStationPage.class);

        //Then removed role should not be present
        assertThat(motUI.manageRoles.isUserAssignedToVts(tester), is(false));
    }

    @DataProvider(name = "dvsaTester")
    private Object[][] dvsaTester() throws IOException {
        AeDetails aeDetails = aeData.createAeWithDefaultValues();
        Site site = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        User tester = userData.createTester(site.getId());

        return new Object[][]{{tester}};
    }

    @DataProvider(name = "dvsaTesterAndVehicle")
    private Object[][] dvsaTesterAndVehicle() throws IOException {
        AeDetails aeDetails = aeData.createAeWithDefaultValues();
        Site site = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        User tester = userData.createTester(site.getId());
        Vehicle testVehicle = vehicleData.getNewVehicle(tester);

        return new Object[][]{{tester, testVehicle}};
    }

    @DataProvider(name = "dvsaTesterAndSiteAndAreaOfficer")
    private Object[][] dvsaTesterAndSiteAndAreaOfficer() throws IOException {
        AeDetails aeDetails = aeData.createAeWithDefaultValues();
        Site site = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        User tester = userData.createTester(site.getId());
        User areaOffice1User = userData.createAreaOfficeOne("AreaOfficer");

        return new Object[][]{{tester, site, areaOffice1User}};
    }

    @DataProvider(name = "dvsaTesterAndSiteManager")
    private Object[][] dvsaTesterAndSitemanager() throws IOException {
        AeDetails aeDetails = aeData.createAeWithDefaultValues();
        Site site = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        User tester = userData.createTester(site.getId());
        User siteManager = userData.createSiteManager(site.getId(), false);

        return new Object[][]{{tester, siteManager}};
    }
}
