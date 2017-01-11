package uk.gov.dvsa.ui.pages.profile.qualificationdetails;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

import java.util.Date;

public class QualificationDetailsPage extends Page {

    public static final String PATH = "/your-profile/%s/qualification-details";
    private static final String PAGE_TITLE = "MOT tester training certificates";

    @FindBy (id = "certificate-number-group-A-change") private WebElement certificateGroupAChangeLink;
    @FindBy (id = "certificate-number-group-B-remove") private WebElement certificateGroupBRemoveLink;
    @FindBy (id = "certificate-number-group-A") private WebElement certificateNumberGroupA;
    @FindBy (id = "certificate-number-group-B") private WebElement certificateNumberGroupB;
    @FindBy (id = "certificate-number-group-A-meta-data") private WebElement certificateDateGroupA;
    @FindBy (id = "validation-message--success") private WebElement validationMessageSuccess;
    @FindBy (id = "qualification-status-group-B") private WebElement qualificationStatusGroupB;

    public QualificationDetailsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public QualificationDetailsGroupAPage clickChangeGroupADetails() {
        certificateGroupAChangeLink.click();
        return new QualificationDetailsGroupAPage(driver);
    }

    public QualificationDetailsRemovePage clickRemoveGroupBDetails() {
        certificateGroupBRemoveLink.click();
        return new QualificationDetailsRemovePage(driver);
    }

    public String getCertifiacateGroupANumber() {
        return certificateNumberGroupA.getText();
    }

    public String getCertifiacateGroupBNumber() {
        return certificateNumberGroupB.getText();
    }

    public String getCertificateGroupADate() {
        return certificateDateGroupA.getText();
    }

    public boolean validationMessageSuccessIsDisplayed(){
        return validationMessageSuccess.isDisplayed();
    }

    public String getAualificationStatusForGroupB()
    {
        return qualificationStatusGroupB.getText();
    }
}
