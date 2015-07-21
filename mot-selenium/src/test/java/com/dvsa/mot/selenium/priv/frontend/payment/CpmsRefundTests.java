package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.ChequePayment;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Payments;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.FinanceUserCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.ChequePaymentOrderConfirmedPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.PaymentConfirmationPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.SlotRefundConfirmationPage;

import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class CpmsRefundTests extends BaseTest {
    
    private Login createFinanceUserReturnFinanceUserLogin() {
        FinanceUserCreationApi financeUserCreationApi = new FinanceUserCreationApi();
        Login financeUserLogin = financeUserCreationApi.createFinanceUser().getLogin();
        return financeUserLogin;
    }

    @Test(groups = {"Regression", "SPMS-255"}) public void financeUserRefundsChequePayment() {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("ChequePayment");
        String aeRef = aeDetails.getAeRef();
        Login financeUserLogin = createFinanceUserReturnFinanceUserLogin();

        ChequePaymentOrderConfirmedPage chequePaymentOrderConfirmedPage = ChequePaymentOrderConfirmedPage
                        .purchaseSlotsByChequeSuccessfully(driver, financeUserLogin, aeRef,
                                ChequePayment.VALID_CHEQUE_PAYMENTS);
        chequePaymentOrderConfirmedPage.clickLogout();

        SlotRefundConfirmationPage slotRefundConfirmationPage = SlotRefundConfirmationPage
                .navigateHereFromLoginAndRefundSlotsSuccessfully(driver, financeUserLogin, aeRef, 10);

        assertThat("Verifying successful refund message",
                slotRefundConfirmationPage.getRefundSuccessMessage(),
                is("The slot refund has been successful"));
    }

    @Test(groups = {"Regression", "SPMS-255"}) public void financeUserRefundsCardPayment() {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("ChequePayment");
        Login aedmLogin = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);
        String aeRef = aeDetails.getAeRef();
        Login financeUserLogin = createFinanceUserReturnFinanceUserLogin();

        PaymentConfirmationPage paymentConfirmationPage = PaymentConfirmationPage
                .purchaseSlotsByCardSuccessfully(driver, aedmLogin, Payments.VALID_PAYMENTS);
        paymentConfirmationPage.clickLogout();

        SlotRefundConfirmationPage slotRefundConfirmationPage = SlotRefundConfirmationPage
                .navigateHereFromLoginAndRefundSlotsSuccessfully(driver, financeUserLogin, aeRef, 10);

        assertThat("Verifying successful refund message",
                slotRefundConfirmationPage.getRefundSuccessMessage(),
                is("The slot refund has been successful"));
    }

}
