package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.Select;

import java.util.List;

public class ManualAdjustmentOfSlotsPage extends BasePage {

    private static final String PAGE_TITLE = "MANUAL ADJUSTMENT OF SLOTS";

    @FindBy(id = "input_slots_number") private WebElement slotsToAdjust;

    @FindBy(id = "startAdjust") private WebElement adjustButton;

    public ManualAdjustmentOfSlotsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public ManualAdjustmentOfSlotsPage enterSlotsToBeAdjusted(int slots) {
        slotsToAdjust.sendKeys(Integer.toString(slots));
        return new ManualAdjustmentOfSlotsPage(driver);
    }

    public ManualAdjustmentOfSlotsPage selectPositiveAdjustment() {
        List<WebElement> radios = driver.findElements(By.name("type"));
        for (WebElement radio : radios) {
            if (radio.getAttribute("value").equals("1"))
                (radio).click();
        }
        return new ManualAdjustmentOfSlotsPage(driver);
    }

    public ManualAdjustmentOfSlotsPage selectNegativeAdjustment() {
        List<WebElement> radios = driver.findElements(By.name("type"));
        for (WebElement radio : radios) {
            if (radio.getAttribute("value").equals("-1"))
                (radio).click();
        }
        return new ManualAdjustmentOfSlotsPage(driver);
    }

    public ManualAdjustmentOfSlotsPage selectReason(String reason) {
        Select dropDownBox = new Select(driver.findElement(By.id("inputReason")));
        dropDownBox.selectByVisibleText(reason);
        return new ManualAdjustmentOfSlotsPage(driver);
    }

    public AdjustmentConfirmationPage clickAdjustButton() {
        adjustButton.click();
        return new AdjustmentConfirmationPage(driver);
    }

}
