package uk.gov.dvsa.ui.pages.userregistration;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.ContactDetailsHelper;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class AddressPage extends Page {

    private static final String PAGE_TITLE = "address";

    @FindBy(id = "address1") private WebElement homeAddressLineOne;

    @FindBy(id = "address2") private WebElement homeAddressLineTwo;

    @FindBy(id = "address3") private WebElement homeAddressLineThree;

    @FindBy(id = "townOrCity") private WebElement townCity;

    @FindBy(id = "postcode") private WebElement postcode;

    @FindBy(id = "continue") private WebElement continueToNextPage;

    public AddressPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

    public SecurityQuestionOnePage clickContinue()
    {
        continueToNextPage.click();
        return new SecurityQuestionOnePage(driver);
    }

    public SecurityQuestionOnePage enterAddressAndSubmitExpectingFirstSecurityQuestionPage(String addressLine1, String addressLine2, String addressLine3, String town,
                                                     String postCode) {
        FormCompletionHelper.enterText(homeAddressLineOne, addressLine1);
        FormCompletionHelper.enterText(homeAddressLineTwo, addressLine2);
        FormCompletionHelper.enterText(homeAddressLineThree, addressLine3);
        FormCompletionHelper.enterText(townCity, town);
        FormCompletionHelper.enterText(postcode, postCode);
        continueToNextPage.click();
        return new SecurityQuestionOnePage(driver);
    }

    public AddressPage enterAddress()
    {
        FormCompletionHelper.enterText(homeAddressLineOne, ContactDetailsHelper.getAddressLine1());
        FormCompletionHelper.enterText(homeAddressLineTwo, ContactDetailsHelper.getAddressLine2());
        FormCompletionHelper.enterText(homeAddressLineThree, ContactDetailsHelper.getAddressLine3());
        FormCompletionHelper.enterText(townCity, ContactDetailsHelper.getCity());
        FormCompletionHelper.enterText(postcode, ContactDetailsHelper.getPostCode());

        return this;
    }
}
