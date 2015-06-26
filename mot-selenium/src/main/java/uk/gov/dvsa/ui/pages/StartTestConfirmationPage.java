package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class StartTestConfirmationPage extends Page {
    public final String path = "/start-test-confirmation/";
    private final String PAGE_TITLE = "Start test confirmation";

    @FindBy(id = "confirm_vehicle_confirmation") private WebElement confirmButton;

    public StartTestConfirmationPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public TestOptionsPage clickStartMotTest() {
        confirmButton.click();
        return new TestOptionsPage(driver);
    }

    public TestResultsEntryPage clickStartMotTestWhenConductingContingencyTest() {
        confirmButton.click();

        return new TestResultsEntryPage(driver);
    }
}
