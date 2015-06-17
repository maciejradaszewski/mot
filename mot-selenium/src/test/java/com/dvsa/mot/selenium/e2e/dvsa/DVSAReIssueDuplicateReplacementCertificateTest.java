package com.dvsa.mot.selenium.e2e.dvsa;


import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.enums.Colour;
import com.dvsa.mot.selenium.datasource.enums.VehicleMake;
import com.dvsa.mot.selenium.datasource.enums.VehicleModel;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.MotTestApi.TestOutcome;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.DuplicateReplacementCertificatePage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.DuplicateReplacementCertificatePrintPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.ReplacementCertificateReviewPage;
import org.testng.Assert;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;
import static org.hamcrest.Matchers.is;

public class DVSAReIssueDuplicateReplacementCertificateTest extends BaseTest {

    Site defaultSite = Site.POPULAR_GARAGES;
    
    @DataProvider public Object[][] dvsaLoginsRePrintCertificate() {
        return new Object[][] {{Login.LOGIN_AREA_OFFICE1}, {Login.LOGIN_DVLA_CENTRAL_OPERATIVE},
                {Login.LOGIN_CUSTOMER_SERVICE},};
    }

    @Test(groups = {"VM-2269", "VM-4515", "VM-2268", "E2E", "VM-7269", "VM-7276", "VM-2268",
            "W-Sprint1", "VM-4355", "slice_A"},
            dataProvider = "dvsaLoginsRePrintCertificate",
            description = "Dvsa Logins can RePrint certificates)")
    public void testRePrintCertificateAsDVSAdminUser(Login adminLogin) {
        DuplicateReplacementCertificatePage
                .navigateHereFromLoginPage(driver, adminLogin, Vehicle.VEHICLE_CLASS1_BALENO_2002)
                .returnToReplacementSearch().clickLogout();

        String motNumber =
                createMotTest(login, defaultSite, Vehicle.VEHICLE_CLASS1_BALENO_2002, 12345,
                        TestOutcome.PASSED);
        DuplicateReplacementCertificatePrintPage duplicateReplacementCertificatePrintPage =
                DuplicateReplacementCertificatePage.navigateHereFromLoginPage(driver, adminLogin,
                        Vehicle.VEHICLE_CLASS1_BALENO_2002).clickViewByMOTNumber(motNumber)
                        .clickReprintCertificate();
        Assert.assertTrue(
                duplicateReplacementCertificatePrintPage.isPrintDocumentButtonDisplayed());
    }

    @Test(enabled = true, groups = {"VM-2570", "VM-2571", "VM-2597", "VM-4648", "VM-7785", "E2E",
            "VM-7269", "VM-4346", "slice_A"},
            description = "Edit fail replacement certificate")
    public void testDVSAUserIssueAndEditReplacementCertificate() {
        Site vts = Site.JOHNS_MOTORCYCLE_GARAGE;
        Colour primaryColour = Colour.Bronze;
        Colour secondaryColour = Colour.Maroon;
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BMW_ALPINA_REISSUE_CERT);
        createMotTest(login, defaultSite, vehicle, 13345, TestOutcome.PASSED);

        ReplacementCertificateReviewPage replacementCertificateReviewPage =
                DuplicateReplacementCertificatePage
                        .navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1, vehicle)

                        .clickEditButtonPass()
                        .editMakeAndModelAndSubmit(VehicleMake.Ford, VehicleModel.Ford_MONDEO)
                        .submitEditedOdometerInfo("12345")
                        .editColoursAndSubmit(primaryColour, secondaryColour)
                        .editVTSLocationAndSubmit(vts.getNumber())
                        .editVinAndSubmit("4S4BP67CX45487878").editVrmAndSubmit("H665R")
                        .editCountryOfRegistrationAndSubmit().enterReasonForReplacement("None")
                        .reviewChangesButton();

        Assert.assertEquals(replacementCertificateReviewPage.testStatus(), (Text.TEXT_STATUS_PASS));
        Assert.assertEquals(replacementCertificateReviewPage.odometerReading(),
                (Text.TEXT_UPDATED_ODOMETER));
        Assert.assertEquals(replacementCertificateReviewPage.vehicleMake(), VehicleMake.Ford.getVehicleMake());
        Assert.assertEquals(replacementCertificateReviewPage.vehicleModel(),
               VehicleModel.Ford_MONDEO.getModelName());

        assertThat("Updated VTS is not displayed in Replacement Certificate Review page",
                replacementCertificateReviewPage.getVtsName(),
                containsString(vts.getNumberAndName()));
        assertThat(
                "Updated vehicle colours are not displayed in Replacement Certificate Review page",
                replacementCertificateReviewPage.vehicleColours(),
                is(primaryColour + " and " + secondaryColour));
        Assert.assertEquals(replacementCertificateReviewPage.vinNumber(), (Text.TEXT_UPDATED_VIN));
        Assert.assertEquals(replacementCertificateReviewPage.registrationNumber(),
                (Text.TEXT_UPDATED_REG));
        replacementCertificateReviewPage.finishAndPrintCertificate().clickReprintReceiptButton();
        UserDashboardPage userDashboardPage = new UserDashboardPage(driver);
        userDashboardPage.clickHome().reissueCertificate();
    }

}
