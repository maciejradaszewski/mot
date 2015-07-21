package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import java.util.List;

public class SetUpDirectDebitPage extends BasePage {

    private static final String PAGE_TITLE = "SET UP DIRECT DEBIT";

    @FindBy(id = "input_slots_number") private WebElement slotsRequired;

    @FindBy(id = "input_collection_date") private WebElement collectionDate;

    @FindBy(id = "calculateCost") private WebElement continueButton;

    public SetUpDirectDebitPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public SetUpDirectDebitPage enterSlotsRequired(int slots) {
        slotsRequired.sendKeys(Integer.toString(slots));
        return new SetUpDirectDebitPage(driver);
    }

    public SetUpDirectDebitPage selectCollectionDayOfMonth(String date) {
        List<WebElement> radios = driver.findElements(By.name("collection-day"));
        for (WebElement radio : radios) {
            if (radio.getAttribute("value").equals(date))
                (radio).click();
        }
        return new SetUpDirectDebitPage(driver);
    }

    public ReviewDirectDebitDetailsPage clickContinueButton() {
        continueButton.click();
        return new ReviewDirectDebitDetailsPage(driver);
    }

}
