package uk.gov.dvsa.ui.pages.authorisedexaminer.ChangeDetails;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.AreaOfficerAuthorisedExaminerViewPage;
import uk.gov.dvsa.ui.pages.Page;

public abstract class ChangeAEDetailsPage extends Page {
    private String pageTitle = "";

    @FindBy(id = "submitUpdate") private WebElement submitButton;

    public ChangeAEDetailsPage(MotAppDriver driver, String pageTitle) {
        super(driver);
        this.pageTitle = pageTitle;
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), pageTitle);
    }

    public AreaOfficerAuthorisedExaminerViewPage clickSubmitButton() {
        submitButton.click();
        return new AreaOfficerAuthorisedExaminerViewPage(driver);
    }
}
