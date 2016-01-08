package uk.gov.dvsa.ui.feature.journey.cpms;

import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.authorisedexaminer.FinanceAuthorisedExaminerViewPage;
import uk.gov.dvsa.ui.pages.cpms.*;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class CpmsAdjustmentTests extends BaseTest {

    private CardPaymentConfirmationPage financeUserPurchaseSlotsByCard(User user, String path, int aeId, int slots) throws IOException, URISyntaxException {
        CardPaymentConfirmationPage cardPaymentConfirmationPage = pageNavigator
                .goToPageAsAuthorisedExaminer(user, FinanceAuthorisedExaminerViewPage.class, path, aeId)
                .clickBuySlotsLinkAsFinanceUser()
                .selectCardPaymentTypeAndSubmit()
                .enterSlotsRequired(slots)
                .clickCalculateCostButton()
                .clickContinueToPay()
                .enterCardDetails()
                .clickPayNowButton();
        return cardPaymentConfirmationPage;
    }

    @Test(groups = {"BVT", "Regression"}, description = "SPMS-255 Finance user refunds slots", dataProvider = "createFinanceUserAndAe")
    public void userRefundsSlots(User financeUser, AeDetails aeDetails) throws IOException, URISyntaxException {

        //Given I am on Slot refund page as a Finance user with a valid payment
        SlotRefundPage slotRefundPage =
                financeUserPurchaseSlotsByCard(financeUser, FinanceAuthorisedExaminerViewPage.PATH, aeDetails.getId(), 10000)
                .clickBackToAuthorisedExaminerLink()
                .clickRefundSlotsLink();

        //When I request to refund slots providing a valid reason
        SlotRefundConfirmationPage slotRefundConfirmationPage = slotRefundPage
                .enterSlotsToBeRefunded(100)
                .selectRefundReasonAndContinue("User requested")
                .clickRefundSlotsButton();

        //Then Slots refund should be successful
        assertThat("Verifying successful refund message",
                slotRefundConfirmationPage.isRefundSuccessMessageDisplayed(), is(true));
    }

    @Test(enabled = false, groups = {"BVT", "Regression"}, description = "SPMS-42 Finance User processes Payment reversal", dataProvider = "createFinanceUserAndAe")
    public void userReversesAPayment(User financeUser, AeDetails aeDetails) throws IOException, URISyntaxException {

        //Given I am on Reverse payment page of a valid payment
        ReversePaymentSummaryPage reversePaymentSummaryPage =
                financeUserPurchaseSlotsByCard(financeUser, FinanceAuthorisedExaminerViewPage.PATH, aeDetails.getId(), 10000)
                .clickViewPaymentDetailslink()
                .clickReverseThisPaymentButton();

        //When I request to reverse the payment with a valid reason
        PaymentReversalConfirmationPage paymentReversalConfirmationPage = reversePaymentSummaryPage
                .selectReasonAndConfirmPaymentReverse("Card - Chargeback request made");

        //Then Payment should be reversed successfully
        assertThat("Verifying Payment reversal successful message",
                paymentReversalConfirmationPage.isReversalSuccessfulMessageDisplayed(), is(true));

    }

    @DataProvider(name = "createFinanceUserAndAe")
    public Object[][] createFinanceUserAndAe() throws IOException {
        AeDetails aeDetails = aeData.createAeWithDefaultValues();
        User financeUser = userData.createAFinanceUser("Finance", false);
        return new Object[][]{{financeUser, aeDetails}};
    }
}
