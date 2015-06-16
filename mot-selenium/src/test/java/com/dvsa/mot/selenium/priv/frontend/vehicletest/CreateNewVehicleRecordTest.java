package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.enums.*;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.Utilities;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.*;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class CreateNewVehicleRecordTest extends BaseTest {

    private final int LIST_OF_COUNTRY_OF_REGISTRATION_COUNT = 37;
    private final String WRONG_VRM = "12345678";
    private final String WRONG_VIN = "wrongvin";


    @Test(groups = {"slice_A", "VM-2333", "Sprint 22", "VM-2588", "MOT Testing"},
            description = "Create a new vehicle and validate that it has been successfully created")
    public void testCreateNewVehicleRecordSuccessfully() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_NON_EXISTENT_6);

        VehicleSearchPage searchVehicle =
                VehicleSearchPage.navigateHereFromLoginPage(driver, login).typeReg(vehicle.carReg)
                        .typeVIN(vehicle.fullVIN).submitSearchExpectingError()
                        .createNewVehicle()
                        .cancelReturnToVehicleSearch();

        assertThat("Search form not displayed", searchVehicle.isVehicleSearchFormDisplayed(),
                is(true));

        searchVehicle.typeReg(vehicle.carReg).typeVIN(vehicle.fullVIN).submitSearchExpectingError()
                .createNewVehicle().enterVehicleDetails(vehicle)
                .submit().
                enterVehicleDetailsWithOutCylinderCapacity(vehicle).submitDetailsExpectingError();

        CreateNewVehicleRecordVehicleSpecificationPage
                createNewVehicleRecordVehicleSpecificationPage =
                new CreateNewVehicleRecordVehicleSpecificationPage(driver);

        assertThat(createNewVehicleRecordVehicleSpecificationPage.isErrorMessageDisplayed(),
                is(true));

        createNewVehicleRecordVehicleSpecificationPage.backLink();

        CreateNewVehicleRecordVehicleIdentificationPage
                createNewVehicleRecordVehicleIdentificationPage =
                new CreateNewVehicleRecordVehicleIdentificationPage(driver);

        assertThat("Incorrect details displayed on Step 1",
                createNewVehicleRecordVehicleIdentificationPage.isCorrectDetailsDisplayed(vehicle),
                is(true));
        assertThat("Number of countries registered has changed",
                createNewVehicleRecordVehicleIdentificationPage.getNumberOfCountriesRegistered(),
                is(LIST_OF_COUNTRY_OF_REGISTRATION_COUNT));

        createNewVehicleRecordVehicleIdentificationPage.submit().enterWrongCylinderCapacityValue()
                .submitDetailsExpectingError();

        assertThat(createNewVehicleRecordVehicleSpecificationPage.isErrorMessageDisplayed(),
                is(true));

        createNewVehicleRecordVehicleSpecificationPage.enterCylinderCapacityValue(vehicle).submit()
                .clickOnBackLink();

        assertThat("Incorrect details displayed on step 2",
                createNewVehicleRecordVehicleSpecificationPage.isCorrectDetailsDisplayed(vehicle),
                is(true));

        UserDashboardPage userDashboardPage =
                createNewVehicleRecordVehicleSpecificationPage.submit()
                        .saveVehicleRecord(Text.TEXT_PASSCODE).returnToHome();

        assertThat("Assert user is returned to home page",
                userDashboardPage.existResumeMotTestButton(), is(true));
    }

    @Test(groups = {"slice_A", "VM-2333", "VM-2588", "VM-8824", "Sprint 22", "MOT Testing"},
            description = "Create a new vehicle record, edit fields within the application process and submitButton to database")
    public void testEditVehicleRecordSuccessfully() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_NON_EXISTENT_7);

        CreateNewVehicleRecordVehicleIdentificationPage
                createNewVehicleRecordVehicleIdentificationPage =
                CreateNewVehicleRecordVehicleIdentificationPage
                        .navigateHereFromLoginPage(driver, login, vehicle)
                        .enterVehicleDetails(vehicle).editVehicleReg(vehicle.carReg + WRONG_VRM)
                        .editVehicleVin(vehicle.fullVIN + WRONG_VIN)
                        .selectCountryOfRegistration(CountryOfRegistration.Please_Select)
                        .submitDetailsExpectingError();

        assertThat(createNewVehicleRecordVehicleIdentificationPage.isErrorMessageDisplayed(),
                is(true));

        NewVehicleRecordConfirmPage newVehicleRecordSummaryPage =
                createNewVehicleRecordVehicleIdentificationPage.clearRegField()
                        .enterVinValue(vehicle).enterRegValueWithNonUKSelected().submit()
                        .enterVehicleDetailsAndSubmit(vehicle).changeVehicleIdentificationDetails()
                        .selectTransmissionType(VehicleTransmissionType.Automatic).submit().submit()
                        .changeVehicleSpecificationDetails().enterVehicleClass(VehicleClasses.five)
                        .enterSecondaryColour(Colour.Purple).submit();

        assertThat("Assert that the registration number is displayed correctly",
                newVehicleRecordSummaryPage.getRegistrationNumber(), is(WRONG_VRM));
        assertThat("Assert that VIN is displayed correctly", newVehicleRecordSummaryPage.getVin(),
                is(vehicle.fullVIN));
        assertThat("Assert that Date of first use is displayed correctly",
                newVehicleRecordSummaryPage.getDateOfFirstUse(),
                is(Utilities.convertDateToGDSFormat(vehicle.dateOfFirstUse)));
        assertThat("Assert that make is displayed correctly", newVehicleRecordSummaryPage.getMake(),
                is(vehicle.make.getVehicleMake()));
        assertThat("Assert that Country of registration is displayed correctly",
                newVehicleRecordSummaryPage.getCountryOfRegistration(),
                is(vehicle.countryOfRegistration.getCountryOfRegistration()));
        assertThat("Assert that the transmission type is displayed correctly",
                newVehicleRecordSummaryPage.getTransmissionType(),
                is(VehicleTransmissionType.Automatic.toString()));
        assertThat("Assert that model is displayed correctly",
                newVehicleRecordSummaryPage.getModel(), is(vehicle.model.getModelName()));
        assertThat("Assert that fuel type is displayed correctly",
                newVehicleRecordSummaryPage.getFuelType(), is(vehicle.fuelType.toString()));
        assertThat("Assert that the vehicle class is displayed correctly",
                newVehicleRecordSummaryPage.getVehicleClass(), is(VehicleClasses.five.getId()));
        assertThat("Assert that the cylinder capacity is displayed correctly",
                newVehicleRecordSummaryPage.getCylinderCapacity(),
                is(Integer.toString(vehicle.cylinderCapacity)));
        assertThat("Assert that the primary colour is displayed correctly",
                newVehicleRecordSummaryPage.getColour(), is(vehicle.primaryColour.toString()));
        assertThat("Assert that the secondary colour is displayed correctly",
                newVehicleRecordSummaryPage.getSecondaryColour(),
                is(Colour.Purple.getColourName()));
    }

    @Test(groups = {"slice_A", "VM-2333", "VM-2588", "VM-8320", "VM-8824", "VM-9272", "Sprint 22",
            "MOT Testing"},
            description = "Create a new vehicle record with validation check of all the fields")
    public void testAllValidationMessagesForCreateNewVehicleRecord() {
        Vehicle vehicle1 = createVehicle(Vehicle.VEHICLE_CLASS4_NON_EXISTENT_4);
        Vehicle vehicle2 = createVehicle(Vehicle.VEHICLE_CLASS4_NON_EXISTENT_11);

        CreateNewVehicleRecordVehicleIdentificationPage
                createNewVehicleRecordVehicleIdentificationPage =
                CreateNewVehicleRecordVehicleIdentificationPage
                        .navigateHereFromLoginPage(driver, login, vehicle1)
                        .selectReasonForEmptyRegMark(EmptyRegAndVin.Missing.getReasonDescription())
                        .selectReasonForEmptyVIN(EmptyRegAndVin.NotFound.getReasonDescription())
                        .submitDetailsWithoutRegAndVin();

        assertThat(createNewVehicleRecordVehicleIdentificationPage.isErrorMessageDisplayed(),
                is(true));

        createNewVehicleRecordVehicleIdentificationPage.enterVehicleDetails(vehicle1)
                .enterRegAndVinValues(vehicle1).submitDetailsExpectingError();

        assertThat(createNewVehicleRecordVehicleIdentificationPage.isErrorMessageDisplayed(),
                is(true));

        createNewVehicleRecordVehicleIdentificationPage.clearRegField().clearVinField()
                .selectReasonForEmptyRegMark(EmptyRegAndVin.Please_select.getReasonDescription())
                .selectReasonForEmptyVIN(EmptyRegAndVin.Please_select.getReasonDescription())
                .submitDetailsExpectingError();

        assertThat(createNewVehicleRecordVehicleIdentificationPage.isErrorMessageDisplayed(),
                is(true));

        createNewVehicleRecordVehicleIdentificationPage.clearDateField().
                submitDetailsWithRegForUKVehicles(vehicle2).
                selectCountryOfRegistration(CountryOfRegistration.Great_Britain)
                .submitDetailsExpectingError();

        assertThat(createNewVehicleRecordVehicleIdentificationPage.isErrorMessageDisplayed(),
                is(true));

        createNewVehicleRecordVehicleIdentificationPage
                .selectCountryOfRegistration(CountryOfRegistration.Northern_Ireland)
                .submitDetailsExpectingError();

        assertThat(createNewVehicleRecordVehicleIdentificationPage.isErrorMessageDisplayed(),
                is(true));

        createNewVehicleRecordVehicleIdentificationPage.enterRegValueWithNonUKSelected().
                submitDetailsExpectingError();

        assertThat(createNewVehicleRecordVehicleIdentificationPage.isErrorMessageDisplayed(),
                is(true));

        createNewVehicleRecordVehicleIdentificationPage.clearRegField().clearVinField()
                .enterAllVehicleDetails(vehicle2).submit().submitDetailsExpectingError();

        assertThat(createNewVehicleRecordVehicleIdentificationPage.isErrorMessageDisplayed(),
                is(true));
    }

    @Test(groups = {"slice_A", "VM-2333", "VM-2588", "Sprint 22", "MOT Testing"},
            description = "When a tester attempts three incorrect PINs when creating a new vehicle record, he is blocked and need to reset the PIN.")
    public void testTesterIsBlockedWhenInsertThreeInvalidPIN() {
        Login loginToBlock = createTester();

        NewVehicleRecordConfirmPage newVehicleRecordSummaryPage = NewVehicleRecordConfirmPage
                .navigateHereFromLoginPage(driver, loginToBlock,
                        Vehicle.VEHICLE_CLASS4_NON_EXISTENT_5)
                .confirmAndSaveExpectingError(Text.TEXT_PASSCODE_INVALID);

        assertThat(newVehicleRecordSummaryPage.isErrorMessageDisplayed(), is(true));

        newVehicleRecordSummaryPage.confirmAndSaveExpectingError(Text.TEXT_PASSCODE_INVALID);

        assertThat(newVehicleRecordSummaryPage.isErrorMessageDisplayed(), is(true));

        newVehicleRecordSummaryPage.confirmAndSaveExpectingError(Text.TEXT_PASSCODE_INVALID);

        assertThat(newVehicleRecordSummaryPage.isErrorMessageDisplayed(), is(true));
    }



    @Test(groups = {"slice_A", "VM-8322", "VM-9272", "VM-8331"})
    public void testANewVehicleRecordIsCreatedWithNoVinAndAbleToStartAnMotTest() {

        Vehicle vehicle = Vehicle.VEHICLE_CLASS4_NON_EXISTENT_12;
        NewVehicleRecordConfirmPage newVehicleRecordSummaryPagePage =
                CreateNewVehicleRecordVehicleIdentificationPage
                        .navigateHereFromLoginPage(driver, login, vehicle)
                        .enterVehicleDetails(vehicle)
                        .selectReasonForEmptyVIN(EmptyRegAndVin.NotFound.getReasonDescription())
                        .submit().enterVehicleDetailsAndSubmit(vehicle);

        MotTestStartedPage motTestStartedPage =
                newVehicleRecordSummaryPagePage.saveVehicleRecord(Text.TEXT_PASSCODE);
        assertThat("A new vehicle record is successfully created",
                motTestStartedPage.isSignOutButtonDisplayed(), is(true));
        motTestStartedPage.clickLogout();
    }


}
