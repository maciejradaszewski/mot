package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class RetestVehicleSearchPage extends Page {

    public static final String path = "/retest-vehicle-search";
    private static final String PAGE_TITLE = "Find a vehicle - retest";

    @FindBy(id = "test-number-input") private WebElement testNumberInput;
    @FindBy(id = "vehicle-search") private WebElement searchButton;

    public RetestVehicleSearchPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public RetestVehicleSearchPage fillTestNumberField(String searchValue) {
        testNumberInput.sendKeys(searchValue);
        return this;
    }

    public RetestVehicleSearchPage clickSearchButton() {
        searchButton.click();
        return this;
    }
}
