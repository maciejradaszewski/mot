package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.ui.pages.userregistration.*;

import java.io.IOException;

public class Register {
    PageNavigator pageNavigator;
    private boolean accountCreated = false;

    public Register(PageNavigator pageNavigator)
    {
        this.pageNavigator = pageNavigator;
    }

    public CreateAnAccountPage createAnAccount() throws IOException
    {
        return pageNavigator.goToCreateAnAccountPage();
    }

    public void completeDetails() throws IOException {
        CreateAnAccountPage createAnAccountPage = new CreateAnAccountPage(pageNavigator.getDriver());

        DetailsPage detailsPage = createAnAccountPage.details();
        detailsPage.enterYourDetaisl();

        AddressPage addressPage = detailsPage.clickContinue();
        addressPage.enterAddress();

        SecurityQuestionOnePage questionOnePage = addressPage.clickContinue();
        questionOnePage.chooseQuestionAndAnswer();

        SecurityQuestionTwoPage questionTwoPage = questionOnePage.clickContinue();
        questionTwoPage.chooseQuestionAndAnswer();

        PasswordPage passwordPage = questionTwoPage.clickContinue();
        passwordPage.enterPassword();

        SummaryPage summaryPage = passwordPage.clickContinue();

        AccountCreatedPage createdPage = summaryPage.clickCreateYourAccount();

        accountCreated = createdPage.isAccountCreatedTextDisplayed();
    }

    public boolean isAccountCreated()
    {
        return accountCreated;
    }
}
