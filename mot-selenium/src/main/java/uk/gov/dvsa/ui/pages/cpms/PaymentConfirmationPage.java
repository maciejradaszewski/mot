package uk.gov.dvsa.ui.pages.cpms;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Payments;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.PaymentDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class PaymentConfirmationPage extends BasePage {

    private static final String PAGE_TITLE = "PAYMENT CONFIRMATION";

    @FindBy(id = "successMessage") private WebElement statusMessage;

    @FindBy(id = "amountOrdered") private WebElement slotsOrdered;

    @FindBy(id = "totalCost") private WebElement totalCost;

    @FindBy(id = "viewDetails") private WebElement viewPurchaseDetailsLink;

    @FindBy(id = "backToExaminer") private WebElement backToAeLink;

    public PaymentConfirmationPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String getStatusMessage() {
        return statusMessage.getText();
    }

    public String getSlotsOrdered() {
        return slotsOrdered.getText();
    }

    public String getTotalCost() {
        return totalCost.getText();
    }

    public PaymentDetailsPage clickViewPurchaseDetailsLink() {
        viewPurchaseDetailsLink.click();
        return new PaymentDetailsPage(driver);
    }

    public AuthorisedExaminerOverviewPage clickBackToAuthorisedExaminerLink() {
        backToAeLink.click();
        return new AuthorisedExaminerOverviewPage(driver);
    }

//    public static PaymentConfirmationPage purchaseSlotsByCardSuccessfully(WebDriver driver, Login login,
//            Payments payments) {
//        return UserDashboardPage.navigateHereFromLoginPage(driver, login).clickFirstAeLink()
//                .clickBuySlotsLink().enterSlotsRequired(payments.slots).clickCalculateCostButton()
//                .clickPayByCardButton().enterCardDetails(payments).clickPayNowButton();
//    }

}
