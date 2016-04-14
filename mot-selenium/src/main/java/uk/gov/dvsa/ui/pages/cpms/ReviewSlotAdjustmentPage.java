package uk.gov.dvsa.ui.pages.cpms;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.navigation.MotPageFactory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.authorisedexaminer.FinanceAuthorisedExaminerViewPage;

public class ReviewSlotAdjustmentPage extends Page{

    private static final String PAGE_TITLE = "Review slot adjustment";

    @FindBy(xpath = "//input[@type = 'submit']") private WebElement adjustSlots;

    public ReviewSlotAdjustmentPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public FinanceAuthorisedExaminerViewPage adjustSlots() {
        adjustSlots.click();
        return MotPageFactory.newPage(driver, FinanceAuthorisedExaminerViewPage.class);
    }
}
