package uk.gov.dvsa.ui.views;

import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.vts.ChangeTestingFacilitiesPage;
import uk.gov.dvsa.ui.pages.vts.ConfirmTestFacilitiesPage;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;

import java.io.IOException;
import java.util.Random;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class EditSiteDetailsTest extends BaseTest {

   private String onePersonTestLane;
   private String twoPersonTestLane;
   private Site site;
   private User areaOfficeUser;

    @BeforeMethod(alwaysRun = true)
    public void setUp() throws IOException {
        onePersonTestLane = generateTestLaneNumber();
        twoPersonTestLane = generateTestLaneNumber();
        site = siteData.createSite();
        areaOfficeUser = userData.createUserAsAreaOfficeOneUser("dv");
    }

    @Test(groups = {"Regression", "VM-10407 , Regression"})
    public void changeTestFacilitiesTest() throws IOException {
        //Given I am logged in as AO1 & I navigate to the change testing facilities page
        ChangeTestingFacilitiesPage changeTestingFacilitiesPage = pageNavigator.goToChangeTestingFacilitiesPage(areaOfficeUser, String.valueOf(site.getId()));

        //When I change the site testing facilities details for onePersonTestLane and twoPersonTestLane and submit the request
        ConfirmTestFacilitiesPage confirmTestFacilitiesPage = changeTestingFacilitiesPage
                .selectOnePersonTestLaneNumber(onePersonTestLane)
                .selectTwoPersonTestLaneNumber(twoPersonTestLane)
                .clickOnSaveTestFacilitiesButton();

        //And I confirm my site test facilities changes
        VehicleTestingStationPage finalVehicleTestingStationPage = confirmTestFacilitiesPage.clickOnConfirmButton();

        //Then my changes are displayed on the testing station page
        assertThat(finalVehicleTestingStationPage.verifyOnePersonTestLaneValueDisplayed(), is(onePersonTestLane));
        assertThat(finalVehicleTestingStationPage.verifyTwoPersonTestLaneValueDisplayed(), is(twoPersonTestLane));
    }

    private String generateTestLaneNumber() {
        return String.valueOf( 1 + new Random().nextInt(5));
    }
}
