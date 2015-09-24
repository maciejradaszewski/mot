package uk.gov.dvsa.ui.pages.dvsamanageroles;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.ProfilePage;

import java.util.List;

public class UserSearchResultsPage extends Page {

   private static final String PAGE_TITLE = "User search";

    public UserSearchResultsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    private List<WebElement> getResults() {
        List<WebElement> results = driver.findElements(By.xpath("//*[@data-element='result-details']"));
        return results;
    }

    public UserSearchProfilePage clickUserName(int resultPosition) {
        WebElement result = getResults().get(resultPosition);
        result.findElement(By.cssSelector("[data-element='result-username']")).findElement(By.tagName("a")).click();
        return new UserSearchProfilePage(driver);
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }
}

