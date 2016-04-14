package uk.gov.dvsa.ui.pages.authorisedexaminer;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import uk.gov.dvsa.domain.navigation.MotPageFactory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.ui.pages.cpms.ChoosePaymentTypePage;
import uk.gov.dvsa.ui.pages.cpms.SlotAdjustmentPage;
import uk.gov.dvsa.ui.pages.cpms.SlotRefundPage;

public class FinanceAuthorisedExaminerViewPage extends AuthorisedExaminerViewPage {
    private static String PAGE_TITLE = "Authorised Examiner";
    
    @FindBy(id = "slots-refund") private WebElement refundSlotsLink;
    @FindBy(id = "slot-adjustment") private WebElement slotAdjustment;

    public FinanceAuthorisedExaminerViewPage(MotAppDriver driver) {
        super(driver, PAGE_TITLE );
        selfVerify();
    }
    
    public ChoosePaymentTypePage clickBuySlotsLinkAsFinanceUser() {
        clickBuySlotsLink();
        return new ChoosePaymentTypePage(driver);
    }
    
    public SlotRefundPage clickRefundSlotsLink() {
        refundSlotsLink.click();
        return new SlotRefundPage(driver);
    }

    public SlotAdjustmentPage clickSlotAdjustment() {
        slotAdjustment.click();
        return MotPageFactory.newPage(driver, SlotAdjustmentPage.class);
    }
}
