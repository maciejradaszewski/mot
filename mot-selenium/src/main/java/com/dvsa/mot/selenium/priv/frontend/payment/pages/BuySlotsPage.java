package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

/**
 * Buy Test Slots page.
 */

public class BuySlotsPage extends BasePage {

    private static final String PAGE_TITLE = "BUY TEST SLOTS";

    @FindBy(id = "input_slots_number") private WebElement slotsRequired;

    @FindBy(id = "calculateCost") private WebElement calculateCostButton;

    @FindBy(id = "validationError") private WebElement exceedsMaximumBalanceErrorMessage;

    public BuySlotsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public boolean isSlotsRequiredVisible() {
        return isElementDisplayed(slotsRequired);
    }

    public BuySlotsPage enterSlotsRequired(int slots) {
        slotsRequired.sendKeys(Integer.toString(slots));
        return new BuySlotsPage(driver);
    }

    public boolean isCalculateCostButtonVisible() {
        return isElementDisplayed(calculateCostButton);
    }

    public OrderSummaryPage clickCalculateCostButton() {
        calculateCostButton.click();
        return new OrderSummaryPage(driver);
    }

    public BuySlotsPage clickCalculateCostButtonInvalidSlots() {
        calculateCostButton.click();
        return new BuySlotsPage(driver);
    }

    public boolean isExceedsMaximumSlotBalanceMessageDisplayed() {
        return exceedsMaximumBalanceErrorMessage.isDisplayed();
    }

}
