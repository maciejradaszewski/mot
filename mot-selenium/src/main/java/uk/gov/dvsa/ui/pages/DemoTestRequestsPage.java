package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

import java.util.List;

public class DemoTestRequestsPage extends Page {

    public static final String PATH = "/user-admin/demo-test-requests";
    private static final String PAGE_TITLE = "Users that requested a Demo test";


    public DemoTestRequestsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public boolean usersCertificatesDisplayedAmountCorrect(User userWhoSubmittedCertificate, int expectedCertificateAmount){
        List<WebElement> tableRows = driver.findElements(By.cssSelector("#dataTable > tbody > tr"));
        int actualAmount = 0;

        for (WebElement row: tableRows){
            if(row.getText().contains(userWhoSubmittedCertificate.getFirstName())){
                actualAmount++;
            }
        }

        return actualAmount == expectedCertificateAmount;
    }
}
