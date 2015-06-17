package com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages;

import com.dvsa.mot.selenium.datasource.Business;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class CreateAuthorisedExaminerPage extends BasePage {

    @FindBy(id = "authorisedExaminerReference")
    private WebElement inputAENumber;

    @FindBy(id = "organisationName")
    private WebElement inputAEBusinessName;

    @FindBy(id = "tradingAs")
    private WebElement tradingAs;

    @FindBy(id = "organisationType")
    private WebElement SelectAEBusinessType;

    @FindBy(id = "registeredCompanyNumber")
    private WebElement inputCompanyNumber;

    @FindBy(id = "addressLine1")
    private WebElement inputBusinessAddress1;

    @FindBy(id = "addressLine2")
    private WebElement inputBusinessAddress2;

    @FindBy(id = "addressLine3")
    private WebElement inputBusinessAddress3;

    @FindBy(id = "town")
    private WebElement inputBusinessTown;

    @FindBy(id = "postcode")
    private WebElement inputBusinessPostcode;

    @FindBy(id = "email")
    private WebElement inputBusinessEmail;

    @FindBy(id = "emailConfirmation")
    private WebElement inputBusinessEmailConfirm;

    @FindBy(id = "phoneNumber")
    private WebElement inputBusinessPhoneNumber;

    @FindBy(id = "faxNumber")
    private WebElement inputBusinessFaxNumber;

    @FindBy(id = "correspondenceAddressLine1")
    private WebElement inputCorrespondenceAddressLine1;

    @FindBy(id = "correspondenceAddressLine2")
    private WebElement inputCorrespondenceAddressLine2;

    @FindBy(id = "correspondenceAddressLine3")
    private WebElement inputCorrespondenceAddressLine3;

    @FindBy(id = "correspondenceTown")
    private WebElement inputCorrespondenceTown;

    @FindBy(id = "correspondencePostcode")
    private WebElement inputCorrespondencePostCode;

    @FindBy(id = "correspondenceEmail")
    private WebElement inputCorrespondenceEmail;

    @FindBy(id = "correspondenceEmailConfirmation")
    private WebElement inputCorrespondenceEmailConfirm;

    @FindBy(id = "correspondencePhoneNumber")
    private WebElement inputCorrespondencePhoneNumber;

    @FindBy(id="correspondenceFaxNumber")
    private WebElement inputCorrespondenceFaxNumber;

    @FindBy(id = "correspondenceContactDetailsSame")
    private WebElement checkBoxForSameAddress;

    @FindBy(id = "save")
    private WebElement buttonSave;

    @FindBy(id = "authorised-examiner-home")
    private WebElement cancelLink;

    public CreateAuthorisedExaminerPage(WebDriver driver) {
        super(driver);
    }

    public static CreateAuthorisedExaminerPage navigateHereFromLoginPage(WebDriver driver, Login login){
        return UserDashboardPage.navigateHereFromLoginPage(driver, login).clickAeLink();
    }

    public void enterCorrespondenceAddress1(String corresAddress1) {
        inputCorrespondenceAddressLine1.clear();
        inputCorrespondenceAddressLine1.sendKeys(corresAddress1);
    }

    public void enterCorrespondenceAddress2(String corresAddress2) {
        inputCorrespondenceAddressLine2.clear();
        inputCorrespondenceAddressLine2.sendKeys(corresAddress2);
    }

    public void enterCorrespondenceAddress3(String corresAddress3) {
        inputCorrespondenceAddressLine3.sendKeys(corresAddress3);
    }

    public void enterCorrespondenceTown(String corresTown) {
        inputCorrespondenceTown.clear();
        inputCorrespondenceTown.sendKeys(corresTown);
    }

    public void enterCorrespondencePostCode(String corresPosCode) {
        inputCorrespondencePostCode.clear();
        inputCorrespondencePostCode.sendKeys(corresPosCode);
    }

    public void clearCorrespondenceContactEmailField() {
        inputCorrespondenceEmail.clear();
    }

    public void enterCorrespondenceEmail(String corresEmail) {
        clearCorrespondenceContactEmailField();
        inputCorrespondenceEmail.sendKeys(corresEmail);
    }

    public void clearCorrespondenceConfirmEmailField() {
        inputCorrespondenceEmailConfirm.clear();
    }

    public void enterCorrespondenceConfirmEmail(String corresConfirmEmail) {
        clearCorrespondenceConfirmEmailField();
        inputCorrespondenceEmailConfirm.sendKeys(corresConfirmEmail);
    }

    public void clearCorrespondencePhoneNumberField() {
        inputCorrespondencePhoneNumber.clear();
    }

    public void enterCorrespondencePhoneNumber(String corresPhoneNumber) {
        clearCorrespondencePhoneNumberField();
        inputCorrespondencePhoneNumber.sendKeys(corresPhoneNumber);
    }

    public void clearCorrespondenceFaxNumberField () {
        inputCorrespondenceFaxNumber.clear();
    }

    public void enterCorrespondenceFaxNumber(String corresFaxNumber) {
        clearCorrespondenceFaxNumberField();
        inputCorrespondenceFaxNumber.sendKeys(corresFaxNumber);
    }

    public AuthorisedExaminerOverviewPage  clickOnSaveButton() {
        buttonSave.click();
        return new AuthorisedExaminerOverviewPage(driver);
    }

    public  CreateAuthorisedExaminerPage fillCorresPondenceContactDetails(Business businessInfo) {
        enterCorrespondenceAddress1(businessInfo.busAddress.line1);
        enterCorrespondenceAddress2(businessInfo.busAddress.line2);
        enterCorrespondenceAddress3(businessInfo.busAddress.line3);
        enterCorrespondenceTown(businessInfo.busAddress.town);
        enterCorrespondencePostCode(businessInfo.busAddress.postcode);
        enterCorrespondenceEmail(businessInfo.busDetails.emailAdd);
        enterCorrespondenceConfirmEmail(businessInfo.busDetails.emailAdd);
        enterCorrespondencePhoneNumber(businessInfo.busDetails.phoneNo);
        enterCorrespondenceFaxNumber(businessInfo.busDetails.faxNo);
        return new CreateAuthorisedExaminerPage(driver);
    }

    public AuthorisedExaminerOverviewPage fillAuthorisedExaminerDetailsAndSubmit (Business organisation) {
        return fillCorresPondenceContactDetails(organisation)
                .clickOnSaveButton();
    }

}


