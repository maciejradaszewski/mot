package com.dvsa.mot.selenium.e2e.dvsa;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.enums.MotSearchBy;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.MotTestApi;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EnforcementVTSSearchPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SearchVehicleInformationPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.VehicleSearchPage;
import org.testng.annotations.Test;

public class VehicleExaminerEndToEndTests extends BaseTest {

    @Test(groups = {"VM-7261", "VM-7263", "E2E"}) public void testVehicleExaminerCanAbortTest() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BOXSTER_2001);
        createMotTest(login, Site.POPULAR_GARAGES, vehicle, 12000, MotTestApi.TestOutcome.FAILED);
        SearchVehicleInformationPage.navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER)
                .selectVehicleType(Text.TEXT_VRM_TYPE)
                .submitVehicleInformationSearch(vehicle.carReg).clickHome().clickLogout();

        EnforcementVTSSearchPage enforcementVTSsearchPage =
                EnforcementVTSSearchPage.navigateHereFromLoginPage(driver, Login.LOGIN_VE3);
        enforcementVTSsearchPage.selectMotSearchBy(MotSearchBy.Tester)
                .selectMotSearchBy(MotSearchBy.Vin).selectMotSearchBy(MotSearchBy.Vrm)
                .searchForVehicle(vehicle.carReg).clickOnViewFailResultTest().clickLogout();

        Vehicle vehicle1 = createVehicle(Vehicle.VEHICLE_CLASS7_MERCEDESBENZ_2005);
        VehicleSearchPage vehicleSearchPage =
                VehicleSearchPage.navigateHereFromLoginPage(driver, login);
        vehicleSearchPage.submitSearchWithVinAndReg(vehicle1.fullVIN, vehicle1.carReg)
                .submitConfirm().clickLogout();
        enforcementVTSsearchPage.navigateHereFromLoginPage(driver, Login.LOGIN_VE3)
                .selectMotSearchBy(MotSearchBy.Vrm).searchForVehicle(vehicle1.carReg)
                .clickOnInProgressTest().clickAbortTest()
                .enterReasonForAborting(Text.TEXT_ENTER_A_REASON_FOR_ABORTING_BY_VE)
                .confirmReasonForAborting().clickLogout();
    }
}

