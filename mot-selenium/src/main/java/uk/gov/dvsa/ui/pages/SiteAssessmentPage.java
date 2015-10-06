package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;


public class SiteAssessmentPage extends Page {

    private static final String PAGE_TITLE = "Vehicle Testing Station\nSite assessment";

    @FindBy(id = "risk-assessment-score") private WebElement riskAssessmentScore;

    @FindBy(id = "dvsa-examiner") private WebElement dvsaExaminerInfo;

    @FindBy(id = "ae-representative") private WebElement aeInfo;

    @FindBy(id = "tester") private WebElement testerInfo;

    @FindBy(id = "date-of-assessment") private WebElement assessmentDate;

    @FindBy(linkText = "Enter site assessment") private WebElement enterSiteAssessmentLink;

    @FindBy(linkText = "Return to VTS overview") private WebElement returnToVtsOverviewLink;


    public SiteAssessmentPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public String getRiskAssessmentScore() {
        return riskAssessmentScore.getText();
    }

    public String getDvsaExaminerInfo() {
        return dvsaExaminerInfo.getText();
    }

    public String getAeInfo() {
        return aeInfo.getText();
    }

    public String getTesterInfo() {
        return testerInfo.getText();
    }

    public String getAssessmentDate() {
        return assessmentDate.getText();
    }

    public AddSiteAssessmentPage clickEnterSiteAssessmentLink() {
        enterSiteAssessmentLink.click();
        return new AddSiteAssessmentPage(driver);
    }

    public VehicleTestingStationPage clickReturnToVtsOverviewLink() {
        returnToVtsOverviewLink.click();
        return new VehicleTestingStationPage(driver);
    }
}
