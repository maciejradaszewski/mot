package uk.gov.dvsa.module;


import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.site.Status;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.ui.pages.vts.*;

import java.io.IOException;

public class Vts {
    private PageNavigator pageNavigator = null;
    private VtsSearchForAVtsPage vtsSearchForAVtsPage;
    private VtsSearchResultsPage vtsSearchResultsPage;
    private ChangeSiteDetailsPage changeSiteDetailsPage;
    private ConfirmSiteDetailsPage confirmSiteDetailsPage;
    private VehicleTestingStationPage vehicleTestingStationPage;

    public Vts(PageNavigator pageNavigator) {
        this.pageNavigator = pageNavigator;
    }

    public void vtsSearchPage(User user) throws IOException {
        vtsSearchForAVtsPage = pageNavigator.goToVtsSearchPage(user);
    }

    public void searchForAVtsByNumber(String vtsNumber) throws IOException {
        vtsSearchResultsPage = vtsSearchForAVtsPage.searchForVts(vtsNumber);
    }

    public String getVtsStatus() {
        return vtsSearchResultsPage.getVtsStatus(1);
    }

    public void vtsPage(User user, String vtsId) throws IOException {
        vehicleTestingStationPage = pageNavigator.goToVtsPage(user, vtsId);
    }

    public String changeVtsStatus(Status status) {
        vehicleTestingStationPage
                .clickOnChangeSiteDetailsLink()
                .changeSiteStatus(status.getText())
                .clickSubmitButton()
                .clickSubmitButton();

        return status.getText();
    }
}
