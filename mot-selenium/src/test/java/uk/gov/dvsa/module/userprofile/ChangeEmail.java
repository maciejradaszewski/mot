package uk.gov.dvsa.module.userprofile;

import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.ui.pages.ChangeEmailDetailsPage;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.ProfilePage;
import uk.gov.dvsa.ui.pages.exception.PageInstanceNotFoundException;
import uk.gov.dvsa.ui.pages.profile.NewPersonProfilePage;
import uk.gov.dvsa.ui.pages.profile.NewUserProfilePage;

public class ChangeEmail {

    private PageNavigator pageNavigator;
    private ProfilePage profilePage;

    private static final String EMAIL_MUST_BE_VALID_MESSAGE = "must be a valid email address";
    private static final String EMAIL_MUST_MATCH_MESSAGE = "the email addresses you have entered don't match";

    public ChangeEmail(PageNavigator pageNavigator, ProfilePage profilePage) {
        this.pageNavigator = pageNavigator;
        this.profilePage = profilePage;
    }

    public <T extends Page> T changeUserEmail(String email, String emailConfirmation, String value) {
        profilePage.clickChangeEmailLink().fillEmail(email).fillEmailConfirmation(emailConfirmation);
        switch (value) {
            case "INVALID_INPUT":
                return (T) getEmailChangePage().clickSubmitButton(ChangeEmailDetailsPage.class);
            case "YOUR_PROFILE":
                return (T) getEmailChangePage().clickSubmitButton(NewPersonProfilePage.class);
            case "PERSON_PROFILE":
                return (T) getEmailChangePage().clickSubmitButton(NewUserProfilePage.class);
            default:
                throw new PageInstanceNotFoundException("Page instantiation exception");
        }
    }

    public boolean isValidationMessageOnChangeEmailPageDisplayed(String warningMessage) {
        switch (warningMessage) {
            case "EMAIL_VALID":
                return getEmailChangePage().getValidationMessage().equals(EMAIL_MUST_BE_VALID_MESSAGE);
            case "EMAIL_MATCH":
                return getEmailChangePage().getValidationMessage().equals(EMAIL_MUST_MATCH_MESSAGE);
            case "EMAIL_MATCH_AND_VALID":
                return getEmailChangePage().getValidationMessage().contains(EMAIL_MUST_BE_VALID_MESSAGE)
                        && getEmailChangePage().getValidationMessage().contains(EMAIL_MUST_MATCH_MESSAGE);
            default:
                return false;
        }
    }

    private ChangeEmailDetailsPage getEmailChangePage() {
        return new ChangeEmailDetailsPage(pageNavigator.getDriver());
    }
}
