package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;


public class SiteAssessmentSummaryPage extends Page {

    private static final String PAGE_TITLE = "Vehicle Testing Station\nSite assessment";

    @FindBy(id = "risk-assessment-score") private WebElement riskAssessmentScore;

    @FindBy(id = "dvsa-examiner") private WebElement dvsaExaminerInfo;

    @FindBy(id = "ae-representative") private WebElement aeInfo;

    @FindBy(id = "tester") private WebElement testerInfo;

    @FindBy(id = "date-of-assessment") private WebElement assessmentDate;

    @FindBy(id = "submitSiteAssessmentUpdate") private WebElement submitButton;

    @FindBy(id = "navigation-link-") private WebElement cancelLink;


    public SiteAssessmentSummaryPage(MotAppDriver driver) {
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

    public VehicleTestingStationPage clickSubmitButton() {
        submitButton.click();
        return new VehicleTestingStationPage(driver);
    }

    public AddSiteAssessmentPage clickBackButton() {
        cancelLink.click();
        return new AddSiteAssessmentPage(driver);
    }
}
