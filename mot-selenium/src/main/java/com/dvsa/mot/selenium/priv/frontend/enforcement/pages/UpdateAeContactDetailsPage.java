package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Business;
import com.dvsa.mot.selenium.datasource.BusinessDetails;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class UpdateAeContactDetailsPage extends BasePage {


    private String PAGE_TITLE = "CHANGE CONTACT DETAILS";

    public UpdateAeContactDetailsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    @FindBy(id = "change-details") private WebElement updateAeContactDetailsLink;
    @FindBy(xpath = ".//h1") private WebElement aeContactDetailsPageTitle;
    @FindBy(id = "textWarning") private WebElement emailWarningForAe;
    @FindBy(id = "CORRemail") private WebElement emailId;
    @FindBy(id = "CORRemailConfirmation") private WebElement confirmEmail;
    @FindBy(id = "correspondenceEmailSupply1") private WebElement noEmailIdForAe;
    @FindBy(id = "CORRphoneNumber") private WebElement aePhoneNumber;
    @FindBy(id = "isCorrContactDetailsSame1") private WebElement sameAddress;
    @FindBy(id = "isCorrContactDetailsSame0") private WebElement notSameAddress;
    @FindBy(id = "CORRaddressLine1") private WebElement addressLine1;
    @FindBy(id = "CORRaddressLine2") private WebElement addressLine2;
    @FindBy(id = "CORRaddressLine3") private WebElement addressLine3;
    @FindBy(id = "CORRaddressTown") private WebElement town;
    @FindBy(id = "CORRaddressPostCode") private WebElement postcode;
    @FindBy(id = "submitAeEdit") private WebElement submitBtn;
    @FindBy(id = "cor_address") private WebElement correspondenceAddress;

    @FindBy(className = "validation-message") private WebElement differenceInEmailMessage;

    @FindBy(id = "navigation-link-") private WebElement cancelAeUpdates;




    public UpdateAeContactDetailsPage confirmEmailAddressForAe(BusinessDetails businessDetails ) {


        emailId.sendKeys(businessDetails.emailAdd);
        confirmEmail.sendKeys(businessDetails.emailAdd);
        return new UpdateAeContactDetailsPage(driver);
    }


    public UpdateAeContactDetailsPage wrongEmailAddressForAe(BusinessDetails businessDetails1,BusinessDetails businessDetails2) {
        emailId.sendKeys(businessDetails1.emailAdd);
        confirmEmail.sendKeys(businessDetails2.emailAdd);
        return new UpdateAeContactDetailsPage(driver);
    }

    public UpdateAeContactDetailsPage enterPhoneNumberForAe(BusinessDetails businessDetails) {
        aePhoneNumber.sendKeys(businessDetails.phoneNo);
        return new UpdateAeContactDetailsPage(driver);
    }

    public AuthorisedExaminerOverviewPage submitChangesForAe() {
        submitBtn.click();
        return new AuthorisedExaminerOverviewPage(driver);
    }

    public AuthorisedExaminerOverviewPage cancelAeUpdatesForAe() {
        cancelAeUpdates.click();
        return new AuthorisedExaminerOverviewPage(driver);
    }

    public UpdateAeContactDetailsPage enterAddressForAe(Business business) {
        addressLine1.sendKeys(business.busAddress.line1);
        addressLine2.sendKeys(business.busAddress.line2);
        addressLine3.sendKeys(business.busAddress.line3);
        town.sendKeys(business.busAddress.postcode);
        postcode.sendKeys(business.busAddress.postcode);

        return new UpdateAeContactDetailsPage(driver);

    }

    public String getEmailWarningTextForAe() {
        return emailWarningForAe.getText();
    }

    public String getValidationMsg() {
        return differenceInEmailMessage.getText();
    }

    public UpdateAeContactDetailsPage changeContactDetailsFormForAe(BusinessDetails businessContactDetails, Business business) {
        emailId.clear();
        emailId.sendKeys(businessContactDetails.emailAdd);
        confirmEmail.clear();
        confirmEmail.sendKeys(businessContactDetails.emailAdd);
        aePhoneNumber.clear();
        aePhoneNumber.sendKeys(businessContactDetails.phoneNo);
        notSameAddress.click();
        addressLine1.clear();
        addressLine1.sendKeys(business.busAddress.getLine1());
        addressLine2.clear();
        addressLine2.sendKeys(business.busAddress.getLine2());
        addressLine3.clear();
        addressLine3.sendKeys(business.busAddress.getLine3());
        town.clear();
        town.sendKeys(business.busAddress.getTown());
        postcode.clear();
        postcode.sendKeys(business.busAddress.getPostcode());
        return new UpdateAeContactDetailsPage(driver);
    }
    public AuthorisedExaminerFullDetailsPage submitAeChanges() {
        submitBtn.click();
        return new AuthorisedExaminerFullDetailsPage(driver);
    }
}


