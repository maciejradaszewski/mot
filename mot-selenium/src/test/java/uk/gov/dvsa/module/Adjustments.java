package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.ui.pages.cpms.*;

import java.io.IOException;

public class Adjustments {

    private PageNavigator pageNavigator;
    public SlotRefundConfirmationPage slotRefundConfirmationPage;
    public PaymentReversalConfirmationPage paymentReversalConfirmationPage;

    public Adjustments(PageNavigator pageNavigator)
    {
        this.pageNavigator = pageNavigator;
    }

    public SlotRefundPage navigateToSlotRefundPageAsFinanceUser(User user, String aeId) throws IOException {
        return pageNavigator.goToFinanceAuthorisedExaminerViewPage(user, aeId).clickRefundSlotsLink();
    }

    public ReversePaymentSummaryPage navigateToReversePaymentSummaryPage(User user, String aeId) throws IOException {
        return pageNavigator.goToPurchaseHistoryPage(user, aeId)
                .clickFirstTransactionReference()
                .clickReverseThisPaymentButton();
    }

    public SlotRefundConfirmationPage submitRefundRequestWithValidReason(String slots, String refundReason) {
        SlotRefundPage slotRefundPage = new SlotRefundPage(pageNavigator.getDriver());

        slotRefundConfirmationPage = slotRefundPage
                .enterSlotsToBeRefunded(slots)
                .selectRefundReasonAndContinue(refundReason)
                .clickRefundSlotsButton();
        return slotRefundConfirmationPage;
    }

    public PaymentReversalConfirmationPage submitPaymentReverseRequestWithValidReason(String reversalReason) {
        ReversePaymentSummaryPage reversePaymentSummaryPage = new ReversePaymentSummaryPage(pageNavigator.getDriver());

        paymentReversalConfirmationPage = reversePaymentSummaryPage
                .selectReasonAndConfirmPaymentReverse(reversalReason);
        return paymentReversalConfirmationPage;
    }
}
