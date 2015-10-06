package uk.gov.dvsa.ui.feature.journey;

import org.testng.annotations.BeforeClass;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.RiskScoreAssessmentParameterSetter;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.*;
import org.joda.time.DateTime;
import org.joda.time.format.DateTimeFormat;
import org.joda.time.format.DateTimeFormatter;
import uk.gov.dvsa.ui.pages.authorisedexaminer.AuthorisedExaminerViewPage;
import uk.gov.dvsa.ui.pages.vts.AssociateASitePage;
import uk.gov.dvsa.ui.pages.vts.DisassociateASitePage;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;

import java.io.IOException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class SiteRagStatusTests extends BaseTest {

    private User areaOffice1User;
    private User tester;
    private User vehicleExaminer;
    private DateTime dt = new DateTime();
    private String day = String.valueOf(dt.getDayOfMonth());
    private String month = String.valueOf(dt.getMonthOfYear());
    private String year = String.valueOf(dt.getYear());
    private AddSiteAssessmentPage addSiteAssessmentPage;
    private SiteAssessmentSummaryPage siteAssessmentSummaryPage;
    private VehicleTestingStationPage vehicleTestingStationPage;
    private SiteAssessmentPage siteAssessmentPage;
    private RiskScoreAssessmentParameterSetter riskScoreAssessmentParameterSetter =
            new RiskScoreAssessmentParameterSetter();
    

    private enum scoreValues {White, Green, Amber, Red}

    @BeforeClass(alwaysRun = true) private void setup() throws IOException {
        areaOffice1User = userData.createAreaOfficeOne("AO1");
        tester = userData.createTester(1);
        vehicleExaminer = userData.createVehicleExaminer("ft-Enf-", false);
    }

    @Test(groups = {"BVT", "Regression"}, description = "VM-11544")
    public void testerChecksRagStatus() throws IOException {

        //Given I am logged in as a tester and I'm on the Home Page
        HomePage homePage = pageNavigator.gotoHomePage(tester);

        //And I click on the VTS
        VehicleTestingStationPage vehicleTestingStationPage = homePage.selectRandomVts();

        //and I verify the risk score is present and has values 0 and White
        assertThat(vehicleTestingStationPage.isRiskAssesmentDisplayed(), is(true));

        //and the Add site assessment link is not present
        assertThat(vehicleTestingStationPage.isAddSiteAssessmentLinkDisplayed(), is(false));
    }

    @Test(groups = {"BVT", "Regression"}, description = "VM-11544, Journey 1")
    public void veChecksCancelButtonAndAddsSiteAssessmentScore() throws IOException {

        String vts = "1";
        riskScoreAssessmentParameterSetter.setUser(vehicleExaminer).setVts(vts)
                .setSomeoneElseEnterSiteAssessment(false).setCancelAddSiteAssessment(true)
                .setUpdateExistingScoreAssessment(false)
                .setClickSubmitToUpdateSiteAssessmentOnSummaryPage(false);

        //I am log in as a Vehicle Examiner and I verify if cancel button works and I also add the Risk Score Assessment
        String scorePoints = fillTheAddRiskAssessmentForm();

        //I verify that the correct information are on the site assessment summary page
        String scoreColour = riskScoreVerification(scorePoints);
        assertThat("Assert Risk Assessment Summary Page contains correct Risk Score Colour",
                siteAssessmentSummaryPage.getRiskAssessmentScore().contains(scoreColour));
        assertThat("Assert Risk Assessment Summary Page contains correct Risk Score Points",
                siteAssessmentSummaryPage.getRiskAssessmentScore().contains(scoreColour));
        assertThat(
                "Assert Risk Assessment Summary Page contains correct DVSA examiner name and surname",
                siteAssessmentSummaryPage.getDvsaExaminerInfo()
                        .contains(vehicleExaminer.getNamesAndSurname()));
        assertThat("Assert Risk Assessment Summary Page contains correct DVSA examiner username",
                siteAssessmentSummaryPage.getDvsaExaminerInfo()
                        .contains(vehicleExaminer.getUsername()));
        assertThat(
                "Assert Risk Assessment Summary Page contains correct AE examiner name and surname",
                siteAssessmentSummaryPage.getAeInfo().contains(vehicleExaminer.getFullName()));
        assertThat("Assert Risk Assessment Summary Page contains correct AE examiner username",
                siteAssessmentSummaryPage.getAeInfo().contains(vehicleExaminer.getUsername()));
        assertThat(
                "Assert Risk Assessment Summary Page contains correct Tester examiner name and surname",
                siteAssessmentSummaryPage.getTesterInfo().contains(tester.getNamesAndSurname()));
        assertThat("Assert Risk Assessment Summary Page contains correct Tester examiner username",
                siteAssessmentSummaryPage.getTesterInfo().contains(tester.getUsername()));
        assertThat("Assert Risk Assessment Summary Page contains correct date",
                siteAssessmentSummaryPage.getAssessmentDate().contains(actualDateDisplayed(true)));

        //And verify that the site assessment risk score was added and message that the site assessment was added is displayed
        assertThat("Assert VTS Page contains correct Risk Score Colour",
                vehicleTestingStationPage.getRiskAssessmentScore().contains(scoreColour));
        assertThat("Assert VTS Page contains correct Risk Score Points",
                vehicleTestingStationPage.getRiskAssessmentScore().contains(scorePoints));
    }

    @Test(groups = {"BVT", "Regression"}, description = "VM-11544, Journey 2")
    public void veChecksEventCreated() throws IOException {

        String vts = "2";
        String siteName = getVtsName(vehicleExaminer, vts);

        riskScoreAssessmentParameterSetter.setUser(vehicleExaminer).setVts(vts)
                .setSomeoneElseEnterSiteAssessment(false).setCancelAddSiteAssessment(false)
                .setUpdateExistingScoreAssessment(false)
                .setClickSubmitToUpdateSiteAssessmentOnSummaryPage(true);

        //Given I am logged as a VE and I add Risk score assessment
        String scorePoints = fillTheAddRiskAssessmentForm();

        //And I go to Event History Page
        EventsHistoryPage eventsHistoryPage =
                vehicleReinspectionWorkflow().gotoEventsHistoryPage(vehicleExaminer, "2");

        //And I verify that the correct event was created
        eventsHistoryPage.fillSearchInput("Update site assessment risk score");
        eventsHistoryPage.clickApplyButton();
        String eventType = "Update site assessment risk score";
        String eventDescription =
                String.format("Site assessment risk score %s for %s has been carried out by %s %s",
                        scorePoints, siteName, vehicleExaminer.getNamesAndSurname(),
                        vehicleExaminer.getUsername());
        assertThat("Assert event history is correct date",
                eventsHistoryPage.getEventDateText().equals(actualDateDisplayed(false)));
        assertThat("Assert event history has correct type",
                eventsHistoryPage.getEventTypeText().equals(eventType));
        assertThat("Assert event history has correct description",
                eventsHistoryPage.getEventDescriptionText().
                        contains(eventDescription));
    }

    @Test(groups = {"BVT", "Regression"}, description = "VM-11544, Journey 3")
    public void veUpdatesExistingSiteAssessmentScore() throws IOException {

        String vts = "3";
        riskScoreAssessmentParameterSetter.setUser(vehicleExaminer).setVts(vts)
                .setSomeoneElseEnterSiteAssessment(false).setCancelAddSiteAssessment(false)
                .setUpdateExistingScoreAssessment(false)
                .setClickSubmitToUpdateSiteAssessmentOnSummaryPage(true);

        //Given I am logged as a VE and I add risk score assessment
        fillTheAddRiskAssessmentForm();

        //And I verify that risk assessment score was added
        VehicleTestingStationPage vehicleTestingStationPage =
                vehicleReinspectionWorkflow().gotoVehicleTestingStationPage(vehicleExaminer, vts);
        assertThat(vehicleTestingStationPage.getRiskAssessmentScore()
                .contains(scoreValues.White.toString()), is(false));

        //And link to change risk score site assessment is displayed
        assertThat("Assert link to update risk score assessment is dispalyed",
                vehicleTestingStationPage.isUpdateSiteAssessmentLinkDisplayed());

        //Then I want to update exiting risk score assessment
        riskScoreAssessmentParameterSetter.setUser(vehicleExaminer).setVts(vts)
                .setSomeoneElseEnterSiteAssessment(false).setCancelAddSiteAssessment(false)
                .setUpdateExistingScoreAssessment(true)
                .setClickSubmitToUpdateSiteAssessmentOnSummaryPage(false);
        String scorePoints = fillTheAddRiskAssessmentForm();

        //And I verify that the information I entered are not changed
        String scoreColour = riskScoreVerification(scorePoints);
        assertThat("Assert Risk Assessment Summary Page contains correct Risk Score Colour",
                siteAssessmentSummaryPage.getRiskAssessmentScore().contains(scoreColour));
        assertThat("Assert Risk Assessment Summary Page contains correct Risk Score Points",
                siteAssessmentSummaryPage.getRiskAssessmentScore().contains(scoreColour));
        assertThat(
                "Assert Risk Assessment Summary Page contains correct DVSA examiner name and surname",
                siteAssessmentSummaryPage.getDvsaExaminerInfo()
                        .contains(vehicleExaminer.getNamesAndSurname()));
        assertThat("Assert Risk Assessment Summary Page contains correct DVSA examiner username",
                siteAssessmentSummaryPage.getDvsaExaminerInfo()
                        .contains(vehicleExaminer.getUsername()));
        assertThat("Assert Risk Assessment Summary Page contains correct AE name and surname",
                siteAssessmentSummaryPage.getAeInfo().contains(vehicleExaminer.getFullName()));
        assertThat("Assert Risk Assessment Summary Page contains correct AE username",
                siteAssessmentSummaryPage.getAeInfo().contains(vehicleExaminer.getUsername()));
        assertThat("Assert Risk Assessment Summary Page contains correct Tester name and surname",
                siteAssessmentSummaryPage.getTesterInfo().contains(tester.getNamesAndSurname()));
        assertThat("Assert Risk Assessment Summary Page contains correct tester username",
                siteAssessmentSummaryPage.getTesterInfo().contains(tester.getUsername()));
        assertThat("Assert Risk Assessment Summary Page contains correct date",
                siteAssessmentSummaryPage.getAssessmentDate().contains(actualDateDisplayed(true)));

        //And verify that the site assessment risk score was updated and message that the site assessment was added is displayed
        VehicleTestingStationPage newVehicleTestingStationPage =
                siteAssessmentSummaryPage.clickSubmitButton();
        assertThat("Assert VTS Page contains correct Risk Score Colour",
                newVehicleTestingStationPage.getRiskAssessmentScore().contains(scoreColour));
        assertThat("Assert VTS Page contains correct Risk Score Points",
                newVehicleTestingStationPage.getRiskAssessmentScore().contains(scorePoints));
    }

    @Test(groups = {"BVT", "Regression"}, description = "VM-11544, Journey 4")
    public void ae1UnlinksVtsFromAeAndLinksToDifferentAe() throws IOException {

        String vts = "4";

        riskScoreAssessmentParameterSetter.setUser(vehicleExaminer).setVts(vts)
                .setSomeoneElseEnterSiteAssessment(false).setCancelAddSiteAssessment(false)
                .setUpdateExistingScoreAssessment(false)
                .setClickSubmitToUpdateSiteAssessmentOnSummaryPage(true);

        //Given I am logged as a VE and I add risk score assessment
        String scorePoints = fillTheAddRiskAssessmentForm();

        //and I am logged in as a AreaOffice1 user and I am on the AE site
        AuthorisedExaminerViewPage authorisedExaminerViewPage =
                pageNavigator.goToAuthorisedExaminerPage(areaOffice1User, "12");

        //And I want to unlink VTS from AE
        DisassociateASitePage disassociateASitePage =
                authorisedExaminerViewPage.clickRemoveSiteLink("1");
        disassociateASitePage.selectStatus("SR");
        disassociateASitePage.clickOnConfirmAndRemoveThisAssociationButton();

        //And I verify that the RAG score is not changed and AE is disassociated
        VehicleTestingStationPage vehicleTestingStationPage =
                vehicleReinspectionWorkflow().gotoVehicleTestingStationPage(vehicleExaminer, vts);
        assertThat("Assert VTS is unlink from AE",
                vehicleTestingStationPage.getAuthorisedExaminer().equals(""));
        String scoreColour = riskScoreVerification(scorePoints);
        assertThat("Assert Risk Score Colour is not changed",
                vehicleTestingStationPage.getRiskAssessmentScore().contains(scoreColour));
        assertThat("Assert Risk Score Points is not changed",
                vehicleTestingStationPage.getRiskAssessmentScore().contains(scorePoints));

        //And I want to link VTS to different AE
        AssociateASitePage associateASitePage =
                pageNavigator.goToAssociateASitePage(areaOffice1User, "2");
        associateASitePage.searchForSiteNumberAndAssociate("V12346");

        //And I verify that the VTS score is set back to RAG values 0 and White and AE is associated
        vehicleTestingStationPage =
                vehicleReinspectionWorkflow().gotoVehicleTestingStationPage(vehicleExaminer, vts);
        assertThat("Assert that VTS is now assigned to AE",
                vehicleTestingStationPage.getAuthorisedExaminer().equals(""), is(false));
        assertThat("Assert Risk Score Colour is set to White",
                vehicleTestingStationPage.getRiskAssessmentScore()
                        .contains(scoreValues.White.toString()));
        assertThat("Assert Risk Score Points are set to 0",
                vehicleTestingStationPage.getRiskAssessmentScore().contains("0"));
    }

    @Test(groups = {"BVT", "Regression"}, description = "VM-11544, Journey 5")
    public void ae1UnlinksVtsFromAeAndLinksToSameAe() throws IOException {

        String vts = "5";
        String ae = "1001";

        riskScoreAssessmentParameterSetter.setUser(vehicleExaminer).setVts(vts)
                .setSomeoneElseEnterSiteAssessment(false).setCancelAddSiteAssessment(false)
                .setUpdateExistingScoreAssessment(false)
                .setClickSubmitToUpdateSiteAssessmentOnSummaryPage(true);

        //Given I am logged as VE and I add risk score assessment
        String scorePoints = fillTheAddRiskAssessmentForm();

        //When I am logged in as a AreaOffice1 user and I am on the AE site
        AuthorisedExaminerViewPage authorisedExaminerViewPage =
                pageNavigator.goToAuthorisedExaminerPage(areaOffice1User, ae);

        //And I want to unlink VTS from AE
        DisassociateASitePage disassociateASitePage =
                authorisedExaminerViewPage.clickRemoveSiteLink("1");
        disassociateASitePage.selectStatus("SR");
        disassociateASitePage.clickOnConfirmAndRemoveThisAssociationButton();

        //And I verify that the RAG score is not changed and AE is disassociated
        VehicleTestingStationPage vehicleTestingStationPage =
                vehicleReinspectionWorkflow().gotoVehicleTestingStationPage(vehicleExaminer, vts);
        assertThat("Assert VTS is unlinked from AE",
                vehicleTestingStationPage.getAuthorisedExaminer().equals(""));
        String scoreColour = riskScoreVerification(scorePoints);
        assertThat("Assert Risk Score Colour is not changed",
                vehicleTestingStationPage.getRiskAssessmentScore().contains(scoreColour));
        assertThat("Assert Risk Score Points are not changed",
                vehicleTestingStationPage.getRiskAssessmentScore().contains(scorePoints));

        //And I want to link VTS to the same AE
        AssociateASitePage associateASitePage =
                pageNavigator.goToAssociateASitePage(areaOffice1User, ae);
        associateASitePage.searchForSiteNumberAndAssociate("V12347");

        //Then I verify that the VTS score is not changed
        vehicleTestingStationPage =
                vehicleReinspectionWorkflow().gotoVehicleTestingStationPage(vehicleExaminer, vts);
        assertThat("Assert that VTS is now assigned to AE",
                vehicleTestingStationPage.getAuthorisedExaminer().equals(""), is(false));
        assertThat("Assert Risk Score Colour is not changed",
                vehicleTestingStationPage.getRiskAssessmentScore().contains(scoreColour));
        assertThat("Assert Risk Score Points is not changed",
                vehicleTestingStationPage.getRiskAssessmentScore().contains(scorePoints));
    }

    private String fillTheAddRiskAssessmentForm() throws IOException {

        User user = riskScoreAssessmentParameterSetter.getUser();
        String vts = riskScoreAssessmentParameterSetter.getVts();
        Boolean someoneElseEnterSiteAssessment =
                riskScoreAssessmentParameterSetter.getSomeoneElseEnterSiteAssessment();
        Boolean cancelAddSiteAssessment =
                riskScoreAssessmentParameterSetter.getCancelAddSiteAssessment();
        Boolean update = riskScoreAssessmentParameterSetter.getUpdateExistingScoreAssessment();
        Boolean clickSubmit = riskScoreAssessmentParameterSetter
                .getClickSubmitToUpdateSiteAssessmentOnSummaryPage();
        String scorePoints = riskScoreAssessmentParameterSetter.getScorePoints();

        vehicleTestingStationPage =
                vehicleReinspectionWorkflow().gotoVehicleTestingStationPage(user, vts);

        //Update risk score assessment or add new one
        if (update) {
            siteAssessmentPage = vehicleTestingStationPage.clickOnUpdateSiteAssessmentLink();
            addSiteAssessmentPage = siteAssessmentPage.clickEnterSiteAssessmentLink();
        } else {
            addSiteAssessmentPage = vehicleTestingStationPage.clickOnAddSiteAssessmentLink();
        }

        //cancel button verification
        if (cancelAddSiteAssessment) {
            vehicleTestingStationPage = addSiteAssessmentPage.clickCancelButton();
            addSiteAssessmentPage = vehicleTestingStationPage.clickOnAddSiteAssessmentLink();
        }

        addSiteAssessmentPage.addSiteAssessmentScore(scorePoints);

        if (someoneElseEnterSiteAssessment) {
            addSiteAssessmentPage.clickYesRadioButton();
            addSiteAssessmentPage.enterDvsaId(areaOffice1User.getUsername());
        }

        addSiteAssessmentPage.enterAeFullName(vehicleExaminer.getFullName());
        addSiteAssessmentPage.enterAeRole(vehicleExaminer.getUsername());
        addSiteAssessmentPage.enterTesterId(tester.getUsername());
        addSiteAssessmentPage.enterDate(day, month, year);
        siteAssessmentSummaryPage = addSiteAssessmentPage.clickContinueButton();

        //Do you want click submit or perform additional data verification (asserts in the test)
        if (clickSubmit) {
            siteAssessmentSummaryPage.clickSubmitButton();
        }
        return scorePoints;
    }

    private String riskScoreVerification(String scoreValue) {

        String score;
        if (Double.parseDouble(scoreValue) == 0) {
            score = scoreValues.White.toString();
        } else if (Double.parseDouble(scoreValue) > 0 && Double.parseDouble(scoreValue) <= 324.10) {
            score = scoreValues.Green.toString();
        } else if (Double.parseDouble(scoreValue) > 324.10
                && Double.parseDouble(scoreValue) <= 459.20) {
            score = scoreValues.Amber.toString();
        } else if (Double.parseDouble(scoreValue) > 459.20
                && Double.parseDouble(scoreValue) <= 999.99) {
            score = scoreValues.Red.toString();
        } else {
            score = "Score value not found";
        }
        return score;
    }

    private String actualDateDisplayed(Boolean monthFullName) {

        DateTime dt = new DateTime();
        String pattern = (monthFullName) ? "d MMMM YYYY" : "d MMM YYYY";
        DateTimeFormatter dateFormatter = DateTimeFormat.forPattern(pattern);
        return dateFormatter.print(dt);
    }

    private String getVtsName(User user, String vts) throws IOException {

        VehicleTestingStationPage vehicleTestingStationPage =
                vehicleReinspectionWorkflow().gotoVehicleTestingStationPage(user, vts);
        String siteName = vehicleTestingStationPage.getVtsNumber() + " " + vehicleTestingStationPage
                .getVtsName();
        return siteName;
    }
}

