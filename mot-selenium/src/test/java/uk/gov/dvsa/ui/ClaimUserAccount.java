package uk.gov.dvsa.ui;

import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.helper.RandomDataGenerator;
import uk.gov.dvsa.helper.TestDataHelper;
import uk.gov.dvsa.ui.pages.AccountClaimConfirmationPage;
import uk.gov.dvsa.ui.pages.AccountClaimPage;
import uk.gov.dvsa.ui.pages.AccountClaimReviewPage;
import uk.gov.dvsa.ui.pages.HomePage;

import java.io.IOException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class ClaimUserAccount extends BaseTest {

    @DataProvider(name = "createTester")
    public Object[][] createTesterAndVehicle() throws IOException {
        AeDetails aeDetails = TestDataHelper.createAe();
        Site testSite = TestDataHelper.createSite(aeDetails.getId(), "My_Site");

        return new Object[][]{
                {TestDataHelper.createTester(testSite.getId(), true)},
                {TestDataHelper.createCsco(true)},
                {TestDataHelper.createAedm(true)},
        };
    }

    @Test(groups = {"BVT"}, description = "VM-10319 - Tester, CSCO, AEDM can Claim Account and Set Password",
    dataProvider = "createTester")
    public void claimAsUser(User user) throws IOException {

        //Given I am on the AccountClaim page to my claim my account
        AccountClaimPage accountClaimPage = pageNavigator().gotoAccountClaimPage(user);

        //When I Enter a valid Email Address and a compliant Password
        accountClaimPage.enterEmailAndPassword(
                RandomDataGenerator.generateEmail(7), RandomDataGenerator.generatePassword(8));

        accountClaimPage.clickContinueButton();

        //And I set my security answers
        accountClaimPage.setSecurityQuestionsAndAnswers(
                RandomDataGenerator.generateRandomString(), RandomDataGenerator.generateRandomString());

        AccountClaimReviewPage claimReviewPage = accountClaimPage.clickContinueToAccountReview();

        //And I verify my details entered
        AccountClaimConfirmationPage claimConfirmationPage = claimReviewPage.clickClaimYourAccountButton();

        //Then verify a pin number is displayed
        assertThat(claimConfirmationPage.isPinNumberDisplayed(), is(true));

        //And user is directed to the HomePage
        HomePage homePage = claimConfirmationPage.clickContinueToTheMotTestingService();
        assertThat(homePage.compareUserNameWithSessionUsername(), is(true));
    }
}

