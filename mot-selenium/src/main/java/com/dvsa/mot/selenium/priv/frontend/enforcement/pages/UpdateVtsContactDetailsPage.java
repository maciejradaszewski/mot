package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Business;
import com.dvsa.mot.selenium.datasource.BusinessDetails;
import com.dvsa.mot.selenium.datasource.VTSDetails;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class UpdateVtsContactDetailsPage extends BasePage {

    private String PAGE_TITLE = "CHANGE CONTACT DETAILS";



    public UpdateVtsContactDetailsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }


    @FindBy(xpath = ".//h1") private WebElement vtsContactDetailsPageTitle;
    @FindBy(id = "textWarning") private WebElement emailWarningForVts;
    @FindBy(id = "BUSemail") private WebElement vtsEmailId;
    @FindBy(id = "BUSemailConfirmation") private WebElement vtsEmailIdConfirmation;
    @FindBy(id = "BUS[isEmailNotSupply]1") private WebElement noEmailIdForVts;
    @FindBy(id = "BUSphoneNumber") private WebElement vtsPhoneNumber;
    // @FindBy(id = "submitAeEdit") private WebElement submitVtsDetails;
    @FindBy(id = "email") private WebElement updatedEmail;
    @FindBy(id = "phone-number") private WebElement updatedTelephone;
    @FindBy(id="navigation-link-") private WebElement cancelVtsUpdates;
    @FindBy(id = "submitAeEdit") private WebElement submitVtsDetails;



    public String getVtsChangeContactDetailsPageTitle() {
        return vtsContactDetailsPageTitle.getText();
    }



    public UpdateVtsContactDetailsPage confirmEmailAddressForVts() {
        vtsEmailId.sendKeys(BusinessDetails.BUSINESS_DETAILS_1.emailAdd);
        vtsEmailIdConfirmation.sendKeys(BusinessDetails.BUSINESS_DETAILS_1.emailAdd);
        return new UpdateVtsContactDetailsPage(driver);
    }

    public UpdateVtsContactDetailsPage confirmInvalidEmailAddressForVts() {
        vtsEmailId.sendKeys(BusinessDetails.BUSINESS_DETAILS_1.emailAdd);
        vtsEmailIdConfirmation.sendKeys(BusinessDetails.BUSINESS_DETAILS_2.emailAdd);
        return new UpdateVtsContactDetailsPage(driver);
    }

    public UpdateVtsContactDetailsPage enterPhoneNumberForVts(VTSDetails vtsContactDetails) {
        vtsPhoneNumber.clear();
        vtsPhoneNumber.sendKeys(vtsContactDetails.phoneNo);
        return new UpdateVtsContactDetailsPage(driver);
    }

    public UpdateVtsContactDetailsPage noEmailAddressForVts() {
        noEmailIdForVts.click();
        return new UpdateVtsContactDetailsPage(driver);
    }

    public String getEmailWarningTextForVts() {
        return emailWarningForVts.getText();
    }

    public UpdateVtsContactDetailsPage clearChangeContactDetailsFormForVts() {
        vtsEmailId.clear();
        vtsEmailIdConfirmation.clear();
        vtsPhoneNumber.clear();
        return new UpdateVtsContactDetailsPage(driver);
    }

    public UpdateVtsContactDetailsPage changeContactDetailsFormForVTS(VTSDetails vtsContactDetails) {
        vtsEmailId.clear();
        vtsEmailId.sendKeys(vtsContactDetails.emailAdd);
        vtsEmailIdConfirmation.clear();
        vtsEmailIdConfirmation.sendKeys(vtsContactDetails.emailAdd);
        vtsPhoneNumber.clear();
        vtsPhoneNumber.sendKeys(vtsContactDetails.phoneNo);
        return this;
    }

    public String getVTSNewEmail() {return vtsEmailId.getText();}

    public String getVTSNewTelephone() {return vtsPhoneNumber.getText();
    }

    public SiteDetailsPage cancelUpdatesForVts() {
        cancelVtsUpdates.click();
        return new SiteDetailsPage(driver);
    }


    public SiteDetailsPage submitChangesForVts() {
        submitVtsDetails.click();
        return new SiteDetailsPage(driver);
    }

}
