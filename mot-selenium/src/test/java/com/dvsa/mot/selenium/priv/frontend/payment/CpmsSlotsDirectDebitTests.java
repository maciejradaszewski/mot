package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.DirectDebitCancelConfirmationPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.DirectDebitConfirmationPage;

import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class CpmsSlotsDirectDebitTests extends BaseTest {
    
    private Login createAedmAndReturnAedmLogin(String prefix) {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe(prefix);
        Login aedmLogin = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);
        return aedmLogin;
    }

    //TODO return test to Regression suite after stabilising
    @Test(groups = {"CPMS", "SPMS-147"}) public void setUpDirectDebitTest() {
        Login aedmLogin = createAedmAndReturnAedmLogin("DirectDebitSetup");
        DirectDebitConfirmationPage directDebitConfirmationPage = DirectDebitConfirmationPage
                .setupDirectDebitSuccessfully(driver, aedmLogin, Payments.VALID_PAYMENTS, Person.PERSON_1, Address.ADDRESS_ADDRESS1);
        
        assertThat("Verifying Direct Debit setup success message", directDebitConfirmationPage.getStatusMessage(),
                is(Assertion.ASSERTION_DIRECT_DEBIT_SETUP_SUCCESS_MESSAGE.assertion));
        
        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage = directDebitConfirmationPage.clickReturnToAeLink();
        assertThat("Verifying Setup direct debit link is not displayed",
                authorisedExaminerOverviewPage.isSetupDirectDebitLinkVisible(), is(false));
        assertThat("Verifying Manage direct debit link is displayed",
                authorisedExaminerOverviewPage.isManageDirectDebitLinkVisible(), is(true));
    }

    //TODO return test to Regression suite after stabilising
    @Test(groups = {"CPMS", "SPMS-43"}) public void cancelDirectDebitTest() {
        Login aedmLogin = createAedmAndReturnAedmLogin("CancelDirectDebit");
        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage = DirectDebitCancelConfirmationPage
                .loginAndCancelExistingDirectDebit(driver, aedmLogin, Payments.VALID_PAYMENTS, Person.PERSON_1, Address.ADDRESS_ADDRESS1)
                .clickReturnToAeLink();
        
        assertThat("Verifying Setup direct debit link is displayed",
                authorisedExaminerOverviewPage.isSetupDirectDebitLinkVisible(), is(true));
        assertThat("Verifying Manage direct debit link is not displayed",
                authorisedExaminerOverviewPage.isManageDirectDebitLinkVisible(), is(false));
    }

}
