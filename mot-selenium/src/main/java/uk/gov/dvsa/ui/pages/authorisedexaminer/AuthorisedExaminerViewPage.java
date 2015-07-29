package uk.gov.dvsa.ui.pages.authorisedexaminer;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.CompanyDetailsHelper;
import uk.gov.dvsa.helper.ContactDetailsHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;
import static org.hamcrest.core.IsEqual.equalTo;

public abstract class AuthorisedExaminerViewPage extends Page {

    @FindBy(id = "change-contact-details") private WebElement changeContactDetails;

    @FindBy(id = "cor_email") private WebElement correspondenceEmail;

    @FindBy(id = "cor_phone") private WebElement correspondenceTelephone;

    @FindBy(id = "ae-name") private WebElement aeName;
    @FindBy(id = "ae-tradename") private WebElement aeTradeName;
    @FindBy(id = "ae-type") private WebElement aeType;
    @FindBy(id = "ae-company-number") private WebElement aeCompanyNumber;
    @FindBy(id = "reg_AE_address") private WebElement regAddress;
    @FindBy(id = "reg_email") private WebElement regEmail;
    @FindBy(id = "reg_telephone") private WebElement regTelephone;
    @FindBy(id = "cor_address") private WebElement corrAddress;
    @FindBy(id = "cor_email") private WebElement corrEmail;
    @FindBy(id = "cor_phone") private WebElement corrPhone;

    private static String pageTitle = "";
    public static final String PATH = "/authorised-examiner/%s";

    public AuthorisedExaminerViewPage(MotAppDriver driver, String title) {
        super(driver);
        pageTitle = title;
        selfVerify();
    }

    @Override public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), pageTitle);
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

    public String getAeName() {
        return aeName.getText();
    }

    public String getAeTradeName() {
        return aeTradeName.getText();
    }

    public String getAeType() {
        return aeType.getText();
    }

    public String getAeCompanyNumber() {
        return aeCompanyNumber.getText();
    }

    public String getAeRegAddress() {
        return regAddress.getText();
    }

    public String getAeRegEmail() {
        return regEmail.getText();
    }

    public String getAeRegTelephoneNumber() {
        return regTelephone.getText();
    }

    public String getAeCorrAddress() {
        return corrAddress.getText();
    }

    public String getAeCorrEmail() {
        return corrEmail.getText();
    }

    public String getAeCorrPhone() {
        return corrPhone.getText();
    }

    public boolean verifyNewAeBusinessDetails() {
        assertThat(getAeName(), equalTo(CompanyDetailsHelper.businessName));
        assertThat(getAeTradeName(), equalTo(CompanyDetailsHelper.tradingName));
        assertThat(getAeType(), equalTo(CompanyDetailsHelper.businessType));
        assertThat(getAeCompanyNumber(), equalTo(CompanyDetailsHelper.companyNumber));
        return true;
    }

    public boolean verifyNewAeAddressDetails() {
        assertThat(getAeRegAddress(),
                equalTo(ContactDetailsHelper.addressLine1 + ", " + ContactDetailsHelper.addressLine2 + ", " + ContactDetailsHelper.addressLine3
                        + ", " + ContactDetailsHelper.city + ", " + ContactDetailsHelper.postCode));
        return true;
    }

    public boolean verifyNewAeRegContact() {

        assertThat(getAeRegTelephoneNumber(), equalTo(ContactDetailsHelper.phoneNumber));
        assertThat(getAeRegEmail(), equalTo(ContactDetailsHelper.email));
        return true;
    }

    public boolean verifyNewAeCreated() {
        assertThat(verifyNewAeBusinessDetails(), is(true));
        assertThat(verifyNewAeAddressDetails(), is(true));
        assertThat(verifyNewAeRegContact(), is(true));
        return true;
    }
}
