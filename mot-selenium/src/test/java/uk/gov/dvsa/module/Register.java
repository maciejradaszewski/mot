package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.helper.ContactDetailsHelper;
import uk.gov.dvsa.ui.pages.userregistration.*;

import java.io.IOException;

public class Register {
    PageNavigator pageNavigator;
    private boolean accountCreated = false;
    private boolean duplicateEmailAddress = false;

    public Register(PageNavigator pageNavigator)
    {
        this.pageNavigator = pageNavigator;
    }

    public CreateAnAccountPage createAnAccount() throws IOException
    {
        return pageNavigator.goToCreateAnAccountPage();
    }

    public void completeDetailsWithDefaultValues(String email) throws IOException {
        SummaryPage summaryPage = enterDetails(ContactDetailsHelper.generateUniqueName(), ContactDetailsHelper.generateUniqueName(), email);

        AccountCreatedPage createdPage = summaryPage.clickCreateYourAccount();

        accountCreated = createdPage.isAccountCreatedTextDisplayed();
    }

    public void completeDetailsWithCustomValues(String name, String surname, String emailAddress) throws IOException {

        SummaryPage summaryPage = enterDetails(name, surname, emailAddress);

        duplicateEmailAddress = summaryPage.emailAlreadyUsedMessage();

        summaryPage.clickCreateYourAccount();
    }

    private SummaryPage enterDetails(String name, String surname, String emailAddress) throws IOException {DetailsPage detailsPage = createAnAccount().details();
        detailsPage.enterYourDetails(emailAddress, name, surname);

        AddressPage addressPage = detailsPage.clickContinue();
        addressPage.enterAddress();

        SecurityQuestionOnePage questionOnePage = addressPage.clickContinue();
        questionOnePage.chooseQuestionAndAnswer();

        SecurityQuestionTwoPage questionTwoPage = questionOnePage.clickContinue();
        questionTwoPage.chooseQuestionAndAnswer();

        PasswordPage passwordPage = questionTwoPage.clickContinue();
        passwordPage.enterPassword();

        return passwordPage.clickContinue();
    }

    public boolean isAccountCreated()
    {
        return accountCreated;
    }

    public boolean isEmailDuplicated() {
        return duplicateEmailAddress;
    }


}
