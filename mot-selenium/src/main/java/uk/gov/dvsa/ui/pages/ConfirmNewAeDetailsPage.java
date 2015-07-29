package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.CompanyDetailsHelper;
import uk.gov.dvsa.helper.ContactDetailsHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;
import static org.hamcrest.core.IsEqual.equalTo;

public class ConfirmNewAeDetailsPage extends Page {
    private static final String PAGE_TITLE = "Confirm new AE details";

    @FindBy(id = "businessName") private WebElement businessName;
    @FindBy(id = "tradingName") private WebElement tradingName;
    @FindBy(id = "businessType") private WebElement businessType;
    @FindBy(id = "companyNumber") private WebElement companyNumber;
    @FindBy(id = "regAddress") private WebElement regAddress;
    @FindBy(id = "regEmail") private WebElement regEmail;
    @FindBy(id = "regTelephone") private WebElement regTelephone;
    @FindBy(id = "corrAddress") private WebElement corrAddress;
    @FindBy(id = "corrEmail") private WebElement corrEmail;
    @FindBy(id = "corrTelephone") private WebElement corrTelephone;
    @FindBy(id = "submitAeConfirmation") private WebElement submitAeConfirmation;
    @FindBy(partialLinkText = "Back to Create AE") private WebElement backToCreateAe;

    public ConfirmNewAeDetailsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public String getBusinessName() {
        return businessName.getText();
    }

    public String getTradingName() {
        return tradingName.getText();
    }

    public String getBusinessType() {
        return businessType.getText();
    }

    public String getCompanyNumber() {
        return companyNumber.getText();
    }

    public String getRegAddress() {
        return regAddress.getText();
    }

    public String getRegEmail() {
        return regEmail.getText();
    }

    public String getRegTelephone() {
        return regTelephone.getText();
    }

    public String getCorrAddress() {
        return corrAddress.getText();
    }

    public String getCorrEmail() {
        return corrEmail.getText();
    }

    public String getCorrTelephone() {
        return corrTelephone.getText();
    }

    public CreateAePage clickBackToCreateAe() {
        backToCreateAe.click();
        return new CreateAePage(driver);
    }

    public AreaOfficerAuthorisedExaminerViewPage createNewAe() {
        submitAeConfirmation.click();
        return new AreaOfficerAuthorisedExaminerViewPage(driver);
    }

    public boolean verifyBusinessDetails() {
        assertThat(getBusinessName(), equalTo(CompanyDetailsHelper.businessName));
        assertThat(getTradingName(), equalTo(CompanyDetailsHelper.tradingName));
        assertThat(getBusinessType(), equalTo(CompanyDetailsHelper.businessType));
        assertThat(getCompanyNumber(), equalTo(CompanyDetailsHelper.companyNumber));
        return true;
    }

    public boolean verifyNewAeDetailsOnConfirmationPage(boolean useBusinessDetailsForCorrespondence) {
        assertThat(verifyBusinessDetails(), is(true));
        assertThat(verifyBusinessAddress(), is(true));
        assertThat(verifyBusinessContact(), is(true));

        if(!useBusinessDetailsForCorrespondence){
            assertThat(verifyCorrespondenceAddress(), is(true));
            assertThat(verifyCorrespondenceContact(), is(true));
        }
        return true;
    }

    public boolean verifyBusinessAddress() {
        assertThat(getRegAddress(),
                equalTo(ContactDetailsHelper.addressLine1 + ", " + ContactDetailsHelper.addressLine2 + ", " + ContactDetailsHelper.addressLine3
                        + ", " + ContactDetailsHelper.city + ", " + ContactDetailsHelper.postCode));
        return true;
    }

    public boolean verifyBusinessContact() {
        assertThat(getRegEmail(), equalTo(ContactDetailsHelper.email));
        assertThat(getRegTelephone(), equalTo(ContactDetailsHelper.phoneNumber));
        return true;
    }

    public boolean verifyCorrespondenceAddress() {
        assertThat(getCorrAddress(),
                equalTo(ContactDetailsHelper.addressLine1 + ", " + ContactDetailsHelper.addressLine2 + ", " + ContactDetailsHelper.addressLine3 + ", "
                        + ContactDetailsHelper.city + ", " + ContactDetailsHelper.postCode));
        return true;
    }

    public boolean verifyCorrespondenceContact() {
        assertThat(getCorrEmail(), equalTo(ContactDetailsHelper.email));
        assertThat(getCorrTelephone(), equalTo(ContactDetailsHelper.phoneNumber));
        return true;
    }
}
