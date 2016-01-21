package uk.gov.dvsa.ui.pages.vts;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.model.VtsChangePageTitle;
import uk.gov.dvsa.domain.model.site.Status;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;
import java.util.List;

public class ChangeSiteDetailsPage extends Page {
    public static final String PATH = "/vehicle-testing-station/%s/%s/change";
    private String pageTitle = "";

    @FindBy(id = "vtsStatusSelectSet") private WebElement statusSelect;
    @FindBy(id = "submitUpdate") private WebElement submitButton;
    @FindBy(id = "vtsNameTextBox") private WebElement siteName;

    public ChangeSiteDetailsPage(MotAppDriver driver, String pageTitle) {
        super(driver);
        this.pageTitle = pageTitle;
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), pageTitle);
    }

    public ChangeSiteDetailsPage changeSiteStatus(Status newStatus) {
        FormCompletionHelper.selectFromDropDownByVisibleText(statusSelect, newStatus.getText());
        return this;
    }

    public ChangeSiteDetailsPage chooseOption(String name) {
        FormCompletionHelper.selectInputBox(driver.findElement(By.xpath(String.format("//label[contains(.,'%s')]", name))));
        return this;
    }

    public ChangeSiteDetailsPage uncheckAllSelectedClasses() {
        List <WebElement> allElements = driver.findElements(By.cssSelector("input:checked[type='checkbox']"));
        for (WebElement element : allElements) {
            element.click();
        }
        return this;
    }

    public ChangeSiteDetailsPage inputSiteDetailsName(String name) {
        FormCompletionHelper.enterText(siteName, name);
        return this;
    }

    public VehicleTestingStationPage clickSubmitButton() {
        submitButton.click();
        return new VehicleTestingStationPage(driver);
    }

    public ConfirmSiteDetailsPage clickConfirmationSubmitButton() {
        submitButton.click();
        return new ConfirmSiteDetailsPage(driver, VtsChangePageTitle.ReviewSiteClasses.getText());
    }
}
