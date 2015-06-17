package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.TesterCreationApi;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.StartTestConfirmation1Page;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.VehicleSearchPage;
import org.testng.annotations.Test;

import java.util.Arrays;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class VehicleSearchTest extends BaseTest {

    @Test(groups = "slice_A") public void testDisplayedMotTestingCrumbTrail() {

        VehicleSearchPage vehicleSearch =
                VehicleSearchPage.navigateHereFromLoginPage(driver, login);

        assertThat("Vehicle search step not displayed properly",
                vehicleSearch.getVehicleSearchStepNumber().contains("MOT testing"), is(true));
    }

    @Test(groups = "slice_A") public void testDisplayedHeaderInfo() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        //Header info on Vehicle Search page
        VehicleSearchPage vehicleSearch =
                VehicleSearchPage.navigateHereFromLoginPage(driver, login);

        assertThat("Status not correct!", vehicleSearch.getGlobalHeaderInfo().contains(
                Person.TESTER_2_PERSON.forename + " " + Person.TESTER_2_PERSON.middleName
                        + " " + Text.TEXT_TESTER_2_NAME + " "
                        + Text.TEXT_TESTER_2_GARAGE_ADDRESS), is(false));

        //Header info on Start test confirmation page
        StartTestConfirmation1Page confPage =
                vehicleSearch.submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg);

        assertThat("Status not correct!", confPage.getUserInfo().contains(
                        Person.TESTER_2_PERSON.forename + " " + Text.TEXT_TESTER_2_STATUS + " " +
                                Text.TEXT_TESTER_2_NAME + " " + Text.TEXT_TESTER_2_GARAGE_ADDRESS),
                is(false));
    }

    @Test(groups = {"slice_A", "VM-8752"}) public void testCancelButton() {

        UserDashboardPage vehicleSearchPage =
                VehicleSearchPage.navigateHereFromLoginPage(driver, login).clickCancel();

        assertThat("Check that user is navigated to home page after clicking on cancel link",
                vehicleSearchPage.isStartMotTestDisplayed(), is(true));
    }

    @Test(groups = {"slice_A", "VM-18", "VM-8752"}) public void testSubmitFull20Vin_NoReg() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_NO_REG);

        StartTestConfirmation1Page vehicleSearch =
                VehicleSearchPage.navigateHereFromLoginPage(driver, login)
                        .submitSearchWithVinOnly(vehicle.fullVIN).clickVehicleCTA();

        assertThat("Check that user is on the Start test confirmation page",
                vehicleSearch.isStartMotTestButtonDisplayed(), is(true));

    }

    @Test(groups = {"slice_A", "VM-18", "VM-8752", "short"})
    public void testDiffSearchTypesWithNoReg() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_NO_REG_6VIN);

        VehicleSearchPage vehicleSearch = VehicleSearchPage.navigateHereFromLoginPage(driver, login)
                .submitSearchExpectingFailure();

        assertThat("Empty vehicle Search", vehicleSearch.getMainMessageInfoText(),
                is(Assertion.ASSERTION_NO_VIN_NO_REG_MESSAGE.assertion));
        assertThat("Empty vehicle Search", vehicleSearch.getAdditionalMessageInfo(),
                is(Assertion.ASSERTION_ADDITIONAL_MESSAGE_FOR_NO_VIN_NO_REG.assertion));


        //Search for vehicle with no Reg and with VIN, 0 matches
        vehicleSearch
                .submitSearchWithVinExpectingFail(vehicle.VEHICLE_WITH_VIN_NO_MATCHING.fullVIN);

        assertThat("Search for vehicle with no Reg and with VIN, 0 matches",
                vehicleSearch.getMainMessageInfoText(),
                is(Assertion.ASSERTION_VEHICLE_SEARCH_WITHOUT_REG_NO_RESULTS.assertion
                        + Assertion.ASSERTION_VEHICLE_SEARCH_VIN_ENDING.assertion
                        + Vehicle.VEHICLE_WITH_VIN_NO_MATCHING.fullVIN + "."));
        assertThat("Search for vehicle with no Reg and with VIN, 0 matches",
                vehicleSearch.getAdditionalMessageInfo(),
                is(Assertion.ASSERTION_ADDITIONAL_MESSAGE_FOR_0_RESULTS_WITHOUT_REG.assertion));

        //Search for vehicle with no Reg and with incorrect VIN, 0 matches
        vehicleSearch.submitSearchWithVinExpectingFail(vehicle.fullVIN.substring(1));

        assertThat("Search for vehicle with no Reg and with incorrect VIN, 0 matches",
                vehicleSearch.getMainMessageInfoText(),
                is(Assertion.ASSERTION_VEHICLE_SEARCH_WITHOUT_REG_NO_RESULTS.assertion
                        + Assertion.ASSERTION_VEHICLE_SEARCH_VIN_MATCHING.assertion
                        + vehicle.fullVIN.substring(1) + "."));
        assertThat("Search for vehicle with no Reg and with incorrect VIN, 0 matches",
                vehicleSearch.getAdditionalMessageInfo(),
                is(Assertion.ASSERTION_ADDITIONAL_MESSAGE_FOR_0_RESULTS_WITHOUT_REG_WRONG_VIN.assertion));

        //Search with Full Correct VIN and return positive results
        vehicleSearch.submitSearchWithVinOnly(vehicle.fullVIN);

        assertThat("Search with Full Correct VIN and return positive results",
                vehicleSearch.getMainMessageInfoText(),
                is(Assertion.ASSERTION_VEHICLE_SEARCH_RESULTS_MESSAGE.assertion
                        + Assertion.ASSERTION_VEHICLE_SEARCH_VIN_ENDING.assertion + vehicle.fullVIN
                        + "."));

        vehicleSearch.clickVehicleCTA();
    }

    @Test(groups = {"slice_A", "VM-18", "VM-1498", "VM-8752"})
    public void testSubmitFullVinAbortData() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BOXSTER_2001);

        //Search vehicle with VIN not matching that vehicle reg
        VehicleSearchPage vehicleSearch = VehicleSearchPage.navigateHereFromLoginPage(driver, login)
                .submitSearchWithVinAndRegExpectingError(Vehicle.VEHICLE_NO_REG_6VIN.fullVIN,
                        vehicle.carReg);

        assertThat("Search vehicle with VIN not matching that vehicle reg",
                vehicleSearch.getMainMessageInfoText(),
                is((Assertion.ASSERTION_VEHICLE_SEARCH_MESSAGE_REG.assertion + vehicle.carReg
                        + Assertion.ASSERTION_VEHICLE_SEARCH_VIN_ENDING.assertion)
                        + Vehicle.VEHICLE_NO_REG_6VIN.fullVIN + "."));
        assertThat("Search vehicle with VIN not matching that vehicle reg",
                vehicleSearch.getAdditionalMessageInfo(),
                is(Assertion.ASSERTION_ADDITIONAL_MESSAGE_FOR_0_RESULTS.assertion));

        UserDashboardPage confPage = vehicleSearch.
                submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg).clickRetry();

        assertThat(
                "Check that user is navigated to home page after clicking cancel and return to home page link",
                confPage.isStartMotTestDisplayed(), is(true));
    }

    @Test(groups = {"slice_A", "VM-18"}) public void testLast6VinAndReg_BackToSearch() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);
        String lastSixCharInVin = vehicle.fullVIN.substring(vehicle.fullVIN.length() - 6);

        StartTestConfirmation1Page vehicleSearch =
                VehicleSearchPage.navigateHereFromLoginPage(driver, login).
                        submitSearchWithVinAndReg(vehicle.fullVIN.substring(11), vehicle.carReg);

        assertThat("Check that user is on the Start test confirmation page",
                vehicleSearch.isStartMotTestButtonDisplayed(), is(true));

        VehicleSearchPage vehicleSearchPage = vehicleSearch.clickSearchAgain();

        assertThat("return to search page with data persisting from confirmation page",
                vehicleSearchPage.verifyRegistrationPresent(), is(true));
        assertThat("return to search page with data persisting from confirmation page",
                vehicleSearchPage.getMainMessageInfoText(),
                is(Assertion.ASSERTION_VEHICLE_SEARCH_RESULTS_RETURNED.assertion + vehicle.carReg
                        + Assertion.ASSERTION_VEHICLE_SEARCH_VIN_ENDING.assertion + lastSixCharInVin
                        + "."));

    }

    @Test(groups = {"slice_A", "VM-18", "VM-8752"})
    public void testMid6VinAndFullRegNoResultsAndCreateNewVehicleLink() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        VehicleSearchPage vehicleSearch =
                VehicleSearchPage.navigateHereFromLoginPage(driver, login).
                        submitSearchWithVinAndRegExpectingError(vehicle.fullVIN.substring(4, 10),
                                vehicle.carReg);

        assertThat("Check main message information text", vehicleSearch.getMainMessageInfoText(),
                is(Assertion.ASSERTION_VEHICLE_SEARCH_MESSAGE_REG.assertion + vehicle.carReg
                        + Assertion.ASSERTION_VEHICLE_SEARCH_VIN_ENDING.assertion + vehicle.fullVIN
                        .substring(4, 10) + "."));
        assertThat("Check additional message information text",
                vehicleSearch.getAdditionalMessageInfo(),
                is(Assertion.ASSERTION_ADDITIONAL_MESSAGE_FOR_0_RESULTS.assertion));

        assertThat("Create new vehicle record link", vehicleSearch.getCreateNewVehicleInfoText(),
                is(Assertion.ASSERTION_SEARCH_CREATE_NEW_VEHICLE.assertion));

        //Create new vehicle record link
        assertThat(vehicleSearch.isCreateNewVehicleRecordLinkPresent(),is(true));

    }

    @Test(groups = {"slice_A", "VM-18", "short", "VM-8752"}) public void testSubmitMaxVinAndReg() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_21CHARVIN_14REG);
        Vehicle vehicle1 = createVehicle(Vehicle.VEHICLE_20CHARVIN_13REG);

        VehicleSearchPage vehicleSearch = VehicleSearchPage.navigateHereFromLoginPage(driver, login)
                .submitSearchWithVinAndRegExpectingError(vehicle.fullVIN, vehicle.carReg);

        assertThat("Check main message information text", vehicleSearch.getMainMessageInfoText(),
                is(Assertion.ASSERTION_VEHICLE_SEARCH_MESSAGE_REG.assertion + vehicle.carReg
                        .substring(0, 13)
                        + Assertion.ASSERTION_VEHICLE_SEARCH_VIN_MATCHING.assertion
                        + vehicle.fullVIN.substring(0, 20) + "."));
        assertThat("Check additional message information text",
                vehicleSearch.getAdditionalMessageInfo(),
                is(Assertion.ASSERTION_ADDITIONAL_MESSAGE_FOR_LESS_OR_MORE_6CHAR_VIN.assertion));

        StartTestConfirmation1Page confirmationPage =
                vehicleSearch.submitSearchWithVinAndReg(vehicle1.fullVIN, vehicle1.carReg);

        assertThat("Assert car make and model", confirmationPage.getCarMakeAndModel(),
                is(vehicle1.getCarMakeAndModel()));
    }

    @Test(groups = {"slice_A", "VM-1498", "VM-8752"}) public void testSearchWithNoVin() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_NO_VIN);

        VehicleSearchPage vehicleSearchPage =
                VehicleSearchPage.navigateHereFromLoginPage(driver, login)
                        .submitSearchWithRegOnlyExpectingVehicleSearchPage(
                                vehicle.carReg.substring(1));

        assertThat("Check additional message information text",
                vehicleSearchPage.getMainMessageInfoText(),
                is(Assertion.ASSERTION_VEHICLE_SEARCH_MESSAGE_REG.assertion + vehicle.carReg
                        .substring(1) + Assertion.ASSERTION_VEHICLE_SEARCH_WITHOUT_VIN.assertion));
        assertThat("Check additional message information text",
                vehicleSearchPage.getAdditionalMessageInfo(),
                is(Assertion.ASSERTION_ADDITIONAL_MESSAGE_FOR_0_RESULTS_WITHOUT_VIN.assertion));


        StartTestConfirmation1Page confirmationPage =
                vehicleSearchPage.typeReg(vehicle.carReg).clickSearch().clickVehicleCTA();

        assertThat("Check that user is on the Start test confirmation page",
                confirmationPage.isStartMotTestButtonDisplayed(), is(true));

    }
    @Test(groups = {"slice_A", "VM-4791"})
    public void testTheCookieElementIsPresentInTheDOMOfAVehicleSearchPage() {
        TesterCreationApi testerCreationApi = new TesterCreationApi();
        Person tester = testerCreationApi.createTesterAsPerson(Arrays.asList(1));
        VehicleSearchPage vehicleSearchPage = LoginPage.loginAs(driver, tester.login).startMotTest();
        assertThat(vehicleSearchPage.isCookieElementPresentInDOM(),is(true));
    }
}
