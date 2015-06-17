package com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class ConfirmationClass extends BasePage {

    @FindBy(id = "vehicle-class-select") private WebElement vehicleClass;

    @FindBy(id = "vehicle-class-select_1") private WebElement vehicleClass1;

    @FindBy(id = "vehicle-class-select_2") private WebElement vehicleClass2;

    @FindBy(id = "vehicle-class-select_3") private WebElement vehicleClass3;

    @FindBy(id = "vehicle-class-select_4") private WebElement vehicleClass4;

    @FindBy(id = "vehicle-class-select_5") private WebElement vehicleClass5;

    @FindBy(id = "vehicle-class-select_6") private WebElement vehicleClass6;

    @FindBy(id = "vehicle-class-select_7") private WebElement vehicleClass7;

    @FindBy(id = "vehicle-class-select_8") private WebElement vehicleClass8;

    @FindBy(id = "refusal") private WebElement refusalChooseReason;

    @FindBy(id = "refusal_0") private WebElement refusal0;

    @FindBy(id = "refusal_1") private WebElement refusal1;

    @FindBy(id = "refusal_2") private WebElement refusal2;

    @FindBy(id = "refusal_3") private WebElement refusal3;

    @FindBy(id = "refusal_4") private WebElement refusal4;

    @FindBy(id = "refusal_5") private WebElement refusal5;

    @FindBy(id = "refusal_6") private WebElement refusal6;

    @FindBy(id = "refusal_7") private WebElement refusal7;

    @FindBy(id = "refusal_8") private WebElement refusal8;

    @FindBy(id = "refusal_9") private WebElement refusal9;

    @FindBy(id = "refusal_10") private WebElement refusal10;

    @FindBy(id = "refusal_11") private WebElement refusal11;

    @FindBy(id = "refusal_12") private WebElement refusal12;

    @FindBy(id = "confirm_vehicle_confirmation") private WebElement confirmButton;

    @FindBy(id = "abort_vehicle_confirmation") private WebElement cancelButton;

    public ConfirmationClass(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public ConfirmationClass cancelButton() {
        cancelButton.click();
        return new ConfirmationClass(driver);
    }

}
