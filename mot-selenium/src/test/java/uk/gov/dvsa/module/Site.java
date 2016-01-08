package uk.gov.dvsa.module;


import org.joda.time.DateTime;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.site.Status;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.ui.pages.AddSiteAssessmentPage;
import uk.gov.dvsa.ui.pages.EventsHistoryPage;
import uk.gov.dvsa.ui.pages.vts.*;

import java.io.IOException;
import java.net.URISyntaxException;

public class Site {
    private PageNavigator pageNavigator;
    private SearchForAVtsPage vtsSearchForAVtsPage;
    private SearchResultsPage vtsSearchResultsPage;
    private VehicleTestingStationPage vehicleTestingStationPage;
    private AddSiteAssessmentPage assessmentPage;
    private EventsHistoryPage eventsHistoryPage;
    private String colourBadgeType;

    public Site(PageNavigator pageNavigator) {
        this.pageNavigator = pageNavigator;
    }

    public void vtsSearchPage(User user) throws IOException {
        vtsSearchForAVtsPage = pageNavigator.goToVtsSearchPage(user);
    }

    public void searchById(String vtsNumber) throws IOException {
        vtsSearchResultsPage = vtsSearchForAVtsPage.searchForVts(SearchResultsPage.class, vtsNumber);
    }

    public String getStatus() {
        return vtsSearchResultsPage.getVtsStatus(1);
    }

    public void gotoPage(User user, String vtsId) throws IOException {
        vehicleTestingStationPage = pageNavigator.goToVtsPage(user, vtsId);
    }

    public String changeStatus(Status status) {
        vehicleTestingStationPage
                .clickOnChangeSiteDetailsLink()
                .changeSiteStatus(status.getText())
                .clickSubmitButton()
                .clickSubmitButton();

        return status.getText();
    }

    public boolean isRiskScoreDisplayed() {
        return vehicleTestingStationPage.isRiskAssesmentDisplayed();
    }

    public void gotoAssessment(User user, String siteId) throws IOException, URISyntaxException {
        String pageUrl = String.format(AddSiteAssessmentPage.path, siteId);
        assessmentPage = pageNavigator.goToPage(user, pageUrl, AddSiteAssessmentPage.class);
    }

    public void gotoEventHistory(User user, String siteId) throws IOException, URISyntaxException {
        String pageUrl = String.format(EventsHistoryPage.PATH, siteId);
        eventsHistoryPage = pageNavigator.goToPage(user, pageUrl, EventsHistoryPage.class);
    }

    public void submitAssessment(AssessmentInfo aInfo, DateTime dateTime) {
        assessmentPage.addSiteAssessmentScore(aInfo.getScore());
        assessmentPage.enterAeFullName(aInfo.getAeRepFullName());
        assessmentPage.enterAeId(aInfo.getAeRepUserId());
        assessmentPage.enterAeRole(aInfo.getAeRepRole());
        assessmentPage.enterTesterId(aInfo.getTesterUserId());

        assessmentPage.enterDate(
                dateTime.dayOfMonth().getAsShortText(),
                String.valueOf(dateTime.getMonthOfYear()),
                dateTime.year().getAsShortText()
        );

        setColorBadgeType(aInfo);

        vehicleTestingStationPage = assessmentPage.clickContinueButton().clickSubmitButton();
    }

    private void setColorBadgeType(AssessmentInfo aInfo) {
        colourBadgeType = aInfo.getColourBadgeType();
    }

    public String getScore() {
        return vehicleTestingStationPage.getRiskAssessmentScore();
    }

    public String getAssessmentColour() {
        return vehicleTestingStationPage.getSiteAssessmentColour(colourBadgeType);
    }

    public Integer getEventHistoryTableSize() {
        eventsHistoryPage.fillSearchInput("Update site assessment risk score").clickApplyButton();
        return eventsHistoryPage.getEventHistoryTableSize();
    }
}
