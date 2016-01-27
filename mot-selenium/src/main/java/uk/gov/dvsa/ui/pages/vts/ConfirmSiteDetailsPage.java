package uk.gov.dvsa.ui.pages.vts;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class ConfirmSiteDetailsPage extends Page {
    public static final String path = "/vehicle-testing-station/%s/%s/review";
    private String pageTitle = "";

    @FindBy(id = "submitUpdate") private WebElement submitButton;
    @FindBy(id = "classes") private WebElement tableElementValue;

    public ConfirmSiteDetailsPage(MotAppDriver driver, String pageTitle) {
        super(driver);
        this.pageTitle = pageTitle;
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), pageTitle);
    }

    public  VehicleTestingStationPage clickSubmitButton() {
        submitButton.click();
        return new VehicleTestingStationPage(driver);
    }

    public String getClasses() {
        return tableElementValue.getText();
    }
}