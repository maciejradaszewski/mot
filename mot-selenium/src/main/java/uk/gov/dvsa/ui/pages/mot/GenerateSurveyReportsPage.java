package uk.gov.dvsa.ui.pages.mot;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.navigation.MotPageFactory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.HomePage;
import uk.gov.dvsa.ui.pages.Page;

public class GenerateSurveyReportsPage extends Page {

    @FindBy(id = "generate-survey-reports-page-title") private WebElement pageTitleElementId;
    @FindBy(id = "back-to-home-link") private WebElement backToHomeLink;
    @FindBy(css = "#satisfaction-survey-data-container .list-bullet li:first-child a") private WebElement firstSurveyDownloadLink;

    private static final String PAGE_TITLE = "Satisfaction survey data";

    public static final String PATH = "/survey/reports";

    public GenerateSurveyReportsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public void clickFirstDownloadLink() {
        firstSurveyDownloadLink.click();
    }

    public HomePage clickBackHomeLink() {
        backToHomeLink.click();
        return MotPageFactory.newPage(driver, HomePage.class);
    }
}
