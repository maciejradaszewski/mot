package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.ElementDisplayUtils;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

/**
 * Authorised examiner details page - enforcement version.
 *
 * For trade version see
 * {@link com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage}.
 */
public class AuthorisedExaminerFullDetailsPage extends BasePage {

    private static final String PAGE_TITLE = "FULL DETAILS OF AUTHORISED EXAMINER";

    @FindBy(xpath = ".//h1") private WebElement aeDetails;

    @FindBy(id = "ae-name") private WebElement veAeName;

    @FindBy(id = "ae-auth-status") private WebElement veAeStatus;

    @FindBy(id = "ae-number") private WebElement veAeNumber;

    @FindBy(id = "ae-status-valid-from") private WebElement veAeStatusValidFrom;

    @FindBy(id = "ae-tradename") private WebElement veAeTradeName;

    @FindBy(id = "ae-type") private WebElement veAeType;

    @FindBy(id = "ae-disclosure-indicator") private WebElement veAeDisclosureIndicator;

    @FindBy(id = "value_AE_name") private WebElement aeName;

    @FindBy(id = "value_AE_number") private WebElement aeNumber;

    @FindBy(id = "value_AE_tradingName") private WebElement aeTradeName;

    @FindBy(id = "value_AE_businessType") private WebElement aeType;

    @FindBy(id = "search-again") private WebElement searchAgain;

    @FindBy(id = "ae-company-number") private WebElement aeCompanyNumber;

    @FindBy(id = "cor_address") private WebElement correspondenceAddress;

    @FindBy(id = "cor_email") private WebElement correspondenceEmail;

    @FindBy(id = "cor_phone") private WebElement correspondenceTelephone;

    @FindBy(id = "change-contact-details") private WebElement changeAEDetails;

    @FindBy (id = "return-home") private WebElement returnHomeButton;

    @FindBy(id = "event-history") private WebElement aeEventsHistoryLink;

    public AuthorisedExaminerFullDetailsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public boolean checkSearchAgainLinkExists() {
        return isElementDisplayed(searchAgain);
    }

    public boolean verifyAePageElementsDVSAUsers() {
        WebElement[] elements =
                {veAeName, veAeStatus, veAeNumber, veAeStatusValidFrom, veAeTradeName, veAeType,
                        veAeDisclosureIndicator};
        return ElementDisplayUtils.elementsDisplayed(elements);
    }

    public void clickSearchAgain() {
        searchAgain.click();
    }

    public String getCorrAddress() {
        return correspondenceAddress.getText();
    }

    public String getCorrespondencePhoneNo() {
        return correspondenceTelephone.getText();
    }

    public String getCorrespondenceEmail() {
        return correspondenceEmail.getText();
    }

    public String getAeNameDetails() {return aeDetails.getText();}

    public UpdateAeContactDetailsPage clickUpdateContactDetailsLinkForAe() {
        changeAEDetails.click();
        return new UpdateAeContactDetailsPage(driver);
    }

    public SearchForAePage searchAgainButton() {
        searchAgain.click();
        return new SearchForAePage(driver);
    }

}
