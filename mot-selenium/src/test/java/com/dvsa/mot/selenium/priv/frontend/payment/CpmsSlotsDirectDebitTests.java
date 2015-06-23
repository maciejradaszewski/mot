package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.DirectDebitConfirmationPage;
import org.testng.Assert;
import org.testng.annotations.Test;

public class CpmsSlotsDirectDebitTests extends BaseTest {

    @Test(groups = {"Regression", "SPMS-147"}) public void setUpDirectDebitTest() {

        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("DirectDebitSetup");
        Login aedmLogin = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);

        DirectDebitConfirmationPage directDebitConfirmationPage =
                AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, aedmLogin)
                        .clickSetupDirectDebitLink()
                        .enterSlotsRequired(Payments.VALID_PAYMENTS.slots).selectCollectionDate("5")
                        .clickContinueButton().clickContinueToGoCardlessButton()
                        .fillDirectDebitForm(Person.PERSON_1, Address.ADDRESS_ADDRESS1)
                        .clickContinueButton().clickConfirmButton();

        Assert.assertEquals(directDebitConfirmationPage.getStatusMessage(),
                Assertion.ASSERTION_DIRECT_DEBIT_SETUP_SUCCESS_MESSAGE.assertion,
                "Verifying Direct Debit setup success message");
        directDebitConfirmationPage.clickReturnToAeLink();
    }

}
