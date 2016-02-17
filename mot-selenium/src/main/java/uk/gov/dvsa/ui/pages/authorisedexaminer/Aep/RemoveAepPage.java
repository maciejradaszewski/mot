package uk.gov.dvsa.ui.pages.authorisedexaminer.Aep;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.authorisedexaminer.AedmAuthorisedExaminerViewPage;
import uk.gov.dvsa.ui.pages.authorisedexaminer.AuthorisedExaminerViewPage;

public class RemoveAepPage extends Page {

    @FindBy(id = "confirm-button") private WebElement submit;

    private static String pageTitle = "Remove a principal";

    public RemoveAepPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), pageTitle);
    }

    public AuthorisedExaminerViewPage submitForm() {
        submit.click();
        return new AedmAuthorisedExaminerViewPage(driver);
    }
}
