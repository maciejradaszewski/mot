package uk.gov.dvsa.ui.pages.vts;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

import java.util.List;

public class VtsSearchResultsPage extends Page  {
    public static final String path = "/vehicle-testing-station/result?%s";
    private static final String PAGE_TITLE = "Site search";

    @FindBy(id = "dataTable") private WebElement searchResults;

    public VtsSearchResultsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public VehicleTestingStationPage chooseVts(int resultPosition) {
        getResultsList().get(resultPosition).findElement(By.tagName("a")).click();
        return new VehicleTestingStationPage(driver);
    }

    public String getVtsStatus(int resultPosition) {
        return getResultsList().get(resultPosition)
                .findElements(By.tagName("td"))
                .get(4)
                .findElement(By.tagName("span"))
                .getText();

    }
    private List<WebElement> getResultsList() {
        return searchResults.findElements(By.xpath(".//tr"));
    }
}
