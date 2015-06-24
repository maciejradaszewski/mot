package com.dvsa.mot.selenium.priv.frontend.openam;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class OpenAMClaimAccountMotTestReviewPage extends BasePage {

    private static final String PAGE_TITLE = "CLAIM YOUR ACCOUNT\n" +
            "REVIEW ACCOUNT DETAILS\n" +
            "STEP 3 OF 3";

    @FindBy(id = "btSubmitForm") private WebElement claimYourAccountButton;

    @FindBy(id = "go-to-previous-page") private WebElement goBackLink;

    public OpenAMClaimAccountMotTestReviewPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public OpenAMClaimAccountSecurityQuestionsPage clickBackButton() {
        goBackLink.click();
        return new OpenAMClaimAccountSecurityQuestionsPage(driver);
    }

    public OpenAMClaimAccountMotTestPinPage clickClaimYourAccoutButton() {
        claimYourAccountButton.click();
        return new OpenAMClaimAccountMotTestPinPage(driver);
    }

}
