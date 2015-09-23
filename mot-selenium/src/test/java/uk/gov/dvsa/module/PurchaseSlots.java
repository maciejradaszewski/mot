package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.ui.pages.cpms.BuyTestSlotsPage;
import uk.gov.dvsa.ui.pages.cpms.CardDetailsPage;
import uk.gov.dvsa.ui.pages.cpms.CardPaymentConfirmationPage;

import java.io.IOException;

public class PurchaseSlots {
    private PageNavigator pageNavigator;
    public CardPaymentConfirmationPage cardPaymentConfirmationPage;
    public BuyTestSlotsPage buyTestSlotsPage;

    public PurchaseSlots(PageNavigator pageNavigator)
    {
        this.pageNavigator = pageNavigator;
    }

    public BuyTestSlotsPage navigateToBuyTestSlotsPageAsAedm(User user, String aeId) throws IOException {
        return pageNavigator.goToAuthorisedExaminerPage(user, aeId).clickBuySlotsLink();
    }

    public BuyTestSlotsPage navigateToBuyTestSlotsPageAsFinanceUser(User user, String aeId) throws IOException {
        return pageNavigator.goToFinanceAuthorisedExaminerViewPage(user, aeId)
                .clickBuySlotsLinkAsFinanceUser().selectCardPaymentTypeAndSubmit();
    }

    public BuyTestSlotsPage goToBuyTestSlotsPage(User user, String aeId) throws IOException {
        return pageNavigator.goToBuyTestSlotsPage(user, aeId);
    }

    public CardPaymentConfirmationPage submitPaymentDetailsWithRequiredSlots(String slots) {
        buyTestSlotsPage = new BuyTestSlotsPage(pageNavigator.getDriver());

        cardPaymentConfirmationPage = buyTestSlotsPage
                .enterSlotsRequiredAndCalculateCost(slots)
                .clickContinueToPay()
                .enterCardDetailsAndSubmit();
        return cardPaymentConfirmationPage;
    }

    public BuyTestSlotsPage submitSlotsWhichExceedsMaximumSlotBalance(String slots) {
        buyTestSlotsPage = new BuyTestSlotsPage(pageNavigator.getDriver());

        buyTestSlotsPage = buyTestSlotsPage.enterExcessSlotsAndCalculateCost(slots);
        return buyTestSlotsPage;
    }

    public CardPaymentConfirmationPage userProcessesCardPaymentSuccessfully (User user, String aeId, String slots) throws IOException {
        cardPaymentConfirmationPage = goToBuyTestSlotsPage(user, aeId)
                .enterSlotsRequiredAndCalculateCost(slots)
                .clickContinueToPay()
                .enterCardDetailsAndSubmit();
        return cardPaymentConfirmationPage;
    }

    public CardDetailsPage navigateToCardDetailsPage(User user, String aeId) throws IOException {
        CardDetailsPage cardDetailsPage = goToBuyTestSlotsPage(user, aeId)
                .enterSlotsRequiredAndCalculateCost("100")
                .clickContinueToPay();
        return cardDetailsPage;
    }

    public BuyTestSlotsPage userCancelsCardPayment() {
        CardDetailsPage cardDetailsPage = new CardDetailsPage(pageNavigator.getDriver());

        buyTestSlotsPage = cardDetailsPage.clickCancelButton();
        return buyTestSlotsPage;
    }
}
