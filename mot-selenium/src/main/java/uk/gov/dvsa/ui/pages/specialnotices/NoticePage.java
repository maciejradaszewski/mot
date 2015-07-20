package uk.gov.dvsa.ui.pages.specialnotices;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.service.SpecialNoticeService;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

import java.io.IOException;

public abstract class NoticePage extends Page{

    private static final String PAGE_TITLE = "Special Notices";

    protected SpecialNoticeService specialNoticeService = new SpecialNoticeService();

    private String specialNoticeXpath = "//h4[contains(text(),'%s')]";

    @FindBy (id = "special-notices") private WebElement specialNotices;

    public NoticePage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public boolean checkSpecialNoticeListForTitle(String specialNoticeTitle) {
        return specialNotices.getText().contains(specialNoticeTitle);
    }

    private WebElement getSpecialNoticeElement(String title) {
        return driver.findElement(By.xpath(String.format(specialNoticeXpath, title)));
    }

    protected int getSpecialNoticeIdByTitle(String title) {
        WebElement specialNotice = getSpecialNoticeElement(title);
        String idArray [] = specialNotice.getAttribute("id").split("-");
        return Integer.parseInt(idArray[idArray.length - 1]);
    }

    protected boolean broadcastSpecialNotice(String username, String specialNoticeTitle) throws
        IOException {
        return specialNoticeService.broadcastSpecialNotice(getSpecialNoticeIdByTitle(specialNoticeTitle),
            username, false);
    }
}
