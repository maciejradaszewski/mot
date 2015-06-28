package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.DirectDebitConfirmationPage;

import org.openqa.selenium.WebDriver;
import org.testng.Assert;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class CpmsSlotsDirectDebitTests extends BaseTest {

    private DirectDebitConfirmationPage loginAsAedmAndSetupDirectDebit() {
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("DirectDebitSetup");
        Login aedmLogin = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);
        
        DirectDebitConfirmationPage directDebitConfirmationPage = DirectDebitConfirmationPage
                .setupDirectDebitSuccessfully(driver, aedmLogin, Payments.VALID_PAYMENTS, Person.PERSON_1, Address.ADDRESS_ADDRESS1);
        return directDebitConfirmationPage;
    }

    @Test(groups = {"slice_A", "SPMS-147"}) public void setUpDirectDebitTest() {
        
        DirectDebitConfirmationPage directDebitConfirmationPage = loginAsAedmAndSetupDirectDebit();
        Assert.assertEquals(directDebitConfirmationPage.getStatusMessage(),
                Assertion.ASSERTION_DIRECT_DEBIT_SETUP_SUCCESS_MESSAGE.assertion,
                "Verifying Direct Debit setup success message");
        
        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage = 
                directDebitConfirmationPage.clickReturnToAeLink();
        Assert.assertFalse(authorisedExaminerOverviewPage.isSetupDirectDebitLinkVisible());
        Assert.assertTrue(authorisedExaminerOverviewPage.isManageDirectDebitLinkVisible());
    }

    @Test(groups = {"slice_A", "SPMS-43"}) public void cancelDirectDebitTest() {
        
        DirectDebitConfirmationPage directDebitConfirmationPage = loginAsAedmAndSetupDirectDebit();
        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage = 
                directDebitConfirmationPage.clickReturnToAeLink()
                    .clickManageDirectDebitLink()
                    .clickCancelDirectDebitLink()
                    .clickCancelMandateButton()
                    .clickReturnToAeLink();

        Assert.assertTrue(authorisedExaminerOverviewPage.isSetupDirectDebitLinkVisible());
        Assert.assertFalse(authorisedExaminerOverviewPage.isManageDirectDebitLinkVisible());
    }

}
