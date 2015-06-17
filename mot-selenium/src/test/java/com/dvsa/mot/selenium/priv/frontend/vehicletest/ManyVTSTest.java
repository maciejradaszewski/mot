package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.ReasonToCancel;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.LocationSelectPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.VehicleSearchPage;
import org.apache.commons.lang3.RandomStringUtils;
import org.hamcrest.Matchers;
import org.testng.annotations.Test;

import java.util.ArrayList;

import static com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.LocationSelectPage.navigateHereFromLoginPage;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class ManyVTSTest extends BaseTest {

    @Test(groups = {"slice_A", "short", "VM-2950"})
    public void testVTSOptionsPresentedToTesterWithManyVTS() {

        AeService aeService = new AeService();
        ArrayList vts = new ArrayList();
        String aeName = RandomStringUtils.randomAlphabetic(6);
        String vtsOneName = RandomStringUtils.randomAlphabetic(6);
        String vtsTwoName = RandomStringUtils.randomAlphabetic(6);
        AeDetails aeDetails = aeService.createAe(aeName);
        int vtsOneId =
                createVTS(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsOneName);
        int vtsTwoId =
                createVTS(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsTwoName);
        vts.add(vtsOneId);
        vts.add(vtsTwoId);
        Login manyVtsTesterLogin = createTester(vts);

        LocationSelectPage locationSelectPage =
                navigateHereFromLoginPage(driver, manyVtsTesterLogin);

        assertThat("Check to ensure vts is available for selection",
                locationSelectPage.getRadioBtnLabelText(String.valueOf(vtsOneId))
                        .startsWith("Test Site " + vtsOneName), is(true));
        assertThat("Check to ensure vts is available for selection",
                locationSelectPage.getRadioBtnLabelText(String.valueOf(vtsTwoId))
                        .startsWith("Test Site " + vtsTwoName), is(true));

        locationSelectPage.confirmSelectedExpectingError();

        assertThat("Check validation message when a vts is not selected",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
    }

    @Test(groups = {"VM-3665", "slice_A"})
    public void testVtsSelectionNotNeededAfterResumingTest() {

        AeService aeService = new AeService();
        ArrayList vts = new ArrayList();
        String aeName = RandomStringUtils.randomAlphabetic(6);
        String vtsOneName = RandomStringUtils.randomAlphabetic(6);
        String vtsTwoName = RandomStringUtils.randomAlphabetic(6);
        AeDetails aeDetails = aeService.createAe(aeName);
        Site siteOne = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        vtsOneName);
        Site siteTwo = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        vtsTwoName);
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        vts.add(siteOne.getId());
        vts.add(siteTwo.getId());
        Login manyVtsTesterLogin = createTester(vts);

        VehicleSearchPage
                .navigateHereFromLoginPageForManyVtsTester(driver, manyVtsTesterLogin, siteTwo)
                .submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg).submitConfirm()
                .clickLogout();
        MotTestPage motTestPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, manyVtsTesterLogin)
                        .resumeMotTest();

        assertThat("Check to ensure tester can resume test",
                motTestPage.isReviewTestButtonDisplayed(), is(true));

        motTestPage.cancelMotTest(ReasonToCancel.REASON_INCORRECT_LOCATION);
    }
}
