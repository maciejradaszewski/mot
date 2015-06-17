/*
package com.dvsa.mot.selenium.priv.frontend.review.application.vehicle.inputdetails.pages;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.enums.CountryOfRegistration;
import com.dvsa.mot.selenium.datasource.enums.FuelTypes;
import com.dvsa.mot.selenium.datasource.enums.VehicleClasses;
import com.dvsa.mot.selenium.framework.BasePage;
import org.joda.time.DateTime;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class VehicleInputInformationPage extends BasePage {

    @FindBy(id = "registrationNumber")
    private WebElement inputRegistrationNumber;

    @FindBy(id = "vin")
    private WebElement InputFullVin;

    @FindBy(id = "make")
    private WebElement selectdMakeDropDown;
    
    @FindBy(id = "model")
    private WebElement selectdModelDropDown;

    @FindBy(id = "modelType")
    private WebElement selectModelTypeDropDown;

    @FindBy(id = "colour")
    private WebElement selectColourDropDown;

    @FindBy(id = "secondaryColour")
    private WebElement selectSecondaryColourDropDown;

    @FindBy(id = "day")
    private WebElement selectDayOfFirstUseDropDown;

    @FindBy(id = "month")
    private WebElement selectMonthOfFirstUseDropDown;

    @FindBy(id = "year")
    private WebElement inputYearOfFirstUse;

    @FindBy(id = "fuelType")
    private WebElement selectFuelTypeDropDown;

    @FindBy(id = "testClass")
    private WebElement selectTestClassDropDown;
    
    @FindBy(id = "countryOfRegistration")
    private WebElement selectCountryOfRegDropDown;
    
    @FindBy(id = "cylinderCapacity")
    private WebElement inputVehicleCylinderCapacity;
    
    @FindBy(id = "transmissionType")
    private WebElement selectTransmissionTypeDropDown;
    
    @FindBy(id = "save-button")
    private WebElement buttonSave;
    
    @FindBy(id = "validation-summary-id")
    private WebElement requiredFieldSummary;
    
    public VehicleInputInformationPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public void enterRegistrationNumber(String carReg) {
    	inputRegistrationNumber.sendKeys(carReg);
    }
    
    public void enterFullVehicleIDNumber(String fullVIN) {
    	InputFullVin.sendKeys((fullVIN));
    }
    public void selectMake(String make) {
    	new Select(selectdMakeDropDown).selectByVisibleText(make);
    }
    public void selectModel(String model) {
    	new Select(selectdModelDropDown).selectByVisibleText(model);
    }
    public void selectModelType(String modelType) {
    	new Select(selectModelTypeDropDown).selectByVisibleText(modelType);
    }
    
    public void selectColour(Vehicle.Colour colour) {
    	new Select(selectColourDropDown).selectByVisibleText(colour.toString());
    }
    
    public void selectSecondaryColour(Vehicle.Colour secondaryColour) {
    	new Select(selectSecondaryColourDropDown).selectByVisibleText(secondaryColour.toString());
    }
    
    public VehicleInputInformationPage selectDayOfFirstUse(int day) {
    	new Select(selectDayOfFirstUseDropDown).selectByIndex(day-1);
    	return this;
    }
    
    public VehicleInputInformationPage selectMonthOfFirstUse(int month) {
    	new Select(selectMonthOfFirstUseDropDown).selectByIndex(month-1);
    	return this;
    }
    
    public VehicleInputInformationPage enterYearOfFirstUse(int year) {
        inputYearOfFirstUse.sendKeys(Integer.toString(year));
        return this;
    }
    
    public VehicleInputInformationPage selectFuelType(FuelTypes fuelType) {
    	new Select(selectFuelTypeDropDown).selectByValue(fuelType.getFuelId());
    	return this;
    }
    public VehicleInputInformationPage selectTestClass(VehicleClasses vehicleClass) {
    	new Select(selectTestClassDropDown).selectByVisibleText(vehicleClass.getId());
    	return this;
    }
    
    public VehicleInputInformationPage selectCountryOfRegistration(CountryOfRegistration countryOfRegistration) {
    	new Select(selectCountryOfRegDropDown).selectByVisibleText(countryOfRegistration);
    	return this;
    }
    
    public void enterCylinderCapacity(int cylinderCapacity) {
    	inputVehicleCylinderCapacity.sendKeys(Integer.toString(cylinderCapacity));
    }
    
    public VehicleInputInformationPage selectTransmissionType(Vehicle.TransmissionType transmission) {
    	new Select(selectTransmissionTypeDropDown).selectByVisibleText(transmission.toString());
    	return this;
    }
    
    public void  clickOnSaveButton() {
    	buttonSave.click();
    }
 
   public VehicleInputInformationPage navigateToVehicleInputPage() {
        
    	String inputVehicleUrl = baseUrl() + "/vehicle";
    	driver.get(inputVehicleUrl);
    	return new VehicleInputInformationPage(driver);
    }
   public VehicleInputInformationPage enterDateOfFirstUse(DateTime date) { 
	  
         int day = date.getDayOfMonth();
         selectDayOfFirstUse(day);
         int month = date.getMonthOfYear();
         selectMonthOfFirstUse(month);
         int year = date.getYear();
         enterYearOfFirstUse(year);
         return this;
   }
   

   public void  enterNoRegistrationNumber() {
	   inputRegistrationNumber.clear();
   }
   
   public void  enterNoFullVehicleIDNumber() {
	   InputFullVin.clear();
   }
  
   public void  enterNoCylinderCapacity() {
	   inputVehicleCylinderCapacity.clear();
   }
   
   public boolean isVehicleDetailsWithoutRegistration() {
       return inputRegistrationNumber.equals(findElementMarkedInvalid());
   }

   public boolean isVehicleDetailsWithoutFullVIN() {
       return InputFullVin.equals(findElementMarkedInvalid());
   }
   
   public boolean isVehicleDetailsWithoutYearOfFirstUse() {
       return inputYearOfFirstUse.equals(findElementMarkedInvalid());
   }
   
   public boolean isVehicleDetailsWithoutCylinderCapacity() {
       return inputVehicleCylinderCapacity.equals(findElementMarkedInvalid());
   }
   
   public boolean isVehicleDetailsWithoutColour() {
	   assertThat("Colour Required", errorMessage(), is(Assertion.ASSERTION_VEHICLE_COLOUR.assertion));
	   return true;
   }
   
   public boolean isVehicleDetailsWithoutCountryOfRegistration() {
	   assertThat("Country of Registration Required", errorMessage(), is(Assertion.ASSERTION_VEHICLE_COUNTRY_OF_REGISTRATION.assertion));  
	   return true;
   }
   
   public boolean isVehicleDetailsWithoutMake() {
	   assertThat("Make Required", errorMessage(), is(Assertion.ASSERTION_VEHICLE_MAKE.assertion));
	   return true;
   }
   public boolean isVehicleDetailsWithoutModel() {
	   assertThat("Model Required", errorMessage(), is(Assertion.ASSERTION_VEHICLE_MODEL.assertion));
	   return true;
   }
	
   public boolean isVehicleDetailsWithoutModelType() {
	   assertThat("ModelType Required", errorMessage(), is(Assertion.ASSERTION_VEHICLE_MODEL_TYPE.assertion));
	   return true;
   }
   
   public boolean isVehicleDetailsWithoutTestClass() {
	   assertThat("TestClass Required", errorMessage(), is(Assertion.ASSERTION_VEHICLE_TEST_CLASS.assertion));
	   return true;
   }
   public boolean isVehicleDetailsWithoutFuelType() {
	   assertThat("FuelType Required", errorMessage(), is(Assertion.ASSERTION_VEHICLE_FUEL_TYPE.assertion));
	   return true;
   }
   public boolean isVehicleDetailsWithoutTransmissionType() {
	   assertThat("Transmission Type Required", errorMessage(), is(Assertion.ASSERTION_VEHICLE_TRANSMISSION_TYPE.assertion));
	   return true;
   }
   public String errorMessage() {
	
	    return  requiredFieldSummary.getText(); 
	 
   }
   public void selectNoCountryOfRegistration() {
       	new Select(selectCountryOfRegDropDown).selectByIndex(0);
   }
   public void selectNoFuelType() {
	   new Select(selectFuelTypeDropDown).selectByIndex(0);
   }
   public void selectNoColour() {
	   new Select(selectColourDropDown).selectByIndex(0);
   }
   
   public void selectNoMake() {
	   new Select(selectdMakeDropDown).selectByIndex(0);
   }
   
   public void selectNoModel() {
	   new Select(selectdModelDropDown).selectByIndex(0);
   }
   
   public void selectNoModelType() {
	   new Select(selectModelTypeDropDown).selectByIndex(0);
   }   
   
   public void selectNoTestClass() {
	   new Select(selectTestClassDropDown).selectByIndex(0);
   }
   
   public void selectNoTransmission() {
	   new Select(selectTransmissionTypeDropDown).selectByIndex(0);
   }
   public  VehicleInputInformationPage fillVehicleInfo(Vehicle vehicle) {
		VehicleInputInformationPage vehicleInformation = new VehicleInputInformationPage(driver);
		vehicleInformation.
   			navigateToVehicleInputPage();
   			enterRegistrationNumber(vehicle.carReg);
   			enterFullVehicleIDNumber(vehicle.fullVIN);
   			selectMake(vehicle.make);
   			selectModel(vehicle.model);
   			selectModelType(vehicle.modelType);
   			selectColour(vehicle.primaryColour);
   			selectSecondaryColour(vehicle.secondaryColour);
   			enterDateOfFirstUse(vehicle.dateOfFirstUse);
   			selectFuelType(vehicle.fuelType);
   			selectTestClass(vehicle.vehicleClass);
   			selectCountryOfRegistration(vehicle.countryOfRegistration);
   			enterCylinderCapacity(vehicle.cylinderCapacity);
   			selectTransmissionType(vehicle.transType);
		return vehicleInformation;
	}
}
*/



