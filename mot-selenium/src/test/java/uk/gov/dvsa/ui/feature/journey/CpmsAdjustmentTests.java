package uk.gov.dvsa.ui.feature.journey;

import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.ui.BaseTest;

import java.io.IOException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class CpmsAdjustmentTests extends BaseTest {

    @Test(groups = {"BVT", "Regression"}, description = "SPMS-255 Finance user refunds slots", dataProvider = "createFinanceUserAndAe" )
    public void userRefundsSlots(User financeUser, AeDetails aeDetails) throws IOException {

        //Given The organisation has a valid payment
        motUI.cpms.purchaseSlots.userProcessesCardPaymentSuccessfully(financeUser, String.valueOf(aeDetails.getId()), "10000");

        //And I am on Slot refund page as Finance user
        motUI.cpms.adjustments.navigateToSlotRefundPageAsFinanceUser(financeUser, String.valueOf(aeDetails.getId()));

        //When I request to refund slots providing a valid reason
        motUI.cpms.adjustments.submitRefundRequestWithValidReason("1000", "User requested");

        //Then Slots refund should be successful
        assertThat(motUI.cpms.adjustments.slotRefundConfirmationPage.isRefundSuccessMessageDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression"}, description = "SPMS-42 Finance User processes Payment reversal", dataProvider = "createAedmAndAe")
    public void userReversesAPayment(User aedm, AeDetails aeDetails) throws IOException {
        User financeUser = userData.createAFinanceUser("Finance", false);

        //Given The organisation has a reversible payment
        motUI.cpms.purchaseSlots.userProcessesCardPaymentSuccessfully(aedm, String.valueOf(aeDetails.getId()), "10000");

        //And Finance user navigates to Reverse Payment Summary page
        motUI.cpms.adjustments.navigateToReversePaymentSummaryPage(financeUser, String.valueOf(aeDetails.getId()));

        //When I request to reverse the payment with a valid reason
        motUI.cpms.adjustments.submitPaymentReverseRequestWithValidReason("Card - Chargeback request made");

        //Then Payment should be reversed successfully
        assertThat(motUI.cpms.adjustments.paymentReversalConfirmationPage.isReversalSuccessfulMessageDisplayed(), is(true));
    }

    @DataProvider(name = "createAedmAndAe")
    public Object[][] createAedmAndAe() throws IOException {
        AeDetails aeDetails = aeData.createAeWithDefaultValues();
        User aedm = userData.createAedm(aeDetails.getId(), "My_AEDM", false);
        return new Object[][]{{aedm, aeDetails}};
    }

    @DataProvider(name = "createFinanceUserAndAe")
    public Object[][] createFinanceUserAndAe() throws IOException {
        AeDetails aeDetails = aeData.createAeWithDefaultValues();
        User financeUser = userData.createAFinanceUser("Finance", false);
        return new Object[][]{{financeUser, aeDetails}};
    }
}
