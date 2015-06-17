package com.dvsa.mot.selenium.pub.frontend.application.tester.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class ManualsPage extends BasePage {
    @FindBy(linkText = "MOT testing guide") private WebElement testingGuides;

    @FindBy(linkText = "MOT inspection manual for class 1 and 2 vehicles") private WebElement
            inspectionManual1and2;

    @FindBy(linkText = "MOT inspection manual for class 3, 4, 5, and 7 vehicles") private WebElement
            inspectionManual3To7;

    @FindBy(linkText = "In service exhaust emission standards for road vehicles: 18th edition")
    private WebElement emissionsBook;

    public ManualsPage(WebDriver driver) {
        super(driver);
    }

    public boolean isTestingGuidesLinkPresent() {
        return testingGuides.isDisplayed();
    }

    public boolean isInspectionManualClasses1And2LinkPresent() {
        return inspectionManual1and2.isDisplayed();
    }

    public boolean isInspectionManualClasses3To7LinkPresent() {
        return inspectionManual3To7.isDisplayed();
    }

    public boolean isEmissionsBookLinkPresent() {
        return emissionsBook.isDisplayed();
    }

    public void viewTestingGuides() {
        testingGuides.click();
    }

    public void viewInspectionManualClasses1And2() {
        inspectionManual1and2.click();
    }

    public void viewInspectionManualClasses3To7() {
        inspectionManual3To7.click();
    }

    public void viewEmissionsBook() {
        emissionsBook.click();
    }

}
