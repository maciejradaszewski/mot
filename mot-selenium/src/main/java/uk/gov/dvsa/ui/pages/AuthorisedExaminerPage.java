package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class AuthorisedExaminerPage extends Page {

    @FindBy(id = "change-contact-details") private WebElement changeContactDetails;

    @FindBy(id = "cor_email") private WebElement correspondenceEmail;

    @FindBy(id = "cor_phone") private WebElement correspondenceTelephone;

    private static final String PAGE_TITLE = "Authorised Examiner";
    public static final String PATH = "/authorised-examiner/%s";

    public AuthorisedExaminerPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public AuthorisedExaminerChangeDetailsPage clickChangeContactDetailsLink() {
        changeContactDetails.click();
        return new AuthorisedExaminerChangeDetailsPage(driver);
    }

    public String getCorrespondenceEmailText() {
        return correspondenceEmail.getText();
    }

    public String getCorrespondenceTelephoneText() {
        return correspondenceTelephone.getText();
    }

}
