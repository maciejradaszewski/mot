package uk.gov.dvsa.module.userprofile;

import uk.gov.dvsa.ui.pages.profile.ProfilePage;
import uk.gov.dvsa.ui.pages.profile.qualificationdetails.QualificationDetailsPage;

public class ChangeQualificationDetails {
    private ProfilePage profilePage;
    private QualificationDetailsPage qualificationDetailsPage;

    public ChangeQualificationDetails(ProfilePage profilePage) {
        this.profilePage = profilePage;
    }

    public QualificationDetailsPage changeQualificationDetailsForGroupA(String certificateNumber, String vtsId, String day, String month, String year) {
        goToQualificationDetailsPage();
        qualificationDetailsPage.clickChangeGroupADetails()
            .fillCertificateNumber(certificateNumber)
            .fillVtsId(vtsId)
            .fillDate(day, month, year)
            .submitAndGoToConfirmationPage()
            .submitConfirmChanges();

        return qualificationDetailsPage;
    }

    public QualificationDetailsPage removeQualificationDetailsForGroupB(String certificateNumber) {
        qualificationDetailsPage = profilePage.clickQualificationDetailsLink();

        qualificationDetailsPage
                .clickRemoveGroupBDetails()
                .submitConfirmChanges();

        return qualificationDetailsPage;
    }

    public boolean verifyDetailsChangedForGroupA(String certificateNumber, String newDate) {
        Boolean certificateNumberIsCorrect = qualificationDetailsPage.getCertifiacateGroupANumber().equals(certificateNumber);
        Boolean dateIsCorrect = qualificationDetailsPage.getCertificateGroupADate().contains(newDate);
        Boolean successMessageIsDisplayed = qualificationDetailsPage.validationMessageSuccessIsDisplayed();

        return certificateNumberIsCorrect && dateIsCorrect && successMessageIsDisplayed;
    }

    public QualificationDetailsPage goToQualificationDetailsPage() {
        qualificationDetailsPage = profilePage.clickQualificationDetailsLink();
        return qualificationDetailsPage;
    }

    public int countUserCertificates() {
        int groupA = qualificationDetailsPage.getCertifiacateGroupANumber().isEmpty() ? 0 : 1;
        int groupB = qualificationDetailsPage.getCertifiacateGroupBNumber().isEmpty() ? 0 : 1;
        return groupA + groupB;
    }

    public boolean verifyDetailsAfterRemovedGroupBCertificate() {
        return qualificationDetailsPage.getAualificationStatusForGroupB().equals("Not Applied");
    }
}
