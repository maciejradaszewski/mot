package uk.gov.dvsa.module.userprofile;

import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.ProfilePage;
import uk.gov.dvsa.ui.pages.exception.PageInstanceNotFoundException;
import uk.gov.dvsa.ui.pages.profile.ChangeDateOfBirthPage;
import uk.gov.dvsa.ui.pages.profile.NewUserProfilePage;

public class ChangeDOB {

    private ProfilePage profilePage;
    private ChangeDateOfBirthPage changeDateOfBirthPage;

    private static final String DOB_ERROR_MESSAGE = "must be a valid date of birth";

    public ChangeDOB(ProfilePage profilePage) {
        this.profilePage = profilePage;
    }

    public <T extends Page> T changeDateOfBirth(String day, String month, String year, boolean isValidValues) {
        changeDateOfBirthPage = profilePage.clickChangeDOBLink();

        changeDateOfBirthPage.fillDay(day).fillMonth(month).fillYear(year);
        if (!isValidValues) {
            return (T)changeDateOfBirthPage.clickSubmitButton(ChangeDateOfBirthPage.class);
        }
        return (T)changeDateOfBirthPage.clickSubmitButton(NewUserProfilePage.class);
    }

    public boolean isValidationMessageOnDOBPageDisplayed() {
        if (changeDateOfBirthPage == null) {
            throw new PageInstanceNotFoundException("ChangeDateOfBirthPage wasn't instantiated");
        }
        return changeDateOfBirthPage.getValidationMessage().equals(DOB_ERROR_MESSAGE);
    }
}
