package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.ChequePayment;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Payments;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.DetailsOfAuthorisedExaminerPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SearchForAePage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.ChequePaymentOrderConfirmedPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.PaymentConfirmationPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.SlotRefundConfirmationPage;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class CpmsRefundTests extends BaseTest {

    @Test(groups = {"Regression", "SPMS-255"}) public void financeUserRefundsChequePayment() {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("ChequePayment");
        String aeRef = aeDetails.getAeRef();

        DetailsOfAuthorisedExaminerPage detailsOfAuthorisedExaminerPage =
                ChequePaymentOrderConfirmedPage
                        .purchaseSlotsByChequeSuccessfully(driver, login.LOGIN_FINANCE_USER, aeRef,
                                ChequePayment.VALID_CHEQUE_PAYMENTS).clickReturnToAeLink();

        SlotRefundConfirmationPage slotRefundConfirmationPage =
                detailsOfAuthorisedExaminerPage.clickRefundsLink().enterSlotsToBeRefunded("10")
                        .clickContinueToStartRefund().clickRefundSlotsButton();

        assertThat("Verifying successful refund message",
                slotRefundConfirmationPage.getRefundSuccessMessage(),
                is("The slot refund has been successful"));
    }

    @Test(groups = {"Regression", "SPMS-255"}) public void financeUserRefundsCardPayment() {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("ChequePayment");
        Login aedmLogin = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);
        String aeRef = aeDetails.getAeRef();

        PaymentConfirmationPage paymentConfirmationPage = PaymentConfirmationPage
                .purchaseSlotsByCardSuccessfully(driver, aedmLogin, Payments.VALID_PAYMENTS);
        paymentConfirmationPage.clickLogout();

        SlotRefundConfirmationPage slotRefundConfirmationPage =
                SearchForAePage.navigateHereFromLoginPage(driver, login.LOGIN_FINANCE_USER)
                        .searchForAeAndSubmit(aeRef).clickRefundsLink().enterSlotsToBeRefunded("10")
                        .clickContinueToStartRefund().clickRefundSlotsButton();

        assertThat("Verifying successful refund message",
                slotRefundConfirmationPage.getRefundSuccessMessage(),
                is("The slot refund has been successful"));
    }

}
