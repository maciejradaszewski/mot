package com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages;

import com.dvsa.mot.selenium.datasource.Business;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.Select;

public class CreateAuthorisedExaminerPage extends BasePage {

    public static final String PAGE_TITLE = "Create Authorised Examiner";

    @FindBy(id = "authorisedExaminerReference") private WebElement inputAENumber;

    @FindBy(id = "organisationName") private WebElement inputAEBusinessName;

    @FindBy(id = "tradingAs") private WebElement tradingAs;

    @FindBy(id = "organisationType") private WebElement SelectAEBusinessType;

    @FindBy(id = "registeredCompanyNumber") private WebElement inputCompanyNumber;

    @FindBy(id = "REGCaddressLine1") private WebElement inputBusinessAddress1;

    @FindBy(id = "REGCaddressLine2") private WebElement inputBusinessAddress2;

    @FindBy(id = "REGCaddressLine3") private WebElement inputBusinessAddress3;

    @FindBy(id = "REGCaddressTown") private WebElement inputBusinessTown;

    @FindBy(id = "REGCaddressPostCode") private WebElement inputBusinessPostcode;

    @FindBy(id = "REGCemail") private WebElement inputBusinessEmail;

    @FindBy(id = "REGCemailConfirmation") private WebElement inputBusinessEmailConfirm;

    @FindBy(id = "REGCphoneNumber") private WebElement inputBusinessPhoneNumber;

    @FindBy(id = "CORRaddressLine1") private WebElement inputCorrespondenceAddressLine1;

    @FindBy(id = "CORRaddressLine2") private WebElement inputCorrespondenceAddressLine2;

    @FindBy(id = "CORRaddressLine3") private WebElement inputCorrespondenceAddressLine3;

    @FindBy(id = "CORRaddressTown") private WebElement inputCorrespondenceTown;

    @FindBy(id = "CORRaddressPostCode") private WebElement inputCorrespondencePostCode;

    @FindBy(id = "CORRemail") private WebElement inputCorrespondenceEmail;

    @FindBy(id = "CORRemailConfirmation") private WebElement inputCorrespondenceEmailConfirm;

    @FindBy(id = "CORRphoneNumber") private WebElement inputCorrespondencePhoneNumber;

    @FindBy(id = "correspondenceContactDetailsSame") private WebElement checkBoxForSameAddress;

    @FindBy(id = "submitAeEdit") private WebElement continueToSummary;

    @FindBy(id = "authorised-examiner-home") private WebElement cancelLink;

    @FindBy(id = "REGC[isEmailNotSupply]1") private WebElement noBussEmailOption;

    @FindBy(id = "isCorrDetailsSame0") private WebElement businessDetailsDiff;

    @FindBy(id = "isCorrDetailsSame1") private WebElement businessDetailsSame;

    @FindBy(id = "CORR[isEmailNotSupply]1") private WebElement noCorrEmailOption;

    @FindBy(xpath = ".//*[@id='organisationName']/..//span[@class='validation-message']") private WebElement orgNameMsg;

    @FindBy(xpath = ".//*[@id='companyType']/..//span[@class='validation-message']") private WebElement companyTypeMsg;

    @FindBy(xpath = ".//*[@id='REGCaddressLine1']/..//span[@class='validation-message']") private WebElement addressMsg;

    @FindBy(xpath = ".//*[@id='REGCaddressTown']/..//span[@class='validation-message']") private WebElement townMsg;

    @FindBy(xpath = ".//*[@id='REGCaddressPostCode']/..//span[@class='validation-message']") private WebElement postcodeMsg;

    @FindBy(xpath = ".//*[@id='REGCphoneNumber']/..//span[@class='validation-message']") private WebElement phoneNumberMsg;

    @FindBy(xpath = ".//*[@id='REGCemail']/..//span[@class='validation-message']") private WebElement emailMsg;

    @FindBy(xpath = ".//*[@id='registeredCompanyNumber']/..//span[@class='validation-message']") private WebElement companyNumberMsg;

    @FindBy(xpath = ".//*[@id='REGCemailConfirmation']/..//span[@class='validation-message']") private WebElement confirmationEmailMsg;

    public CreateAuthorisedExaminerPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE.toUpperCase());
    }

    public static CreateAuthorisedExaminerPage navigateHereFromLoginPage(WebDriver driver,
            Login login) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login).clickAeLink();
    }

    public String getOrgNameMsg() {
        return orgNameMsg.getText();
    }

    public String getCompanyTypeMsg() {
        return companyTypeMsg.getText();
    }

    public String getAddressMsg() {
        return addressMsg.getText();
    }

    public String getTownMsg() {
        return townMsg.getText();
    }

    public String getPostCodeMsg() {
        return postcodeMsg.getText();
    }

    public String getPhoneNumberMsg() {
        return phoneNumberMsg.getText();
    }

    public String getEmailMsg() {
        return emailMsg.getText();
    }

    public String getCompanyNumberMsg() {
        return companyNumberMsg.getText();
    }

    public String getSecondaryEmailMsg() {
        return confirmationEmailMsg.getText();
    }

    public CreateAuthorisedExaminerPage enterBusinessAddress1(String busAddress1) {
        inputBusinessAddress1.clear();
        inputBusinessAddress1.sendKeys(busAddress1);
        return this;
    }

    public CreateAuthorisedExaminerPage selectBusinessDetailsSame() {
        businessDetailsSame.click();
        return this;
    }

    public CreateAuthorisedExaminerPage selectBusinessDetailsDiff() {
        businessDetailsDiff.click();
        return this;
    }

    public CreateAuthorisedExaminerPage selectBussNoEmailOption() {
        noBussEmailOption.click();
        return this;
    }

    public boolean isBussEmailOptionSelected() {
        return noBussEmailOption.isSelected();
    }

    public CreateAuthorisedExaminerPage selectCorrNoEmailOption() {
        noCorrEmailOption.click();
        return this;
    }

    public boolean isCorrEmailOptionSelected() {
        return  noCorrEmailOption.isSelected();
    }

    public CreateAuthorisedExaminerPage enterBusinessAddress2(String busAddress2) {
        inputBusinessAddress2.clear();
        inputBusinessAddress2.sendKeys(busAddress2);
        return this;
    }

    public CreateAuthorisedExaminerPage enterBusinessAddress3(String busAddress3) {
        inputBusinessAddress3.clear();
        inputBusinessAddress3.sendKeys(busAddress3);
        return this;
    }

    public CreateAuthorisedExaminerPage enterBusinessPostCode(String postCode) {
        inputBusinessPostcode.clear();
        inputBusinessPostcode.sendKeys(postCode);
        return this;
    }

    public CreateAuthorisedExaminerPage enterBusinessCity(String city) {
        inputBusinessTown.clear();
        inputBusinessTown.sendKeys(city);
        return this;
    }

    public CreateAuthorisedExaminerPage enterBusinessPhoneNumber(String phoneNumber) {
        inputBusinessPhoneNumber.clear();
        inputBusinessPhoneNumber.sendKeys(phoneNumber);
        return this;
    }

    public CreateAuthorisedExaminerPage enterBusinessEmail(String email) {
        inputBusinessEmail.clear();
        inputBusinessEmail.sendKeys(email);
        return this;
    }

    public boolean isBusinessEmailNull() {
        return inputBusinessEmail.getAttribute("value").equals("");
    }

    public CreateAuthorisedExaminerPage enterBusinessConfirmEmail(String email) {
        inputBusinessEmailConfirm.clear();
        inputBusinessEmailConfirm.sendKeys(email);
        return this;
    }

    public boolean isBusinessConfirmEmailNull() {
        return inputBusinessEmailConfirm.getAttribute("value").equals("");
    }

    public CreateAuthorisedExaminerPage enterBusinessName(String businessName) {
        inputAEBusinessName.clear();
        inputAEBusinessName.sendKeys(businessName);
        return this;
    }

    public CreateAuthorisedExaminerPage enterTradingName(String tradingName) {
        tradingAs.clear();
        tradingAs.sendKeys(tradingName);
        return this;
    }

    public CreateAuthorisedExaminerPage selectBusinessType(String businessType) {
        Select dropDownBox = new Select(driver.findElement(By.id("companyType")));
        dropDownBox.selectByVisibleText(businessType);
        return this;
    }

    public CreateAuthorisedExaminerPage enterCompanyNumber(String companyNumber) {
        inputCompanyNumber.clear();
        inputCompanyNumber.sendKeys(companyNumber);
        return this;
    }

    public CreateAuthorisedExaminerPage enterCorrespondenceAddress1(String corresAddress1) {
        inputCorrespondenceAddressLine1.clear();
        inputCorrespondenceAddressLine1.sendKeys(corresAddress1);
        return this;
    }

    public String getCorrespondenceAddress1() {
        return inputCorrespondenceAddressLine1.getAttribute("value");
    }

    public CreateAuthorisedExaminerPage enterCorrespondenceAddress2(String corresAddress2) {
        inputCorrespondenceAddressLine2.clear();
        inputCorrespondenceAddressLine2.sendKeys(corresAddress2);
        return this;
    }

    public CreateAuthorisedExaminerPage enterCorrespondenceAddress3(String corresAddress3) {
        inputCorrespondenceAddressLine3.clear();
        inputCorrespondenceAddressLine3.sendKeys(corresAddress3);
        return this;
    }

    public CreateAuthorisedExaminerPage enterCorrespondenceTown(String corresTown) {
        inputCorrespondenceTown.clear();
        inputCorrespondenceTown.sendKeys(corresTown);
        return this;
    }

    public CreateAuthorisedExaminerPage enterCorrespondencePostCode(String corresPosCode) {
        inputCorrespondencePostCode.clear();
        inputCorrespondencePostCode.sendKeys(corresPosCode);
        return this;
    }

    public CreateAuthorisedExaminerPage enterCorrespondenceEmail(String corresEmail) {
        inputCorrespondenceEmail.clear();
        inputCorrespondenceEmail.sendKeys(corresEmail);
        return this;
    }

    public boolean isCorrEmailNull() {
        return inputCorrespondenceEmail.getAttribute("value").equals("");
    }

    public CreateAuthorisedExaminerPage enterCorrespondenceConfirmEmail(String corresConfirmEmail) {
        inputCorrespondenceEmailConfirm.clear();
        inputCorrespondenceEmailConfirm.sendKeys(corresConfirmEmail);
        return this;
    }

    public boolean isCorrConfirmEmailNull() {
        return inputCorrespondenceEmailConfirm.getAttribute("value").equals("");
    }

    public CreateAuthorisedExaminerPage enterCorrespondencePhoneNumber(String corresPhoneNumber) {
        inputCorrespondencePhoneNumber.clear();
        inputCorrespondencePhoneNumber.sendKeys(corresPhoneNumber);
        return this;
    }

    public AuthorisedExaminerOverviewPage clickContinueToSummaryButton() {
        continueToSummary.click();
        return new AuthorisedExaminerOverviewPage(driver);
    }

    public CreateAuthorisedExaminerPage clickContinueToSummaryButtonExpectingError() {
        continueToSummary.click();
        return new CreateAuthorisedExaminerPage(driver);
    }

    public CreateAuthorisedExaminerPage fillCorresPondenceContactDetails(Business businessInfo) {
        enterCorrespondenceAddress1(businessInfo.busAddress.line1);
        enterCorrespondenceAddress2(businessInfo.busAddress.line2);
        enterCorrespondenceAddress3(businessInfo.busAddress.line3);
        enterCorrespondenceTown(businessInfo.busAddress.town);
        enterCorrespondencePostCode(businessInfo.busAddress.postcode);
        enterCorrespondencePhoneNumber(businessInfo.busDetails.phoneNo);
        fillCorrEmail(businessInfo);
        return new CreateAuthorisedExaminerPage(driver);
    }

    public String getCorrespondenceAddress2() {
        return inputCorrespondenceAddressLine2.getAttribute("value");
    }

    public String getCorrespondenceAddress3() {
        return inputCorrespondenceAddressLine3.getAttribute("value");
    }

    public String getCorrespondenceTown() {
        return inputCorrespondenceTown.getAttribute("value");
    }

    public String getCorrespondencePostCode() {
        return inputCorrespondencePostCode.getAttribute("value");
    }

    public String getCorrespondencePhoneNumber() {
        return inputCorrespondencePhoneNumber.getAttribute("value");
    }

    public String getCorrespondenceEmail() {
        return inputCorrespondenceEmail.getAttribute("value");
    }

    public String getCorrespondenceConfirmEmail() {
        return inputCorrespondenceEmailConfirm.getAttribute("value");
    }

    public CreateAuthorisedExaminerPage fillCorrEmail(Business businessInfo) {
        enterCorrespondenceEmail(businessInfo.busDetails.emailAdd);
        enterCorrespondenceConfirmEmail(businessInfo.busDetails.emailAdd);
        return this;
    }

    public AuthorisedExaminerOverviewPage fillAuthorisedExaminerDetailsAndSubmit(
            Business organisation) {
        return fillCorresPondenceContactDetails(organisation).clickContinueToSummaryButton();
    }

    public CreateAuthorisedExaminerPage fillBusinessDetailsAndBUtypeRegCompany(
            Business businessInfo) {
        enterBusinessName(businessInfo.busDetails.companyName);
        enterTradingName(businessInfo.busDetails.tradingAs);
        selectBusinessType(businessInfo.busDetails.companyType.getName());
        enterCompanyNumber(businessInfo.busDetails.companyNo);
        return this;
    }

    public CreateAuthorisedExaminerPage fillBusinessContactDetails(Business businessInfo) {
        enterBusinessAddress1(businessInfo.busAddress.line1);
        enterBusinessAddress2(businessInfo.busAddress.line2);
        enterBusinessAddress3(businessInfo.busAddress.line3);
        enterBusinessCity(businessInfo.busAddress.town);
        enterBusinessPostCode(businessInfo.busAddress.postcode);
        fillBusinessEmail(businessInfo);
        enterBusinessPhoneNumber(businessInfo.busDetails.phoneNo);
        return this;
    }

    public CreateAuthorisedExaminerPage fillBusinessEmail(Business businessInfo) {
        enterBusinessEmail(businessInfo.busDetails.emailAdd);
        enterBusinessConfirmEmail(businessInfo.busDetails.emailAdd);
        return this;
    }

}


