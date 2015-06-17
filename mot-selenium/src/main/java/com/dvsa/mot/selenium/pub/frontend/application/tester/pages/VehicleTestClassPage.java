package com.dvsa.mot.selenium.pub.frontend.application.tester.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class VehicleTestClassPage extends BasePage {

    //Page Factory

    @FindBy(id = "classes1and2") private WebElement class1And2CheckBox;

    @FindBy(id = "classes3to7") private WebElement classes3To7CheckBox;

    @FindBy(id = "drivingLicenceNumber") private WebElement drivingLicenceNumberField;

    @FindBy(id = "non-uk") private WebElement nonUkLicence;

    @FindBy(id = "experience-section") private WebElement experienceSection;

    @FindBy(id = "vehicle-test-class-section") private WebElement vehicleTestClassSection;

    @FindBy(id = "edit-button") private WebElement editButton;

    @FindBy(id = "done-button") private WebElement doneButton;

    @FindBy(id = "vehicle-test-class-done-label") private WebElement vehicleTestClassDoneLabel;

    public VehicleTestClassPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    // Page services
    public VehicleTestClassPage class1And2CheckBoxEnabled() {
        if (!class1And2CheckBox.isSelected())
            class1And2CheckBox.click();
        return this;
    }

    public VehicleTestClassPage class1And2CheckBoxDisabled() {
        if (class1And2CheckBox.isSelected())
            class1And2CheckBox.click();
        return this;
    }

    public VehicleTestClassPage classes3To7CheckBoxEnabled() {
        if (!classes3To7CheckBox.isSelected())
            classes3To7CheckBox.click();
        return this;
    }

    public VehicleTestClassPage classes3To7CheckBoxDisabled() {
        if (classes3To7CheckBox.isSelected())
            classes3To7CheckBox.click();
        return this;
    }

    public VehicleTestClassPage enterDrivingLicenceNumber(String text) {
        drivingLicenceNumberField.sendKeys(text);
        return this;
    }

    public VehicleTestClassPage clearDrivingLicenseNumber() {
        drivingLicenceNumberField.clear();
        return this;
    }

    public VehicleTestClassPage selectNonUKDrivingLicenceCheckbox() {
        if (!nonUkLicence.isSelected())
            nonUkLicence.click();
        return this;
    }

    public VehicleTestClassPage unSelectNonUKDrivingLicenceCheckbox() {
        if (nonUkLicence.isSelected())
            nonUkLicence.click();
        return this;
    }

    public WebElement clickEditButton() {
        editButton.click();
        return editButton;
    }

    public void clickDoneButton() {
        doneButton.click();
    }

    public VehicleTestClassPage completeVehicleTestClass(boolean isClass1And2Vehicles,
            boolean isClass3To7Vehicles, String licenceNumber, boolean isUKDrivingLicence) {
        if (isClass1And2Vehicles)
            class1And2CheckBoxEnabled();
        else
            class1And2CheckBoxDisabled();
        if (isClass3To7Vehicles)
            classes3To7CheckBoxEnabled();
        else
            classes3To7CheckBoxDisabled();
        enterDrivingLicenceNumber(licenceNumber);
        if (isUKDrivingLicence)
            unSelectNonUKDrivingLicenceCheckbox();
        else
            selectNonUKDrivingLicenceCheckbox();
        return this;
    }

    public VehicleTestClassPage editAndNotSaveVehicleTestClass(boolean isClass1And2Vehicles,
            boolean isClass3To7Vehicles, String drivingLicence, boolean isUKDrivingLicence) {
        completeVehicleTestClass(isClass1And2Vehicles, isClass3To7Vehicles, drivingLicence,
                isUKDrivingLicence);
        experienceSection.click();
        vehicleTestClassSection.click();
        return this;
    }

    public boolean isDrivingLicenceNumberField() {
        return drivingLicenceNumberField.equals(findElementMarkedInvalid());
    }

    public boolean isDoneLabelDisplayed() {
        return isElementDisplayed(vehicleTestClassDoneLabel);
    }
}


